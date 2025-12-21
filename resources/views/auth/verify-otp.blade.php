@extends('layouts.app')

@section('content')
    <div style="max-width: 480px; width: 100%; margin: 2rem auto; padding: 0 1rem;">
        <div class="card" style="padding: 1.5rem;">
            <div style="text-align: center; margin-bottom: 2rem;">
                <div class="brand" style="justify-content: center; margin-bottom: 1rem;">
                    <img src="/images/locally-logo.png" alt="Locally" style="width: 120px; height: 120px; object-fit: contain; background: #0f172a; padding: 12px; border-radius: 20px;">
                </div>
                <h1 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 0.5rem;">Verify Code</h1>
                <p style="color: var(--text-dim); font-size: 0.9rem;">Enter the 4-digit code we sent to</p>
                <p style="color: var(--text-main); font-size: 0.9rem; font-weight: 500; margin-top: 0.25rem;">{{ $email }}</p>
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

            <form method="POST" action="{{ route('password.verify-otp.store') }}" style="display: grid; gap: 1.25rem;" id="otpForm">
                @csrf
                <input type="hidden" name="email" value="{{ $email }}">

                <!-- OTP Input -->
                <div>
                    <label for="otp" style="display: block; font-size: 0.9rem; font-weight: 500; margin-bottom: 0.5rem; color: var(--text-main); text-align: center;">
                        Enter 4-Digit OTP
                    </label>
                    <input 
                        id="otp" 
                        type="text" 
                        name="otp" 
                        required 
                        autofocus
                        maxlength="4"
                        pattern="[0-9]{4}"
                        class="input-full"
                        style="padding-left: 0.75rem; text-align: center; font-size: 1.5rem; letter-spacing: 0.5rem; font-weight: 600;"
                        placeholder="0000"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 4)"
                    />
                </div>

                <!-- Submit Button -->
                <button type="submit" class="add-cart-btn" style="width: 100%; margin-top: 0.5rem;">
                    Verify OTP
                </button>
            </form>

            <!-- Resend OTP Link -->
            <div style="text-align: center; margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-col);">
                <p style="color: var(--text-dim); font-size: 0.9rem; margin-bottom: 0.75rem;">
                    Didn't receive the code?
                </p>
                <form method="POST" action="{{ route('password.email') }}" style="display: inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <button type="submit" class="chip-btn" style="background: transparent; border: 1px solid var(--border-col); cursor: pointer;">
                        Resend OTP
                    </button>
                </form>
            </div>

            <!-- Back to Login Link -->
            <div style="text-align: center; margin-top: 1rem;">
                <a href="{{ route('login') }}" style="color: var(--text-dim); font-size: 0.85rem; text-decoration: none;">
                    Back to Login
                </a>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit when 4 digits are entered
        document.getElementById('otp').addEventListener('input', function(e) {
            if (this.value.length === 4) {
                // Small delay to show the last digit
                setTimeout(() => {
                    document.getElementById('otpForm').submit();
                }, 300);
            }
        });
    </script>
@endsection

