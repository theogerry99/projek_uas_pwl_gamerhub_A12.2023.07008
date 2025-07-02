<?php
require_once '../config/database.php';

// Validasi server-side sederhana
if (empty($_POST['nama_lengkap']) || empty($_POST['email']) || empty($_POST['password'])) {
    // Menggunakan notifikasi terpusat
    header('Location: ../index.php?error=Harap isi semua kolom yang wajib diisi.');
    exit();
}

$nama_lengkap = $_POST['nama_lengkap'];
$email = $_POST['email'];
// Selalu hash password sebelum disimpan!
$password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
$no_hp = $_POST['no_hp'] ?? null;
$alamat = $_POST['alamat'] ?? null;

// Cek apakah email sudah ada
$stmt = $pdo->prepare("SELECT id_user FROM users WHERE email = ?");
$stmt->execute([$email]);
if ($stmt->fetch()) {
    // Menggunakan notifikasi terpusat
    header('Location: ../index.php?error=Email sudah terdaftar. Silakan gunakan email lain.'); // Notifikasi gagal registrasi (email sudah ada)
    exit();
}

// Masukkan data ke database
$stmt = $pdo->prepare("INSERT INTO users (nama_lengkap, email, password, no_hp, alamat) VALUES (?, ?, ?, ?, ?)");

try {
    $stmt->execute([$nama_lengkap, $email, $password, $no_hp, $alamat]);
    // Menggunakan notifikasi terpusat
    header('Location: ../index.php?status=reg_success'); // Notifikasi berhasil registrasi
    exit();
} catch (\PDOException $e) {
    // Menggunakan notifikasi terpusat
    header('Location: ../index.php?error=reg_failed'); // Notifikasi gagal registrasi (kesalahan database)
    exit();
}
?>
