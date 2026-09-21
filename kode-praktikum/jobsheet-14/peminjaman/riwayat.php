<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Riwayat Peminjaman";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$anggotaId = $_GET['anggota_id'] ?? '';
$daftarAnggota = $pdo->query("SELECT * FROM anggota ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);

$riwayat = [];
$anggotaTerpilih = null;

if ($anggotaId !== '') {
    $stmtA = $pdo->prepare("SELECT * FROM anggota WHERE id = :id");
    $stmtA->execute(['id' => $anggotaId]);
    $anggotaTerpilih = $stmtA->fetch(PDO::FETCH_ASSOC);

    if ($anggotaTerpilih) {
        $stmt = $pdo->prepare(
            "SELECT b.judul, p.tanggal_pinjam, p.tanggal_kembali, p.status
             FROM peminjaman p
             JOIN buku b ON b.id = p.buku_id
             WHERE p.anggota_id = :id
             ORDER BY p.tanggal_pinjam DESC"
        );
        $stmt->execute(['id' => $anggotaId]);
        $riwayat = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
        <section>
            <h2>Riwayat Peminjaman</h2>

            <form method="get" action="riwayat.php">
                <p>
                    <label for="anggota_id">Pilih Anggota</label><br>
                    <select id="anggota_id" name="anggota_id">
                        <option value="">-- Pilih Anggota --</option>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <option value="<?php echo $anggota['id']; ?>" <?php echo (string) $anggotaId === (string) $anggota['id'] ? 'selected' : ''; ?>>
                            <?php echo e($anggota['nama']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <button type="submit">Tampilkan</button>
                </p>
            </form>

            <?php if ($anggotaTerpilih): ?>
            <h3>Riwayat &mdash; <?php echo e($anggotaTerpilih['nama']); ?></h3>
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Buku</th>
                        <th>Pinjam</th>
                        <th>Kembali</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($riwayat)): ?>
                    <tr>
                        <td colspan="4">Belum ada riwayat peminjaman.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($riwayat as $r): ?>
                        <tr>
                            <td><?php echo e($r['judul']); ?></td>
                            <td><?php echo $r['tanggal_pinjam']; ?></td>
                            <td><?php echo $r['tanggal_kembali'] ?? '-'; ?></td>
                            <td><?php echo $r['status'] === 'dipinjam' ? 'Dipinjam' : 'Selesai'; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            </div>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
