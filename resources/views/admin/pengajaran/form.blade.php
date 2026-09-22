{{--
  Bagian dropdown form pengajaran.
  Hanya berisi field pilihan — pencarian tiap field ada di partial "search".
  Variabel yang diharapkan: $guruList, $mapelList, $kelasList, $selGuru, $selMapel, $selKelas, $errors
--}}

<div class="field-grid">

    <div class="form-group">
        <select id="guru_id" name="guru_id" class="form-select" required>
            <option value="">Pilih guru</option>
            @forelse ($guruList ?? [] as $guru)
                <option value="{{ $guru->id }}" {{ (string) $selGuru === (string) $guru->id ? 'selected' : '' }}>
                    {{ $guru->name }}
                </option>
            @empty
                <option value="" disabled>— tidak ditemukan —</option>
            @endforelse
        </select>
        @error('guru_id') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <select id="mata_pelajaran_id" name="mata_pelajaran_id" class="form-select" required>
            <option value="">Pilih mata pelajaran</option>
            @forelse ($mapelList ?? [] as $mapel)
                <option value="{{ $mapel->id }}" {{ (string) $selMapel === (string) $mapel->id ? 'selected' : '' }}>
                    {{ $mapel->nama }}
                </option>
            @empty
                <option value="" disabled>— tidak ditemukan —</option>
            @endforelse
        </select>
        @error('mata_pelajaran_id') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    <div class="form-group">
        <select id="kelas_id" name="kelas_id" class="form-select" required>
            <option value="">Pilih kelas</option>
            @forelse ($kelasList ?? [] as $kelas)
                <option value="{{ $kelas->id }}" {{ (string) $selKelas === (string) $kelas->id ? 'selected' : '' }}>
                    {{ $kelas->nama_kelas }} — {{ $kelas->jurusan }}
                </option>
            @empty
                <option value="" disabled>— tidak ditemukan —</option>
            @endforelse
        </select>
        @error('kelas_id') <span class="form-error">{{ $message }}</span> @enderror
    </div>

</div>