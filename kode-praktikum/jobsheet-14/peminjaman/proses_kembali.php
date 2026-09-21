<?php
require __DIR__ . '/../includes/auth.php';
require __DIR__ . '/../includes/csrf.php';
require __DIR__ . '/../includes/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: kembali.php');
    exit;
}

csrf_verify();

$id = $_POST['id'] ?? null;
if (!$id) {
    header('Location: kembali.php');
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("SELECT buku_id, status FROM peminjaman WHERE id = :id FOR UPDATE");
    $stmt->execute(['id' => $id]);
    $trx = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$trx || $trx['status'] !== 'dipinjam') {
        throw new Exception('Transaksi tidak ditemukan atau sudah dikembalikan.');
    }

    $updatePeminjaman = $pdo->prepare(
        "UPDATE peminjaman SET status = 'dikembalikan', tanggal_kembali = CURRENT_DATE WHERE id = :id"
    );
    $updatePeminjaman->execute(['id' => $id]);

    $updateBuku = $pdo->prepare("UPDATE buku SET stok = stok + 1 WHERE id = :buku_id");
    $updateBuku->execute(['buku_id' => $trx['buku_id']]);

    $pdo->commit();
    $_SESSION['flash'] = ['type' => 'success', 'pesan' => 'Buku berhasil dikembalikan.'];
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['flash'] = ['type' => 'error', 'pesan' => 'Gagal memproses pengembalian: ' . $e->getMessage()];
}

header('Location: kembali.php');
exit;
