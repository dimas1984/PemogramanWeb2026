# Dokumentasi Jobsheet 3 (Versi Bootstrap)

Dokumentasi ini adalah **versi alternatif** dari
[dokumentasi jobsheet-03](../../jobsheet-03/Dokumentasi/README.md) yang
memakai CSS murni (hand-written CSS). Fungsionalitas dan halamannya
**identik** — Beranda, Daftar Buku, Tambah Buku, Daftar Anggota, Tambah
Anggota — tapi seluruh tata letak dan komponen visualnya dibangun ulang
memakai **framework CSS Bootstrap 5**, bukan CSS custom dari nol.

Kalau kamu belum paham konsep responsive design dasar (viewport, media
query, breakpoint), baca dulu
[bab 1 dokumentasi jobsheet-03 asli](../../jobsheet-03/Dokumentasi/01-konsep-dasar-responsive.md)
karena dokumentasi ini akan sering membandingkan "cara CSS murni" vs
"cara Bootstrap" untuk menyelesaikan masalah yang sama.

## Kenapa Ada Dua Versi?

Tujuannya supaya kamu bisa membandingkan langsung **dua pendekatan** untuk
mencapai hasil visual yang mirip:

| | Jobsheet 3 (CSS Murni) | Jobsheet 3 (Bootstrap) |
|---|---|---|
| Layout | `display: flex`, `display: grid` ditulis manual | `.row` / `.col-*` (grid 12 kolom Bootstrap) |
| Navbar & hamburger | Checkbox hack (`:checked` + sibling combinator `~`) | Komponen `.navbar` bawaan + sedikit JavaScript Bootstrap |
| Kartu | `<section>` + CSS custom (`border-radius`, `box-shadow` manual) | Komponen `.card` bawaan |
| Tabel | `<table>` + CSS custom (`nth-child`, `:hover`) | Class utility `.table`, `.table-striped`, `.table-hover` |
| Form | `<input>`/`<select>` + CSS custom | Class utility `.form-control`, `.form-select`, `.form-label` |
| Breakpoint | Ditulis sendiri (`768px`, `480px`) | Bawaan Bootstrap (`sm`, `md`, `lg`, `xl`, `xxl`) |
| Total baris CSS custom | ~245 baris (`style.css`) | ~15 baris (`style.css`) |

Intinya: **hasil akhirnya bisa mirip, tapi cara mencapainya sangat
berbeda.** CSS murni memberi kontrol penuh tapi butuh menulis semua
aturan sendiri; Bootstrap memberi banyak komponen & utility class siap
pakai, dengan konsekuensi harus memuat file CSS/JS tambahan dan mengikuti
konvensi nama class-nya.

## Daftar Isi

1. [Konsep Dasar Bootstrap](01-konsep-dasar-bootstrap.md)
2. [Apa yang Berubah di File HTML?](02-perubahan-file-html.md)
3. [Navbar Responsif ala Bootstrap](03-navbar-responsive-bootstrap.md)
4. [Grid System & Komponen Card](04-grid-dan-card.md)
5. [Tabel & Form dengan Utility Class Bootstrap](05-tabel-dan-form-bootstrap.md)
6. [Rangkuman & Perbandingan dengan CSS Murni](06-rangkuman-dan-perbandingan.md)

## Struktur Folder

```
jobsheet-03-bootstrap/
├── index.html              # Beranda
├── assets/
│   └── css/
│       └── style.css       # Override kecil di atas Bootstrap (~15 baris)
├── buku/
│   ├── list.html
│   └── tambah.html
├── anggota/
│   ├── list.html
│   └── tambah.html
└── Dokumentasi/             # Folder dokumentasi ini
```

Tidak ada file JavaScript custom yang perlu ditulis — komponen navbar
Bootstrap sudah membawa JavaScript-nya sendiri lewat `bootstrap.bundle.min.js`
(dijelaskan di [bab 3](03-navbar-responsive-bootstrap.md)).

Silakan baca urut dari nomor 1, atau langsung loncat ke bagian yang ingin
dipahami.
