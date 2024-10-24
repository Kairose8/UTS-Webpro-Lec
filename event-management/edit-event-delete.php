<?php
include '../db_conn.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_event = $_POST['id_event'];

    // Update the event status to "Cancelled"
    $stmt = $conn->prepare("UPDATE event SET status = 'Cancelled' WHERE id_event = :id_event");
    $stmt->execute(['id_event' => $id_event]);

    echo "Event marked as Cancelled.";
    header("Location: ../admin-dashboard/admin-dashboard-index.php");
    exit;
}
?>
