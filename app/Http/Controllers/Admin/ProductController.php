<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->latest()->paginate(20);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'gender' => 'required|in:women,men,unisex',
            'tags' => 'nullable|string',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'string|in:XS,S,M,L,XL,XXL',
            'colors' => 'nullable|array',
            'colors.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|array',
            'images.*.*' => 'image|mimes:jpeg,png,jpg,gif,webp',
        ]);

        $product = Product::create($request->only([
            'category_id', 'name_en', 'name_ar', 'base_price', 'old_price',
            'rating', 'gender', 'tags'
        ]));

        // Create variants for each size/color combination
        $sizes = $request->sizes ?? [];
        $colors = $request->colors ?? [];

        if (empty($colors)) {
            // If no colors, create variants with sizes only
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => null,
                    'stock_quantity' => 100,
                ]);
            }
        } else {
            // Create variants for each size/color combination
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color,
                        'stock_quantity' => 100,
                    ]);
                }
            }
        }

        // Handle image uploads (now grouped by color)
        $uploadedImages = $request->file('images');
        if ($uploadedImages && is_array($uploadedImages)) {
            $sortOrder = 0;
            $primaryIndex = $request->input('primary_image_index'); // e.g., "Black_0" or "general_2"
            $createdImages = [];
            
            foreach ($uploadedImages as $color => $images) {
                // Skip if not an array of files
                if (!is_array($images)) continue;
                
                // If color is 'general', set it to null
                $imageColor = ($color === 'general') ? null : $color;
                $colorId = str_replace(' ', '_', $color);
                
                foreach ($images as $index => $image) {
                    $filename = time() . '_' . $sortOrder . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $image->getClientOriginalName());
                    $path = $image->storeAs('images/products', $filename, 'public');
                    
                    // Check if this image should be primary
                    $isPrimary = ($primaryIndex === "{$colorId}_{$index}");
                    
                    $createdImages[] = ProductImage::create([
                        'product_id' => $product->id,
                        'path' => 'storage/' . $path,
                        'color' => $imageColor,
                        'is_primary' => $isPrimary,
                        'sort_order' => $sortOrder,
                    ]);
                    $sortOrder++;
                }
            }
            
            // If no primary was set, make the first image primary
            if (!$primaryIndex && count($createdImages) > 0) {
                $createdImages[0]->update(['is_primary' => true]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $product->load('category', 'images', 'variants');
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name_en' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'old_price' => 'nullable|numeric|min:0',
            'rating' => 'nullable|numeric|min:0|max:5',
            'gender' => 'required|in:women,men,unisex',
            'tags' => 'nullable|string',
            'sizes' => 'required|array|min:1',
            'sizes.*' => 'string|in:XS,S,M,L,XL,XXL',
            'colors' => 'nullable|array',
            'colors.*' => 'string',
            'images' => 'nullable|array',
            'images.*' => 'nullable|array',
            'images.*.*' => 'image|mimes:jpeg,png,jpg,gif,webp',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:product_images,id',
        ]);

        $product->update($request->only([
            'category_id', 'name_en', 'name_ar', 'base_price', 'old_price',
            'rating', 'gender', 'tags'
        ]));

        // Delete old variants and create new ones
        $product->variants()->delete();
        
        $sizes = $request->sizes ?? [];
        $colors = $request->colors ?? [];

        if (empty($colors)) {
            foreach ($sizes as $size) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'color' => null,
                    'stock_quantity' => 100,
                ]);
            }
        } else {
            foreach ($sizes as $size) {
                foreach ($colors as $color) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $color,
                        'stock_quantity' => 100,
                    ]);
                }
            }
        }

        // Handle primary image selection
        if ($request->has('primary_image')) {
            // Reset all images to non-primary
            $product->images()->update(['is_primary' => false]);
            // Set selected image as primary
            ProductImage::where('id', $request->primary_image)
                ->where('product_id', $product->id)
                ->update(['is_primary' => true]);
        }

        // Delete selected images
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image && $image->product_id === $product->id) {
                    // Delete file from storage
                    $filePath = str_replace('storage/', '', $image->path);
                    \Storage::disk('public')->delete($filePath);
                    $image->delete();
                }
            }
        }

        // Handle new image uploads (grouped by color)
        $uploadedImages = $request->file('images');
        if ($uploadedImages && is_array($uploadedImages)) {
            $maxSort = $product->images()->max('sort_order') ?? -1;
            $sortOrder = $maxSort + 1;
            $hasPrimary = $product->images()->where('is_primary', true)->exists();
            $isFirstImage = !$hasPrimary;
            
            foreach ($uploadedImages as $color => $images) {
                // Skip if not an array of files
                if (!is_array($images)) continue;
                
                $imageColor = ($color === 'general') ? null : $color;
                
                foreach ($images as $image) {
                    $filename = time() . '_' . $sortOrder . '_' . preg_replace('/[^a-zA-Z0-9_.-]/', '_', $image->getClientOriginalName());
                    $path = $image->storeAs('images/products', $filename, 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'path' => 'storage/' . $path,
                        'color' => $imageColor,
                        'is_primary' => $isFirstImage,
                        'sort_order' => $sortOrder,
                    ]);
                    $sortOrder++;
                    $isFirstImage = false;
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }
}
