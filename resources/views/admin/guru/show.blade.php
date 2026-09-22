<x-app-layout>
    <x-slot name="title">Detail guru — {{ $guru->name }}</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Detail guru</h1>
    </x-slot>
    <x-slot name="headerAction">
        <a href="{{ route('admin.guru.edit', $guru) }}" class="btn btn-primary">Ubah data</a>
    </x-slot>

    <div style="display:grid; grid-template-columns:1fr 2fr; gap:1.5rem; align-items:start;">

        {{-- Kartu profil --}}
        <div class="panel" style="padding:1.5rem;">
            <div style="margin-bottom:1rem;">
                <p style="font-size:1.125rem; font-weight:600; color:var(--cream);">{{ $guru->name }}</p>
                <p style="font-size:.82rem; color:var(--muted); margin-top:.25rem;">{{ $guru->email }}</p>
            </div>

            <div style="display:grid; gap:.75rem; font-size:.875rem;">
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--muted);">Role</span>
                    <span>Guru</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--muted);">Status akun</span>
                    <span>
                        @if ($guru->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-muted">Nonaktif</span>
                        @endif
                    </span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--muted);">Wajib ganti sandi</span>
                    <span>{{ $guru->must_change_password ? 'Ya' : 'Tidak' }}</span>
                </div>
                <div style="display:flex; justify-content:space-between;">
                    <span style="color:var(--muted);">Bergabung</span>
                    <span>{{ $guru->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <hr class="divider">

            {{-- Hapus guru --}}
            @if ($pengajaranList->isEmpty())
                <form method="POST" action="{{ route('admin.guru.destroy', $guru) }}"
                      data-confirm
                      data-confirm-title="Hapus guru"
                      data-confirm-text="Yakin ingin menghapus guru {{ $guru->name }}? Aksi ini tidak dapat dibatalkan.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-sm" style="width:100%; color:var(--danger);">
                        Hapus guru
                    </button>
                </form>
            @else
                <p style="font-size:.78rem; color:var(--muted); text-align:center;">
                    Hapus semua data pengajaran terlebih dahulu sebelum menghapus guru ini.
                </p>
            @endif
        </div>

        {{-- Daftar pengajaran --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                <p style="font-weight:600; color:var(--cream);">
                    Data pengajaran
                    <span style="font-weight:400; color:var(--muted); font-size:.82rem;">
                        ({{ $pengajaranList->count() }} mata pelajaran)
                    </span>
                </p>
            </div>

            @if ($pengajaranList->isEmpty())
                <div class="empty-state" style="padding:2rem;">
                    <p>Guru ini belum memiliki data pengajaran.</p>
                </div>
            @else
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Mata pelajaran</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pengajaranList as $p)
                                <tr>
                                    <td style="font-weight:500;">{{ $p->mataPelajaran->nama ?? '—' }}</td>
                                    <td>{{ $p->kelas->nama_kelas ?? '—' }}</td>
                                    <td style="color:var(--muted);">{{ $p->kelas->jurusan ?? '—' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>

    <div style="margin-top:1rem;">
        <a href="{{ route('admin.guru.index') }}" class="btn btn-ghost btn-sm">← Kembali ke daftar guru</a>
    </div>

</x-app-layout>
