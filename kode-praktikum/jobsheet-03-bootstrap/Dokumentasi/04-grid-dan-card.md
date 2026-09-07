# 4. Grid System & Komponen Card

Bab ini membahas penggantian dua hal dari
[jobsheet-02](../../jobsheet-02/Dokumentasi/06-css-grid-kartu-statistik.md):
CSS Grid manual (`display: grid; grid-template-columns: repeat(3, 1fr);`)
diganti **grid system 12 kolom Bootstrap**, dan `<section>` polos diganti
komponen `.card`.

## 4.1 Kode HTML Lengkap (Kartu Statistik di Beranda)

```html
<section class="card shadow-sm mb-4">
    <div class="card-body">
        <h2 class="card-title mb-3" style="color:#1d5b8a;">Ringkasan</h2>
        <div class="row g-3 text-center">
            <div class="col-12 col-md-4">
                <div class="p-3 rounded-3" style="background-color:#eef4fa;">
                    <h3 class="h6 text-secondary">Total Buku</h3>
                    <p class="fs-2 fw-bold mb-0" style="color:#1d5b8a;">12</p>
                </div>
            </div>
            <div class="col-12 col-md-4"> ... Total Anggota ... </div>
            <div class="col-12 col-md-4"> ... Sedang Dipinjam ... </div>
        </div>
    </div>
</section>
```

## 4.2 Komponen Card: `.card`, `.card-body`, `.card-title`

