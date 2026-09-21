# Dokumentasi Jobsheet 14 — Deployment ke Internet (Render + Supabase)

Dokumentasi ini melanjutkan
[dokumentasi jobsheet-13](../../jobsheet-13/Dokumentasi/README.md)
(Deployment & Dokumentasi).

> **Materi pengayaan, di luar RPS 13 pertemuan.** Rangkaian resmi mata
> kuliah berakhir di jobsheet-13. Jobsheet-14 opsional — untuk yang
> ingin aplikasinya benar-benar hidup di internet.

## Kenapa Masih Ada Jobsheet 14?

Jobsheet-13 berjudul "Deployment & Dokumentasi", tapi kalau dibaca
teliti, yang benar-benar dikerjakan di sana adalah **persiapan**
deployment: kredensial dikeluarkan dari kode sumber ke environment
variable ([bab 1 jobsheet-13](../../jobsheet-13/Dokumentasi/01-konsep-dasar-deployment-config.md)),
README ditulis ulang, manual pengguna disusun. Aplikasinya sendiri
**tetap hanya berjalan di komputermu**.

Jobsheet-14 menuntaskan sisanya: memindahkan aplikasi ke server milik
orang lain, agar bisa dibuka siapa pun lewat tautan `https://`. Di
sinilah environment variable yang dibuat di jobsheet-13 akhirnya
**benar-benar terpakai** — bukan sekadar teori.

## Kabar Baik: Kode Aplikasi Nyaris Tidak Berubah

Sama seperti jobsheet-13, sebagian besar kode **tidak disentuh**.
Seluruh CRUD Buku/Anggota, autentikasi, proteksi CSRF, dan modul
Peminjaman **identik persis** dengan jobsheet-13 — sudah dibahas tuntas
di [dokumentasi jobsheet-07](../../jobsheet-07/Dokumentasi/README.md)
sampai [dokumentasi jobsheet-12](../../jobsheet-12/Dokumentasi/README.md).

Yang berubah hanya **2 berkas**, ditambah **3 berkas baru**:

| Berkas | Status | Dibahas di |
|---|---|---|
| `includes/config.php` | Diubah | [bab 3 §3.6](03-supabase-database-produksi.md#36-memetakan-connection-string-ke-environment-variable) |
| `includes/koneksi.php` | Diubah | [bab 3 §3.7](03-supabase-database-produksi.md#37-tiga-penyesuaian-di-koneksiphp) |
| `Dockerfile` | Baru | [bab 2](02-dockerfile-php-apache.md) |
| `.dockerignore` | Baru | [bab 2 §2.7](02-dockerfile-php-apache.md#27-dockerignore--menahan-berkas-yang-tak-perlu) |
| `render.yaml` | Baru | [bab 4 §4.7](04-deploy-ke-render.md#47-cara-alternatif-renderyaml-blueprint) |

## Daftar Isi

1. [Konsep Dasar: Hosting, Container, & Kenapa Render](01-konsep-dasar-hosting-container.md)
2. [Membedah `Dockerfile`: PHP + Apache + `pdo_pgsql`](02-dockerfile-php-apache.md)
3. [Supabase: Database PostgreSQL di Internet](03-supabase-database-produksi.md)
4. [Deploy ke Render: Langkah demi Langkah](04-deploy-ke-render.md)
5. [Uji Coba & Troubleshooting](05-uji-coba-dan-troubleshooting.md)
6. [Rangkuman & Latihan Lanjutan](06-rangkuman-latihan.md)

## Struktur Folder

```
jobsheet-14/
├── index.php, buku/, anggota/, auth/, peminjaman/   # Identik dengan jobsheet-13
├── assets/, sql/, docs/                              # Identik dengan jobsheet-13
├── includes/
│   ├── config.php                # Diubah — tambah db_sslmode & app_debug
│   ├── koneksi.php               # Diubah — SSL, pesan error aman, EMULATE_PREPARES
│   └── ...                       # header, footer, auth, csrf, helpers — tidak berubah
├── Dockerfile                     # BARU — resep container
├── .dockerignore                  # BARU
├── render.yaml                    # BARU — Render Blueprint (opsional)
├── README.md                      # BARU — ringkasan & 5 langkah deployment
└── Dokumentasi/                   # Folder dokumentasi ini
```

## Yang Kamu Butuhkan Sebelum Mulai

| Kebutuhan | Biaya | Catatan |
|---|---|---|
| Akun GitHub | Gratis | Repo praktikum ini sudah ada di GitHub |
| Akun [Supabase](https://supabase.com) | Gratis | Daftar dengan akun GitHub-mu |
| Akun [Render](https://render.com) | Gratis | Daftar dengan akun GitHub-mu |
| Docker di komputer | — | **Tidak wajib.** Render yang membangun image-nya |

Tidak ada kartu kredit yang diperlukan untuk jalur di dokumentasi ini.

## Peringatan Kejujuran

Bagian ini memisahkan mana yang sudah dibuktikan dan mana yang belum,
supaya kamu tahu di mana harus lebih waspada.

**Kode aplikasinya sudah diuji.** Perubahan pada `includes/config.php`
dan `includes/koneksi.php` sudah dijalankan terhadap PostgreSQL 14.5
sungguhan: koneksi, CRUD, registrasi & login, transaksi peminjaman
(`SELECT … FOR UPDATE`), `rollBack`, `JOIN` riwayat, proteksi CSRF, dan
ketahanan SQL injection — semuanya masih berfungsi. Rinciannya di
[README jobsheet](../README.md#sudah-diuji--kode-aplikasinya).

**Bagian deployment-nya belum.** `Dockerfile` belum pernah di-*build*
(Docker tidak terpasang di mesin penyusun), dan belum ada deployment
nyata ke Render maupun koneksi nyata ke Supabase. Nilai-nilai di
[bab 3](03-supabase-database-produksi.md) — host pooler, port `6543`,
format `postgres.<project-ref>`, `sslmode=require` — disusun dari
dokumentasi resmi, bukan dari pengamatan langsung.

Artinya: **nama menu dan tata letak antarmuka Render/Supabase bisa
berbeda** dari yang tertulis di sini, karena kedua layanan itu sering
memperbarui tampilannya. Pahami *maksud* tiap langkah — apa yang sedang
dicari dan mengapa — supaya kamu tetap bisa menemukannya walau namanya
sudah berganti. [Bab 5](05-uji-coba-dan-troubleshooting.md) membekali
kamu membaca pesan error saat ada yang meleset.

Lanjut ke: [Konsep Dasar: Hosting, Container, & Kenapa Render](01-konsep-dasar-hosting-container.md)
