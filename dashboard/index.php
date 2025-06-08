<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Query untuk mengambil data influencer
$sql = "SELECT * FROM influencers";
$result = $connection->query($sql);
?>

<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>
  <div class="section-body">
    <div class="row mb-4">
      <div class="col-md-6">
        <form method="GET" action="">
          <div class="d-flex">
            <select name="category" class="form-control" onchange="this.form.submit();">
              <option value="">Pilih Kategori</option>
              <?php
                $category_query = "SELECT DISTINCT category FROM influencers";
                $category_result = $connection->query($category_query);
                while ($cat_row = $category_result->fetch_assoc()) {
                  echo '<option value="' . htmlspecialchars($cat_row['category']) . '">' . htmlspecialchars($cat_row['category']) . '</option>';
                }
              ?>
            </select>
          </div>
        </form>
      </div>
    </div>

    <!-- Menampilkan Influencer -->
    <div class="row">
      <?php
        $category_filter = isset($_GET['category']) ? $_GET['category'] : '';
        
        if ($category_filter) {
          $sql = "SELECT * FROM influencers WHERE category LIKE '%$category_filter%'";
        } else {
          $sql = "SELECT * FROM influencers";
        }
        $result = $connection->query($sql);
      ?>

      <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="col-md-4 col-lg-3 mb-4">
          <div class="card influencer-card shadow-sm border-0 rounded-lg overflow-hidden">
            <!-- Gambar Profil -->
            <div class="position-relative">
              <img src="<?= !empty($row['profile_image']) 
                ? '../uploads/' . $row['profile_image'] 
                : 'https://via.placeholder.com/400x200?text=No+Image'; ?>" 
                class="card-img-top" 
                alt="<?= htmlspecialchars($row['full_name']) ?>" style="object-fit: cover; height: 200px;">
            </div>
            <div class="card-body text-center">
              <!-- Nama dan Username Influencer -->
              <h5 class="card-title"><?= htmlspecialchars($row['full_name']) ?></h5>
              

              <!-- Instagram dan Lokasi -->
              <?php if (!empty($row['instagram'])): ?>
                <p class="mt-2">
                  <i class="fab fa-instagram text-danger"></i>
                  @<?= htmlspecialchars(ltrim($row['instagram'], '@')) ?>
                </p>
              <?php endif; ?>
              <p class="text-muted">
                <i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['city']) ?>, <?= htmlspecialchars($row['province']) ?>
              </p>

              <!-- Kategori Influencer -->
              <div class="mb-2">
                <?php foreach (explode(',', $row['category']) as $cat) : ?>
                  <span class="badge badge-category"><?= trim(htmlspecialchars($cat)) ?></span>
                <?php endforeach; ?>
              </div>

              <!-- Jumlah Pengikut Instagram -->
              <?php if (!empty($row['followers_instagram'])) : ?>
                <div class="mb-2 text-secondary small social-stats">
                  <span><i class="fab fa-instagram"></i> <?= number_format($row['followers_instagram']) ?> followers</span>
                </div>
              <?php endif; ?>

              <!-- Tombol Lihat Rate Card dan Testimonial -->
              <p>
                <a href="javascript:void(0);" onclick="showRateCard('<?= $row['username'] ?>');" class="text-info text-decoration-none">📩 Lihat Rate Card</a>
              </p>

              <!-- Testimonial -->
              <div class="testimonial-box d-flex align-items-center mt-3">
                <div class="testimonial-avatar">SA</div>
                <div>
                  <strong>Stefani Andini</strong><br>
                  <small class="text-muted">fast response, ramah dan sesuai brief.</small>
                </div>
              </div>

              <!-- Tombol Endorse dan Kirim Pesan -->
              <div class="d-grid gap-2 mt-3">
                <a href="../transaksi1/endorsment.php?influencer_id=<?= $row['id'] ?>" class="btn btn-success btn-sm">Endorse</a>
                <a href="../pesan/sentMessage.php?influencer_id=<?= $row['id'] ?>" class="btn btn-primary btn-sm">Kirim Pesan</a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- Modal Rate Card -->
<div class="modal fade" id="rateCardModal" tabindex="-1" role="dialog" aria-labelledby="rateCardModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-scrollable modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rateCardModalLabel">Rate Card</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" id="rateCardContent">
        <p class="text-muted">Memuat...</p>
      </div>
    </div>
  </div>
</div>

<script>
function showRateCard(username) {
  fetch('../helper/get_rate_card.php?username=' + encodeURIComponent(username))
    .then(response => response.text())
    .then(data => {
      document.getElementById('rateCardContent').innerHTML = data;
      new bootstrap.Modal(document.getElementById('rateCardModal')).show();
    })
    .catch(err => {
      document.getElementById('rateCardContent').innerHTML = '<p class="text-danger">Gagal memuat data.</p>';
    });
}
</script>

<style>

/* Style card influencer */
.influencer-card {
  min-height: 400px; 
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  border-radius: 15px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.card-title {
  font-weight: 700;
  font-size: 1.2rem;
  margin-bottom: 0.5rem;
}

.card-body {
  padding: 1.2rem;
  text-align: center;
}

.card-img-top {
  height: 200px;
  object-fit: cover;
  width: 100%;
  border-bottom: 1px solid #ddd;
}

.badge-category {
  background-color: #f0f4f8;
  color: #333;
  border-radius: 12px;
  font-size: 0.75rem;
  padding: 5px 8px;
  margin: 3px 6px;
  display: inline-block;
}

.testimonial-box {
  background-color: #f4f6f8;
  border-left: 3px solid #28a745;
  padding: 10px;
  border-radius: 6px;
  font-size: 0.85rem;
  margin-top: 10px;
  text-align: center;
}

.testimonial-avatar {
  width: 32px;
  height: 32px;
  background-color: #28a745;
  color: #fff;
  border-radius: 50%;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  font-weight: bold;
  margin-right: 10px;
}

.social-stats span {
  font-size: 0.85rem;
  color: #6c757d;
}

.btn-success.btn-sm, .btn-primary.btn-sm {
  font-weight: 600;
  font-size: 0.85rem;
  padding: 6px 12px;
  border-radius: 4px;
  width: 100%;
}

a.text-info.text-decoration-none {
  font-weight: 500;
}

/* Responsif untuk ukuran kecil */
@media (max-width: 768px) {
  .influencer-card {
    min-height: 350px;
  }

  .card-img-top {
    height: 180px;
  }

  .card-title {
    font-size: 1rem;
  }
}
</style>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
