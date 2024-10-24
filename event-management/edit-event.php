<?php
include '../db_conn.php'; // Database connection

// Fetch the event details based on the event ID (GET parameter)
if (isset($_GET['id_event'])) {
    $id_event = $_GET['id_event'];

    // Prepare a statement to fetch event data
    $stmt = $conn->prepare("SELECT * FROM event WHERE id_event = :id_event");
    $stmt->execute(['id_event' => $id_event]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "Event not found.";
        exit;
    }
} else {
    echo "Invalid event ID.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Event</title>
    <link href="../style/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-10 flex justify-center items-center min-h-screen">

    <!-- Main Container -->
    <div class="bg-white p-10 rounded-lg shadow-lg max-w-4xl w-full">

        <!-- Header -->
        <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">Edit Event: <?php echo $event['nama_event']; ?></h1>

        <!-- Edit Event Form -->
        <form action="edit-event-insertdb.php" method="POST" enctype="multipart/form-data" class="space-y-6">

            <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">

            <!-- Event Name -->
            <div>
                <label for="event_name" class="block text-sm font-medium text-gray-700">Event Name:</label>
                <input type="text" name="event_name" id="event_name" value="<?php echo $event['nama_event']; ?>" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <!-- Event Date -->
            <div>
                <label for="event_date" class="block text-sm font-medium text-gray-700">Event Date:</label>
                <input type="date" name="event_date" id="event_date" value="<?php echo $event['tanggal']; ?>" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <!-- Event Location -->
            <div>
                <label for="event_location" class="block text-sm font-medium text-gray-700">Location:</label>
                <input type="text" name="event_location" id="event_location" value="<?php echo $event['lokasi']; ?>" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <!-- Event Description -->
            <div>
                <label for="event_description" class="block text-sm font-medium text-gray-700">Description:</label>
                <textarea name="event_description" id="event_description" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800"><?php echo $event['deskripsi']; ?></textarea>
            </div>

            <!-- Max Capacity -->
            <div>
                <label for="event_capacity" class="block text-sm font-medium text-gray-700">Max Capacity:</label>
                <input type="number" name="event_capacity" id="event_capacity" value="<?php echo $event['jumlah_maksimum']; ?>" required
                    class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
            </div>

            <!-- Event Status -->
            <div>
                <label for="event_status" class="block text-sm font-medium text-gray-700">Status:</label>
                <select name="event_status" id="event_status"
                        class="mt-1 block w-full p-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-slate-800 focus:border-slate-800">
                    <option value="Open" <?php if ($event['status'] == 'Open') echo 'selected'; ?>>Open</option>
                    <option value="Closed" <?php if ($event['status'] == 'Closed') echo 'selected'; ?>>Closed</option>
                    <option value="Cancelled" <?php if ($event['status'] == 'Cancelled') echo 'selected'; ?>>Cancelled</option>
                </select>
            </div>

            <!-- Update Event Banner -->
            <div class="mt-6">
                <h3 class="text-lg font-bold mb-2">Update Event Banner</h3>
                <label for="event_banner" class="block text-sm font-medium text-gray-700">Upload New Banner (leave blank if no change):</label>
                <input type="file" name="event_banner" id="event_banner" class="mt-1 block w-full p-2 border border-gray-300 rounded-lg shadow-sm">
            </div>

            <!-- Submit Button -->
            <div class="flex justify-between mt-6">
                <form action="edit-event-delete.php" method="POST" class="flex justify-between items-center">
                    <input type="hidden" name="id_event" value="<?php echo $event['id_event']; ?>">
                    <button type="submit" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                        Delete Event
                    </button>
                </form>
                <button type="submit" class="bg-green-800 text-white px-6 py-2 rounded-lg hover:bg-slate-700 transition">
                    Update Event
                </button>

                <!-- Delete Event Section -->
            </div>
        </form>


        <!-- Back to Dashboard -->
        <div class="mt-8 flex justify-center">
            <form action="../admin-dashboard/admin-dashboard-index.php" method="GET">
                <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                    Back to Dashboard
                </button>
            </form>
        </div>

    </div>

</body>
</html>
