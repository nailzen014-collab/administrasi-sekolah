@props(['status'])

@php
$labels = [
    'diajukan'     => 'Diajukan',
    'diperiksa'    => 'Diperiksa',
    'dikembalikan' => 'Dikembalikan',
    'disetujui'    => 'Disetujui',
    'ditolak'      => 'Ditolak',
    // status siswa
    'aktif'        => 'Aktif',
    'nonaktif'     => 'Nonaktif',
    'pending'      => 'Pending',
    'lulus'        => 'Lulus',
];
$label = $labels[$status] ?? ucfirst($status);
@endphp

<span class="badge badge-{{ $status }}">{{ $label }}</span>
