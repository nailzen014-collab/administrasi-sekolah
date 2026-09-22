<x-app-layout>
    <x-slot name="title">Periksa pengajuan</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Periksa pengajuan</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('admin.pemeriksaan.index'))
            <a href="{{ route('admin.pemeriksaan.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
        @endif
    </x-slot>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px,1fr)); gap:1.5rem; max-width:960px;">

        {{-- Detail pengajuan --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11); display:flex; align-items:center; gap:.75rem;">
                <div class="avatar">
                    {{ strtoupper(substr($pengajuan->student->user->name ?? 'S', 0, 2)) }}
                </div>
                <div>
                    <p style="font-weight:600;">{{ $pengajuan->student->user->name ?? '—' }}</p>
                    <p style="font-size:.78rem; color:var(--muted);">
                        {{ $pengajuan->student->kelas->nama_kelas ?? '' }}
                        {{ $pengajuan->student->kelas->jurusan ?? '' }}
                    </p>
                </div>
                <div style="margin-left:auto;">
                    <x-status-badge :status="$pengajuan->status" />
                </div>
            </div>

            <div style="padding:1.25rem 1.5rem;">
                @foreach ([
                    'Jenis pengajuan' => $pengajuan->jenisPengajuan->nama ?? '—',
                    'Mata pelajaran'  => $pengajuan->pengajaran->mataPelajaran->nama ?? '—',
                    'Guru tujuan'     => $pengajuan->pengajaran->guru->name ?? '—',
                    'Tanggal izin'    => \Carbon\Carbon::parse($pengajuan->tanggal)->translatedFormat('d F Y'),
                ] as $label => $value)
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; padding:.6rem 0; border-bottom:1px solid var(--line-11); gap:1rem;">
                        <span style="font-size:.8rem; color:var(--muted); flex-shrink:0;">{{ $label }}</span>
                        <span style="font-size:.875rem; font-weight:500; text-align:right;">{{ $value }}</span>
                    </div>
                @endforeach

                <div style="padding:.75rem 0;">
                    <p style="font-size:.8rem; color:var(--muted); margin-bottom:.4rem;">Keterangan</p>
                    <p style="font-size:.9rem; line-height:1.6; color:var(--cream);">{{ $pengajuan->keterangan ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Panel aksi + riwayat --}}
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            {{-- Aksi: hanya tampil jika status masih diajukan --}}
            @if ($pengajuan->status === 'diajukan')
            <div class="panel">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                    <h2 style="font-size:1rem; font-weight:600;">Tindakan pemeriksaan</h2>
                </div>

                {{-- Teruskan ke guru --}}
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                    <p style="font-size:.8rem; color:var(--muted); margin-bottom:.75rem;">
                        Teruskan pengajuan ini ke guru yang bersangkutan untuk diproses lebih lanjut.
                    </p>
                    <form method="POST" action="{{ route('admin.pemeriksaan.update', $pengajuan) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="aksi" value="diperiksa">
                        <div class="form-group" style="margin-bottom:.75rem;">
                            <label class="form-label">Catatan (opsional)</label>
                            <textarea name="catatan" class="form-textarea" rows="2" placeholder="Catatan untuk guru..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">Teruskan ke guru</button>
                    </form>
                </div>

                {{-- Kembalikan ke siswa --}}
                <div style="padding:1.25rem 1.5rem;">
                    <p style="font-size:.8rem; color:var(--muted); margin-bottom:.75rem;">
                        Kembalikan ke siswa untuk dilengkapi atau diperbaiki.
                    </p>
                    <form method="POST" action="{{ route('admin.pemeriksaan.update', $pengajuan) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="aksi" value="dikembalikan">
                        <div class="form-group" style="margin-bottom:.75rem;">
                            <label class="form-label">Alasan pengembalian <span style="color:var(--s-ditolak);">*</span></label>
                            <textarea name="catatan" class="form-textarea" rows="2" placeholder="Tuliskan alasan..." required></textarea>
                            @error('catatan') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-danger btn-sm">Kembalikan ke siswa</button>
                    </form>
                </div>
            </div>
            @endif

            {{-- Riwayat --}}
            <div class="panel">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                    <h2 style="font-size:1rem; font-weight:600;">Riwayat status</h2>
                </div>
                <div style="padding:1.25rem 1.5rem;">
                    <x-riwayat :riwayat="$pengajuan->riwayatPengajuans()->orderBy('created_at')->get()" />
                </div>
            </div>

        </div>
    </div>

</x-app-layout>
