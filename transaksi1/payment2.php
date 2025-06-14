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
    $rate_cards = $connection->query("SELECT * FROM rate_cards WHERE username = '{$influencer['username']}'")->fetch_all(MYSQLI_ASSOC);
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

// Proses form jika tombol checkout diklik
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form
    $quantities = $_POST['quantity']; // Mengecek jumlah yang dipilih untuk setiap jasa
    $total_payment = 0; // Total pembayaran sementara
    
    // Menghitung total pembayaran berdasarkan jumlah yang dipilih
    foreach ($quantities as $key => $quantity) {
        // Mencari harga dari jasa yang dipilih
        $rate_card = $connection->query("SELECT * FROM rate_cards WHERE id = '$key' LIMIT 1")->fetch_assoc();
        $total_payment += $rate_card['harga'] * $quantity; // Menghitung total pembayaran
    }

    // Setel detail transaksi
    $transaction_details = array(
        'order_id' => $order_id,
        'gross_amount' => $total_payment, // Total pembayaran
    );

    // Setel data pelanggan (Anda bisa mengganti sesuai data pengguna)
    $customer_name = "Nama Pelanggan"; // Ganti dengan nama pelanggan
    $customer_email = "email@domain.com"; // Ganti dengan email pelanggan
    $customer_phone = "081234567890"; // Ganti dengan nomor telepon pelanggan

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

    // Menyimpan transaksi ke database (Anda bisa menambahkan data lain yang dibutuhkan)
    $query = "INSERT INTO transactions (endorsement_id, influencer_id, service, price, payment_image, account_number, transaction_date, status, created_at, updated_at)
              VALUES ('$endorsement_id', '{$influencer['id']}', '".json_encode($quantities)."', '$total_payment', NULL, '{$influencer['account_number']}', NOW(), 'Pending', NOW(), NOW())";

    if (mysqli_query($connection, $query)) {
        // Redirect langsung ke Midtrans untuk pembayaran
        echo "<script>
            snap.pay('$snapToken', {
                onSuccess: function(result){
                    // Pembayaran berhasil, update status transaksi di database
                    window.location.href = 'payment_success.php?transaction_id={$order_id}';
                },
                onPending: function(result){
                    // Pembayaran menunggu konfirmasi
                    alert('Pembayaran menunggu konfirmasi!');
                },
                onError: function(result){
                    // Pembayaran gagal
                    alert('Terjadi kesalahan dalam pembayaran!');
                }
            });
        </script>";
        exit();
    } else {
        echo "Error saat menyimpan data transaksi: " . mysqli_error($connection);
    }
}

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

              <!-- Menampilkan semua jasa yang tersedia dan input jumlah -->
              <div class="mb-4">
                <h6 class="fw-bold">Pilih Jasa</h6>
                <form method="POST">
                  <ul id="service-list" class="list-group">
                    <?php 
                    $total_payment = 0; // Menyimpan total pembayaran
                    foreach ($rate_cards as $rate_card):
                    ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                          <span class="service-name"><?= htmlspecialchars($rate_card['jasa']) ?></span> 
                          <span>Rp <?= number_format($rate_card['harga'], 0, ',', '.') ?></span>
                        </div>
                        <div class="d-flex">
                          <button type="button" class="btn btn-secondary btn-sm" onclick="updateQuantity('<?= $rate_card['id'] ?>', -1)">-</button>
                          <input type="number" name="quantity[<?= $rate_card['id'] ?>]" value="0" min="0" class="quantity-input form-control w-25 mx-2" readonly />
                          <button type="button" class="btn btn-secondary btn-sm" onclick="updateQuantity('<?= $rate_card['id'] ?>', 1)">+</button>
                        </div>
                      </li>
                    <?php endforeach; ?>
                  </ul>
                  <div class="d-flex justify-content-between mt-2">
                    <span><strong>Total Pembayaran:</strong></span>
                    <strong class="text-success" id="total-payment">
                      Rp <?= number_format($total_payment, 0, ',', '.') ?>
                    </strong>
                  </div>

                  <!-- Checkout Button -->
                  <div class="mt-4">
                    <button type="submit" class="btn btn-success w-100 fs-5">Checkout</button>
                  </div>
                </form>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Fungsi untuk mengupdate total berdasarkan jumlah yang dipilih
  function updateQuantity(serviceId, change) {
    const input = $("input[name='quantity[" + serviceId + "]']");
    let currentQuantity = parseInt(input.val());
    if (currentQuantity + change >= 0) {
      input.val(currentQuantity + change);
      updateTotal();
    }
  }

  // Fungsi untuk mengupdate total pembayaran
  function updateTotal() {
    let total = 0;
    $('input.quantity-input').each(function() {
      const price = $(this).closest('li').find('span').eq(1).text().replace('Rp ', '').replace(/\./g, '');
      const quantity = $(this).val();
      total += price * quantity;
    });
    $('#total-payment').text('Rp ' + total.toLocaleString());
  }

  // Event listener untuk perubahan jumlah jasa
  $(document).on('input', '.quantity-input', function() {
    updateTotal();
  });
</script>

<?php require_once '../layout/_bottom.php'; ?>
