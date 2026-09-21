# 5. Uji Coba & Troubleshooting

Status service sudah **Live**. Bab ini memastikan aplikasinya benar-benar
berfungsi — dan membekalimu saat tidak.

## 5.1 Daftar Uji Setelah Deploy

Jangan berhenti di "halaman depan terbuka". Halaman depan bisa tampil
sempurna meski database sama sekali tidak tersambung. Telusuri urutan
ini:

| # | Yang diuji | Cara | Kalau berhasil berarti… |
|---|---|---|---|
| 1 | Apache & PHP hidup | Buka `https://<nama>.onrender.com/` | Container jalan, `Dockerfile` benar |
| 2 | **Database tersambung** | Lihat angka statistik di beranda | `pdo_pgsql`, SSL, & kredensial benar |
| 3 | Menulis ke database | Daftar petugas di `/auth/register.php` | `INSERT` & hash password jalan |
| 4 | **Session hidup** | Login di `/auth/login.php` | Inti alasan memilih Render |
| 5 | **CSRF hidup** | Tambah satu buku | Token bertahan antar permintaan |
| 6 | Relasi & transaksi | Buat satu peminjaman | `JOIN` & `TRANSACTION` jalan |
| 7 | Data benar-benar permanen | Cek **Table Editor** Supabase | Data tersimpan di luar container |

