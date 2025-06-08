<?php
require_once '../helper/connection.php';

// Ambil data berdasarkan ID Endorsement (ini contoh, sesuaikan dengan kebutuhan Anda)
$endorsement_id = isset($_GET['endorsement_id']) ? $_GET['endorsement_id'] : null;

if ($endorsement_id) {
    // Ambil data endorsement
    $sql = "SELECT * FROM endorsements WHERE id = '$endorsement_id'";
    $result = $connection->query($sql);
    
    if ($result->num_rows > 0) {
        $endorsement = $result->fetch_assoc();
        // Kirimkan response JSON
        echo json_encode([
            'service' => $endorsement['service'],
            'price' => $endorsement['price']
        ]);
    } else {
        echo json_encode(['error' => 'Data tidak ditemukan']);
    }
} else {
    echo json_encode(['error' => 'ID endorsement tidak valid']);
}
?>
