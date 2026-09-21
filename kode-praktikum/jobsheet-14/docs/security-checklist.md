# Checklist Keamanan — Jobsheet 11

Audit menyeluruh terhadap kode Jobsheet 7-10, dengan bukti before/after.

| # | Kerentanan | Ditemukan di | Sebelum | Sesudah (perbaikan) |
|---|---|---|---|---|
| 1 | SQL Injection | Semua query di `buku/`, `anggota/`, `auth/` | Sejak Jobsheet 8 sudah memakai prepared statement PDO (`:parameter`) | **Diaudit ulang, sudah aman** — tidak ada satupun query yang menyisipkan `$_POST`/`$_GET` langsung ke string SQL. Diuji input `' OR '1'='1` di form login → tidak berhasil bypass. |
| 2 | XSS (Cross-Site Scripting) | `buku/list.php`, `buku/edit.php`, `anggota/list.php`, `anggota/edit.php`, `includes/header.php` (nama petugas) | Output `judul`, `pengarang`, `nama`, `alamat`, `no_hp`, dan nilai pencarian (`q`) dicetak langsung tanpa escaping | Dibungkus fungsi `e()` (`includes/helpers.php`, `htmlspecialchars` dengan `ENT_QUOTES`). Diuji simpan judul buku `<script>alert(1)</script>` → tampil sebagai teks biasa, bukan dieksekusi. |
| 3 | CSRF (Cross-Site Request Forgery) | Form Tambah/Edit/Hapus Buku & Anggota, Login, Register | Form POST tidak memiliki token verifikasi — bisa dipicu dari situs lain | Ditambah `includes/csrf.php` (`csrf_field()` + `csrf_verify()`), token disimpan di `$_SESSION['csrf_token']`, diverifikasi di setiap `proses_*.php` dan `hapus.php` sebelum query dijalankan. |
| 4 | Validasi & Sanitasi Input | `proses_tambah.php`, `proses_edit.php` (buku & anggota) | Sudah ada validasi tipe (`is_numeric`) dan wajib-isi sejak Jobsheet 7-9 | **Diaudit ulang, tetap dipertahankan** — ditambah cast eksplisit `(int)` pada `id` di form Edit untuk mencegah nilai non-numerik masuk sebagai hidden input. |
| 5 | Session Fixation | `auth/proses_login.php` | Session ID tidak diperbarui setelah login | `session_regenerate_id(true)` dipanggil tepat setelah `password_verify()` berhasil. |

## Catatan Implementasi
- Guard `includes/auth.php` selalu dijalankan **sebelum** `includes/csrf.php` di halaman proses — memastikan pengguna yang belum login tidak bisa memicu pengecekan CSRF sama sekali (langsung di-redirect ke login).
- Fungsi `csrf_verify()` mengembalikan HTTP 403 dan menghentikan eksekusi (`die()`) bila token tidak cocok atau tidak ada — diverifikasi manual dengan mengirim POST tanpa `csrf_token` ke `proses_tambah.php` (setelah login).
