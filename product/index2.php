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
    <h1>Campaign</h1>
  </div>

  <div class="container">
    <div class="row justify-content-center g-4">
      
      <!-- Tombol tambah produk -->
      <div class="col-md-3 d-flex justify-content-center">
        <a href="add_product.php" class="add-product-box">
          <h1>+</h1>
          <p>Add a product</p>
        </a>
      </div>

      <!-- Menampilkan daftar produk -->
      <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
          <div class="col-md-3 d-flex justify-content-center">
            <div class="card product-card shadow-sm">
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
                <a href="choose_influencer.php?product_id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Pilih</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-center">Belum ada produk.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- Styling -->
<style>
  .add-product-box, .product-card {
    width: 200px;
    height: 230px;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: 0.3s;
  }

  .add-product-box {
    border: 2px dashed #ccc;
    background-color: #f8f9fa;
    text-decoration: none;
    color: black;
  }

  .add-product-box:hover {
    background-color: #e0e0e0;
    border-color: #007bff;
  }

  .product-card {
    overflow: hidden;
    width: 200px; /* Sesuaikan dengan kotak tambah */
    height: 230px; /* Sesuaikan dengan kotak tambah */
  }

  .product-img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
  }

  .card-body {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
  }
</style>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
