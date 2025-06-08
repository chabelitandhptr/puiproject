<?php
require_once '../helper/connection.php';

$username = isset($_GET['username']) ? trim($_GET['username']) : '';

$sql = "SELECT jasa, harga FROM rate_cards WHERE username = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p class='text-muted'>Tidak ada data rate card.</p>";
} else {
    echo "<ul class='list-group'>";
    while ($row = $result->fetch_assoc()) {
        echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
        echo htmlspecialchars($row['jasa']);
        echo "<span class='badge bg-primary'>Rp " . number_format($row['harga']) . "</span>";
        echo "</li>";
    }
    echo "</ul>";
}

$stmt->close();
$connection->close();
?>
