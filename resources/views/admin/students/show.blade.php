<x-app-layout>
    <x-slot name="title">Detail siswa</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Detail siswa</h1>
    </x-slot>
    <x-slot name="headerAction">
        @if (Route::has('admin.students.edit'))
            <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-ghost">Ubah data</a>
        @endif
    </x-slot>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px,1fr)); gap:1.5rem; max-width:900px;">

        {{-- Info siswa --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11); display:flex; align-items:center; gap:1rem;">
                <div class="avatar" style="width:48px; height:48px; font-size:1rem;">
                    {{ strtoupper(substr($student->user->name ?? 'S', 0, 2)) }}
                </div>
                <div>
                    <p style="font-weight:600; color:var(--cream);">{{ $student->user->name ?? '—' }}</p>
                    <p style="font-size:.8rem; color:var(--muted);">{{ $student->user->email ?? '—' }}</p>
                </div>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                @foreach ([
                    'NIS'     => $student->nis,
                    'NISN'    => $student->nisn,
                    'Kelas'   => $student->kelas->nama_kelas ?? '—',
                    'Jurusan' => $student->kelas->jurusan ?? '—',
                ] as $label => $value)
                    <div style="display:flex; justify-content:space-between; padding:.6rem 0; border-bottom:1px solid var(--line-11);">
                        <span style="font-size:.82rem; color:var(--muted);">{{ $label }}</span>
                        <span style="font-size:.875rem; font-weight:500;">{{ $value ?? '—' }}</span>
                    </div>
                @endforeach
                <div style="display:flex; justify-content:space-between; align-items:center; padding:.6rem 0;">
                    <span style="font-size:.82rem; color:var(--muted);">Status</span>
                    <x-status-badge :status="$student->status" />
                </div>
                <hr class="divider">
                <form method="POST" action="{{ route('admin.students.destroy', $student) }}"
                      data-confirm
                      data-confirm-title="Hapus siswa"
                      data-confirm-text="Yakin ingin menghapus siswa {{ $student->user->name ?? '' }}? Seluruh riwayat pengajuan ikut terhapus.">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-sm" style="width:100%; color:var(--danger);">
                        Hapus siswa
                    </button>
                </form>
            </div>
        </div>

        {{-- Riwayat pengajuan --}}
        <div class="panel">
            <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
                <h2 style="font-size:1rem; font-weight:600;">Riwayat pengajuan</h2>
            </div>
            <div style="padding:1.25rem 1.5rem;">
                @forelse ($student->pengajuans ?? [] as $p)
                    <div style="display:flex; justify-content:space-between; align-items:center; padding:.65rem 0; border-bottom:1px solid var(--line-11);">
                        <div>
                            <p style="font-size:.875rem; font-weight:500;">{{ $p->jenisPengajuan->nama ?? '—' }}</p>
                            <p style="font-size:.75rem; color:var(--muted);">
                                {{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}
                                &middot; {{ $p->pengajaran->mataPelajaran->nama ?? '—' }}
                            </p>
                        </div>
                        <x-status-badge :status="$p->status" />
                    </div>
                @empty
                    <p style="color:var(--muted); font-size:.85rem;">Belum ada pengajuan.</p>
                @endforelse
            </div>
        </div>

    </div>

</x-app-layout>
