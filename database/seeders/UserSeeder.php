<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ──────────────────────────────────────────────
        User::create([
            'name'                => 'Admin Tata Usaha',
            'email'               => 'admin@sekolah.sch.id',
            'password'            => Hash::make('password'),
            'role'                => 'admin',
            'must_change_password'=> false,
            'email_verified_at'   => now(),
        ]);

        // ── Guru ───────────────────────────────────────────────
        
        $guruData = [
            ['name' => 'Budi Santoso',     'email' => 'budi@sekolah.sch.id'],


            ['name' => 'Siti Rahayu',      'email' => 'siti@sekolah.sch.id'],
            ['name' => 'Ahmad Fauzi',      'email' => 'ahmad@sekolah.sch.id'],
            ['name' => 'Dewi Lestari',     'email' => 'dewi@sekolah.sch.id'],
        ];

        foreach ($guruData as $guru) {
            User::create([
                'name'                => $guru['name'],
                'email'               => $guru['email'],
                'password'            => Hash::make('password'),
                'role'                => 'guru',
                'must_change_password'=> true,
                'email_verified_at'   => now(),
            ]);
        }

        // ── Siswa ──────────────────────────────────────────────
        $siswaData = [
            ['name' => 'Andi Pratama',   'email' => 'andi@siswa.sch.id'],
            ['name' => 'Bella Safitri',  'email' => 'bella@siswa.sch.id'],
            ['name' => 'Cahyo Nugroho',  'email' => 'cahyo@siswa.sch.id'],
            ['name' => 'Dina Oktavia',   'email' => 'dina@siswa.sch.id'],
            ['name' => 'Eko Susanto',    'email' => 'eko@siswa.sch.id'],
            ['name' => 'Fani Rahmawati', 'email' => 'fani@siswa.sch.id'],
        ];

        foreach ($siswaData as $siswa) {
            User::create([
                'name'                => $siswa['name'],
                'email'               => $siswa['email'],
                'password'            => Hash::make('password'),
                'role'                => 'siswa',
                'must_change_password'=> true,
                'email_verified_at'   => now(),
            ]);
        }
    }
}
