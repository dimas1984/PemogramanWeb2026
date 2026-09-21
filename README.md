# RPS Berbasis OBE/OBA — Desain dan Pemrograman Web (Semester 3, 4 SKS)

**Stack:** HTML/CSS/JS (front-end) + PHP native + PostgreSQL (back-end)
**Output akhir:** Aplikasi web dinamis (CRUD + autentikasi)

---

## 1. Identitas Mata Kuliah

| Item | Keterangan |
|---|---|
| Nama Mata Kuliah | Desain dan Pemrograman Web |
| Kode MK | *(sesuaikan dengan kurikulum prodi)* |
| SKS | 4 SKS (teori terintegrasi praktik) |
| Semester | 3 |
| Prasyarat | Algoritma & Pemrograman Dasar, Struktur Data |
| Rumpun/Prodi | *(isi sesuai prodi)* |
| Dosen Pengampu | *(diisi)* |
| Model Pembelajaran | Project-Based Learning (PjBL) — Outcome Based Education (OBE) |
| Estimasi waktu/minggu | 4 x 50' tatap muka + 4 x 60' terstruktur + 4 x 60' mandiri |

---

## 2. Capaian Pembelajaran Lulusan (CPL) yang Dibebankan pada MK

> Sesuaikan kode CPL berikut dengan dokumen kurikulum prodi Anda — kode di bawah bersifat contoh generik.

| Kode | Deskripsi CPL |
|---|---|
| CPL-S | Menunjukkan sikap bertanggung jawab dan bekerja sama dalam tim untuk menyelesaikan pekerjaan di bidang rekayasa perangkat lunak |
| CPL-P | Menguasai konsep teoretis desain antarmuka, pemrograman web client-side dan server-side, serta basis data relasional |
| CPL-KU | Mampu menerapkan pemikiran logis, kritis, dan sistematis dalam merancang dan mengimplementasikan solusi berbasis web |
| CPL-KK | Mampu merancang, membangun, menguji, dan mendokumentasikan aplikasi web yang fungsional sesuai kebutuhan pengguna |

---

## 3. Capaian Pembelajaran Mata Kuliah (CPMK)

| Kode | CPMK | Ranah |
|---|---|---|
| CPMK-1 | Mahasiswa mampu merancang struktur informasi dan antarmuka (UI/UX) aplikasi web sesuai kebutuhan pengguna | C6, KK (CPL-KK) |
| CPMK-2 | Mahasiswa mampu mengimplementasikan halaman web statis yang responsif menggunakan HTML5 dan CSS3 | C3 (CPL-P) |
| CPMK-3 | Mahasiswa mampu membangun interaktivitas sisi klien menggunakan JavaScript (DOM, event, AJAX/fetch) | C3 (CPL-P) |
| CPMK-4 | Mahasiswa mampu mengembangkan aplikasi web dinamis (CRUD, autentikasi) menggunakan PHP dan PostgreSQL | C4 (CPL-P, CPL-KK) |
| CPMK-5 | Mahasiswa mampu mengintegrasikan front-end dan back-end, menguji, mengamankan, dan mendeploy aplikasi web | C4/C5 (CPL-KU, CPL-KK) |
| CPMK-6 | Mahasiswa menunjukkan tanggung jawab individu/tim dalam menyelesaikan proyek aplikasi web secara mandiri | Afektif (CPL-S) |

**Peta Sub-CPMK (Kemampuan Akhir Tiap Tahapan Belajar):** lihat matriks mingguan di bagian 5 — setiap Sub-CPMK adalah turunan terukur dari CPMK di atas (prinsip *constructive alignment* OBE).

---

## 4. Deskripsi Singkat & Bahan Kajian

**Deskripsi:** Mata kuliah ini membekali mahasiswa untuk merancang dan membangun aplikasi web end-to-end: mulai dari perancangan UI/UX, implementasi front-end (HTML/CSS/JS), hingga back-end dinamis (PHP + PostgreSQL) dengan autentikasi dan keamanan dasar. Pembelajaran berbasis proyek (PjBL) — setiap tahap materi langsung diterapkan pada satu proyek aplikasi web yang dikembangkan secara inkremental sepanjang semester dan didemokan pada UAS.

**Bahan Kajian:** UI/UX & wireframing, HTML5 semantic, CSS3 & responsive design, JavaScript DOM/event/AJAX, PHP dasar-lanjut, PostgreSQL & query, autentikasi/session, keamanan web dasar, testing & deployment.

