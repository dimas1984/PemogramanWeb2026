<?php
$config = require __DIR__ . '/config.php';

try {
    $dsn = "pgsql:host={$config['db_host']};port={$config['db_port']};"
         . "dbname={$config['db_name']};sslmode={$config['db_sslmode']}";

    $pdo = new PDO(
        $dsn,
        $config['db_user'],
        $config['db_pass'],
        [
            // Supabase mengarahkan koneksi lewat connection pooler. Pada mode
            // transaksi, prepared statement bernama milik PostgreSQL bisa
            // bentrok antar-koneksi. Emulasi prepared statement di sisi PHP
            // menghindari masalah itu -- keamanannya tetap sama karena nilai
            // tetap di-escape oleh driver, bukan disambung sebagai string.
            PDO::ATTR_EMULATE_PREPARES => true,
        ]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Pesan asli PDOException memuat host, nama database, dan username.
    // Di produksi pesan itu tidak boleh sampai ke layar pengunjung:
    // catat ke log server, tampilkan pesan umum saja.
    error_log('Koneksi database gagal: ' . $e->getMessage());
    http_response_code(500);

    if ($config['app_debug']) {
        die('Koneksi database gagal: ' . $e->getMessage());
    }

    die('Koneksi database gagal. Silakan hubungi administrator.');
}
