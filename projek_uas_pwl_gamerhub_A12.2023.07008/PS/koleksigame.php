<?php
require_once 'includes/header.php';
require_once 'config/database.php';

// Ambil semua data game
$stmt_games = $pdo->prepare("SELECT * FROM produk WHERE tipe = 'game' ORDER BY id_produk DESC");
$stmt_games->execute();
$games = $stmt_games->fetchAll();
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center">Koleksi Game Kami</h2>
    <p class="lead text-center mb-5">Temukan game favoritmu dan mulai petualangan baru!</p>

    <div class="row" id="gameCollection">
        <?php if (empty($games)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    Belum ada game yang tersedia saat ini.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($games as $game): ?>
            <div class="col-md-3 mb-4 game-item">
                <div class="card game-card h-100">
                    <img src="assets/images/<?php echo htmlspecialchars($game['gambar'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($game['nama_produk']); ?>" class="card-img-top">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($game['nama_produk']); ?></h5>
                        <p class="text-muted flex-grow-1"><?php echo htmlspecialchars($game['deskripsi'] ?? 'Deskripsi tidak tersedia.'); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="fw-bold">Rp <?php echo number_format($game['harga_sewa_harian'], 0, ',', '.'); ?>/hari</span>
                            <button class="btn btn-sm btn-outline-primary add-to-cart" data-id="<?php echo $game['id_produk']; ?>">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
