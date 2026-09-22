<?php

namespace Database\Seeders;

use App\Models\pengajaran;
use App\Models\kelas;
use App\Models\mata_pelajaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class PengajaranSeeder extends Seeder
{
    public function run(): void
    {
        $guruList  = User::where('role', 'guru')->get();
        $kelasList = kelas::all();
        $mapelList = mata_pelajaran::all();

        if ($guruList->isEmpty() || $kelasList->isEmpty() || $mapelList->isEmpty()) {
            $this->command->warn('Jalankan UserSeeder dan MasterDataSeeder terlebih dahulu.');
            return;
        }

        // Setiap guru mengajar 2-3 mapel di beberapa kelas
        // Kombinasi harus unik (guru_id + kelas_id + mata_pelajaran_id)
        $assignments = [
            // guru[0] — Budi Santoso: Matematika di kelas X RPL 1, XI RPL 1
            [0, 0, 0], // guru[0], kelas[0], mapel[0] = Matematika
            [0, 2, 0], // guru[0], kelas[2], mapel[0]
            [0, 0, 9], // guru[0], kelas[0], mapel[9] = Fisika
            [0, 2, 9],

            // guru[1] — Siti Rahayu: Bahasa Indonesia & Inggris
            [1, 0, 1], // mapel[1] = Bahasa Indonesia
            [1, 2, 1],
            [1, 0, 2], // mapel[2] = Bahasa Inggris
            [1, 2, 2],

            // guru[2] — Ahmad Fauzi: Pemrograman Web & Basis Data
            [2, 0, 3], // mapel[3] = Pemrograman Web
            [2, 2, 3],
            [2, 1, 3],
            [2, 0, 4], // mapel[4] = Basis Data
            [2, 2, 4],

            // guru[3] — Dewi Lestari: Jaringan Komputer & PKn
            [3, 5, 5], // kelas X TKJ, mapel[5] = Jaringan Komputer
            [3, 6, 5], // kelas XI TKJ
            [3, 0, 8], // mapel[8] = Pendidikan Pancasila
            [3, 2, 8],

            // ── Lengkapi kelas RPL yang masih kosong (XI RPL 2, XII RPL 1) ──
            [0, 3, 0], // XI RPL 2 — Matematika
            [0, 4, 0], // XII RPL 1 — Matematika
            [0, 3, 9], // XI RPL 2 — Fisika
            [0, 4, 9], // XII RPL 1 — Fisika
            [1, 3, 1], // XI RPL 2 — Bahasa Indonesia
            [1, 4, 1], // XII RPL 1 — Bahasa Indonesia
            [1, 3, 2], // XI RPL 2 — Bahasa Inggris
            [1, 4, 2], // XII RPL 1 — Bahasa Inggris
            [2, 3, 3], // XI RPL 2 — Pemrograman Web
            [2, 4, 3], // XII RPL 1 — Pemrograman Web
            [2, 3, 4], // XI RPL 2 — Basis Data
            [2, 4, 4], // XII RPL 1 — Basis Data
            [3, 3, 8], // XI RPL 2 — Pendidikan Pancasila
            [3, 4, 8], // XII RPL 1 — Pendidikan Pancasila

            // ── Lengkapi kelas TKJ yang masih kosong (XII TKJ 1) ──
            [3, 7, 5], // XII TKJ 1 — Jaringan Komputer
            [0, 7, 9], // XII TKJ 1 — Fisika
            [1, 7, 1], // XII TKJ 1 — Bahasa Indonesia
            [1, 7, 2], // XII TKJ 1 — Bahasa Inggris
            [3, 7, 8], // XII TKJ 1 — Pendidikan Pancasila
        ];

        foreach ($assignments as [$gi, $ki, $mi]) {
            $guru  = $guruList->get($gi);
            $kelas = $kelasList->get($ki);
            $mapel = $mapelList->get($mi);

            if (! $guru || ! $kelas || ! $mapel) {
                continue;
            }

            // Hindari duplikat (unique constraint)
            pengajaran::firstOrCreate([
                'guru_id'          => $guru->id,
                'kelas_id'         => $kelas->id,
                'mata_pelajaran_id'=> $mapel->id,
            ]);
        }
    }
}
