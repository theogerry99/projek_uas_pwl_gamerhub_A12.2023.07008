Proyek UAS - Website Rental PS "GamerHub"
Proyek UAS - Website Rental PS "GamerHub" GamerHub adalah aplikasi web fungsional yang dibuat untuk memenuhi Ujian Akhir Semester mata kuliah Pemrograman Web Lanjut. Aplikasi ini mensimulasikan platform penyewaan (rental) konsol PlayStation dan video game, di mana pengguna dapat mendaftar, menelusuri produk, melakukan penyewaan, dan mengelola akun mereka.

👥 Tim Pengembang Proyek ini disusun dan dikembangkan oleh:

Bngkit Agung N (A12.2023.07089)

Theodorus Gerry (A12.2023.07008)

Nabilla Zahra Diyas (A12.2023.07026)

Muhammad Dzaky Hamid (A12.2023.07109)

Nadya Laksitaningtyas (A12.2023.07100)

✨ Fitur Utama
Proyek ini mencakup fungsionalitas e-commerce standar yang disesuaikan untuk sistem rental:

Autentikasi Pengguna

Registrasi: Pengguna dapat membuat akun baru yang datanya disimpan ke database dengan kata sandi yang di-hash.
Login: Pengguna dapat masuk ke akun mereka menggunakan email dan kata sandi. Sistem menggunakan PHP session untuk mengelola status login.
Logout: Menghancurkan session pengguna dan mengarahkan kembali ke halaman utama.
Katalog Produk

Penjelajahan produk yang dibagi menjadi dua kategori utama: Koleksi Game dan Koleksi PS.
Setiap produk ditampilkan dalam format kartu (card) yang berisi gambar, nama, deskripsi singkat, dan harga sewa harian.
Keranjang Belanja Interaktif

Tambah ke Keranjang: Menambahkan produk ke keranjang secara dinamis (tanpa refresh halaman) menggunakan JavaScript fetch API.
Lihat Keranjang: Halaman khusus (keranjang.php) untuk melihat semua item, total biaya, dan mengatur jumlah sewa.
Update & Hapus: Pengguna dapat memperbarui jumlah hari sewa atau menghapus item langsung dari halaman keranjang.
Proses Checkout & Sewa

Halaman ringkasan (checkout.php) untuk memeriksa kembali item sebelum melakukan konfirmasi sewa.
Setelah konfirmasi, status pesanan diubah dari keranjang menjadi disewa di database, dan tanggal sewa dicatat.
Dasbor Akun Pengguna

Halaman terpusat (account.php) dengan beberapa tab:
Profil: Mengubah informasi pribadi seperti nama, no. HP, dan kata sandi.
Pinjaman Aktif: Melihat daftar semua item yang sedang dalam status "disewa", lengkap dengan tanggal kembali.
Riwayat: Melihat riwayat transaksi dari penyewaan yang telah selesai.
🏗 Arsitektur & Pola Desain
Meskipun tidak menggunakan framework formal, proyek ini menerapkan prinsip dasar dari pola arsitektur Model-View-Controller (MVC) untuk memisahkan logika dan tampilan.

Model: Bertanggung jawab atas semua interaksi database dan logika bisnis. Terletak di dalam direktori PS/process/ (misalnya cart_process.php, login_process.php) dan file konfigurasi PS/config/database.php.
View: Bertanggung jawab untuk presentasi data (UI). Terdiri dari file-file PHP di direktori root seperti index.php, keranjang.php, dan account.php, serta komponen includes/header.php dan includes/footer.php.
Controller: Bertindak sebagai penghubung. Logika controller berada di sisi server (file-file di PS/process/ yang menerima input) dan di sisi klien (PS/assets/js/script.js) yang menangani interaksi pengguna dan memanggil backend.
🛠 Teknologi yang Digunakan
Backend
PHP: Sebagai bahasa pemrograman utama di sisi server.
MySQL: Sistem manajemen database untuk menyimpan semua data aplikasi.
PDO (PHP Data Objects): Digunakan untuk koneksi database yang aman dan untuk mencegah SQL Injection.
Frontend
HTML5 & CSS3: Untuk struktur dan styling halaman web.
JavaScript (ES6): Untuk fungsionalitas dinamis seperti notifikasi dan "Add to Cart".
Bootstrap 5: Framework CSS untuk membangun antarmuka yang responsif dan modern.
Font Awesome: Untuk ikon-ikon di seluruh aplikasi.
Animate.css: Library untuk menambahkan animasi sederhana pada elemen UI.
🚀 Cara Menjalankan Proyek
Berikut adalah langkah-langkah untuk menjalankan proyek ini di lingkungan lokal:

Prasyarat:

Pastikan Anda memiliki server web lokal seperti XAMPP atau Laragon yang sudah terinstal dan berjalan (Apache & MySQL).
Dapatkan File Proyek:

Unduh atau kloning repositori ini ke komputer Anda.
Letakkan folder proyek di dalam direktori root server web Anda (htdocs untuk XAMPP, www untuk Laragon).
Setup Database:

Buka phpMyAdmin dari panel kontrol XAMPP/Laragon.
Buat database baru dengan nama gamerhub_db.
Pilih database gamerhub_db yang baru dibuat, lalu buka tab "Import".
Pilih file PS/gamerhub_db.sql dan klik "Go" atau "Import" untuk membuat semua tabel dan mengisi data awal.
Konfigurasi Koneksi:

Buka file PS/config/database.php.
Pastikan variabel $user dan $pass sesuai dengan konfigurasi database MySQL Anda. (Default XAMPP: $user = 'root'; dan $pass = '';).
Akses Aplikasi:

Buka browser web Anda.
Akses aplikasi melalui URL: http://localhost/NAMA_FOLDER_PROYEK/PS/
👥 Anggota Kelompok
1.Bangkit Agung N (A12.2023.07089) 2.Theodorus Gerry (A12.2023.07008) 3.Nabilla Zahra Diyas (A12.2023.07026) 4.Muhammad Dzaky Hamid (A12.2023.07109) 5.Nadya Laksitaningtyas (A12.2023.07100)
