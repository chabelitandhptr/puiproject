<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Mendapatkan influencer_id dari URL
$influencer_id = isset($_GET['influencer_id']) ? $_GET['influencer_id'] : 0;

// Query untuk mengambil data influencer berdasarkan ID
$sql = "SELECT * FROM influencers WHERE id = $influencer_id";
$result = $connection->query($sql);

// Mengecek apakah data influencer ditemukan
if ($result->num_rows > 0) {
  $row = $result->fetch_assoc();
} else {
  echo "Influencer tidak ditemukan.";
  exit;
}
?>

<section class="container-fluid mt-5">
  <div class="row">
    <!-- Kolom Kiri: Gambar Profil -->
    <div class="col-md-4 d-flex justify-content-center mb-4 mb-md-0">
      <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <img src="<?= !empty($row['profile_image']) ? '../uploads/' . $row['profile_image'] : 'https://via.placeholder.com/400x400?text=No+Image'; ?>" class="card-img-top" alt="<?= htmlspecialchars($row['full_name']) ?>" style="object-fit: cover; height: 300px; border-radius: 0.5rem;">
      </div>
    </div>

    <!-- Kolom Kanan: Detail Influencer -->
    <div class="col-md-8">
      <div class="card shadow-sm border-0 rounded-4 p-4">
        <div class="card-body">
          <h2 class="card-title mb-3"><?= htmlspecialchars($row['full_name']) ?></h2>

          <!-- Sosial Media dan Followers -->
          <div class="d-flex mb-3">
            <div class="me-4">
              <i class="fab fa-facebook fs-5"></i> 2.9K followers
            </div>
            <div class="me-4">
              <i class="fab fa-instagram fs-5"></i> 47.6K followers
            </div>
            <div>
              <i class="fab fa-youtube fs-5"></i> 4.5K followers
            </div>
          </div>

          <!-- Bio Influencer -->
          <p><strong>Bio:</strong> <?= htmlspecialchars($row['bio']) ?></p>

          <!-- Skills Influencer -->
          <div class="mb-3">
            <strong>Skills:</strong>
            <?php foreach (explode(',', $row['category']) as $cat) : ?>
              <span class="badge bg-info text-white"><?= trim(htmlspecialchars($cat)) ?></span>
            <?php endforeach; ?>
          </div>

          <!-- Lokasi Influencer -->
          <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['city']) ?>, <?= htmlspecialchars($row['province']) ?></p>

          <!-- Instagram Username -->
          <p><i class="fab fa-instagram"></i> @<?= htmlspecialchars(ltrim($row['instagram'], '@')) ?></p>

          <!-- Tombol Actions -->
          <div class="d-flex mt-4">
            <a href="#" class="btn btn-danger me-3 px-4 py-2">Completed Campaign</a>
            <a href="#" class="btn btn-primary px-4 py-2">Reviews</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CSS Custom -->
<style>
  /* Menggunakan font dari Google Fonts */
  @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Poppins:wght@400;500;600&display=swap');

  /* Mengatur font utama dan font tombol */
  body {
    font-family: 'Roboto', sans-serif;
  }

  .card-title {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    font-family: 'Poppins', sans-serif;
  }

  .badge {
    margin-right: 10px;
    font-size: 0.875rem;
    padding: 8px 12px;
  }

  .btn {
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 1rem;
    border-radius: 25px;
    padding: 10px 20px;
  }

  /* Mengatur kontainer agar menggunakan hampir seluruh lebar */
  .container-fluid {
    padding-left: 0;
    padding-right: 0;
    width: 100%;
  }

  .card-img-top {
    border-radius: 0.5rem;
    height: auto;
    max-width: 100%;
    margin-bottom: 20px;
  }

  .fab {
    font-size: 1.3rem;
    margin-right: 8px;
  }

  .card-body {
    padding: 2rem;
  }

  /* Responsive layout untuk ukuran layar kecil */
  @media (max-width: 768px) {
    .col-md-4, .col-md-8 {
      text-align: center;
    }

    .card-img-top {
      max-width: 250px;
      margin-bottom: 20px;
    }
  }
</style>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
