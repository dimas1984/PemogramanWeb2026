# Jobsheet 14 — Deployment ke Internet: Render + Supabase (SIMPUS-Mini)

> **Materi pengayaan, di luar RPS 13 pertemuan.** Jobsheet 13 menutup
> rangkaian resmi mata kuliah. Jobsheet 14 bersifat opsional, untuk
> mahasiswa yang ingin aplikasinya benar-benar bisa dibuka lewat
> internet — bukan hanya di `localhost`.

Sub-CPMK: Menjalankan aplikasi yang sudah selesai dibangun pada layanan
hosting publik, dengan database terkelola dan kredensial yang aman.

## Jobsheet 13 vs Jobsheet 14

Jobsheet 13 **menyiapkan** aplikasi agar *bisa* dipindah: kredensial
dikeluarkan dari kode sumber ke environment variable. Tapi aplikasinya
tetap hanya berjalan di komputermu. Jobsheet 14 benar-benar
**memindahkannya** ke internet:

| | Jobsheet 13 | Jobsheet 14 |
|---|---|---|
| Web server | `php -S localhost:8000` / Laragon | Render (container Docker) |
| Database | PostgreSQL di komputer sendiri | Supabase (PostgreSQL terkelola) |
| Alamat | `http://localhost:8000` | `https://<nama>.onrender.com` |
| Yang bisa membuka | hanya kamu | siapa pun yang punya tautannya |
| Biaya | — | Rp0 (free tier kedua layanan) |

## Perubahan Kode dari Jobsheet 13

Seluruh logika aplikasi — CRUD Buku/Anggota, autentikasi, CSRF, modul
Peminjaman — **identik** dengan jobsheet-13. Yang berubah hanya **2
file**, ditambah 3 berkas baru:

| Berkas | Status | Alasan |
|---|---|---|
| `includes/config.php` | Diubah | Tambah `db_sslmode` & `app_debug` |
| `includes/koneksi.php` | Diubah | SSL, pesan error aman, `EMULATE_PREPARES` |
| `Dockerfile` | Baru | Resep membangun container PHP + Apache + `pdo_pgsql` |
| `.dockerignore` | Baru | Berkas yang tak perlu ikut ke dalam image |
| `render.yaml` | Baru | Blueprint Render (opsional) |

Alasan tiap perubahan dibahas di
[`Dokumentasi/`](Dokumentasi/README.md).

## Ringkas: 5 Langkah Deployment

Langkah lengkap beserta penjelasannya ada di
[`Dokumentasi/`](Dokumentasi/README.md). Ini peta besarnya saja:

1. **Buat project Supabase** → jalankan `sql/01`, `02`, `03` berurutan
   lewat SQL Editor.
2. **Salin connection string** dari menu *Connect* — pakai **Connection
   pooling** (port `6543`), bukan koneksi langsung.
3. **Push repository ke GitHub** (repo ini sudah ada di GitHub).
4. **Buat Web Service di Render** → pilih repo, *Runtime* **Docker**,
   *Root Directory* `kode-praktikum/jobsheet-14`, plan **Free**.
5. **Isi environment variable** `DB_HOST`, `DB_PORT`, `DB_NAME`,
   `DB_USER`, `DB_PASS`, `DB_SSLMODE` → *Deploy*.

Setelah deploy berhasil, buka `https://<nama>.onrender.com/auth/register.php`
untuk mendaftarkan akun petugas pertama.

## Environment Variable yang Dibutuhkan

| Variabel | Contoh nilai (Supabase) | Keterangan |
|---|---|---|
| `DB_HOST` | `aws-0-ap-southeast-1.pooler.supabase.com` | Host **pooler**, bukan `db.<ref>.supabase.co` |
| `DB_PORT` | `6543` | Port pooler mode transaksi |
| `DB_NAME` | `postgres` | Supabase selalu memakai nama `postgres` |
| `DB_USER` | `postgres.abcdefghijklmnop` | Format `postgres.<project-ref>` |
| `DB_PASS` | *(password project Supabase)* | Jangan pernah di-commit |
| `DB_SSLMODE` | `require` | Supabase mewajibkan SSL |
| `APP_DEBUG` | *(kosongkan)* | Isi `true` hanya saat mencari masalah |

