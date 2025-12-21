<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'images', 'variants'])
            ->where('is_active', true);

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_en', 'like', "%{$search}%")
                  ->orWhere('name_ar', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category && $request->category !== 'All') {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('name', $request->category);
            });
        }

        // Gender filter
        if ($request->has('gender') && $request->gender && $request->gender !== 'all') {
            $query->where('gender', $request->gender);
        }

        // Size filter
        if ($request->has('size') && $request->size) {
            $sizes = is_array($request->size) ? $request->size : [$request->size];
            $query->whereHas('variants', function ($q) use ($sizes) {
                $q->whereIn('size', $sizes);
            });
        }

        // Tag filter (for "New Arrivals")
        if ($request->has('tag') && $request->tag) {
            $query->where('tags', 'like', "%{$request->tag}%");
        }

        // Sort
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price-asc':
                $query->orderBy('base_price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('base_price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(9);
        $categories = Category::all();

        $favorites = [];
        if (auth()->check()) {
            $favorites = auth()->user()->favorites()->pluck('product_id')->toArray();
        }

        return view('home', compact('products', 'categories', 'favorites'));
    }
}
