<x-guest-layout>
<div class="halftone halftone-login" style="
    position:fixed; inset:0;
    background: var(--ink);
    display:flex; align-items:center; justify-content:center;
    padding:1rem;
">
    <!-- Kartu login -->
    <div style="
        width:100%; max-width:400px;
        background:var(--ink-2);
        border:1px solid var(--line-22);
        border-radius:20px;
        padding:2.5rem 2rem;
        position:relative; z-index:2;
    ">
        <!-- Pill label -->
        <div style="display:flex; justify-content:center; margin-bottom:1.75rem;">
            <span class="pill-role" style="font-size:.7rem; letter-spacing:.05em;">
                <span class="pill-role-dot"></span>
                Sistem administrasi sekolah
            </span>
        </div>

        <!-- Wordmark serif -->
        <h1 class="t-display" style="
            text-align:center;
            margin-bottom:.35rem;
            font-size:clamp(1.8rem,5vw,2.6rem);
        ">
            {{ config('app.name', 'NOVARA') }}
        </h1>
        <p class="t-muted" style="text-align:center; font-size:.85rem; margin-bottom:2rem;">
            Masuk untuk melanjutkan
        </p>

        <!-- Flash status -->
        @if (session('status'))
            <div class="flash flash-success" style="margin-bottom:1.25rem;">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="form-group" style="margin-bottom:1rem;">
                <label for="email" class="form-label">Alamat email</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    class="form-input"
                    value="{{ old('email') }}"
                    required autofocus autocomplete="username"
                    placeholder="nama@sekolah.sch.id"
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group" style="margin-bottom:1.25rem;">
                <label for="password" class="form-label">Kata sandi</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    class="form-input"
                    required autocomplete="current-password"
                    placeholder="••••••••"
                >
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <!-- Ingat saya -->
            <div style="margin-bottom:1.75rem;">
                <label class="form-check">
                    <input type="checkbox" name="remember" id="remember_me">
                    <span style="font-size:.875rem; color:var(--muted);">Ingat saya</span>
                </label>
            </div>

            <!-- Tombol masuk -->
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:.7rem;">
                Masuk
            </button>
        </form>

        <!-- Catatan -->
        <p style="
            text-align:center;
            font-size:.75rem;
            color:var(--muted);
            margin-top:1.5rem;
            line-height:1.5;
        ">
            Akun dibuat oleh admin.<br>
            Hubungi tata usaha jika belum memiliki akun.
        </p>
    </div>
</div>
</x-guest-layout>
