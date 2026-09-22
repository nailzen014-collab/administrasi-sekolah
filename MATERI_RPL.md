# Penerapan Materi RPL – Sistem Administrasi Sekolah

**Mata Pelajaran:** Rekayasa Perangkat Lunak (RPL)  
**Tingkat:** XI  
**Project:** Sistem Administrasi Sekolah — Pengajuan Izin & Dispensasi  
**Tech Stack:** Laravel 13 (brand: NOVARA), PHP 8.3, Tailwind CSS

---

## 1. Introduction Laravel 13

**Pengertian:**  
Laravel adalah framework PHP yang menyediakan struktur dan alat bantu untuk membangun aplikasi web dengan cepat dan terorganisir. Laravel mengikuti pola arsitektur MVC (Model-View-Controller) yang memisahkan logika bisnis, tampilan, dan pengelolaan data.

**Penerapan di project ini:**  
Seluruh aplikasi Sistem Administrasi Sekolah dibangun di atas Laravel versi 13. Struktur direktori project mengikuti konvensi Laravel: logika bisnis berada di folder `app/`, tampilan halaman berada di `resources/views/`, konfigurasi database berada di `database/`, dan semua route atau alur URL didefinisikan di `routes/`. Perintah `php artisan` digunakan untuk berbagai keperluan seperti menjalankan migrasi database, mengisi data awal (seeding), dan menjalankan server lokal.

---

## 2. Laravel – Create, Read, Update, Delete (CRUD)

**Pengertian:**  
CRUD adalah empat operasi dasar yang dilakukan terhadap data dalam sebuah sistem: membuat data baru (Create), membaca atau menampilkan data (Read), memperbarui data yang sudah ada (Update), dan menghapus data (Delete). Dalam Laravel, CRUD biasanya diimplementasikan melalui controller yang terhubung dengan route resource.

**Penerapan di project ini:**  
Admin memiliki kemampuan CRUD penuh terhadap dua jenis data utama:

- **Data Siswa** — Admin dapat menambah siswa baru (Create), melihat daftar dan detail siswa (Read), mengubah informasi seperti kelas dan status siswa (Update), serta menghapus akun siswa yang tidak lagi aktif (Delete).
- **Data Guru** — Admin dapat mendaftarkan akun guru baru (Create), melihat daftar guru beserta mata pelajaran yang diajarkan (Read), mengubah informasi guru termasuk mereset kata sandi (Update), dan menghapus akun guru yang tidak memiliki data pengajaran aktif (Delete).

Untuk pengajuan, siswa dapat membuat pengajuan baru (Create) dan melihat daftar serta detail pengajuannya (Read), namun tidak dapat mengubah atau menghapus pengajuan yang sudah dikirim.

---

## 3. Authentication dan Authorization

**Pengertian:**  
- **Authentication (Autentikasi)** adalah proses verifikasi identitas pengguna — memastikan bahwa orang yang mencoba masuk ke sistem adalah benar-benar siapa yang mereka klaim. Biasanya dilakukan melalui kombinasi email dan kata sandi.
- **Authorization (Otorisasi)** adalah proses menentukan hak akses — setelah pengguna berhasil login, sistem memutuskan halaman atau fitur apa saja yang boleh mereka akses berdasarkan peran (role) mereka.

**Penerapan di project ini:**  
Autentikasi dibangun menggunakan Laravel Breeze. Setiap pengguna (admin, guru, siswa) login melalui halaman yang sama dengan email dan kata sandi. Sistem kemudian memeriksa identitas tersebut di database.

Setelah login, otorisasi bekerja dalam dua lapisan:
1. **Middleware Role** — Setiap kelompok halaman dijaga oleh middleware yang memeriksa role pengguna. Halaman admin hanya bisa diakses oleh admin, halaman guru hanya oleh guru, dan halaman siswa hanya oleh siswa. Jika role tidak sesuai, sistem menampilkan halaman error 403 (Forbidden).
2. **Policy** — Untuk pemeriksaan yang lebih detail, digunakan Policy. Misalnya, guru hanya boleh melihat pengajuan dari kelas yang ia ajar, bukan semua pengajuan di seluruh sekolah. Siswa hanya boleh melihat pengajuan miliknya sendiri.

