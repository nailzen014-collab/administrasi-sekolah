<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'must_change_password', 'is_active', 'email_verified_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at'   => 'datetime',
            'password'            => 'hashed',
            'must_change_password'=> 'boolean',
            'is_active'           => 'boolean',
        ];
    }

    /**
     * Relasi ke students (user dengan role siswa memiliki satu data student)
     */
    public function student(): HasOne
    {
        return $this->hasOne(students::class, 'user_id');
    }

    /**
     * Relasi ke pengajarans (user dengan role guru mengajar banyak kelas/mapel)
     */
    public function pengajarans(): HasMany
    {
        return $this->hasMany(pengajaran::class, 'guru_id');
    }

    /**
     * Relasi ke riwayat_pengajuans (user yang memproses pengajuan)
     */
    public function riwayatPengajuans(): HasMany
    {
        return $this->hasMany(riwayat_pengajuan::class, 'user_id');
    }
}
