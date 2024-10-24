<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <a href="login.php" class="absolute top-5 right-5 bg-indigo-900 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded-md">
        Login Page
    </a>

    <div class="">
        <!-- <img> log in-->
    </div>
    <main class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <p id="form-title" class="text-2xl font-bold text-center mb-6">Admin</p>
        <form id="auth-form" action="admin_login_process.php" method="post" enctype="multipart/form-data">
            <!-- For Login and Sign-up: Username -->
            <div class="mb-4" id="name-field">
                <input required type="text" id="username" name="username" placeholder="Username" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- For Login and Sign-up: Password -->
            <div class="mb-4" id="password-field">
                <input required type="password" id="password" name="password" placeholder="Password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex justify-center mb-4">
                <button type="submit" id="submit-btn" class="bg-indigo-900 hover:bg-indigo-800 w-28 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Login
                </button>
            </div>
        </form>
    </main>
    
</body>
</html>