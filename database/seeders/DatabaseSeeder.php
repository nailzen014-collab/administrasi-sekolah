<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Urutan penting: User harus ada sebelum MasterData (untuk students),
     * dan MasterData harus ada sebelum Pengajaran.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,        // 1. Buat akun admin, guru, siswa
            MasterDataSeeder::class,  // 2. Kelas, mapel, jenis pengajuan, data students
            PengajaranSeeder::class,  // 3. Assign guru ke kelas & mata pelajaran
        ]);
    }
}
