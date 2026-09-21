# 1. Konsep Dasar: Hosting, Container, & Kenapa Render

## 1.1 Masalahnya: `localhost` Hanya Milikmu Sendiri

Sejak jobsheet-07 kamu menjalankan SIMPUS-Mini dengan `php -S localhost:8000`
atau lewat Laragon. Kata **localhost** artinya harfiah: *komputer ini*.
Alamat itu tidak bisa dibuka dari HP-mu, dari laptop teman, apalagi dari
rumah dosen. Begitu komputermu dimatikan, aplikasinya ikut mati.

Supaya sebuah aplikasi web bisa dibuka siapa pun kapan pun, ia harus
berjalan di komputer yang:

1. **menyala terus-menerus**, dan
2. **punya alamat publik** yang bisa dijangkau dari internet.

Komputer semacam itu disebut **server**. Menyewa dan mengelola server
sendiri itu mahal dan rumit, maka muncullah **layanan hosting** —
perusahaan yang menyediakan server siap pakai. Render adalah salah
satunya.

## 1.2 Dua Model Hosting yang Perlu Dibedakan

Tidak semua layanan hosting bekerja dengan cara yang sama. Ada dua
model besar, dan perbedaannya menentukan apakah aplikasi PHP-mu bisa
jalan di sana.

### Model A — Server/Container Persisten

Aplikasimu berjalan sebagai **satu proses yang hidup terus**, persis
seperti Apache di Laragon. Ia punya memori dan sistem berkas yang tetap
ada di antara permintaan pengunjung. Contohnya: Render, Railway, hosting
cPanel tradisional.

### Model B — Serverless

Tidak ada proses yang hidup terus. Setiap permintaan pengunjung
menjalankan fungsi yang dibuat **dari nol**, lalu dimatikan lagi begitu
jawabannya terkirim. Sistem berkasnya bersifat **sementara** dan bisa
berbeda antar permintaan. Contohnya: Vercel, Netlify Functions, AWS
Lambda.

## 1.3 Kenapa SIMPUS-Mini Tidak Bisa di Vercel

Vercel populer dan gratis, jadi wajar kalau tergoda memakainya. Tapi
Vercel memakai model serverless — dan SIMPUS-Mini bergantung penuh pada
sesuatu yang serverless tidak sediakan: **session**.

Ingat kembali dari
[dokumentasi jobsheet-07 bab 3](../../jobsheet-07/Dokumentasi/03-session-dan-alur-data.md)
bahwa PHP menyimpan isi `$_SESSION` sebagai **berkas di server**. Di
model serverless, berkas itu bisa lenyap atau tertinggal di "mesin" lain
pada permintaan berikutnya. Akibatnya nyata dan merusak:

| Fitur | Apa yang terjadi di serverless |
|---|---|
| Login ([`includes/auth.php`](../includes/auth.php)) | Mahasiswa login, lalu di halaman berikutnya dilempar balik ke halaman login |
| Token CSRF ([`includes/csrf.php`](../includes/csrf.php)) | Token dibuat saat form ditampilkan, hilang saat form dikirim → semua simpan data ditolak `403` |
| Flash message | Pesan "Buku berhasil ditambahkan" tidak pernah muncul |

Perhatikan kasus CSRF: token dibuat pada **satu** permintaan (saat
halaman form dirender) lalu diverifikasi pada permintaan **berikutnya**
(saat tombol Simpan ditekan). Dua permintaan yang berurutan itulah yang
tidak dijamin melihat berkas session yang sama. Itu sebabnya
masalahnya muncul **acak** — kadang jalan, kadang tidak — yang justru
lebih menyusahkan daripada gagal total.

Ditambah lagi: Vercel **tidak punya dukungan resmi untuk PHP** sama
sekali. Yang ada hanya runtime buatan komunitas.

## 1.4 Kenapa Render

Render memakai model persisten. Aplikasimu berjalan di dalam container
yang hidup terus, sehingga:

- berkas session tetap ada di antara permintaan → login, CSRF, dan flash
  message bekerja **tanpa satu baris kode pun perlu diubah**;
- Apache dan PHP berjalan persis seperti di Laragon, hanya saja komputernya
  milik Render dan menyala 24 jam.

Ditambah: paket gratisnya benar-benar gratis dan berkelanjutan, tanpa
kartu kredit. Bandingkan dengan dua alternatif yang sering disebut:

| Layanan | Gratis? | Catatan |
|---|---|---|
| **Render** | Ya | 512 MB RAM, 750 jam/bulan. Tidur setelah 15 menit menganggur |
| Railway | Nyaris tidak | Kredit $5 sekali, lalu hanya $1/bulan — habis untuk satu service kecil |
| Fly.io | Tidak | Free tier dihapus Oktober 2024; sekarang wajib kartu kredit |

