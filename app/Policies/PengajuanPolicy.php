<?php

namespace App\Policies;

use App\Models\User;
use App\Models\pengajuan;

class PengajuanPolicy
{
    /**
     * Admin bisa melihat semua pengajuan.
     * Guru hanya bisa melihat pengajuan yang terkait pengajaran mereka.
     * Siswa hanya bisa melihat pengajuan miliknya sendiri.
     */
    public function view(User $user, pengajuan $pengajuan): bool
    {
        return match ($user->role) {
            'admin'  => true,
            'guru'   => $pengajuan->pengajaran->guru_id === $user->id,
            'siswa'  => $pengajuan->student->user_id === $user->id,
            default  => false,
        };
    }

    /**
     * Hanya siswa yang bisa membuat pengajuan.
     */
    public function create(User $user): bool
    {
        return $user->role === 'siswa';
    }

    /**
     * Admin bisa mengubah status menjadi diperiksa atau dikembalikan.
     * Guru bisa mengubah status menjadi disetujui atau ditolak (hanya pengajaran miliknya).
     */
    public function update(User $user, pengajuan $pengajuan): bool
    {
        return match ($user->role) {
            'admin' => in_array($pengajuan->status, ['diajukan'], true),
            'guru'  => $pengajuan->pengajaran->guru_id === $user->id
                       && in_array($pengajuan->status, ['diperiksa'], true),
            default => false,
        };
    }

    /**
     * Hanya admin yang bisa menghapus (jika diperlukan).
     */
    public function delete(User $user, pengajuan $pengajuan): bool
    {
        return $user->role === 'admin';
    }
}
