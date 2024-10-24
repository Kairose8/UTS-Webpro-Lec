<?php
session_start();
include '../db_conn.php'; // Database connection

// Get event ID 
$id_event = isset($_GET['id_event']) ? htmlspecialchars($_GET['id_event']) : null;

// Fetch event details from database using prepared statements
$stmt = $conn->prepare("SELECT * FROM event WHERE id_event = :id_event");
$stmt->execute(['id_event' => $id_event]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="../style/output.css" rel="stylesheet">
    <title>Event Details</title>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen text-lg">
    <div class="bg-white p-10 rounded-lg shadow-lg max-w-4xl w-full mt-8">
        <h1 class="text-2xl font-bold text-blue-600 mb-6 text-center">Event Details</h1>
        
    <div class="h-32 w-full">
        <img src="<?= htmlspecialchars($event['banner']) ?>" alt="Event Banner" 
             class="w-full h-full object-cover">
    </div>

        <!-- Event Information -->
        <div class="space-y-4 mt-6">
            <p><strong class="font-semibold">Event Name:</strong> <?= htmlspecialchars($event['nama_event']) ?></p>
            <p><strong class="font-semibold">Event Date:</strong> <?= htmlspecialchars($event['tanggal']) ?></p>
            <p><strong class="font-semibold">Event Time:</strong> <?= htmlspecialchars($event['waktu']) ?></p>
            <p><strong class="font-semibold">Event Location:</strong> <?= htmlspecialchars($event['lokasi']) ?></p>
            <p><strong class="font-semibold">Event Description:</strong> <?= htmlspecialchars($event['deskripsi']) ?></p>
            <p><strong class="font-semibold">Registrants:</strong> <?= htmlspecialchars($event['jumlah_sekarang']) . '/' . htmlspecialchars($event['jumlah_maksimum']) ?></p>
        </div>

            <div class="flex justify-between mt-8">
                <!-- Back Button -->
                <form action="./admin-dashboard-index.php" method="GET">
                    <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        Back to Admin Dashboard
                    </button>
                </form>
                
                <!-- Form to view registrants -->
                <form action="../view_event_registrations/list_of_registrants.php" method="GET">
                    <input type="hidden" name="id_event" value="<?= htmlspecialchars($event['id_event']) ?>">
                    <button type="submit" name="registrant" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                        View Registrants
                    </button>
                </form>

            </div>
    </div>
</body>
</html>
