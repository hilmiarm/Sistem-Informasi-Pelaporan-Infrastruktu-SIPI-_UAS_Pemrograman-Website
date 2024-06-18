<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari POST request
    $laporan_id = $_POST['laporan_id'];
    $status_id = $_POST['status_id'];

    // Update status laporan
    $updateQuery = "UPDATE laporan SET ID_STATUS = ? WHERE ID_LAPORAN = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ii', $status_id, $laporan_id);

    if ($stmt->execute()) {
        $response = array('status' => 'success', 'message' => 'Status updated successfully.');
    } else {
        $response = array('status' => 'error', 'message' => 'Failed to update status.');
    }

    // Kirim respons JSON ke JavaScript
    header('Content-Type: application/json');
    echo json_encode($response);

    $stmt->close();
    $conn->close();
}
?>