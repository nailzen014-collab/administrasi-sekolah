<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class kelas extends Model
{
    protected $table = 'kelas';

    protected $fillable = [
        'nama_kelas',
        'jurusan',
    ];

    /**
     * Relasi ke students (satu kelas memiliki banyak siswa)
     */
    public function students(): HasMany
    {
        return $this->hasMany(students::class, 'kelas_id');
    }

    /**
     * Relasi ke pengajarans (satu kelas memiliki banyak pengajaran)
     */
    public function pengajarans(): HasMany
    {
        return $this->hasMany(pengajaran::class, 'kelas_id');
    }
}
