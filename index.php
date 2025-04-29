<?php
session_start();

if (isset($_SESSION['login'])) {
    $role = $_SESSION['login']['role']; // Sekarang role sudah tersedia

    if ($role === 'influencer') {
        header('Location: influencer/index.php');
    } elseif ($role === 'brand') {
        header('Location: dashboard/umkm/index.php');
    } else {
        header('Location: dashboard/index.php');
    }
} else {
    header('Location: login.php');
}
