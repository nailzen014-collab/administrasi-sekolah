<x-app-layout>
    <x-slot name="title">Ubah data siswa</x-slot>
    <x-slot name="header">
        <h1 class="page-title">Ubah data siswa</h1>
    </x-slot>

    <div class="panel" style="max-width:860px;">
        <div style="padding:1.25rem 1.5rem; border-bottom:1px solid var(--line-11);">
            <p style="font-weight:600; color:var(--cream);">{{ $student->user->name ?? '—' }}</p>
            <p style="font-size:.8rem; color:var(--muted);">NIS {{ $student->nis }}</p>
        </div>
        <form method="POST" action="{{ route('admin.students.update', $student) }}" style="padding:1.5rem;">
            @csrf
            @method('PUT')
            @include('admin.students.form')
            <hr class="divider">
            <div style="display:flex; gap:.75rem; justify-content:flex-end;">
                <a href="{{ route('admin.students.show', $student) }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan perubahan</button>
            </div>
        </form>
    </div>

</x-app-layout>
