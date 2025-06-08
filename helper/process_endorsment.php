<?php
require_once '../helper/connection.php';

// Pastikan folder tujuan ada
$target_dir = __DIR__ . '/../uplouds/';
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Buat folder jika belum ada
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $promoted_item = mysqli_real_escape_string($connection, $_POST['promoted_item']);
    $details = mysqli_real_escape_string($connection, $_POST['details']);
    $tasks = mysqli_real_escape_string($connection, $_POST['tasks']);
    $requirements = mysqli_real_escape_string($connection, $_POST['requirements']);
    $service = mysqli_real_escape_string($connection, $_POST['service']);
    $free_product = mysqli_real_escape_string($connection, $_POST['free_product']);
    $contact_permission = isset($_POST['contact_permission']) ? 1 : 0;

    // Cek apakah ada file yang diunggah
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validasi tipe file (hanya jpg, jpeg, png)
        $allowed_types = ['jpg', 'jpeg', 'png'];
        if (!in_array($imageFileType, $allowed_types)) {
            die("Error: Hanya file JPG, JPEG, dan PNG yang diperbolehkan.");
        }

        // Pindahkan file ke folder target
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Simpan ke database (hanya nama file, bukan path lengkap)
            $query = "INSERT INTO endorsements (promoted_item, details, tasks, requirements, service, free_product, contact_permission, image, created_at)
                      VALUES ('$promoted_item', '$details', '$tasks', '$requirements', '$service', '$free_product', '$contact_permission', '$image_name', NOW())";
            if (mysqli_query($connection, $query)) {
                echo "Data berhasil disimpan!";
            } else {
                echo "Error: " . mysqli_error($connection);
            }
        } else {
            echo "Error: Gagal mengunggah gambar.";
        }
    } else {
        echo "Error: Tidak ada file yang diunggah.";
    }
}
?>
