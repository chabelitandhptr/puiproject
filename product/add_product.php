<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Pastikan folder tujuan ada
$target_dir = __DIR__ . '/../assets/img/products/';
if (!is_dir($target_dir)) {
    mkdir($target_dir, 0777, true); // Buat folder jika belum ada
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = mysqli_real_escape_string($connection, $_POST['name']);
    $description = mysqli_real_escape_string($connection, $_POST['description']);
    $price = mysqli_real_escape_string($connection, $_POST['price']);

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
            $query = "INSERT INTO products (name, description, price, image) VALUES ('$name', '$description', '$price', '$image_name')";
            if (mysqli_query($connection, $query)) {
                echo "<script>alert('Produk berhasil ditambahkan!'); window.location.href = 'index.php';</script>";
                exit();
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


<section class="section">
    <div class="section-header">
        <h1>Add Product</h1>
    </div>
    
    <div class="container">
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" class="form-control" id="image" name="image" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Product</button>
        </form>
    </div>
</section>

<?php require_once '../layout/_bottom.php'; ?>
