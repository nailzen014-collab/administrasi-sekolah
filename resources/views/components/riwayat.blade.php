@props(['riwayat'])

{{--
  Komponen timeline riwayat pengajuan.
  Props:
    $riwayat — koleksi riwayat_pengajuan (ordered by created_at asc)
  Setiap item: status_lama, status_baru, catatan, user->name, created_at
--}}

<div class="timeline">
    @forelse ($riwayat as $item)
        @php
            $isDone    = true;   // semua item di koleksi sudah terjadi
            $isLast    = $loop->last;
            $dotClass  = $isLast ? 'current' : 'done';
            $labels = [
                'diajukan'     => 'Diajukan',
                'diperiksa'    => 'Diperiksa',
                'dikembalikan' => 'Dikembalikan',
                'disetujui'    => 'Disetujui',
                'ditolak'      => 'Ditolak',
            ];
            $labelBaru = $labels[$item->status_baru] ?? ucfirst($item->status_baru);
        @endphp

        <div class="timeline-item">
            <div class="timeline-dot {{ $dotClass }}"></div>
            <div class="timeline-body">
                <div class="timeline-title">
                    {{ $labelBaru }}
                    @if ($item->status_lama)
                        <span style="color:var(--muted); font-weight:400; font-size:.8rem;">
                            dari {{ $labels[$item->status_lama] ?? $item->status_lama }}
                        </span>
                    @endif
                </div>
                <div class="timeline-meta">
                    {{ $item->user->name ?? '—' }}
                    &middot;
                    {{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d M Y, H:i') }}
                </div>
                @if ($item->catatan)
                    <div style="
                        margin-top:.4rem;
                        font-size:.8rem;
                        color:var(--muted);
                        background:var(--line-11);
                        border-radius:8px;
                        padding:.4rem .65rem;
                        border-left:2px solid var(--ember);
                    ">
                        {{ $item->catatan }}
                    </div>
                @endif
            </div>
        </div>
    @empty
        <p style="color:var(--muted); font-size:.85rem;">Belum ada riwayat.</p>
    @endforelse
</div>
