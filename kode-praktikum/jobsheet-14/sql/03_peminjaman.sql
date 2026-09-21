-- Jobsheet 12: tabel peminjaman, menghubungkan buku, anggota, dan users
-- Jalankan: psql -d simpus_mini -f sql/03_peminjaman.sql

CREATE TABLE IF NOT EXISTS peminjaman (
    id SERIAL PRIMARY KEY,
    buku_id INTEGER NOT NULL REFERENCES buku(id),
    anggota_id INTEGER NOT NULL REFERENCES anggota(id),
    tanggal_pinjam DATE NOT NULL DEFAULT CURRENT_DATE,
    tanggal_kembali DATE,
    status VARCHAR(20) NOT NULL DEFAULT 'dipinjam'
);
