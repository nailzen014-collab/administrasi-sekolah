{{--
  Bar pencarian tiap field (diproses server-side via PHP).
  Satu kolom = satu fitur pencarian yang menyatu dengan dropdown di bawahnya.
  Variabel yang diharapkan: $guruQ, $mapelQ, $kelasQ, $selGuru, $selMapel, $selKelas
--}}

<div class="search-grid">

    <form method="GET" action="{{ url()->current() }}">
        <label for="guru_q" class="form-label">Guru</label>
        <div class="search-row">
            <input
                type="search"
                id="guru_q"
                name="guru_q"
                class="form-input field-search"
                value="{{ $guruQ }}"
                placeholder="Cari guru…"
                autocomplete="off"
            >
            <button type="submit" class="btn btn-ghost btn-sm">Cari</button>
        </div>
        <input type="hidden" name="guru_id" value="{{ $selGuru }}">
        <input type="hidden" name="mata_pelajaran_id" value="{{ $selMapel }}">
        <input type="hidden" name="kelas_id" value="{{ $selKelas }}">
        <input type="hidden" name="mapel_q" value="{{ $mapelQ }}">
        <input type="hidden" name="kelas_q" value="{{ $kelasQ }}">
    </form>

    <form method="GET" action="{{ url()->current() }}">
        <label for="mapel_q" class="form-label">Mata pelajaran</label>
        <div class="search-row">
            <input
                type="search"
                id="mapel_q"
                name="mapel_q"
                class="form-input field-search"
                value="{{ $mapelQ }}"
                placeholder="Cari mata pelajaran…"
                autocomplete="off"
            >
            <button type="submit" class="btn btn-ghost btn-sm">Cari</button>
        </div>
        <input type="hidden" name="guru_id" value="{{ $selGuru }}">
        <input type="hidden" name="mata_pelajaran_id" value="{{ $selMapel }}">
        <input type="hidden" name="kelas_id" value="{{ $selKelas }}">
        <input type="hidden" name="guru_q" value="{{ $guruQ }}">
        <input type="hidden" name="kelas_q" value="{{ $kelasQ }}">
    </form>

    <form method="GET" action="{{ url()->current() }}">
        <label for="kelas_q" class="form-label">Kelas</label>
        <div class="search-row">
            <input
                type="search"
                id="kelas_q"
                name="kelas_q"
                class="form-input field-search"
                value="{{ $kelasQ }}"
                placeholder="Cari kelas…"
                autocomplete="off"
            >
            <button type="submit" class="btn btn-ghost btn-sm">Cari</button>
        </div>
        <input type="hidden" name="guru_id" value="{{ $selGuru }}">
        <input type="hidden" name="mata_pelajaran_id" value="{{ $selMapel }}">
        <input type="hidden" name="kelas_id" value="{{ $selKelas }}">
        <input type="hidden" name="guru_q" value="{{ $guruQ }}">
        <input type="hidden" name="mapel_q" value="{{ $mapelQ }}">
    </form>

</div>