<x-app-layout>
    <x-slot name="title">Buat pengajuan</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Buat pengajuan</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('siswa.pengajuan.index'))
            <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-ghost btn-sm">← Riwayat saya</a>
        @endif
    </x-slot>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; max-width:960px;">

        {{-- KIRI: Formulir pengajuan --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                <h2 style="font-size:1rem; font-weight:600;">Formulir pengajuan</h2>
            </div>
            <form method="POST" action="{{ route('siswa.pengajuan.store') }}" style="padding:1.5rem; display:flex; flex-direction:column; gap:1.1rem;">
                @csrf

                <div class="form-group">
                    <label for="jenis_pengajuan_id" class="form-label">Jenis pengajuan</label>
                    <select id="jenis_pengajuan_id" name="jenis_pengajuan_id" class="form-select" required>
                        <option value="">Pilih jenis</option>
                        @foreach ($jenisPengajuanList ?? [] as $jenis)
                            <option value="{{ $jenis->id }}"
                                {{ old('jenis_pengajuan_id') == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_pengajuan_id') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="pengajaran_id" class="form-label">Pelajaran dan guru</label>
                    @if (($pengajaranList ?? collect())->isEmpty())
                        <div class="empty-state" style="padding:.9rem 1rem;">
                            <p style="font-size:.82rem;">
                                Belum ada daftar pelajaran untuk kelas Anda. Silakan hubungi tata usaha.
                            </p>
                        </div>
                        <input type="hidden" name="pengajaran_id" value="">
                    @else
                        <select id="pengajaran_id" name="pengajaran_id" class="form-select" required>
                            <option value="">Pilih pelajaran</option>
                            @foreach ($pengajaranList as $p)
                                <option value="{{ $p->id }}"
                                    {{ old('pengajaran_id') == $p->id ? 'selected' : '' }}>
                                    {{ $p->mataPelajaran->nama ?? '—' }} — {{ $p->guru->name ?? '—' }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                    @error('pengajaran_id') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal" class="form-label">Tanggal izin</label>
                    <input
                        id="tanggal" type="date" name="tanggal"
                        class="form-input"
                        value="{{ old('tanggal') }}"
                        min="{{ date('Y-m-d') }}"
                        required
                        style="color-scheme:dark;"
                    >
                    @error('tanggal') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea
                        id="keterangan" name="keterangan"
                        class="form-textarea"
                        rows="4"
                        placeholder="Jelaskan alasan pengajuan secara singkat..."
                        required
                    >{{ old('keterangan') }}</textarea>
                    @error('keterangan') <span class="form-error">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="align-self:flex-start;">
                    Kirim pengajuan
                </button>
            </form>
        </div>

        {{-- KANAN: Riwayat pengajuan terakhir --}}
        <div style="display:flex; flex-direction:column; gap:1.5rem;">

            {{-- Timeline alur status --}}
            <div class="panel">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                    <h2 style="font-size:1rem; font-weight:600;">Alur pengajuan</h2>
                </div>
                <div style="padding:1.25rem 1.5rem;">
                    <div class="timeline">
                        @foreach ([
                            ['label' => 'Diajukan',              'sub' => 'Siswa mengirim pengajuan'],
                            ['label' => 'Diperiksa admin',       'sub' => 'Tata usaha memverifikasi kelengkapan'],
                            ['label' => 'Menunggu keputusan guru','sub' => 'Guru menyetujui atau menolak'],
                        ] as $i => $step)
                            <div class="timeline-item">
                                <div class="timeline-dot"></div>
                                <div class="timeline-body">
                                    <div class="timeline-title">{{ $step['label'] }}</div>
                                    <div class="timeline-meta">{{ $step['sub'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- 3 pengajuan terakhir --}}
            @if (isset($pengajuanTerakhir) && $pengajuanTerakhir->isNotEmpty())
            <div class="panel">
                <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11); display:flex; align-items:center; justify-content:space-between;">
                    <h2 style="font-size:1rem; font-weight:600;">Pengajuan terakhir</h2>
                    @if (Route::has('siswa.pengajuan.index'))
                        <a href="{{ route('siswa.pengajuan.index') }}" class="btn btn-ghost btn-sm">Semua</a>
                    @endif
                </div>
                @foreach ($pengajuanTerakhir as $item)
                    <div class="list-row" style="padding:.85rem 1.25rem;">
                        <div class="list-row-main">
                            <div style="display:flex; gap:.5rem; align-items:center; flex-wrap:wrap;">
                                <span style="font-size:.875rem; font-weight:500;">{{ $item->jenisPengajuan->nama ?? '—' }}</span>
                                <x-status-badge :status="$item->status" />
                            </div>
                            <p style="font-size:.75rem; color:var(--muted); margin-top:.2rem;">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                            </p>
                        </div>
                        @if (Route::has('siswa.pengajuan.show'))
                            <a href="{{ route('siswa.pengajuan.show', $item) }}"
                               class="btn btn-ghost btn-sm">Detail</a>
                        @endif
                    </div>
                @endforeach
            </div>
            @endif

        </div>
    </div>

    {{-- Responsif: stack pada layar kecil --}}
    <style>
        @media (max-width: 680px) {
            .pengajuan-grid { grid-template-columns: 1fr !important; }
        }
    </style>

</x-app-layout>
