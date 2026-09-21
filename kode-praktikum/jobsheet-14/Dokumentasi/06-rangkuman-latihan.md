# 6. Rangkuman & Latihan Lanjutan

## 6.1 Rangkuman Keseluruhan Jobsheet 14

| Bagian | Konsep yang Dipelajari |
|---|---|
| [Konsep Hosting & Container](01-konsep-dasar-hosting-container.md) | Persisten vs serverless, image vs container, kenapa database dipisah |
| [Membedah `Dockerfile`](02-dockerfile-php-apache.md) | `FROM`/`RUN`/`COPY`/`ENV`/`CMD`, `pdo_pgsql`, port dinamis |
| [Supabase](03-supabase-database-produksi.md) | PostgreSQL terkelola, connection pooler, SSL, pesan error aman |
| [Deploy ke Render](04-deploy-ke-render.md) | Deploy dari Git, root directory, environment variable, Blueprint |
| [Uji Coba & Troubleshooting](05-uji-coba-dan-troubleshooting.md) | Membaca log, kamus error, batas free tier |

## 6.2 Konsep Inti yang Perlu Diingat

1. **Model hosting menentukan apakah aplikasimu bisa jalan.** PHP
   dengan session butuh hosting **persisten**. Serverless membuat ulang
   lingkungan tiap permintaan, sehingga login dan token CSRF gugur
   secara acak
   ([bab 1 §1.3](01-konsep-dasar-hosting-container.md#13-kenapa-simpus-mini-tidak-bisa-di-vercel)).
2. **Container itu sekali pakai, data tidak boleh menumpang di
   dalamnya.** Setiap deploy membuang container lama — karena itu
   database hidup terpisah di Supabase
   ([bab 1 §1.7](01-konsep-dasar-hosting-container.md#17-kenapa-databasenya-terpisah-supabase)).
3. **`Dockerfile` mengubah "lingkungan server" menjadi kode.** Versi
   PHP dan daftar ekstensi tertulis eksplisit dan masuk ke Git — inilah
   yang meredam masalah klasik *"di komputerku jalan, kok di server
   tidak"*
   ([bab 1 §1.6](01-konsep-dasar-hosting-container.md#16-kenapa-repot-dengan-container-kenapa-tidak-unggah-saja-seperti-hosting-biasa)).
4. **Kapan sebuah nilai ditetapkan itu menentukan.** Kutip tunggal vs
   ganda pada `printf` memutuskan apakah `${PORT}` dibekukan saat image
   dibangun atau dibaca saat container berjalan
   ([bab 2 §2.6](02-dockerfile-php-apache.md#26-env-port-dan-konfigurasi-apache--bagian-yang-paling-membingungkan)).
   Pola yang sama muncul pada nilai cadangan `getenv()` di
   [`includes/config.php`](../includes/config.php).
5. **Connection pooler bukan sekadar optimasi.** Pada Supabase gratis
   ia menentukan apakah koneksi bisa terjalin sama sekali — soal IPv4
   dan soal jatah koneksi
   ([bab 3 §3.5](03-supabase-database-produksi.md#35-kenapa-harus-pooler-bukan-koneksi-langsung)).
6. **Pesan error adalah permukaan keamanan.** `$e->getMessage()` dari
   PDO memuat host, nama database, dan username — persis nilai yang
   susah payah dipindahkan ke environment variable. Catat ke log,
   jangan tampilkan
   ([bab 3 §3.7.3](03-supabase-database-produksi.md#373-pesan-error-yang-tidak-membocorkan-kredensial)).
7. **Environment variable akhirnya terbukti gunanya.** Kamu menyambung
   ke database produksi tanpa mengedit satu baris kode pun — buah dari
   [jobsheet-13](../../jobsheet-13/Dokumentasi/01-konsep-dasar-deployment-config.md)
   ([bab 4 §4.5](04-deploy-ke-render.md#inilah-saat-pelajaran-jobsheet-13-terbayar)).

## 6.3 Perjalanan Lengkap SIMPUS-Mini

| Jobsheet | Yang Ditambahkan |
|---|---|
| [01](../../jobsheet-01/Dokumentasi/README.md) | HTML5 semantic — kerangka halaman statis |
| [02](../../jobsheet-02/Dokumentasi/README.md) | CSS3 — Flexbox, Grid, styling |
| [03](../../jobsheet-03/Dokumentasi/README.md) | Responsive design — media query, hamburger menu |
| [04](../../jobsheet-04/Dokumentasi/README.md) | Rancangan UI/UX — wireframe, user flow, aktor |
| [05](../../jobsheet-05/Dokumentasi/README.md) | JavaScript DOM & Event — interaktivitas di browser |
| [06](../../jobsheet-06/Dokumentasi/README.md) | Fetch API & JSON — data dinamis dari berkas |
| [07](../../jobsheet-07/Dokumentasi/README.md) | PHP dasar — server-side, `$_SESSION`, form handling |
| [08](../../jobsheet-08/Dokumentasi/README.md) | PostgreSQL — data yang benar-benar persisten |
| [09](../../jobsheet-09/Dokumentasi/README.md) | CRUD penuh — Update, Delete, pagination |
| [10](../../jobsheet-10/Dokumentasi/README.md) | Autentikasi — Login, Register, otorisasi |
| [11](../../jobsheet-11/Dokumentasi/README.md) | Keamanan — XSS, CSRF, session fixation |
| [12](../../jobsheet-12/Dokumentasi/README.md) | Integrasi Peminjaman — transaction, relasi tabel |
| [13](../../jobsheet-13/Dokumentasi/README.md) | Deployment & dokumentasi — siap dipindahkan |
| **14** | **Deployment nyata — hidup di internet** |

Perhatikan bentuk perjalanannya. Jobsheet 01–12 menambah **kemampuan
aplikasi**. Jobsheet 13 menambah **kesiapan** — tanpa fitur baru sama
sekali. Jobsheet 14 tidak menambah baris kode fitur pun, tapi mengubah
statusnya: dari "program di laptopku" menjadi "layanan yang bisa dipakai
orang lain".

Dua jobsheet terakhir mengajarkan hal yang sering luput: **pekerjaan
seorang pengembang tidak selesai saat fiturnya jalan.** Ia selesai saat
aplikasinya bisa dijalankan, dipahami, dan dirawat oleh orang lain.

## 6.4 Latihan Lanjutan

### Latihan 1 — Deploy Ulang Setelah Mengubah Kode (Dasar)

Ubah tahun atau nama pada [`includes/footer.php`](../includes/footer.php),
lalu `git push`. Amati di tab Logs Render: berapa lama deploy kedua
dibanding yang pertama?

**Pertanyaan:** kenapa jauh lebih cepat? (Petunjuk:
[bab 2 §2.5](02-dockerfile-php-apache.md#25-copy--varwwwhtml--memasukkan-kode-aplikasi))

### Latihan 2 — Membuktikan Data Bertahan (Dasar)

Tambah tiga buku lewat aplikasi di Render. Lalu picu deploy ulang.
Setelah selesai, buka lagi daftar buku.

**Pertanyaan:** ketiga buku masih ada. Jelaskan ke mana sebenarnya data
itu disimpan, dan kenapa container yang dibuang tidak membawanya serta.

### Latihan 3 — Mengalami Error dengan Sengaja (Menengah)

Ubah `DB_NAME` di Render menjadi `simpus_mini`, tunggu deploy ulang,
lalu buka aplikasinya. Baca pesan di runtime log. Kembalikan ke
`postgres` setelahnya.

**Pertanyaan:** pesan apa yang muncul di layar pengunjung, dan pesan apa
yang muncul di log? Kenapa keduanya berbeda — dan kenapa perbedaan itu
disengaja?

### Latihan 4 — Menguji Batas Session (Menengah)

Login ke aplikasi, lalu biarkan tanpa dibuka selama lebih dari 15 menit.
Buka lagi.

**Pertanyaan:** kamu ter-logout, tapi data buku tetap utuh. Jelaskan
kenapa dua hal itu berbeda nasib. Kaitkan dengan
[bab 5 §5.4](05-uji-coba-dan-troubleshooting.md#54-batas-pertama-render-tidur-setelah-15-menit).

### Latihan 5 — Membandingkan dengan Serverless (Menengah)

Tanpa perlu benar-benar mencoba, tulis setengah halaman: bila
SIMPUS-Mini dipaksa berjalan di Vercel, bagian mana **persisnya** yang
gagal lebih dulu — dan gejala apa yang akan dilihat pengguna?

Sebutkan berkasnya dan jelaskan mekanismenya, jangan hanya menyimpulkan
"tidak bisa".

### Latihan 6 — Memakai `render.yaml` (Lanjut)

Hapus service-mu, lalu buat ulang lewat **Blueprint** memakai
[`render.yaml`](../render.yaml).

**Pertanyaan:** variabel mana yang tetap harus diisi manual, dan kenapa
justru yang itu tidak boleh ditulis di dalam berkas?

### Latihan 7 — Memindahkan Session ke Database (Menantang)

Rancang — tidak harus diimplementasikan — cara menyimpan session di
tabel PostgreSQL alih-alih berkas, memakai `session_set_save_handler()`.

Pertanyaan yang perlu kamu jawab: tabelnya berisi kolom apa saja?
Kapan barisnya dihapus? Apa yang terjadi kalau dua permintaan mengubah
session yang sama bersamaan? Dan setelah itu — apakah aplikasinya jadi
bisa berjalan di Vercel?

### Latihan 8 — Menjaga Supabase Tetap Hidup (Menantang)

Rancang cara otomatis mencegah project Supabase-mu *paused*, tanpa
membuka aplikasinya manual tiap minggu.

Pikirkan: apa yang harus dipanggil, seberapa sering, dan dari mana
pemanggilannya berasal (aplikasi Render-mu sendiri juga ikut tidur —
apakah ia bisa diandalkan?).

## 6.5 Penutup

Setelah 14 jobsheet, SIMPUS-Mini sudah menempuh jalur penuh sebuah
aplikasi web: dari satu berkas HTML statis, menjadi layanan yang
berjalan di internet dengan database terkelola, autentikasi, proteksi
keamanan dasar, dan dokumentasi yang bisa dibaca orang lain.

Yang lebih berharga daripada aplikasinya sendiri adalah pola yang kamu
lalui — dan pola itu berulang di hampir semua proyek nyata:

> rancang → bangun bertahap → amankan → dokumentasikan → deploy → rawat

Tahap terakhir, **rawat**, baru saja kamu mulai: memantau log,
mengantisipasi batas layanan, dan memperbaiki saat ada yang rusak.
Tahap itu tidak punya jobsheet penutup — ia berlangsung selama
aplikasinya masih dipakai orang.
