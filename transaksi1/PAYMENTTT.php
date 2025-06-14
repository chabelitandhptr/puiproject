<?php    
require_once '../layout/_top.php';
require_once '../helper/connection.php'; // Koneksi ke database
require_once '../vendor/autoload.php'; // Pastikan path ke autoload sesuai dengan lokasi kamu

// Ambil ID endorsement dari URL
$endorsement_id = $_GET['endorsement_id'] ?? null;
$selected_service = $_GET['service'] ?? ''; // Mengambil service yang dipilih dari URL

$initial_total = 0; // Inisialisasi total pembayaran

if ($endorsement_id) {
    // Ambil data endorsement berdasarkan ID
    $endorsement = $connection->query("SELECT * FROM endorsements WHERE id = '$endorsement_id'")->fetch_assoc();
    $influencer = $connection->query("SELECT * FROM influencers WHERE id = '{$endorsement['influencer_id']}'")->fetch_assoc();
    
    // Ambil jasa yang dipilih oleh pengguna dari table endorsement
    $services = explode(', ', $endorsement['service']);
    $rate_cards = [];
    
    foreach ($services as $service) {
        $rate_card_query = "SELECT * FROM rate_cards WHERE jasa = '$service' LIMIT 1";
        $rate_card_result = $connection->query($rate_card_query);
        if ($rate_card_result->num_rows > 0) {
            $rate_cards[] = $rate_card_result->fetch_assoc();
        }
    }

    // Cari harga jasa yang dipilih di rate_cards
    if ($selected_service) {
        foreach ($rate_cards as $rate_card) {
            if ($rate_card['jasa'] == $selected_service) {
                $initial_total = $rate_card['harga']; // Menetapkan harga jasa yang dipilih
            }
        }
    }
} else {
    echo "<p>ID endorsement tidak ditemukan.</p>";
    exit;
}

// Get all available services (rate cards)
$all_rate_cards = $connection->query("SELECT * FROM rate_cards WHERE username = '{$influencer['username']}'")->fetch_all(MYSQLI_ASSOC);
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

              <!-- Influencer Info -->
              <div class="d-flex align-items-center mb-4">
                <img src="<?= !empty($influencer['profile_image']) ? '../uploads/' . htmlspecialchars($influencer['profile_image']) : 'https://via.placeholder.com/80' ?>" 
                     alt="Foto Influencer" class="rounded-circle me-3" style="width: 80px; height: 80px; object-fit: cover;">
                <div>
                  <h5 class="mb-0"><?= htmlspecialchars($influencer['full_name']) ?></h5>
                  <small class="text-muted">@<?= htmlspecialchars($influencer['username']) ?></small>
                </div>
              </div>

              <!-- Selected Services -->
              <div class="mb-4 fs-5">
                <h6 class="fw-bold">Jasa yang Dipilih:</h6>
                <ul id="selected-services-list">
                  <?php 
                    // Tampilkan jasa yang dipilih
                    if ($selected_service) {
                        echo "<li>$selected_service</li>";
                    } else {
                        foreach ($rate_cards as $rate_card) {
                            echo "<li>" . htmlspecialchars($rate_card['jasa']) . " - Rp " . number_format($rate_card['harga'], 0, ',', '.') . "</li>";
                        }
                    }
                  ?>
                </ul>
              </div>

              <!-- Total Payment -->
              <div class="d-flex justify-content-between mt-2">
                <span><strong>Total Pembayaran:</strong></span>
                <strong class="text-success" id="total-payment">
                  Rp <?= number_format($initial_total, 0, ',', '.') ?>
                </strong>
              </div>

              <hr>

              <!-- Checkout Button -->
              <div class="mt-4">
                <button type="button" id="checkout-btn" class="btn btn-success w-100 fs-5" disabled>Checkout</button>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Fungsi untuk memeriksa apakah tombol checkout bisa diklik
  function enableCheckoutButton() {
    const totalPayment = $('#total-payment').text().replace('Rp ', '').replace(/\./g, '').replace(',', '');
    if (totalPayment > 0) {
        $('#checkout-btn').prop('disabled', false);  // Enable checkout button
    }
  }

  // Ketika tombol checkout diklik, langsung memproses pembayaran menggunakan Snap Token
  $('#checkout-btn').on('click', function() {
    const totalPayment = $('#total-payment').text().replace('Rp ', '').replace(/\./g, '').replace(',', '');

    // Langsung bayar tanpa menunggu klik tombol (mode pop-up)
    snap.pay('<?php echo $snapToken; ?>', {
        onSuccess: function(result){
            document.getElementById('result-json').innerHTML = 'Pembayaran berhasil! ' + JSON.stringify(result, null, 2);
        },
        onPending: function(result){
            document.getElementById('result-json').innerHTML = 'Pembayaran menunggu konfirmasi! ' + JSON.stringify(result, null, 2);
        },
        onError: function(result){
            document.getElementById('result-json').innerHTML = 'Terjadi kesalahan! ' + JSON.stringify(result, null, 2);
        }
    });
  });

  // Memanggil fungsi enableCheckoutButton untuk memeriksa total pembayaran
  enableCheckoutButton();
</script>
<?php require_once '../layout/_bottom.php'; ?>
