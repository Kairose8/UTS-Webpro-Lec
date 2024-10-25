<?php
session_start();
include '../db_conn.php'; // Database connection

if (!isset($_SESSION['id_user'])) {
    // Redirect to login page if user is not logged in
    header("Location: ../login/login.php");
    exit();
}

// Assuming user is logged in and user ID is available in session
$id_user = isset($_SESSION['id_user']) ? (int)$_SESSION['id_user'] : null; // Cast to integer for safety

// Get event ID from query parameters and ensure it's an integer
$id_event = isset($_GET['id_event']) ? (int)$_GET['id_event'] : null;

if (!$id_event) {
    echo "Invalid event ID.";
    exit;
}

// Fetch event details from database with prepared statements
$stmt = $conn->prepare("SELECT * FROM event WHERE id_event = :id_event");
$stmt->execute(['id_event' => $id_event]);
$event = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit;
}

// Check if user is already registered, using prepared statements
$checkRegistration = $conn->prepare("SELECT * FROM daftar WHERE id_event = :id_event AND id_user = :id_user");
$checkRegistration->execute(['id_event' => $id_event, 'id_user' => $id_user]);
$userRegistered = $checkRegistration->fetch();

// Handle event registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    // Check if capacity is full
    if ($event['jumlah_sekarang'] >= $event['jumlah_maksimum']) {
        // Update event status to 'Closed' if capacity reached
        $updateStatus = $conn->prepare("UPDATE event SET status = 'Closed' WHERE id_event = :id_event");
        $updateStatus->execute(['id_event' => $id_event]);
        $capacityFull = true;
    } else {
        // Register user and increase the capacity, using prepared statements
        $registerUser = $conn->prepare("INSERT INTO daftar (id_event, id_user) VALUES (:id_event, :id_user)");
        $registerUser->execute(['id_event' => $id_event, 'id_user' => $id_user]);

        // Update the jumlah_sekarang in the event table
        $updateCapacity = $conn->prepare("UPDATE event SET jumlah_sekarang = jumlah_sekarang + 1 WHERE id_event = :id_event");
        $updateCapacity->execute(['id_event' => $id_event]);

        // $_SESSION['message'] = 'You have successfully registered for the event.';
        header("Location: event-registration-success.php?id_event=" . urlencode($id_event));
        exit();
    }
}

// Handle event cancellation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel'])) {
    // Remove user from daftar and decrease capacity, using prepared statements
    $cancelRegistration = $conn->prepare("DELETE FROM daftar WHERE id_event = :id_event AND id_user = :id_user");
    $cancelRegistration->execute(['id_event' => $id_event, 'id_user' => $id_user]);

    // Update the jumlah_sekarang in the event table
    $updateCapacity = $conn->prepare("UPDATE event SET jumlah_sekarang = jumlah_sekarang - 1 WHERE id_event = :id_event");
    $updateCapacity->execute(['id_event' => $id_event]);

    $_SESSION['message'] = 'You have cancelled your registration.';
    header("Location: event-register.php?id_event=" . urlencode($id_event));
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="../style/output.css" rel="stylesheet">
    <title>Event Registration</title>
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen text-lg">
    <div class="bg-white p-10 rounded-lg shadow-lg max-w-4xl w-full mt-8">
        <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">Event Registration</h1>

        <div class="h-32 w-full">
            <img src="<?= htmlspecialchars($event['banner'], ENT_QUOTES, 'UTF-8') ?>" alt="Event Banner" class="w-full h-full object-cover">
        </div>

        <!-- Event Information -->
        <div class="space-y-4 mt-6">
            <p><strong class="font-semibold">Event Name:</strong> <?= htmlspecialchars($event['nama_event'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong class="font-semibold">Event Date:</strong> <?= htmlspecialchars($event['tanggal'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong class="font-semibold">Event Time:</strong> <?= htmlspecialchars($event['waktu'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong class="font-semibold">Event Location:</strong> <?= htmlspecialchars($event['lokasi'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong class="font-semibold">Event Description:</strong> <?= htmlspecialchars($event['deskripsi'], ENT_QUOTES, 'UTF-8') ?></p>
            <p><strong class="font-semibold">Registrants:</strong> <?= htmlspecialchars($event['jumlah_sekarang'], ENT_QUOTES, 'UTF-8') . '/' . htmlspecialchars($event['jumlah_maksimum'], ENT_QUOTES, 'UTF-8') ?></p>
        </div>

        <?php if ($event['jumlah_sekarang'] >= $event['jumlah_maksimum']): ?>
            <!-- Capacity Full Message -->
            <div class="text-red-600 font-bold mt-6">
                This event has reached its maximum capacity. You cannot register.
            </div>
            <div class="flex justify-between mt-8">
                <!-- Back Button -->
                <form action="../index.php" method="GET">
                    <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        Back to Event Browsing
                    </button>
                </form>
        <?php else: ?>
            <!-- Registration or Cancel Button -->
            <div class="flex justify-between mt-8">
                <!-- Back Button -->
                <form action="../index.php" method="GET">
                    <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                        Back to Event Browsing
                    </button>
                </form>

                <?php if (!$userRegistered): ?>
                    <form action="./event-registration.php?id_event=<?= urlencode($event['id_event']) ?>" method="POST">
                        <button type="submit" name="register" class="bg-slate-800 text-white px-6 py-2 rounded-lg hover:bg-slate-700 transition">
                            Register for Event
                        </button>
                    </form>
                <?php else: ?>
                    <form action="./event-registration.php?id_event=<?= urlencode($event['id_event']) ?>" method="POST" onsubmit="return confirmCancel();">
                        <button type="submit" name="cancel" class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                            Cancel Registration
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function confirmCancel() {
            return confirm("Are you sure you want to cancel your registration?");
        }
    </script>
</body>
</html>
