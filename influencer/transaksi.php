<?php
require_once 'layout/_top.php';
require_once '../helper/connection.php'; // Koneksi ke database
require_once '../helper/auth.php'; // Untuk autentikasi pengguna (memastikan login)

$username = $_SESSION['login']['username'] ?? null;  // Menggunakan username dari session

if (!$username) {
    echo "Username tidak ditemukan di session!";
    exit;
}

// Ambil data influencer berdasarkan username
$query_influencer = "SELECT id, full_name, username FROM influencers WHERE username = '$username'";
$influencer_result = $connection->query($query_influencer);
$influencer = $influencer_result->fetch_assoc();

if (!$influencer) {
    echo "Influencer tidak ditemukan!";
    exit;
}

// Ambil transaksi influencer berdasarkan influencer_id
$influencer_id = $influencer['id'];  // ID influencer yang sudah terautentikasi

// Ambil transaksi dengan status Pending (Pesanan Baru)
$query_pending_transactions = "
    SELECT t.id, t.service, t.price, t.payment_image, t.status, t.created_at 
    FROM transactions t
    WHERE t.influencer_id = '$influencer_id' AND t.status = 'Pending'
    ORDER BY t.created_at DESC
";

// Ambil transaksi dengan status Completed (Pesanan Selesai)
$query_completed_transactions = "
    SELECT t.id, t.service, t.price, t.payment_image, t.status, t.created_at 
    FROM transactions t
    WHERE t.influencer_id = '$influencer_id' AND t.status = 'Completed'
    ORDER BY t.created_at DESC
";

// Query untuk menjalankan pengambilan data
$pending_result = $connection->query($query_pending_transactions);
$completed_result = $connection->query($query_completed_transactions);
?>

<section class="section">
  <div class="section-header">
    <h1>Menu Transaksi Influencer</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <!-- Kolom untuk Pesanan Baru Masuk dan Pesanan Selesai -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3 text-center">Pesanan Baru Masuk</h5>

            <!-- Tabel Pesanan Baru Masuk -->
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Jasa Dipilih</th>
                  <th>Total Pembayaran</th>
                  <th>Status</th>
                  <th>Tanggal Transaksi</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if ($pending_result->num_rows > 0) {
                  $counter = 1;
                  while ($transaction = $pending_result->fetch_assoc()) {
                    $status_label = '<span class="badge bg-warning">Pending</span>';
                    echo "<tr>
                            <td>{$counter}</td>
                            <td>
                              <a href='influencer_view_transaction.php?transaction_id={$transaction['id']}' class='text-decoration-none'>{$transaction['service']}</a>
                            </td>
                            <td>Rp " . number_format($transaction['price'], 0, ',', '.') . "</td>
                            <td>{$status_label}</td>
                            <td>" . date('d M Y H:i', strtotime($transaction['created_at'])) . "</td>
                          </tr>";
                    $counter++;
                  }
                } else {
                  echo "<tr><td colspan='5' class='text-center'>Tidak ada pesanan baru ditemukan.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- Kolom untuk Pesanan Selesai -->
      <div class="col-md-6">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3 text-center">Pesanan Selesai</h5>

            <!-- Tabel Pesanan Selesai -->
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Jasa Dipilih</th>
                  <th>Total Pembayaran</th>
                  <th>Status</th>
                  <th>Tanggal Transaksi</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if ($completed_result->num_rows > 0) {
                  $counter = 1;
                  while ($transaction = $completed_result->fetch_assoc()) {
                    $status_label = '<span class="badge bg-success">Completed</span>';
                    echo "<tr>
                            <td>{$counter}</td>
                            <td>
                              <a href='influencer_view_transaction.php?transaction_id={$transaction['id']}' class='text-decoration-none'>{$transaction['service']}</a>
                            </td>
                            <td>Rp " . number_format($transaction['price'], 0, ',', '.') . "</td>
                            <td>{$status_label}</td>
                            <td>" . date('d M Y H:i', strtotime($transaction['created_at'])) . "</td>
                          </tr>";
                    $counter++;
                  }
                } else {
                  echo "<tr><td colspan='5' class='text-center'>Tidak ada pesanan selesai ditemukan.</td></tr>";
                }
                ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
require_once 'layout/_bottom.php'; // Footer layout
$connection->close();
?>
