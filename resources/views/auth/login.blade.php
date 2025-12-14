@extends('layouts.app')

@section('content')
    <div style="max-width: 480px; width: 100%; margin: 2rem auto; padding: 0 1rem;">
        <div class="card" style="padding: 1.5rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="brand" style="justify-content: center; margin-bottom: 1rem;">
                    <div class="brand-icon">👜</div>
                    <span style="font-size: 1.5rem;">L&N Store</span>
                </div>
                <h1 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;">Sign In</h1>
                <p style="color: var(--text-dim); font-size: 0.9rem;">Enter your credentials to access your account</p>
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

            <form method="POST" action="{{ route('login') }}" style="display: grid; gap: 1.25rem;">
                @csrf
                @if(request('redirect'))
                    <input type="hidden" name="redirect" value="{{ request('redirect') }}">
                @endif

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
                        autocomplete="username"
                        class="input-full"
                        style="padding-left: 0.75rem;"
                        placeholder="your.email@example.com"
                    />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text-main);">
                        Password
                    </label>
                    <div style="position: relative;">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="current-password"
                            class="input-full"
                            style="padding-left: 0.75rem; padding-right: 2.5rem;"
                            placeholder="Enter your password"
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

                <!-- Remember Me & Forgot Password -->
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem;">
                    <label for="remember_me" style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember"
                            style="width: 16px; height: 16px; cursor: pointer;"
                        />
                        <span style="color: var(--text-dim);">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: var(--bg-accent); text-decoration: none;">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" class="add-cart-btn" style="width: 100%; margin-top: 0.5rem;">
                    Log In
                </button>
            </form>

            <!-- Register Link -->
            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-col);">
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-bottom: 0.75rem;">
                    Don't have an account?
                </p>
                <a href="{{ route('register') }}" class="chip-btn" style="text-decoration: none; display: inline-block;">
                    Create Account
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
