<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <a href="admin_login.php" class="absolute top-5 right-5 bg-indigo-900 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-md">
        Admin Dashboard
    </a>
    <main class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <p class="text-2xl font-bold text-center mb-6">Sign Up</p>
        <form action="signup_process.php" method="post" enctype="multipart/form-data">
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
                <input required type="text" name="favorite" placeholder="Favorite Thing" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-5">
                <label for="profilepic" class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                <input type="file" id="profilepic" name="profilepic">
            </div>
            <div class="flex justify-center mb-4">
                <button type="submit" class="bg-indigo-900 hover:bg-indigo-800 w-28 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Sign Up</button>
            </div>
        </form>
        <p class="text-center text-gray-600">Sudah punya akun? | Lupa Password?</p>
        <div class="flex justify-center">
            <a href="login.php" class="text-indigo-900 hover:cursor-pointer pr-2">Login di sini</a>
            <a href="forget_password.php" class="text-indigo-900 hover:cursor-pointer">| Ganti password</a>
        </div>
    </main>
</body>
</html>
