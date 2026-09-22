<x-app-layout>
    <x-slot name="title">Tambah guru</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Tambah guru</h1>
    </x-slot>

    <div class="panel" style="max-width:860px;">
        <div style="padding:1.5rem; border-bottom:1px solid var(--line-11);">
            <p style="color:var(--muted); font-size:.875rem;">
                Isi data di bawah. Guru wajib mengganti kata sandi saat pertama kali masuk.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.guru.store') }}" style="padding:1.5rem;">
            @csrf
            @include('admin.guru.form')
            <hr class="divider">
            <div style="display:flex; gap:.75rem; justify-content:flex-end;">
                <a href="{{ route('admin.guru.index') }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan guru</button>
            </div>
        </form>
    </div>

</x-app-layout>
