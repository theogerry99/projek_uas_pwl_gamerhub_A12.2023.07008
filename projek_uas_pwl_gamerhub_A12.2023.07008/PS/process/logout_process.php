<?php
session_start();
session_destroy();
// Menggunakan notifikasi terpusat
header('Location: ../index.php?status=logout_success'); // Notifikasi berhasil logout
exit();
?>
