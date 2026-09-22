<x-app-layout>
    <x-slot name="title">Tambah pengajaran</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Tambah pengajaran</h1>
    </x-slot>

    <div class="panel" style="max-width:860px;">
        <div style="padding:1.5rem; border-bottom:1px solid var(--line-11);">
            <p style="color:var(--muted); font-size:.875rem;">
                Tugaskan seorang guru untuk mengampu satu mata pelajaran di satu kelas.
                Gunakan kolom pencarian untuk mempersempit pilihan pada masing-masing field.
            </p>
        </div>
        <div style="padding:1.5rem;">
            @include('admin.pengajaran.search')
            <form method="POST" action="{{ route('admin.pengajaran.store') }}">
                @csrf
                @include('admin.pengajaran.form')
                <hr class="divider">
                <div style="display:flex; gap:.75rem; justify-content:flex-end;">
                    <a href="{{ route('admin.pengajaran.index') }}" class="btn btn-ghost">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan pengajaran</button>
                </div>
            </form>
        </div>
    </div>

</x-app-layout>