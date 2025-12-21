@extends('layouts.app')

@section('content')
    <div style="max-width: 480px; width: 100%; margin: 2rem auto; padding: 0 1rem;">
        <div class="card" style="padding: 1.5rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="brand" style="justify-content: center; margin-bottom: 1rem;">
                    <img src="/images/locally-logo.png" alt="Locally" style="width: 120px; height: 120px; object-fit: contain; background: #0f172a; padding: 12px; border-radius: 20px;">
                </div>
                <h1 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;">New Password</h1>
                <p style="color: var(--text-dim); font-size: 0.9rem;">Choose a strong password for your account</p>
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

            <form method="POST" action="{{ route('password.store') }}" style="display: grid; gap: 1.25rem;">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- Password -->
                <div>
                    <label for="password" style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text-main);">
                        New Password
                    </label>
                    <div style="position: relative;">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autofocus
                            class="input-full"
                            style="padding-left: 0.75rem; padding-right: 2.5rem;"
                            placeholder="Enter your new password"
                        />
                        <button 
                            type="button" 
                            onclick="togglePassword('password')"
                            class="password-toggle-btn"
                            title="Show/Hide Password"
                        >
                            <span id="passwordToggle">Show</span>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text-main);">
                        Confirm Password
                    </label>
                    <div style="position: relative;">
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required
                            class="input-full"
                            style="padding-left: 0.75rem; padding-right: 2.5rem;"
                            placeholder="Confirm your new password"
                        />
                        <button 
                            type="button" 
                            onclick="togglePassword('password_confirmation')"
                            class="password-toggle-btn"
                            title="Show/Hide Password"
                        >
                            <span id="password_confirmationToggle">Show</span>
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="add-cart-btn" style="width: 100%; margin-top: 0.5rem;">
                    Reset Password
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

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(inputId + 'Toggle');
            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = 'Hide';
            } else {
                input.type = 'password';
                toggle.textContent = 'Show';
            }
        }
    </script>
@endsection
