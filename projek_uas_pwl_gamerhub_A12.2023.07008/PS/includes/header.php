<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head> <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
/>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GamerHub - Rental PS & Sewa Game</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css?v=1.1">
</head>
<body>
    <?php
    // === BLOK NOTIFIKASI TERPUSAT ===
    function tampilkan_notifikasi() {
        if (!isset($_GET['status']) && !isset($_GET['error'])) {
            return; // Jika tidak ada notifikasi, hentikan fungsi
        }

        $pesan = '';
        $tipe = '';

        // Logika untuk menentukan pesan dan tipe notifikasi
        if (isset($_GET['status'])) {
            $tipe = 'success'; // Default untuk status adalah success
            switch ($_GET['status']) {
                case 'reg_success':
                    $pesan = 'Registrasi berhasil! Silakan login untuk melanjutkan.';
                    break;
                case 'login_success':
                    $pesan = 'Anda berhasil login. Selamat datang kembali!';
                    break;
                case 'deleted':
                    $pesan = 'Item berhasil dihapus dari keranjang.';
                    break;
                case 'updated':
                    $pesan = 'Jumlah item berhasil diperbarui.';
                    break;
                case 'checkout_success':
                    $pesan = 'Checkout berhasil! Pesanan Anda sedang diproses.';
                    break;
                case 'profile_updated':
                    $pesan = 'Profil Anda berhasil diperbarui.';
                    break;
                case 'logout_success': // Notifikasi baru
                    $pesan = 'Anda telah berhasil logout.';
                    break;
            }
        } elseif (isset($_GET['error'])) {
            $tipe = 'error'; // Default untuk error adalah error
            switch ($_GET['error']) {
                case 'login_failed':
                    $pesan = 'Login gagal. Email atau password yang Anda masukkan salah.';
                    break;
                case 'reg_failed': // Notifikasi baru
                    $pesan = 'Registrasi gagal. Email mungkin sudah terdaftar atau terjadi kesalahan.';
                    break;
                case 'loginrequired': 
                case 'notloggedin': // Notifikasi baru
                    $pesan = 'Anda harus login untuk mengakses fitur ini.';
                    break;
                default:
                    // Menampilkan pesan error custom dari URL
                    $pesan = htmlspecialchars($_GET['error']);
                    break;
            }
        }

        // Cetak elemen notifikasi jika ada pesan yang valid
        if ($pesan && $tipe) {
            echo '<div class="container mt-4" id="notification-wrapper">'; // Tambahkan ID wrapper
            echo '<div class="notification-bar" data-type="'. $tipe .'">';
            echo '<span>'. $pesan .'</span>';
            // Tombol close opsional
            echo '<button class="notification-close" onclick="this.parentElement.style.display=\'none\'">&times;</button>';
            echo '</div>';
            echo '</div>';

            // Script untuk menghilangkan notifikasi secara otomatis
            echo "
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const notificationWrapper = document.getElementById('notification-wrapper');
                    if (notificationWrapper) {
                        setTimeout(() => {
                            const notifBar = notificationWrapper.querySelector('.notification-bar');
                            if (notifBar) {
                                notifBar.classList.add('fade-out');
                                // Hapus elemen dari DOM setelah animasi selesai
                                notifBar.addEventListener('animationend', () => {
                                    notificationWrapper.remove();
                                });
                            }
                        }, 5000); // Hilang setelah 5 detik
                    }
                });
            </script>
            ";
        }
    }

?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-gamepad me-2"></i>GamerHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home me-1"></i> Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="koleksigame.php"><i class="fas fa-gamepad me-1"></i> Koleksi Game</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="koleksips.php"><i class="fas fa-tv me-1"></i> Koleksi PS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="account.php"><i class="fas fa-user me-1"></i> Akun</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="keranjang.php" class="btn btn-outline-light position-relative me-2">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartItemCount">
                            0
                        </span>
                    </a>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="process/logout_process.php" class="btn btn-danger"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                    <?php else: ?>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>
    <?php tampilkan_notifikasi(); // Panggil fungsi notifikasi di sini ?>