Selain itu, terdapat mekanisme `must_change_password` — akun baru (guru dan siswa) diwajibkan mengganti kata sandi saat pertama kali login sebelum bisa mengakses fitur lain.

---

## 4. Eloquent Relationship

**Pengertian:**  
Eloquent adalah ORM (Object-Relational Mapping) bawaan Laravel yang memungkinkan pengembang berinteraksi dengan database menggunakan objek PHP, bukan query SQL langsung. Eloquent Relationship mendefinisikan bagaimana satu tabel/model berhubungan dengan tabel/model lain, misalnya satu siswa memiliki banyak pengajuan, atau satu pengajuan dimiliki oleh satu siswa.

**Penerapan di project ini:**  
Project ini memiliki beberapa jenis relasi antar data:

- **Satu siswa dimiliki oleh satu akun user** — Setiap akun di tabel `users` dengan role siswa memiliki satu data profil di tabel `students`.
- **Satu siswa bisa memiliki banyak pengajuan** — Setiap siswa dapat mengajukan izin atau dispensasi berkali-kali.
- **Satu pengajuan dimiliki oleh satu siswa** — Setiap pengajuan tercatat atas nama satu siswa tertentu.
- **Satu pengajuan memiliki banyak riwayat status** — Setiap kali status pengajuan berubah, catatan perubahan tersebut tersimpan di tabel `riwayat_pengajuans`.
- **Satu pengajaran diajar oleh satu guru** — Data pengajaran menghubungkan guru, kelas, dan mata pelajaran menjadi satu relasi.

Relasi-relasi ini memungkinkan sistem menampilkan data yang saling terhubung, misalnya menampilkan nama siswa, kelas, dan guru terkait di satu halaman detail pengajuan.

---

## 5. Middleware Laravel

**Pengertian:**  
Middleware adalah lapisan perantara yang berjalan sebelum sebuah request HTTP sampai ke controller. Middleware dapat memeriksa kondisi tertentu dan memutuskan apakah request boleh dilanjutkan atau harus dihentikan/diarahkan ke tempat lain.

**Penerapan di project ini:**  
Project ini menggunakan tiga middleware utama:

1. **Auth Middleware** — Memastikan pengguna sudah login sebelum mengakses halaman manapun selain halaman login. Jika belum login, pengguna otomatis diarahkan ke halaman login.

2. **Role Middleware** — Memeriksa role pengguna yang sedang login. Jika seorang guru mencoba mengakses halaman admin (misalnya `/admin/students`), middleware ini akan menghentikan akses dan menampilkan error 403. Begitu pula sebaliknya.

3. **Force Password Change Middleware** — Memeriksa apakah pengguna memiliki flag `must_change_password`. Jika ya, pengguna diarahkan ke halaman profil untuk mengganti kata sandi terlebih dahulu. Satu-satunya halaman yang bisa diakses selama kondisi ini adalah halaman profil dan halaman ganti kata sandi itu sendiri.

---

## 6. Search, Filter dan Paginate

**Pengertian:**  
- **Search (Pencarian)** adalah fitur yang memungkinkan pengguna mencari data berdasarkan kata kunci tertentu, seperti mencari nama siswa.
- **Filter** adalah fitur untuk menyaring data berdasarkan kriteria tertentu, seperti menampilkan hanya siswa dari kelas tertentu atau pengajuan dengan status tertentu.
- **Paginate (Paginasi)** adalah teknik membagi data dalam jumlah besar menjadi halaman-halaman kecil agar tampilan tidak terlalu panjang dan performa tetap terjaga.

**Penerapan di project ini:**  
Fitur ini diterapkan di beberapa halaman utama:

