# 6. Rangkuman & Perbandingan dengan CSS Murni

## 6.1 Rangkuman Keseluruhan

| Bagian | Lokasi Kode | Konsep yang Dipelajari |
|---|---|---|
| [Konsep Dasar Bootstrap](01-konsep-dasar-bootstrap.md) | `<link>`/`<script>` CDN | Framework CSS, utility-first, CDN, breakpoint bawaan |
| [Perubahan HTML](02-perubahan-file-html.md) | Semua file `.html` | `.container`, `.card`, pola pembungkus form `.mb-3` |
| [Navbar Responsif](03-navbar-responsive-bootstrap.md) | `.navbar`, `.navbar-toggler`, `.collapse` | Komponen berbasis JavaScript, atribut `data-bs-*`, aksesibilitas (`aria-*`) |
| [Grid & Card](04-grid-dan-card.md) | `.row`, `.col-*`, `.card` | Grid 12 kolom, pendekatan mobile-first Bootstrap |
| [Tabel & Form](05-tabel-dan-form-bootstrap.md) | `.table-*`, `.btn-*`, `.form-control` | Utility class semantik (warna berdasarkan makna), styling form tanpa CSS custom |

## 6.2 Tabel Perbandingan Class Bootstrap vs CSS Murni

Ringkasan seluruh pemetaan yang sudah dibahas di bab-bab sebelumnya:

| Kebutuhan | CSS Murni (jobsheet-03 asli) | Bootstrap (jobsheet ini) |
|---|---|---|
| Membatasi & menengahkan lebar konten | `main { max-width: 1000px; margin: 0 auto; }` | `.container` |
| Kartu putih dengan bayangan | `section { border-radius: 8px; box-shadow: ...; }` | `.card` + `.shadow-sm` |
| Grid 3 kolom kartu statistik | `display: grid; grid-template-columns: repeat(3, 1fr);` | `.row` + `.col-md-4` |
| Navbar hamburger | Checkbox hack (`:checked` + `~`) | `.navbar-toggler` + `.collapse` (JS) |
| Tabel belang & hover | `nth-child(even)`, `:hover` | `.table-striped`, `.table-hover` |
| Tabel scroll horizontal | `.table-responsive { overflow-x: auto; }` (custom) | `.table-responsive` (bawaan, nama sama) |
| Tombol warna | `td button:first-of-type { background: ...; }` | `.btn-warning`, `.btn-danger` |
| Input & select form | `form input { width:100%; padding:...; border:...; }` | `.form-control`, `.form-select` |
| Breakpoint tablet/mobile | `@media (max-width: 768px)` / `(max-width: 480px)` custom | Infix bawaan: `sm`, `md`, `lg`, `xl`, `xxl` |
| Baris CSS custom dibutuhkan | ~245 baris | ~15 baris |

## 6.3 Kapan Tetap Perlu CSS Custom?

