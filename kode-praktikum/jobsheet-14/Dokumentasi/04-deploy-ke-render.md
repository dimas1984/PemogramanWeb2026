# 4. Deploy ke Render: Langkah demi Langkah

Database sudah siap di [bab 3](03-supabase-database-produksi.md),
resep container sudah siap di [bab 2](02-dockerfile-php-apache.md).
Bab ini menyatukan keduanya.

> Sama seperti bab sebelumnya: tata letak dashboard Render bisa berubah.
> Pahami **tujuan** tiap langkah supaya tetap bisa mengikutinya walau
> nama menunya bergeser.

## 4.1 Bagaimana Render Mendapatkan Kodemu?

Render **tidak** menerima unggahan folder lewat FTP seperti hosting
tradisional. Ia membaca langsung dari **repository Git**.

Alurnya:

```
  komputermu          GitHub                    Render
  ----------          ------                    ------
  git push  ------->  repo diperbarui  ------->  build image
                                                 dari Dockerfile
                                                      |
                                                      v
                                                 jalankan container
```

Konsekuensi yang perlu dibiasakan: **cara memperbarui aplikasi di
internet adalah dengan `git push`.** Tidak ada langkah unggah terpisah.
Render memantau repo-mu dan otomatis men-deploy ulang setiap ada commit
baru di branch yang dipilih.

## 4.2 Langkah 1 — Pastikan Kode Sudah di GitHub

Repository praktikum ini sudah ada di GitHub. Pastikan folder
`jobsheet-14/` sudah ikut ter-*push*:

```bash
git add kode-praktikum/jobsheet-14
git commit -m "Tambah jobsheet-14: deployment ke Render + Supabase"
git push
```

Buka repo-mu di browser dan pastikan folder `jobsheet-14` benar-benar
terlihat di sana. Render membaca dari GitHub, bukan dari hard disk-mu —
apa pun yang belum di-*push* tidak akan ikut ter-deploy.

**Periksa sekali lagi sebelum lanjut:** tidak ada password Supabase yang
tertulis di dalam kode. Buka [`includes/config.php`](../includes/config.php)
— isinya harus tetap nilai cadangan lokal (`localhost`, `postgres`),
bukan kredensial asli. Kredensial sungguhan diisi di §4.5, **di
dashboard Render**, tidak pernah di dalam repository.

## 4.3 Langkah 2 — Membuat Web Service

