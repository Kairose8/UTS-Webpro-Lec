<?php
include '../db_conn.php';

$event = $_GET['id_event'];

// Fetch data for the event and its registrants
$sql = "SELECT user.id_user, user.nama, user.email, user.profile_pic,
        event.id_event, event.nama_event
        FROM user
        LEFT JOIN daftar ON user.id_user = daftar.id_user
        LEFT JOIN event ON daftar.id_event = event.id_event
        WHERE event.id_event = ?;";

$stmt = $conn->prepare($sql);
$stmt->execute([$event]);
$registrants = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Check if there are any registrants and event data
$data_event = !empty($registrants) ? $registrants[0] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Registrants</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-10">
        <h1 class="text-4xl font-bold text-center mb-1 ml-3">List of Registrants</h1>
        <p class="text-2xl text-center mb-8 mt-6"><?= htmlspecialchars($data_event['nama_event'] ?? 'No participants yet')?></p>

        <div class="overflow-x-auto pb-10 flex justify-center items-center">
            <?php if (!is_null($data_event)): ?>
            <table class="w-9/12 max-w-full text-center bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-800 text-center text-white">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Name</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6 text-center">Profile Picture</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrants as $peserta): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-4 px-6"><?= htmlspecialchars($peserta['id_user'])?></td>
                        <td class="py-4 px-6"><?= htmlspecialchars($peserta['nama'])?></td>
                        <td class="py-4 pl-6"><?= htmlspecialchars($peserta['email'])?></td>
                        <td class="py-4 px-6">
                            <div class="flex justify-center items-center">
                                <img src="../uploads/profile_photo/<?= htmlspecialchars($peserta['profile_pic']) ?>" 
                                     alt="Profile picture of <?= htmlspecialchars($peserta['nama']) ?>" 
                                     class="w-16 h-16 rounded-full object-cover" />
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
                <p class="text-center">No registrants found for this event.</p>
            <?php endif; ?>
        </div>
                
        <?php if (!is_null($data_event)): ?>
            <div class="flex justify-center content-center items-center mt-5">
                <a href="generate_excel.php?id_event=<?= htmlspecialchars($data_event['id_event'])?>" 
                   class="bg-slate-800 hover:bg-slate-700 text-xl text-white font-bold py-2 px-4 rounded-lg shadow-md">
                   Download .xlsx File
                </a>
            </div>
        <?php endif; ?>

        <div class="flex justify-center content-center items-center mt-5">
            <form action="../admin-dashboard/admin-dashboard-index.php" method="GET">
                <button type="submit"
                    class="bg-slate-800 text-white px-6 py-2 rounded-lg hover:bg-slate-700 transition duration-300">
                    Back to Dashboard
                </button>
            </form>
        </div>
    </div>
</body>
</html>
