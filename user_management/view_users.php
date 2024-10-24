<?php 

include '../db_conn.php';

$sql = "SELECT user.id_user, user.nama, user.email, user.profile_pic
        FROM user;";

$stmt = $conn->prepare($sql);
$stmt->execute();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-10 ">
        <h1 class="text-3xl font-bold text-center mb-8">User Management</h1>
        
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-800 text-white text-left">
                        <th class="py-4 px-6">ID</th>
                        <th class="py-4 px-6">Name</th>
                        <th class="py-4 px-6">Email</th>
                        <th class="py-4 px-6">Profile Picture</th>
                        <th class="py-4 px-6">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($peserta = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-4 px-6"><?= htmlspecialchars($peserta['id_user'])?></td>
                        <td class="py-4 px-6"><?= htmlspecialchars($peserta['nama'])?></td>
                        <td class="py-4 px-6"><?= htmlspecialchars($peserta['email'])?></td>
                        <td class="py-4 px-6">
                        <img src="<?= htmlspecialchars($peserta['profile_pic']) ?>" 
                                alt="Profile picture of <?= htmlspecialchars($peserta['nama']) ?>" 
                                class="w-16 h-16 rounded-full object-cover"/>
                        </td>
                        <td class="py-4 px-6">
                            <a href="user_history.php?id_user=<?= htmlspecialchars($peserta['id_user'])?>" 
                               class="text-blue-500 hover:underline mr-4">
                                View History
                            </a>
                            <br/>
                            <a href="javascript:void(0)" 
                               onclick="showModal(<?= htmlspecialchars($peserta['id_user']) ?>)" 
                               class="text-red-500 hover:underline">
                                Delete User Account
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script>
        // Function to show the modal
        function showModal(userId) {
            document.getElementById('confirm-modal').classList.remove('hidden');
            document.getElementById('confirm-btn').setAttribute('onclick', `deleteUser(${userId})`);
        }

        // Function to hide the modal
        function hideModal() {
            document.getElementById('confirm-modal').classList.add('hidden');
        }

        // Function to handle deletion after confirmation
        function deleteUser(userId) {
            window.location.href = `delete_user_process.php?id_user=${userId}`;
        }
    </script>
    <!-- Confirmation Modal -->
    <div id="confirm-modal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex justify-center items-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg max-w-sm w-full">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">DELETE USER</h2>
            <p class="text-l text-gray-600 mb-6">This action cannot be undone!</p>
            <div class="flex justify-end">
                <button onclick="hideModal()" class="bg-green-800 text-gray-200 font-semibold py-2 px-4 rounded-lg mr-2">Cancel</button>
                <button id="confirm-btn" class="bg-red-500 text-white font-semibold py-2 px-4 rounded-lg">Delete</button>
            </div>
        </div>
    </div>

    <div class="flex justify-center mb-6 -mt-5 text-xl">
        <form action="../admin-dashboard/admin-dashboard-index.php" method="GET">
            <button type="submit" class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition">
                Back to Admin Dashboard
            </button>
        </form>
    </div>
</body>
</html>
