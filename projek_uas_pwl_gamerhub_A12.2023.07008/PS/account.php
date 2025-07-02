<?php
require_once 'includes/header.php';
require_once 'config/database.php';

// Otentikasi: Wajib login untuk akses halaman ini
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?error=loginrequired'); // Menggunakan notifikasi terpusat
    exit();
}

// Ambil data pengguna yang sedang login untuk ditampilkan di form
$id_user = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
$stmt->execute([$id_user]);
$user = $stmt->fetch();
// Query untuk mengambil semua item dari pinjaman yang statusnya 'disewa'
$stmt_pinjaman = $pdo->prepare("
    SELECT 
        s.id_sewa, 
        s.tanggal_sewa, 
        s.tanggal_kembali_rencana, 
        p.nama_produk, 
        p.gambar, 
        ds.jumlah
    FROM sewa s
    JOIN detail_sewa ds ON s.id_sewa = ds.id_sewa
    JOIN produk p ON ds.id_produk = p.id_produk
    WHERE s.id_user = ? AND s.status = 'disewa'
    ORDER BY s.tanggal_sewa DESC
");
$stmt_pinjaman->execute([$id_user]);
$pinjaman_items = $stmt_pinjaman->fetchAll();

// Kelompokkan item berdasarkan id_sewa agar mudah ditampilkan
$pinjaman_aktif = [];
foreach ($pinjaman_items as $item) {
    $id_sewa = $item['id_sewa'];
    // Jika ID sewa ini belum ada di array, buat entri baru
    if (!isset($pinjaman_aktif[$id_sewa])) {
        $pinjaman_aktif[$id_sewa] = [
            'tanggal_sewa' => $item['tanggal_sewa'],
            'tanggal_kembali_rencana' => $item['tanggal_kembali_rencana'],
            'items' => [] // Buat array untuk menampung item-itemnya
        ];
    }
    // Tambahkan item saat ini ke dalam grup sewa yang sesuai
    $pinjaman_aktif[$id_sewa]['items'][] = $item;
}
// Query untuk mengambil semua item dari transaksi yang statusnya 'selesai'
$stmt_riwayat = $pdo->prepare("
    SELECT 
        s.id_sewa, 
        s.tanggal_sewa, 
        s.tanggal_kembali_rencana,
        p.nama_produk, 
        p.gambar, 
        ds.jumlah
    FROM sewa s
    JOIN detail_sewa ds ON s.id_sewa = ds.id_sewa
    JOIN produk p ON ds.id_produk = p.id_produk
    WHERE s.id_user = ? AND s.status = 'selesai'
    ORDER BY s.tanggal_sewa DESC
");
$stmt_riwayat->execute([$id_user]);
$riwayat_items = $stmt_riwayat->fetchAll();

// Kelompokkan item riwayat berdasarkan id_sewa
$riwayat_transaksi = [];
foreach ($riwayat_items as $item) {
    $id_sewa = $item['id_sewa'];
    if (!isset($riwayat_transaksi[$id_sewa])) {
        $riwayat_transaksi[$id_sewa] = [
            'tanggal_sewa' => $item['tanggal_sewa'],
            'tanggal_kembali_rencana' => $item['tanggal_kembali_rencana'],
            'items' => []
        ];
    }
    $riwayat_transaksi[$id_sewa]['items'][] = $item;
}


?>



<div class="container my-5">
    <h2 class="fw-bold mb-4">Dasbor Akun Saya</h2>

    <?php
    // --- HAPUS BLOK NOTIFIKASI INI ---
    /*
    if (isset($_GET['status'])) {
        if ($_GET['status'] == 'checkout_success') {
            echo '<div class="alert alert-success">Checkout berhasil! Pesanan Anda sedang diproses.</div>';
        } elseif ($_GET['status'] == 'profile_updated') {
            echo '<div class="alert alert-success">Profil Anda berhasil diperbarui.</div>';
        }
    }
    */
    ?>

    <ul class="nav nav-tabs" id="accountTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profil</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="pinjaman-tab" data-bs-toggle="tab" data-bs-target="#pinjaman" type="button" role="tab">Pinjaman Aktif</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab">Riwayat</button>
        </li>
    </ul>

    <div class="tab-content card" id="accountTabContent">
        <div class="tab-pane fade show active p-4" id="profile" role="tabpanel">
            <h4 class="card-title mb-4">Ubah Informasi Profil</h4>
            <form action="process/account_process.php" method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?php echo htmlspecialchars($user['nama_lengkap']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                        <div class="form-text">Email tidak dapat diubah.</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nomor HP</label>
                    <input type="tel" name="no_hp" class="form-control" value="<?php echo htmlspecialchars($user['no_hp']); ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Alamat</label>
                    <textarea name="alamat" class="form-control" rows="3"><?php echo htmlspecialchars($user['alamat']); ?></textarea>
                </div>
                <hr>
                <p class="text-muted">Kosongkan jika tidak ingin mengubah password.</p>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_password" class="form-control">
                    </div>
                </div>
                <button type="submit" name="update_profile" class="btn btn-primary">Simpan Perubahan</button>
            </form>
        </div>

        <div class="tab-pane fade p-4" id="pinjaman" role="tabpanel">
            <h4 class="card-title mb-4">Pinjaman Aktif</h4>

            <?php if (empty($pinjaman_aktif)): ?>
                <div class="alert alert-info">Tidak ada pinjaman aktif saat ini.</div>
            <?php else: ?>
                <?php foreach ($pinjaman_aktif as $id_sewa => $sewa): ?>
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between">
                            <span>
                                <strong>ID Sewa:</strong> #<?php echo $id_sewa; ?>
                            </span>
                            <span>
                                <strong>Tanggal Sewa:</strong> <?php echo date('d M Y', strtotime($sewa['tanggal_sewa'])); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($sewa['items'] as $item): ?>
                                    <li class="list-group-item d-flex align-items-center">
                                        <img src="assets/images/<?php echo htmlspecialchars($item['gambar']); ?>" alt="" class="img-fluid rounded me-3" style="width: 80px;">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($item['nama_produk']); ?></h6>
                                            <small class="text-muted">Durasi: <?php echo htmlspecialchars($item['jumlah']); ?> hari</small>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="card-footer bg-light text-end">
                            <strong>Rencana Kembali:</strong> <span class="text-danger"><?php echo date('d M Y', strtotime($sewa['tanggal_kembali_rencana'])); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="tab-pane fade p-4" id="riwayat" role="tabpanel">
        <h4 class="card-title mb-4">Riwayat Transaksi</h4>

        <?php if (empty($riwayat_transaksi)): ?>
            <div class="alert alert-info">Belum ada riwayat transaksi yang selesai.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID Sewa</th>
                            <th>Tanggal Sewa</th>
                            <th>Item yang Disewa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($riwayat_transaksi as $id_sewa => $sewa): ?>
                            <tr>
                                <td>#<?php echo $id_sewa; ?></td>
                                <td><?php echo date('d M Y', strtotime($sewa['tanggal_sewa'])); ?></td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        <?php foreach ($sewa['items'] as $item): ?>
                                            <li><?php echo htmlspecialchars($item['nama_produk']); ?> (<?php echo htmlspecialchars($item['jumlah']); ?> hari)</li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td><span class="badge bg-success">Selesai</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
    <?php endif; ?>
</div>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>
