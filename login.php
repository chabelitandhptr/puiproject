<?php
require_once 'helper/connection.php';
session_start();

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = $_POST['password'];

   
    $sql = "SELECT login.*, users.role 
            FROM login 
            JOIN users ON login.user_id = users.id 
            WHERE login.username='$username' 
            LIMIT 1";
    
    $result = mysqli_query($connection, $sql);
    $row = mysqli_fetch_assoc($result);

    if ($row && password_verify($password, $row['password'])) {
        $allowed_roles = ['influencer', 'umkm']; 
        if (in_array($row['role'], $allowed_roles)) {
            $_SESSION['login'] = $row;
            header('Location: index.php');
            exit();
        } else {
            echo "<script>alert('Login gagal! Role tidak diizinkan untuk login.'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Login gagal! Username atau password salah.'); window.location='login.php';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>Login &mdash; CollabStar</title>

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

    /* Container for the login layout */
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

    /* Right side login form */
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

    /* Styling for forgot password and signup link */
    .text-center p {
      font-size: 14px;
    }

    .text-center a {
      color: #FF4F5A;
    }

    /* Styling for Tabs - Brand / Influencer */
    .nav-tabs {
      border-bottom: 1px solid #E9ECEF;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .nav-item {
      width: 48%;
      text-align: center;
    }

    .nav-link {
      padding: 12px 0; /* Increased padding to avoid text cutoff */
      border: 2px solid #FF4F5A;
      color: #FF4F5A;
      font-size: 16px;
      border-radius: 4px;
      margin-right: 4%; /* Added margin for spacing between tabs */
    }

    .nav-link.active {
      background-color: #FF4F5A;
      color: white;
      font-weight: bold;
    }

    .tab-content {
      margin-top: 20px;
    }

    .tab-description {
      text-align: center;
      margin-top: 10px;
      font-size: 16px;
      color: #6c757d;
    }

    /* Make sure form is responsive */
    .form-group {
      margin-bottom: 1rem;
    }

  </style>

</head>

<body>
  <div class="login-container">
    <!-- Right side login form -->
    <div class="login-form">
      <!-- Logo at the top -->
      <div class="login-brand">
        <img src="assets/img/logo.png" alt="logo">
      </div>

      <div class="card card-primary">
        <div class="card-header">
          <ul class="nav nav-tabs" id="loginTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="brand-tab" data-toggle="tab" href="#brand" role="tab" aria-controls="brand" aria-selected="true">Brand</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="influencer-tab" data-toggle="tab" href="#influencer" role="tab" aria-controls="influencer" aria-selected="false">Influencer</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="loginTabsContent">
            <!-- Brand Login Tab -->
            <div class="tab-pane fade show active" id="brand" role="tabpanel" aria-labelledby="brand-tab">
              <form method="POST" action="" class="needs-validation" novalidate="">

                <div class="form-group">
                  <label for="username">Username or Email</label>
                  <input id="username" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                  <div class="invalid-feedback">
                    Mohon isi username
                  </div>
                </div>

                <div class="form-group">
                  <label for="password">Password</label>
                  <input id="password" type="password" class="form-control" name="password" tabindex="2" required>
                  <div class="invalid-feedback">
                    Mohon isi kata sandi
                  </div>
                </div>

                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me">
                    <label class="custom-control-label" for="remember-me">Remember me</label>
                  </div>
                </div>

                <div class="form-group">
                  <button name="submit" type="submit" class="btn btn-primary btn-lg btn-block" tabindex="3">
                    Login
                  </button>
                </div>
              </form>
            </div>

            <!-- Influencer Login Tab -->
            <div class="tab-pane fade" id="influencer" role="tabpanel" aria-labelledby="influencer-tab">
              <form method="POST" action="" class="needs-validation" novalidate="">

                <div class="form-group">
                  <label for="username-influencer">Username or Email</label>
                  <input id="username-influencer" type="text" class="form-control" name="username" tabindex="1" required autofocus>
                  <div class="invalid-feedback">
                    Mohon isi username
                  </div>
                </div>

                <div class="form-group">
                  <label for="password-influencer">Password</label>
                  <input id="password-influencer" type="password" class="form-control" name="password" tabindex="2" required>
                  <div class="invalid-feedback">
                    Mohon isi kata sandi
                  </div>
                </div>

                <div class="form-group">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="remember" class="custom-control-input" tabindex="3" id="remember-me-influencer">
                    <label class="custom-control-label" for="remember-me-influencer">Remember me</label>
                  </div>
                </div>

                <div class="form-group">
                  <button name="submit" type="submit" class="btn btn-primary btn-lg btn-block" tabindex="3">
                    Login
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Sign up link below -->
      <div class="text-center">
        <p>Don't have an account? <a href="register.php">Create New Account</a></p>
      </div>

      <div class="simple-footer">
        Copyright &copy; Caca 2025
      </div>
    </div>
  </div>

  <!-- General JS Scripts -->
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script src="assets/js/stisla.js"></script>
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>
</body>

</html>
