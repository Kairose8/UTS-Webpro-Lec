<?php
session_start();

// Function to sanitize output data (against XSS)
function sanitize_output($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="../style/output.css" rel="stylesheet">
    <title>Review Event</title>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-10 rounded-lg shadow-lg max-w-4xl w-full">
        <!-- Progress Tracker -->
        <div class="progress-tracker mb-8">
            <ul class="flex items-center justify-between">
                <li class="flex-1 text-center relative">
                    <span class="step">1</span>
                    <p class="step-label">Edit Event Details</p>
                </li>
                <li class="flex-1 text-center relative">
                    <span class="step">2</span>
                    <p class="step-label">Upload Banner</p>
                </li>
                <li class="active-step flex-1 text-center relative">
                    <span class="step">3</span>
                    <p class="step-label">Review</p>
                </li>
            </ul>
            <div class="progress-bar bg-gray-300 w-full h-1 rounded-full relative">
                <div class="active-bar bg-slate-800 h-1 rounded-full" style="width:100%"></div>
            </div>
        </div>

        <!-- Review Section -->
        <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">Review Event Details</h1>

        <!-- Event Information -->
        <div class="space-y-4">
            <p><strong class="font-semibold">Event Name:</strong> <?php echo sanitize_output($_SESSION['event_name']); ?></p>
            <p><strong class="font-semibold">Event Date:</strong> <?php echo sanitize_output($_SESSION['event_date']); ?></p>
            <p><strong class="font-semibold">Event Time:</strong> <?php echo sanitize_output($_SESSION['event_time']); ?></p>
            <p><strong class="font-semibold">Event Location:</strong> <?php echo sanitize_output($_SESSION['event_location']); ?></p>
            <p><strong class="font-semibold">Event Description:</strong> <?php echo sanitize_output($_SESSION['event_description']); ?></p>
            <p><strong class="font-semibold">Max Capacity:</strong> <?php echo sanitize_output($_SESSION['event_capacity']); ?></p>
        </div>

        <!-- Event Banner -->
        <div class="mt-6">
            <h2 class="text-xl font-bold mb-2">Event Banner</h2>
            <img src="<?php echo '../uploads/banner/' . sanitize_output($_SESSION['event_banner']); ?>" alt="Event Banner" class="max-w-md border rounded-lg shadow-md">
        </div>

        <!-- Buttons -->
        <div class="flex justify-between mt-8">
            <form action="create-event-banner.php" method="GET">
                <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                    Back to Change Banner
                </button>
            </form>

            <form action="create-event-insertdb.php" method="POST">
                <button type="submit" class="bg-slate-800 text-white px-6 py-2 rounded-lg hover:bg-slate-700 transition">
                    Confirm and Create Event
                </button>
            </form>
        </div>
    </div>
</body>
</html>
