<?php
// Selalu mulai session di awal untuk mengakses data login
session_start();
require_once '../config/database.php';

// Langkah 1: OTENTIKASI PENGGUNA
// Pastikan ada pengguna yang login sebelum melakukan aksi apapun.
if (!isset($_SESSION['user_id'])) {
    // Jika tidak ada session, kembalikan ke halaman utama atau tampilkan error.
    // Mengirim respons JSON lebih cocok untuk aksi AJAX (seperti 'add').
    if (isset($_POST['action']) && $_POST['action'] == 'add') {
        echo json_encode([
            'success' => false,
            'message' => 'Anda harus login terlebih dahulu!'
        ]);
    } else {
        // Untuk aksi lain (update/remove) yang redirect, kita bisa langsung arahkan.
        header('Location: ../keranjang.php?error=notloggedin'); // Notifikasi harus login
    }
    exit(); // Hentikan eksekusi skrip
}

$id_user = $_SESSION['user_id'];
// Tentukan aksi berasal dari POST (form) atau GET (link)
$action = $_POST['action'] ?? $_GET['action'] ?? null;

try {
    // ===================================================================
    // AKSI: MENAMBAHKAN ITEM (CREATE) - Dipanggil oleh AJAX dari halaman produk
    // ===================================================================
    if ($action === 'add' && isset($_POST['product_id'])) {
        $id_produk = (int)$_POST['product_id'];

        $stmt_produk = $pdo->prepare("SELECT harga_sewa_harian FROM produk WHERE id_produk = ?");
        $stmt_produk->execute([$id_produk]);
        $produk = $stmt_produk->fetch();

        if (!$produk) {
            throw new Exception("Produk tidak ditemukan.");
        }
        $harga_produk = $produk['harga_sewa_harian'];

        $stmt_sewa = $pdo->prepare("SELECT id_sewa FROM sewa WHERE id_user = ? AND status = 'keranjang'");
        $stmt_sewa->execute([$id_user]);
        $sewa = $stmt_sewa->fetch();

        if (!$sewa) {
            $stmt_new_sewa = $pdo->prepare("INSERT INTO sewa (id_user, status) VALUES (?, 'keranjang')");
            $stmt_new_sewa->execute([$id_user]);
            $id_sewa = $pdo->lastInsertId();
        } else {
            $id_sewa = $sewa['id_sewa'];
        }

        $stmt_detail = $pdo->prepare("SELECT id_detail_sewa, jumlah FROM detail_sewa WHERE id_sewa = ? AND id_produk = ?");
        $stmt_detail->execute([$id_sewa, $id_produk]);
        $detail_item = $stmt_detail->fetch();

        if ($detail_item) {
            $new_quantity = $detail_item['jumlah'] + 1;
            $stmt_update = $pdo->prepare("UPDATE detail_sewa SET jumlah = ? WHERE id_detail_sewa = ?");
            $stmt_update->execute([$new_quantity, $detail_item['id_detail_sewa']]);
        } else {
            $stmt_insert = $pdo->prepare("INSERT INTO detail_sewa (id_sewa, id_produk, jumlah, harga_saat_sewa) VALUES (?, ?, 1, ?)");
            $stmt_insert->execute([$id_sewa, $id_produk, $harga_produk]);
        }
        
        $stmt_count = $pdo->prepare("SELECT SUM(jumlah) as total_items FROM detail_sewa WHERE id_sewa = ?");
        $stmt_count->execute([$id_sewa]);
        $cart_count = $stmt_count->fetchColumn();

        echo json_encode([
            'success' => true,
            'message' => 'Item berhasil ditambahkan ke keranjang!', // Notifikasi tambah keranjang
            'cart_count' => $cart_count
        ]);
        exit();

    // ===================================================================
    // AKSI: MEMPERBARUI JUMLAH ITEM (UPDATE) - Dipanggil oleh form dari keranjang.php
    // ===================================================================
    } else if ($action === 'update' && isset($_POST['id_detail_sewa']) && isset($_POST['quantity'])) {
        $id_detail_sewa = (int)$_POST['id_detail_sewa'];
        $quantity = (int)$_POST['quantity'];
        $quantity = ($quantity < 1) ? 1 : $quantity; // Pastikan jumlah minimal 1

        $stmt_auth = $pdo->prepare("SELECT ds.id_detail_sewa FROM detail_sewa ds JOIN sewa s ON ds.id_sewa = s.id_sewa WHERE ds.id_detail_sewa = ? AND s.id_user = ?");
        $stmt_auth->execute([$id_detail_sewa, $id_user]);
        
        if ($stmt_auth->fetch()) {
            $stmt_update = $pdo->prepare("UPDATE detail_sewa SET jumlah = ? WHERE id_detail_sewa = ?");
            $stmt_update->execute([$quantity, $id_detail_sewa]);
            header('Location: ../keranjang.php?status=updated'); // Notifikasi update keranjang
            exit();
        } else {
            throw new Exception("Aksi update tidak diizinkan.");
        }

    // ===================================================================
    // AKSI: MENGHAPUS ITEM (DELETE) - Dipanggil oleh link dari keranjang.php
    // ===================================================================
    } else if ($action === 'remove' && isset($_GET['item_id'])) {
        $id_detail_sewa = (int)$_GET['item_id'];

        $stmt_auth = $pdo->prepare("SELECT ds.id_detail_sewa FROM detail_sewa ds JOIN sewa s ON ds.id_sewa = s.id_sewa WHERE ds.id_detail_sewa = ? AND s.id_user = ?");
        $stmt_auth->execute([$id_detail_sewa, $id_user]);
        
        if ($stmt_auth->fetch()) {
            $stmt_delete = $pdo->prepare("DELETE FROM detail_sewa WHERE id_detail_sewa = ?");
            $stmt_delete->execute([$id_detail_sewa]);
            header('Location: ../keranjang.php?status=deleted'); // Notifikasi hapus keranjang
            exit();
        } else {
            throw new Exception("Aksi hapus tidak diizinkan.");
        }
    } else {
        // Jika tidak ada aksi yang cocok
        throw new Exception("Aksi tidak valid atau parameter tidak lengkap.");
    }

} catch (Exception $e) {
    // Menangani semua jenis error yang mungkin terjadi
    // Bisa redirect ke halaman error atau kembali ke keranjang dengan pesan error
    header('Location: ../keranjang.php?error=' . urlencode($e->getMessage()));
    exit();
}
?>
