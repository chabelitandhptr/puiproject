<?php 
require_once '../helper/connection.php'; 

 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $promoted_item = mysqli_real_escape_string($connection, $_POST['promoted_item']);
    $details = mysqli_real_escape_string($connection, $_POST['details']);
    $tasks = mysqli_real_escape_string($connection, $_POST['tasks']);
    $requirements = mysqli_real_escape_string($connection, $_POST['requirements']);
    $service = mysqli_real_escape_string($connection, $_POST['service']);
    $free_product = mysqli_real_escape_string($connection, $_POST['free_product']);
    $contact_permission = isset($_POST['contact_permission']) ? 1 : 0;

    $target_dir = __DIR__ . '/../uplouds/';
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $allowed_types = ['jpg', 'jpeg', 'png'];

        if (!in_array($imageFileType, $allowed_types)) {
            die("Error: Hanya file JPG, JPEG, dan PNG yang diperbolehkan.");
        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "Error: Gagal mengunggah gambar.";
            exit;
        }
    }

    $influencer_id = isset($_GET['influencer_id']) ? $_GET['influencer_id'] : null;

    if ($influencer_id) {
        $query = "INSERT INTO endorsements 
            (influencer_id, promoted_item, details, tasks, requirements, service, free_product, contact_permission, image, created_at)
            VALUES 
            ('$influencer_id', '$promoted_item', '$details', '$tasks', '$requirements', '$service', '$free_product', '$contact_permission', '$image_name', NOW())";

        if (mysqli_query($connection, $query)) {
            $endorsement_id = mysqli_insert_id($connection);
            header("Location: payment_info.php?endorsement_id={$endorsement_id}");
            exit();
        } else {
            echo "Error saat menyimpan data: " . mysqli_error($connection);
        }
    } else {
        echo "<p>ID Influencer tidak ditemukan.</p>";
        exit;
    }
}


require_once '../layout/_top.php';


$influencer_id = isset($_GET['influencer_id']) ? $_GET['influencer_id'] : null;

if ($influencer_id) {
    $influencer_query = "SELECT * FROM influencers WHERE id = '$influencer_id'";
    $influencer_result = $connection->query($influencer_query);

    if ($influencer_result->num_rows > 0) {
        $influencer = $influencer_result->fetch_assoc();
    } else {
        echo "<p>Influencer tidak ditemukan.</p>";
        exit;
    }

    $rate_card_query = "SELECT * FROM rate_cards WHERE username = '{$influencer['username']}'";
    $rate_card_result = $connection->query($rate_card_query);
    $rate_cards = [];

    if ($rate_card_result->num_rows > 0) {
        while ($rate_card = $rate_card_result->fetch_assoc()) {
            $rate_cards[] = $rate_card;
        }
    }
} else {
    echo "<p>ID Influencer tidak ditemukan.</p>";
    exit;
}
?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  tinymce.init({
    selector: 'textarea',
    menubar: false,
    plugins: 'autoresize',
    toolbar: 'undo redo | styleselect | bold italic | link | alignleft aligncenter alignright | bullist numlist outdent indent | removeformat',
    autoresize_bottom_margin: 10,
    min_height: 100,
    max_height: 300
  });

  function updatePaymentInfo() {
    const selectedOption = document.getElementById('service').selectedOptions[0];
    const service = selectedOption ? selectedOption.value : '';
    const price = selectedOption ? selectedOption.getAttribute('data-price') : '';
    document.getElementById('selected-service').innerText = service;
    document.getElementById('total-payment').innerText = 'Rp ' + price;
  }

  document.getElementById('service').addEventListener('change', updatePaymentInfo);

  function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('image-preview');

    if (input.files && input.files[0]) {
      const reader = new FileReader();

      reader.onload = function (e) {
        preview.src = e.target.result;
        preview.style.display = 'block';
      };

      reader.readAsDataURL(input.files[0]);
    }
  }
</script>

<style>
  .tox-tinymce {
    min-height: 100px !important;
    max-height: 300px !important;
  }

  textarea.form-control {
  border: 1px solid #dce0e5;
  border-radius: 8px;
  padding: 12px 14px;
  font-size: 14px;
  min-height: 120px;
  resize: vertical;
  box-shadow: none;
  transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

textarea.form-control:focus {
  border-color: #4a90e2;
  outline: none;
  box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
}

#loading-spinner {
    margin-top: 15px;
    font-size: 16px;
    color: #28a745;
  }
</style>

<section class="section">
  <div class="section-header">
    <h1>Form Endorsment</h1>
  </div>
  <div class="section-body">
    <div class="container">
      <form method="POST" action="" enctype="multipart/form-data">
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-4">
              <div class="card-header bg-primary text-white">Informasi Promosi</div>
              <div class="card-body">
                <div class="form-group">
                  <label>1. Apa yang ingin dipromosikan?</label>
                  <textarea class="form-control" name="promoted_item" placeholder="Sebutkan nama produk / layanan / brand / campaign"></textarea>
                </div>
                <div class="form-group">
                  <label>2. Jelaskan lebih detail mengenai</label>
                  <textarea class="form-control" name="details" placeholder="Jelaskan lebih lanjut tentang produk atau layanan yang dipromosikan"></textarea>
                </div>
                <div class="form-group">
                  <label>3. Apa yang perlu dilakukan influencer?</label>
                  <textarea class="form-control" name="tasks" placeholder="Contoh: Posting foto, mention akun, gunakan hashtag"></textarea>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-4">
              <div class="card-header bg-info text-white">Detail Tambahan</div>
              <div class="card-body">
                <div class="form-group">
                  <label>4. Syarat dan ketentuan lain (jika ada)</label>
                  <textarea class="form-control" name="requirements" placeholder="Misal: Posting wajib di-keep minimal 30 hari"></textarea>
                </div>
                <div class="form-group">
                  <label>5. Pilih Jasa Influencer</label>
                  <select name="service" id="service" class="form-control" required>
                    <option value="">Pilih Jasa</option>
                    <?php foreach ($rate_cards as $rate_card) : ?>
                      <option value="<?= htmlspecialchars($rate_card['jasa']) ?>" data-price="<?= htmlspecialchars($rate_card['harga']) ?>">
                        <?= htmlspecialchars($rate_card['jasa']) ?> - Rp <?= number_format($rate_card['harga'], 0, ',', '.') ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>6. Produk/layanan GRATIS untuk influencer</label>
                  <textarea class="form-control" name="free_product" placeholder="Deskripsikan produk atau layanan yang diberikan kepada influencer"></textarea>
                </div>
                <div class="form-group">
                  <label>7. Upload gambar (max 5MB)</label>
                  <input type="file" class="form-control-file" name="image" accept="image/*" onchange="previewImage(event)">
                  <br>
                  <img id="image-preview" style="max-width: 100%; max-height: 200px; margin-top: 10px; display: none;" />
                </div>

                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="contactPermission" name="contact_permission">
                  <label class="form-check-label" for="contactPermission">Saya bersedia dihubungi langsung oleh influencer.</label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center mb-5">
          <button type="submit" class="btn btn-success btn-lg">Lanjut ke Pembayaran</button>
          <div id="loading-spinner" style="display:none;">
            <i class="fa fa-spinner fa-spin"></i> Menyimpan data...
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
