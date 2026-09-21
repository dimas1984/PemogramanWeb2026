# 2. Membedah `Dockerfile`: PHP + Apache + `pdo_pgsql`

Bab ini membaca [`Dockerfile`](../Dockerfile) baris per baris. Ingat
dari [bab 1 §1.5](01-konsep-dasar-hosting-container.md#15-apa-itu-container-dan-image):
berkas ini adalah **resep** untuk membangun image. Render yang akan
memasaknya — kamu tidak perlu memasang Docker di komputermu.

## 2.1 Cara Membaca Sebuah `Dockerfile`

Setiap baris yang diawali huruf kapital (`FROM`, `RUN`, `COPY`, `ENV`,
`CMD`) adalah satu **instruksi**. Docker menjalankannya **berurutan
dari atas ke bawah**, dan setiap instruksi menghasilkan satu *lapisan*
(*layer*) baru di atas lapisan sebelumnya — mirip menumpuk transparansi.

Instruksi yang dipakai di jobsheet ini hanya lima:

| Instruksi | Artinya |
|---|---|
| `FROM` | Mulai dari image yang sudah ada |
| `RUN` | Jalankan perintah **saat image dibangun** |
| `COPY` | Salin berkas dari repository ke dalam image |
| `ENV` | Tetapkan environment variable di dalam container |
| `CMD` | Perintah yang dijalankan **saat container mulai hidup** |

Bedakan baik-baik `RUN` dan `CMD`. `RUN` terjadi **sekali**, saat
memasak image. `CMD` terjadi **setiap kali** container dinyalakan.

## 2.2 `FROM php:8.3-apache` — Titik Berangkat

```dockerfile
FROM php:8.3-apache
```

Kita tidak membangun dari sistem operasi kosong. `php:8.3-apache` adalah
image resmi yang sudah berisi Linux minimal, **PHP 8.3**, dan **Apache**
yang sudah dikonfigurasi untuk menjalankan berkas `.php`. Ini kurang
lebih setara dengan apa yang Laragon siapkan untukmu di Windows.

Angka `8.3` itu penting: versi PHP-mu jadi **tertulis eksplisit**. Siapa
pun yang men-deploy ulang repo ini — sekarang atau tahun depan — akan
mendapat PHP 8.3 yang sama.

## 2.3 `RUN ... docker-php-ext-install pdo_pgsql` — Bagian Paling Krusial

```dockerfile
RUN apt-get update \
 && apt-get install -y --no-install-recommends libpq-dev \
 && docker-php-ext-install pdo_pgsql \
 && rm -rf /var/lib/apt/lists/*
```

**Kalau kamu melewatkan satu instruksi saja di seluruh berkas ini,
inilah yang paling mungkin bikin gagal.**

Image `php:8.3-apache` memang punya PDO, tapi PDO itu sekadar
*antarmuka* — ia butuh **driver** untuk tiap jenis database. Driver
PostgreSQL (`pdo_pgsql`) **tidak** ikut secara bawaan. Tanpa baris ini,
`new PDO("pgsql:...")` di [`includes/koneksi.php`](../includes/koneksi.php)
gagal dengan pesan:

```
could not find driver
```

Mari bedah tiap potongannya:

- **`apt-get update`** — memperbarui daftar paket Linux. Tanpa ini,
  `apt-get install` sering gagal karena daftarnya kosong/usang.
- **`libpq-dev`** — pustaka klien PostgreSQL. Dibutuhkan sebagai bahan
  saat `pdo_pgsql` dikompilasi.
- **`--no-install-recommends`** — jangan ikut memasang paket "anjuran"
  yang tidak kita butuhkan. Image jadi lebih ramping.
- **`docker-php-ext-install pdo_pgsql`** — perkakas bawaan image PHP
  resmi untuk mengompilasi lalu mengaktifkan sebuah ekstensi.
- **`rm -rf /var/lib/apt/lists/*`** — buang cache daftar paket yang
  sudah tidak diperlukan, supaya ukuran image mengecil.

### Kenapa disambung dengan `&&`, bukan ditulis sebagai `RUN` terpisah?

Dua alasan. Pertama, `&&` berarti "jalankan berikutnya **hanya jika**
yang sebelumnya berhasil" — kalau `apt-get update` gagal, proses berhenti
di situ, tidak diam-diam lanjut dengan daftar paket yang rusak. Kedua,
satu `RUN` menghasilkan satu lapisan. Kalau `rm -rf` ditulis sebagai
`RUN` tersendiri, berkas cache sudah terlanjur tersimpan di lapisan
sebelumnya — menghapusnya di lapisan berikutnya **tidak** mengecilkan
image.

## 2.4 `RUN mv php.ini-production php.ini` — Mode Produksi

```dockerfile
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
```

Image PHP resmi membawa dua contoh konfigurasi tapi **tidak
mengaktifkan satu pun**. Kalau dibiarkan, PHP memakai nilai bawaan yang
menampilkan pesan error ke layar.

`php.ini-production` mengatur `display_errors = Off`. Ini penting untuk
keamanan, dan nyambung langsung dengan yang kamu pelajari di
[jobsheet-11](../../jobsheet-11/Dokumentasi/01-konsep-dasar-keamanan-web.md):
pesan error PHP sering memuat **path lengkap berkas di server**, potongan
query, bahkan nama tabel. Semua itu bahan berharga bagi penyerang.
Errornya tetap dicatat ke log server — hanya tidak dipamerkan ke
pengunjung.

## 2.5 `COPY . /var/www/html/` — Memasukkan Kode Aplikasi

```dockerfile
COPY . /var/www/html/
```

Titik (`.`) berarti "seluruh isi folder tempat `Dockerfile` ini berada"
— yaitu `jobsheet-14/`. `/var/www/html/` adalah *document root* Apache
di image ini: folder yang isinya dilayani ke pengunjung. Setelah baris
ini, `index.php` bisa diakses di `https://<nama>.onrender.com/`, dan
`buku/list.php` di `https://<nama>.onrender.com/buku/list.php`.

Perhatikan urutannya: `COPY` sengaja ditaruh **setelah** pemasangan
ekstensi. Docker menyimpan hasil tiap lapisan (*cache*); lapisan hanya
dibangun ulang kalau ada perubahan. Karena kodemu jauh lebih sering
berubah daripada daftar ekstensi, menaruh `COPY` di bawah membuat deploy
berikutnya jauh lebih cepat — bagian pemasangan ekstensi cukup diambil
dari cache.

## 2.6 `ENV PORT` dan Konfigurasi Apache — Bagian yang Paling Membingungkan

```dockerfile
ENV PORT=10000

RUN printf 'Listen ${PORT}\n' > /etc/apache2/ports.conf \
 && printf '<VirtualHost *:${PORT}>\n\tDocumentRoot /var/www/html\n...' \
      > /etc/apache2/sites-available/000-default.conf
```

**Masalahnya:** Apache bawaan mendengarkan port `80`. Tapi Render
menentukan sendiri port mana yang harus dipakai, dan memberitahukannya
lewat environment variable bernama `PORT`. Kalau Apache tetap keras
kepala di port 80, Render tidak menemukan aplikasimu dan deploy
dinyatakan gagal.

**Solusinya:** tulis ulang konfigurasi Apache supaya membaca `${PORT}`.

Di sinilah letak kehalusan yang mudah salah. Perhatikan **kutip
tunggal** pada `printf '...'`. Kalau memakai kutip ganda, shell di dalam
Docker akan menerjemahkan `${PORT}` **saat image dibangun** dan menulis
angka mati `Listen 10000` ke dalam berkas. Dengan kutip tunggal, teks
`${PORT}` tersimpan **apa adanya** sebagai huruf — dan yang
menerjemahkannya nanti adalah **Apache**, saat container dinyalakan,
memakai nilai `PORT` yang Render berikan saat itu.

Satu karakter kutip menentukan apakah nilainya ditetapkan saat *memasak*
atau saat *menyajikan*.

Lalu untuk apa `ENV PORT=10000`? Itu **nilai cadangan**. Render selalu
mengisi `PORT` sendiri, tapi kalau image ini dijalankan di komputer yang
tidak mengisinya, Apache tidak akan gagal kebingungan. Polanya sama
persis dengan nilai cadangan di [`includes/config.php`](../includes/config.php)
yang kamu kenal dari
[jobsheet-13 bab 1 §1.4](../../jobsheet-13/Dokumentasi/01-konsep-dasar-deployment-config.md).

## 2.7 `.dockerignore` — Menahan Berkas yang Tak Perlu

[`.dockerignore`](../.dockerignore) bekerja mirip `.gitignore`: ia
mendaftar apa yang **tidak** ikut saat `COPY .` dijalankan.

```
Dokumentasi/
docs/
README.md
*.md
```

Folder `Dokumentasi/` ini saja berisi ribuan baris penjelasan yang
sama sekali tidak dibutuhkan aplikasi untuk berjalan. Mengeluarkannya
membuat image lebih kecil dan build lebih cepat. Berkas-berkas itu tetap
ada di repository GitHub-mu — hanya tidak ikut masuk ke dalam container.

## 2.8 `CMD ["apache2-foreground"]` — Menyalakan Apache

```dockerfile
CMD ["apache2-foreground"]
```

Kenapa `apache2-foreground`, bukan `service apache2 start` seperti di
tutorial Linux pada umumnya?

Karena **container hidup selama proses utamanya hidup**. Perintah
`service apache2 start` menyalakan Apache sebagai *daemon* — bekerja di
latar belakang — lalu perintahnya sendiri selesai. Bagi Docker, itu
berarti "tugas selesai", dan container langsung dimatikan bersama Apache
di dalamnya.

`apache2-foreground` menjalankan Apache **di depan**: prosesnya tidak
pernah selesai selama server melayani. Container pun ikut hidup terus.

## 2.9 Apakah Perlu Memasang Docker di Komputer Sendiri?

**Tidak.** Render yang membangun image-nya di servernya sendiri, dari
`Dockerfile` yang ia baca di repository GitHub-mu.

Kalau kamu **ingin** mengujinya lebih dulu sebelum deploy (sangat
dianjurkan bila Docker Desktop sudah terpasang), dari dalam folder
`jobsheet-14/`:

```bash
docker build -t simpus-mini .
docker run --rm -p 8080:10000 simpus-mini
```

Lalu buka `http://localhost:8080`. Halaman akan tampil tapi statistiknya
gagal memuat — wajar, karena container itu belum diberi kredensial
database. Yang sedang kamu uji di tahap ini hanyalah: *apakah image-nya
berhasil dibangun dan Apache-nya menyala.*

> **Catatan kejujuran:** perintah di atas belum pernah dijalankan oleh
> penyusun dokumen ini karena Docker tidak terpasang di mesinnya. Bila
> kamu menemukan kesalahan pada `Dockerfile`, catat dan perbaiki — itu
> justru latihan yang bagus.

## 2.10 Rangkuman Bab Ini

| Instruksi | Perannya |
|---|---|
| `FROM php:8.3-apache` | Mengunci versi PHP & menyediakan Apache |
| `RUN ... pdo_pgsql` | **Wajib** — tanpanya: `could not find driver` |
| `RUN mv php.ini-production` | `display_errors = Off` untuk produksi |
| `COPY . /var/www/html/` | Memasukkan kode; ditaruh akhir demi cache |
| `ENV PORT` + `printf '...'` | Apache mengikuti port pilihan Render |
| `CMD apache2-foreground` | Apache di depan agar container tetap hidup |

Lanjut ke: [Supabase: Database PostgreSQL di Internet](03-supabase-database-produksi.md)