Harga layanan berubah-ubah. Periksa lagi halaman harga masing-masing
sebelum memutuskan.

## 1.5 Apa itu Container dan Image?

Render menjalankan aplikasi PHP lewat **container**. Dua istilah yang
perlu dibedakan:

- **Image** — cetakan/resep yang sudah jadi: berisi sistem operasi
  minimal, Apache, PHP, ekstensi yang dibutuhkan, plus kode aplikasimu.
  Ia diam, seperti berkas `.iso`.
- **Container** — image yang **sedang dijalankan**. Ia hidup, punya
  proses dan memori.

Analogi yang sering dipakai: *image* adalah resep masakan, *container*
adalah masakan yang sudah tersaji di meja. Dari satu resep bisa dibuat
banyak porsi.

**Dockerfile** adalah tulisan resepnya — daftar perintah untuk membangun
image. Kamu menulis `Dockerfile`, Render membacanya, membangun image, lalu
menjalankannya sebagai container.

## 1.6 Kenapa Repot dengan Container? Kenapa Tidak Unggah Saja Seperti Hosting Biasa?

Pertanyaan yang wajar. Jawabannya: **Render tidak menyediakan runtime
PHP bawaan.** Ia mendukung Node.js, Python, Ruby, Go, dan beberapa
lainnya secara langsung — tapi tidak PHP. Untuk bahasa yang tidak
didukung, Render menyediakan jalur Docker: "kirimkan `Dockerfile`-nya,
kami jalankan apa pun isinya."

Ada keuntungan tak terduga dari keharusan ini. Dengan `Dockerfile`,
**versi PHP dan daftar ekstensi ditulis eksplisit** di dalam
repository-mu. Kamu tidak lagi bergantung pada "kebetulan versi PHP di
server sama dengan di laptopku". Masalah klasik *"di komputerku jalan,
kok di server tidak"* sebagian besar lenyap, karena resepnya sama persis
di mana pun dijalankan.

## 1.7 Kenapa Databasenya Terpisah (Supabase)?

Container itu **sekali pakai**: setiap kali kamu memperbarui kode dan
Render men-deploy ulang, container lama dibuang dan container baru
dibuat dari image yang baru. Apa pun yang ditulis ke dalam sistem
berkas container akan **ikut terbuang**.

Kalau PostgreSQL dipasang di dalam container yang sama, seluruh data
buku, anggota, dan peminjaman lenyap setiap kali kamu memperbaiki satu
baris kode. Maka database harus hidup **di luar** container, di layanan
yang memang bertugas menyimpan data secara permanen. Di sinilah
**Supabase** masuk — PostgreSQL terkelola yang gratis dan berjalan
terus-menerus terlepas dari container aplikasimu.

Bagan besarnya:

```
   Pengunjung
       |  https://
       v
  +----------------+          +--------------------+
  |     RENDER     |  pgsql   |      SUPABASE      |
  |  container     |--------->|   PostgreSQL       |
  |  Apache + PHP  |  :6543   |   (data permanen)  |
  +----------------+          +--------------------+
   dibuang & dibuat            tetap ada walau
   ulang tiap deploy           aplikasi di-deploy ulang
```

Karena Supabase adalah PostgreSQL asli, **skema dan query-mu tidak perlu
diubah sama sekali**. Berkas `sql/01`–`sql/03` yang kamu tulis sejak
[jobsheet-08](../../jobsheet-08/Dokumentasi/02-skema-database-sql.md)
dan [jobsheet-12](../../jobsheet-12/Dokumentasi/02-skema-peminjaman-dan-relasi.md)
berjalan apa adanya.

## 1.8 Rangkuman Bab Ini

1. `localhost` hanya bisa diakses dari komputermu sendiri; aplikasi perlu
   **server** yang menyala terus dan beralamat publik.
2. Hosting **persisten** (Render) menjaga proses tetap hidup; hosting
   **serverless** (Vercel) membuat ulang tiap permintaan.
3. SIMPUS-Mini butuh yang persisten karena login, CSRF, dan flash message
   bersandar pada **berkas session**.
4. Render menjalankan PHP lewat **container**, dijelaskan lewat
   **`Dockerfile`** — yang sekaligus mengunci versi PHP & ekstensi.
5. Database ditaruh **di luar** container (Supabase) karena container
   dibuang setiap kali deploy.

Lanjut ke: [Membedah `Dockerfile`: PHP + Apache + `pdo_pgsql`](02-dockerfile-php-apache.md)
