<?php 
require_once '../layout/_top.php';
require_once '../helper/connection.php'; // Koneksi ke database

// Ambil ID endorsement dari URL
$endorsement_id = $_GET['endorsement_id'] ?? null;

if ($endorsement_id) {
    // Ambil data endorsement berdasarkan ID
    $endorsement = $connection->query("SELECT * FROM endorsements WHERE id = '$endorsement_id'")->fetch_assoc();
    $influencer = $connection->query("SELECT * FROM influencers WHERE id = '{$endorsement['influencer_id']}'")->fetch_assoc();
    $rate_card = $connection->query("SELECT * FROM rate_cards WHERE username = '{$influencer['username']}' LIMIT 1")->fetch_assoc();
} else {
    echo "<p>ID endorsement tidak ditemukan.</p>"; exit;
}

// Proses upload pembayaran jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $payment_image = $_FILES['payment_image']['name'];
    $target_dir = __DIR__ . '/../uploads/payment_receipts/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Tentukan lokasi file upload
    $target_file = $target_dir . time() . "_" . basename($payment_image);
    
    if (move_uploaded_file($_FILES['payment_image']['tmp_name'], $target_file)) {
        // Simpan transaksi ke dalam database
        $query = "INSERT INTO transactions (endorsement_id, influencer_id, service, price, payment_image, account_number, transaction_date, status, created_at, updated_at)
                  VALUES ('$endorsement_id', '{$influencer['id']}', '{$rate_card['jasa']}', '{$rate_card['harga']}', '$payment_image', '{$influencer['account_number']}', NOW(), 'Pending', NOW(), NOW())";

        if (mysqli_query($connection, $query)) {
            // Redirect atau kirim respons sukses
            echo "<script>
                var toast = new bootstrap.Toast(document.getElementById('payment-toast'));
                toast.show();
                setTimeout(function() {
                    window.location.href = '../dashboard/index.php';  // Redirect ke dashboard setelah 2 detik
                }, 2000); // 2000ms = 2 detik
            </script>";
            exit();
        } else {
            echo "Error saat menyimpan data transaksi: " . mysqli_error($connection);
        }
    } else {
        echo "<p>Gagal mengunggah bukti pembayaran.</p>";
    }
}

?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<section class="section">
  <div class="section-header">
    <h1 class="text-center">Informasi Pembayaran</h1>
  </div>
  <div class="section-body">
    <div class="container py-4">
      <div class="row justify-content-center">
        <div class="col-lg-7">
          <div class="card border-0 shadow rounded-4">
            <div class="card-body p-4">

              <div class="d-flex align-items-center mb-4">
                <img src="<?= !empty($influencer['profile_image']) ? '../uploads/' . htmlspecialchars($influencer['profile_image']) : 'https://via.placeholder.com/80' ?>" 
                     alt="Foto Influencer" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                <div>
                  <h5 class="mb-0"><?= htmlspecialchars($influencer['full_name']) ?></h5>
                  <small class="text-muted">@<?= htmlspecialchars($influencer['username']) ?></small>
                </div>
              </div>

              <div class="mb-4 fs-5">
                <div class="d-flex justify-content-between">
                  <span>Jasa Dipilih</span>
                  <strong><?= htmlspecialchars($rate_card['jasa']) ?></strong>
                </div>
                <div class="d-flex justify-content-between mt-2">
                  <span>Total Pembayaran</span>
                  <strong class="text-success">Rp <?= number_format($rate_card['harga'], 0, ',', '.') ?></strong>
                </div>
              </div>

              <hr>

              <div class="mb-4 fs-6">
                <h6 class="fw-bold">Informasi Rekening</h6>
                <p class="mb-1">Bank: <strong>Bank XYZ</strong></p>
                <p>No. Rekening: <strong><?= htmlspecialchars($influencer['account_number']) ?></strong></p>
              </div>

              <!-- Form untuk upload bukti pembayaran -->
              <form id="payment-form" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="endorsement_id" value="<?= $endorsement_id ?>">
                <input type="hidden" name="influencer_id" value="<?= $influencer['id'] ?>">
                <input type="hidden" name="service" value="<?= $rate_card['jasa'] ?>">
                <input type="hidden" name="price" value="<?= $rate_card['harga'] ?>">
                <input type="hidden" name="account_number" value="<?= $influencer['account_number'] ?>">

                <div class="mb-3">
                  <label for="payment_image" class="form-label fw-medium">Upload Bukti Pembayaran</label>
                  <input type="file" name="payment_image" id="payment_image" class="form-control" accept="image/*" required>
                </div>

                <div class="mb-3 text-center">
                  <img id="preview" src="#" alt="Preview Bukti" style="max-width: 100%; max-height: 300px; display: none; border-radius: 8px;" />
                </div>

                <button type="submit" class="btn btn-primary w-100 fs-5" id="submit-button">Upload Bukti Pembayaran</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Notifikasi Toast -->
<div id="payment-toast" class="toast align-items-center text-bg-success border-0 position-fixed top-50 start-50 translate-middle m-3" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
      Pembayaran berhasil! Silakan cek status Anda di menu transaksi.
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>


<script>
  // Preview gambar sebelum diupload
  $("#payment_image").change(function () {
    const file = this.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        $("#preview").attr("src", e.target.result).fadeIn();
      };
      reader.readAsDataURL(file);
    }
  });

  // Submit form menggunakan AJAX
  $("#payment-form").on("submit", function (e) {
    e.preventDefault();
    $("#submit-button").attr("disabled", true).text("Mengunggah...");

    var formData = new FormData(this);
    $.ajax({
      url: $(this).attr("action"),
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function(response) {
        $("#submit-button").attr("disabled", false).text("Upload Bukti Pembayaran");
        var toast = new bootstrap.Toast(document.getElementById('payment-toast'));
        toast.show();
        setTimeout(() => window.location.href = "../dashboard/index.php", 2000);  // Redirect ke dashboard setelah 2 detik
      },
      error: function() {
        alert("Terjadi kesalahan saat upload.");
        $("#submit-button").attr("disabled", false).text("Upload Bukti Pembayaran");
      }
    });
  });
</script>

<?php require_once '../layout/_bottom.php'; ?>
