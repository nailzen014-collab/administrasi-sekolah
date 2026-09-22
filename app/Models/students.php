<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class students extends Model
{
    protected $table = 'students';

    protected $fillable = [
        'user_id',
        'kelas_id',
        'nis',
        'nisn',
        'status',
    ];

    /**
     * Relasi ke user (setiap student dimiliki oleh satu user)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relasi ke kelas (setiap student berada di satu kelas)
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(kelas::class, 'kelas_id');
    }

    /**
     * Relasi ke pengajuans (satu siswa bisa memiliki banyak pengajuan)
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(pengajuan::class, 'student_id');
    }
}
