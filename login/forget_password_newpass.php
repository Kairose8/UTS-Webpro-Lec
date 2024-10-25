<?php
session_start();
include '../db_conn.php'; // Adjust the path if necessary

// Check if user is logged in (i.e., if they passed the previous step)
if (!isset($_SESSION['id_user'])) {
    header('Location: forget_password.php'); // Redirect if accessed directly
    exit;
}

// Handle form submission for new password
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $id_user = $_SESSION['id_user']; // Get user id from session

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    try {
        // Update the password in the database
        $stmt = $conn->prepare("UPDATE user SET password = :password WHERE id_user = :id");
        $stmt->bindValue(':password', $hashed_password);
        $stmt->bindValue(':id', $id_user);
        $stmt->execute();

        // Password updated, clear the session and redirect to login
        session_unset();
        session_destroy();
        header('Location: login.php?message=Password updated successfully');
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enter New Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <a href="admin_login.php" class="absolute top-5 right-5 bg-indigo-900 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-md">
        Admin Dashboard
    </a>
    <main class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <p class="text-2xl font-bold text-center mb-6">Enter New Password</p>
        <form action="forget_password_newpass.php" method="post">
            <div class="mb-4">
                <input required type="password" name="new_password" placeholder="New Password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-center mb-4">
                <button type="submit" class="bg-indigo-900 hover:bg-indigo-800 w-38 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Change Password</button>
            </div>
        </form>
        <div class="text-center">
            <a href="login.php" class="text-indigo-900 hover:cursor-pointer">Back to Login</a>
        </div>
    </main>
</body>
</html>
