# Product Requirements Document (PRD)
## Sistem Administrasi Sekolah — Pengajuan Izin & Dispensasi

**Versi:** 1.0.0  
**Tanggal:** 21 September 2026  
**Status:** In Development  
**Tech Stack:** Laravel 13 (brand: NOVARA), PHP 8.3, Tailwind CSS, Vite

---

## 1. Latar Belakang

Proses pengajuan izin dan dispensasi siswa di sekolah saat ini masih dilakukan secara manual (kertas/lisan), menyebabkan keterlambatan informasi, sulitnya pelacakan status, dan tidak adanya riwayat yang terstruktur. Sistem ini hadir untuk mendigitalisasi alur tersebut agar lebih efisien, transparan, dan dapat diaudit.

---

## 2. Tujuan Produk

- Memberikan platform terpusat bagi siswa untuk mengajukan izin/dispensasi secara digital.
- Memungkinkan guru dan admin memproses, memeriksa, dan menyetujui/menolak pengajuan dengan cepat.
- Menyimpan seluruh riwayat perubahan status pengajuan secara otomatis.
- Mengurangi penggunaan kertas dan mempercepat waktu respons pengajuan.

---

## 3. Pengguna (Roles)

| Role   | Deskripsi |
|--------|-----------|
| Admin  | Mengelola data master (siswa, kelas, guru, mata pelajaran, pengajaran). Memeriksa pengajuan dan mengubah status menjadi `diperiksa` atau `dikembalikan`. |
| Guru   | Melihat pengajuan yang masuk untuk kelas/mapel yang diajarkan. Menyetujui (`disetujui`) atau menolak (`ditolak`) pengajuan. |
| Siswa  | Mengajukan izin/dispensasi. Melihat status dan riwayat pengajuan miliknya sendiri. |

---

## 4. Alur Sistem (User Flow)

### 4.1 Alur Pengajuan Siswa
```
Siswa login
  → Buat pengajuan baru (pilih jenis, pengajaran, tanggal, keterangan)
  → Status: "diajukan"
  → Menunggu pemeriksaan admin
```

### 4.2 Alur Pemeriksaan Admin
```
Admin login
  → Lihat daftar pengajuan masuk (status: "diajukan")
  → Periksa kelengkapan data
  → Ubah status ke "diperiksa" (lanjut ke guru) ATAU "dikembalikan" (minta revisi siswa)
  → Riwayat perubahan status otomatis tersimpan
```

### 4.3 Alur Persetujuan Guru
```
Guru login
  → Lihat daftar pengajuan (status: "diperiksa") untuk kelas/mapel yang diajarkan
  → Setujui → status: "disetujui" ATAU Tolak → status: "ditolak"
  → Riwayat perubahan status otomatis tersimpan
```

### 4.4 Status Pengajuan
```
diajukan → diperiksa → disetujui
                    ↘ ditolak
         → dikembalikan (kembali ke siswa untuk revisi)
```

---

## 5. Fitur Produk

### 5.1 Autentikasi & Otorisasi
- Login/logout dengan email dan password (via Laravel Breeze).
- Role-based access control: `admin`, `guru`, `siswa`.
- Middleware proteksi route berdasarkan role.
- Fitur `must_change_password` untuk akun baru yang wajib ganti password saat pertama login.

### 5.2 Manajemen Data Master (Admin)
- **Kelas:** CRUD nama kelas dan jurusan.
- **Mata Pelajaran:** CRUD nama mata pelajaran.
- **Guru/Pengajaran:** Assign guru ke kelas dan mata pelajaran (kombinasi unik: guru + kelas + mapel).
- **Siswa:** CRUD data siswa, termasuk NIS, NISN, kelas, dan status (`aktif`, `nonaktif`, `lulus`).

### 5.3 Pengajuan Izin/Dispensasi (Siswa)
- Buat pengajuan baru dengan memilih:
  - Jenis pengajuan (izin sakit, dispensasi lomba, dll.)
  - Pengajaran (guru, kelas, mapel) yang terdampak
  - Tanggal dan keterangan
- Lihat daftar pengajuan sendiri beserta status terkini.
- Lihat detail dan riwayat perubahan status setiap pengajuan.

### 5.4 Pemeriksaan Pengajuan (Admin)
- Lihat semua pengajuan masuk dengan filter status.
- Ubah status ke `diperiksa` atau `dikembalikan` dengan catatan.
- Setiap perubahan status otomatis dicatat di tabel `riwayat_pengajuans`.

