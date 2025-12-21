<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'payment_method' => 'required|in:cod,card',
        ]);

        // Get cart from request (localStorage) or session (fallback)
        $cart = [];
        
        if ($request->has('cart')) {
            // Cart from localStorage (sent as JSON string)
            $cartData = $request->input('cart');
            if (is_string($cartData)) {
                $cart = json_decode($cartData, true) ?? [];
            } else {
                $cart = $cartData;
            }
        } else {
            // Fallback to session cart
            $cart = session()->get('cart', []);
        }

        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ], 400);
        }

        // Validate products exist and calculate totals using DATABASE PRICES (security!)
        $subtotal = 0;
        $validatedCart = [];
        
        foreach ($cart as $item) {
            $productId = $item['product_id'] ?? null;
            if (!$productId) continue;
            
            // Get product from database
            $product = Product::with('images')->find($productId);
            if (!$product) continue;
            
            // SECURITY: Always use price from database, never trust client-side price!
            $price = $product->base_price;
            $quantity = max(1, intval($item['quantity'] ?? 1)); // Ensure positive integer
            
            // Get product image from database
            $primaryImage = $product->images->first();
            $imagePath = $primaryImage ? $primaryImage->path : '';
            
            $validatedCart[] = [
                'product_id' => $product->id,
                'name' => $product->name_en,
                'image' => $imagePath,
                'size' => $item['size'] ?? 'One Size',
                'color' => $item['color'] ?? null,
                'price' => $price,
                'quantity' => $quantity,
            ];
            
            $subtotal += $price * $quantity;
        }

        if (empty($validatedCart)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid products in cart.',
            ], 400);
        }

        $shipping = $subtotal > 100 ? 0 : 100;
        $total = $subtotal + $shipping;

        $order = Order::create([
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'payment_method' => $request->payment_method,
            'status' => 'pending',
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ]);

        foreach ($validatedCart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'product_name' => $item['name'],
                'product_image' => $item['image'],
                'size' => $item['size'],
                'color' => $item['color'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Clear session cart (if it was used)
        session()->forget('cart');

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'message' => 'Order placed successfully!',
            'redirect' => route('orders.show', $order),
        ]);
    }
}