| Class | Fungsi |
|---|---|
| `.card` | Bingkai kotak dasar: border tipis, sudut membulat (`border-radius`), latar putih. Menggantikan CSS custom `section { border-radius: 8px; box-shadow: ...; }` dari [dokumentasi jobsheet-02 §5.3](../../jobsheet-02/Dokumentasi/05-css-main-dan-section.md#53-kartu-putih-untuk-setiap-section). |
| `.card-body` | Pembungkus **wajib** di dalam `.card` yang memberi padding dalam. Tanpa ini, isi kartu akan menempel langsung ke tepi border. |
| `.card-title` | Ditempelkan ke heading (di sini `<h2>`) di dalam card — memberi sedikit gaya khas judul kartu (margin bawah otomatis, dsb). |
| `.shadow-sm` | Bayangan halus di sekeliling card — pengganti `box-shadow: 0 1px 3px rgba(0,0,0,0.08);` custom dari CSS murni. Bootstrap menyediakan beberapa level: `.shadow-sm` (tipis), `.shadow` (sedang), `.shadow-lg` (tebal). |
| `.mb-4` | Jarak margin bawah antar-card, menggantikan `section { margin-bottom: 1.5rem; }`. |

Perhatikan pola ini **berulang** di semua halaman lain (`buku/list.html`,
`anggota/tambah.html`, dst) — setiap `<section>` konten utama dibungkus
`.card` + `.card-body` dengan pola yang identik, memberi tampilan
konsisten di seluruh aplikasi tanpa perlu mengulang definisi CSS.

## 4.3 Grid 12 Kolom: `.row` dan `.col-*`

Konsep dasar grid system Bootstrap: setiap baris (`.row`) dibagi menjadi
**12 kolom virtual** sama lebar. Class `.col-*` menentukan **berapa dari
12 kolom itu** yang dipakai suatu elemen.

```html
<div class="row g-3 text-center">
    <div class="col-12 col-md-4"> ... </div>
    <div class="col-12 col-md-4"> ... </div>
    <div class="col-12 col-md-4"> ... </div>
</div>
```

| Class | Artinya |
|---|---|
| `.row` | Wadah (container) untuk kolom-kolom di dalamnya — wajib ada sebagai pembungkus setiap kumpulan `.col-*`. Secara internal `display: flex` dengan sedikit negative margin untuk kompensasi padding kolom. |
| `.col-12` | Di layar **paling sempit** (tanpa infix breakpoint, `<768px`), kolom ini memakai **12 dari 12** bagian — artinya melebar **penuh** satu baris sendiri. |
| `.col-md-4` | Mulai breakpoint `md` (≥768px, lihat [bab 1 §1.4](01-konsep-dasar-bootstrap.md#14-breakpoint-bawaan-bootstrap)) ke atas, kolom ini memakai **4 dari 12** bagian — karena ada 3 kartu dan `4 × 3 = 12`, ketiganya pas sejajar dalam satu baris penuh. |
| `.g-3` | *Gutter* (jarak antar kolom) level 3 (`1rem`) — mengatur jarak horizontal **dan** vertikal antar kartu sekaligus, termasuk saat kartu ketiga turun ke baris baru di layar sempit. |
| `.text-center` | Utility class perataan teks ke tengah, diterapkan ke seluruh `.row` sehingga berlaku ke semua kolom di dalamnya. |

**Progresi responsifnya** (mobile-first, dari
[bab 1 §1.4](01-konsep-dasar-bootstrap.md#14-breakpoint-bawaan-bootstrap)):

| Lebar Layar | Class Aktif | Susunan Kartu |
|---|---|---|
| `<768px` (HP) | `.col-12` | 1 kolom, bertumpuk vertikal |
| `≥768px` (tablet/desktop) | `.col-md-4` | 3 kolom sejajar |

Dibandingkan [versi CSS murni](../../jobsheet-03/Dokumentasi/05-css-media-query-breakpoint.md#54-grid-kartu-statistik-di-mobile-1-kolom)
yang punya **3 tingkat** (3 kolom desktop → 2 kolom tablet → 1 kolom
mobile lewat 2 blok `@media` terpisah), versi Bootstrap ini disederhanakan
jadi **2 tingkat** saja (`col-12` lalu langsung `col-md-4`) — kamu bisa
menambah tingkat ketiga sendiri sebagai latihan, misalnya menyisipkan
`col-sm-6` di antaranya supaya ada juga tampilan 2-kolom di breakpoint
`sm` (lihat [bab 6 §6.4](06-rangkuman-dan-perbandingan.md#64-ide-latihan-tambahan)).

## 4.4 Styling Isi Tiap Kartu Statistik

```html
<div class="p-3 rounded-3" style="background-color:#eef4fa;">
    <h3 class="h6 text-secondary">Total Buku</h3>
    <p class="fs-2 fw-bold mb-0" style="color:#1d5b8a;">12</p>
</div>
```

| Class | Fungsi |
|---|---|
| `.p-3` | Padding semua sisi level 3 (`1rem`) — menggantikan `article { padding: 1.25rem; }`. |
| `.rounded-3` | Sudut membulat level 3 — menggantikan `article { border-radius: 8px; }`. |
| `.h6` (pada tag `<h3>`) | Trik "ukuran heading berbeda dari levelnya" yang sudah disinggung di [bab 2 §2.5](02-perubahan-file-html.md#25-kartu-statistik-dari-article-ke-rowcol-md-4) — tag tetap `<h3>` demi urutan heading semantic yang benar, tapi tampilan fontnya memakai ukuran `<h6>` yang lebih kecil, cocok untuk label kecil "Total Buku" dst. |
| `.text-secondary` | Warna teks abu-abu bawaan tema Bootstrap — menggantikan `color: #55677a;` custom. |
| `.fs-2` (*font-size 2*) | Skala ukuran font bawaan Bootstrap (dari `.fs-1` terbesar sampai `.fs-6` terkecil) — dipakai untuk angka besar statistik, menggantikan `font-size: 1.8rem;` custom. |
| `.fw-bold` | *Font-weight bold* — menggantikan `font-weight: 700;`. |
| `.mb-0` | Menghapus margin bawah default paragraf `<p>` supaya angka statistik menempel rapat ke label di atasnya. |

Satu-satunya nilai yang **tetap** ditulis manual lewat atribut `style`
adalah warna latar `#eef4fa` dan warna teks `#1d5b8a` — karena keduanya
adalah warna **brand khusus** SIMPUS-Mini, bukan bagian dari palet warna
tema bawaan Bootstrap (`primary`, `secondary`, `success`, dst). Ini
dibahas lebih dalam di [bab 6 §6.3](06-rangkuman-dan-perbandingan.md#63-kapan-tetap-perlu-css-custom).

Lanjut ke: [Tabel & Form dengan Utility Class Bootstrap](05-tabel-dan-form-bootstrap.md)
