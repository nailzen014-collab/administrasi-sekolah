<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Dashboard</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('siswa.pengajuan.create'))
            <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-primary">Buat pengajuan</a>
        @endif
    </x-slot>

    {{-- Ringkasan status --}}
    <div class="stat-strip" style="margin-bottom:2rem;">
        <div class="stat-item">
            <span class="stat-number">{{ $total }}</span>
            <span class="stat-label">Total pengajuan</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $diajukan }}</span>
            <span class="stat-label">Menunggu pemeriksaan</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $diperiksa }}</span>
            <span class="stat-label">Sedang diperiksa</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $disetujui }}</span>
            <span class="stat-label">Disetujui</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $ditolak }}</span>
            <span class="stat-label">Ditolak</span>
        </div>
    </div>

    {{-- Pengajuan terbaru --}}
    <div class="panel">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:.75rem;">
            <h2 style="font-size:1rem; font-weight:600; color:var(--cream);">Pengajuan terbaru</h2>
            @if (Route::has('siswa.pengajuan.index'))
                <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-ghost btn-sm">Lihat semua</a>
            @endif
        </div>

        @forelse ($pengajuanTerbaru as $item)
            <div class="list-row">
                <div class="list-row-main">
                    <div style="display:flex; align-items:center; gap:.65rem; flex-wrap:wrap; margin-bottom:.3rem;">
                        <span style="font-weight:600;">{{ $item->jenisPengajuan->nama ?? '—' }}</span>
                        <x-status-badge :status="$item->status" />
                    </div>
                    <div style="font-size:.82rem; color:var(--muted); display:flex; flex-wrap:wrap; gap:.6rem;">
                        <span>{{ $item->pengajaran->mataPelajaran->nama ?? '—' }}</span>
                        <span>&middot;</span>
                        <span>{{ $item->pengajaran->guru->name ?? '—' }}</span>
                        <span>&middot;</span>
                        <span>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                    </div>
                    @if ($item->status === 'dikembalikan')
                        @php $lastNote = $item->riwayatPengajuans->where('status_baru','dikembalikan')->last(); @endphp
                        @if ($lastNote && $lastNote->catatan)
                            <p style="font-size:.78rem; color:var(--s-dikembalikan); margin-top:.3rem;">
                                Catatan: {{ $lastNote->catatan }}
                            </p>
                        @endif
                    @endif
                </div>
                <div class="list-row-actions">
                    @if (Route::has('siswa.pengajuan.show'))
                        <a href="{{ route('siswa.pengajuan.show', $item) }}"
                           class="btn btn-ghost btn-sm">Detail</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p>Belum ada pengajuan.
                    @if (Route::has('siswa.pengajuan.create'))
                        <a href="{{ route('siswa.pengajuan.create') }}"
                           style="color:var(--ember); text-decoration:none;">Buat pengajuan pertama.</a>
                    @endif
                </p>
            </div>
        @endforelse
    </div>

</x-app-layout>
