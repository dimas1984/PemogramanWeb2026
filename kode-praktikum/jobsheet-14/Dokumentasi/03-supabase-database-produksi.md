# 3. Supabase: Database PostgreSQL di Internet

Sesuai [bab 1 §1.7](01-konsep-dasar-hosting-container.md#17-kenapa-databasenya-terpisah-supabase),
database harus hidup **di luar** container Render. Bab ini menyiapkannya.

> Tampilan dashboard Supabase cukup sering berubah. Yang perlu kamu
> pegang adalah **apa yang sedang dicari** di tiap langkah, bukan nama
> tombolnya. Setiap langkah di bawah menyebutkan tujuannya.

## 3.1 Apa itu Supabase?

Supabase adalah layanan **PostgreSQL terkelola** (*managed database*):
mereka yang memasang, menjalankan, mencadangkan, dan memperbarui
PostgreSQL — kamu tinggal memakai databasenya.

Kata kuncinya: **PostgreSQL asli**, bukan tiruan. Konsekuensi
menyenangkannya, semua yang kamu pelajari sejak
[jobsheet-08](../../jobsheet-08/Dokumentasi/01-konsep-dasar-database-sql.md)
berlaku persis:

- berkas `sql/01`–`sql/03` jalan apa adanya;
- semua query `SELECT`, `INSERT`, `JOIN`, dan `TRANSACTION` di modul
  Peminjaman ([jobsheet-12](../../jobsheet-12/Dokumentasi/03-peminjaman-baru-dan-transaction.md))
  tidak perlu diubah satu huruf pun;
- driver `pdo_pgsql` yang sama tetap dipakai.

Supabase juga menawarkan banyak fitur lain (Auth, Storage, REST API
otomatis). **Semuanya kita abaikan.** SIMPUS-Mini sudah punya sistem
autentikasi sendiri sejak
[jobsheet-10](../../jobsheet-10/Dokumentasi/01-konsep-dasar-autentikasi.md).
Kita hanya butuh satu hal dari Supabase: sebuah database PostgreSQL yang
menyala terus.

## 3.2 Langkah 1 — Membuat Project

1. Buka [supabase.com](https://supabase.com) → **Sign in with GitHub**.
2. Buat project baru (*New project*).
3. Isi yang diminta:

| Isian | Saran | Alasan |
|---|---|---|
| **Name** | `simpus-mini` | Bebas, sekadar penanda |
| **Database Password** | acak & panjang | **Salin dan simpan sekarang** |
| **Region** | Singapore | Paling dekat dari Indonesia → lebih cepat |
| **Plan** | Free | Cukup untuk keperluan ini |

> **Password itu hanya ditampilkan sekali.** Kalau hilang, kamu harus
> mengaturnya ulang dari *Settings → Database*. Simpan sementara di
> tempat aman — dan ingat pesan
> [jobsheet-13](../../jobsheet-13/Dokumentasi/01-konsep-dasar-deployment-config.md):
> **jangan pernah** menuliskannya ke dalam kode lalu di-commit.

Proses penyiapan memakan waktu 1–2 menit.

## 3.3 Langkah 2 — Menjalankan Skema Database

Cari menu **SQL Editor** di sisi kiri. Ini pengganti `psql` yang kamu
pakai di
[jobsheet-08](../../jobsheet-08/Dokumentasi/03-persiapan-database.md),
hanya saja berbentuk halaman web.

Jalankan isi ketiga berkas SQL-mu **berurutan** — buka tiap berkas dari
folder [`sql/`](../sql/), salin seluruh isinya, tempel, lalu *Run*:

| Urutan | Berkas | Isi |
|---|---|---|
| 1 | [`sql/01_buku_anggota.sql`](../sql/01_buku_anggota.sql) | Tabel `buku` & `anggota` |
| 2 | [`sql/02_users.sql`](../sql/02_users.sql) | Tabel `users` |
| 3 | [`sql/03_peminjaman.sql`](../sql/03_peminjaman.sql) | Tabel `peminjaman` |

**Urutannya wajib dipatuhi.** Tabel `peminjaman` punya *foreign key* ke
`buku` dan `anggota` (lihat
[jobsheet-12 bab 2](../../jobsheet-12/Dokumentasi/02-skema-peminjaman-dan-relasi.md)).
PostgreSQL menolak membuat referensi ke tabel yang belum ada, dengan
pesan seperti `relation "buku" does not exist`.

Setelah selesai, buka menu **Table Editor** — mestinya terlihat empat
tabel: `buku`, `anggota`, `users`, `peminjaman`.

## 3.4 Langkah 3 — Mengambil Connection String

Cari tombol **Connect** (biasanya di bagian atas dashboard). Akan
muncul beberapa pilihan cara menyambung. **Pilihan inilah bagian
terpenting di seluruh bab ini.**

Kamu akan melihat kira-kira dua kelompok:

| Jenis | Bentuk host | Port |
|---|---|---|
| **Direct connection** | `db.<project-ref>.supabase.co` | `5432` |
| **Connection pooling** / Transaction pooler | `aws-0-<region>.pooler.supabase.com` | `6543` |

**Pilih Connection pooling (port `6543`).** Jangan yang direct.

## 3.5 Kenapa Harus Pooler, Bukan Koneksi Langsung?

Dua alasan, keduanya nyata.

**Pertama, soal jaringan.** Koneksi langsung ke `db.<ref>.supabase.co`
pada paket gratis hanya tersedia lewat **IPv6**. Banyak jaringan —
termasuk lingkungan tempat Render menjalankan container — tidak selalu
bisa menjangkaunya. Host pooler bisa dijangkau lewat IPv4, sehingga jauh
lebih aman dipakai.

**Kedua, soal jumlah koneksi.** Perhatikan kembali
[`includes/koneksi.php`](../includes/koneksi.php): berkas itu di-`require`
di **setiap** halaman, dan membuat koneksi PDO **baru** setiap kali ada
pengunjung membuka halaman. Pola ini sudah kamu pakai sejak
jobsheet-08 dan tidak masalah di lokal.

Tapi PostgreSQL membatasi jumlah koneksi yang boleh terbuka bersamaan,
dan paket gratis Supabase memberi jatah yang kecil. **Connection
pooler** adalah perantara: ia memelihara sejumlah kecil koneksi ke
database, lalu meminjamkannya bergantian ke banyak permintaan. Aplikasimu
merasa punya koneksi sendiri, padahal sedang antre memakai kolam yang
sama.

```
Tanpa pooler:          Dengan pooler:
 halaman 1 --\          halaman 1 --\
 halaman 2 ---> DB      halaman 2 ---> POOLER ==> DB
 halaman 3 --/          halaman 3 --/            (sedikit koneksi,
 (tiap halaman                                    dipakai bergantian)
  1 koneksi penuh)
```

## 3.6 Memetakan Connection String ke Environment Variable

Connection string yang Supabase berikan berbentuk satu baris panjang:

```
postgresql://postgres.abcdefghijklmnop:PASSWORD@aws-0-ap-southeast-1.pooler.supabase.com:6543/postgres
```

Aplikasi kita tidak memakainya utuh — [`includes/config.php`](../includes/config.php)
membaca nilai per bagian. Bongkar baris itu seperti ini:

```
postgresql:// postgres.abcdefghijklmnop : PASSWORD @ aws-0-...supabase.com : 6543 / postgres
              └──── DB_USER ─────────┘   └DB_PASS┘   └────── DB_HOST ────┘  └DB_PORT┘ └DB_NAME┘
```

| Variabel | Nilainya | Catatan |
|---|---|---|
| `DB_USER` | `postgres.abcdefghijklmnop` | **Termasuk titik dan kode project.** Bukan `postgres` saja |
| `DB_PASS` | password dari §3.2 | |
| `DB_HOST` | `aws-0-ap-southeast-1.pooler.supabase.com` | Host **pooler** |
| `DB_PORT` | `6543` | |
| `DB_NAME` | `postgres` | Supabase memakai nama itu, bukan `simpus_mini` |
| `DB_SSLMODE` | `require` | Dibahas di §3.7 |

**Dua kesalahan yang paling sering terjadi:**

1. Mengisi `DB_USER` dengan `postgres` saja. Pooler memakai bagian
   `.abcdefghijklmnop` untuk mengetahui project mana yang kamu tuju —
   tanpa itu autentikasi gagal.
2. Mengisi `DB_NAME` dengan `simpus_mini` karena terbiasa dari lokal.
   Nama database di Supabase adalah `postgres`. Tabel-tabelmu ada di
   dalamnya.

## 3.7 Tiga Penyesuaian di `koneksi.php`

Ini satu-satunya berkas logika yang berubah dari jobsheet-13. Bandingkan
[versi jobsheet-13](../../jobsheet-13/includes/koneksi.php) dengan
[versi jobsheet-14](../includes/koneksi.php).

### 3.7.1 `sslmode` — Mengenkripsi Perjalanan Data

```php
$dsn = "pgsql:host={$config['db_host']};port={$config['db_port']};"
     . "dbname={$config['db_name']};sslmode={$config['db_sslmode']}";
```

Di lokal, PHP dan PostgreSQL ada di komputer yang sama — datanya tidak
ke mana-mana. Di produksi, data berjalan dari server Render ke server
Supabase **melewati internet publik**. Tanpa enkripsi, username, password,
dan isi tabelmu lewat dalam bentuk terbaca.

`sslmode=require` memerintahkan driver menolak tersambung kalau
sambungannya tidak terenkripsi. Ini melengkapi pelajaran keamanan di
[jobsheet-11](../../jobsheet-11/Dokumentasi/01-konsep-dasar-keamanan-web.md):
di sana kamu mengamankan data **di dalam** aplikasi (XSS, CSRF, SQL
injection), di sini kamu mengamankan data **dalam perjalanan**.

### Kenapa nilainya `prefer`, bukan `require`, di `config.php`?

```php
'db_sslmode' => getenv('DB_SSLMODE') ?: 'prefer',
```

Kalau nilai cadangannya `require`, folder ini **gagal dijalankan di
Laragon** — PostgreSQL lokal umumnya tidak mengaktifkan SSL, sehingga
koneksi ditolak sebelum sempat mencoba.

`prefer` berarti: "coba SSL dulu; kalau server tidak mendukung, lanjutkan
tanpa SSL." Jadi di lokal tetap jalan, dan di Render kamu **menimpanya**
dengan `require` lewat environment variable. Inilah gunanya pola nilai
cadangan dari
[jobsheet-13 §1.4](../../jobsheet-13/Dokumentasi/01-konsep-dasar-deployment-config.md):
satu kode sumber, dua perilaku, tanpa mengedit berkas.

### 3.7.2 `PDO::ATTR_EMULATE_PREPARES` — Berdamai dengan Pooler

```php
[PDO::ATTR_EMULATE_PREPARES => true]
```

Ingat *prepared statement* dari
[jobsheet-08 bab 5](../../jobsheet-08/Dokumentasi/05-insert-prepared-statement.md)?
Secara bawaan, driver PostgreSQL mengirim query bertanda `?` ke server
untuk "didaftarkan" dulu dengan sebuah nama, baru kemudian dijalankan
dengan nilainya.

Masalahnya, pooler mode transaksi (§3.5) memindah-mindahkan koneksi
antar permintaan. Query yang didaftarkan lewat satu koneksi bisa jadi
dijalankan lewat koneksi lain yang belum mengenalnya — memicu error
yang muncul sesekali dan membingungkan.

`EMULATE_PREPARES => true` membuat PHP menyusun query lengkap di sisi
klien, lalu mengirimnya sebagai satu perintah utuh.

**Apakah ini mengurangi keamanan dari SQL injection?** Tidak. Nilai tetap
di-*escape* oleh driver PDO, bukan kamu sambung sendiri dengan titik.
Yang berubah hanya **di mana** penyusunan akhirnya terjadi — di PHP,
bukan di PostgreSQL. Seluruh `prepare()`/`execute()` di kodemu tetap
ditulis persis seperti sebelumnya, dan tetap aman. Yang tetap berbahaya
juga tetap sama: menyambung input pengguna langsung ke string query.

### 3.7.3 Pesan Error yang Tidak Membocorkan Kredensial

Versi jobsheet-13:

```php
die("Koneksi database gagal: " . $e->getMessage());
```

Terlihat tidak berbahaya, dan memang membantu saat belajar di lokal.
Tapi di produksi, `$e->getMessage()` dari PDO memuat **host, nama
database, dan username** — persis nilai rahasia yang susah payah kamu
pindahkan ke environment variable di jobsheet-13. Begitu Supabase
sedang bermasalah, semua itu tercetak di layar untuk siapa pun yang
kebetulan membuka situsmu.

Versi jobsheet-14:

```php
error_log('Koneksi database gagal: ' . $e->getMessage());
http_response_code(500);

if ($config['app_debug']) {
    die('Koneksi database gagal: ' . $e->getMessage());
}

die('Koneksi database gagal. Silakan hubungi administrator.');
```

Tiga perbaikan sekaligus:

- **`error_log()`** — pesan lengkapnya tetap tersimpan, tapi di log
  server (bisa kamu baca di tab *Logs* Render), bukan di layar
  pengunjung.
- **`http_response_code(500)`** — memberi tahu browser dan mesin
  pencari bahwa ini kegagalan server, bukan halaman biasa. Sebelumnya
  `die()` polos mengirim status `200 OK` — seolah semuanya baik-baik saja.
- **`app_debug`** — pintu keluar yang disengaja. Saat mencari masalah,
  set `APP_DEBUG=true` untuk sementara agar pesan aslinya tampil, lalu
  **hapus lagi** begitu selesai.

## 3.8 Rangkuman Bab Ini

1. Supabase = PostgreSQL asli → skema & query dari jobsheet-08 s.d. 12
   berjalan tanpa diubah.
2. Jalankan `sql/01` → `02` → `03` **berurutan** lewat SQL Editor.
3. Pakai **connection pooling port `6543`**, bukan koneksi langsung
   (alasan: IPv4 & hemat koneksi).
4. `DB_USER` berformat `postgres.<project-ref>`; `DB_NAME` adalah
   `postgres`, bukan `simpus_mini`.
5. Tiga penyesuaian `koneksi.php`: **SSL** (data terenkripsi di jalan),
   **EMULATE_PREPARES** (damai dengan pooler), **pesan error aman**
   (kredensial tidak bocor).

Lanjut ke: [Deploy ke Render: Langkah demi Langkah](04-deploy-ke-render.md)
