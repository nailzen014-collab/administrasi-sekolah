<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Dashboard</h1>
    </x-slot>

    {{-- Strip angka ringkasan --}}
    <div class="stat-strip" style="margin-bottom:2rem;">
        <div class="stat-item">
            <span class="stat-number">{{ $totalPengajuan ?? 0 }}</span>
            <span class="stat-label">Total pengajuan</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $menungguPemeriksaan ?? 0 }}</span>
            <span class="stat-label">Menunggu pemeriksaan</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $totalSiswa ?? 0 }}</span>
            <span class="stat-label">Siswa aktif</span>
        </div>
    </div>

    {{-- Tabel pengajuan terbaru --}}
    <div class="panel">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem;">
            <h2 style="font-size:1rem; font-weight:600; color:var(--cream);">Pengajuan terbaru</h2>
            @if (Route::has('admin.pemeriksaan.index'))
                <a href="{{ route('admin.pemeriksaan.index') }}" class="btn btn-ghost btn-sm">Lihat semua</a>
            @endif
        </div>

        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Jenis</th>
                        <th>Guru tujuan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuanTerbaru ?? [] as $item)
                        <tr>
                            <td>{{ $item->student->user->name ?? '—' }}</td>
                            <td>{{ $item->jenisPengajuan->nama ?? '—' }}</td>
                            <td>{{ $item->pengajaran->guru->name ?? '—' }}</td>
                            <td style="color:var(--muted); white-space:nowrap;">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </td>
                            <td><x-status-badge :status="$item->status" /></td>
                            <td class="col-action">
                                @if (Route::has('admin.pemeriksaan.show'))
                                    <a href="{{ route('admin.pemeriksaan.show', $item) }}"
                                       class="btn btn-ghost btn-sm">Periksa</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <p>Belum ada pengajuan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
