<?php
require_once 'helper/connection.php';
session_start();

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = mysqli_real_escape_string($connection, $_POST['role']);
var_dump($role); // Menampilkan nilai role


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
        $user_id = mysqli_insert_id($connection);

        // 🔹 Insert ke tabel login
        $query_login = "INSERT INTO login (user_id, username, password) VALUES ('$user_id', '$username', '$password')";
        $result_login = mysqli_query($connection, $query_login);

        // 🔹 Jika role influencer, insert ke tabel influencers
        if ($role === 'influencer') {
          $query_influencer = "INSERT INTO influencers (id, username, email) VALUES ('$user_id', '$username', '$email')";
          $result_influencer = mysqli_query($connection, $query_influencer);
      
          if (!$result_influencer) {
              $_SESSION['error'] = "Registrasi gagal! (Gagal menyimpan data influencer)";
              // Hapus data yang sudah dimasukkan agar konsisten
              mysqli_query($connection, "DELETE FROM login WHERE user_id='$user_id'");
              mysqli_query($connection, "DELETE FROM users WHERE id='$user_id'");
              header("Location: register.php");
              exit();
          }
      }
      

        if ($result_login) {
            $_SESSION['success'] = "Registrasi berhasil! Silakan login.";
            header("Location: login.php");
            exit();
        } else {
            // Rollback jika gagal menyimpan login
            mysqli_query($connection, "DELETE FROM users WHERE id='$user_id'");
            if ($role === 'influencer') {
                mysqli_query($connection, "DELETE FROM influencers WHERE id='$user_id'");
            }
            $_SESSION['error'] = "Registrasi gagal! (Gagal menyimpan data login)";
        }
    } else {
        $_SESSION['error'] = "Registrasi gagal! (Gagal menyimpan data user)";
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
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">

  <style>
    /* Styling for the background and layout */
    body {
      background-color: #F8F9FA;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    /* Container for the register layout */
    .login-container {
      display: flex;
      justify-content: center;
      align-items: center;
      background-color: white;
      padding: 50px;
      border-radius: 10px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
      max-width: 500px;
      width: 100%;
    }

    /* Logo */
    .login-brand {
      text-align: center;
      margin-bottom: 30px;
    }

    .login-brand img {
      width: 100%;
      max-width: 250px;
    }

    /* Right side register form */
    .login-form {
      width: 100%;
    }

    .card-header {
      background-color: #F8F9FA;
      border-bottom: 1px solid #E9ECEF;
    }

    .btn-primary {
      background-color: #FF4F5A; /* Color to match the branding */
      border-color: #FF4F5A;
    }

    .btn-primary:hover {
      background-color: #FF3B45;
      border-color: #FF3B45;
    }

    .form-group label {
      font-weight: bold;
    }

    .simple-footer {
      font-size: 12px;
      text-align: center;
      margin-top: 20px;
    }

    /* Styling for signup link */
    .text-center p {
      font-size: 14px;
    }

    .text-center a {
      color: #FF4F5A;
    }

    /* Styling for the form controls */
    .form-group {
      margin-bottom: 1rem;
    }

  </style>

</head>

<body>
  <div class="login-container">
    <!-- Right side register form -->
    <div class="login-form">
      <!-- Logo at the top -->
      <div class="login-brand">
        <img src="assets/img/logo.png" alt="logo">
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
                <option value="umkm">Umkm</option>
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

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
  <script src="assets/js/stisla.js"></script>
  <script src="assets/js/scripts.js"></script>
</body>

</html>
