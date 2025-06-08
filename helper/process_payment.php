<?php
require_once '../helper/connection.php';

function generateTransactionId() {
  $letters = chr(rand(65,90)) . chr(rand(65,90)); // AA-ZZ
  $numbers = str_pad(rand(0,9999), 4, '0', STR_PAD_LEFT); // 0000-9999
  return $letters . $numbers;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = generateTransactionId();
    $product_id = $_POST['endorsement_id'];
    $influencer_id = $_POST['influencer_id'];
    $service = $_POST['service'];
    $price = $_POST['price'];
    $account_number = $_POST['account_number'];
    $status = 'pending';
    $payment_method = 'transfer';
    $transaction_date = date('Y-m-d H:i:s');
    $created_at = $updated_at = date('Y-m-d H:i:s');

    $upload_dir = '../uploads/';
    $file_name = uniqid() . '_' . $_FILES['payment_image']['name'];
    $upload_path = $upload_dir . $file_name;

    if (move_uploaded_file($_FILES['payment_image']['tmp_name'], $upload_path)) {
        $query = "INSERT INTO transactions (id, product_id, influencer_id, service, price, payment_method, account_number, transaction_date, status, created_at, updated_at)
                  VALUES ('$id', '$product_id', '$influencer_id', '$service', '$price', '$payment_method', '$account_number', '$transaction_date', '$status', '$created_at', '$updated_at')";

        if ($connection->query($query)) {
            http_response_code(200);
            echo "success";
        } else {
            http_response_code(500);
            echo "Database error: " . $connection->error;
        }
    } else {
        http_response_code(500);
        echo "Upload gagal";
    }
}
?>
