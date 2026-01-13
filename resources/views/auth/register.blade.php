<x-guest-layout>
    <div class="mb-4 text-center text-md-start">
        <h2 class="text-white fw-bold font-cinzel mb-1 frozen-title">JOIN THE RANKS</h2>
        <p class="text-secondary small">Begin your legacy in the Frozen Realm.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="auth-label">Hero Name</label>
            <input type="text" name="name" class="form-control auth-input rounded" 
                   value="{{ old('name') }}" required autofocus placeholder="Your Display Name">
            <x-input-error :messages="$errors->get('name')" class="mt-1 text-danger small" />
        </div>

        <div class="mb-3">
            <label class="auth-label">Email Codex</label>
            <input type="email" name="email" class="form-control auth-input rounded" 
                   value="{{ old('email') }}" required placeholder="email@example.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-danger small" />
        </div>

        <div class="mb-3">
            <label class="auth-label">Secret Key</label>
            <input type="password" name="password" class="form-control auth-input rounded" 
                   required autocomplete="new-password" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-danger small" />
        </div>

        <div class="mb-4">
            <label class="auth-label">Confirm Secret Key</label>
            <input type="password" name="password_confirmation" class="form-control auth-input rounded" 
                   required placeholder="••••••••">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-danger small" />
        </div>

        <div class="d-grid">
            <button type="submit" class="btn btn-neon-auth py-3 rounded">
                Register Account
            </button>
        </div>

        <div class="text-center mt-4">
            <p class="text-secondary small mb-0">Already have an account?</p>
            <a href="{{ route('login') }}" class="text-info fw-bold small text-decoration-none" style="letter-spacing: 1px;">
                LOGIN HERE
            </a>
        </div>
    </form>
</x-guest-layout>