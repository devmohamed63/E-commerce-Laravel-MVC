<?php

namespace App\Http\Controllers;

use App\Models\Newsletter;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
        ]);

        $email = strtolower(trim($request->email));

        // Check if already subscribed
        $existing = Newsletter::where('email', $email)->first();

        if ($existing) {
            if ($existing->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This email is already subscribed!',
                ], 200);
            } else {
                // Reactivate subscription
                $existing->update(['is_active' => true]);
                return response()->json([
                    'success' => true,
                    'message' => 'Welcome back! You have been resubscribed.',
                ]);
            }
        }

        // Create new subscription
        Newsletter::create([
            'email' => $email,
            'is_active' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for subscribing!',
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $newsletter = Newsletter::where('email', strtolower(trim($request->email)))->first();

        if ($newsletter) {
            $newsletter->update(['is_active' => false]);
        }

        return response()->json([
            'success' => true,
            'message' => 'You have been unsubscribed.',
        ]);
    }
}
