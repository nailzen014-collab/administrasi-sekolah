<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class jenis_pengajuan extends Model
{
    protected $table = 'jenis_pengajuans';

    protected $fillable = [
        'nama',
    ];

    /**
     * Relasi ke pengajuans (satu jenis pengajuan digunakan di banyak pengajuan)
     */
    public function pengajuans(): HasMany
    {
        return $this->hasMany(pengajuan::class, 'jenis_pengajuan_id');
    }
}
