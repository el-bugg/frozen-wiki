<x-guest-layout>
    <div class="mb-4 text-center text-md-start">
        <h2 class="text-white fw-bold font-cinzel mb-1 frozen-title">WELCOME BACK</h2>
        <p class="text-secondary small">The battlefield misses your presence.</p>
    </div>

    <x-auth-session-status class="mb-4 text-success small" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-4">
            <label class="auth-label">Email Codex</label>
            <input type="email" name="email" class="form-control auth-input rounded" 
                   value="{{ old('email') }}" required autofocus placeholder="Enter your email">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />
        </div>

        <div class="mb-4">
            <label class="auth-label">Secret Key</label>
            <input type="password" name="password" class="form-control auth-input rounded" 
                   required autocomplete="current-password" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger small" />
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input bg-dark border-secondary" name="remember">
                <label for="remember_me" class="form-check-label text-secondary small">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a class="text-info small text-decoration-none" href="{{ route('password.request') }}">
                    Forgot Password?
                </a>
            @endif
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-neon-auth py-3 rounded">
                Enter Realm
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-secondary small mb-0">New to the realm?</p>
            <a href="{{ route('register') }}" class="text-info fw-bold small text-decoration-none" style="letter-spacing: 1px;">
                CREATE ACCOUNT
            </a>
        </div>
    </form>
</x-guest-layout>