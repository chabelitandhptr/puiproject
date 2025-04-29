<?php
require_once 'helper/connection.php';
session_start();

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = mysqli_real_escape_string($connection, $_POST['role']);

    // 🔹 Cek apakah username sudah digunakan
    $check_query = "SELECT id FROM users WHERE username='$username'";
    $check_result = mysqli_query($connection, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $_SESSION['error'] = "Username sudah digunakan! Pilih username lain.";
        header("Location: register.php");
        exit();
    }

    // 🔹 Cek apakah email sudah digunakan
    $check_email = "SELECT id FROM users WHERE email='$email'";
    $check_email_result = mysqli_query($connection, $check_email);

    if (mysqli_num_rows($check_email_result) > 0) {
        $_SESSION['error'] = "Email sudah terdaftar! Gunakan email lain.";
        header("Location: register.php");
        exit();
    }

    // 🔹 Insert ke tabel users
    $query_users = "INSERT INTO users (username, email, role) VALUES ('$username', '$email', '$role')";
    $result_users = mysqli_query($connection, $query_users);

    if ($result_users) {
        $user_id = mysqli_insert_id($connection); // ✅ Ambil ID terakhir dari users

        // 🔹 Insert ke tabel login (Pastikan FK benar: `user_id`)
        $query_login = "INSERT INTO login (user_id, username, password) VALUES ('$user_id', '$username', '$password')";
        $result_login = mysqli_query($connection, $query_login);

        // 🔹 Jika role influencer, tambahkan ke tabel influencers
        if ($role === 'influencer') {
            $query_influencer = "INSERT INTO influencers (id, username) VALUES ('$user_id', '$username')";
            $result_influencer = mysqli_query($connection, $query_influencer);

            if (!$result_influencer) {
                $_SESSION['error'] = "Registrasi gagal! (Error saat menyimpan data influencer)";
                header("Location: register.php");
                exit();
            }
        }

        if ($result_login) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header("Location: login.php");
            exit();
        } else {
            $_SESSION['error'] = "Registrasi gagal! (Error saat menyimpan login)";
        }
    } else {
        $_SESSION['error'] = "Registrasi gagal! (Error saat menyimpan user)";
    }

    header("Location: register.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Register &mdash; CollabNest</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">
</head>

<body>
  <div id="app">
    <section class="section">
      <div class="container mt-5">
        <div class="row">
          <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="login-brand">
              <img src="https://ids.ac.id/wp-content/uploads/2019/11/Logo-IDS-College.png" alt="logo" width="300">
            </div>

            <div class="card card-primary">
              <div class="card-header">
                <h4>Register</h4>
              </div>

              <div class="card-body">

                <!-- 🔹 Tampilkan Notifikasi -->
                <?php if (isset($_SESSION['success'])) : ?>
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])) : ?>
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error']; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form method="POST" action="" class="needs-validation" novalidate="">
                  <div class="form-group">
                    <label for="username">Username</label>
                    <input id="username" type="text" class="form-control" name="username" required autofocus>
                    <div class="invalid-feedback">
                      Mohon isi username
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input id="email" type="email" class="form-control" name="email" required>
                    <div class="invalid-feedback">
                      Mohon isi email yang valid
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password" required>
                    <div class="invalid-feedback">
                      Mohon isi kata sandi
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" class="form-control" required>
                      <option value="umkm">UMKM</option>
                      <option value="influencer">Influencer</option>
                    </select>
                    <div class="invalid-feedback">
                      Mohon pilih role
                    </div>
                  </div>

                  <div class="form-group">
                    <button name="submit" type="submit" class="btn btn-primary btn-lg btn-block">
                      Register
                    </button>
                  </div>
                </form>

                <div class="text-center">
                  <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                </div>
              </div>
            </div>

            <div class="simple-footer">
              Copyright &copy; CollabNest 2025
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="assets/js/stisla.js"></script>

  <!-- Page Specific JS File -->
</body>

</html>
