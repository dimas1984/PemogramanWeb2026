# 2. Apa yang Berubah di File HTML?

Struktur besar 5 halaman (Beranda, Daftar Buku, Tambah Buku, Daftar
Anggota, Tambah Anggota) tetap sama seperti
[jobsheet-03 versi CSS murni](../../jobsheet-03/Dokumentasi/02-perubahan-file-html.md),
tapi hampir setiap tag mendapat tambahan atribut `class="..."` berisi
nama-nama utility/komponen Bootstrap, dan `<meta viewport>` tetap
dipertahankan karena masih wajib untuk responsive design (lihat
[dokumentasi jobsheet-03 §1.2](../../jobsheet-03/Dokumentasi/01-konsep-dasar-responsive.md#12-meta-nameviewport--supaya-browser-hp-tidak-berbohong)).

## 2.1 Dua Tag Baru di `<head>` dan Akhir `<body>`

```html
<head>
    ...
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    ...
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
```

Urutan **penting**:

1. CSS Bootstrap dimuat **lebih dulu** di `<head>`.
2. `style.css` milik proyek sendiri dimuat **setelah** CSS Bootstrap —
   supaya kalau ada aturan yang bentrok, override kecil kita
   ([bab 6](06-rangkuman-dan-perbandingan.md)) tetap menang (ingat aturan
   "yang ditulis belakangan menang" dari
   [dokumentasi jobsheet-03 §1.5](../../jobsheet-03/Dokumentasi/01-konsep-dasar-responsive.md#15-pendekatan-desktop-first-yang-dipakai-di-jobsheet-ini)).
3. JavaScript Bootstrap diletakkan di **paling akhir** `<body>`, bukan di
   `<head>` — supaya browser merender seluruh konten HTML terlebih dahulu
   sebelum mengunduh & menjalankan skrip, membuat halaman terasa lebih
   cepat tampil.

## 2.2 `<header>` Diganti Struktur Komponen `.navbar`

**Sebelumnya** (checkbox hack, lihat
[dokumentasi jobsheet-03 bab 2 §2.2](../../jobsheet-03/Dokumentasi/02-perubahan-file-html.md#22-pasangan-checkbox-dan-label-untuk-hamburger-menu)):
```html
<header>
    <h1>SIMPUS-Mini</h1>
    <input type="checkbox" id="nav-toggle" class="nav-toggle">
    <label for="nav-toggle" class="nav-toggle-label">&#9776;</label>
    <nav>...</nav>
</header>
```

**Sekarang:**
```html
<header class="navbar navbar-expand-lg navbar-dark" style="background-color:#1d5b8a;">
    <div class="container">
        <a class="navbar-brand fw-semibold" href="index.html">SIMPUS-Mini</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu" aria-controls="navMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <nav class="collapse navbar-collapse" id="navMenu">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link active" href="index.html">Beranda</a></li>
                ...
            </ul>
        </nav>
    </div>
</header>
```

Tidak ada lagi `<input type="checkbox">` — komponen navbar Bootstrap
membutuhkan tombol `<button>` sungguhan yang dikontrol lewat atribut
`data-bs-*` dan JavaScript Bootstrap, bukan lagi checkbox tersembunyi.
Detail lengkap tiap bagiannya dibahas di
[bab 3](03-navbar-responsive-bootstrap.md).

## 2.3 `<div class="container">` — Pembatas Lebar Konten

```html
<div class="container">
    ...
</div>
```

Ini menggantikan peran `main { max-width: 1000px; margin: 0 auto; }`
dari
[dokumentasi jobsheet-02](../../jobsheet-02/Dokumentasi/05-css-main-dan-section.md#52-membatasi-lebar--menengahkan-konten-main):
class `.container` bawaan Bootstrap otomatis memberi `max-width`
berbeda-beda **mengikuti breakpoint aktif** (lihat
[bab 1 §1.4](01-konsep-dasar-bootstrap.md#14-breakpoint-bawaan-bootstrap)),
serta padding kiri-kanan dan `margin: 0 auto` untuk menengahkan
kontennya — semua dalam **satu** nama class, tanpa CSS tambahan apa pun.
Jobsheet ini memakainya baik di dalam `<header>` (membatasi lebar isi
navbar) maupun di `<main class="container my-4">` (membatasi lebar
konten halaman, `my-4` = margin atas-bawah level 4).

## 2.4 `<section>` Diganti `<div class="card">`

**Sebelumnya:**
```html
<section>
    <h2>Ringkasan</h2>
    ...
</section>
```

**Sekarang:**
```html
<section class="card shadow-sm mb-4">
    <div class="card-body">
        <h2 class="card-title mb-3" style="color:#1d5b8a;">Ringkasan</h2>
        ...
    </div>
</section>
```

Tag `<section>` (elemen semantic HTML5, tetap dipertahankan sesuai
[konsep tag semantic di jobsheet-01](../../jobsheet-01/Dokumentasi/01-konsep-dasar.md#13-tag-semantic-html5))
sekarang **juga** diberi class `.card` dari Bootstrap, ditambah
`<div class="card-body">` di dalamnya sebagai pembungkus wajib komponen
card. Dijelaskan lengkap di [bab 4](04-grid-dan-card.md).

## 2.5 Kartu Statistik: dari `<article>` ke `.row`/`.col-md-4`

**Sebelumnya** ([dokumentasi jobsheet-02](../../jobsheet-02/Dokumentasi/06-css-grid-kartu-statistik.md)):
```html
<section>
    <h2>Ringkasan</h2>
    <article>
        <h3>Total Buku</h3>
        <p>12</p>
    </article>
    ...
</section>
```

**Sekarang:**
```html
<div class="row g-3 text-center">
    <div class="col-12 col-md-4">
        <div class="p-3 rounded-3" style="background-color:#eef4fa;">
            <h3 class="h6 text-secondary">Total Buku</h3>
            <p class="fs-2 fw-bold mb-0" style="color:#1d5b8a;">12</p>
        </div>
    </div>
    ...
</div>
```

Tag `<article>` diganti `<div>` polos karena perannya sekarang murni
sebagai kolom grid Bootstrap (`col-12 col-md-4`), dijelaskan penuh di
[bab 4](04-grid-dan-card.md). Perhatikan `<h3 class="h6">` — trik umum
Bootstrap untuk memakai tag heading yang **semantically benar** (`h3`,
urutan ketiga setelah `h1` navbar dan `h2` judul section) tapi tampil
dengan **ukuran font** heading level lain (`h6`, lebih kecil) lewat
class utility, tanpa perlu menulis CSS custom untuk itu.

## 2.6 Tabel: `table-responsive` Tetap Dipakai, Tabelnya Ditambah Class

```html
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead style="background-color:#1d5b8a;">
            <tr class="text-white">
                <th>Judul</th>
                ...
```

Pembungkus `<div class="table-responsive">` **tidak berubah** dari
[dokumentasi jobsheet-03 §2.3](../../jobsheet-03/Dokumentasi/02-perubahan-file-html.md#23-pembungkus-div-classtable-responsive)
— kebetulan nama class ini identik antara CSS custom jobsheet-03 asli
dan Bootstrap, karena keduanya sama-sama menyelesaikan masalah yang
sama (scroll horizontal di layar sempit) dengan pola HTML yang sama.
Bedanya, class `table-responsive` di sini sudah **didefinisikan
Bootstrap sendiri**, jadi baris `.table-responsive { overflow-x: auto; }`
di `style.css` (lihat
[dokumentasi jobsheet-03 §4.1](../../jobsheet-03/Dokumentasi/04-css-table-responsive.md#41-kode-css))
tidak perlu ditulis ulang lagi. Detail class tabel lainnya
(`table-striped`, `table-hover`) dibahas di
[bab 5](05-tabel-dan-form-bootstrap.md).

## 2.7 Form: Setiap Isian Dibungkus `<div class="mb-3">`

**Sebelumnya** (pola `<p>` dari
[dokumentasi jobsheet-01 §4.3](../../jobsheet-01/Dokumentasi/04-buku-tambah-html.md#43-pola-setiap-isian-form-label--input)):
```html
<p>
    <label for="judul">Judul</label><br>
    <input type="text" id="judul" name="judul" required>
</p>
```

**Sekarang:**
```html
<div class="mb-3">
    <label for="judul" class="form-label fw-semibold">Judul</label>
    <input type="text" class="form-control" id="judul" name="judul" required>
</div>
```

Pembungkus `<p>` diganti `<div class="mb-3">` — secara semantic, sebuah
isian form (label + input) bukan benar-benar sebuah "paragraf teks",
jadi `<div>` polos yang diberi class utility spacing (`mb-3`) lebih
tepat, sekaligus konvensi resmi yang dipakai dokumentasi Bootstrap
sendiri untuk form. Atribut `for`/`id` yang menghubungkan label ke input
([konsepnya dari jobsheet-01](../../jobsheet-01/Dokumentasi/04-buku-tambah-html.md#43-pola-setiap-isian-form-label--input))
tetap **tidak berubah** — Bootstrap tidak mengubah perilaku HTML native
ini sama sekali, hanya menambah tampilan lewat class `form-label` dan
`form-control`. Detail lengkap dibahas di [bab 5](05-tabel-dan-form-bootstrap.md).

Lanjut ke: [Navbar Responsif ala Bootstrap](03-navbar-responsive-bootstrap.md)
