<?php 
require_once '../layout/_top.php';
require_once '../helper/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data pesan
    $message = $_POST['message'];
    $influencer_id = $_POST['influencer_id'];
    $umkm_id = $_SESSION['umkm_id']; // Asumsikan UMKM sudah login dan ID-nya disimpan di session

    // Validasi pesan
    if (!empty($message)) {
        // Simpan pesan ke database
        $sql = "INSERT INTO messages (umkm_id, influencer_id, message, sent_at) 
                VALUES ('$umkm_id', '$influencer_id', '$message', NOW())";
        
        if ($connection->query($sql) === TRUE) {
            $success_message = "Pesan berhasil dikirim!";
        } else {
            $error_message = "Terjadi kesalahan: " . $connection->error;
        }
    } else {
        $error_message = "Pesan tidak boleh kosong!";
    }
}

// Ambil data influencer berdasarkan influencer_id
$influencer_id = isset($_GET['influencer_id']) ? $_GET['influencer_id'] : 0;
$influencer_sql = "SELECT * FROM influencers WHERE id = '$influencer_id'";
$influencer_result = $connection->query($influencer_sql);
$influencer = $influencer_result->fetch_assoc();
?>

<section class="section">
  <div class="section-body">
    <div class="row mb-4">
      <div class="col-md-6">
        <h4>Kirim Pesan ke <?= htmlspecialchars($influencer['full_name']) ?></h4>
      </div>
    </div>

    <div class="row">
      <div class="col-md-8">
        <?php if (isset($success_message)): ?>
          <div class="alert alert-success"><?= $success_message ?></div>
        <?php elseif (isset($error_message)): ?>
          <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>

        <form method="POST" action="send_message.php">
          <div class="form-group">
            <label for="message">Pesan</label>
            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
          </div>
          <input type="hidden" name="influencer_id" value="<?= $influencer['id'] ?>">
          <button type="submit" class="btn btn-primary">Kirim Pesan</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
