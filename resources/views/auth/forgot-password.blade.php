@extends('layouts.app')

@section('content')
    <div style="max-width: 480px; width: 100%; margin: 2rem auto; padding: 0 1rem;">
        <div class="card" style="padding: 1.5rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="brand" style="justify-content: center; margin-bottom: 1rem;">
                    <div class="brand-icon">👜</div>
                    <span style="font-size: 1.5rem;">L&N Store</span>
                </div>
                <h1 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;">Reset Password</h1>
                <p style="color: var(--text-dim); font-size: 0.9rem;">Enter your email address and we'll send you a verification code</p>
            </div>

            @if(session('status'))
                <div style="background: #10b981; color: white; padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.9rem;">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #ef4444; color: white; padding: 0.75rem 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; font-size: 0.9rem;">
                    <ul style="margin: 0; padding-left: 1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" style="display: grid; gap: 1.25rem;">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text-main);">
                        Email Address
                    </label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        class="input-full"
                        style="padding-left: 0.75rem;"
                        placeholder="your.email@example.com"
                    />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="add-cart-btn" style="width: 100%; margin-top: 0.5rem;">
                    Send Verification Code
                </button>
            </form>

            <!-- Back to Login Link -->
            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-col);">
                <a href="{{ route('login') }}" class="chip-btn" style="text-decoration: none; display: inline-block;">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
@endsection
