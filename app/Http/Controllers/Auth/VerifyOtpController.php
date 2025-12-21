<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordResetOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VerifyOtpController extends Controller
{
    /**
     * Display the OTP verification view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('email') ?? $request->get('email');
        
        if (!$email) {
            return redirect()->route('password.request');
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    /**
     * Handle OTP verification.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'otp' => ['required', 'string', 'size:4'],
        ]);

        $otpRecord = PasswordResetOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$otpRecord || !$otpRecord->isValid()) {
            return back()->withInput($request->only('email'))
                ->withErrors(['otp' => 'Invalid or expired OTP. Please request a new one.']);
        }

        // Mark OTP as used
        $otpRecord->markAsUsed();

        // Store email in session for password reset
        $request->session()->put('password_reset_email', $request->email);

        return redirect()->route('password.reset')
            ->with('status', 'OTP verified successfully. Please enter your new password.');
    }
}
