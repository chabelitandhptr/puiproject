<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Role - CollabNest</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    
    <style>
        body {
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .choice-card {
            padding: 30px;
            border-radius: 10px;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .choice-card h1 {
            margin-bottom: 30px;
            font-weight: bold;
        }
        .btn-choice {
            width: 100%;
            margin-bottom: 15px;
            padding: 15px;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="choice-card">
    <h1>Masuk Sebagai?</h1>

    <!-- 🔹 Tombol pilih register -->
    <form method="POST" action="">
        <button type="submit" name="register_client" class="btn btn-primary btn-choice">Daftar sebagai Client / UMKM</button>
        <button type="submit" name="register_talent" class="btn btn-success btn-choice">Daftar sebagai Talent / Influencer</button>
    </form>

    <hr>

    <!-- 🔹 Tombol pilih login -->
    <form method="POST" action="">
        <button type="submit" name="login_client" class="btn btn-outline-primary btn-choice">Login sebagai Client</button>
        <button type="submit" name="login_talent" class="btn btn-outline-success btn-choice">Login sebagai Talent</button>
    </form>
</div>

<?php
// 🔹 Cek tombol apa yang diklik
if (isset($_POST['register_client'])) {
    header('Location: akses/register_client.php');
    exit();
} elseif (isset($_POST['register_talent'])) {
    header('Location: register_talent.php');
    exit();
} elseif (isset($_POST['login_client'])) {
    header('Location: login_client.php');
    exit();
} elseif (isset($_POST['login_talent'])) {
    header('Location: login_talent.php');
    exit();
}
?>


</body>
</html>
