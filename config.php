<?php
$host = "localhost"; // Host database (biasanya localhost)
$user = "root"; // Username MySQL (default: root di XAMPP)
$pass = ""; // Password MySQL (kosong di XAMPP, jika ada ubah sesuai pengaturan)
$dbname = "collabnest"; // Nama database

// Membuat koneksi
$conn = new mysqli($host, $user, $pass, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
