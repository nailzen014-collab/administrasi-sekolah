<x-app-layout>
    <x-slot name="title">Pengajuan saya</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Pengajuan saya</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('siswa.pengajuan.create'))
            <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-primary">Buat pengajuan</a>
        @endif
    </x-slot>

    {{-- Filter chip status --}}
    <div class="chip-group" style="margin-bottom:1.5rem;">
        @foreach ([
            ''             => 'Semua',
            'diajukan'     => 'Diajukan',
            'diperiksa'    => 'Diperiksa',
            'dikembalikan' => 'Dikembalikan',
            'disetujui'    => 'Disetujui',
            'ditolak'      => 'Ditolak',
        ] as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
               class="chip {{ request('status', '') === $val ? 'active' : '' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="panel">
        @forelse ($pengajuans ?? [] as $item)
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

        @if (isset($pengajuans) && $pengajuans->hasPages())
            <div class="pagination">
                {{ $pengajuans->withQueryString()->links('pagination::simple-default') }}
            </div>
        @endif
    </div>

</x-app-layout>
