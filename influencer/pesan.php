<?php
require_once 'layout/_top.php';
require_once '../helper/connection.php';
require_once '../helper/auth.php';

isLogin(); 
$username = $_SESSION['username'] ?? null;

// Cek apakah form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $full_name = mysqli_real_escape_string($connection, $_POST['full_name']);
    $phone = mysqli_real_escape_string($connection, $_POST['phone']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $instagram = mysqli_real_escape_string($connection, $_POST['instagram']);
    $followers_instagram = mysqli_real_escape_string($connection, $_POST['followers_instagram']);
    $tiktok = mysqli_real_escape_string($connection, $_POST['tiktok']);
    $followers_tiktok = mysqli_real_escape_string($connection, $_POST['followers_tiktok']);
    $category = mysqli_real_escape_string($connection, $_POST['category']);
    $bio = mysqli_real_escape_string($connection, $_POST['bio']);

    // Cek jika ada gambar profil yang diupload
    $profile_image = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        // Menangani upload gambar
        $profile_image = $_FILES['profile_image']['name'];
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($profile_image);
        
        // Pastikan file yang diupload adalah gambar
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            // Gambar berhasil diupload
        } else {
            // Gagal upload gambar
            $_SESSION['error'] = "Gagal mengunggah gambar.";
            header("Location: profil.php");
            exit();
        }
    }

    // Query untuk memperbarui data pengguna
    $query_update = "
        UPDATE influencers
        SET 
            full_name = '$full_name',
            phone = '$phone',
            email = '$email',
            instagram = '$instagram',
            followers_instagram = '$followers_instagram',
            tiktok = '$tiktok',
            followers_tiktok = '$followers_tiktok',
            category = '$category',
            bio = '$bio'";

    // Jika ada gambar profil baru, tambahkan ke query
    if ($profile_image) {
        $query_update .= ", profile_image = '$profile_image'";
    }

    // Tambahkan kondisi untuk hanya memperbarui berdasarkan username
    $query_update .= " WHERE username = '$username'";

    // Eksekusi query
    if (mysqli_query($connection, $query_update)) {
        $_SESSION['success'] = "Profil berhasil diperbarui!";
        header("Location: profil.php?message=Profil berhasil diperbarui");
        exit();
    } else {
        $_SESSION['error'] = "Gagal memperbarui profil: " . mysqli_error($connection);
        header("Location: profil.php");
        exit();
    }
}

// Mengambil data pengguna langsung dari tabel influencers
$query = mysqli_query($connection, "
  SELECT * 
  FROM influencers 
  WHERE username = '$username'
");

$data = mysqli_fetch_assoc($query);
// Gambar profil, jika tidak ada foto, gunakan gambar default
$foto_path = !empty($data['profile_image']) ? '../uploads/' . $data['profile_image'] : 'assets/img/ppkosong.jpg';
?>

<div class="section-body">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-9">
            <div class="card shadow-lg p-4">
                <div class="card-body">
                    <?php if (isset($_GET['message'])): ?>
                    <div class="alert alert-success"><?= htmlspecialchars($_GET['message']) ?></div>
                    <?php endif; ?>

                    <form method="post" action="profil.php" enctype="multipart/form-data">
                        <!-- Layout dengan 2 Kolom untuk Foto Profil dan Informasi Pengguna -->
                        <div class="row mb-4">
                            <!-- Kolom Foto Profil -->
                            <div class="col-md-4 text-center">
                                <div class="position-relative" style="width: 150px; height: 150px; margin: 0 auto;">
                                    <!-- Foto Profil Bulat -->
                                    <img src="<?= $foto_path ?>" 
                                         id="preview-img" 
                                         class="rounded-circle shadow-sm" 
                                         style="width: 100%; height: 100%; object-fit: cover; border: 3px solid #f8f9fa;"
                                         alt="Foto Profil">
                                    
                                    <!-- Tombol Edit Foto -->
                                    <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2" 
                                         style="width: 40px; height: 40px; cursor: pointer;">
                                        <label for="profile_image" class="d-block w-100 h-100 m-0 p-0" style="cursor: pointer;">
                                            <i class="fas fa-camera text-white d-flex justify-content-center align-items-center h-100"></i>
                                        </label>
                                    </div>
                                </div>
                                
                                <!-- Input File (hidden) -->
                                <input type="file" 
                                       name="profile_image" 
                                       id="profile_image" 
                                       accept="image/*" 
                                       style="display:none;" 
                                       onchange="previewImage(event)">
                                
                                <!-- Nama di bawah foto -->
                                <h5 class="mt-3 mb-0"><?= htmlspecialchars($data['full_name'] ?? '') ?></h5>
                                <small class="text-muted"><?= htmlspecialchars($data['category'] ?? '') ?></small>
                            </div>
                            
                            <!-- Kolom Informasi -->
                            <div class="col-md-8">
                                <div class="form-group mb-3">
                                    <label for="full_name">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" value="<?= htmlspecialchars($data['full_name'] ?? '') ?>" placeholder="Nama Lengkap">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="phone">No HP</label>
                                    <input type="text" class="form-control" id="phone" name="phone" value="<?= htmlspecialchars($data['phone'] ?? '') ?>" placeholder="No HP">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="email">Email</label>
                                    <input type="text" class="form-control" id="email" name="email" value="<?= htmlspecialchars($data['email'] ?? '') ?>" placeholder="Email" readonly>
                                </div>
                            </div>
                        </div>
                        <!-- Sosial Media dan Lainnya (2 Kolom) -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="instagram">Instagram</label>
                                    <input type="text" class="form-control" id="instagram" name="instagram" value="<?= $data['instagram'] ?? '' ?>" placeholder="Instagram Username">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="followers_instagram">Followers Instagram</label>
                                    <input type="number" class="form-control" id="followers_instagram" name="followers_instagram" value="<?= $data['followers_instagram'] ?? '' ?>" placeholder="Instagram Followers">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="tiktok">Tiktok</label>
                                    <input type="text" class="form-control" id="tiktok" name="tiktok" value="<?= $data['tiktok'] ?? '' ?>" placeholder="Tiktok Username">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="followers_tiktok">Followers Tiktok</label>
                                    <input type="number" class="form-control" id="followers_tiktok" name="followers_tiktok" value="<?= $data['followers_tiktok'] ?? '' ?>" placeholder="Tiktok Followers">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="category">Kategori</label>
                                    <input type="text" class="form-control" id="category" name="category" value="<?= htmlspecialchars($data['category'] ?? '') ?>" placeholder="Kategori">
                                </div>
                                <div class="form-group mb-3">
                                    <label for="bio">Bio</label>
                                    <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Bio"><?= htmlspecialchars($data['bio'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>

                        <!-- [Bagian form lainnya tetap sama...] -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Efek hover untuk foto profil */
    .rounded-circle:hover {
        opacity: 0.9;
        transition: opacity 0.3s ease;
    }
    
    /* Efek hover untuk tombol kamera */
    .bg-primary:hover {
        background-color: #0b5ed7 !important;
    }
</style>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('preview-img');
            output.src = reader.result;
            
            // Tambahkan efek animasi saat gambar berubah
            output.style.transform = 'scale(1.05)';
            setTimeout(() => {
                output.style.transform = 'scale(1)';
            }, 300);
        };
        reader.readAsDataURL(event.target.files[0]);
    }
    
    // Tambahkan event listener untuk klik pada foto profil
    document.getElementById('preview-img').addEventListener('click', function() {
        document.getElementById('profile_image').click();
    });
</script>

<?php require_once 'layout/_bottom.php'; ?>