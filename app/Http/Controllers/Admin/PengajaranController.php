<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\kelas;
use App\Models\mata_pelajaran;
use App\Models\pengajaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PengajaranController extends Controller
{
    public function index(Request $request)
    {
        $query = pengajaran::with(['guru', 'kelas', 'mataPelajaran'])->withCount('pengajuans');

        if ($search = trim((string) $request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('guru', fn ($q) => $q->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('mataPelajaran', fn ($q) => $q->where('nama', 'like', "%{$search}%"))
                  ->orWhereHas('kelas', fn ($q) => $q->where('nama_kelas', 'like', "%{$search}%"));
            });
        }

        if ($kelasId = $request->input('kelas_id')) {
            $query->where('kelas_id', $kelasId);
        }

        $pengajarans = $query->orderBy('kelas_id')->paginate(20)->withQueryString();
        $kelasList   = kelas::orderBy('nama_kelas')->get();

        return view('admin.pengajaran.index', compact('pengajarans', 'kelasList'));
    }

    public function create(Request $request)
    {
        return $this->formData($request);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        pengajaran::create($validated);

        return redirect()
            ->route('admin.pengajaran.index')
            ->with('success', 'Data pengajaran disimpan.');
    }

    public function edit(Request $request, pengajaran $pengajaran)
    {
        return $this->formData($request, $pengajaran);
    }

    public function update(Request $request, pengajaran $pengajaran)
    {
        $validated = $this->validateData($request, $pengajaran);

        $pengajaran->update($validated);

        return redirect()
            ->route('admin.pengajaran.index')
            ->with('success', 'Data pengajaran diperbarui.');
    }

    public function destroy(pengajaran $pengajaran)
    {
        if ($pengajaran->pengajuans()->exists()) {
            return redirect()
                ->route('admin.pengajaran.index')
                ->with('error', 'Pengajaran masih memiliki data pengajuan. Tidak dapat dihapus.');
        }

        $pengajaran->delete();

        return redirect()
            ->route('admin.pengajaran.index')
            ->with('success', 'Data pengajaran dihapus.');
    }

    /**
     * Validasi input untuk store & update.
     * Kombinasi guru + kelas + mapel harus unik.
     */
    private function validateData(Request $request, ?pengajaran $pengajaran = null): array
    {
        $unique = Rule::unique('pengajarans', 'mata_pelajaran_id')
            ->where(fn ($q) => $q
                ->where('guru_id', $request->input('guru_id'))
                ->where('kelas_id', $request->input('kelas_id')));

        if ($pengajaran) {
            $unique = $unique->ignore($pengajaran->id);
        }

        return $request->validate([
            'guru_id'           => ['required', 'exists:users,id'],
            'kelas_id'          => ['required', 'exists:kelas,id'],
            'mata_pelajaran_id' => ['required', 'exists:mata_pelajarans,id', $unique],
        ]);
    }

    /**
     * Kumpulkan data dropdown untuk form create & edit.
     * Mendukung filter pencarian per field via query string/old input.
     */
    private function formData(Request $request, ?pengajaran $pengajaran = null)
    {
        $selGuru  = old('guru_id',  $pengajaran?->guru_id           ?? $request->query('guru_id'));
        $selMapel = old('mata_pelajaran_id', $pengajaran?->mata_pelajaran_id ?? $request->query('mata_pelajaran_id'));
        $selKelas = old('kelas_id', $pengajaran?->kelas_id          ?? $request->query('kelas_id'));

        $guruQ  = old('guru_q',  (string) $request->query('guru_q'));
        $mapelQ = old('mapel_q', (string) $request->query('mapel_q'));
        $kelasQ = old('kelas_q', (string) $request->query('kelas_q'));

        // Daftar guru (filter nama)
        $guruQuery = User::where('role', 'guru');
        if ($guruQ !== '') {
            $guruQuery->where('name', 'like', "%{$guruQ}%");
        }
        $guruList = $guruQuery->orderBy('name')->get();
        $this->ensureSelected($guruList, $selGuru, User::class);

        // Daftar mata pelajaran (filter nama)
        $mapelQuery = mata_pelajaran::query();
        if ($mapelQ !== '') {
            $mapelQuery->where('nama', 'like', "%{$mapelQ}%");
        }
        $mapelList = $mapelQuery->orderBy('nama')->get();
        $this->ensureSelected($mapelList, $selMapel, mata_pelajaran::class);

        // Daftar kelas (filter nama kelas / jurusan)
        $kelasQuery = kelas::query();
        if ($kelasQ !== '') {
            $kelasQuery->where(function ($q) use ($kelasQ) {
                $q->where('nama_kelas', 'like', "%{$kelasQ}%")
                  ->orWhere('jurusan', 'like', "%{$kelasQ}%");
            });
        }
        $kelasList = $kelasQuery->orderBy('nama_kelas')->get();
        $this->ensureSelected($kelasList, $selKelas, kelas::class);

        $data = compact(
            'guruList', 'mapelList', 'kelasList',
            'guruQ', 'mapelQ', 'kelasQ',
            'selGuru', 'selMapel', 'selKelas',
        );

        if ($pengajaran) {
            return view('admin.pengajaran.edit', compact('pengajaran') + $data);
        }

        return view('admin.pengajaran.create', $data);
    }

    /**
     * Pastikan nilai terpilih selalu tampil di dropdown meski hasil filter
     * tidak memuatnya (mis. nilai sudah disimpan sebelumnya).
     */
    private function ensureSelected($list, $id, string $modelClass): void
    {
        if (! $id || $list->contains('id', $id)) {
            return;
        }

        if ($item = $modelClass::find($id)) {
            $list->prepend($item);
        }
    }
}