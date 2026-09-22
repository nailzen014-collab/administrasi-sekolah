<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\pengajaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'guru')
            ->withCount('pengajarans');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->input('status') === 'aktif') {
            $query->where('is_active', true);
        } elseif ($request->input('status') === 'nonaktif') {
            $query->where('is_active', false);
        }

        $gurus = $query->orderBy('name')->paginate(20)->withQueryString();

        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        return view('admin.guru.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        User::create([
            'name'                 => $validated['name'],
            'email'                => $validated['email'],
            'password'             => Hash::make($validated['password']),
            'role'                 => 'guru',
            'must_change_password' => true,
            'is_active'            => true,
            'email_verified_at'    => now(),
        ]);

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil disimpan.');
    }

    public function show(User $guru)
    {
        $this->authorizeGuru($guru);

        $guru->load('pengajarans.kelas', 'pengajarans.mataPelajaran');

        $pengajaranList = pengajaran::with(['kelas', 'mataPelajaran'])
            ->where('guru_id', $guru->id)
            ->get();

        return view('admin.guru.show', compact('guru', 'pengajaranList'));
    }

    public function edit(User $guru)
    {
        $this->authorizeGuru($guru);

        return view('admin.guru.edit', compact('guru'));
    }

    public function update(Request $request, User $guru)
    {
        $this->authorizeGuru($guru);

        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($guru->id)],
            'is_active'=> ['required', 'boolean'],
        ]);

        $guru->update([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'is_active' => $validated['is_active'],
        ]);

        // Reset password jika diisi
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['string', 'min:8'],
            ]);
            $guru->update([
                'password'             => Hash::make($request->input('password')),
                'must_change_password' => true,
            ]);
        }

        return redirect()
            ->route('admin.guru.show', $guru)
            ->with('success', 'Data guru berhasil diperbarui.');
    }

    /**
     * Reset kata sandi guru ke default "password".
     * Guru wajib ganti sandi saat login berikutnya.
     */
    public function resetPassword(User $guru)
    {
        $this->authorizeGuru($guru);

        $guru->update([
            'password'             => Hash::make('password'),
            'must_change_password' => true,
        ]);

        return redirect()
            ->route('admin.guru.show', $guru)
            ->with('success', 'Kata sandi ' . $guru->name . ' berhasil direset ke default. Guru wajib mengganti saat login berikutnya.');
    }

    public function destroy(User $guru)    {
        $this->authorizeGuru($guru);

        // Cegah hapus guru yang masih punya pengajaran aktif
        if ($guru->pengajarans()->exists()) {
            return redirect()
                ->route('admin.guru.show', $guru)
                ->with('error', 'Guru masih memiliki data pengajaran. Hapus pengajaran terlebih dahulu.');
        }

        $guru->delete();

        return redirect()
            ->route('admin.guru.index')
            ->with('success', 'Data guru berhasil dihapus.');
    }

    /**
     * Pastikan model yang di-bind memang role guru.
     */
    private function authorizeGuru(User $guru): void
    {
        if ($guru->role !== 'guru') {
            abort(404);
        }
    }
}
