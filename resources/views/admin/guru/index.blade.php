<x-app-layout>
    <x-slot name="title">Data guru</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Data guru</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('admin.guru.create'))
            <a href="{{ route('admin.guru.create') }}" class="btn btn-primary">Tambah guru</a>
        @endif
    </x-slot>

    {{-- Filter bar --}}
    <form method="GET" action="{{ route('admin.guru.index') }}" style="margin-bottom:1.5rem;">
        <div class="filter-bar">
            <div class="form-group" style="flex:1; min-width:180px; max-width:320px;">
                <label class="form-label">Cari nama / email</label>
                <input
                    type="text"
                    name="search"
                    class="form-input"
                    placeholder="Ketik nama atau email..."
                    value="{{ request('search') }}"
                >
            </div>

            <div class="form-group" style="min-width:140px;">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua</option>
                    <option value="aktif"    {{ request('status') === 'aktif'    ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div class="form-group" style="justify-content:flex-end; align-self:flex-end;">
                <button type="submit" class="btn btn-primary">Terapkan</button>
            </div>

            @if (request()->hasAny(['search', 'status']))
                <div class="form-group" style="align-self:flex-end;">
                    <a href="{{ route('admin.guru.index') }}" class="btn btn-ghost">Reset</a>
                </div>
            @endif
        </div>
    </form>

    {{-- Tabel guru --}}
    <div class="panel">
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Jml. Pengajaran</th>
                        <th>Status</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($gurus as $guru)
                        <tr>
                            <td style="font-weight:500;">{{ $guru->name }}</td>
                            <td style="color:var(--muted); font-size:.82rem;">{{ $guru->email }}</td>
                            <td style="text-align:center;">{{ $guru->pengajarans_count }}</td>
                            <td>
                                @if ($guru->is_active)
                                    <span class="badge badge-success">Aktif</span>
                                @else
                                    <span class="badge badge-muted">Nonaktif</span>
                                @endif
                            </td>
                            <td class="col-action" style="display:flex; gap:.5rem; justify-content:flex-end;">
                                <a href="{{ route('admin.guru.show', $guru) }}" class="btn btn-ghost btn-sm">Detail</a>
                                <a href="{{ route('admin.guru.edit', $guru) }}" class="btn btn-ghost btn-sm">Ubah</a>
                                <form method="POST" action="{{ route('admin.guru.destroy', $guru) }}"
                                      data-confirm
                                      data-confirm-title="Hapus guru"
                                      data-confirm-text="Yakin ingin menghapus guru {{ $guru->name }}? Aksi ini tidak dapat dibatalkan.">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state"><p>Tidak ada guru yang cocok.</p></div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($gurus->hasPages())
            <div class="pagination">
                {{ $gurus->withQueryString()->links('pagination::simple-default') }}
            </div>
        @endif
    </div>

</x-app-layout>
