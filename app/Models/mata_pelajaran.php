<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class mata_pelajaran extends Model
{
    protected $table = 'mata_pelajarans';

    protected $fillable = [
        'nama',
    ];

    /**
     * Relasi ke pengajarans (satu mata pelajaran diajarkan di banyak pengajaran)
     */
    public function pengajarans(): HasMany
    {
        return $this->hasMany(pengajaran::class, 'mata_pelajaran_id');
    }
}
