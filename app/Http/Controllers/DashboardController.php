<?php

namespace App\Http\Controllers;

use App\Models\pengajuan;
use App\Models\students;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        return match ($user->role) {
            'admin' => $this->adminDashboard(),
            'guru'  => $this->guruDashboard($user),
            'siswa' => $this->siswaDashboard($user),
            default => abort(403),
        };
    }

    // ── Admin ────────────────────────────────────────────────
    private function adminDashboard()
    {
        $totalPengajuan       = pengajuan::count();
        $menungguPemeriksaan  = pengajuan::where('status', 'diajukan')->count();
        $totalSiswa           = students::where('status', 'aktif')->count();

        $pengajuanTerbaru = pengajuan::with([
            'student.user',
            'jenisPengajuan',
            'pengajaran.guru',
            'pengajaran.mataPelajaran',
        ])
        ->latest()
        ->limit(10)
        ->get();

        return view('dashboard.admin', compact(
            'totalPengajuan',
            'menungguPemeriksaan',
            'totalSiswa',
            'pengajuanTerbaru',
        ));
    }

    // ── Guru ─────────────────────────────────────────────────
    private function guruDashboard($user)
    {
        $base = pengajuan::whereHas('pengajaran', fn ($q) => $q->where('guru_id', $user->id));

        $total       = (clone $base)->count();
        $menunggu    = (clone $base)->where('status', 'diperiksa')->count();
        $disetujui   = (clone $base)->where('status', 'disetujui')->count();
        $ditolak     = (clone $base)->where('status', 'ditolak')->count();

        $pengajuanTerbaru = (clone $base)
            ->with(['student.user', 'student.kelas', 'jenisPengajuan', 'pengajaran.mataPelajaran', 'riwayatPengajuans.user'])
            ->whereIn('status', ['diperiksa', 'disetujui', 'ditolak'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.guru', compact(
            'total', 'menunggu', 'disetujui', 'ditolak', 'pengajuanTerbaru',
        ));
    }

    // ── Siswa ────────────────────────────────────────────────
    private function siswaDashboard($user)
    {
        $student = $user->student;

        if (! $student) {
            // Akun siswa belum punya data profil student, tampil dashboard kosong
            return view('dashboard.siswa', [
                'total'           => 0,
                'diajukan'        => 0,
                'diperiksa'       => 0,
                'disetujui'       => 0,
                'ditolak'         => 0,
                'pengajuanTerbaru'=> collect(),
            ]);
        }

        $base = pengajuan::where('student_id', $student->id);

        $total     = (clone $base)->count();
        $diajukan  = (clone $base)->where('status', 'diajukan')->count();
        $diperiksa = (clone $base)->where('status', 'diperiksa')->count();
        $disetujui = (clone $base)->where('status', 'disetujui')->count();
        $ditolak   = (clone $base)->where('status', 'ditolak')->count();

        $pengajuanTerbaru = (clone $base)
            ->with(['jenisPengajuan', 'pengajaran.guru', 'pengajaran.mataPelajaran', 'riwayatPengajuans'])
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.siswa', compact(
            'total', 'diajukan', 'diperiksa', 'disetujui', 'ditolak', 'pengajuanTerbaru',
        ));
    }
}
