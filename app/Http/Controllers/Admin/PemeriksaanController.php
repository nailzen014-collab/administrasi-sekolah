<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\pengajuan;
use App\Models\riwayat_pengajuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PemeriksaanController extends Controller
{
    public function index()
    {
        $pengajuans = pengajuan::with([
            'student.user',
            'student.kelas',
            'jenisPengajuan',
            'pengajaran.guru',
            'pengajaran.mataPelajaran',
        ])
        ->whereIn('status', ['diajukan', 'diperiksa', 'dikembalikan'])
        ->latest()
        ->paginate(20);

        return view('admin.pemeriksaan.index', compact('pengajuans'));
    }

    public function show(pengajuan $pengajuan)
    {
        $pengajuan->load([
            'student.user',
            'student.kelas',
            'jenisPengajuan',
            'pengajaran.guru',
            'pengajaran.mataPelajaran',
            'riwayatPengajuans.user',
        ]);

        return view('admin.pemeriksaan.show', compact('pengajuan'));
    }

    public function update(Request $request, pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'aksi'    => ['required', Rule::in(['diperiksa', 'dikembalikan'])],
            'catatan' => [
                Rule::requiredIf($request->input('aksi') === 'dikembalikan'),
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        // Hanya boleh memproses pengajuan yang masih berstatus 'diajukan'
        if ($pengajuan->status !== 'diajukan') {
            return back()->with('error', 'Pengajuan ini tidak dapat diubah karena sudah diproses.');
        }

        $statusLama = $pengajuan->status;
        $statusBaru = $validated['aksi'];

        $pengajuan->update(['status' => $statusBaru]);

        riwayat_pengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id'      => $request->user()->id,
            'status_lama'  => $statusLama,
            'status_baru'  => $statusBaru,
            'catatan'      => $validated['catatan'] ?? null,
        ]);

        $pesan = $statusBaru === 'diperiksa'
            ? 'Pengajuan diteruskan ke guru.'
            : 'Pengajuan dikembalikan ke siswa.';

        return redirect()
            ->route('admin.pemeriksaan.index')
            ->with('success', $pesan);
    }
}
