<?php
include '../db_conn.php'; // Database connection

// Fetch locations from the database for the location dropdown filter
$stmt = $conn->prepare("SELECT DISTINCT lokasi FROM event");
$stmt->execute();
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the current date
$currentDate = date("Y-m-d");

// Get the filter values from the query parameters (if set)
$selectedLocation = isset($_GET['location']) && $_GET['location'] != '' ? $_GET['location'] : null;
$startDate = isset($_GET['start-date']) && $_GET['start-date'] != '' ? $_GET['start-date'] : null;
$endDate = isset($_GET['end-date']) && $_GET['end-date'] != '' ? $_GET['end-date'] : null;

// Flag to check if filters are applied
$filtersApplied = $selectedLocation || ($startDate && $endDate);

// Build the query for upcoming events
$upcomingEventsQuery = "SELECT * FROM event WHERE tanggal >= :currentDate AND status != 'deleted' ORDER BY tanggal ASC";

// Add location filter if a location is selected
if ($selectedLocation) {
    $upcomingEventsQuery .= " AND lokasi = :location";
}

// Add date range filter if both start and end dates are selected
if ($startDate && $endDate) {
    $upcomingEventsQuery .= " AND tanggal BETWEEN :startDate AND :endDate";
}

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
$upcomingStmt->execute();
$upcomingEvents = $upcomingStmt->fetchAll(PDO::FETCH_ASSOC);

// Build the query for past events
$pastEventsQuery = "SELECT * FROM event WHERE tanggal < :currentDate AND status != 'deleted'";

// Add location filter if a location is selected
if ($selectedLocation) {
    $pastEventsQuery .= " AND lokasi = :location";
}

// Add date range filter if both start and end dates are selected
if ($startDate && $endDate) {
    $pastEventsQuery .= " AND tanggal BETWEEN :startDate AND :endDate";
}

// Prepare and execute the query
$pastStmt = $conn->prepare($pastEventsQuery);

// Bind parameters based on the filters
$pastStmt->bindValue(':currentDate', $currentDate);
if ($selectedLocation) {
    $pastStmt->bindValue(':location', $selectedLocation);
}
if ($startDate && $endDate) {
    $pastStmt->bindValue(':startDate', $startDate);
    $pastStmt->bindValue(':endDate', $endDate);
}
$pastStmt->execute();
$pastEvents = $pastStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../style/output.css" rel="stylesheet">
    <script src="../js/toggleSidebar.js"></script>
    <style>
        /* Hide the hamburger menu when the sidebar is open */
        #sidebar.open + #main-content #toggle-sidebar {
            display: none;
        }

        /* Add smooth transition animation to the buttons */
        #upcomingTab, #pastTab {
            transition: all 0.3s ease; /* Smooth animation for all properties */
        }

        /* Hover effects for buttons */
        #upcomingTab:hover, #pastTab:hover {
            background-color: #1D4ED8; /* Darker blue for hover state */
            transform: scale(1.05); /* Slightly enlarge the button */
            color: white; /* Make the text color white */
        }
    </style>
