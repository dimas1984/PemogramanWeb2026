# 3. Navbar Responsif ala Bootstrap

Bab ini membedah bagian yang paling banyak berubah dari
[versi CSS murni](../../jobsheet-03/Dokumentasi/03-css-hamburger-checkbox-hack.md):
menu hamburger yang dulunya murni CSS (checkbox hack) sekarang memakai
komponen `.navbar` bawaan Bootstrap yang **membutuhkan JavaScript**
untuk animasi buka-tutupnya.

## 3.1 Struktur HTML Lengkap

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
                <li class="nav-item"><a class="nav-link" href="buku/list.html">Daftar Buku</a></li>
                ...
            </ul>
        </nav>
    </div>
</header>
```

## 3.2 Class `.navbar`, `.navbar-expand-lg`, `.navbar-dark`

| Class | Fungsi |
|---|---|
| `.navbar` | Menandai elemen ini sebagai komponen navbar Bootstrap — mengaktifkan seluruh perilaku dasarnya (flexbox internal, padding, dst). |
| `.navbar-expand-lg` | **Kunci responsivitasnya.** Berarti: navbar tampil **horizontal terbuka** (seperti desktop) mulai breakpoint `lg` (≥992px, lihat [bab 1 §1.4](01-konsep-dasar-bootstrap.md#14-breakpoint-bawaan-bootstrap)) ke atas. Di **bawah** `lg` (tablet & HP), navbar otomatis "terlipat" jadi tombol hamburger. |
| `.navbar-dark` | Mengatur skema warna teks/ikon navbar supaya **kontras di atas latar gelap** (teks putih, dsb) — cocok dipakai bersama `style="background-color:#1d5b8a;"` (biru tua) yang ditulis manual karena warna brand ini bukan salah satu tema warna bawaan Bootstrap. |

Bandingkan dengan pendekatan CSS murni yang hanya punya **satu**
breakpoint hamburger (`480px`, lihat
[dokumentasi jobsheet-03 §5.3](../../jobsheet-03/Dokumentasi/05-css-media-query-breakpoint.md#53-breakpoint-2-mobile-480px--perubahan-terbanyak)) —
di jobsheet ini, breakpoint kapan navbar "terlipat" bisa diganti hanya
dengan mengubah infix di nama class (`navbar-expand-md`,
`navbar-expand-sm`, dst), tanpa menyentuh CSS sama sekali.

## 3.3 Tombol Hamburger: `.navbar-toggler`

```html
<button class="navbar-toggler" type="button"
        data-bs-toggle="collapse"
        data-bs-target="#navMenu"
        aria-controls="navMenu"
        aria-expanded="false"
        aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
</button>
```

Ini adalah **tombol asli** (`<button>`), bukan `<label>` yang berpura-pura
jadi tombol seperti di
[checkbox hack versi CSS murni](../../jobsheet-03/Dokumentasi/03-css-hamburger-checkbox-hack.md#33-langkah-2--label-berperan-sebagai-tombol-pengganti).
Beberapa atribut pentingnya:

| Atribut | Fungsi |
|---|---|
| `data-bs-toggle="collapse"` | Memberi tahu JavaScript Bootstrap: "tombol ini mengontrol komponen **collapse** (buka/tutup)". Ini adalah **data attribute** — atribut HTML custom berawalan `data-` yang boleh dibaca skrip, konsepnya mirip `id`/`class` tapi khusus dipakai sebagai "pengait" JavaScript, bukan untuk styling CSS. |
| `data-bs-target="#navMenu"` | Menentukan **elemen mana** yang dibuka/ditutup ketika tombol ini diklik — mengacu ke `id="navMenu"` pada `<nav>` di bawahnya. Konsepnya mirip `for="nav-toggle"` pada [checkbox hack](../../jobsheet-03/Dokumentasi/02-perubahan-file-html.md#22-pasangan-checkbox-dan-label-untuk-hamburger-menu) yang menghubungkan label ke checkbox lewat `id` — bedanya di sini yang membaca hubungan `id` ini adalah **JavaScript**, bukan CSS. |
| `aria-controls`, `aria-expanded`, `aria-label` | Atribut **aksesibilitas** (accessibility) untuk pembaca layar (screen reader) — menjelaskan tombol ini mengontrol elemen apa dan statusnya sedang terbuka/tertutup. Bootstrap otomatis mengubah nilai `aria-expanded` lewat JavaScript saat tombol diklik. |
| `<span class="navbar-toggler-icon">` | Elemen kosong yang diberi gaya CSS bawaan Bootstrap berupa **ikon garis tiga (☰)** memakai `background-image` — pengganti karakter HTML entity `&#9776;` yang dipakai manual di [versi CSS murni](../../jobsheet-03/Dokumentasi/02-perubahan-file-html.md#22-pasangan-checkbox-dan-label-untuk-hamburger-menu). |

