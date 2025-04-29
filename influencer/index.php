<?php
<<<<<<< HEAD
require_once '../influencer/layout/_top.php';
=======
require_once '../layout/_top.php';
require_once '../helper/connection.php';

// Ambil data influencer dari database
$sql = "SELECT * FROM influencers";
$result = $connection->query($sql);
>>>>>>> 188c392f357ca7fe800f838dc63cf92ef02bd99e
?>

<section class="section">
  <div class="section-header">
<<<<<<< HEAD
    <h1>Dashboard Influencer</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body text-center">
            <h5 class="mb-3">Selamat datang di Dashboard Influencer 🎉</h5>
            <p class="text-muted">Belum ada data yang ditampilkan saat ini.</p>
            <a href="#" class="btn btn-primary btn-sm">Mulai Eksplor</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

=======
    <h1>influencer</h1>
  </div>
  
  <div class="row">
    <?php while ($row = $result->fetch_assoc()) : ?>
      <div class="col-md-4 mb-4">
        <div class="card shadow-sm border-0 rounded-lg p-3 text-center">
          <!-- Tampilkan gambar profil jika ada, jika tidak gunakan placeholder -->
          <?php if (!empty($row['profile_image'])) : ?>
            <img src="../assets/img/influencers/<?php echo $row['profile_image']; ?>" class="rounded-circle mb-3" width="100" height="100" alt="Profile">
          <?php else : ?>
            <div class="profile-placeholder mx-auto mb-3"></div>
          <?php endif; ?>

          <div class="card-body">
            <h5 class="card-title">@<?php echo htmlspecialchars($row['username']); ?></h5>
            <p class="card-text"><strong><?php echo htmlspecialchars($row['full_name']); ?></strong></p>
            <p class="card-text text-muted"><?php echo htmlspecialchars($row['country']); ?></p>
            <p class="card-text">
              <span class="badge bg-secondary">Followers: <?php echo number_format($row['followers']); ?></span>
            </p>
            <div>
              <?php foreach (explode(',', $row['category']) as $tag) : ?>
                <span class="badge bg-primary">#<?php echo htmlspecialchars(trim($tag)); ?></span>
              <?php endforeach; ?>
            </div>
            <a href="detail.php?id=<?php echo $row['id']; ?>" class="btn btn-info mt-3">Lihat Detail</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<style>
  /* Styling untuk membuat placeholder lingkaran */
  .profile-placeholder {
    width: 100px;
    height: 100px;
    background-color: #e0e0e0;
    border-radius: 50%;
  }

  /* Styling untuk card */
  .card {
    transition: transform 0.2s, box-shadow 0.2s;
  }

  .card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.15);
  }
</style>

>>>>>>> 188c392f357ca7fe800f838dc63cf92ef02bd99e
<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
