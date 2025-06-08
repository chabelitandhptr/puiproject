<?php
$host = "localhost"; // Sesuaikan dengan server database
$user = "root";      // Sesuaikan dengan username database
$pass = "";          // Sesuaikan dengan password database (jika ada)
$db   = "collabnest2"; // Sesuaikan dengan nama database

$connection = mysqli_connect($host, $user, $pass, $db);

// Cek koneksi
if (!$connection) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