## Menjalankan Secara Lokal (tetap bisa)

Folder ini tetap bisa dijalankan di Laragon/`php -S` seperti jobsheet-13,
karena semua nilai di `includes/config.php` punya cadangan lokal
(`DB_SSLMODE` default `prefer`, yang otomatis mundur ke koneksi tanpa SSL
bila server lokal tidak mendukungnya):

```bash
php -S localhost:8000
```

## Dua Keterbatasan Free Tier yang Wajib Diantisipasi

1. **Render menidurkan aplikasi setelah 15 menit tanpa kunjungan.**
   Permintaan pertama sesudahnya butuh 30–60 detik untuk membangunkan
   container. Buka aplikasimu beberapa menit sebelum demo.
2. **Supabase menghentikan (*pause*) project gratis setelah 7 hari tanpa
   aktivitas database.** Data tidak hilang, tapi database menolak semua
   koneksi sampai di-*restore* manual dari dashboard.

Keduanya dibahas di
[bab 5](Dokumentasi/05-uji-coba-dan-troubleshooting.md).

## Status Pengujian — Baca Ini

Supaya tidak menyesatkan, berikut yang **sudah** dan **belum**
diverifikasi saat jobsheet ini disusun.

### Sudah diuji — kode aplikasinya

Diuji terhadap PostgreSQL 14.5 di Laragon (Windows), memakai
`includes/config.php` dan `includes/koneksi.php` versi jobsheet-14 ini
apa adanya:

| Yang diuji | Hasil |
|---|---|
| 30 berkas PHP lolos `php -l` | Lulus |
| `sslmode=prefer` mundur mulus ke server lokal tanpa SSL | Lulus |
| Prepared statement bernama (`:id`) dengan `EMULATE_PREPARES` | Lulus |
| CRUD Buku & Anggota | Lulus |
| Registrasi, `password_hash`, `password_verify` | Lulus |
| Transaksi peminjaman (`SELECT … FOR UPDATE` + `INSERT` + `UPDATE`) | Lulus |
| `rollBack` saat stok habis | Lulus |
| `JOIN` tiga tabel pada riwayat | Lulus |
| Ketahanan SQL injection (`' OR '1'='1`, `DROP TABLE`) | Lulus |
| Alur HTTP penuh: registrasi → login → session → tambah buku | Lulus |
| Token CSRF palsu ditolak `403` | Lulus |
| Akses tanpa login dialihkan ke halaman login | Lulus |
| Pesan error produksi tidak membocorkan kredensial | Lulus |
| `APP_DEBUG=true` menampilkan detail teknis | Lulus |

Artinya perubahan pada `koneksi.php` — SSL, `EMULATE_PREPARES`, dan
pesan error aman — **tidak merusak satu pun fungsi** yang dibangun
sejak jobsheet-07.

### Belum diuji — bagian deployment-nya

- **`Dockerfile` belum pernah di-*build*.** Docker tidak terpasang di
  lingkungan penyusunan dokumen ini.
- **Belum pernah benar-benar di-deploy ke Render**, dan **belum pernah
  disambungkan ke Supabase**. Karena itu nilai `DB_HOST` pooler, port
  `6543`, format `postgres.<project-ref>`, dan `sslmode=require` masih
  berupa anjuran berdasarkan dokumentasi resmi — bukan hasil
  pengamatan langsung.
- **Konsekuensinya:** langkah-langkah di [bab 3](Dokumentasi/03-supabase-database-produksi.md)
  dan [bab 4](Dokumentasi/04-deploy-ke-render.md) disusun mengikuti pola
  resmi kedua layanan, tapi **nama menu di antarmukanya bisa berbeda**
  dari yang tertulis karena keduanya sering berubah. Perlakukan sebagai
  peta, bukan transkrip layar. Bila menemukan perbedaan, catat — itu
  bagian dari latihan.

## Dokumentasi

Penjelasan lengkap tiap langkah: [`Dokumentasi/`](Dokumentasi/README.md)
