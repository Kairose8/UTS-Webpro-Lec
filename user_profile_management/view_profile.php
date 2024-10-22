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
            <h1 class="text-3xl font-bold mb-4 text-gray-800">Account Information</h1>
            <hr class="mb-6"/>

            <!-- Profile Photo Section -->
            <h2 class="text-xl font-semibold mb-2 text-gray-700">Profile Photo</h2>
            <img src="<?= !empty($profile['profile_pic']) ? '../uploads/profile_photo/' . htmlspecialchars($profile['profile_pic']) : '../assets/default_profile.jpg' ?>" 
                 alt="Your Profile Photo" 
                 class="rounded-full w-64 h-64 object-cover mb-6 mx-auto">

            <!-- Profile Information Section -->
            <h2 class="text-xl font-semibold mb-1 text-gray-700">Profile Information</h2>
            <p class="text-lg text-gray-600"><strong>Username:</strong> <?= htmlspecialchars($profile['nama']) ?></p>
            <p class="text-lg text-gray-600 mb-6"><strong>Email:</strong> <?= htmlspecialchars($profile['email']) ?></p>

            <!-- Edit Profile Link -->
            <a href="edit_profile.php?id_user=<?= htmlspecialchars($profile['id_user'])?>" 
               class="inline-block bg-slate-800 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-slate-700 transition duration-300">
               Edit Profile
            </a>
        </div>

        <!-- Event History Container -->
        <div class="bg-white p-8 rounded-lg shadow-lg flex-1">
            <h1 class="text-3xl font-bold mb-4 text-gray-800">Event History</h1>
            <hr class="border-gray-300 mb-6"/>

            <div class="h-96 overflow-y-auto">
                <!-- Event List -->
                <ul class="space-y-6">
                    <?php foreach ($event as $event): ?>
                        <li class="text-base text-gray-700 bg-gray-50 p-4 rounded-lg shadow-sm">
                            <strong class="block text-gray-900 font-semibold mb-1">Event Name:</strong> 
                            <?= htmlspecialchars($event['nama_event']) ?> 
                            <br>
                            <strong class="block text-gray-900 font-semibold mt-2">Date:</strong> 
                            <?= htmlspecialchars($event['tanggal_formated']) ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="text-right mt-8">
                <a href="../user_management/user_history.php?id_user=<?= htmlspecialchars($profile['id_user'])?>" 
                class="inline-block bg-slate-800 text-white font-semibold py-2 px-6 rounded-lg shadow-lg hover:bg-slate-700 transition-all duration-300">
                More Detail
                </a>
            </div>
        </div>

    </div>
</body>
</html>

</html>