- **Halaman Data Siswa (Admin)** — Admin dapat mencari siswa berdasarkan nama, NIS, atau NISN. Admin juga dapat memfilter berdasarkan kelas, jurusan, dan status siswa. Data ditampilkan 20 siswa per halaman.
- **Halaman Data Guru (Admin)** — Admin dapat mencari guru berdasarkan nama atau email, dan memfilter berdasarkan status akun.
- **Halaman Pemeriksaan Pengajuan (Admin)** — Admin dapat melihat pengajuan dan memfilter berdasarkan status.
- **Halaman Pengajuan Saya (Siswa)** — Siswa dapat memfilter pengajuan miliknya berdasarkan status (semua, diajukan, diperiksa, dikembalikan, disetujui, ditolak).

Parameter filter dan pencarian dipertahankan saat berpindah halaman pagination, sehingga pengguna tidak perlu mengisi ulang filter setelah pindah ke halaman berikutnya.

---

## 7. Handout Search, Filter dan Paginate

**Pengertian Tambahan:**

- **Query Builder** adalah cara Laravel membangun query database secara bertahap dan kondisional. Ini memungkinkan filter yang hanya diterapkan jika pengguna mengisi nilai tertentu, tanpa perlu menulis banyak percabangan `if/else`.
- **`withQueryString()`** adalah metode yang memastikan semua parameter URL (seperti kata kunci pencarian dan filter) ikut terbawa saat pengguna mengklik halaman berikutnya di paginasi.
- **`LengthAwarePaginator`** adalah objek hasil paginasi Laravel yang sudah berisi informasi total data, jumlah halaman, halaman aktif saat ini, dan tautan navigasi antar halaman.

**Penerapan di project ini:**  
Ketika admin membuka halaman data siswa dan mengetik nama di kolom pencarian lalu memilih filter kelas, sistem membangun query database secara bertahap: pertama ambil semua siswa, lalu tambahkan kondisi pencarian nama jika diisi, lalu tambahkan filter kelas jika dipilih, dan seterusnya. Hasilnya kemudian dipaginasi. Jika admin berada di halaman 2 dan ingin ke halaman 3, parameter pencarian dan filter tetap terbawa di URL sehingga hasilnya konsisten.

---

## 8. Form Request dan Validation

**Pengertian:**  
- **Validation (Validasi)** adalah proses memeriksa apakah data yang dikirimkan oleh pengguna melalui form sudah memenuhi aturan yang ditetapkan. Misalnya, memastikan email diisi dengan format yang benar, kata sandi minimal 8 karakter, atau NIS belum pernah digunakan.
- **Form Request** adalah class khusus di Laravel yang memisahkan logika validasi dari controller, sehingga kode lebih rapi dan mudah dikelola.

**Penerapan di project ini:**  
Setiap form yang ada di aplikasi ini dilindungi oleh validasi:

- **Form Tambah Siswa** — Memvalidasi bahwa nama, email, NIS wajib diisi; email harus unik di seluruh database; NIS dan NISN juga harus unik; dan kelas harus dipilih dari data yang ada.
- **Form Ubah Siswa** — Sama seperti form tambah, namun aturan keunikan dikecualikan untuk data siswa yang sedang diedit (agar tidak error saat menyimpan tanpa mengubah email).
- **Form Buat Pengajuan** — Memvalidasi bahwa jenis pengajuan, pengajaran, tanggal, dan keterangan wajib diisi. Tanggal tidak boleh di masa lalu. Pengajaran yang dipilih harus memang terkait dengan kelas siswa tersebut.

Jika validasi gagal, pengguna dikembalikan ke form dengan pesan error yang spesifik di setiap field yang bermasalah.

---

## 9. N+1 Query Problem

**Pengertian:**  
N+1 Query Problem adalah masalah performa database yang terjadi ketika sebuah halaman membutuhkan 1 query untuk mengambil daftar data utama, lalu untuk setiap baris data tersebut dijalankan 1 query tambahan untuk mengambil data relasinya. Jika ada 20 baris data, total query yang dijalankan menjadi 1 + 20 = 21 query — yang seharusnya bisa diselesaikan hanya dengan 2–3 query.

