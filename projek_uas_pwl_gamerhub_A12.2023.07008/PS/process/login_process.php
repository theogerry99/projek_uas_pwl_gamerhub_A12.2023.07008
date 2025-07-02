<?php
session_start(); // Wajib ada di awal setiap file yang butuh session
require_once '../config/database.php';

if (empty($_POST['email']) || empty($_POST['password'])) {
    // Menggunakan notifikasi terpusat
    header('Location: ../index.php?error=Email dan password tidak boleh kosong.');
    exit();
}

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch();

// Verifikasi user dan password
if ($user && password_verify($password, $user['password'])) {
    // Login sukses, simpan data user di session
    $_SESSION['user_id'] = $user['id_user'];
    $_SESSION['user_nama'] = $user['nama_lengkap'];
    $_SESSION['user_role'] = $user['role'];
    
    // Menggunakan notifikasi terpusat
    header('Location: ../index.php?status=login_success'); // Notifikasi berhasil login
    exit();
} else {
    // Login gagal
    // Menggunakan notifikasi terpusat
    header('Location: ../index.php?error=login_failed'); // Notifikasi gagal login
    exit();
}
?>
