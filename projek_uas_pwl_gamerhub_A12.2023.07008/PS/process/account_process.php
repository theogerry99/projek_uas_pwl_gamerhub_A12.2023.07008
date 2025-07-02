<?php
session_start();
require_once '../config/database.php';

// Otentikasi: Pastikan pengguna login dan form disubmit
if (!isset($_SESSION['user_id']) || !isset($_POST['update_profile'])) {
    header('Location: ../index.php');
    exit();
}

$id_user = $_SESSION['user_id'];
$nama_lengkap = $_POST['nama_lengkap'];
$no_hp = $_POST['no_hp'];
$alamat = $_POST['alamat'];
$password_baru = $_POST['password_baru'];
$konfirmasi_password = $_POST['konfirmasi_password'];

// Array untuk menampung query dan parameter, untuk fleksibilitas
$sql_parts = [];
$params = [];

// Selalu update data dasar
$sql_parts[] = "nama_lengkap = ?";
$params[] = $nama_lengkap;
$sql_parts[] = "no_hp = ?";
$params[] = $no_hp;
$sql_parts[] = "alamat = ?";
$params[] = $alamat;

// Cek jika pengguna ingin mengubah password
if (!empty($password_baru)) {
    if ($password_baru !== $konfirmasi_password) {
        header('Location: ../account.php?error=password_mismatch');
        exit();
    }
    // Hash password baru sebelum disimpan
    $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);
    $sql_parts[] = "password = ?";
    $params[] = $hashed_password;
}

// Gabungkan semua bagian query menjadi satu
$sql = "UPDATE users SET " . implode(', ', $sql_parts) . " WHERE id_user = ?";
$params[] = $id_user;

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    // Redirect kembali ke halaman akun dengan pesan sukses
    header('Location: ../account.php?status=profile_updated');
    exit();

} catch (PDOException $e) {
    // Tangani jika ada error saat update
    header('Location: ../account.php?error=' . urlencode($e->getMessage()));
    exit();
}
?>