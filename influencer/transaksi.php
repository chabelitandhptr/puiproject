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

// Proses update jika tombol "Mark as Completed" diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_transaction_id'])) {
    $transaction_id = $_POST['complete_transaction_id'];
    
    // Periksa apakah 'link_post' ada di $_POST
    $link_post = isset($_POST['link_post']) ? $_POST['link_post'] : '';

    // Periksa apakah 'upload_image' ada di $_FILES
    $proof_image = isset($_FILES['upload_image']) ? $_FILES['upload_image'] : null;

    // Menangani upload file gambar bukti jika ada
    if ($proof_image && $proof_image['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'uploads/';  // Folder upload file
        $file_name = basename($proof_image['name']);
        $target_file = $upload_dir . time() . "_" . $file_name; // Menambahkan timestamp untuk nama file

        // Membuat folder jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Memindahkan file yang diunggah ke folder tujuan
        if (move_uploaded_file($proof_image['tmp_name'], $target_file)) {
            // Update status transaksi menjadi "Completed" dan simpan link serta bukti foto
            $update_query = "
                UPDATE transactions 
                SET status = 'Completed', link_post = '$link_post', proof_image = '$target_file' 
                WHERE id = '$transaction_id' AND influencer_id = '$influencer_id'
            ";
            $connection->query($update_query);
            header("Location: " . $_SERVER['PHP_SELF']); // Refresh halaman setelah update
            exit;
        } else {
            echo "<p>Gagal mengunggah bukti foto postingan.</p>";
        }
    } else {
        echo "<p>Gagal mengunggah foto. Pastikan file gambar sudah dipilih.</p>";
    }
}

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
                              <a href='javascript:void(0)' class='text-decoration-none open-popup' data-transaction-id='{$transaction['id']}' data-link-post='{$transaction['link_post']}' data-proof-image='{$transaction['proof_image']}'>{$transaction['service']}</a>
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

<!-- Popup Form -->
<div id="popupForm" class="popup-form">
  <div class="popup-content">
    <span class="close-popup">&times;</span>
    <h5>Upload Link dan Bukti Foto Postingan</h5>
    <form method="POST" enctype="multipart/form-data">
      <input type="hidden" id="transaction_id" name="complete_transaction_id">
      <div class="mb-3">
        <label for="link_post" class="form-label">Link Postingan</label>
        <input type="text" class="form-control" id="link_post" name="link_post" required>
      </div>
      <div class="mb-3">
        <label for="upload_image" class="form-label">Upload Foto Postingan</label>
        <input type="file" class="form-control" id="upload_image" name="upload_image" accept="image/*" required>
        <div id="imagePreview" class="mt-2" style="display:none;">
          <img id="previewImg" src="" alt="Image Preview" style="width: 100%; max-width: 200px;"/>
        </div>
      </div>
      <button type="submit" class="btn btn-success">Upload dan Selesaikan</button>
    </form>
  </div>
</div>

<?php
require_once 'layout/_bottom.php'; // Footer layout
$connection->close();
?>

<!-- CSS for Popup -->
<style>
  .popup-form {
    display: none;
    position: fixed;
    z-index: 1;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
  }

  .popup-content {
    position: relative;
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    width: 80%;
    max-width: 500px;
    border-radius: 5px;
  }

  .close-popup {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 30px;
    font-weight: bold;
    cursor: pointer;
  }
</style>

<!-- JavaScript for handling Popup and Image Preview -->
<script>
  // Open the popup when clicking on the transaction service
  document.querySelectorAll('.open-popup').forEach(item => {
    item.addEventListener('click', event => {
      const transactionId = item.getAttribute('data-transaction-id');
      document.getElementById('transaction_id').value = transactionId;
      document.getElementById('popupForm').style.display = 'block';
    });
  });

  // Close the popup when clicking on the close button
  document.querySelector('.close-popup').addEventListener('click', () => {
    document.getElementById('popupForm').style.display = 'none';
  });

  // Close the popup when clicking outside of the popup content
  window.onclick = function(event) {
    if (event.target === document.getElementById('popupForm')) {
      document.getElementById('popupForm').style.display = 'none';
    }
  }

  // Image preview when selecting a file
  document.getElementById('upload_image').addEventListener('change', function(event) {
    const file = event.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function(e) {
        document.getElementById('imagePreview').style.display = 'block';
        document.getElementById('previewImg').src = e.target.result;
      }
      reader.readAsDataURL(file);
    }
  });
</script>
