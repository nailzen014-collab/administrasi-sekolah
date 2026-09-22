<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class pengajuan extends Model
{
    protected $table = 'pengajuans';

    protected $fillable = [
        'student_id',
        'pengajaran_id',
        'jenis_pengajuan_id',
        'tanggal',
        'keterangan',
        'status',
    ];

    /**
     * Relasi ke students (banyak pengajuan dimiliki oleh satu siswa)
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(students::class, 'student_id');
    }

    /**
     * Relasi ke jenis_pengajuan (banyak pengajuan memiliki satu jenis pengajuan)
     */
    public function jenisPengajuan(): BelongsTo
    {
        return $this->belongsTo(jenis_pengajuan::class, 'jenis_pengajuan_id');
    }

    /**
     * Relasi ke pengajaran (banyak pengajuan terkait satu pengajaran)
     */
    public function pengajaran(): BelongsTo
    {
        return $this->belongsTo(pengajaran::class, 'pengajaran_id');
    }

    /**
     * Relasi ke riwayat_pengajuans (satu pengajuan memiliki banyak riwayat status)
     */
    public function riwayatPengajuans(): HasMany
    {
        return $this->hasMany(riwayat_pengajuan::class, 'pengajuan_id');
    }
}

