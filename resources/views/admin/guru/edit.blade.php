<x-app-layout>
    <x-slot name="title">Ubah data guru</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Ubah data guru</h1>
    </x-slot>

    <div class="panel" style="max-width:860px;">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
            <p style="font-weight:600; color:var(--cream);">{{ $guru->name }}</p>
            <p style="font-size:.8rem; color:var(--muted);">{{ $guru->email }}</p>
        </div>
        <form method="POST" action="{{ route('admin.guru.update', $guru) }}" style="padding:1.5rem;">
            @csrf
            @method('PUT')
            @include('admin.guru.form')
            <hr class="divider">
            <div style="display:flex; gap:.75rem; justify-content:flex-end;">
                <a href="{{ route('admin.guru.show', $guru) }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            </div>
        </form>
    </div>

</x-app-layout>
