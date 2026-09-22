<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\kelas;
use App\Models\students;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = students::with(['user', 'kelas'])
            ->join('users', 'students.user_id', '=', 'users.id')
            ->select('students.*');

        // Pencarian nama / NIS / NISN
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                  ->orWhere('students.nis', 'like', "%{$search}%")
                  ->orWhere('students.nisn', 'like', "%{$search}%");
            });
        }

        // Filter kelas
        if ($kelasId = $request->input('kelas_id')) {
            $query->where('students.kelas_id', $kelasId);
        }

        // Filter jurusan
        if ($jurusan = $request->input('jurusan')) {
            $query->whereHas('kelas', fn ($q) => $q->where('jurusan', $jurusan));
        }

        // Filter status
        if ($status = $request->input('status')) {
            $query->where('students.status', $status);
        }

        $students    = $query->orderBy('users.name')->paginate(20)->withQueryString();
        $kelasList   = kelas::orderBy('nama_kelas')->get();
        $jurusanList = kelas::distinct()->orderBy('jurusan')->pluck('jurusan');

        return view('admin.students.index', compact('students', 'kelasList', 'jurusanList'));
    }

    public function create()
    {
        $kelasList = kelas::orderBy('nama_kelas')->get();
        return view('admin.students.create', compact('kelasList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'nis'      => ['required', 'string', 'unique:students,nis'],
            'nisn'     => ['nullable', 'string', 'unique:students,nisn'],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'status'   => ['required', Rule::in(['aktif', 'nonaktif', 'lulus'])],
            'password' => ['required', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name'                 => $validated['name'],
                'email'                => $validated['email'],
                'password'             => Hash::make($validated['password']),
                'role'                 => 'siswa',
                'must_change_password' => true,
                'email_verified_at'    => now(),
            ]);

            students::create([
                'user_id'  => $user->id,
                'kelas_id' => $validated['kelas_id'],
                'nis'      => $validated['nis'],
                'nisn'     => $validated['nisn'] ?? null,
                'status'   => $validated['status'],
            ]);
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa disimpan.');
    }

    public function show(students $student)
    {
        $student->load(['user', 'kelas', 'pengajuans.jenisPengajuan', 'pengajuans.pengajaran.mataPelajaran']);
        return view('admin.students.show', compact('student'));
    }

    public function edit(students $student)
    {
        $kelasList = kelas::orderBy('nama_kelas')->get();
        return view('admin.students.edit', compact('student', 'kelasList'));
    }

    public function update(Request $request, students $student)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($student->user_id)],
            'nis'      => ['required', 'string', Rule::unique('students', 'nis')->ignore($student->id)],
            'nisn'     => ['nullable', 'string', Rule::unique('students', 'nisn')->ignore($student->id)],
            'kelas_id' => ['required', 'exists:kelas,id'],
            'status'   => ['required', Rule::in(['aktif', 'nonaktif', 'lulus'])],
        ]);

        DB::transaction(function () use ($validated, $student) {
            $student->user->update([
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ]);

            $student->update([
                'kelas_id' => $validated['kelas_id'],
                'nis'      => $validated['nis'],
                'nisn'     => $validated['nisn'] ?? null,
                'status'   => $validated['status'],
            ]);
        });

        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Data siswa diperbarui.');
    }

    public function destroy(students $student)
    {
        DB::transaction(function () use ($student) {
            $userId = $student->user_id;
            $student->delete();
            User::destroy($userId);
        });

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'Data siswa dihapus.');
    }
}
