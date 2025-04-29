<?php
// Ambil data user dari database, contoh ambil dari session
session_start();
$user_id = $_SESSION['login']['id'] ?? 1; // ganti sesuai implementasi
$conn = new mysqli("localhost", "root", "", "collabnest");
$data = $conn->query("SELECT * FROM influencer WHERE id = $user_id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Profil</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap + Font Awesome -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <style>
    .card-section { border-radius: 10px; padding: 20px; }
    .card-section h5 { margin-bottom: 20px; }
    .profile-img { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; }
  </style>
</head>
<body class="bg-light">

<div class="container py-5">
  <div class="text-center mb-4">
    <img src="<?= $data['profile_image'] ?? 'default.jpg' ?>" class="profile-img" alt="Profile">
    <h3 class="mt-2"><?= htmlspecialchars($data['full_name']) ?></h3>
    <p class="text-muted"><?= $data['username'] ?> | <?= $data['category'] ?></p>
  </div>

  <form action="update_profile.php" method="POST" enctype="multipart/form-data">
    <div class="card card-section mb-3 shadow-sm">
      <h5>Informasi Umum</h5>
      <div class="form-group">
        <label>Full Name</label>
        <input type="text" name="full_name" class="form-control" value="<?= $data['full_name'] ?>">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="<?= $data['email'] ?>">
      </div>
      <div class="form-group">
        <label>Phone</label>
        <input type="text" name="phone" class="form-control" value="<?= $data['phone'] ?>">
      </div>
      <div class="form-group">
        <label>Negara</label>
        <input type="text" name="country" class="form-control" value="<?= $data['country'] ?>">
      </div>
      <div class="form-group">
        <label>Kategori</label>
        <input type="text" name="category" class="form-control" value="<?= $data['category'] ?>">
      </div>
      <div class="form-group">
        <label>Foto Profil</label>
        <input type="file" name="profile_image" class="form-control-file">
      </div>
    </div>

    <div class="card card-section mb-3 shadow-sm">
      <h5>Media Sosial & Bio</h5>
      <div class="form-group">
        <label>Bio</label>
        <textarea name="bio" class="form-control" rows="3"><?= $data['bio'] ?? '' ?></textarea>
      </div>
      <div class="form-group">
        <label>Link Sosial Media</label>
        <input type="text" name="social_links" class="form-control" value="<?= $data['social_links'] ?? '' ?>">
        <small class="text-muted">Pisahkan dengan koma jika lebih dari satu.</small>
      </div>
      <div class="form-group">
        <label>LinkBio</label>
        <input type="text" name="linkbio_url" class="form-control" value="<?= $data['linkbio_url'] ?? '' ?>">
      </div>
    </div>

    <div class="text-center">
      <button class="btn btn-primary">Simpan Perubahan</button>
    </div>
  </form>
</div>

<!-- JS Bootstrap -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

</body>
</html>