**Contoh N+1 yang mungkin terjadi di halaman Data Siswa:**  
Halaman daftar siswa menampilkan nama siswa (dari tabel `users`), kelas (dari tabel `kelas`), dan jurusan. Jika sistem tidak mengambil data relasi secara efisien, maka untuk setiap dari 20 siswa yang ditampilkan, sistem akan menjalankan query terpisah ke tabel `users` untuk nama, dan query lagi ke tabel `kelas` untuk nama kelasnya. Total: 1 query untuk daftar siswa + 20 query nama + 20 query kelas = **41 query hanya untuk menampilkan 1 halaman**.

**Solusi yang diterapkan — Eager Loading:**  
Di project ini, masalah N+1 dihindari dengan teknik **Eager Loading**, yaitu mengambil semua data relasi yang dibutuhkan sekaligus dalam satu instruksi sebelum data ditampilkan. Hasilnya, 20 siswa beserta nama dan kelasnya dapat ditampilkan hanya dengan **3 query**, berapapun jumlah datanya.

Teknik ini diterapkan di:
- **Halaman Data Siswa** — Data user (nama) dan kelas diambil sekaligus bersama data siswa.
- **Dashboard Admin** — Data student, user, jenis pengajuan, pengajaran, guru, dan mata pelajaran untuk 10 pengajuan terbaru semuanya diambil sekaligus, bukan satu per satu.
- **Halaman Detail Pengajuan** — Seluruh riwayat perubahan status beserta nama user yang melakukan aksi diambil dalam satu instruksi.
- **Halaman Data Guru** — Jumlah pengajaran per guru dihitung langsung di database tanpa harus memuat seluruh data pengajaran ke memori.

---

## 10. Handout N+1 Query Problem

**Konsep Pendukung:**

- **Lazy Loading** adalah perilaku default Eloquent di mana relasi baru diambil dari database pada saat relasi tersebut pertama kali diakses. Ini menjadi penyebab N+1 jika dilakukan di dalam loop.

- **Eager Loading** adalah teknik mengambil semua relasi yang dibutuhkan sekaligus sebelum data diproses. Cukup 1–2 query tambahan untuk semua baris, bukan 1 query per baris.

- **Lazy Eager Loading** adalah versi eageer loading yang dilakukan setelah data utama sudah diambil, biasanya ketika kondisi loading relasi baru diketahui setelah query utama berjalan.

- **Aggregate di Database** — Daripada mengambil semua data relasi ke PHP lalu menghitungnya, lebih efisien memerintahkan database untuk menghitung langsung. Di project ini digunakan untuk menampilkan jumlah mata pelajaran yang diajar setiap guru di halaman daftar guru.

**Dampak jika N+1 dibiarkan:**  
Pada skala kecil (10–20 data), N+1 mungkin tidak terasa. Namun jika ada 100 siswa, 500 pengajuan, atau ratusan pengguna mengakses bersamaan, performa aplikasi akan menurun drastis karena database dibebani ratusan query yang tidak perlu.

---

## 11. Software Development Life Cycle (SDLC)

**Pengertian:**  
SDLC adalah rangkaian tahapan terstruktur yang dilalui dalam proses pengembangan sebuah perangkat lunak, dari tahap perencanaan awal hingga pemeliharaan setelah sistem diluncurkan. SDLC memastikan pengembangan dilakukan secara sistematis, terencana, dan dapat dipertanggungjawabkan.

**Penerapan di project ini:**

