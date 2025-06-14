<?php
require_once '../vendor/autoload.php'; // Pastikan path autoload sesuai dengan lokasi kamu
require_once '../helper/connection.php'; // Koneksi ke database

// Setting Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-bCRQAP2WyTnwCHw7Yly_MhvD'; // Ganti dengan server key dari Midtrans
\Midtrans\Config::$clientKey = 'SB-Mid-client-2LtTi-Yt7yTsUyop'; // Ganti dengan client key dari Midtrans
\Midtrans\Config::$isProduction = false; // Ubah ke true jika sudah di produksi
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;

// Ambil data dari form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = $_POST['quantity'];
    $endorsement_id = $_GET['endorsement_id'];

    // Mengambil data endorsement dan influencer
    $endorsement = $connection->query("SELECT * FROM endorsements WHERE id = '$endorsement_id'")->fetch_assoc();
    $influencer = $connection->query("SELECT * FROM influencers WHERE id = '{$endorsement['influencer_id']}'")->fetch_assoc();
    
    // Menghitung total harga
    $total_price = 0;
    foreach ($quantity as $service_id => $qty) {
        $rate_card = $connection->query("SELECT * FROM rate_cards WHERE id = '$service_id'")->fetch_assoc();
        $total_price += $rate_card['harga'] * $qty;
    }

    // Menyiapkan data transaksi untuk Midtrans
    $transaction_details = [
        'order_id' => 'ORD-' . time(), // Gunakan ID unik untuk order
        'gross_amount' => $total_price, // Total pembayaran
    ];

    $item_details = [];
    foreach ($quantity as $service_id => $qty) {
        $rate_card = $connection->query("SELECT * FROM rate_cards WHERE id = '$service_id'")->fetch_assoc();
        $item_details[] = [
            'id' => $service_id,
            'price' => $rate_card['harga'],
            'quantity' => $qty,
            'name' => $rate_card['jasa'],
        ];
    }

    // Detail pelanggan
    $customer_details = [
        'first_name' => $influencer['full_name'],
        'email' => $influencer['email'], // Ganti dengan email influencer
    ];

    // Mengirim request ke Midtrans API
    $transaction_data = [
        'transaction_details' => $transaction_details,
        'item_details' => $item_details,
        'customer_details' => $customer_details,
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($transaction_data);
        echo json_encode(['snapToken' => $snapToken]); // Mengembalikan snap token ke frontend
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
