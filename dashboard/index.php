<?php
require_once '../layout/_top.php';
require_once '../helper/connection.php';
<<<<<<< HEAD

$sql = "SELECT * FROM influencers";
$result = $connection->query($sql);
=======
>>>>>>> 188c392f357ca7fe800f838dc63cf92ef02bd99e
?>

<section class="section">
  <div class="section-header">
    <h1>Dashboard</h1>
  </div>

<<<<<<< HEAD
  <div class="section-body">
    <div class="row">
      <?php while ($row = $result->fetch_assoc()) : ?>
        <div class="col-md-4 col-lg-3 mb-4">
          <div class="card influencer-card shadow-sm border-0 rounded-lg overflow-hidden">
            <div class="position-relative">
              <img src="<?= !empty($row['profile_image']) 
                            ? '../assets/img/influencers/' . $row['profile_image'] 
                            : 'https://via.placeholder.com/400x200?text=No+Image'; ?>" 
                   class="card-img-top" 
                   alt="<?= htmlspecialchars($row['full_name']) ?>">
              <span class="badge badge-dark position-absolute top-0 start-0 m-2">PRISM Live Studio</span>
            </div>
            <div class="card-body">
              <h5 class="card-title mb-1">
                <?= htmlspecialchars($row['full_name']) ?> - 
                <?= htmlspecialchars($row['username']) ?> 
                <i class="fas fa-info-circle text-muted" style="font-size: 0.9rem;"></i>
              </h5>
              <p class="text-muted mb-2"><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($row['country']) ?></p>

              <div class="mb-2">
                <?php foreach (explode(',', $row['category']) as $cat) : ?>
                  <span class="badge badge-category"><?= trim(htmlspecialchars($cat)) ?></span>
                <?php endforeach; ?>
              </div>

              <div class="d-flex flex-wrap gap-2 mb-2 text-secondary small social-stats">
                <span><i class="fab fa-youtube"></i> <?= number_format($row['youtube']) ?></span>
                <span><i class="fab fa-twitter"></i> <?= number_format($row['followers']) ?></span>
                <span><i class="fab fa-instagram"></i> <?= number_format($row['instagram']) ?></span>
                <span>ER <?= htmlspecialchars($row['engagement_rate']) ?></span>
              </div>

              <p class="mb-2"><a href="#" class="text-info text-decoration-none">📩 Tanya Rate Card</a></p>

              <div class="testimonial-box mb-3">
                <div class="d-flex align-items-center">
                  <div class="testimonial-avatar">SA</div>
                  <div>
                    <strong>Stefani Andini</strong><br>
                    <small class="text-muted">fast response, ramah dan sesuai brief.</small>
                  </div>
                </div>
              </div>

              <div class="d-grid gap-2">
                <a href="#" class="btn btn-success btn-sm">Endorse</a>
                <a href="#" class="btn btn-primary btn-sm">Kirim Pesan</a>
              </div>
            </div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<style>
.card-title {
  font-weight: 600;
}

.badge-category {
  background-color: #f1f1f1;
  color: #333;
  border: 1px solid #ddd;
  font-size: 0.75rem;
  margin-right: 3px;
}

.badge-dark {
  background-color: #333;
  color: #fff;
  padding: 5px 8px;
  font-size: 0.7rem;
  border-radius: 4px;
}

.testimonial-box {
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 10px;
  font-size: 0.85rem;
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
  margin-right: 10px;
}

.card-img-top {
  height: 180px;
  object-fit: cover;
}
</style>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
=======
<?php
require_once '../layout/_bottom.php';
?>
>>>>>>> 188c392f357ca7fe800f838dc63cf92ef02bd99e
