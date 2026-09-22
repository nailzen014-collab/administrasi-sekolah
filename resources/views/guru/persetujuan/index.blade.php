<x-app-layout>
    <x-slot name="title">Pengajuan masuk</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Pengajuan masuk</h1>
    </x-slot>

    {{-- Filter chip --}}
    <div class="chip-group" style="margin-bottom:1.5rem;">
        @foreach ([
            ''            => 'Semua',
            'diperiksa'   => 'Menunggu',
            'disetujui'   => 'Disetujui',
            'ditolak'     => 'Ditolak',
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
                {{-- Avatar inisial --}}
                <div class="avatar">
                    {{ strtoupper(substr($item->student->user->name ?? 'S', 0, 2)) }}
                </div>

                {{-- Info utama --}}
                <div class="list-row-main">
                    <div style="display:flex; align-items:center; gap:.6rem; flex-wrap:wrap; margin-bottom:.25rem;">
                        <span style="font-weight:600; font-size:.95rem;">{{ $item->student->user->name ?? '—' }}</span>
                        <span style="font-size:.78rem; color:var(--muted);">
                            {{ $item->student->kelas->nama_kelas ?? '' }} {{ $item->student->kelas->jurusan ?? '' }}
                        </span>
                        <x-status-badge :status="$item->status" />
                    </div>
                    <div style="font-size:.82rem; color:var(--muted); display:flex; flex-wrap:wrap; gap:.75rem;">
                        <span>{{ $item->jenisPengajuan->nama ?? '—' }}</span>
                        <span>&middot;</span>
                        <span>{{ $item->pengajaran->mataPelajaran->nama ?? '—' }}</span>
                        <span>&middot;</span>
                        <span>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</span>
                    </div>
                    @if ($item->keterangan)
                        <p style="font-size:.82rem; color:var(--muted); margin-top:.3rem; line-height:1.5;">
                            {{ Str::limit($item->keterangan, 100) }}
                        </p>
                    @endif
                    {{-- Riwayat singkat: satu baris terakhir --}}
                    @if ($item->riwayatPengajuans->isNotEmpty())
                        @php $last = $item->riwayatPengajuans->last(); @endphp
                        <p style="font-size:.75rem; color:var(--muted); margin-top:.3rem;">
                            Terakhir diubah oleh {{ $last->user->name ?? '—' }}
                            &middot; {{ \Carbon\Carbon::parse($last->created_at)->diffForHumans() }}
                        </p>
                    @endif
                </div>

                {{-- Aksi --}}
                @if ($item->status === 'diperiksa')
                    <div class="list-row-actions">
                        @if (Route::has('guru.persetujuan.show'))
                            <a href="{{ route('guru.persetujuan.show', $item) }}"
                               class="btn btn-ghost btn-sm">Detail</a>
                        @endif
                        @if (Route::has('guru.persetujuan.update'))
                            <form method="POST" action="{{ route('guru.persetujuan.update', $item) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="aksi" value="disetujui">
                                <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('guru.persetujuan.update', $item) }}" style="display:inline;">
                                @csrf @method('PATCH')
                                <input type="hidden" name="aksi" value="ditolak">
                                <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                            </form>
                        @endif
                    </div>
                @else
                    <div class="list-row-actions">
                        @if (Route::has('guru.persetujuan.show'))
                            <a href="{{ route('guru.persetujuan.show', $item) }}"
                               class="btn btn-ghost btn-sm">Detail</a>
                        @endif
                    </div>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <p>Tidak ada pengajuan{{ request('status') ? ' dengan status ini' : '' }}.</p>
            </div>
        @endforelse

        @if (isset($pengajuans) && $pengajuans->hasPages())
            <div class="pagination">
                {{ $pengajuans->withQueryString()->links('pagination::simple-default') }}
            </div>
        @endif
    </div>

</x-app-layout>
