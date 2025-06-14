<?php 
require_once '../layout/_top.php';
require_once '../helper/connection.php'; // Koneksi ke database
require_once '../vendor/autoload.php'; // Pastikan path ke autoload sesuai dengan lokasi kamu

// Ambil ID endorsement dari URL
$endorsement_id = $_GET['endorsement_id'] ?? null;

if ($endorsement_id) {
    // Ambil data endorsement berdasarkan ID
    $endorsement = $connection->query("SELECT * FROM endorsements WHERE id = '$endorsement_id'")->fetch_assoc();
    $influencer = $connection->query("SELECT * FROM influencers WHERE id = '{$endorsement['influencer_id']}'")->fetch_assoc();
    $rate_card = $connection->query("SELECT * FROM rate_cards WHERE username = '{$influencer['username']}' LIMIT 1")->fetch_assoc();
} else {
    echo "<p>ID endorsement tidak ditemukan.</p>";
    exit;
}

// Setel Kunci Server Midtrans
\Midtrans\Config::$serverKey = 'SB-Mid-server-bCRQAP2WyTnwCHw7Yly_MhvD'; // Ganti dengan Server Key Anda
\Midtrans\Config::$isProduction = false; // Setel ke true untuk lingkungan produksi
\Midtrans\Config::$isSanitized = true; // Mengaktifkan sanitasi
\Midtrans\Config::$is3ds = true; // Mengaktifkan 3D Secure untuk transaksi kartu kredit

// Generate order ID unik
$order_id = rand();

// Setel detail transaksi
$transaction_details = array(
    'order_id' => $order_id,
    'gross_amount' => (int)$rate_card['harga'], // Total pembayaran dalam bentuk angka tanpa desimal
);

// Setel data pelanggan
$customer_name = "Nama Pelanggan"; // Ganti sesuai dengan data pelanggan
$customer_email = "email@domain.com"; // Ganti sesuai dengan email pelanggan
$customer_phone = "081234567890"; // Ganti sesuai dengan nomor telepon pelanggan

// Setel data pelanggan
$customer_details = array(
    'first_name' => $customer_name,
    'email' => $customer_email,
    'phone' => $customer_phone,
);

// Setel parameter transaksi
$params = array(
    'transaction_details' => $transaction_details,
    'customer_details' => $customer_details,
);

// Dapatkan Snap Token dari Midtrans
$snapToken = \Midtrans\Snap::getSnapToken($params);
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="YOUR_CLIENT_KEY"></script>

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

              <!-- Checkout Button -->
              <div class="mt-4">
                <button type="button" id="checkout-btn" class="btn btn-success w-100 fs-5">Checkout</button>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Ketika tombol checkout diklik, langsung memproses pembayaran menggunakan Snap Token
  $('#checkout-btn').on('click', function() {
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
</script>

<?php require_once '../layout/_bottom.php'; ?>
