<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\jenis_pengajuan;
use App\Models\pengajaran;
use App\Models\pengajuan;
use App\Models\riwayat_pengajuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengajuanController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $query = pengajuan::with(['jenisPengajuan', 'pengajaran.guru', 'pengajaran.mataPelajaran', 'riwayatPengajuans'])
            ->where('student_id', $student->id);

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $pengajuans = $query->latest()->paginate(15);

        return view('siswa.pengajuan.index', compact('pengajuans'));
    }

    public function create(Request $request)
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $jenisPengajuanList = jenis_pengajuan::orderBy('nama')->get();

        // Hanya tampilkan pengajaran yang terkait kelas siswa ini
        $pengajaranList = pengajaran::with(['guru', 'mataPelajaran'])
            ->where('kelas_id', $student->kelas_id)
            ->get();

        // Tiga pengajuan terakhir untuk sidebar
        $pengajuanTerakhir = pengajuan::with(['jenisPengajuan'])
            ->where('student_id', $student->id)
            ->latest()
            ->limit(3)
            ->get();

        return view('siswa.pengajuan.create', compact(
            'jenisPengajuanList',
            'pengajaranList',
            'pengajuanTerakhir',
        ));
    }

    public function store(Request $request)
    {
        $student = $request->user()->student;

        if (! $student) {
            abort(404, 'Data siswa tidak ditemukan.');
        }

        $validated = $request->validate([
            'jenis_pengajuan_id' => ['required', 'exists:jenis_pengajuans,id'],
            'pengajaran_id'      => [
                'required',
                // Pastikan pengajaran ini memang untuk kelas siswa
                Rule::exists('pengajarans', 'id')->where('kelas_id', $student->kelas_id),
            ],
            'tanggal'    => ['required', 'date', 'after_or_equal:today'],
            'keterangan' => ['required', 'string', 'min:10', 'max:1000'],
        ]);

        $pengajuan = pengajuan::create([
            'student_id'         => $student->id,
            'jenis_pengajuan_id' => $validated['jenis_pengajuan_id'],
            'pengajaran_id'      => $validated['pengajaran_id'],
            'tanggal'            => $validated['tanggal'],
            'keterangan'         => $validated['keterangan'],
            'status'             => 'diajukan',
        ]);

        // Catat riwayat awal
        riwayat_pengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id'      => $request->user()->id,
            'status_lama'  => null,
            'status_baru'  => 'diajukan',
            'catatan'      => null,
        ]);

        return redirect()
            ->route('siswa.pengajuan.show', $pengajuan)
            ->with('success', 'Pengajuan terkirim dan sedang menunggu pemeriksaan.');
    }

    public function show(Request $request, pengajuan $pengajuan)
    {
        // Hanya bisa lihat pengajuan milik sendiri
        if ($pengajuan->student->user_id !== $request->user()->id) {
            abort(403);
        }

        $pengajuan->load([
            'jenisPengajuan',
            'pengajaran.guru',
            'pengajaran.mataPelajaran',
            'riwayatPengajuans.user',
        ]);

        return view('siswa.pengajuan.show', compact('pengajuan'));
    }
}
