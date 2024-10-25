<?php
include '../db_conn.php'; // Database connection
include '../navbar/navbar-admin.php';

session_start();
$admin = $_SESSION['admin'];

if ($admin) {
    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = :username");
    $stmt->execute(['username' => $admin]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}else{
    header('location: ../index.php');
}
// Fetch locations from the database for the location dropdown filter
$stmt = $conn->prepare("SELECT DISTINCT lokasi FROM event");
$stmt->execute();
$locations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get the current date
$currentDate = date("Y-m-d");

// Get the filter values from the query parameters (if set)
$selectedLocation = isset($_GET['location']) && $_GET['location'] != '' ? htmlspecialchars($_GET['location']) : null;
$selectedStatus = isset($_GET['status']) && $_GET['status'] != '' ? htmlspecialchars($_GET['status']) : null; 
$startDate = isset($_GET['start-date']) && $_GET['start-date'] != '' ? htmlspecialchars($_GET['start-date']) : null;
$endDate = isset($_GET['end-date']) && $_GET['end-date'] != '' ? htmlspecialchars($_GET['end-date']) : null;
$searchQuery = isset($_GET['search']) && $_GET['search'] != '' ? htmlspecialchars($_GET['search']) : null;

// Flag to check if filters are applied
$filtersApplied = $selectedLocation || $selectedStatus || ($startDate && $endDate);

// Build query for upcoming events
$upcomingEventsQuery = "SELECT * FROM event WHERE tanggal >= :currentDate";
if ($selectedLocation) {
    $upcomingEventsQuery .= " AND lokasi = :location";
}
if ($selectedStatus) {
    $upcomingEventsQuery .= " AND status = :status";
}
if (!$selectedStatus) {
    $upcomingEventsQuery .= " AND status != 'Cancelled'";
}
if ($startDate && $endDate) {
    $upcomingEventsQuery .= " AND tanggal BETWEEN :startDate AND :endDate";
}
if ($searchQuery) {
    $upcomingEventsQuery .= " AND nama_event LIKE :searchQuery";
}
$upcomingEventsQuery .= " ORDER BY tanggal ASC";

// Prepare and execute the query for upcoming events
$upcomingStmt = $conn->prepare($upcomingEventsQuery);
$upcomingStmt->bindValue(':currentDate', $currentDate);
if ($selectedLocation) {
    $upcomingStmt->bindValue(':location', $selectedLocation);
}
if ($selectedStatus) {
    $upcomingStmt->bindValue(':status', $selectedStatus);
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

// Build query for past events
$pastEventsQuery = "SELECT * FROM event WHERE tanggal < :currentDate";
if ($selectedLocation) {
    $pastEventsQuery .= " AND lokasi = :location";
}
if ($selectedStatus) {
    $pastEventsQuery .= " AND status = :status";
}
if (!$selectedStatus) {
    $pastEventsQuery .= " AND status != 'Cancelled'";
}
if ($startDate && $endDate) {
    $pastEventsQuery .= " AND tanggal BETWEEN :startDate AND :endDate";
}
$pastEventsQuery .= " ORDER BY tanggal DESC";

// Prepare and execute the query for past events
$pastStmt = $conn->prepare($pastEventsQuery);
$pastStmt->bindValue(':currentDate', $currentDate);
if ($selectedLocation) {
    $pastStmt->bindValue(':location', $selectedLocation);
}
if ($selectedStatus) {
    $pastStmt->bindValue(':status', $selectedStatus);
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
    <meta http-equiv="Content-Security-Policy" content="default-src 'self'; img-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';">
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
            transition: all 0.3s ease;
        }

        /* Hover effects for buttons */
        #upcomingTab:hover, #pastTab:hover {
            background-color: #1D4ED8;
            transform: scale(1.05);
            color: white;
        }

        #deleteModal {
            display: none;
        }

        #deleteModal.flex {
            display: flex;
        }

    </style>
