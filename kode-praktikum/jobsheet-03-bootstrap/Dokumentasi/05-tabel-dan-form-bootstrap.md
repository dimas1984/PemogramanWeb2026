# 5. Tabel & Form dengan Utility Class Bootstrap

## 5.1 Tabel: `.table`, `.table-striped`, `.table-hover`

Kode dari `buku/list.html` dan `anggota/list.html`:

```html
<div class="table-responsive">
    <table class="table table-striped table-hover align-middle">
        <thead style="background-color:#1d5b8a;">
            <tr class="text-white">
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Tahun</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Laskar Pelangi</td>
                ...
                <td>
                    <button type="button" class="btn btn-warning btn-sm text-white">Edit</button>
                    <button type="button" class="btn btn-danger btn-sm">Hapus</button>
                </td>
            </tr>
            ...
        </tbody>
    </table>
</div>
```

| Class | Fungsi | Menggantikan (CSS Murni) |
|---|---|---|
| `.table` | Class dasar wajib — memberi spacing sel, border tipis antar baris, dan lebar penuh (`width: 100%`). | `table { width: 100%; border-collapse: collapse; } th, td { padding: ...; border-bottom: ...; }` |
| `.table-striped` | Baris genap-ganjil otomatis diberi warna latar berselang-seling. | `tbody tr:nth-child(even) { background-color: #f7f9fb; }` |
| `.table-hover` | Baris berubah warna latar saat kursor mouse diarahkan ke atasnya. | `tbody tr:hover { background-color: #eef4fa; }` |
| `.align-middle` | Isi setiap sel (`<td>`) rata tengah **secara vertikal** — berguna khususnya di kolom "Aksi" supaya tombol Edit/Hapus sejajar rapi dengan teks di kolom lain. | *(tidak ada di versi CSS murni)* |
| `.table-responsive` (pada `<div>` pembungkus) | Scroll horizontal otomatis kalau tabel lebih lebar dari layar. | `.table-responsive { overflow-x: auto; }` — **nama classnya kebetulan identik**, lihat [dokumentasi jobsheet-03 §2.3](../../jobsheet-03/Dokumentasi/02-perubahan-file-html.md#23-pembungkus-div-classtable-responsive). |

Warna header tabel (`background-color:#1d5b8a`) dan teks putihnya
(`.text-white` pada `<tr>`) tetap ditulis manual dengan alasan yang sama
seperti [bab 4 §4.4](04-grid-dan-card.md#44-styling-isi-tiap-kartu-statistik):
warna brand khusus, bukan bagian tema bawaan Bootstrap.

## 5.2 Tombol Aksi: `.btn`, `.btn-warning`, `.btn-danger`, `.btn-sm`

```html
<button type="button" class="btn btn-warning btn-sm text-white">Edit</button>
<button type="button" class="btn btn-danger btn-sm">Hapus</button>
```

Bootstrap menyediakan sistem warna tombol **semantik** (berdasarkan
makna/fungsi, bukan sekadar warna):

| Class | Warna Bawaan | Dipakai untuk |
|---|---|---|
| `.btn-primary` | Biru | Aksi utama (tidak dipakai di jobsheet ini karena warna brand SIMPUS-Mini berbeda dari biru default Bootstrap) |
| `.btn-warning` | Kuning/oranye | Tombol **Edit** — menandakan "hati-hati, akan mengubah data" |
| `.btn-danger` | Merah | Tombol **Hapus** — menandakan aksi destruktif |
| `.btn-sm` | *(bukan warna)* | Memperkecil ukuran tombol (padding & font lebih kecil), cocok untuk tombol di dalam sel tabel yang sempit |

Dua class ini menggantikan CSS custom:
```css
td button:first-of-type { background-color: #f0ad4e; color: #fff; }
td button:last-of-type  { background-color: #d9534f; color: #fff; }
```
dari [dokumentasi jobsheet-02](../../jobsheet-02/Dokumentasi/07-css-tabel.md#76-tombol-aksi-edit--hapus).
Selector `:first-of-type`/`:last-of-type` yang **bergantung urutan**
tombol di HTML (rawan salah kalau urutan tombol berubah) digantikan
class yang **eksplisit menempel** ke tombol yang dimaksud — pendekatan
Bootstrap ini lebih tahan terhadap perubahan struktur HTML di kemudian
hari. Tambahan `.text-white` pada tombol Edit karena warna default teks
`.btn-warning` adalah gelap (agar kontras dengan latar kuning terangnya),
sedangkan jobsheet ini tetap mengikuti gaya teks putih dari
[versi CSS murni](../../jobsheet-03/Dokumentasi/../02-perubahan-file-html.md).

## 5.3 Form: `.form-label`, `.form-control`, `.form-select`

Kode dari `buku/tambah.html`:

```html
<form>
    <div class="mb-3">
        <label for="judul" class="form-label fw-semibold">Judul</label>
        <input type="text" class="form-control" id="judul" name="judul" required>
    </div>
    ...
    <div class="mb-3">
        <label for="kategori" class="form-label fw-semibold">Kategori</label>
        <select class="form-select" id="kategori" name="kategori">
            <option value="fiksi">Fiksi</option>
            ...
        </select>
    </div>
    <button type="submit" class="btn" style="background-color:#1d5b8a; color:#fff;">Simpan</button>
</form>
```

| Class | Ditempelkan ke | Fungsi | Menggantikan |
|---|---|---|---|
| `.form-label` | `<label>` | Jarak margin bawah label yang konsisten dan sedikit penyesuaian tampilan. | `form label { display: block; margin-bottom: 0.35rem; }` |
| `.fw-semibold` | `<label>` | Ketebalan font semi-bold, agar label tetap menonjol. | `form label { font-weight: 600; color: #444; }` |
| `.form-control` | `<input>` | Lebar penuh, padding nyaman, border, dan **efek fokus** (garis biru saat elemen diklik) — semuanya otomatis. | `form input { width: 100%; padding: 0.55rem 0.7rem; border: 1px solid #cdd4da; border-radius: 4px; }` |
| `.form-select` | `<select>` | Sama seperti `.form-control` tapi khusus dropdown, termasuk ikon panah bawah otomatis. | `form select { ... }` (aturan sama dengan input di CSS murni) |

Perhatikan atribut `for`, `id`, `name`, `type`, `required`, `min`, `max`
pada setiap input **tidak berubah sama sekali** dari
[versi CSS murni](../../jobsheet-03/Dokumentasi/02-perubahan-file-html.md) —
semua itu adalah perilaku HTML native (validasi form bawaan browser,
hubungan label-input) yang **sepenuhnya independen** dari framework CSS
apa pun yang dipakai. Bootstrap hanya menambah **tampilan** lewat class,
tidak pernah mengubah cara kerja HTML form itu sendiri.

Tombol submit `<button type="submit" class="btn" style="background-color:#1d5b8a; color:#fff;">`
memakai class dasar `.btn` saja (bukan `.btn-primary` dkk) karena warna
birunya adalah warna brand khusus yang ditulis manual — pola yang sama
seperti dibahas di [bab 4 §4.4](04-grid-dan-card.md#44-styling-isi-tiap-kartu-statistik).
Efek hover-nya (`background-color: #164869`) didefinisikan di
`style.css` (dibahas di [bab 6](06-rangkuman-dan-perbandingan.md)) karena
Bootstrap tidak tahu warna hover yang tepat untuk warna brand custom ini.

Lanjut ke: [Rangkuman & Perbandingan dengan CSS Murni](06-rangkuman-dan-perbandingan.md)
