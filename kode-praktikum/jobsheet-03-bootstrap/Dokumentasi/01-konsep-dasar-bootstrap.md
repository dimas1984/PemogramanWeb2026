# 1. Konsep Dasar Bootstrap

## 1.1 Apa itu Bootstrap?

**Bootstrap** adalah **framework CSS** (dan sedikit JavaScript) yang
dibuat oleh Twitter, berisi kumpulan siap pakai dari:

- **Utility class** — class CSS kecil dengan satu tugas spesifik, misalnya
  `.text-center` (rata tengah), `.mb-3` (margin bawah), `.shadow-sm`
  (bayangan tipis). Alih-alih menulis CSS sendiri di `style.css`, kamu
  tinggal menempelkan nama class ini ke HTML.
- **Komponen siap pakai** — potongan HTML+CSS+JS yang sudah didesain,
  seperti navbar, card, tombol, alert, modal, dsb. Kamu tinggal menyalin
  struktur HTML-nya dan komponen itu langsung berfungsi.
- **Grid system 12 kolom** — sistem tata letak berbasis `.row` dan
  `.col-*` untuk membagi halaman jadi kolom-kolom responsif (dibahas
  detail di [bab 4](04-grid-dan-card.md)).

Ini adalah kebalikan filosofi dari
[jobsheet-03 versi CSS murni](../../jobsheet-03/Dokumentasi/01-konsep-dasar-responsive.md):
di sana **semua** aturan CSS (flexbox navbar, grid kartu, checkbox hack,
media query) ditulis sendiri dari nol di `style.css`. Di versi ini,
sebagian besar pekerjaan itu sudah "dibungkus" jadi class-class yang
tinggal dipakai.

## 1.2 Cara Memuat Bootstrap: CDN

Jobsheet ini memuat Bootstrap lewat **CDN** (Content Delivery Network) —
artinya file CSS dan JS Bootstrap **tidak disimpan** di dalam folder
proyek, melainkan diambil langsung dari server publik jsDelivr setiap
halaman dibuka:

```html
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
```

```html
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
```

| Bagian | Artinya |
|---|---|
| `<link ... rel="stylesheet">` | Memuat file **CSS** Bootstrap — berisi semua definisi class (`.navbar`, `.card`, `.btn`, dst). Diletakkan di `<head>`, sama seperti `style.css` sendiri. |
| `<script src="...bundle.min.js">` | Memuat file **JavaScript** Bootstrap — dibutuhkan untuk komponen yang punya interaksi (buka/tutup navbar mobile, dropdown, modal). Diletakkan di akhir `<body>`, **setelah** semua konten, supaya halaman tampil dulu sebelum skrip dijalankan. |
| `bootstrap@5.3.3` | Versi Bootstrap yang dipakai — dikunci di angka `5.3.3` (bukan `@latest`) supaya tampilan tidak berubah tiba-tiba kalau Bootstrap merilis versi baru. |
| `.bundle.min.js` | Kata `bundle` berarti file ini sudah **menyertakan** library Popper.js di dalamnya (dibutuhkan untuk dropdown/tooltip). Kata `min` berarti sudah **diminifikasi** (dipadatkan, spasi & komentar dibuang) supaya ukuran file lebih kecil dan cepat diunduh. |

**Kelebihan CDN:** tidak perlu instalasi apa pun (`npm install`, dsb),
tinggal tempel dua baris ini dan Bootstrap langsung aktif — cocok untuk
belajar dan proyek kecil.

**Kekurangannya:** halaman butuh **koneksi internet aktif** untuk memuat
Bootstrap; kalau CDN sedang down atau internet mati, halaman akan tampil
tanpa gaya Bootstrap sama sekali (HTML polos). Untuk proyek produksi
skala besar, Bootstrap biasanya di-install lewat `npm` dan disatukan ke
proses build sendiri — di luar cakupan jobsheet ini.

## 1.3 Filosofi Utility-First: Styling Lewat Nama Class

Bandingkan dua cara menulis "judul kartu berwarna biru dengan margin
bawah" berikut:

**Cara CSS murni** (dari [jobsheet-02](../../jobsheet-02/Dokumentasi/README.md)):
```css
section h2 {
    margin-bottom: 1rem;
    color: #1d5b8a;
}
```
```html
<h2>Ringkasan</h2>
```

**Cara Bootstrap** (dipakai di jobsheet ini):
```html
<h2 class="card-title mb-3" style="color:#1d5b8a;">Ringkasan</h2>
```

Bootstrap menyediakan `mb-3` (margin-bottom level 3, setara `1rem`)
sebagai **utility class siap pakai**, jadi tidak perlu menulis aturan CSS
terpisah di file lain. Warna biru `#1d5b8a` di jobsheet ini tetap ditulis
lewat atribut `style` langsung karena itu adalah warna brand khusus
SIMPUS-Mini yang bukan bagian dari palet warna bawaan Bootstrap — hal ini
dibahas lebih lanjut di [bab 6 §6.3](06-rangkuman-dan-perbandingan.md#63-kapan-tetap-perlu-css-custom).

Pola penamaan `mb-3` mengikuti skema **spacing scale** Bootstrap:

| Class | Ukuran |
|---|---|
| `mb-0` | `margin-bottom: 0` |
| `mb-1` | `0.25rem` |
| `mb-2` | `0.5rem` |
| `mb-3` | `1rem` |
| `mb-4` | `1.5rem` |
| `mb-5` | `3rem` |

Pola yang sama berlaku untuk arah lain: `mt-*` (margin-top), `ms-*`
(margin-start/kiri), `me-*` (margin-end/kanan), `p-*` (padding semua
sisi), dan seterusnya.

## 1.4 Breakpoint Bawaan Bootstrap

Alih-alih menulis `@media (max-width: 768px)` sendiri seperti di
[jobsheet-03 versi CSS murni](../../jobsheet-03/Dokumentasi/05-css-media-query-breakpoint.md),
Bootstrap sudah punya 6 breakpoint standar yang dipakai konsisten di
**semua** class responsifnya (grid, navbar, utility, dst):

| Breakpoint | Infix (sisipan nama class) | Lebar Minimal |
|---|---|---|
| Extra small | *(tanpa infix)* | `<576px` |
| Small | `sm` | `≥576px` |
| Medium | `md` | `≥768px` |
| Large | `lg` | `≥992px` |
| Extra large | `xl` | `≥1200px` |
| Extra extra large | `xxl` | `≥1400px` |

Contoh pemakaian: class `col-md-4` (dipakai di [bab 4](04-grid-dan-card.md))
berarti "lebar kolom 4/12 bagian, **mulai dari** lebar layar `md`
(≥768px) ke atas" — di bawah itu (layar HP), kolom otomatis melebar
penuh. Perhatikan arah logikanya **mobile-first**: gaya dasar (tanpa
infix) berlaku untuk layar **paling sempit**, lalu class berinfix
menambahkan aturan untuk layar yang **lebih lebar** — kebalikan dari
strategi *desktop-first* (`max-width`) yang dipakai di
[jobsheet-03 versi CSS murni](../../jobsheet-03/Dokumentasi/01-konsep-dasar-responsive.md#15-pendekatan-desktop-first-yang-dipakai-di-jobsheet-ini).

Karena breakpoint ini sudah "terpasang" di dalam setiap komponen
Bootstrap, jobsheet ini **tidak perlu menulis satupun** blok `@media`
manual — bandingkan dengan `style.css` jobsheet-03 asli yang punya 2
blok `@media` custom.

Lanjut ke: [Apa yang Berubah di File HTML?](02-perubahan-file-html.md)
