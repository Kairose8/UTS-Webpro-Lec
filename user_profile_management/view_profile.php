<?php 
$user = $_GET['id_user'];

include '../db_conn.php';

$sql_profile = "SELECT * FROM user WHERE id_user = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->execute([$user]);

$profile = $stmt_profile->fetch(PDO::FETCH_ASSOC);

$sql_events = "SELECT user.id_user, event.nama_event, DATE_FORMAT(event.tanggal, '%M %d, %Y') as tanggal_formated
        FROM user
        LEFT JOIN daftar ON user.id_user = daftar.id_user
        LEFT JOIN event ON daftar.id_event = event.id_event
        WHERE user.id_user = ?;
        ";
$stmt = $conn->prepare($sql_events);
$stmt->execute([$user]);

$event = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="flex flex-col lg:flex-row gap-8 max-w-5xl w-full">

        <!-- Profile Container -->
        <div class="bg-white p-8 rounded-lg shadow-lg flex-1">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Account Information</h1>
            <hr class="mb-6"/>

            <!-- Profile Photo Section -->
            <h2 class="text-xl font-semibold mb-2 text-gray-700">Profile Photo</h2>
            <img src="../uploads/profile_photo/<?= htmlspecialchars($profile['profile_pic']) ?>" 
                 alt="Your Profile Photo" 
                 class="rounded-full w-64 h-64 object-cover mb-6 mx-auto">

            <!-- Profile Information Section -->
            <h2 class="text-xl font-semibold mb-4 text-gray-700">Profile Information</h2>
            <p class="text-lg text-gray-600"><strong>Username:</strong> <?= htmlspecialchars($profile['nama']) ?></p>
            <p class="text-lg text-gray-600 mb-6"><strong>Email:</strong> <?= htmlspecialchars($profile['email']) ?></p>

            <!-- Edit Profile Link -->
            <a href="edit_profile.php?id_user=<?= htmlspecialchars($profile['id_user'])?>" 
               class="inline-block bg-blue-500 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-blue-600 transition duration-300">
               Edit Profile
            </a>
        </div>

        <!-- Event History Container -->
        <div class="bg-white p-8 rounded-lg shadow-lg flex-1">
            <h1 class="text-2xl font-bold mb-4 text-gray-800">Event History</h1>
            <hr class="mb-6"/>

            <!-- Event List -->
            <ul class="space-y-4">
                <?php foreach ($event as $event): ?>
                    <li class="text-lg text-gray-600">
                        <strong>Event Name:</strong> <?= htmlspecialchars($event['nama_event']) ?> <br>
                        <strong>Date:</strong> <?= htmlspecialchars($event['tanggal_formated']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>

        </div>
    </div>
</body>
</html>

</html>