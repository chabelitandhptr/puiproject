<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Ambil data produk dari database
$query = "SELECT * FROM products";
$result = $connection->query($query);

// Cek jika query error
if (!$result) {
    die("Query error: " . $connection->error);
}
?>

<section class="section">
  <div class="section-header">
    <h1>Daftar Produk</h1>
  </div>

  <div class="container">
    <div class="row justify-content-center g-4">
      
      <!-- Tombol tambah produk -->
      <div class="col-md-4 col-sm-6 d-flex justify-content-center">
        <a href="add_product.php" class="add-product-box shadow">
          <h1 class="display-4">+</h1>
          <p class="mt-2">Tambah Produk</p>
        </a>
      </div>

      <!-- Menampilkan daftar produk -->
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-4 col-sm-6 d-flex justify-content-center">
            <div class="card product-card shadow-sm border-0 rounded-3">
              <?php
                $image_path = "../assets/img/products/" . htmlspecialchars($row['image']);
                if (!file_exists($image_path) || empty($row['image'])) {
                  $image_path = "../assets/img/default.png"; // Placeholder jika gambar tidak ditemukan
                }
              ?>
              <img src="<?php echo $image_path; ?>" class="card-img-top product-img" alt="Product Image">
              <div class="card-body text-center">
                <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                <p class="card-text text-muted"><?php echo htmlspecialchars($row['description']); ?></p>
                <a href="choose_influencer.php?product_id=<?php echo $row['id']; ?>" class="btn btn-success btn-sm rounded-3">Pilih</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center text-muted">Belum ada produk.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Styling -->
<style>
  .add-product-box {
    width: 250px;
    height: 250px;
    border-radius: 15px;
    background-color: #f8f9fa;
    text-decoration: none;
    color: #333;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s ease, transform 0.3s ease;
  }

  .add-product-box:hover {
    background-color: #007bff;
    color: #fff;
    transform: translateY(-5px);
  }

  .add-product-box h1 {
    font-size: 4rem;
    margin: 0;
  }

  .add-product-box p {
    font-size: 1rem;
    font-weight: 500;
  }

  .product-card {
    width: 100%;
    border-radius: 10px;
    overflow: hidden;
    transition: transform 0.3s ease;
  }

  .product-card:hover {
    transform: translateY(-10px);
    box-shadow: 0px 12px 20px rgba(0, 0, 0, 0.1);
  }

  .product-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
  }

  .card-body {
    padding: 1rem;
  }

  .card-title {
    font-size: 1.2rem;
    font-weight: bold;
  }

  .card-text {
    font-size: 0.9rem;
    color: #6c757d;
  }

  .btn-success {
    background-color: #28a745;
    border-color: #28a745;
  }

  .btn-success:hover {
    background-color: #218838;
    border-color: #1e7e34;
  }
</style>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
