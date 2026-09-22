<x-app-layout>
    <x-slot name="title">Detail pengajuan</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Detail pengajuan</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('siswa.pengajuan.index'))
            <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
        @endif
    </x-slot>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(280px,1fr)); gap:1.5rem; max-width:860px;">

        {{-- Detail pengajuan --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11); display:flex; align-items:center; justify-content:space-between; gap:.75rem;">
                <h2 style="font-size:1rem; font-weight:600;">{{ $pengajuan->jenisPengajuan->nama ?? 'Pengajuan' }}</h2>
                <x-status-badge :status="$pengajuan->status" />
            </div>

            <div style="padding:1.25rem 1.5rem;">
                @foreach ([
                    'Mata pelajaran' => $pengajuan->pengajaran->mataPelajaran->nama ?? '—',
                    'Guru'           => $pengajuan->pengajaran->guru->name ?? '—',
                    'Tanggal izin'   => \Carbon\Carbon::parse($pengajuan->tanggal)->translatedFormat('d F Y'),
                    'Diajukan pada'  => \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y, H:i'),
                ] as $label => $value)
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:.6rem 0; border-bottom:1px solid var(--line-11); gap:1rem;">
                        <span style="font-size:.8rem; color:var(--muted); flex-shrink:0;">{{ $label }}</span>
                        <span style="font-size:.875rem; font-weight:500; text-align:right;">{{ $value }}</span>
                    </div>
                @endforeach
                <div style="padding:.75rem 0;">
                    <p style="font-size:.8rem; color:var(--muted); margin-bottom:.4rem;">Keterangan</p>
                    <p style="font-size:.9rem; line-height:1.6;">{{ $pengajuan->keterangan ?? '—' }}</p>
                </div>
            </div>

            {{-- Tombol buat ulang jika dikembalikan --}}
            @if ($pengajuan->status === 'dikembalikan' && Route::has('siswa.pengajuan.create'))
                <div style="padding:0 1.5rem 1.5rem;">
                    <hr class="divider" style="margin-top:0;">
                    <p style="font-size:.8rem; color:var(--muted); margin-bottom:.75rem;">
                        Pengajuan ini dikembalikan. Buat pengajuan baru untuk mengajukan kembali.
                    </p>
                    <a href="{{ route('siswa.pengajuan.create') }}" class="btn btn-primary btn-sm">
                        Buat pengajuan baru
                    </a>
                </div>
            @endif
        </div>

        {{-- Riwayat status --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                <h2 style="font-size:1rem; font-weight:600;">Riwayat status</h2>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                <x-riwayat :riwayat="$pengajuan->riwayatPengajuans()->orderBy('created_at')->get()" />
            </div>
        </div>

    </div>

</x-app-layout>
