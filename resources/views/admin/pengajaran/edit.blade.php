<x-app-layout>
    <x-slot name="title">Ubah data pengajaran</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Ubah data pengajaran</h1>
    </x-slot>

    <div class="panel" style="max-width:860px;">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
            <p style="font-weight:600; color:var(--cream);">
                {{ $pengajaran->mataPelajaran->nama ?? '—' }} — {{ $pengajaran->kelas->nama_kelas ?? '—' }}
            </p>
            <p style="font-size:.8rem; color:var(--muted);">{{ $pengajaran->guru->name ?? '—' }}</p>
        </div>
        <div style="padding:1.5rem;">
            @include('admin.pengajaran.search')
            <form method="POST" action="{{ route('admin.pengajaran.update', $pengajaran) }}">
                @csrf
                @method('PUT')
                @include('admin.pengajaran.form')
                <hr class="divider">
                <div style="display:flex; gap:.75rem; justify-content:flex-end;">
                    <a href="{{ route('admin.pengajaran.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan perubahan</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>