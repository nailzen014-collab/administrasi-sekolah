{{--
  Partial form siswa.
  Dipakai di: admin/students/create.blade.php dan admin/students/edit.blade.php
  Variabel yang diharapkan: $student (optional, untuk edit), $kelasList, $errors
--}}

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px,1fr)); gap:1.25rem;">

    <div class="form-group">
        <label for="name" class="form-label">Nama lengkap</label>
        <input
            id="name" type="text" name="name" class="form-input"
            value="{{ old('name', $student?->user->name ?? '') }}"
            placeholder="Nama sesuai rapor"
            required
        >
        @error('name') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input
            id="email" type="email" name="email" class="form-input"
            value="{{ old('email', $student?->user->email ?? '') }}"
            placeholder="email@sekolah.sch.id"
            required
        >
        @error('email') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="nis" class="form-label">NIS</label>
        <input
            id="nis" type="text" name="nis" class="form-input"
            value="{{ old('nis', $student?->nis ?? '') }}"
            placeholder="Nomor induk siswa"
            required
        >
        @error('nis') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="nisn" class="form-label">NISN</label>
        <input
            id="nisn" type="text" name="nisn" class="form-input"
            value="{{ old('nisn', $student?->nisn ?? '') }}"
            placeholder="Nomor induk siswa nasional"
        >
        @error('nisn') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="kelas_id" class="form-label">Kelas</label>
        <select id="kelas_id" name="kelas_id" class="form-select" required>
            <option value="">Pilih kelas</option>
            @foreach ($kelasList ?? [] as $kelas)
                <option value="{{ $kelas->id }}"
                    {{ old('kelas_id', $student?->kelas_id ?? '') == $kelas->id ? 'selected' : '' }}>
                    {{ $kelas->nama_kelas }} — {{ $kelas->jurusan }}
                </option>
            @endforeach
        </select>
        @error('kelas_id') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <label for="status" class="form-label">Status siswa</label>
        <select id="status" name="status" class="form-select" required>
            <option value="aktif"    {{ old('status', $student?->status ?? 'aktif') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
            <option value="nonaktif" {{ old('status', $student?->status ?? '') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            <option value="lulus"    {{ old('status', $student?->status ?? '') === 'lulus'    ? 'selected' : '' }}>Lulus</option>
        </select>
        @error('status') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    @if (!isset($student))
        {{-- Hanya tampil saat create: input password awal --}}
        <div class="form-group">
            <label for="password" class="form-label">Kata sandi awal</label>
            <input
                id="password" type="password" name="password" class="form-input"
                placeholder="Min. 8 karakter"
                required
            >
            @error('password') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    @endif

</div>