Meski Bootstrap dipakai di hampir seluruh halaman, jobsheet ini
**tidak sepenuhnya bebas** dari CSS/atribut `style` manual. Perhatikan
warna `#1d5b8a` (biru SIMPUS-Mini) muncul berkali-kali lewat atribut
`style="..."` langsung di HTML ([bab 4 §4.4](04-grid-dan-card.md#44-styling-isi-tiap-kartu-statistik),
[bab 5 §5.1](05-tabel-dan-form-bootstrap.md#51-tabel-table-table-striped-table-hover)),
dan file [`assets/css/style.css`](../assets/css/style.css) masih berisi
beberapa baris:

```css
.navbar-brand,
.nav-link {
    color: #fff !important;
}

.nav-link:hover,
.nav-link.active {
    color: #d9e8f5 !important;
}

form button[type="submit"]:hover,
button[type="submit"]:hover {
    background-color: #164869 !important;
}
```

Ini terjadi karena **palet warna tema bawaan Bootstrap** (`primary` =
biru `#0d6efd`, `secondary` = abu-abu, dst) **berbeda** dari warna brand
SIMPUS-Mini (`#1d5b8a`) yang sudah ditentukan sejak
[jobsheet-01](../../jobsheet-01/README.md). Ada dua pilihan setiap kali
ini terjadi:

1. **Pakai warna tema bawaan Bootstrap apa adanya** (`.btn-primary`,
   `.bg-primary`, dst) — paling cepat dikerjakan, tapi warnanya jadi
   biru default Bootstrap, bukan biru brand SIMPUS-Mini.
2. **Tetap pakai warna brand custom** lewat atribut `style` atau sedikit
   CSS tambahan — seperti yang dipilih jobsheet ini, demi menjaga
   identitas visual tetap sama dengan
   [jobsheet-03 versi CSS murni](../../jobsheet-03/README.md).

Perhatikan tanda seru (`!important`) pada 3 aturan di atas — dipakai
karena Bootstrap sendiri sudah mendefinisikan warna default untuk
`.nav-link` dkk dengan spesifisitas tertentu; `!important` diperlukan
di sini supaya warna brand custom benar-benar menang menimpa aturan
bawaan Bootstrap tersebut. Ini konsekuensi umum ketika mengombinasikan
warna custom di atas framework siap pakai — pemakaian `!important` yang
berlebihan sebenarnya sering dianggap "bau kode" (code smell) karena
menyulitkan override berikutnya, jadi sebaiknya dipakai secukupnya saja
seperti di sini, bukan di semua tempat.

**Kesimpulannya:** Bootstrap sangat mempercepat pengerjaan tata letak
dan komponen umum, tapi begitu ada kebutuhan **desain brand yang
spesifik** (warna, font khusus, dst di luar tema bawaan), tetap
dibutuhkan sedikit CSS custom untuk menyesuaikannya — Bootstrap
menggantikan **sebagian besar** pekerjaan CSS, bukan seluruhnya.

## 6.4 Ide Latihan Tambahan (Opsional)

1. **Ganti warna brand ke tema bawaan Bootstrap** — hapus semua
   `style="background-color:#1d5b8a;"` dan `style="color:#1d5b8a;"`,
   ganti dengan class bawaan seperti `.bg-primary`/`.text-primary`, lalu
   bandingkan seberapa banyak baris `style.css` yang jadi tidak
   diperlukan lagi.
2. **Tambah breakpoint ketiga** di grid kartu statistik — sisipkan
   `col-sm-6` di antara `col-12` dan `col-md-4`
   ([bab 4 §4.3](04-grid-dan-card.md#43-grid-12-kolom-row-dan-col-)) supaya
   ada juga tampilan 2 kolom di breakpoint `sm`, meniru progresi 3
   tingkat dari [versi CSS murni](../../jobsheet-03/Dokumentasi/05-css-media-query-breakpoint.md#54-grid-kartu-statistik-di-mobile-1-kolom).
3. **Ganti breakpoint navbar** dari `.navbar-expand-lg` menjadi
   `.navbar-expand-md`, amati di lebar layar berapa navbar mulai
   "terlipat" jadi hamburger — buktikan bahwa breakpoint Bootstrap bisa
   diganti hanya lewat nama class, tanpa CSS tambahan sama sekali.
4. **Tambahkan komponen Bootstrap baru** yang belum dipakai jobsheet
   ini, misalnya
   [`.badge`](https://getbootstrap.com/docs/5.3/components/badge/) untuk
   menandai status "Tersedia"/"Kosong" di kolom Stok pada
   `buku/list.html`, atau
   [`.alert`](https://getbootstrap.com/docs/5.3/components/alerts/)
   untuk menampilkan pesan sukses setelah form disimpan.
5. **Bandingkan ukuran file** — buka DevTools tab **Network**, refresh
   `index.html` versi Bootstrap ini dan bandingkan total ukuran yang
   diunduh (termasuk CSS+JS Bootstrap dari CDN) dengan versi
   [jobsheet-03 CSS murni](../../jobsheet-03/index.html) yang hanya
   memuat satu file `style.css` kecil — diskusikan trade-off ukuran
   unduhan vs kecepatan pengembangan.

Kalau ada bagian yang masih membingungkan, terutama soal grid 12 kolom
atau atribut `data-bs-*`, coba baca ulang
[bab 3](03-navbar-responsive-bootstrap.md) dan
[bab 4](04-grid-dan-card.md) sambil membuka
[dokumentasi resmi Bootstrap 5.3](https://getbootstrap.com/docs/5.3/getting-started/introduction/)
di tab browser terpisah untuk menjelajahi komponen lain yang tersedia.