**Pustaka:**
1. Duckett, J. — *HTML & CSS: Design and Build Websites*
2. Duckett, J. — *JavaScript and JQuery: Interactive Front-End Web Development*
3. Matthew, N. & Stones, R. — *Beginning Databases with PostgreSQL: From Novice to Professional*
4. Nixon, R. — *Learning PHP, MySQL & JavaScript* (gunakan sebagai referensi PHP umum, ganti bagian database dengan dokumentasi PDO_PGSQL)
5. Dokumentasi resmi: MDN Web Docs, PHP.net (PDO_PGSQL), PostgreSQL.org, W3C

---

## 5. Matriks Rencana Pembelajaran Mingguan (16 Minggu)

| Mgg | Sub-CPMK (Kemampuan Akhir) | Indikator | Kriteria & Bentuk Penilaian (OBA) | Metode Pembelajaran | Materi Pembelajaran | Bobot |
|---|---|---|---|---|---|---|
| 1 | Memahami alur mata kuliah, konsep dasar web, dan tools pengembangan | Mampu menjelaskan cara kerja web (client-server) dan instalasi tools | Non-tes: keaktifan diskusi | Ceramah, demo tools (VSCode, Laragon, browser devtools, Git) | Cara kerja web, HTTP, tools & environment setup | 2% |
| 2 | Menyusun struktur halaman web dengan HTML5 semantic | Struktur HTML valid & semantic sesuai kebutuhan konten | Tes praktik: membuat halaman statis | Praktik lab, latihan terbimbing | Tag semantic, form, tabel, aksesibilitas dasar | 3% |
| 3 | Mengimplementasikan styling dasar dengan CSS3 | Halaman terstyle sesuai box model & layout | Tes praktik: styling halaman HTML | Praktik lab | CSS selector, box model, Flexbox, Grid | 3% |
| 4 | Membangun tampilan responsif | Halaman tampil baik di berbagai ukuran layar | Tes praktik: uji responsif multi-device | Praktik lab, studi kasus | Media query, mobile-first, hamburger menu (CSS/checkbox hack) — CSS native, tanpa framework | 4% |
| 5 | Merancang UI/UX aplikasi (proyek) | Wireframe/mockup sesuai kebutuhan pengguna | Non-tes: penilaian rancangan (rubrik) | Studio desain, PjBL kickoff | Prinsip UI/UX, wireframing (ASCII art di Markdown), user flow | 5% |
| 6 | Menerapkan manipulasi DOM & event JavaScript | Interaksi halaman berjalan sesuai skenario | Tes praktik: fitur interaktif sederhana | Praktik lab | DOM, event handling, form validation client-side | 4% |
| 7 | Menerapkan komunikasi asinkron (AJAX/fetch, JSON) | Data dinamis tampil tanpa reload halaman | Tes praktik: konsumsi API/JSON | Praktik lab, studi kasus | Fetch API, JSON, async/await dasar | 4% |
| 8 | **UTS** — Mendemonstrasikan front-end statis-interaktif sesuai rancangan proyek | Front-end proyek berjalan sesuai wireframe | Tes praktik individu (produk + source code) | Ujian praktik | Evaluasi CPMK-1, CPMK-2, CPMK-3 | 15% |
| 9 | Mengimplementasikan dasar PHP & pengolahan form | Script PHP memproses input form dengan benar | Tes praktik: form handling PHP | Praktik lab | Sintaks PHP, variabel, kontrol alur, superglobal ($_GET/$_POST) | 4% |
| 10 | Menghubungkan aplikasi dengan basis data PostgreSQL | Koneksi & query dasar berhasil dieksekusi | Tes praktik: query SELECT/INSERT | Praktik lab | Perancangan tabel (ERD), koneksi PDO_PGSQL, tipe data PostgreSQL (SERIAL, VARCHAR, INTEGER), query dasar | 5% |
| 11 | Membangun fitur CRUD pada proyek | Fitur create-read-update-delete berfungsi penuh | Tes praktik: modul CRUD proyek | Praktik lab, PjBL lanjutan | CRUD dengan PHP & PostgreSQL, prepared statement | 6% |
| 12 | Menerapkan autentikasi & manajemen sesi pengguna | Login/register/logout berfungsi & data sesi aman | Tes praktik: modul login proyek | Praktik lab | Session, cookie, password hashing, tabel users di PostgreSQL | 6% |
| 13 | Menerapkan prinsip keamanan web dasar | Aplikasi tervalidasi bebas kerentanan dasar | Tes praktik: uji keamanan checklist | Praktik lab, studi kasus | SQL Injection (prepared statement PDO_PGSQL), XSS, CSRF (token per-session), Session Fixation (`session_regenerate_id`), validasi & sanitasi input | 5% |
| 14 | Mengintegrasikan front-end & back-end proyek secara utuh | Seluruh modul proyek terintegrasi & teruji | Non-tes: review progress (rubrik) | Praktik lab, peer review | Integrasi sistem, functional testing | 6% |
| 15 | Mendeploy dan mendokumentasikan aplikasi | Aplikasi ter-deploy & terdokumentasi lengkap | Non-tes: dokumen teknis + demo progress | Praktik lab, presentasi progress | Deployment (local/hosting), dokumentasi teknis | 4% |
| 16 | **UAS** — Mendemonstrasikan aplikasi web akhir secara utuh | Aplikasi web berfungsi sesuai CPMK-1 s.d. CPMK-5 | Tes praktik + presentasi (produk, source code, laporan) | Ujian praktik & presentasi | Evaluasi capaian akhir proyek | 20% |
| — | Sikap & kolaborasi selama proyek (CPMK-6) | Konsistensi kontribusi individu dalam tim/mandiri | Non-tes: observasi & logbook mingguan | Sepanjang semester | — | 4% |

