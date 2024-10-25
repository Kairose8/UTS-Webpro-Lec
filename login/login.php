<?php
session_start(); // Start the session

// Check if user is already logged in, if yes then redirect to the event browsing page
if (isset($_SESSION['id_user'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../style/background.css"> 
</head>
<body class="bg-custom-pattern bg-cover bg-center flex items-center justify-center h-screen">
    <a href="admin_login.php" class="absolute top-5 right-5 bg-indigo-900 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-md">
        Admin Page
    </a>
    <main class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <p class="text-2xl font-bold text-center mb-6">Login</p>
        
        <!-- Display error message if any -->
        <?php if (isset($_GET['error'])): ?>
            <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($_GET['error']) ?></p>
        <?php endif; ?>

        <form action="login_process.php" method="post">
            <div class="mb-4">
                <input required type="email" name="email" placeholder="Email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <input required type="password" name="password" placeholder="Password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-center mb-4">
                <button type="submit" class="bg-indigo-900 hover:bg-indigo-800 w-28 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Login</button>
            </div>
        </form>
        <div class="flex justify-center text-lg mt-5">
            <a href="signup.php" class="text-indigo-900 hover:cursor-pointer hover:text-blue-700 pr-2">Daftar di sini</a>
            <a href="forget_password.php" class="text-indigo-900 hover:cursor-pointer hover:text-blue-700">| Lupa Password</a>
        </div>
        <div class="flex justify-center mt-6">
            <form action="../index.php" method="GET">
                <button type="submit"
                        class="bg-slate-800 text-white px-4 py-1 rounded-lg hover:bg-slate-500 transition duration-300">
                    Browse Events as Guest
                </button>
            </form>
        </div>
    </main>
</body>
</html>
