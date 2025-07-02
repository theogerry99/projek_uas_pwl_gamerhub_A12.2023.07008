<?php
require_once 'includes/header.php';
require_once 'config/database.php';

// Otentikasi: Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?error=loginrequired'); // Menggunakan notifikasi terpusat
    exit();
}

$id_user = $_SESSION['user_id'];
$cart_items = [];
$total_harga = 0;

// Ambil item dari keranjang (logika mirip seperti di keranjang.php)
try {
    $stmt = $pdo->prepare("
        SELECT p.nama_produk, ds.jumlah, ds.harga_saat_sewa
        FROM sewa s
        JOIN detail_sewa ds ON s.id_sewa = ds.id_sewa
        JOIN produk p ON ds.id_produk = p.id_produk
        WHERE s.id_user = ? AND s.status = 'keranjang'
    ");
    $stmt->execute([$id_user]);
    $cart_items = $stmt->fetchAll();

    // Jika keranjang kosong, tidak ada yang bisa di-checkout, kembalikan ke keranjang
    if (empty($cart_items)) {
        header('Location: keranjang.php?error=Keranjang Anda kosong.'); // Notifikasi keranjang kosong
        exit();
    }

} catch (PDOException $e) {
    die("Error: Tidak dapat mengambil data. " . $e->getMessage());
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <h2 class="fw-bold mb-4">Konfirmasi Penyewaan</h2>
            <div class="card">
                <div class="card-header">
                    Ringkasan Pesanan
                </div>
                <div class="card-body">
                    <form method="post" action="checkout.php">
                    <ul class="list-group list-group-flush">
                        <?php foreach ($cart_items as $item): ?>
                            <?php
                                $subtotal = $item['jumlah'] * $item['harga_saat_sewa'];
                                $total_harga += $subtotal;
                            ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="my-0"><?php echo htmlspecialchars($item['nama_produk']); ?></h6>
                                    <small class="text-muted">Durasi: <select name="durasi[<?php echo $item['id_produk']; ?>]" style="width: 80px;">
                                        <option value="1">1 hari</option>
                                        <option value="3">3 hari</option>
                                        <option value="7">7 hari</option>
                                        <option value="14">14 hari</option>
                                        <option value="30">30 hari</option>
                                        </select>
                                    </small>
                                </div>
                                <span class="text-muted"><?php echo 'Rp ' . number_format($subtotal, 0, ',', '.'); ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <span class="fw-bold">Total Biaya Sewa (per hari)</span>
                            <strong class="text-primary"><?php echo 'Rp ' . number_format($total_harga, 0, ',', '.'); ?></strong>
                        </li>
                    </ul>
                    <button type="submit" name="update_durasi" class="btn btn-sm btn-primary mt-3">Update</button>
                    </form>
                </div>
                <div class="card-footer text-center">
                    <p class="text-muted small">Dengan menekan tombol di bawah, Anda setuju dengan Syarat & Ketentuan kami. Anda akan dihubungi oleh tim kami untuk proses selanjutnya.</p>
                    <form action="process/checkout_process.php" method="POST">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-check-circle me-2"></i>Konfirmasi & Sewa Sekarang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="text-center mt-3">
                <a href="keranjang.php">&larr; Kembali ke Keranjang</a>
            </div>
        </div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