</head>
<body class="h-screen bg-gray-100">

    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-7 left-0 w-64 h-full bg-gray-800 text-white transition-transform duration-300 ease-in-out transform -translate-x-full z-20">
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
                    <option value="<?= htmlspecialchars($location['lokasi']) ?>" <?= $selectedLocation == htmlspecialchars($location['lokasi']) ? 'selected' : '' ?>><?= htmlspecialchars($location['lokasi']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Filter by Status -->
        <div class="p-4">
            <label for="statusFilter" class="block">Status:</label>
            <select id="statusFilter" class="w-full p-2 rounded bg-gray-700">
                <option value="">All</option>
                <option value="Open" <?= $selectedStatus == 'Open' ? 'selected' : '' ?>>Open</option>
                <option value="Closed" <?= $selectedStatus == 'Closed' ? 'selected' : '' ?>>Closed</option>
                <option value="Cancelled" <?= $selectedStatus == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
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
            <button onclick="applyFilters()" class="bg-slate-800 p-2 rounded w-full">Apply Filters</button>
            <button onclick="resetFilters()" class="bg-red-600 p-2 rounded w-full mt-2">Reset Filters</button>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="transition-all duration-300 ml-0 pl-0">
            <button id="toggle-sidebar" onclick="toggleSidebar()" class="top-5 left-4 z-30 absolute">
                <svg class="w-6 h-6" fill="none" stroke="white" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </header>

        <!-- Search Bar (Centered in Main Content) -->
        <div class="flex justify-center my-8">
            <input type="text" id="search" name="search" class="w-full md:w-1/2 p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800" placeholder="Search events" value="<?= $searchQuery ?>" />
            <button onclick="applyFilters()" class="ml-3 bg-slate-800 text-white px-4 py-2 rounded-lg hover:bg-slate-700 transition">Search</button>
        </div>

        <!-- Tabs for Upcoming and Past Events (Hidden if filters applied) -->
        <?php if (!$filtersApplied): ?>
        <div class="flex justify-center my-4">
            <button id="upcomingTab" onclick="showUpcoming()" class="p-4 text-lg font-semibold transition-all duration-300 bg-slate-800 text-white rounded-l-lg">Upcoming Events</button>
            <button id="pastTab" onclick="showPast()" class="p-4 text-lg font-semibold transition-all duration-300 bg-slate-200 text-black rounded-r-lg">Past Events</button>
        </div>
        <?php endif; ?>

        <!-- Upcoming Events Section -->
        <div id="upcomingEvents" class="<?= !$filtersApplied ? '' : 'hidden' ?> grid grid-cols-3 gap-4 p-4">
            <?php if (count($upcomingEvents) > 0): ?>
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="bg-white shadow p-4 rounded relative group">
                        <a href="./admin-dashboard-event.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="<?= htmlspecialchars($event['status']) == 'Closed' ? 'opacity-50' : '' ?>">
                            <img src="<?= htmlspecialchars($event['banner']) ?>" alt="Event Banner" class="w-full h-40 object-cover mb-4">
                            <h3 class="text-xl font-bold"><?= htmlspecialchars($event['nama_event']) ?></h3>
                            <p><?= htmlspecialchars($event['tanggal']) ?></p>
                            <p><?= htmlspecialchars($event['lokasi']) ?></p>
                        </a>

                        <!-- Edit and Delete Buttons on Hover -->
                        <div class="absolute top-2 right-2 hidden group-hover:flex space-x-2">
                            <a href="../event-management/edit-event.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="bg-slate-800 text-white p-2 rounded">Edit</a>
                            <form action="../event-management/edit-event-delete.php" method="POST">
                                <input type="hidden" name="id_event" value="<?= htmlspecialchars($event['id_event']) ?>">
                                <button type="button" onclick="showDeleteModal(<?= htmlspecialchars($event['id_event']) ?>)" class="bg-red-600 text-white p-2 rounded">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center col-span-3">No upcoming events found.</p>
            <?php endif; ?>
        </div>

        <!-- Past Events Section -->
        <div id="pastEvents" class="<?= !$filtersApplied ? '' : 'hidden' ?> grid grid-cols-3 gap-4 p-4">
            <?php if (count($pastEvents) > 0): ?>
                <?php foreach ($pastEvents as $event): ?>
                    <div class="bg-white shadow p-4 rounded relative group">
                        <a href="./admin-dashboard-event.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="opacity-50">
                            <img src="<?= htmlspecialchars($event['banner']) ?>" alt="Event Banner" class="w-full h-40 object-cover mb-4">
                            <h3 class="text-xl font-bold"><?= htmlspecialchars($event['nama_event']) ?></h3>
                            <p><?= htmlspecialchars($event['tanggal']) ?></p>
                            <p><?= htmlspecialchars($event['lokasi']) ?></p>
                        </a>

                        <!-- Edit and Delete Buttons on Hover -->
                        <div class="absolute top-2 right-2 hidden group-hover:flex space-x-2">
                            <a href="../event-management/edit-event.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="bg-slate-800 text-white p-2 rounded">Edit</a>
                            <form action="../event-management/edit-event-delete.php" method="POST" >
                                <input type="hidden" name="id_event" value="<?= htmlspecialchars($event['id_event']) ?>">
                                <button type="button" onclick="showDeleteModal(<?= htmlspecialchars($event['id_event']) ?>)" class="bg-red-600 text-white p-2 rounded" onsubmit="return confirm('Are you sure you want to delete this event?');">Delete</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center col-span-3">No past events found.</p>
            <?php endif; ?>
        </div>

        <!-- Filtered Events Section (visible if filters are applied) -->
        <?php if ($filtersApplied): ?>
        <div id="filteredEvents" class="grid grid-cols-3 gap-4 p-4">
            <?php foreach ($upcomingEvents as $event): ?>
                <div class="bg-white shadow p-4 rounded relative group">
                    <a href="./admin-dashboard-event.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="<?= $event['status'] == 'Closed' ? 'opacity-50' : '' ?>">
                        <img src="<?= htmlspecialchars($event['banner']) ?>" alt="Event Banner" class="w-full h-40 object-cover mb-4">
                        <h3 class="text-xl font-bold"><?= htmlspecialchars($event['nama_event']) ?></h3>
                        <p><?= htmlspecialchars($event['tanggal']) ?></p>
                        <p><?= htmlspecialchars($event['lokasi']) ?></p>
                    </a>
                    <!-- Edit and Delete Buttons for Filtered Events -->
                    <div class="absolute top-2 right-2 hidden group-hover:flex space-x-2">
                        <a href="../event-management/edit-event.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="bg-slate-800 text-white p-2 rounded">Edit</a>
                        <form action="../event-management/edit-event-delete.php" method="POST">
                            <input type="hidden" name="id_event" value="<?= htmlspecialchars($event['id_event']) ?>">
                            <button type="button" onclick="showDeleteModal(<?= htmlspecialchars($event['id_event']) ?>)" type="submit" class="bg-red-600 text-white p-2 rounded">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <?php foreach ($pastEvents as $event): ?>
                <div class="bg-white shadow p-4 rounded relative group">
                    <a href="./admin-dashboard-event.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="opacity-50">
                        <img src="<?= htmlspecialchars($event['banner']) ?>" alt="Event Banner" class="w-full h-40 object-cover mb-4">
                        <h3 class="text-xl font-bold"><?= htmlspecialchars($event['nama_event']) ?></h3>
                        <p><?= htmlspecialchars($event['tanggal']) ?></p>
                        <p><?= htmlspecialchars($event['lokasi']) ?></p>
                    </a>
                    <!-- Edit and Delete Buttons for Filtered Past Events -->
                    <div class="absolute top-2 right-2 hidden group-hover:flex space-x-2">
                        <a href="../event-management/edit-event.php?id_event=<?= htmlspecialchars($event['id_event']) ?>" class="bg-slate-800 text-white p-2 rounded">Edit</a>
                        <form action="../event-management/edit-event-delete.php" method="POST">
                            <input type="hidden" name="id_event" value="<?= htmlspecialchars($event['id_event']) ?>">
                            <button type="button" onclick="showDeleteModal(<?= htmlspecialchars($event['id_event']) ?>)" type="submit" class="bg-red-600 text-white p-2 rounded">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Modal Structure for Delete Confirmation -->
        <div id="deleteModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-md w-full">
                <h2 class="text-xl font-bold mb-4">Are you sure?</h2>
                <p class="mb-6">Do you really want to delete this event?</p>
                <div class="flex justify-end space-x-4">
                    <button id="cancelButton" class="bg-gray-300 hover:bg-gray-400 text-black py-2 px-4 rounded-lg">Cancel</button>
                    <form id="deleteForm" action="../event-management/edit-event-delete.php" method="POST">
                        <input type="hidden" name="id_event" id="deleteEventId">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg">Delete</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Event Button -->
        <a href="../event-management/create-event-details.php" class="fixed bottom-24 right-7 text-xl bg-green-500 text-white p-3 rounded-lg shadow-lg hover:bg-green-600 transition duration-300">
            Add Event
        </a>
        
        <a href="../user_management/view_users.php" class="fixed bottom-7 right-7 text-xl bg-blue-500 text-white p-3 rounded-lg shadow-lg hover:bg-slate-800 transition duration-300">
            Manage Users
        </a>
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
            const statusFilter = document.getElementById('statusFilter').value;
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            const searchQuery = document.getElementById('search').value;

            let url = 'admin-dashboard-index.php?';

            if (locationFilter) {
                url += 'location=' + encodeURIComponent(locationFilter) + '&';
            }
            if (statusFilter) {
                url += 'status=' + encodeURIComponent(statusFilter) + '&';
            }
            if (startDate) {
                url += 'start-date=' + encodeURIComponent(startDate) + '&';
            }
            if (endDate) {
                url += 'end-date=' + encodeURIComponent(endDate);
            }
            if (searchQuery) {
                url += 'search=' + encodeURIComponent(searchQuery);
            }

            window.location.href = url;
        }

        function resetFilters() {
            // Reset filters and reload the page to its initial state
            window.location.href = 'admin-dashboard-index.php';

            // Remove active styling from both tabs
            document.getElementById("upcomingTab").classList.remove("bg-slate-800", "text-white");
            document.getElementById("upcomingTab").classList.add("bg-slate-200", "text-black");

            document.getElementById("pastTab").classList.remove("bg-slate-800", "text-white");
            document.getElementById("pastTab").classList.add("bg-slate-200", "text-black");
        }

        function showUpcoming() {
            // Show upcoming events
            document.getElementById("upcomingEvents").classList.remove("hidden");
            document.getElementById("pastEvents").classList.add("hidden");

            // Apply active styles to the upcoming tab
            document.getElementById("upcomingTab").classList.add("bg-slate-800", "text-white");
            document.getElementById("upcomingTab").classList.remove("bg-slate-200", "text-black");

            // Reset past tab to inactive styles
            document.getElementById("pastTab").classList.remove("bg-slate-800", "text-white");
            document.getElementById("pastTab").classList.add("bg-slate-200", "text-black");
        }

        function showPast() {
            // Show past events
            document.getElementById("upcomingEvents").classList.add("hidden");
            document.getElementById("pastEvents").classList.remove("hidden");

            // Apply active styles to the past tab
            document.getElementById("pastTab").classList.add("bg-slate-800", "text-white");
            document.getElementById("pastTab").classList.remove("bg-slate-200", "text-black");

            // Reset upcoming tab to inactive styles
            document.getElementById("upcomingTab").classList.remove("bg-slate-800", "text-white");
            document.getElementById("upcomingTab").classList.add("bg-slate-200", "text-black");
        }

        window.onload = function () {
            // Check if filters are applied (based on PHP variable passed)
            hideDeleteModal(); // Ensure the modal is hidden by default

            const filtersApplied = <?= json_encode($filtersApplied) ?>;

            // If no filters are applied, remove active styles from both tabs
            if (!filtersApplied) {
                document.getElementById("upcomingTab").classList.remove("bg-slate-800", "text-white");
                document.getElementById("upcomingTab").classList.add("bg-slate-200", "text-black");

                document.getElementById("pastTab").classList.remove("bg-slate-800", "text-white");
                document.getElementById("pastTab").classList.add("bg-slate-200", "text-black");
            }
        };

        // Function to show the modal
        function showDeleteModal(eventId) {
            const modal = document.getElementById('deleteModal');
            const deleteEventId = document.getElementById('deleteEventId');
            deleteEventId.value = eventId; // Set the event ID in the form
            modal.classList.remove('hidden'); // Show the modal
            modal.classList.add('flex');     // Display modal flexibly
        }

        // Function to hide the modal
        function hideDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.add('hidden');  // Hide the modal
            modal.classList.remove('flex'); // Remove the flex display
        }

        // When the Cancel button is clicked, hide the modal
        document.getElementById('cancelButton').addEventListener('click', function () {
            hideDeleteModal();
        });

    </script>
</body>
</html>