1. Buka [render.com](https://render.com) → **Sign in with GitHub**.
2. **New** → **Web Service**.
3. Hubungkan akun GitHub-mu, lalu pilih repository praktikum ini.

Kalau repo-mu tidak muncul di daftar, Render belum diberi izin
mengaksesnya — cari tautan pengaturan izin (*Configure account* /
*Install*) dan berikan akses ke repo tersebut.

## 4.4 Langkah 3 — Mengisi Formulir Service

Inilah isian yang menentukan. Yang bertanda **wajib diperhatikan**
adalah tempat kesalahan paling sering terjadi.

| Isian | Nilai | Keterangan |
|---|---|---|
| **Name** | `simpus-mini` | Menentukan alamatmu: `https://simpus-mini.onrender.com`. Harus unik se-Render |
| **Language / Runtime** | **Docker** | **Wajib diperhatikan.** Bukan Node/Python. Inilah yang membuat Render membaca `Dockerfile` |
| **Branch** | `main` | Branch yang dipantau |
| **Root Directory** | `kode-praktikum/jobsheet-14` | **Wajib diperhatikan.** Lihat §4.4.1 |
| **Instance Type** | **Free** | Pastikan tidak tersalah pilih paket berbayar |

### 4.4.1 Kenapa *Root Directory* Wajib Diisi?

Secara bawaan Render menganggap `Dockerfile` ada di **akar
repository**. Repo praktikum ini tidak begitu — akarnya berisi RPS,
cheatsheet, dan folder `kode-praktikum/` dengan 14 jobsheet di dalamnya.

Mengisi *Root Directory* dengan `kode-praktikum/jobsheet-14` berarti
memberitahu Render: "anggap folder itu sebagai akar proyek." Dari sana
`Dockerfile` ditemukan, dan `COPY . /var/www/html/` menyalin isi
jobsheet-14 saja — bukan seluruh repo beserta 13 jobsheet lainnya.

Kalau isian ini dikosongkan, build gagal dengan pesan semacam
`Dockerfile not found`.

## 4.5 Langkah 4 — Mengisi Environment Variable

Cari bagian **Environment Variables** (kadang di balik tombol *Advanced*
pada formulir pembuatan, atau di tab *Environment* setelah service
terbuat). Tambahkan tujuh baris berikut, memakai nilai hasil bongkaran
connection string di
[bab 3 §3.6](03-supabase-database-produksi.md#36-memetakan-connection-string-ke-environment-variable):

| Key | Value |
|---|---|
| `DB_HOST` | `aws-0-ap-southeast-1.pooler.supabase.com` |
| `DB_PORT` | `6543` |
| `DB_NAME` | `postgres` |
| `DB_USER` | `postgres.<project-ref>` |
| `DB_PASS` | *(password Supabase-mu)* |
| `DB_SSLMODE` | `require` |
| `APP_DEBUG` | `true` *(sementara — lihat catatan)* |

> **Tentang `APP_DEBUG`:** isi `true` **hanya untuk deploy pertama**,
> supaya kalau koneksi gagal kamu langsung melihat penyebabnya di layar
> (lihat [bab 3 §3.7.3](03-supabase-database-produksi.md#373-pesan-error-yang-tidak-membocorkan-kredensial)).
> Begitu aplikasi terbukti jalan, **hapus variabel ini**. Membiarkannya
> menyala di produksi berarti mengembalikan kebocoran kredensial yang
> justru kita perbaiki.

### Inilah Saat Pelajaran Jobsheet 13 Terbayar

Berhenti sejenak dan perhatikan apa yang baru saja terjadi. Kamu
menyambungkan aplikasi ke database produksi **tanpa mengedit satu baris
kode pun**. Kredensialnya hidup di dashboard Render, terpisah dari
repository yang bisa dibaca publik.

Seandainya `koneksi.php` masih menuliskan kredensial langsung seperti di
jobsheet-08, kamu punya dua pilihan buruk: mengedit kode setiap ganti
lingkungan, atau meng-commit password Supabase ke GitHub. Abstraksi yang
di jobsheet-13 terasa mengada-ada — "kenapa repot-repot pakai
`getenv()`?" — barulah kelihatan gunanya sekarang.

## 4.6 Langkah 5 — Deploy dan Membaca Log

Tekan **Create Web Service**. Render mulai bekerja, dan tab **Logs**
menampilkan prosesnya secara langsung. Biasakan membacanya — di situlah
semua jawaban berada saat ada yang gagal.

Yang akan kamu lihat berurutan:

1. **Cloning** — Render mengunduh repo dari GitHub.
2. **Building** — `Dockerfile` dijalankan baris demi baris. Tahap
   `docker-php-ext-install pdo_pgsql` adalah yang paling lama (1–3 menit)
   karena ekstensi dikompilasi dari sumber.
3. **Pushing/Uploading** — image hasilnya disimpan.
4. **Starting** — container dinyalakan, `apache2-foreground` berjalan.
5. **Live** — status berubah hijau, alamat `https://...onrender.com`
   bisa dibuka.

Deploy pertama wajar memakan **5–10 menit**. Deploy berikutnya lebih
cepat karena lapisan pemasangan ekstensi diambil dari cache — persis
seperti dijelaskan di
[bab 2 §2.5](02-dockerfile-php-apache.md#25-copy--varwwwhtml--memasukkan-kode-aplikasi).

Kalau gagal, jangan menebak-nebak: gulir log ke bawah dan baca pesan
error terakhir, lalu cocokkan dengan tabel di
[bab 5](05-uji-coba-dan-troubleshooting.md).

## 4.7 Cara Alternatif: `render.yaml` (Blueprint)

Semua isian di §4.4 dan §4.5 bisa ditulis sebagai berkas, bukan diketik
di formulir. Itulah isi [`render.yaml`](../render.yaml):

```yaml
services:
  - type: web
    name: simpus-mini
    runtime: docker
    plan: free
    region: singapore
    rootDir: kode-praktikum/jobsheet-14
    dockerfilePath: ./Dockerfile
    envVars:
      - key: DB_HOST
        sync: false
      ...
      - key: DB_SSLMODE
        value: require
```

Cara memakainya: **New** → **Blueprint**, lalu pilih repo ini. Render
membaca `render.yaml` dan membuatkan service sesuai isinya.

Perhatikan perbedaan dua bentuk pada `envVars`:

- **`sync: false`** — "variabel ini ada, tapi nilainya **tidak** ditulis
  di berkas ini; tanyakan kepadaku saat deploy." Dipakai untuk semua
  yang rahasia (`DB_PASS`, `DB_USER`, `DB_HOST`).
- **`value: require`** — nilai ditulis langsung, karena `DB_SSLMODE`
  bukan rahasia.

Inilah cara menaruh konfigurasi di dalam Git tanpa ikut menaruh
rahasianya — kelanjutan langsung dari prinsip jobsheet-13.

Untuk satu service, mengisi formulir manual lebih sederhana. Blueprint
baru terasa berguna kalau kamu perlu membuat ulang service yang sama
berkali-kali, atau ingin konfigurasinya tercatat dalam riwayat Git.

## 4.8 Rangkuman Bab Ini

1. Render mengambil kode dari **GitHub**; memperbarui aplikasi = `git push`.
2. **Runtime: Docker** — kalau salah pilih, `Dockerfile` tidak dibaca.
3. **Root Directory: `kode-praktikum/jobsheet-14`** — kalau kosong,
   `Dockerfile not found`.
4. Kredensial diisi sebagai **environment variable di dashboard**,
   tidak pernah di dalam repo.
5. Baca tab **Logs**; deploy pertama 5–10 menit.
6. `render.yaml` menyimpan konfigurasi di Git dengan `sync: false`
   untuk bagian yang rahasia.

Lanjut ke: [Uji Coba & Troubleshooting](05-uji-coba-dan-troubleshooting.md)
