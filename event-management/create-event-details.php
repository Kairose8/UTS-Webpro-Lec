<?php
session_start();

// Sanitize inputs to prevent XSS
function sanitize_input($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and store user inputs to prevent XSS
    $_SESSION['event_name'] = sanitize_input($_POST['event_name']);
    $_SESSION['event_date'] = sanitize_input($_POST['event_date']);
    $_SESSION['event_time'] = sanitize_input($_POST['event_time']);
    $_SESSION['event_location'] = sanitize_input($_POST['event_location']);
    $_SESSION['event_description'] = sanitize_input($_POST['event_description']);
    $_SESSION['event_capacity'] = sanitize_input($_POST['event_capacity']);

    // Redirect after form submission
    header('Location: create-event-banner.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="../style/output.css" rel="stylesheet">
    <title>Create Event - Details</title>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <!-- Main Container -->
    <div class="bg-white p-10 rounded-lg shadow-lg max-w-4xl w-full">

        <!-- Progress Tracker -->
        <div class="progress-tracker mb-8">
            <ul class="flex items-center justify-between">
                <li class="active-step flex-1 text-center relative">
                    <span class="step">1</span>
                    <p class="step-label">Enter Event Details</p>
                </li>
                <li class="flex-1 text-center relative">
                    <span class="step">2</span>
                    <p class="step-label">Upload Banner</p>
                </li>
                <li class="flex-1 text-center relative">
                    <span class="step">3</span>
                    <p class="step-label">Review</p>
                </li>
            </ul>
            <div class="progress-bar bg-gray-300 w-full h-1 rounded-full relative">
                <div class="active-bar bg-slate-800 h-1 rounded-full" style="width: 25%;"></div>
            </div>
        </div>

        <form action="create-event-details.php" method="POST" class="space-y-6">

            <div>
                <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name:</label>
                <input type="text" name="event_name" id="event_name" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <div>
                <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date:</label>
                <input type="date" name="event_date" id="event_date" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <div>
                <label for="event_time" class="block text-sm font-medium text-gray-700">Event Time:</label>
                <input type="time" name="event_time" id="event_time" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <div>
                <label for="event_location" class="block text-sm font-medium text-gray-700">Location:</label>
                <input type="text" name="event_location" id="event_location" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <div>
                <label for="event_description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea name="event_description" id="event_description" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800"></textarea>
            </div>

            <div>
                <label for="event_capacity" class="block text-sm font-medium text-gray-700">Max Capacity:</label>
                <input type="number" name="event_capacity" id="event_capacity" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <div class="flex justify-between mt-6">
                <a href="../admin-dashboard/admin-dashboard-index.php"
                   class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                    Back to Dashboard
                </a>
                <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-lg hover:bg-slate-700 transition">
                    Next
                </button>
            </div>

        </form>
    </div>

</body>
</html>
