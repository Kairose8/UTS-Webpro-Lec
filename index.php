<?php
session_start();
include './db_conn.php'; 

include 'navbar/navbar-user.php';
// if (!isset($_SESSION['user_id'])) {
//     include 'navbar/navbar_guest.php'; // Optional: a guest navbar
// }else{
//     include 'navbar/navbar_user.php'; // User navbar

// }

$stmt = $conn->prepare("SELECT DISTINCT lokasi FROM event");
$stmt->execute();
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the current date
$currentDate = date("Y-m-d");

// Get the filter values from the query parameters (if set)
$selectedLocation = isset($_GET['location']) && $_GET['location'] != '' ? htmlspecialchars($_GET['location']) : null;
$startDate = isset($_GET['start-date']) && $_GET['start-date'] != '' ? htmlspecialchars($_GET['start-date']) : null;
$endDate = isset($_GET['end-date']) && $_GET['end-date'] != '' ? htmlspecialchars($_GET['end-date']) : null;
$searchQuery = isset($_GET['search']) && $_GET['search'] != '' ? htmlspecialchars($_GET['search']) : null;

// Build the query for upcoming events sorted by date
$upcomingEventsQuery = "SELECT * FROM event WHERE status != 'Cancelled' AND tanggal >= :currentDate";

// Add location filter if a location is selected
if ($selectedLocation) {
    $upcomingEventsQuery .= " AND lokasi = :location";
}

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
if ($selectedLocation) {
    $upcomingStmt->bindValue(':location', $selectedLocation);
}
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
    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-gray-800 text-white transition-transform duration-300 ease-in-out transform -translate-x-full z-20">
        <button onclick="toggleSidebar()" class="p-4 text-right">
            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <h2 class="text-center text-lg font-bold">Filter Events</h2>

        <!-- Filter by Location -->
        <div class="p-4">
            <label for="locationFilter" class="block">Location:</label>
            <select id="locationFilter" class="w-full p-2 rounded bg-gray-700">
                <option value="">All</option>
                <?php foreach ($locations as $location): ?>
                    <option value="<?= htmlspecialchars($location['lokasi']) ?>" <?= $selectedLocation == htmlspecialchars($location['lokasi']) ? 'selected' : '' ?>><?= htmlspecialchars($location['lokasi']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Filter by Date -->
        <div class="p-4">
            <label for="start-date">Start Date:</label>
            <input type="date" id="start-date" name="start-date" class="block w-full mt-1 text-black" value="<?= $startDate ?>" />
            
            <label for="end-date">End Date:</label>
            <input type="date" id="end-date" name="end-date" class="block w-full mt-1 text-black" value="<?= $endDate ?>" />
        </div>
        
        <!-- Apply and Reset Filter Buttons -->
        <div class="p-4">
            <button onclick="applyFilters()" class="bg-blue-600 p-2 rounded w-full">Apply Filters</button>
            <button onclick="resetFilters()" class="bg-red-600 p-2 rounded w-full mt-2">Reset Filters</button>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="transition-all duration-300 ml-0 pl-0">
        <button id="toggle-sidebar" onclick="toggleSidebar()" class="top-5 left-4 z-30 absolute">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>


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
                        <img src="<?= htmlspecialchars(ltrim($event['banner'], './')) ?>" alt="Event Banner" class="w-full h-40 object-cover mb-4">
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

    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("-translate-x-full");
            // document.getElementById("main-content").classList.toggle("ml-64");
            // Toggle hamburger menu visibility
            document.getElementById("toggle-sidebar").classList.toggle("hidden");
        }
        function applyFilters() {
            const locationFilter = document.getElementById('locationFilter').value;
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            const searchQuery = document.getElementById('search').value;
            let url = 'index.php?';
            if (locationFilter) {
                url += 'location=' + encodeURIComponent(locationFilter) + '&';
            }
            if (startDate) {
                url += 'start-date=' + encodeURIComponent(startDate) + '&';
            }
            if (endDate) {
                url += 'end-date=' + encodeURIComponent(endDate) + '&';
            }
            if (searchQuery) {
                url += 'search=' + encodeURIComponent(searchQuery);
            }
            window.location.href = url;
        }
        function resetFilters() {
            window.location.href = 'index.php';
        }
    </script>
</body>
</html>

