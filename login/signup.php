<?php
session_start(); // Start the session

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../style/background.css"> 
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <a href="admin_login.php" class="absolute top-5 right-5 bg-indigo-900 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-md">
        Admin Page
    </a>
    <main class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <p class="text-2xl font-bold text-center mb-6">Sign Up</p>
            <form action="signup_process.php" method="post" enctype="multipart/form-data">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div style="background-color: #d4edda; color: #155724; border-color: #c3e6cb; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                        <?php echo $_SESSION['success_message']; ?>
                        <?php unset($_SESSION['success_message']);?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['error_message'])): ?>
                    <div style="background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                        <?php echo $_SESSION['error_message']; ?>
                        <?php unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>
                <div class="mb-4">
                    <input required type="text" name="username" placeholder="Nama" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <input required type="email" name="email" placeholder="Email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <input required type="password" name="password" placeholder="Password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <input required type="password" name="confirm_password" placeholder="Confirm Password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <input required type="text" name="favorite" placeholder="Favorite Thing" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-5">
                    <label for="profilepic" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                    <input type="file" id="profilepic" name="profilepic" class="border border-gray-300 rounded-md">
                </div>
                <div class="flex justify-center mb-4">
                    <button type="submit" class="bg-indigo-900 hover:bg-indigo-800 w-28 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Sign Up</button>
                </div>
            </form>
        

        <div class="flex justify-center text-lg">
            <a href="login.php" class="text-indigo-900 hover:cursor-pointer hover:text-blue-700 pr-2">Login di sini</a>
            <a href="forget_password.php" class="text-indigo-900 hover:cursor-pointer hover:text-blue-700">| Lupa password</a>
        </div>
    </main>
</body>
</html>
