<?php
include 'db_conn.php'; // Make sure this uses PDO connection

// Prepare the SQL query to fetch upcoming events
$query = "SELECT * FROM event WHERE tanggal >= CURDATE()";

// Prepare the statement
$stmt = $conn->prepare($query);

// Execute the prepared statement
$stmt->execute();

// Fetch all the resulting rows into an associative array
$events = $stmt->fetchAll();

echo "<h2>Upcoming Events</h2><hr>";
foreach ($events as $event) {
    echo "<h3>" . $event['nama_event'] . "</h3>";
    echo "<p>Date: " . $event['tanggal'] . "</p>";
    echo "<p>Location: " . $event['lokasi'] . "</p><br>";
}
?>