Langkah 4 dan 5 adalah pembuktian dari
[bab 1 §1.3](01-konsep-dasar-hosting-container.md#13-kenapa-simpus-mini-tidak-bisa-di-vercel):
keduanya persis yang akan gagal acak di hosting serverless. Di Render
keduanya jalan tanpa satu baris kode pun diubah.

Langkah 7 menutup pelajaran
[bab 1 §1.7](01-konsep-dasar-hosting-container.md#17-kenapa-databasenya-terpisah-supabase):
lakukan satu deploy ulang (ubah sedikit README lalu `git push`), tunggu
selesai, lalu buka lagi daftar bukumu. Datanya masih ada — karena
tinggal di Supabase, bukan di dalam container yang baru saja dibuang.

## 5.2 Membaca Log: Kebiasaan yang Menghemat Berjam-jam

Di lokal, pesan error PHP muncul di layar. Di produksi tidak — dan itu
memang disengaja ([bab 2 §2.4](02-dockerfile-php-apache.md#24-run-mv-phpini-production-phpini--mode-produksi)).
Gantinya, semuanya tercatat di tab **Logs** dashboard Render.

Dua jenis log yang berbeda, dan membedakannya mempersempit pencarian:

- **Build logs** — proses membangun image. Error di sini berarti
  masalah pada `Dockerfile`; aplikasinya bahkan belum sempat berjalan.
- **Runtime logs** — setelah container menyala. Error di sini berarti
  `Dockerfile` sudah benar, masalahnya di PHP atau koneksi database.
  Inilah tempat pesan `error_log()` dari
  [`includes/koneksi.php`](../includes/koneksi.php) muncul.

Aturan praktisnya: **gagal sebelum status Live → build log; gagal
sesudah Live → runtime log.**

## 5.3 Kamus Error yang Sering Muncul

### `could not find driver`

**Penyebab:** ekstensi `pdo_pgsql` tidak terpasang di image.

Periksa `Dockerfile` masih memuat baris `docker-php-ext-install pdo_pgsql`
([bab 2 §2.3](02-dockerfile-php-apache.md#23-run--docker-php-ext-install-pdo_pgsql--bagian-paling-krusial)),
dan pastikan **Runtime**-nya memang **Docker** — kalau Render salah
mengira ini proyek bahasa lain, `Dockerfile`-mu tidak pernah dibaca.

### `Dockerfile not found` / `no such file or directory`

**Penyebab:** *Root Directory* salah atau kosong.

Isi persis `kode-praktikum/jobsheet-14`, tanpa garis miring di depan
([bab 4 §4.4.1](04-deploy-ke-render.md#441-kenapa-root-directory-wajib-diisi)).

### `Port scan timeout` / `No open ports detected`

**Penyebab:** Apache mendengarkan port yang salah.

Ini gejala khas kesalahan kutip yang dibahas di
[bab 2 §2.6](02-dockerfile-php-apache.md#26-env-port-dan-konfigurasi-apache--bagian-yang-paling-membingungkan).
Pastikan `printf` memakai **kutip tunggal**, sehingga `${PORT}` tersimpan
sebagai teks dan diterjemahkan Apache saat container hidup — bukan
dibekukan jadi angka saat image dibangun.

### `password authentication failed for user "postgres"`

**Penyebab:** `DB_USER` hanya diisi `postgres`.

Pooler membutuhkan format `postgres.<project-ref>`
([bab 3 §3.6](03-supabase-database-produksi.md#36-memetakan-connection-string-ke-environment-variable)).
Perhatikan pesannya sendiri menyebut `user "postgres"` — itu petunjuk
bahwa kode project-nya hilang.

> **Kenapa error ini tidak pernah muncul saat latihan di Laragon?**
> PostgreSQL bawaan Laragon umumnya dipasang dengan metode autentikasi
> `trust`, yang **menerima koneksi tanpa benar-benar memeriksa
> password**. Jadi selama 13 jobsheet sebelumnya, salah password pun
> tidak pernah menimbulkan error. Supabase tentu tidak begitu. Inilah
> salah satu contoh perbedaan lingkungan lokal dan produksi yang baru
> terasa saat deployment — dan alasan kenapa "di komputerku jalan"
> bukan bukti yang cukup.

### `database "simpus_mini" does not exist`

**Penyebab:** `DB_NAME` diisi `simpus_mini` karena terbawa kebiasaan lokal.

Di Supabase nama databasenya `postgres`. Tabel-tabelmu ada di dalamnya.

### `relation "buku" does not exist`

**Penyebab:** berkas SQL belum dijalankan, atau urutannya tertukar.

Jalankan ulang `sql/01` → `02` → `03` **berurutan** lewat SQL Editor
([bab 3 §3.3](03-supabase-database-produksi.md#33-langkah-2--menjalankan-skema-database)).

### `SSL connection is required` / koneksi ditolak

**Penyebab:** `DB_SSLMODE` belum diisi `require` di Render.

### `Connection timed out` / `could not connect to server`

**Penyebab paling mungkin:** `DB_HOST` memakai koneksi langsung
(`db.<ref>.supabase.co`), bukan host pooler.

Ganti ke `aws-0-<region>.pooler.supabase.com` dengan port `6543`.
Alasannya di [bab 3 §3.5](03-supabase-database-produksi.md#35-kenapa-harus-pooler-bukan-koneksi-langsung).

**Kemungkinan kedua:** project Supabase-mu sedang *paused* — lihat §5.5.

### Halaman terbuka tapi tanpa gambar/warna (CSS tidak termuat)

**Penyebab:** salah jalur aset, bukan masalah deployment.

Ingat dari [jobsheet-13](../../jobsheet-13/README.md) bahwa
[`includes/header.php`](../includes/header.php) menghitung jalur CSS/JS
secara relatif berdasarkan kedalaman folder halaman. Buka *Developer
Tools* browser (F12) → tab *Network* untuk melihat jalur mana yang
dicari dan mana yang `404`.

### Perubahan kode tidak muncul di aplikasi

**Penyebab:** commit belum di-*push*, atau Render men-deploy branch lain.

Render hanya melihat apa yang sudah sampai di GitHub
([bab 4 §4.1](04-deploy-ke-render.md#41-bagaimana-render-mendapatkan-kodemu)).
Cek riwayat commit di GitHub, dan pastikan **Branch** di pengaturan
service sesuai.

## 5.4 Batas Pertama: Render Tidur Setelah 15 Menit

Pada paket gratis, container **dihentikan** setelah 15 menit tanpa
permintaan masuk. Kunjungan berikutnya membangunkannya kembali, tapi
butuh **30–60 detik** — layar tampak menggantung seperti error.

**Saat demo, ini yang paling mungkin mempermalukanmu.** Buka aplikasimu
2–3 menit sebelum mulai presentasi supaya sudah "panas".

Satu efek samping yang penting dipahami: saat container tidur lalu
bangun, container barunya **kosong dari berkas session**. Semua pengguna
yang tadinya login jadi ter-logout. Data tidak hilang sama sekali —
semuanya di Supabase — hanya status login yang perlu diulang.

Ini sekaligus memperjelas batas argumen di
[bab 1](01-konsep-dasar-hosting-container.md#13-kenapa-simpus-mini-tidak-bisa-di-vercel).
Render **tidak** membuat session abadi; ia membuat session **bertahan
selama container hidup**. Bedanya dengan serverless: di serverless
session bisa hilang **antara satu klik dan klik berikutnya**, sedangkan
di sini hanya hilang saat container benar-benar berganti. Yang pertama
membuat aplikasi tak bisa dipakai; yang kedua hanya merepotkan sesekali.

> Untuk aplikasi sungguhan yang butuh session tahan restart, session
> dipindahkan ke penyimpanan bersama — tabel di database atau Redis —
> lewat `session_set_save_handler()`. Itu di luar cakupan jobsheet ini;
> cukup tahu bahwa jalurnya ada.

## 5.5 Batas Kedua: Supabase Berhenti Setelah 7 Hari Menganggur

Project Supabase gratis **dihentikan** (*paused*) setelah kira-kira 7
hari tanpa aktivitas database. Gejalanya: aplikasi di Render tetap
terbuka, tapi setiap halaman yang menyentuh database gagal tersambung.

Datamu **tidak hilang** — hanya dibekukan. Cara menghidupkannya: buka
dashboard Supabase, cari tombol *Restore*/*Resume* pada project itu,
tunggu beberapa menit.

**Untuk demo UAS, ini risiko yang nyata.** Kalau kamu deploy hari ini
lalu demo tiga minggu lagi, hampir pasti project-nya sudah *paused*.
Dua pencegahan:

1. Buka aplikasimu minimal **seminggu sekali** — satu kunjungan ke
   beranda sudah memicu query dan menghitung sebagai aktivitas.
2. Periksa dan *restore* **sehari sebelum** demo, bukan pada hari-H.

## 5.6 Checklist Sebelum Demo

- [ ] Project Supabase berstatus **aktif**, bukan *paused* (§5.5)
- [ ] Aplikasi dibuka 2–3 menit sebelumnya agar sudah bangun (§5.4)
- [ ] `APP_DEBUG` **sudah dihapus** dari environment variable Render
      ([bab 4 §4.5](04-deploy-ke-render.md#45-langkah-4--mengisi-environment-variable))
- [ ] Akun petugas sudah terdaftar dan passwordnya kamu ingat
- [ ] Ada data contoh: beberapa buku, anggota, dan satu peminjaman aktif
- [ ] Alamat `https://...onrender.com` sudah disalin ke bahan presentasi
- [ ] Siap menjelaskan **kenapa Render dan bukan Vercel** — pertanyaan
      yang sangat mungkin ditanyakan

## 5.7 Rangkuman Bab Ini

1. Uji sampai **login** dan **tambah buku**, bukan berhenti di beranda —
   keduanya membuktikan session & CSRF hidup.
2. **Build log** untuk kegagalan sebelum Live, **runtime log** sesudahnya.
3. Sebagian besar error punya penyebab tunggal yang khas — cocokkan
   pesannya di §5.3 sebelum menebak.
4. Render tidur setelah **15 menit**; Supabase berhenti setelah **7
   hari**. Keduanya bisa diantisipasi, asal diingat.

Lanjut ke: [Rangkuman & Latihan Lanjutan](06-rangkuman-latihan.md)
