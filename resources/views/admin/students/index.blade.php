<x-app-layout>
    <x-slot name="title">Data siswa</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Data siswa</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('admin.students.create'))
            <a href="{{ route('admin.students.create') }}" class="btn btn-primary">Tambah siswa</a>
        @endif
    </x-slot>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('admin.students.index') }}" style="margin-bottom:1.5rem;">
        <div class="filter-bar">
            <div class="form-group" style="flex:1; min-width:180px; max-width:280px;">
                <label class="form-label">Cari nama / NIS / NISN</label>
                <input
                    type="text"
                    name="search"
                    class="form-input"
                    placeholder="Ketik nama atau nomor..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="form-group" style="min-width:140px;">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua kelas</option>
                    @foreach ($kelasList ?? [] as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="min-width:140px;">
                <label class="form-label">Jurusan</label>
                <select name="jurusan" class="form-select">
                    <option value="">Semua jurusan</option>
                    @foreach ($jurusanList ?? [] as $j)
                        <option value="{{ $j }}" {{ request('jurusan') === $j ? 'selected' : '' }}>{{ $j }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="min-width:130px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua status</option>
                    <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="lulus"    {{ request('status') === 'lulus'    ? 'selected' : '' }}>Lulus</option>
                </select>
            </div>

            <div class="form-group" style="justify-content:flex-end; align-self:flex-end;">
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>

            @if (request()->hasAny(['search','kelas_id','jurusan','status']))
                <div class="form-group" style="align-self:flex-end;">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-ghost">Reset</a>
                </div>
            @endif
        </div>
    </form>

    {{-- Tabel siswa --}}
    <div class="panel">
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th>Status</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students ?? [] as $student)
                        <tr>
                            <td style="font-weight:500;">{{ $student->user->name ?? '—' }}</td>
                            <td style="color:var(--muted); font-size:.82rem;">{{ $student->nis }}</td>
                            <td>{{ $student->kelas->nama_kelas ?? '—' }}</td>
                            <td style="color:var(--muted);">{{ $student->kelas->jurusan ?? '—' }}</td>
                            <td><x-status-badge :status="$student->status" /></td>
                            <td class="col-action" style="display:flex; gap:.5rem; justify-content:flex-end;">
                                @if (Route::has('admin.students.show'))
                                    <a href="{{ route('admin.students.show', $student) }}"
                                       class="btn btn-ghost btn-sm">Detail</a>
                                @endif
                                @if (Route::has('admin.students.edit'))
                                    <a href="{{ route('admin.students.edit', $student) }}"
                                       class="btn btn-ghost btn-sm">Ubah</a>
                                @endif
                                @if (Route::has('admin.students.destroy'))
                                    <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                                          data-confirm
                                          data-confirm-title="Hapus siswa"
                                          data-confirm-text="Yakin ingin menghapus siswa {{ $student->user->name ?? '' }}? Seluruh riwayat pengajuan ikut terhapus.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);">Hapus</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state"><p>Tidak ada siswa yang cocok.</p></div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if (isset($students) && $students->hasPages())
            <div class="pagination">
                {{ $students->withQueryString()->links('pagination::simple-default') }}
            </div>
        @endif
    </div>

</x-app-layout>
