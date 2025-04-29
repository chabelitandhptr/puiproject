<?php
session_start();
<<<<<<< HEAD

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
=======
if(isset($_SESSION['login'])){
  header('Location: dashboard/index.php');
}else{
  header('Location: ./login.php');
}
>>>>>>> 188c392f357ca7fe800f838dc63cf92ef02bd99e
