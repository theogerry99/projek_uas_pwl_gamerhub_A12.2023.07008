<?php
// Memanggil header.php yang sudah berisi session_start()
require_once 'includes/header.php';
require_once 'config/database.php';

// --- 1. OTENTIKASI ---
// Cek apakah pengguna sudah login. Jika belum, tendang kembali ke halaman utama.
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?error=loginrequired'); // Menggunakan notifikasi terpusat
    exit();
}

$id_user = $_SESSION['user_id'];
$cart_items = [];
$total_harga = 0;

try {
    // --- 2. LOGIKA READ (Mengambil Data Keranjang) ---
    // Query ini menggabungkan 3 tabel untuk mendapatkan semua informasi yang kita butuhkan.
    $stmt = $pdo->prepare("
        SELECT 
            p.id_produk,
            p.nama_produk,
            p.gambar,
            ds.jumlah,
            ds.harga_saat_sewa,
            ds.id_detail_sewa
        FROM sewa s
        JOIN detail_sewa ds ON s.id_sewa = ds.id_sewa
        JOIN produk p ON ds.id_produk = p.id_produk
        WHERE s.id_user = ? AND s.status = 'keranjang'
    ");
    $stmt->execute([$id_user]);
    $cart_items = $stmt->fetchAll();

} catch (PDOException $e) {
    // Menangani jika ada error saat mengambil data
    die("Error: Tidak dapat mengambil data keranjang. " . $e->getMessage());
}
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4">Keranjang Belanja Anda</h2>

    <?php
    // --- HAPUS BLOK NOTIFIKASI INI ---
    /*
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'deleted') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Item berhasil dihapus dari keranjang.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        } elseif ($_GET['status'] == 'updated') {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
                    Jumlah item berhasil diperbarui.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
        }
    }
    */
    ?>

    <?php if (empty($cart_items)): ?>
        <div class="alert alert-info text-center">
            <p class="lead mb-0">Keranjang Anda masih kosong.</p>
            <a href="index.php#koleksi-game" class="btn btn-primary mt-3">Mulai Belanja</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" colspan="2">Produk</th>
                        <th scope="col" class="text-center">Jumlah</th>
                        <th scope="col" class="text-end">Harga Satuan</th>
                        <th scope="col" class="text-end">Subtotal</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <?php
                            $subtotal = $item['jumlah'] * $item['harga_saat_sewa'];
                            $total_harga += $subtotal;
                        ?>
                        <tr>
                            <form action="process/cart_process.php" method="POST">
                                <td style="width: 100px;">
                                    <img src="assets/images/<?php echo htmlspecialchars($item['gambar']); ?>" alt="<?php echo htmlspecialchars($item['nama_produk']); ?>" class="img-fluid rounded">
                                </td>
                                <td>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($item['nama_produk']); ?></h6>
                                </td>
                                <td class="text-center" style="width: 150px;">
                                    <input type="number" name="quantity" class="form-control text-center" value="<?php echo htmlspecialchars($item['jumlah']); ?>" min="1">
                                </td>
                                <td class="text-end">
                                    <?php echo 'Rp ' . number_format($item['harga_saat_sewa'], 0, ',', '.'); ?>
                                </td>
                                <td class="text-end fw-bold">
                                    <?php echo 'Rp ' . number_format($subtotal, 0, ',', '.'); ?>
                                </td>
                                <td class="text-center">
                                    <input type="hidden" name="action" value="update">
                                    <input type="hidden" name="id_detail_sewa" value="<?php echo $item['id_detail_sewa']; ?>">

                                    <button type="submit" class="btn btn-primary btn-sm" title="Update jumlah">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                    <a href="process/cart_process.php?action=remove&item_id=<?php echo $item['id_detail_sewa']; ?>" class="btn btn-danger btn-sm" title="Hapus item" onclick="return confirm('Anda yakin ingin menghapus item ini?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-4">
            <div class="col-md-6 offset-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="fw-bold">Total</h5>
                            <h5 class="fw-bold text-primary"><?php echo 'Rp ' . number_format($total_harga, 0, ',', '.'); ?></h5>
                        </div>
                        <p class="text-muted">Biaya sewa per hari. Pajak dan biaya lainnya akan dihitung saat checkout.</p>
                        <div class="d-grid">
                            <a href="checkout.php" class="btn btn-primary btn-lg mt-3">Lanjutkan ke Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<?php
// Memanggil footer
require_once 'includes/footer.php';
?>