</head>
<body class="h-screen bg-gray-100">

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-gray-800 text-white transition-transform duration-300 ease-in-out transform -translate-x-full z-20">
        <button onclick="toggleSidebar()" class="p-4 text-right">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
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
                    <option value="<?= $location['lokasi'] ?>" <?= $selectedLocation == $location['lokasi'] ? 'selected' : '' ?>><?= $location['lokasi'] ?></option>
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
        <header class="flex justify-between items-center p-4 bg-white shadow">
            <h1 class="text-2xl font-bold ml-7">Admin Dashboard</h1>
            <button id="toggle-sidebar" onclick="toggleSidebar()" class="top-5 left-4 z-30 absolute">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </header>

        <!-- Tabs for Upcoming and Past Events (Hidden if filters applied) -->
        <?php if (!$filtersApplied): ?>
        <div class="flex justify-center my-4">
            <button id="upcomingTab" onclick="showUpcoming()" class="p-4 text-lg transition-all duration-300 bg-blue-600 text-white rounded-l-lg">Upcoming Events</button>
            <button id="pastTab" onclick="showPast()" class="p-4 text-lg transition-all duration-300 bg-slate-200 text-black rounded-r-lg">Past Events</button>
        </div>
        <?php endif; ?>

        <!-- Upcoming Events Section -->
        <div id="upcomingEvents" class="grid grid-cols-3 gap-4 p-4">
            <?php if (count($upcomingEvents) > 0): ?>
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="bg-white shadow p-4 rounded relative group">
                        <img src="<?= $event['banner'] ?>" alt="Event Banner" class="w-full h-40 object-cover mb-4">
                        <h3 class="text-xl font-bold"><?= $event['nama_event'] ?></h3>
                        <p><?= $event['tanggal'] ?></p>
                        <p><?= $event['lokasi'] ?></p>

                        <!-- Edit and Delete Buttons on Hover -->
                        <div class="absolute top-2 right-2 hidden group-hover:flex space-x-2">
                            <a href="../event-management/edit-event.php?id_event=<?= $event['id_event'] ?>" class="bg-blue-600 text-white p-2 rounded">Edit</a>
                            <form action="../event-management/edit-event-delete.php" method="POST">
                                <input type="hidden" name="id_event" value="<?= $event['id_event'] ?>">
                                <button type="submit" class="bg-red-600 text-white p-2 rounded">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center col-span-3">No upcoming events found.</p>
            <?php endif; ?>
        </div>

        <!-- Past Events Section -->
        <div id="pastEvents" class="grid grid-cols-3 gap-4 p-4 hidden">
            <?php if (count($pastEvents) > 0): ?>
                <?php foreach ($pastEvents as $event): ?>
                    <div class="bg-white shadow p-4 rounded relative group">
                        <img src="<?= $event['banner'] ?>" alt="Event Banner" class="w-full h-40 object-cover mb-4">
                        <h3 class="text-xl font-bold"><?= $event['nama_event'] ?></h3>
                        <p><?= $event['tanggal'] ?></p>
                        <p><?= $event['lokasi'] ?></p>

                        <!-- Edit and Delete Buttons on Hover -->
                        <div class="absolute top-2 right-2 hidden group-hover:flex space-x-2">
                            <a href="../event-management/edit-event.php?id_event=<?= $event['id_event'] ?>" class="bg-blue-600 text-white p-2 rounded">Edit</a>

                            <form action="../event-management/edit-event-delete.php" method="POST" >
                                <input type="hidden" name="id_event" value="<?= $event['id_event'] ?>">
                                <button type="submit" class="bg-red-600 text-white p-2 rounded" onsubmit="return confirm('Are you sure you want to delete this event?');">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center col-span-3">No past events found.</p>
            <?php endif; ?>
        </div>

        <!-- Add Event Button -->
        <a href="../event-management/create-event-details.php" class="fixed bottom-7 right-7  text-xl bg-green-600 text-white p-3 rounded-full shadow-lg hover:bg-green-700 transition duration-300">
            Add Event
        </a>

    </div>

    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("-translate-x-full");
            document.getElementById("main-content").classList.toggle("ml-64");
            // document.getElementById("main-content").classList.toggle("pl-32");

            // Toggle hamburger menu visibility
            document.getElementById("toggle-sidebar").classList.toggle("hidden");
        }

        function applyFilters() {
            const locationFilter = document.getElementById('locationFilter').value;
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;

            let url = 'admin-dashboard-index.php?';

            if (locationFilter) {
                url += 'location=' + encodeURIComponent(locationFilter) + '&';
            }
            if (startDate) {
                url += 'start-date=' + encodeURIComponent(startDate) + '&';
            }
            if (endDate) {
                url += 'end-date=' + encodeURIComponent(endDate);
            }

            window.location.href = url;
        }

        function resetFilters() {
            // Reload the page without any filters
            window.location.href = 'admin-dashboard-index.php';
        }

        function showUpcoming() {
        // Show upcoming events
        document.getElementById("upcomingEvents").classList.remove("hidden");
        document.getElementById("pastEvents").classList.add("hidden");

        // Apply active styles to the upcoming tab
        document.getElementById("upcomingTab").classList.add("bg-blue-600", "text-white");
        document.getElementById("upcomingTab").classList.remove("bg-slate-200", "text-black");

        // Reset past tab to inactive styles
        document.getElementById("pastTab").classList.remove("bg-blue-600", "text-white");
        document.getElementById("pastTab").classList.add("bg-slate-200", "text-black");
        }

        function showPast() {
            // Show past events
            document.getElementById("upcomingEvents").classList.add("hidden");
            document.getElementById("pastEvents").classList.remove("hidden");

            // Apply active styles to the past tab
            document.getElementById("pastTab").classList.add("bg-blue-600", "text-white");
            document.getElementById("pastTab").classList.remove("bg-slate-200", "text-black");

            // Reset upcoming tab to inactive styles
            document.getElementById("upcomingTab").classList.remove("bg-blue-600", "text-white");
            document.getElementById("upcomingTab").classList.add("bg-slate-200", "text-black");
        }

        function confirmDelete() {
            return confirm("Are you sure you want to delete this event?");
        }
    </script>
</body>
</html>
