<?php 
require_once '../layout/_top.php';
require_once '../helper/connection.php';

$endorsement_id = $_GET['endorsement_id'] ?? null;

if ($endorsement_id) {
    $endorsement_query = "SELECT * FROM endorsements WHERE id = '$endorsement_id'";
    $endorsement_result = $connection->query($endorsement_query);

    if ($endorsement_result->num_rows > 0) {
        $endorsement = $endorsement_result->fetch_assoc();
        $influencer_id = $endorsement['influencer_id'];
        $influencer_result = $connection->query("SELECT * FROM influencers WHERE id = '$influencer_id'");

        if ($influencer_result->num_rows > 0) {
            $influencer = $influencer_result->fetch_assoc();
        } else {
            exit("<p>Influencer tidak ditemukan.</p>");
        }

        $rate_card_result = $connection->query("SELECT * FROM rate_cards WHERE username = '{$influencer['username']}'");
        $rate_cards = $rate_card_result->fetch_all(MYSQLI_ASSOC);
        if (count($rate_cards) == 0) {
            exit("<p>Rate card tidak ditemukan.</p>");
        }

    } else {
        exit("<p>ID endorsement tidak ditemukan.</p>");
    }
} else {
    exit("<p>ID endorsement tidak ditemukan.</p>");
}
?>

<!-- Bootstrap & jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<section class="section py-5">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 shadow rounded-4">
          <div class="card-body p-4">

            <!-- Header Influencer -->
            <div class="d-flex align-items-center mb-4">
              <img src="<?= $influencer['profile_image'] ? '../uploads/' . htmlspecialchars($influencer['profile_image']) : 'https://via.placeholder.com/80' ?>" 
                alt="Foto Influencer" class="rounded-circle me-3 border shadow-sm" 
                style="width: 80px; height: 80px; object-fit: cover;">
              <div>
                <h5 class="mb-0"><?= htmlspecialchars($influencer['full_name']) ?></h5>
                <small class="text-muted">@<?= htmlspecialchars($influencer['username']) ?></small>
              </div>
            </div>

            <!-- Rincian Jasa & Harga -->
            <div class="mb-4">
              <div class="d-flex justify-content-between border-bottom pb-2">
                <span class="text-muted">Jasa Dipilih</span>
                <span class="fw-semibold"><?= htmlspecialchars($rate_cards[0]['jasa']) ?></span>
              </div>
              <div class="d-flex justify-content-between mt-2">
                <span class="text-muted">Total Pembayaran</span>
                <span class="text-success fw-bold">Rp <?= number_format($rate_cards[0]['harga'], 0, ',', '.') ?></span>
              </div>
            </div>

            <!-- Informasi Rekening -->
            <div class="bg-light rounded p-3 mb-4">
              <h6 class="fw-bold mb-2">Informasi Rekening</h6>
              <p class="mb-1">Bank: <strong>Bank XYZ</strong></p>
              <p class="mb-0">No. Rekening: <strong><?= htmlspecialchars($influencer['account_number']) ?></strong></p>
            </div>

            <!-- Form Upload -->
            <form id="payment-form" action="process_payment.php" method="POST" enctype="multipart/form-data">
              <div class="mb-3">
                <label for="payment_image" class="form-label fw-medium">Upload Bukti Pembayaran</label>
                <input type="file" name="payment_image" id="payment_image" class="form-control" accept="image/*" required>
              </div>

              <!-- Preview -->
              <div class="mb-3 text-center">
                <img id="preview" src="#" alt="Preview Bukti" class="img-fluid rounded shadow" style="display: none; max-height: 300px;">
              </div>

              <button type="submit" class="btn btn-success w-100 fs-5" id="submit-button">
                Upload Bukti Pembayaran
              </button>
            </form>

          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Toast -->
<div id="payment-toast" class="toast align-items-center text-bg-success border-0 position-fixed top-0 end-0 m-4 z-3" role="alert" aria-live="assertive" aria-atomic="true">
  <div class="d-flex">
    <div class="toast-body">
      Bukti pembayaran berhasil diunggah!
    </div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>
</div>

<script>
  // Gambar preview
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

  // Upload AJAX
  $("#payment-form").on("submit", function (e) {
    e.preventDefault();
    $("#submit-button").attr("disabled", true).text("Mengunggah...");

    let formData = new FormData(this);
    $.ajax({
      url: $(this).attr("action"),
      type: "POST",
      data: formData,
      contentType: false,
      processData: false,
      success: function () {
        $("#submit-button").attr("disabled", false).text("Upload Bukti Pembayaran");
        new bootstrap.Toast(document.getElementById('payment-toast')).show();
        setTimeout(() => window.location.href = "menu_transaksi.php", 3000);
      },
      error: function () {
        alert("Terjadi kesalahan saat upload.");
        $("#submit-button").attr("disabled", false).text("Upload Bukti Pembayaran");
      }
    });
  });
</script>

<?php require_once '../layout/_bottom.php'; $connection->close(); ?>
