<?php
session_start();

if (isset($_SESSION['login'])) {
    $role = $_SESSION['login']['role']; // Mendapatkan role pengguna dari session

    // Jika role adalah 'influencer', arahkan ke halaman influencer
    if ($role === 'influencer') {
        header('Location: influencer/index.php');
    }
    // Jika role adalah 'umkm', arahkan ke dashboard UMKM
    elseif ($role === 'umkm') {
        header('Location: dashboard/index.php'); // Pastikan file ini ada
    }
    // Jika role lain (misalnya admin), arahkan ke dashboard umum atau halaman lain
    else {
        header('Location: dashboard/index.php'); // Atau bisa ditujukan ke halaman admin
    }
} else {
    // Jika belum login, arahkan ke halaman login
    header('Location: login.php');
}
