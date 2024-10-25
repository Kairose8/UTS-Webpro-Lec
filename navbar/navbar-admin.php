<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="../style/output.css" rel="stylesheet">
</head>
<body>

<!-- Admin Navbar -->
<nav class="bg-gray-800 p-4 shadow-md flex justify-around items-center">
    <!-- Left side: Website Logo and Name -->
    <div class="flex items-center">
        <a href="../admin-dashboard/admin-dashboard-index.php" class="flex items-center">
            <img src="../assets/logo.png" alt="Website Logo" class="h-10 w-10 mt-3.5">
            <span class="ml-2 text-white font-bold text-2xl">Eventure</span>
        </a>
    </div>

    <!-- Right side: Admin Greeting -->
    <div class="text-white text-2xl">
        Hello, Admin!
    </div>
    <div class="flex items-center">
            <a href="../login/logout_process.php" class="ml-5 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                Logout
            </a>
    </div>
</nav>

</body>
</html>
