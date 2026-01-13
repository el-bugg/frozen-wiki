<x-layout>
    <x-slot:title>Settings - Frozen Wiki</x-slot>

    <div class="container py-5 fade-in-anim">
        <h2 class="frozen-text text-center mb-5" data-text="PROFILE SETTINGS">PROFILE SETTINGS</h2>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="p-4 bg-black border border-secondary rounded mb-4">
                    <h4 class="text-white font-cinzel mb-4">Avatar & Information</h4>

                    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data"
                        class="mt-6 space-y-6">
                        @csrf
                        @method('patch')

                        <div class="d-flex align-items-center gap-4 mb-4">
                            <img src="{{ $user->profile_url }}"
                                class="rounded-circle border border-info object-fit-cover" width="80"
                                height="80">

                            <div class="flex-grow-1">
                                <label class="form-label text-secondary small text-uppercase">Change Avatar</label>
                                <input type="file" name="avatar"
                                    class="form-control bg-dark border-secondary text-white form-control-sm">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-ice">Hero Name (Display Name)</label>
                            <input type="text" name="name"
                                class="form-control bg-dark border-secondary text-white"
                                value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-ice">Email Codex</label>
                            <input type="email" name="email"
                                class="form-control bg-dark border-secondary text-white"
                                value="{{ old('email', $user->email) }}" required>
                        </div>

                        <button type="submit" class="btn btn-info fw-bold">SAVE CHANGES</button>

                        @if (session('status') === 'profile-updated')
                            <p class="text-success small mt-2">Saved.</p>
                        @endif
                    </form>
                </div>

                <div class="p-4 bg-black border border-secondary rounded mb-4">
                    <h4 class="text-white font-cinzel mb-4">Update Secret Key</h4>
                    <form method="post" action="{{ route('password.update') }}">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label class="form-label text-secondary">Current Password</label>
                            <input type="password" name="current_password"
                                class="form-control bg-dark border-secondary text-white">
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-secondary">New Password</label>
                            <input type="password" name="password"
                                class="form-control bg-dark border-secondary text-white">
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-secondary">Confirm New Password</label>
                            <input type="password" name="password_confirmation"
                                class="form-control bg-dark border-secondary text-white">
                        </div>

                        <button type="submit" class="btn btn-outline-info">UPDATE PASSWORD</button>
                        @if (session('status') === 'password-updated')
                            <p class="text-success small mt-2">Password Updated.</p>
                        @endif
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-layout>
