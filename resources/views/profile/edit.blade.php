<x-app-layout>
    <x-slot name="title">Profil saya</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Profil saya</h1>
    </x-slot>

    @if (session('must_change_password') || ($user->must_change_password ?? false))
        <div style="padding:0 0 1rem;">
            <div class="flash flash-error">
                Harap ganti kata sandi Anda sebelum melanjutkan.
            </div>
        </div>
    @endif

    <div style="display:grid; gap:1.5rem; max-width:640px;">

        {{-- ── Informasi profil ────────────────────────────────── --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                <h2 style="font-size:1rem; font-weight:600; color:var(--cream);">Informasi profil</h2>
                <p style="font-size:.82rem; color:var(--muted); margin-top:.25rem;">
                    Perbarui nama dan alamat email akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" style="padding:1.5rem;">
                @csrf
                @method('PATCH')

                <div style="display:grid; gap:1rem;">
                    <div class="form-group">
                        <label for="name" class="form-label">Nama lengkap</label>
                        <input
                            id="name" type="text" name="name" class="form-input"
                            value="{{ old('name', $user->name) }}"
                            required autocomplete="name"
                        >
                        @error('name') <span class="form-error">{{ $message }}</span> @enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input
                            id="email" type="email" name="email" class="form-input"
                            value="{{ old('email', $user->email) }}"
                            required autocomplete="username"
                        >
                        @error('email') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr class="divider">
                <div style="display:flex; gap:.75rem; align-items:center; justify-content:flex-end;">
                    @if (session('status') === 'profile-updated')
                        <span style="font-size:.82rem; color:var(--muted);">Tersimpan.</span>
                    @endif
                    <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                </div>
            </form>
        </div>

        {{-- ── Ganti kata sandi ────────────────────────────────── --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                <h2 style="font-size:1rem; font-weight:600; color:var(--cream);">Ganti kata sandi</h2>
                <p style="font-size:.82rem; color:var(--muted); margin-top:.25rem;">
                    Gunakan kata sandi yang panjang dan unik untuk keamanan akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.update') }}" style="padding:1.5rem;">
                @csrf
                @method('PUT')

                <div style="display:grid; gap:1rem;">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Kata sandi saat ini</label>
                        <input
                            id="current_password" type="password"
                            name="current_password" class="form-input"
                            autocomplete="current-password"
                        >
                        @error('current_password', 'updatePassword')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Kata sandi baru</label>
                        <input
                            id="password" type="password"
                            name="password" class="form-input"
                            autocomplete="new-password"
                        >
                        @error('password', 'updatePassword')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Konfirmasi kata sandi baru</label>
                        <input
                            id="password_confirmation" type="password"
                            name="password_confirmation" class="form-input"
                            autocomplete="new-password"
                        >
                        @error('password_confirmation', 'updatePassword')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <hr class="divider">
                <div style="display:flex; gap:.75rem; align-items:center; justify-content:flex-end;">
                    @if (session('status') === 'password-updated')
                        <span style="font-size:.82rem; color:var(--muted);">Kata sandi diperbarui.</span>
                    @endif
                    <button type="submit" class="btn btn-primary">Ganti kata sandi</button>
                </div>
            </form>
        </div>

    </div>

</x-app-layout>
