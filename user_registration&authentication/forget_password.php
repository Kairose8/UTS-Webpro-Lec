<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forget Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <a href="admin_login.php" class="absolute top-5 right-5 bg-indigo-900 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-md">
        Admin Dashboard
    </a>
    <main class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <p class="text-2xl font-bold text-center mb-6">Forget Password</p>
        <form action="forget_password_auth.php" method="post">
            <div class="mb-4">
                <input required type="text" name="username" placeholder="Nama" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <input required type="email" name="email" placeholder="Email" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <input required type="text" name="favorite" placeholder="Favorite Thing" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex justify-center mb-4">
                <button type="submit" class="bg-indigo-900 hover:bg-indigo-800 w-28 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">Submit</button>
            </div>
        </form>
        <p class="text-center text-gray-600">Belum punya akun? | Sudah ingat password?</p>
        <div class="flex justify-center">
            <a href="signup.php" class="text-indigo-900 hover:cursor-pointer pr-2">Daftar di sini</a>
            <a href="login.php" class="text-indigo-900 hover:cursor-pointer">| Login di sini</a>
        </div>
    </main>
</body>
</html>
