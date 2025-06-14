<?php
require_once '../helper/connection.php'; // Koneksi ke database

// Ambil data dari request
$endorsement_id = $_POST['endorsement_id'];
$influencer_id = $_POST['influencer_id'];
$service = $_POST['service'];
$price = $_POST['price'];
$payment_method = $_POST['payment_method'];
$status = $_POST['status'];
$transaction_id = $_POST['transaction_id'];

// Query untuk menyimpan transaksi
$query = "INSERT INTO transactions 
            (endorsement_id, influencer_id, service, price, payment_method, status, transaction_id, transaction_date, created_at, updated_at)
          VALUES 
            ('$endorsement_id', '$influencer_id', '$service', '$price', '$payment_method', '$status', '$transaction_id', NOW(), NOW(), NOW())";

// Debugging: Menampilkan query yang akan dijalankan
echo "Query yang dijalankan: " . $query . "<br>";

if (mysqli_query($connection, $query)) {
    echo "Transaksi berhasil disimpan";
} else {
    // Menampilkan error jika gagal
    echo "Error saat menyimpan transaksi: " . mysqli_error($connection);
}
?>
