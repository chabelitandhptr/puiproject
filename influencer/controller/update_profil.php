<?php
require_once '../../helper/connection.php'; // Path ke file koneksi Anda
session_start();

$username = $_SESSION['username']; // Mengambil username dari session yang sudah ada

// Tangkap data dari form
$full_name = $_POST['full_name'] ?? null;
$phone = $_POST['phone'] ?? null;
$country = $_POST['country'] ?? null;
$instagram = $_POST['instagram'] ?? null;
$youtube = $_POST['youtube'] ?? null;
$twitter = $_POST['twitter'] ?? null;
$followers = $_POST['followers'] ?? null;
$engagement_rate = $_POST['engagement_rate'] ?? null;
$rate_card = $_POST['rate_card'] ?? null;
$category = $_POST['category'] ?? null;
$bio = $_POST['bio'] ?? null;
$linkbio_url = $_POST['linkbio_url'] ?? null;
$payment_method = $_POST['payment_method'] ?? null; // Ambil metode pembayaran
$account_number = $_POST['account_number'] ?? null; // Ambil nomor rekening

// Menangani upload gambar profil
$profile_image = $_FILES['profile_image'] ?? null;
$profile_image_path = null;

if ($profile_image && $profile_image['error'] == 0) {
    // Tentukan folder tempat menyimpan gambar
    $upload_dir = '../../uploads/';
    $profile_image_name = basename($profile_image['name']);
    $profile_image_path = $upload_dir . $profile_image_name;

    // Pindahkan file ke folder uploads
    if (move_uploaded_file($profile_image['tmp_name'], $profile_image_path)) {
        // Berhasil mengupload gambar
    } else {
        echo "Terjadi kesalahan saat mengupload gambar.";
    }
}

// Update data di database (periksa setiap field, update yang ada)
$query = "UPDATE influencers SET 
            full_name = IFNULL(?, full_name), 
            phone = IFNULL(?, phone), 
            country = IFNULL(?, country), 
            instagram = IFNULL(?, instagram), 
            youtube = IFNULL(?, youtube), 
            twitter = IFNULL(?, twitter), 
            followers = IFNULL(?, followers), 
            engagement_rate = IFNULL(?, engagement_rate), 
            rate_card = IFNULL(?, rate_card), 
            category = IFNULL(?, category), 
            bio = IFNULL(?, bio), 
            linkbio_url = IFNULL(?, linkbio_url),
            profile_image = IFNULL(?, profile_image),
            payment_method = IFNULL(?, payment_method),
            account_number = IFNULL(?, account_number)
          WHERE username = ?";

$stmt = $connection->prepare($query);
$stmt->bind_param(
    'ssssssssssssssss',
    $full_name, $phone, $country, $instagram, $youtube, 
    $twitter, $followers, $engagement_rate, $rate_card, $category,
    $bio, $linkbio_url, $profile_image_path, $payment_method, $account_number, $username
);

if ($stmt->execute()) {
    // Redirect ke halaman profil setelah berhasil update
    header("Location: ../profil.php?message=Profil berhasil diperbarui.");
    exit();
} else {
    echo "Terjadi kesalahan saat memperbarui data.";
}
?>
