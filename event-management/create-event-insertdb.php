<?php
session_start();
include '../db_conn.php'; 

$query = "INSERT INTO event (nama_event, tanggal, waktu, lokasi, deskripsi, jumlah_maksimum, banner) 
          VALUES (:name, :date, :time, :location, :description, :capacity, :banner)";

$stmt = $conn->prepare($query);

$stmt->execute([
    ':name' => $_SESSION['event_name'],
    ':date' => $_SESSION['event_date'],
    ':time' => $_SESSION['event_time'],
    ':location' => $_SESSION['event_location'],
    ':description' => $_SESSION['event_description'],
    ':capacity' => $_SESSION['event_capacity'],
    ':banner' => $_SESSION['event_banner']
]);

session_unset();

header('Location: create-event-success.php');
exit();
?>
