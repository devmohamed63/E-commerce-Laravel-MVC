<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        $items = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            $product = Product::with('images')->find($item['product_id']);
            if ($product) {
                // Get image path - handle both stored path and asset path
                $imagePath = $item['image'] ?? '';
                if ($imagePath && !str_starts_with($imagePath, 'http') && !str_starts_with($imagePath, '/')) {
                    $imagePath = asset($imagePath);
                } elseif ($imagePath && str_starts_with($imagePath, '/')) {
                    $imagePath = asset(ltrim($imagePath, '/'));
                } elseif (empty($imagePath)) {
                    $primaryImage = $product->images->first();
                    $imagePath = $primaryImage ? asset($primaryImage->path) : asset('images/placeholder.png');
                }
                
                $itemData = [
                    'key' => $key,
                    'product_id' => $item['product_id'],
                    'name' => $item['name'] ?? $product->name_en,
                    'image' => $imagePath,
                    'price' => $item['price'] ?? $product->base_price,
                    'size' => $item['size'] ?? 'One Size',
                    'color' => $item['color'] ?? null,
                    'quantity' => $item['quantity'] ?? 1,
                    'total' => ($item['price'] ?? $product->base_price) * ($item['quantity'] ?? 1),
                ];
                $subtotal += $itemData['total'];
                $items[] = $itemData;
            }
        }

        $shipping = $subtotal > 100 ? 0 : 100;
        $total = $subtotal + $shipping;

        return response()->json([
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'count' => count($items),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|string',
            'color' => 'nullable|string',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $product = Product::with('images')->findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        $itemKey = $request->product_id . '_' . $request->size . '_' . ($request->color ?? 'none');

        if (isset($cart[$itemKey])) {
            $cart[$itemKey]['quantity'] += $request->quantity ?? 1;
        } else {
            $primaryImage = $product->images->first();
            $cart[$itemKey] = [
                'product_id' => $product->id,
                'name' => $product->name_en,
                'image' => $primaryImage ? $primaryImage->path : '',
                'price' => $product->base_price,
                'size' => $request->size,
                'color' => $request->color,
                'quantity' => $request->quantity ?? 1,
            ];
        }

        session()->put('cart', $cart);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => count($cart),
                'message' => 'Product added to cart',
            ]);
        }

        $redirectUrl = $request->get('redirect', route('home'));
        return redirect($redirectUrl)->with('success', 'Product added to cart!');
    }

    public function update(Request $request, $key)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = session()->get('cart', []);
        $key = urldecode($key);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
        }

        return $this->index();
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);
        $key = urldecode($key);
        unset($cart[$key]);
        session()->put('cart', $cart);

        return $this->index();
    }
}