*(Total bobot = 100%)*

---

## 6. Komponen dan Bobot Penilaian Akhir (Ringkasan OBA)

| Komponen | Bobot | Memetakan CPMK |
|---|---|---|
| Tugas/Praktik Mingguan (formatif) | 30% | CPMK-1, 2, 3, 4 |
| Sikap & Kolaborasi (logbook) | 4% | CPMK-6 |
| UTS (praktik front-end individu) | 15% | CPMK-1, 2, 3 |
| Progress & Dokumentasi Proyek | 11% | CPMK-4, 5 |
| UAS (demo + presentasi aplikasi akhir) | 20% | CPMK-4, 5, 6 |
| Produk Akhir Aplikasi Web (source code + fungsionalitas) | 20% | CPMK-4, 5 |

**Nilai Akhir Mata Kuliah (NAM)** dihitung dari kontribusi tiap komponen terhadap ketercapaian CPMK, bukan sekadar rata-rata skor — sesuai prinsip OBA bahwa penilaian harus membuktikan *outcome* tercapai, bukan hanya aktivitas selesai.

---

## 7. Rubrik Penilaian Proyek Akhir (Aplikasi Web) — Contoh OBA

| Kriteria | Sangat Baik (86-100) | Baik (71-85) | Cukup (56-70) | Kurang (<56) |
|---|---|---|---|---|
| Fungsionalitas (CRUD, autentikasi) | Semua fitur berjalan sempurna tanpa bug | Fitur utama berjalan, bug minor | Sebagian fitur berjalan | Fitur utama tidak berfungsi |
| Desain UI/UX | Konsisten, responsif, mudah digunakan | Cukup konsisten & responsif | Responsif sebagian | Tidak responsif/tidak konsisten |
| Kualitas Kode & Keamanan | Terstruktur, tervalidasi, bebas kerentanan dasar | Terstruktur, validasi sebagian | Kurang terstruktur, validasi minim | Tidak terstruktur, rentan |
| Basis Data (PostgreSQL) | Skema normal (ERD jelas), tipe data tepat, query efisien & aman (prepared statement) | Skema cukup baik, sebagian prepared statement | Skema kurang optimal, query rawan injeksi | Skema tidak sesuai kebutuhan |
| Dokumentasi & Presentasi | Lengkap, jelas, mampu menjawab pertanyaan | Cukup lengkap | Dokumentasi minim | Tidak ada dokumentasi |

---

## Lampiran: Opsi Pemadatan 8 Pertemuan (Kelas Intensif)

Alternatif struktur di luar matriks 16 minggu pada bagian 5 — untuk format kelas yang lebih singkat (mis. kelas intensif/bootcamp), bukan pengganti RPS 16 minggu reguler. Menggabungkan pasangan topik yang berdekatan secara konsep supaya proyek SIMPUS-Mini tetap utuh dari awal sampai akhir.

