<x-app-layout>
    <x-slot name="title">Detail pengajuan</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Detail pengajuan</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('guru.persetujuan.index'))
            <a href="{{ route('guru.persetujuan.index') }}" class="btn btn-ghost btn-sm">← Kembali</a>
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
                        &middot; NIS {{ $pengajuan->student->nis ?? '' }}
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
                    'Tanggal izin'    => \Carbon\Carbon::parse($pengajuan->tanggal)->translatedFormat('d F Y'),
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
        </div>

        {{-- Panel aksi + riwayat --}}
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            @if ($pengajuan->status === 'diperiksa')
            <div class="panel">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                    <h2 style="font-size:1rem; font-weight:600;">Keputusan</h2>
                </div>

                {{-- Setujui --}}
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                    <form method="POST" action="{{ route('guru.persetujuan.update', $pengajuan) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="aksi" value="disetujui">
                        <div class="form-group" style="margin-bottom:.75rem;">
                            <label class="form-label">Catatan persetujuan (opsional)</label>
                            <textarea name="catatan" class="form-textarea" rows="2" placeholder="Catatan untuk siswa..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-success">Setujui pengajuan</button>
                    </form>
                </div>

                {{-- Tolak --}}
                <div style="padding:1.25rem 1.5rem;">
                    <form method="POST" action="{{ route('guru.persetujuan.update', $pengajuan) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="aksi" value="ditolak">
                        <div class="form-group" style="margin-bottom:.75rem;">
                            <label class="form-label">Alasan penolakan <span style="color:var(--s-ditolak);">*</span></label>
                            <textarea name="catatan" class="form-textarea" rows="2" placeholder="Tuliskan alasan penolakan..." required></textarea>
                            @error('catatan') <span class="form-error">{{ $message }}</span> @enderror
                        </div>
                        <button type="submit" class="btn btn-danger">Tolak pengajuan</button>
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
