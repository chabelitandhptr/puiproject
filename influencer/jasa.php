<?php
require_once 'layout/_top.php';
require_once '../helper/connection.php';
require_once '../helper/auth.php';

$username = $_SESSION['login']['username'] ?? null;
if (!$username) {
    echo "<div class='alert alert-danger'>Username tidak ditemukan di session!</div>";
    exit;
}

$success = false;

// Simpan data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $jasa = $_POST['jasa'];
    $harga = $_POST['harga'];

    $stmt = $connection->prepare("INSERT INTO rate_cards (jasa, harga, username) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $jasa, $harga, $username);
    $stmt->execute();
    $stmt->close();

    $success = true;
}

// Ambil data hanya milik user ini berdasarkan username
$stmt = $connection->prepare("SELECT * FROM rate_cards WHERE username = ? ORDER BY id DESC");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<section class="section">
  <div class="section-header">
    <h1>Jasa</h1>
  </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Data berhasil disimpan.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Form Input -->
    <div class="card mb-4 shadow-sm border-light">
        <div class="card-body">
            <form method="post" class="row g-3">
                <div class="col-md-6">
                    <label for="jasa" class="form-label fw-semibold">Jenis Jasa</label>
                    <select name="jasa" id="jasa" class="form-select form-select-lg" required>
                        <option value="" disabled selected class="text-muted">-- Pilih Jenis Jasa --</option>
                        <option value="1x Post di Story">📷 1x Post di Story</option>
                        <option value="1x Post di Feed">🖼️ 1x Post di Feed</option>
                        <option value="1x Video Reels">🎥 1x Video Reels</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="harga" class="form-label fw-semibold">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" class="form-control form-control-lg" required>
                </div>

                <div class="col-12 text-end mt-3">
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data -->
    <h4 class="mb-3 text-center">Daftar Rate Card</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Jenis Jasa</th>
                    <th>Harga</th>
                    <th>Tanggal Input</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['jasa']) ?></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                            <td><?= $row['created_at'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">Belum ada rate card.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
require_once '../layout/_bottom.php';
$connection->close();
?>
