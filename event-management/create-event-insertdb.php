<?php
session_start();
include '../db_conn.php'; 

$query = "INSERT INTO event (nama_event, tanggal, waktu, lokasi, deskripsi, jumlah_maksimum, banner) 
          VALUES (:name, :date, :time, :location, :description, :capacity, :banner)";

$stmt = $conn->prepare($query);

$stmt->execute([
    ':name' => htmlspecialchars($_SESSION['event_name']),
    ':date' => htmlspecialchars($_SESSION['event_date']),
    ':time' => htmlspecialchars($_SESSION['event_time']),
    ':location' => htmlspecialchars($_SESSION['event_location']),
    ':description' => htmlspecialchars($_SESSION['event_description']),
    ':capacity' => htmlspecialchars($_SESSION['event_capacity']),
    ':banner' => htmlspecialchars($_SESSION['event_banner'])
]);

session_unset();

header('Location: create-event-success.php');
exit();
?>
