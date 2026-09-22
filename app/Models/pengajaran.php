<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class pengajaran extends Model
{
    protected $table = 'pengajarans';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mata_pelajaran_id',
    ];

    /**
     * Relasi ke user/guru (banyak pengajaran dimiliki oleh satu guru)
     */
    public function guru(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guru_id');
    }

    /**
     * Relasi ke kelas (banyak pengajaran berada di satu kelas)
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke mata_pelajaran (banyak pengajaran untuk satu mata pelajaran)
     */
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(mata_pelajaran::class, 'mata_pelajaran_id');
    }

    /**
     * Relasi ke pengajuans (satu pengajaran bisa memiliki banyak pengajuan)
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(pengajuan::class, 'pengajaran_id');
    }
}
