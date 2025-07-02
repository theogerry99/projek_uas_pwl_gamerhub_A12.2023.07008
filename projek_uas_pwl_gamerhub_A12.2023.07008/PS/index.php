<?php
// Memanggil header.php (yang sudah berisi session_start() dan semua tag <head>)
require_once 'includes/header.php';
require_once 'config/database.php';

// Logika untuk mengambil data produk dari database (ini sudah benar dari kode Anda)
// Ambil data game
$stmt_games = $pdo->prepare("SELECT * FROM produk WHERE tipe = 'game' ORDER BY id_produk DESC LIMIT 4");
$stmt_games->execute();
$games = $stmt_games->fetchAll();

// Ambil data PS
$stmt_ps = $pdo->prepare("SELECT * FROM produk WHERE tipe = 'ps' ORDER BY id_produk DESC LIMIT 3");
$stmt_ps->execute();
$playstations = $stmt_ps->fetchAll();
?>

<section id="beranda" class="hero-section mb-5">
    <div class="container py-5">
        <h1 class="display-4 fw-bold mb-3">GamerHub</h1>
        <p class="lead mb-4">Tempat terbaik untuk menyewa PlayStation dan game favoritmu</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="#koleksi-game" class="btn btn-primary btn-lg">
                <i class="fas fa-gamepad me-2"></i>Sewa Game
            </a>
            <a href="#koleksi-ps" class="btn btn-success btn-lg">
                <i class="fas fa-tv me-2"></i>Rental PS
            </a>
        </div>
    </div>
</section>

<div class="container">
    <section class="mb-5">
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-bolt text-warning mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Cepat & Mudah</h5>
                        <p class="card-text">Proses sewa yang cepat dalam beberapa klik saja.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-shield-alt text-success mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Garansi Aman</h5>
                        <p class="card-text">Garansi 100% uang kembali jika ada masalah.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-headset text-primary mb-3" style="font-size: 2rem;"></i>
                        <h5 class="card-title">Support 24/7</h5>
                        <p class="card-text">Tim kami siap membantu kapan saja.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="koleksi-game" class="mb-5 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Koleksi Game Terbaru</h2>
            <a href="#" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row" id="gameCollection">
            <?php if (empty($games)): ?>
                <p class="text-center">Belum ada game yang tersedia.</p>
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
    </section>

    <section id="koleksi-ps" class="mb-5 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Koleksi PlayStation</h2>
            <a href="#" class="btn btn-outline-primary">Lihat Semua</a>
        </div>
        <div class="row" id="psCollection">
            <?php if (empty($playstations)): ?>
                <p class="text-center">Belum ada PlayStation yang tersedia.</p>
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
    </section>

</div> <?php
// Memanggil footer yang berisi modal dan script
require_once 'includes/footer.php';
?>