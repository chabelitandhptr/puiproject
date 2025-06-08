<?php 
require_once '../layout/_top.php';
require_once '../helper/connection.php'; // Koneksi ke database

// Ambil data transaksi yang sudah disimpan
$query = "SELECT t.id, t.endorsement_id, t.service, t.price, t.payment_image, t.status, t.created_at, i.full_name, i.username 
          FROM transactions t 
          JOIN influencers i ON t.influencer_id = i.id
          ORDER BY t.created_at DESC"; // Ambil transaksi terakhir

$result = $connection->query($query);
?>

<section class="section">
  <div class="section-header">
    <h1 class="text-center">Daftar Transaksi</h1>
  </div>
  <div class="section-body">
    <div class="container py-4">
      <div class="row">
        <div class="col-lg-12">
          <div class="card border-0 shadow rounded-4">
            <div class="card-body p-4">


              <!-- Tabel Daftar Transaksi -->
              <table class="table table-striped table-bordered">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Influencer</th>
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
                              <td><strong>{$transaction['full_name']}</strong><br>@{$transaction['username']}</td>
                              <td>{$transaction['service']}</td>
                              <td>Rp " . number_format($transaction['price'], 0, ',', '.') . "</td>
                              <td>{$status_label}</td>
                              <td>" . date('d M Y H:i', strtotime($transaction['created_at'])) . "</td>
                              <td>
                                <a href='view_transaction.php?transaction_id={$transaction['id']}' class='btn btn-info btn-sm'>Detail</a>
                              </td>
                            </tr>";
                      $counter++;
                    }
                  } else {
                    echo "<tr><td colspan='7' class='text-center'>Tidak ada transaksi ditemukan.</td></tr>";
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require_once '../layout/_bottom.php'; ?>
