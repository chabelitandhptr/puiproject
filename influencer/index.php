<?php
require_once '../influencer/layout/_top.php';
?>

<section class="section">
  <div class="section-header">
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

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
