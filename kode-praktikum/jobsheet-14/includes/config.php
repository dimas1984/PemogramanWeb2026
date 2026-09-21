<?php
// Pemisahan kredensial dari kode sumber (includes/koneksi.php).
// Di lingkungan produksi/hosting, isi nilai-nilai ini lewat environment
// variable (getenv) yang diatur di server, JANGAN di-commit ke repository
// bila nilai default di bawah sudah diganti dengan kredensial asli.
//
// Jobsheet 14: ditambah dua kunci baru untuk kebutuhan deployment.
//   - db_sslmode : dipakai Supabase (isi 'require' di Render).
//                  Default 'prefer' supaya tetap jalan di PostgreSQL lokal
//                  (Laragon) yang biasanya tidak mengaktifkan SSL.
//   - app_debug  : 'true' hanya saat mengembangkan di komputer sendiri.
//                  Di produksi biarkan kosong agar pesan error teknis
//                  tidak ditampilkan ke pengunjung.
return [
    'db_host'    => getenv('DB_HOST') ?: 'localhost',
    'db_port'    => getenv('DB_PORT') ?: '5432',
    'db_name'    => getenv('DB_NAME') ?: 'simpus_mini',
    'db_user'    => getenv('DB_USER') ?: 'postgres',
    'db_pass'    => getenv('DB_PASS') ?: 'postgres',
    'db_sslmode' => getenv('DB_SSLMODE') ?: 'prefer',
    'app_debug'  => (getenv('APP_DEBUG') ?: '') === 'true',
];
