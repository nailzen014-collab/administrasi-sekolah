<x-app-layout>
    <x-slot name="title">Tambah siswa</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Tambah siswa</h1>
    </x-slot>

    <div class="panel" style="max-width:860px;">
        <div style="padding:1.5rem; border-bottom:1px solid var(--line-11);">
            <p style="color:var(--muted); font-size:.875rem;">
                Isi data di bawah. Kata sandi awal dapat diganti siswa saat pertama kali masuk.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.students.store') }}" style="padding:1.5rem;">
            @csrf
            @include('admin.students.form')
            <hr class="divider">
            <div style="display:flex; gap:.75rem; justify-content:flex-end;">
                <a href="{{ route('admin.students.index') }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan siswa</button>
            </div>
        </form>
    </div>

</x-app-layout>
