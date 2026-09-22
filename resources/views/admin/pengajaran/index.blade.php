<x-app-layout>
    <x-slot name="title">Data pengajaran</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Data pengajaran</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('admin.pengajaran.create'))
            <a href="{{ route('admin.pengajaran.create') }}" class="btn btn-primary">Tambah pengajaran</a>
        @endif
    </x-slot>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('admin.pengajaran.index') }}" style="margin-bottom:1.5rem;">
        <div class="filter-bar">
            <div class="form-group" style="flex:1; min-width:180px; max-width:340px;">
                <label class="form-label">Cari guru / mapel / kelas</label>
                <input
                    type="text"
                    name="search"
                    class="form-input"
                    placeholder="Ketik nama guru, mapel, atau kelas..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="form-group" style="min-width:160px;">
                <label class="form-label">Kelas</label>
                <select name="kelas_id" class="form-select">
                    <option value="">Semua</option>
                    @foreach ($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }} — {{ $kelas->jurusan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="justify-content:flex-end; align-self:flex-end;">
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>

            @if (request()->hasAny(['search', 'kelas_id']))
                <div class="form-group" style="align-self:flex-end;">
                    <a href="{{ route('admin.pengajaran.index') }}" class="btn btn-ghost">Reset</a>
                </div>
            @endif
        </div>
    </form>

    {{-- Tabel pengajaran --}}
    <div class="panel">
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Guru</th>
                        <th>Mata pelajaran</th>
                        <th>Kelas</th>
                        <th>Jumlah pengajuan</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajarans as $p)
                        <tr>
                            <td style="font-weight:500;">{{ $p->guru->name ?? '—' }}</td>
                            <td>{{ $p->mataPelajaran->nama ?? '—' }}</td>
                            <td>
                                <span class="badge">{{ $p->kelas->nama_kelas ?? '—' }} {{ $p->kelas->jurusan ?? '' }}</span>
                            </td>
                            <td style="text-align:center;">{{ $p->pengajuans_count ?? 0 }}</td>
                            <td class="col-action" style="display:flex; gap:.5rem; justify-content:flex-end;">
<a href="{{ route('admin.pengajaran.edit', $p) }}" class="btn btn-ghost btn-sm">Ubah</a>
                                <form
                                    method="POST"
                                    action="{{ route('admin.pengajaran.destroy', $p) }}"
                                    data-confirm
                                    data-confirm-title="Hapus pengajaran"
                                    data-confirm-text="Yakin ingin menghapus {{ $p->mataPelajaran->nama ?? '' }} — {{ $p->kelas->nama_kelas ?? '' }} {{ $p->kelas->jurusan ?? '' }} ({{ $p->guru->name ?? '' }})?"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state"><p>Tidak ada data pengajaran yang cocok.</p></div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($pengajarans->hasPages())
            <div class="pagination">
                {{ $pengajarans->withQueryString()->links('pagination::simple-default') }}
            </div>
        @endif
    </div>

</x-app-layout>