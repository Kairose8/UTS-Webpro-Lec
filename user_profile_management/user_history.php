<?php
include '../db_conn.php';
$user = $_GET['id_user'];

$sql_profile = "SELECT * FROM user WHERE id_user = ?";
$stmt_profile = $conn->prepare($sql_profile);
$stmt_profile->execute([$user]);

$profile = $stmt_profile->fetch(PDO::FETCH_ASSOC);

$sql_events = "SELECT event.id_event, event.nama_event, DATE_FORMAT(event.tanggal, '%M %d, %Y') as tanggal_formated,
        event.waktu, event.lokasi, event.deskripsi, event.banner, event.status 
        FROM user
        LEFT JOIN daftar ON user.id_user = daftar.id_user
        LEFT JOIN event ON daftar.id_event = event.id_event
        WHERE user.id_user = ?
        ORDER BY event.tanggal;
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
    <title>User History</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-10">
        <!-- User Profile Info -->
        <div class="bg-slate-800 p-6 rounded-lg shadow-lg mb-8 flex items-center">
            <img src="../uploads/profile_photo/<?= htmlspecialchars($profile['profile_pic']) ?>" 
                 alt="Profile picture of <?= htmlspecialchars($profile['nama']) ?>" 
                 class="w-24 h-24 rounded-full object-cover mr-4">
            <div>
                <h2 class="text-2xl font-semibold text-gray-200"><?= htmlspecialchars($profile['nama']) ?></h2>
                <p class="text-gray-300"><?= htmlspecialchars($profile['email']) ?></p>
                <p class="text-gray-300"><?= htmlspecialchars($profile['id_user']) ?></p>
            </div>
        </div>

        <!-- Events Info -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Registered Events</h1>
            <hr class="border-gray-300 mb-4"/>

            <div class="space-y-4">
                <?php if (empty($event)): ?>
                    <p class="text-gray-600">No registered events found.</p>
                <?php else: ?>
                    <?php foreach ($event as $eventItem): ?>
                        <div class="bg--50 p-4 rounded-lg shadow-sm">
                            <?php if (!empty($eventItem['banner'])): ?>
                                <img src="../uploads/banner/<?= htmlspecialchars($eventItem['banner']) ?>" 
                                     alt="Banner for <?= htmlspecialchars($eventItem['nama_event']) ?>" 
                                     class="w-full h-48 object-cover rounded-lg mb-4 transition-transform duration-300 transform hover:scale-105 cursor-pointer" 
                                     onclick="openModal('<?= htmlspecialchars($eventItem['banner']) ?>')">
                            <?php endif; ?>
                            <div class="flex items-center">
                                <h3 class="text-2xl font-semibold text-gray-800 mr-2"><?= htmlspecialchars($eventItem['nama_event']) ?></h3>
                                <p class="text-gray-600">(<?= htmlspecialchars($eventItem['id_event']) ?>)</p>
                            </div>
                            <p class="text-gray-600"><?= htmlspecialchars($eventItem['tanggal_formated']) ?></p>
                            <p class="text-gray-600"><?= htmlspecialchars($eventItem['waktu']) ?></p>
                            <p class="text-gray-600"><?= htmlspecialchars($eventItem['lokasi']) ?></p>
                            <p class="text-gray-500 mt-2"><?= htmlspecialchars($eventItem['deskripsi']) ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div id="image-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center hidden z-50">
        <img id="full-image" src="" alt="Full view" class="max-w-full max-h-full rounded-lg">
        <button onclick="closeModal()" class="absolute top-5 right-5 text-white text-3xl">×</button>
    </div>

    <div class="flex justify-center mb-6 -mt-5 text-xl">
        <form action="../index.php" method="GET">
            <button type="submit" class="mr-5 bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                To Browse Events
            </button>
        </form>

        <form action="view_profile.php" method="GET">
            <input type="hidden" name="id_user" value="<?= htmlspecialchars($profile['id_user']) ?>">
            <button type="submit" class="ml-5 bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                To Profile
            </button>
        </form>
    </div>

    <script>
    function openModal(imageSrc) {
        document.getElementById('full-image').src = '../uploads/banner/' + imageSrc;
        document.getElementById('image-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('image-modal').classList.add('hidden');
    }
    </script>
</body>
</html>
