{{--
  Partial form guru.
  Dipakai di: admin/guru/create.blade.php dan admin/guru/edit.blade.php
  Variabel yang diharapkan: $guru (optional, untuk edit), $errors
--}}

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px,1fr)); gap:1.25rem;">

    <div class="form-group">
        <label for="name" class="form-label">Nama lengkap</label>
        <input
            id="name" type="text" name="name" class="form-input"
            value="{{ old('name', $guru->name ?? '') }}"
            placeholder="Nama lengkap guru"
            required
        >
        @error('name') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input
            id="email" type="email" name="email" class="form-input"
            value="{{ old('email', $guru->email ?? '') }}"
            placeholder="email@sekolah.sch.id"
            required
        >
        @error('email') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    @if (!isset($guru))
        {{-- Hanya tampil saat create --}}
        <div class="form-group">
            <label for="password" class="form-label">Kata sandi awal</label>
            <input
                id="password" type="password" name="password" class="form-input"
                placeholder="Min. 8 karakter"
                required
            >
            <span style="font-size:.78rem; color:var(--muted); margin-top:.25rem; display:block;">
                Guru wajib ganti kata sandi saat pertama login.
            </span>
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    @else
        {{-- Saat edit: password opsional --}}
        <div class="form-group">
            <label for="password" class="form-label">Kata sandi baru <span style="color:var(--muted); font-weight:400;">(opsional)</span></label>
            <input
                id="password" type="password" name="password" class="form-input"
                placeholder="Kosongkan jika tidak ingin mengubah"
            >
            <span style="font-size:.78rem; color:var(--muted); margin-top:.25rem; display:block;">
                Jika diisi, guru akan diminta ganti kata sandi saat login berikutnya.
            </span>
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label for="is_active" class="form-label">Status akun</label>
            <select id="is_active" name="is_active" class="form-select" required>
                <option value="1" {{ old('is_active', $guru->is_active ? '1' : '0') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ old('is_active', $guru->is_active ? '1' : '0') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            @error('is_active') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    @endif

</div>
