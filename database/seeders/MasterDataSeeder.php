<?php

namespace Database\Seeders;

use App\Models\kelas;
use App\Models\mata_pelajaran;
use App\Models\jenis_pengajuan;
use App\Models\students;
use App\Models\User;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Kelas ──────────────────────────────────────────────
        $kelasData = [
            ['nama_kelas' => 'X RPL 1',  'jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_kelas' => 'X RPL 2',  'jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_kelas' => 'XI RPL 1', 'jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_kelas' => 'XI RPL 2', 'jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_kelas' => 'XII RPL 1','jurusan' => 'Rekayasa Perangkat Lunak'],
            ['nama_kelas' => 'X TKJ 1',  'jurusan' => 'Teknik Komputer dan Jaringan'],
            ['nama_kelas' => 'XI TKJ 1', 'jurusan' => 'Teknik Komputer dan Jaringan'],
            ['nama_kelas' => 'XII TKJ 1','jurusan' => 'Teknik Komputer dan Jaringan'],
        ];

        foreach ($kelasData as $data) {
            kelas::create($data);
        }

        // ── Mata Pelajaran ─────────────────────────────────────
        $mapelData = [
            'Matematika',
            'Bahasa Indonesia',
            'Bahasa Inggris',
            'Pemrograman Web',
            'Basis Data',
            'Jaringan Komputer',
            'Pemrograman Berorientasi Objek',
            'Pendidikan Agama',
            'Pendidikan Pancasila',
            'Fisika',
        ];

        foreach ($mapelData as $nama) {
            mata_pelajaran::create(['nama' => $nama]);
        }

        // ── Jenis Pengajuan ────────────────────────────────────
        $jenisData = [
            'Izin sakit',
            'Izin keperluan keluarga',
            'Dispensasi lomba',
            'Dispensasi kegiatan organisasi',
            'Dispensasi kegiatan olahraga',
            'Izin lainnya',
        ];

        foreach ($jenisData as $nama) {
            jenis_pengajuan::create(['nama' => $nama]);
        }

        // ── Data Siswa ─────────────────────────────────────────
        // Siswa dibuat dari user yang memiliki role 'siswa'
        $siswaUsers = User::where('role', 'siswa')->get();
        $kelasList  = kelas::all();

        $nisCounter = 2024001;
        foreach ($siswaUsers as $i => $user) {
            students::create([
                'user_id'  => $user->id,
                'kelas_id' => $kelasList[$i % $kelasList->count()]->id,
                'nis'      => (string) ($nisCounter + $i),
                'nisn'     => '0' . str_pad((string) ($nisCounter + $i), 9, '0', STR_PAD_LEFT),
                'status'   => 'aktif',
            ]);
        }
    }
}
