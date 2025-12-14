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
}
