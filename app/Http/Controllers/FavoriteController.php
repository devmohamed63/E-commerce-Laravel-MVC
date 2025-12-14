<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = auth()->user()->favorites()->with('product.images')->get();
        return view('favorites.index', compact('favorites'));
    }

    public function store(Request $request, Product $product)
    {
        $redirectUrl = $request->get('redirect', url()->previous());

        $favorite = Favorite::firstOrCreate([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'favorited' => true]);
        }

        return redirect($redirectUrl)->with('success', 'Added to favorites!');
    }

    public function destroy(Request $request, Product $product)
    {
        $redirectUrl = $request->get('redirect', url()->previous());

        Favorite::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'favorited' => false]);
        }

        return redirect($redirectUrl)->with('success', 'Removed from favorites!');
    }
}
