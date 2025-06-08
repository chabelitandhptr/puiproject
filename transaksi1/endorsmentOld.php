<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Ambil data produk dari database
$product_query = "SELECT * FROM products";
$product_result = $connection->query($product_query);

// Ambil data influencer dari URL
$influencer_id = isset($_GET['influencer_id']) ? $_GET['influencer_id'] : null;

if ($influencer_id) {
    // Ambil detail influencer berdasarkan ID
    $influencer_query = "SELECT * FROM influencers WHERE id = '$influencer_id'";
    $influencer_result = $connection->query($influencer_query);

    if ($influencer_result->num_rows > 0) {
        $influencer = $influencer_result->fetch_assoc();
    } else {
        echo "<p>Influencer tidak ditemukan.</p>";
        exit;
    }

    // Ambil data rate card untuk influencer ini
    $rate_card_query = "SELECT * FROM rate_cards WHERE username = '$influencer[username]'";
    $rate_card_result = $connection->query($rate_card_query);
    $rate_cards = [];
    if ($rate_card_result->num_rows > 0) {
        while ($rate_card = $rate_card_result->fetch_assoc()) {
            $rate_cards[] = $rate_card;
        }
    }
} else {
    echo "<p>ID Influencer tidak ditemukan.</p>";
    exit;
}
?>

<section class="section">
  <form method="POST" action="../helper/process_endorsement.php" id="endorse-form">
    
    <!-- Pilihan Produk dengan Gambar -->
    <div class="form-group">
      <label>Pilih Produk untuk Endorse</label>
      <div class="row">
        <?php while ($product = $product_result->fetch_assoc()) : ?>
          <div class="col-md-3">
            <div class="card" style="width: 18rem;">
              <img src="<?= !empty($product['image']) ? '../assets/img/products/' . htmlspecialchars($product['image']) : 'https://via.placeholder.com/150x150' ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>">
              <div class="card-body text-center">
                <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                <a href="javascript:void(0);" class="btn btn-outline-success select-product" 
                   data-product-id="<?= $product['id'] ?>" 
                   data-product-name="<?= htmlspecialchars($product['name']) ?>" 
                   data-product-image="<?= !empty($product['image']) ? '../assets/img/products/' . htmlspecialchars($product['image']) : 'https://via.placeholder.com/150x150' ?>">Pilih</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    </div>

    <!-- Influencer info -->
    <div class="form-group">
      <label>Influencer yang Dipilih</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($influencer['full_name']) ?>" disabled>
      <input type="hidden" name="influencer" value="<?= $influencer['id'] ?>">
      <input type="hidden" name="product" id="product-id">
      <input type="hidden" name="product-name" id="product-name">
      <input type="hidden" name="product-image" id="product-image">
    </div>

    <!-- Metode Pembayaran -->
    <div class="form-group">
      <label>Metode Pembayaran Influencer</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($influencer['payment_method'] ?? 'Tidak Ada Metode Pembayaran') ?>" disabled>
    </div>
    <div class="form-group">
      <label>Nomor Rekening Influencer</label>
      <input type="text" class="form-control" value="<?= htmlspecialchars($influencer['account_number'] ?? 'Tidak Ada Nomor Rekening') ?>" disabled>
    </div>

    <!-- Pilihan jasa -->
    <div class="form-group">
      <label for="service">Pilih Jasa Influencer</label>
      <select name="service" id="service" class="form-control" required>
        <option value="">Pilih Jasa</option>
        <?php foreach ($rate_cards as $rate_card) : ?>
          <option value="<?= htmlspecialchars($rate_card['jasa']) ?>" data-price="<?= htmlspecialchars($rate_card['harga']) ?>">
            <?= htmlspecialchars($rate_card['jasa']) ?> - Rp <?= number_format($rate_card['harga'], 0, ',', '.') ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <button type="submit" class="btn btn-success">Kirim Endorsement</button>
    </div>
  </form>
</section>

<script>
  // Handle pilih produk dengan gambar
  const selectProductButtons = document.querySelectorAll('.select-product');
  
  selectProductButtons.forEach(button => {
    button.addEventListener('click', function() {
      const productId = this.getAttribute('data-product-id');
      const productName = this.getAttribute('data-product-name');
      const productImage = this.getAttribute('data-product-image');

      document.getElementById('product-id').value = productId;
      document.getElementById('product-name').value = productName;
      document.getElementById('product-image').value = productImage;

      alert('Produk Terpilih: ' + productName);
    });
  });

  // Validasi sebelum submit form
  document.getElementById('endorse-form').addEventListener('submit', function(e) {
    const productId = document.getElementById('product-id').value;
    const service = document.getElementById('service').value;

    if (!productId) {
      alert('Silakan pilih produk terlebih dahulu.');
      e.preventDefault();
      return false;
    }

    if (!service) {
      alert('Silakan pilih jasa influencer.');
      e.preventDefault();
      return false;
    }
  });
</script>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