| Fase | Deskripsi | Wujud di Project |
|------|-----------|-----------------|
| **Perencanaan** | Menentukan tujuan, ruang lingkup, dan kebutuhan sistem | File `PRD.md` yang mendokumentasikan latar belakang, tujuan, dan fitur yang akan dibuat |
| **Analisis** | Mengidentifikasi kebutuhan pengguna secara detail | Identifikasi 3 role (admin, guru, siswa), alur status pengajuan 5 tahap, dan aturan bisnis di `PRD.md` |
| **Perancangan** | Merancang struktur sistem sebelum coding dimulai | Skema database di `database/migrations/`, diagram relasi antar tabel, dan alur status pengajuan |
| **Implementasi** | Penulisan kode program | Kode di `app/` (controller, model, middleware), tampilan di `resources/views/`, dan route di `routes/` |
| **Pengujian** | Memverifikasi sistem berjalan sesuai rencana | Suite pengujian otomatis di folder `tests/` menggunakan framework Pest PHP |
| **Deployment** | Menyiapkan dan menjalankan sistem di lingkungan nyata | Konfigurasi via file `.env`, perintah instalasi di `README.md`, dan script setup di `composer.json` |
| **Pemeliharaan** | Memantau dan menjaga sistem tetap berjalan dengan baik | Audit trail otomatis di tabel `riwayat_pengajuans` mencatat setiap perubahan untuk keperluan pelacakan masalah |

**Alur status pengajuan sebagai cerminan siklus iteratif:**  
Sistem memungkinkan pengajuan dikembalikan ke siswa untuk diperbaiki (`dikembalikan`), lalu diajukan ulang. Ini mencerminkan prinsip SDLC iteratif di mana sebuah produk dapat melalui beberapa siklus perbaikan sebelum diterima.

---

## 12. Assessmen Diagnostik Awal

**Pengertian:**  
Assessmen diagnostik awal adalah penilaian yang dilakukan di awal pembelajaran untuk mengukur sejauh mana peserta didik sudah memahami dasar-dasar yang dibutuhkan sebelum masuk ke materi inti. Hasilnya digunakan sebagai acuan untuk menentukan titik awal pembelajaran yang tepat.

**Kompetensi dasar yang diukur dan wujudnya di project ini:**

| Kompetensi Dasar | Indikator dalam Project |
|------------------|------------------------|
| Pemahaman PHP OOP | Seluruh controller, model, middleware, dan policy ditulis sebagai class dengan method, property, dan pewarisan |
| Pemahaman HTTP & REST | Penggunaan method HTTP yang tepat: GET untuk membaca, POST untuk membuat, PUT/PATCH untuk mengubah, DELETE untuk menghapus |
| Pemahaman Database | Relasi antar tabel, foreign key, unique constraint, dan enum didefinisikan dengan benar di file migrasi |
| Pemahaman HTML/Form | Tampilan halaman menggunakan Blade template dengan form yang terhubung ke route dan dilindungi CSRF token |
| Pemahaman MVC | Model mengelola data (`app/Models/`), View menampilkan antarmuka (`resources/views/`), Controller menjadi penghubung (`app/Http/Controllers/`) |
| Pemahaman Git | Struktur project menggunakan `.gitignore` untuk mengecualikan file sensitif, dan `README.md` menjelaskan konvensi branch dan commit |

---

## Ringkasan Peta Materi

| # | Materi | Lokasi Penerapan di Project |
|---|--------|-----------------------------|
| 1 | Introduction Laravel 13 | Seluruh struktur project, file `composer.json`, perintah `artisan` |
| 2 | CRUD | Halaman Data Siswa dan Data Guru di panel admin |
| 3 | Authentication & Authorization | Sistem login Breeze, Role Middleware, Policy pengajuan |
| 4 | Eloquent Relationship | Relasi antar model: `pengajuan`, `students`, `pengajaran`, `riwayat_pengajuan` |
| 5 | Middleware Laravel | `RoleMiddleware`, `ForcePasswordChange` |
| 6 | Search, Filter, Paginate | Halaman daftar siswa, guru, dan pengajuan |
| 7 | Handout Search, Filter, Paginate | Query kondisional dan `withQueryString()` di controller |
| 8 | Form Request & Validation | Validasi form tambah/ubah siswa dan buat pengajuan |
| 9 | N+1 Query Problem | Eager loading di `DashboardController` dan `StudentController` |
| 10 | Handout N+1 | Konsep lazy vs eager loading, aggregate di database |
| 11 | SDLC | `PRD.md`, `README.md`, migrasi database, suite pengujian |
| 12 | Assessmen Diagnostik | Seluruh codebase sebagai bukti kompetensi dasar PHP dan web |
