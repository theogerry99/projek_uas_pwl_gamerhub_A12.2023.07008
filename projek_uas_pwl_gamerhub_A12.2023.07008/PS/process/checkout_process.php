<?php
session_start();
require_once '../config/database.php';

// Otentikasi dan Otorisasi
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php?error=notloggedin'); // Notifikasi harus login
    exit();
}

$id_user = $_SESSION['user_id'];

try {
    // 1. Cari keranjang aktif milik pengguna
    $stmt_sewa = $pdo->prepare("SELECT * FROM sewa WHERE id_user = ? AND status = 'keranjang'");
    $stmt_sewa->execute([$id_user]);
    $sewa = $stmt_sewa->fetch();

    if (!$sewa) {
        throw new Exception("Keranjang tidak ditemukan atau sudah di-checkout.");
    }
    $id_sewa = $sewa['id_sewa'];

    // 2. Ambil semua item di keranjang untuk menghitung total & durasi
    $stmt_items = $pdo->prepare("SELECT jumlah, harga_saat_sewa FROM detail_sewa WHERE id_sewa = ?");
    $stmt_items->execute([$id_sewa]);
    $items = $stmt_items->fetchAll();

    if (empty($items)) {
        throw new Exception("Tidak ada item di dalam keranjang.");
    }

    $total_harga_final = 0;
    $max_durasi = 0;
    foreach ($items as $item) {
        $total_harga_final += $item['jumlah'] * $item['harga_saat_sewa'];
        if ($item['jumlah'] > $max_durasi) {
            $max_durasi = $item['jumlah'];
        }
    }

    // 3. Tentukan tanggal sewa dan tanggal kembali
    $tanggal_sewa = date('Y-m-d H:i:s'); // Waktu saat ini
    $tanggal_kembali = date('Y-m-d H:i:s', strtotime($tanggal_sewa . " + $max_durasi days"));

    // 4. Lakukan UPDATE pada tabel 'sewa'
    $stmt_update = $pdo->prepare("
        UPDATE sewa 
        SET status = 'disewa', 
            tanggal_sewa = ?, 
            tanggal_kembali_rencana = ?, 
            total_harga = ?
        WHERE id_sewa = ?
    ");
    $stmt_update->execute([$tanggal_sewa, $tanggal_kembali, $total_harga_final, $id_sewa]);

    // 5. Redirect ke halaman akun dengan pesan sukses
    // Halaman akun adalah tempat yang paling pas untuk melihat pinjaman aktif
    header('Location: ../account.php?status=checkout_success'); // Notifikasi checkout berhasil
    exit();

} catch (Exception $e) {
    header('Location: ../checkout.php?error=' . urlencode($e->getMessage())); // Notifikasi checkout gagal
    exit();
}
?>
