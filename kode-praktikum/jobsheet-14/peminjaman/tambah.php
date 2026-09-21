<?php
require __DIR__ . '/../includes/auth.php';
$page_title = "Peminjaman Baru";
include __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/koneksi.php';

$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);

$daftarAnggota = $pdo->query("SELECT * FROM anggota ORDER BY nama")->fetchAll(PDO::FETCH_ASSOC);
$daftarBukuTersedia = $pdo->query("SELECT * FROM buku WHERE stok > 0 ORDER BY judul")->fetchAll(PDO::FETCH_ASSOC);
?>
        <section>
            <h2>Peminjaman Buku Baru</h2>

            <?php if ($flash): ?>
                <p class="flash flash-<?php echo $flash['type']; ?>"><?php echo e($flash['pesan']); ?></p>
            <?php endif; ?>

            <?php if (empty($daftarAnggota)): ?>
                <p class="flash flash-error">Belum ada data anggota. Tambahkan anggota terlebih dahulu.</p>
            <?php elseif (empty($daftarBukuTersedia)): ?>
                <p class="flash flash-error">Tidak ada buku dengan stok tersedia saat ini.</p>
            <?php else: ?>
            <form method="post" action="proses_tambah.php">
                <?php echo csrf_field(); ?>
                <p>
                    <label for="anggota_id">Anggota</label><br>
                    <select id="anggota_id" name="anggota_id" required>
                        <option value="">-- Pilih Anggota --</option>
                        <?php foreach ($daftarAnggota as $anggota): ?>
                        <option value="<?php echo $anggota['id']; ?>">
                            <?php echo e($anggota['nama']); ?> (<?php echo e($anggota['no_anggota']); ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <label for="buku_id">Buku (hanya yang stoknya tersedia)</label><br>
                    <select id="buku_id" name="buku_id" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php foreach ($daftarBukuTersedia as $buku): ?>
                        <option value="<?php echo $buku['id']; ?>">
                            <?php echo e($buku['judul']); ?> (stok: <?php echo $buku['stok']; ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p>
                    <button type="submit">Simpan Peminjaman</button>
                </p>
            </form>
            <?php endif; ?>
        </section>
<?php include __DIR__ . '/../includes/footer.php'; ?>
