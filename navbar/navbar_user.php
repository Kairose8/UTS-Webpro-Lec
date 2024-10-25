<?php

include './db_conn.php';

// Assuming user is logged in and you have `id_user` stored in session
$id_user = $_SESSION['id_user'] ?? null;

if ($id_user) {
    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT nama, profile_pic FROM user WHERE id_user = :id_user");
    $stmt->execute(['id_user' => $id_user]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no profile picture, set to default
    $profilePic = !empty($user['profile_pic']) ? htmlspecialchars($user['profile_pic']) : '../assets/default-profile-picture.jpg';
    $userName = htmlspecialchars($user['nama']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Navbar</title>
    <link href="../style/output.css" rel="stylesheet">
</head>
<body>

<!-- User Navbar -->
<nav class="bg-gray-800 p-4 shadow-md flex justify-between items-center">
    <!-- Left side: Website Logo and Name -->
    <div class="flex items-center">
        <img src="../assets/logo.png" alt="Website Logo" class="h-10 w-10">
        <span class="ml-2 text-white font-bold text-xl">Eventure</span>
    </div>

    <!-- Right side: User Greeting and Profile Picture -->
    <div class="flex items-center">
        <span class="text-white mr-4">Hello, <?= $userName ?></span>
        <a href="../index1.php">
            <img src="<?= $profilePic ?>" alt="Profile Picture" class="h-10 w-10 rounded-full object-cover border-2 border-white">
        </a>
    </div>
</nav>

</body>
</html>