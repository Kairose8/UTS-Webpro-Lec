<?php 
$user = $_GET['id_user'];

include '../db_conn.php';

$sql = "SELECT * FROM user WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$user]);

$profile = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .profile-pic-container {
            position: relative;
            display: inline-block;
        }
        .camera-icon {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background-color: white;
            border-radius: 50%;
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-lg w-full relative">
        <a href="view_profile.php?id_user=<?= htmlspecialchars($profile['id_user']) ?>" 
        class="absolute top-4 right-4 text-gray-700 hover:text-gray-800 transition duration-300">
            ✖ 
        </a>
        <h1 class="text-3xl font-bold mb-4 text-gray-800">Edit Account Information</h1>
        <hr class="mb-6"/>

        <!-- Profile Photo Section -->
        <h2 class="text-xl font-semibold mb-2 text-gray-700">Profile Photo</h2>
        <div class="profile-pic-container">
        <img src="../uploads/profile_photo/<?= htmlspecialchars($profile['profile_pic']) ?>"  
                alt="Your Profile Photo" 
                class="rounded-full w-64 h-64 object-cover mx-auto">
            <div class="camera-icon" onclick="document.getElementById('photo-upload').click()">
               <img src="../assets/icons8-pencil-100.png" alt="icon camera"
               class="bg-gray-900 object-cover rounded-full h-10">
               
            </div>
        </div>
        <span id="file-name" class="block mt-2 text-gray-700">No photo selected yet</span>
        
        <!-- Edit Form -->
        <form action="edit_process.php?id_user=<?= htmlspecialchars($profile['id_user'])?>" method="post" enctype="multipart/form-data" class="mt-6">
            <input type="file" id="photo-upload" name="profile_pic" class="hidden" onchange="updateFileName()" />
            <input type="hidden" name="id_user" value="<?= htmlspecialchars($profile['id_user']) ?>" />
            <div class="mb-4">
                <label class="block text-gray-700">Username:</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($profile['nama']) ?>" required class="border border-gray-300 p-2 w-full rounded" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email:</label>
                <input type="email" name="email" value="<?= htmlspecialchars($profile['email']) ?>" required class="border border-gray-300 p-2 w-full rounded" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password:</label>
                <input type="password" name="password" placeholder="****" required class="border border-gray-300 p-2 w-full rounded" />
            </div>
            <button type="submit" class="bg-slate-800 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-slate-700 transition duration-300">Save Changes</button>
        </form>
    </div>

    <script>
        function updateFileName() {
            const fileInput = document.getElementById('photo-upload');
            const fileNameDisplay = document.getElementById('file-name');

            if (fileInput.files.length > 0) {
                // Get the name of the uploaded file
                fileNameDisplay.textContent = fileInput.files[0].name;
            } else {
                fileNameDisplay.textContent = ''; // Clear if no file is selected
            }

        }
    </script>
</body>
</html>