### 5.5 Persetujuan Pengajuan (Guru)
- Lihat pengajuan yang relevan (hanya kelas/mapel yang diajarkan oleh guru bersangkutan).
- Ubah status ke `disetujui` atau `ditolak` dengan catatan.
- Setiap perubahan status otomatis dicatat di tabel `riwayat_pengajuans`.

### 5.6 Riwayat Pengajuan
- Setiap perubahan status (status_lama → status_baru) dicatat bersama user yang melakukan aksi, catatan, dan timestamp.
- Dapat dilihat oleh siswa (miliknya), admin, dan guru (yang bersangkutan).

### 5.7 Dashboard
- Admin: ringkasan jumlah pengajuan per status, data siswa aktif.
- Guru: ringkasan pengajuan masuk untuk kelas/mapel yang diajarkan.
- Siswa: status pengajuan terbaru miliknya.

---

## 6. Struktur Database

| Tabel               | Deskripsi |
|---------------------|-----------|
| `users`             | Akun login semua role (admin, guru, siswa) |
| `kelas`             | Data kelas dan jurusan |
| `students`          | Data detail siswa, terhubung ke `users` dan `kelas` |
| `mata_pelajarans`   | Master data mata pelajaran |
| `pengajarans`       | Relasi guru (user) + kelas + mata pelajaran (unique per kombinasi) |
| `jenis_pengajuans`  | Master jenis pengajuan (izin sakit, dispensasi, dll.) |
| `pengajuans`        | Tabel utama pengajuan siswa |
| `riwayat_pengajuans`| Audit trail setiap perubahan status pengajuan |

---

## 7. Relasi Antar Entitas

```
users ──────────── students (1:1, via user_id)
users ──────────── pengajarans (1:N, via guru_id)
users ──────────── riwayat_pengajuans (1:N, via user_id)

kelas ──────────── students (1:N)
kelas ──────────── pengajarans (1:N)

mata_pelajarans ── pengajarans (1:N)

students ────────── pengajuans (1:N)
pengajarans ─────── pengajuans (1:N)
jenis_pengajuans ── pengajuans (1:N)

pengajuans ─────── riwayat_pengajuans (1:N)
```

---

## 8. Aturan Bisnis

1. Satu siswa hanya dapat memiliki **satu akun user** (`users.role = 'siswa'`).
2. Kombinasi `guru_id + kelas_id + mata_pelajaran_id` di tabel `pengajarans` harus **unik**.
3. Siswa hanya dapat mengajukan pengajuan jika statusnya **`aktif`**.
4. Guru hanya dapat melihat dan memproses pengajuan yang terkait dengan **pengajaran yang mereka ampu**.
5. Setiap perubahan status **wajib** dicatat ke `riwayat_pengajuans` secara otomatis.
6. Status pengajuan hanya bisa bergerak sesuai alur yang ditentukan (tidak bisa skip atau mundur sembarangan).
7. Admin tidak dapat langsung menyetujui/menolak; hanya bisa `diperiksa` atau `dikembalikan`.
8. Guru tidak dapat mengubah status yang masih `diajukan`; hanya bisa memproses yang sudah `diperiksa`.

---

## 9. Non-Functional Requirements

| Aspek        | Ketentuan |
|--------------|-----------|
| Autentikasi  | Session-based via Laravel Auth (Breeze) |
| Otorisasi    | Middleware role-based per grup route |
| Validasi     | Form Request Validation di setiap controller |
| UI Framework | Tailwind CSS |
| Build Tool   | Vite |
| Test Runner  | Pest PHP |
| Code Style   | Laravel Pint |

---

## 10. Out of Scope (Versi 1.0)

- Notifikasi email atau push notification.
- Upload lampiran/dokumen pendukung pengajuan.
- Laporan/export data ke PDF atau Excel.
- Multi-tahun ajaran.
- API untuk aplikasi mobile.

---

## 11. Milestone Pengembangan

| Fase | Deskripsi | Status |
|------|-----------|--------|
| 1    | Setup project, autentikasi, migrasi database, model & relasi | ✅ Selesai |
| 2    | Manajemen data master (admin): kelas, mapel, guru, siswa | 🔄 In Progress |
| 3    | Fitur pengajuan siswa (buat, lihat, riwayat) | ⏳ Belum Mulai |
| 4    | Fitur pemeriksaan admin | ⏳ Belum Mulai |
| 5    | Fitur persetujuan guru | ⏳ Belum Mulai |
| 6    | Dashboard per role | ⏳ Belum Mulai |
| 7    | Testing & QA | ⏳ Belum Mulai |
