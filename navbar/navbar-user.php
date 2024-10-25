<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include './db_conn.php'; // Database connection

// Check if user is logged in
$isLoggedIn = isset($_SESSION['id_user']);
$id_user = $isLoggedIn ? (int)$_SESSION['id_user'] : null;
$user = null;

if ($isLoggedIn) {
    // Fetch user data for the profile picture and name
    $stmt = $conn->prepare("SELECT nama, profile_pic FROM user WHERE id_user = :id_user");
    $stmt->execute(['id_user' => $id_user]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!-- Navbar -->
<div class="flex justify-around items-center bg-gray-800 p-4 text-white">
    <div class="flex items-center">
        <img src="./assets/logo.png" alt="Website Logo" class="w-10 h-10">
        <span class="ml-4 text-2xl font-bold">Eventure</span>
    </div>
    
    <div class="flex items-center">
        <?php if ($isLoggedIn && $user): ?>
            <span>Hello, <?= htmlspecialchars($user['nama']) ?></span>
            <a href="../user_profile_management/view_profile.php?id_user=<?= htmlspecialchars($id_user) ?>">
                <img src="<?= htmlspecialchars($user['profile_pic']) ?>" alt="Profile Picture" class="w-10 h-10 rounded-full ml-4">
            </a>
            <a href="../login/logout_process.php" class="ml-5 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                Logout
            </a>
        <?php else: ?>
            <a href="../login/login.php" class="ml-5 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Login
            </a>
        <?php endif; ?>
    </div>
</div>
