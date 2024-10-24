<?php
session_start();
include 'db_conn.php'; 

if (!isset($_SESSION['user_id'])) {
    include 'navbar/navbar_guest.php'; // Optional: a guest navbar
}else{
    include 'navbar/navbar_user.php'; // User navbar

}


// Get the current date
$currentDate = date("Y-m-d");

// Get the filter values from the query parameters (if set)
$startDate = isset($_GET['start-date']) && $_GET['start-date'] != '' ? htmlspecialchars($_GET['start-date']) : null;
$endDate = isset($_GET['end-date']) && $_GET['end-date'] != '' ? htmlspecialchars($_GET['end-date']) : null;
$searchQuery = isset($_GET['search']) && $_GET['search'] != '' ? htmlspecialchars($_GET['search']) : null;

// Build the query for upcoming events sorted by date
$upcomingEventsQuery = "SELECT * FROM event WHERE status != 'deleted' AND tanggal >= :currentDate";

// Add date range filter if both start and end dates are selected
if ($startDate && $endDate) {
    $upcomingEventsQuery .= " AND tanggal BETWEEN :startDate AND :endDate";
}

// Add search filter if a search query is provided
if ($searchQuery) {
    $upcomingEventsQuery .= " AND nama_event LIKE :searchQuery";
}

// Add ordering by upcoming date
$upcomingEventsQuery .= " ORDER BY tanggal ASC";

// Prepare and execute the query
$upcomingStmt = $conn->prepare($upcomingEventsQuery);

// Bind parameters based on the filters
$upcomingStmt->bindValue(':currentDate', $currentDate);
if ($startDate && $endDate) {
    $upcomingStmt->bindValue(':startDate', $startDate);
    $upcomingStmt->bindValue(':endDate', $endDate);
}
if ($searchQuery) {
    $upcomingStmt->bindValue(':searchQuery', '%' . $searchQuery . '%');
}
$upcomingStmt->execute();
$upcomingEvents = $upcomingStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Events</title>
    <link href="./style/output.css" rel="stylesheet">
    <script src="./js/toggleSidebar.js"></script>
</head>
<body class="h-screen bg-gray-100">

    <!-- Main Content -->
    <div id="main-content" class="transition-all duration-300 ml-0 pl-0">

        <!-- Top Banner -->
        <div class="w-full h-96 mb-8">
            <img src="./assets/banner-abstract5.jpg" alt="Event Banner" class="w-full h-full object-cover">
        </div>
        
        <!-- Search Bar (Centered in Main Content) -->
        <div class="flex justify-center my-8">
            <input type="text" id="search" name="search" class="w-full md:w-1/2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800" placeholder="Search events" value="<?= htmlspecialchars($searchQuery) ?>" />
            <button onclick="applyFilters()" class="ml-3 bg-slate-800 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition">Search</button>
        </div>

        <!-- Upcoming Events Section -->
        <div id="upcomingEvents" class="grid grid-cols-3 gap-4 p-4">
            <?php if (count($upcomingEvents) > 0): ?>
                <?php foreach ($upcomingEvents as $event): ?>
                    <a href="./event-registration/event-registration.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="bg-white shadow p-4 rounded relative group hover:bg-gray-100 transition">
                        <img src="<?= htmlspecialchars($event['banner']) ?>" alt="Event Banner" class="w-full h-40 object-cover mb-4">
                        <h3 class="text-xl font-bold"><?= htmlspecialchars($event['nama_event']) ?></h3>
                        <p><?= htmlspecialchars($event['tanggal']) ?></p>
                        <p><?= htmlspecialchars($event['lokasi']) ?></p>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center col-span-3">No upcoming events found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

