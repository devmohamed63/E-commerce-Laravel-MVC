<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Carbon\Carbon;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset OTP request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We could not find a user with that email address.']);
        }

        // Generate 4-digit OTP
        $otp = PasswordResetOtp::generateOtp();
        $expiresAt = Carbon::now()->addMinutes(10); // OTP expires in 10 minutes

        // Mark old OTPs as used
        PasswordResetOtp::where('email', $request->email)
            ->where('used', false)
            ->update(['used' => true]);

        // Create new OTP
        PasswordResetOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'used' => false,
        ]);

        // Send OTP via email
        try {
            Mail::raw("Your password reset OTP is: {$otp}\n\nThis OTP will expire in 10 minutes.", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Password Reset OTP - Locally');
            });

            return redirect()->route('password.verify-otp')
                ->with('email', $request->email)
                ->with('status', 'We have sent a 4-digit OTP to your email address.');
        } catch (\Exception $e) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Failed to send OTP. Please try again later.']);
        }
    }
}
