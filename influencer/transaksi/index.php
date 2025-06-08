<?php
require_once 'layout/_top.php';
require_once '../helper/connection.php';
require_once '../helper/auth.php';
// Ambil influencer_id dari session atau URL
$influencer_id = $_GET['influencer_id'] ?? null;  // Bisa menggunakan session jika sudah login, atau bisa dari URL

if (!$influencer_id) {
    echo "<p>ID Influencer tidak ditemukan.</p>";
    exit;
}

// Ambil data transaksi yang dilakukan oleh influencer ini
$query = "SELECT t.id, t.service, t.price, t.payment_image, t.status, t.created_at, i.full_name, i.username
          FROM transactions t
          JOIN influencers i ON t.influencer_id = i.id
          WHERE t.influencer_id = '$influencer_id'
          ORDER BY t.created_at DESC"; // Mengambil transaksi influencer terbaru

$result = $connection->query($query);
?>

<section class="section">
  <div class="section-header">
    <h1>Menu Transaksi Influencer</h1>
  </div>

  <div class="section-body">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="mb-3 text-center">Daftar Transaksi Anda</h5>

            <!-- Tabel Daftar Transaksi -->
            <table class="table table-striped table-bordered">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Jasa Dipilih</th>
                  <th>Total Pembayaran</th>
                  <th>Status</th>
                  <th>Tanggal Transaksi</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php 
                if ($result->num_rows > 0) {
                  $counter = 1;
                  while ($transaction = $result->fetch_assoc()) {
                    $status_label = '';
                    switch ($transaction['status']) {
                      case 'Pending':
                        $status_label = '<span class="badge bg-warning">Pending</span>';
                        break;
                      case 'Completed':
                        $status_label = '<span class="badge bg-success">Completed</span>';
                        break;
                      case 'Failed':
                        $status_label = '<span class="badge bg-danger">Failed</span>';
                        break;
                    }
                    echo "<tr>
                            <td>{$counter}</td>
                            <td>{$transaction['service']}</td>
                            <td>Rp " . number_format($transaction['price'], 0, ',', '.') . "</td>
                            <td>{$status_label}</td>
                            <td>" . date('d M Y H:i', strtotime($transaction['created_at'])) . "</td>
                            <td>
                              <a href='influencer_view_transaction.php?transaction_id={$transaction['id']}' class='btn btn-info btn-sm'>Detail</a>
                            </td>
                          </tr>";
                    $counter++;
                  }
                } else {
                  echo "<tr><td colspan='6' class='text-center'>Tidak ada transaksi ditemukan.</td></tr>";
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
require_once '../layout/_bottom.php';
$connection->close();
?>
