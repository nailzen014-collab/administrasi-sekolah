<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\pengajuan;
use App\Models\riwayat_pengajuan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PersetujuanController extends Controller
{
    public function index(Request $request)
    {
        $guru = $request->user();

        $query = pengajuan::with([
            'student.user',
            'student.kelas',
            'jenisPengajuan',
            'pengajaran.mataPelajaran',
            'riwayatPengajuans.user',
        ])
        // Hanya pengajuan yang terkait pengajaran milik guru ini
        ->whereHas('pengajaran', fn ($q) => $q->where('guru_id', $guru->id));

        // Filter status dari chip
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        } else {
            // Default: tampilkan semua kecuali yang masih diajukan (belum diperiksa admin)
            $query->whereIn('status', ['diperiksa', 'disetujui', 'ditolak']);
        }

        $pengajuans = $query->latest()->paginate(20);

        return view('guru.persetujuan.index', compact('pengajuans'));
    }

    public function show(Request $request, pengajuan $pengajuan)
    {
        // Pastikan guru ini yang mengajar kelas terkait
        $this->authorizeGuru($request->user(), $pengajuan);

        $pengajuan->load([
            'student.user',
            'student.kelas',
            'jenisPengajuan',
            'pengajaran.mataPelajaran',
            'riwayatPengajuans.user',
        ]);

        return view('guru.persetujuan.show', compact('pengajuan'));
    }

    public function update(Request $request, pengajuan $pengajuan)
    {
        $guru = $request->user();
        $this->authorizeGuru($guru, $pengajuan);

        $validated = $request->validate([
            'aksi'    => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan' => [
                Rule::requiredIf($request->input('aksi') === 'ditolak'),
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        if ($pengajuan->status !== 'diperiksa') {
            return back()->with('error', 'Pengajuan ini tidak dapat diubah.');
        }

        $statusLama = $pengajuan->status;
        $statusBaru = $validated['aksi'];

        $pengajuan->update(['status' => $statusBaru]);

        riwayat_pengajuan::create([
            'pengajuan_id' => $pengajuan->id,
            'user_id'      => $guru->id,
            'status_lama'  => $statusLama,
            'status_baru'  => $statusBaru,
            'catatan'      => $validated['catatan'] ?? null,
        ]);

        $pesan = $statusBaru === 'disetujui'
            ? 'Pengajuan disetujui.'
            : 'Pengajuan ditolak.';

        return redirect()
            ->route('guru.persetujuan.index')
            ->with('success', $pesan);
    }

    /**
     * Pastikan pengajuan ini memang terkait dengan guru yang sedang login.
     */
    private function authorizeGuru($guru, pengajuan $pengajuan): void
    {
        if ($pengajuan->pengajaran->guru_id !== $guru->id) {
            abort(403, 'Anda tidak berwenang mengakses pengajuan ini.');
        }
    }
}