## 3.4 Elemen yang Dibuka-Tutup: `.collapse.navbar-collapse`

```html
<nav class="collapse navbar-collapse" id="navMenu">
    <ul class="navbar-nav ms-auto">
        ...
    </ul>
</nav>
```

- `.collapse` — class umum Bootstrap untuk elemen yang bisa
  disembunyikan/ditampilkan dengan animasi geser (slide). Secara default
  elemen ini **tersembunyi** kalau ukuran layar di bawah breakpoint
  `.navbar-expand-lg`.
- `.navbar-collapse` — varian khusus `.collapse` yang disesuaikan untuk
  konteks navbar (misalnya otomatis kembali terlihat horizontal begitu
  layar ≥ breakpoint `lg`, tanpa perlu status "terbuka/tertutup" sama
  sekali di layar besar).
- `id="navMenu"` — inilah target yang dicari `data-bs-target="#navMenu"`
  dari tombol di [§3.3](#33-tombol-hamburger-navbar-toggler).
- `.navbar-nav` pada `<ul>` — mengubah daftar `<li>` di dalamnya jadi
  item navbar dengan spacing dan layout flexbox bawaan Bootstrap
  (menggantikan `header nav ul { display: flex; gap: 1.25rem; }` manual
  dari [dokumentasi jobsheet-02](../../jobsheet-02/Dokumentasi/04-css-header-navbar-flexbox.md)).
- `.ms-auto` (*margin-start: auto*) — mendorong seluruh menu ke **sisi
  kanan** navbar, menggantikan `justify-content: space-between` pada
  `<header>` dari CSS murni.

## 3.5 Kenapa Ini Butuh JavaScript, Sedangkan Checkbox Hack Tidak?

Perbedaan filosofis penting:

- **Checkbox hack** ([dokumentasi jobsheet-03 bab 3](../../jobsheet-03/Dokumentasi/03-css-hamburger-checkbox-hack.md))
  memanfaatkan pseudo-class `:checked` bawaan CSS pada elemen
  `<input type="checkbox">` — **tidak butuh JavaScript sama sekali**,
  tapi triknya cukup "curang" (meminjam perilaku checkbox untuk keperluan
  di luar konteks form) dan terbatas kemampuannya (tidak ada animasi
  transisi halus, sulit dikombinasikan dengan komponen lain).
- **Komponen `.collapse` Bootstrap** memakai JavaScript sungguhan
  (`bootstrap.bundle.min.js`, dimuat di [bab 2 §2.1](02-perubahan-file-html.md#21-dua-tag-baru-di-head-dan-akhir-body))
  yang menambah/menghapus class (misalnya `.show`) dan attribute style
  secara dinamis saat tombol diklik, sekaligus menjalankan animasi CSS
  transition untuk efek geser yang halus. Ini lebih "resmi" dan
  fleksibel, tapi **bergantung pada file JavaScript berhasil dimuat** —
  kalau CDN gagal diakses ([bab 1 §1.2](01-konsep-dasar-bootstrap.md#12-cara-memuat-bootstrap-cdn)),
  tombol hamburger tidak akan berfungsi sama sekali.

Ini adalah trade-off nyata antara dua pendekatan: **CSS murni** = tidak
ada dependensi eksternal tapi teknik lebih rumit ditulis sendiri;
**Bootstrap** = jauh lebih cepat dibangun & lebih banyak fitur, tapi
bergantung pada library pihak ketiga.

## 3.6 Cara Mencoba Sendiri

1. Buka `index.html` di browser, lebarkan jendela browser sampai lebih
   dari 992px — menu navigasi akan tampil sejajar horizontal (efek
   `.navbar-expand-lg`).
2. Sempitkan jendela browser (atau buka DevTools mode responsif,
   `Ctrl+Shift+M`) sampai di bawah 992px — menu berubah jadi ikon ☰ di
   kanan atas.
3. Klik ikon tersebut — perhatikan menu muncul dengan **animasi geser
   halus** dari atas, berbeda dengan checkbox hack yang muncul/hilang
   secara instan (`display: none` → `display: block` tidak beranimasi).
4. Buka tab **Network** di DevTools, refresh halaman, cari
   `bootstrap.bundle.min.js` — pastikan statusnya berhasil dimuat (200),
   sebagai bukti bahwa interaksi ini memang bergantung pada file
   JavaScript tersebut.

Lanjut ke: [Grid System & Komponen Card](04-grid-dan-card.md)
