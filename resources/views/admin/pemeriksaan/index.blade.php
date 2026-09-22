<x-app-layout>
    <x-slot name="title">Pemeriksaan pengajuan</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Pemeriksaan pengajuan</h1>
    </x-slot>

    <div class="panel">
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Jenis</th>
                        <th>Mata pelajaran</th>
                        <th>Guru tujuan</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th class="col-action">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pengajuans ?? [] as $item)
                        <tr>
                            <td style="font-weight:500;">{{ $item->student->user->name ?? '—' }}</td>
                            <td>{{ $item->jenisPengajuan->nama ?? '—' }}</td>
                            <td style="color:var(--muted);">{{ $item->pengajaran->mataPelajaran->nama ?? '—' }}</td>
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
                            <td colspan="7">
                                <div class="empty-state"><p>Tidak ada pengajuan yang perlu diperiksa.</p></div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if (isset($pengajuans) && $pengajuans->hasPages())
            <div class="pagination">
                {{ $pengajuans->withQueryString()->links('pagination::simple-default') }}
            </div>
        @endif
    </div>

</x-app-layout>
