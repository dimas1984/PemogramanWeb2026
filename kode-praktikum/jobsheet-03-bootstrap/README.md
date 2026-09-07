# Jobsheet 3 (Versi Bootstrap) — Responsive Design dengan Framework

Sub-CPMK: Membangun tampilan responsif memakai framework CSS (Bootstrap 5).

Versi ini adalah alternatif dari [jobsheet-03](../jobsheet-03/README.md)
(CSS murni) — halaman dan fungsionalitasnya identik, tapi seluruh tata
letak dibangun memakai Bootstrap 5 (dimuat via CDN) alih-alih menulis
CSS dari nol.

## Perubahan dari Jobsheet 3 (CSS Murni)
- Bootstrap 5.3 dimuat via CDN (`bootstrap.min.css` + `bootstrap.bundle.min.js`).
- Navbar: hamburger memakai komponen `.navbar`/`.navbar-toggler`/`.collapse` bawaan Bootstrap (butuh JavaScript), menggantikan checkbox hack murni CSS.
- Kartu statistik: grid CSS manual diganti sistem grid 12 kolom Bootstrap (`.row`/`.col-md-4`).
- Section dibungkus komponen `.card` menggantikan styling `<section>` custom.
- Tabel & form memakai utility class Bootstrap (`.table-striped`, `.table-hover`, `.form-control`, `.btn-warning`, dst).
- `assets/css/style.css` menyusut dari ~245 baris menjadi ~15 baris (hanya override warna brand).

## Cara menjalankan
Buka `index.html` di browser (butuh koneksi internet karena Bootstrap dimuat dari CDN), uji dengan DevTools responsive mode pada breakpoint Bootstrap (mobile <768px, tablet ~768–991px, desktop ≥992px).

## Dokumentasi
Penjelasan lengkap tiap perubahan ada di folder [Dokumentasi/](Dokumentasi/README.md), termasuk tabel perbandingan class Bootstrap vs CSS murni.

## Catatan
- Warna brand SIMPUS-Mini (`#1d5b8a`) bukan bagian tema bawaan Bootstrap, sehingga tetap ditulis manual lewat atribut `style` dan sedikit CSS custom — lihat [Dokumentasi bab 6 §6.3](Dokumentasi/06-rangkuman-dan-perbandingan.md#63-kapan-tetap-perlu-css-custom).