| Pert. | Sub-CPMK Gabungan | Jobsheet yang Dipakai | Materi Inti | Materi Yang Dipadatkan |
|---|---|---|---|---|
| 1 | Struktur HTML5 semantic + Styling dasar CSS3 | jobsheet-01 + 02 | Tag semantic, form, tabel + CSS selector, box model, Flexbox, Grid | Intro tools (minggu 1 lama) jadi 15 menit pembuka, bukan pertemuan sendiri |
| 2 | Tampilan responsif + Rancangan UI/UX | jobsheet-03 + 04 | Media query, mobile-first, hamburger menu + wireframe & user flow | Wireframe dibuat lebih ringkas (mahasiswa pakai template, bukan dari nol) |
| 3 | DOM/event JS + AJAX/fetch & JSON | jobsheet-05 + 06 | Event handling, validasi client-side + fetch API, JSON, async/await | Jadi evaluasi front-end |
| 4 | PHP dasar & form handling + Koneksi PostgreSQL | jobsheet-07 + 08 | Sintaks PHP, superglobal + PDO, ERD, query dasar | Sesi instalasi Laragon/PostgreSQL disarankan jadi tugas mandiri sebelum pertemuan (flipped), bukan makan waktu tatap muka |
| 5 | CRUD pada proyek | jobsheet-09 | Edit/hapus, prepared statement, pagination & pencarian server-side | Berdiri sendiri — CRUD butuh waktu penuh, tidak digabung |
| 6 | Autentikasi & sesi + Keamanan web dasar | jobsheet-10 + 11 | Login/register/logout, password hashing + SQLi, XSS, CSRF, session fixation | Padat — keamanan dibahas sambil menempel langsung ke kode auth yang baru dibuat, bukan sesi audit terpisah |
| 7 | Integrasi front-end/back-end penuh | jobsheet-12 | Modul peminjaman/pengembalian, transaction (`BEGIN`/`COMMIT`/`ROLLBACK`) | Peer review progress (minggu 14 lama) dipangkas jadi umpan balik singkat di akhir sesi, bukan sesi khusus |
| 8 | Deploy, dokumentasi + **Demo Akhir (UTS)** | jobsheet-13 | Pemisahan config/kredensial, dokumentasi teknis + presentasi produk akhir | Deploy & UTS digabung jadi satu pertemuan penutup Materi |
| 9-15 | PBL |PBL| PBL |PBL
| 16 | **Demo PBL** | **Demo PBL**| **Demo PBL** | **Demo PBL** |

> **Materi pengayaan (di luar RPS):** [`kode-praktikum/jobsheet-14`](kode-praktikum/jobsheet-14/README.md) — mendeploy aplikasi ke internet secara nyata memakai Render (container Docker) + Supabase (PostgreSQL terkelola), keduanya paket gratis. Bersifat opsional bagi mahasiswa yang ingin aplikasinya bisa dibuka lewat tautan publik, bukan hanya `localhost`.


---

## Catatan Implementasi OBE/OBA

- **OBE**: setiap Sub-CPMK mingguan diturunkan langsung dari CPMK dan CPL agar keterkaitan *learning outcome* → aktivitas → penilaian terjaga (*constructive alignment*).
- **OBA**: bentuk penilaian didominasi tes/non-tes **praktik berbasis produk** (bukan hafalan teori), karena luaran MK adalah aplikasi web fungsional.
- Kode CPL, nama MK, dan bobot SKS teori/praktik sebaiknya disesuaikan dengan **dokumen kurikulum resmi prodi** sebelum diajukan ke penjaminan mutu.

## Catatan Teknis Lab (PostgreSQL)

- Instalasi lab: gunakan **Laragon Full** (bukan XAMPP) — sudah membundel Apache, PHP, dan PostgreSQL (ditambahkan lewat fitur **Quick Add** kalau belum otomatis terpasang), plus terminal bawaan yang sudah mengenali `psql`/`php` tanpa perlu mengatur PATH manual. Ekstensi `pdo_pgsql` dan `pgsql` perlu diaktifkan manual di `php.ini` (lewat menu PHP → Extensions di Laragon). Panduan instalasi step-by-step lengkap: `kode-praktikum/jobsheet-08/Dokumentasi/08-instalasi-postgresql-laragon.md`, dan panduan setup database proyek: `Setup-Database-PostgreSQL-Laragon.md` (root repo).
- Koneksi PHP disarankan pakai **PDO** (`new PDO("pgsql:host=...;dbname=...", $user, $pass)`) agar sintaks query mirip dan gampang di-switch, alih-alih fungsi `mysqli_*`.
- Perbedaan sintaks yang perlu ditekankan ke mahasiswa: `SERIAL`/`BIGSERIAL` (bukan `AUTO_INCREMENT`), `RETURNING` clause untuk mengambil ID setelah `INSERT`, penggunaan tanda kutip ganda untuk identifier case-sensitive.
- Kalau di komputer lab/mahasiswa sudah ada instalasi PostgreSQL lain (mis. dari installer resmi postgresql.org) yang berjalan sebagai Windows Service otomatis, ia bisa bentrok rebutan port `5432` dengan PostgreSQL bawaan Laragon (Laragon gagal start, muncul dialog *"waiting for server to start...."*) — cara mendeteksi & mengatasinya ada di dokumen instalasi yang dirujuk di atas.
