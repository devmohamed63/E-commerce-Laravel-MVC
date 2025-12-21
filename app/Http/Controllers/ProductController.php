<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        $product->load(['category', 'images', 'variants']);

        $favorited = false;
        if (auth()->check()) {
            $favorited = auth()->user()->favorites()->where('product_id', $product->id)->exists();
        }

        if (request()->wantsJson() || request()->ajax()) {
            return view('product.show', ['product' => $product, 'favorited' => $favorited, 'standalone' => false]);
        }

        return view('product.show', ['product' => $product, 'favorited' => $favorited, 'standalone' => true]);
    }

    /**
     * Return product details as JSON for the cart
     */
    public function apiShow(Product $product)
    {
        $product->load(['images', 'variants']);
        
        $primaryImage = $product->images->first();
        $imagePath = $primaryImage ? asset($primaryImage->path) : asset('images/placeholder.svg');
        
        return response()->json([
            'id' => $product->id,
            'name' => $product->name_en,
            'name_en' => $product->name_en,
            'name_ar' => $product->name_ar,
            'base_price' => $product->base_price,
            'price' => $product->base_price,
            'image' => $imagePath,
            'variants' => $product->variants->map(function ($variant) {
                return [
                    'size' => $variant->size,
                    'color' => $variant->color,
                    'price' => $variant->price,
                    'stock' => $variant->stock,
                ];
            }),
        ]);
    }
}

