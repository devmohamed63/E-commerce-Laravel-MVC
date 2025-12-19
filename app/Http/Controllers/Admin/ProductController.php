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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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

        // Handle image uploads
        if ($request->hasFile('images')) {
            $sortOrder = 0;
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $sortOrder . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('images/products', $filename, 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => 'storage/' . $path,
                    'is_primary' => $sortOrder === 0,
                    'sort_order' => $sortOrder,
                ]);
                $sortOrder++;
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
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
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

        // Handle new image uploads
        if ($request->hasFile('images')) {
            $maxSort = $product->images()->max('sort_order') ?? -1;
            $sortOrder = $maxSort + 1;
            $hasPrimary = $product->images()->where('is_primary', true)->exists();
            
            foreach ($request->file('images') as $image) {
                $filename = time() . '_' . $sortOrder . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('images/products', $filename, 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => 'storage/' . $path,
                    'is_primary' => !$hasPrimary && $sortOrder === $maxSort + 1,
                    'sort_order' => $sortOrder,
                ]);
                $sortOrder++;
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
