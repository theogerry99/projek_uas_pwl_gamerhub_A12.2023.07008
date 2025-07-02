<?php
require_once 'includes/header.php';
require_once 'config/database.php';

// Ambil semua data PlayStation
$stmt_ps = $pdo->prepare("SELECT * FROM produk WHERE tipe = 'ps' ORDER BY id_produk DESC");
$stmt_ps->execute();
$playstations = $stmt_ps->fetchAll();
?>

<div class="container my-5">
    <h2 class="fw-bold mb-4 text-center">Koleksi PlayStation Kami</h2>
    <p class="lead text-center mb-5">Temukan konsol PlayStation impianmu dan mulai petualangan gaming!</p>

    <div class="row" id="psCollection">
        <?php if (empty($playstations)): ?>
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    Belum ada PlayStation yang tersedia saat ini.
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($playstations as $ps): ?>
            <div class="col-md-4 mb-4 ps-item">
                <div class="card ps-card h-100">
                    <img src="assets/images/<?php echo htmlspecialchars($ps['gambar'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($ps['nama_produk']); ?>" class="card-img-top ps-img">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?php echo htmlspecialchars($ps['nama_produk']); ?></h5>
                        <p class="card-text flex-grow-1"><?php echo htmlspecialchars($ps['deskripsi'] ?? 'Deskripsi tidak tersedia.'); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <span class="fw-bold">Rp <?php echo number_format($ps['harga_sewa_harian'], 0, ',', '.'); ?>/hari</span>
                            <button class="btn btn-sm btn-outline-primary add-to-cart" data-id="<?php echo $ps['id_produk']; ?>">
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
