<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class riwayat_pengajuan extends Model
{
    protected $table = 'riwayat_pengajuans';

    protected $fillable = [
        'pengajuan_id',
        'user_id',
        'status_lama',
        'status_baru',
        'catatan',
    ];

    /**
     * Relasi ke pengajuan (banyak riwayat dimiliki oleh satu pengajuan)
     */
    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(pengajuan::class, 'pengajuan_id');
    }

    /**
     * Relasi ke user (banyak riwayat dicatat oleh satu user/admin/guru)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
