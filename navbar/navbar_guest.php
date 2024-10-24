<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="../style/output.css" rel="stylesheet"> <!-- Include your CSS -->
</head>
<body>
    <header class="flex justify-between items-center p-4 bg-white shadow">
        <h1 class="text-2xl font-bold ml-7">Browse Events</h1>
        <button id="toggle-sidebar" onclick="toggleSidebar()" class="top-5 left-4 z-30 absolute">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
            </svg>
        </button>
    </header>

    <nav>
        <!-- Sidebar -->
        <div id="sidebar" class="fixed top-0 left-0 w-64 h-full bg-gray-800 text-white transition-transform duration-300 ease-in-out transform -translate-x-full z-20">
            <button onclick="toggleSidebar()" class="p-4 text-right">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <h2 class="text-center text-lg font-bold">Filter Events</h2>
            <!-- Filter by Date -->
            <div class="p-4">
                <label for="start-date">Start Date:</label>
                <input type="date" id="start-date" name="start-date" class="block w-full mt-1 text-black" />
                <label for="end-date">End Date:</label>
                <input type="date" id="end-date" name="end-date" class="block w-full mt-1 text-black" />
            </div>
            <!-- Apply and Reset Filter Buttons -->
            <div class="p-4">
                <button onclick="applyFilters()" class="bg-slate-800 p-2 rounded w-full">Apply Filters</button>
                <button onclick="resetFilters()" class="bg-red-600 p-2 rounded w-full mt-2">Reset Filters</button>
            </div>
        </div>

        <!-- Login Button -->
        <div class="absolute top-4 right-4">
            <a href="./login/login.php" class="bg-slate-800 text-white font-semibold py-2 px-4 rounded-lg">Login</a>
        </div>
    </nav>

    <script>
        function toggleSidebar() {
            document.getElementById("sidebar").classList.toggle("-translate-x-full");
            document.getElementById("toggle-sidebar").classList.toggle("hidden");
        }

        function applyFilters() {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            let url = '../index.php?';

            if (startDate) {
                url += 'start-date=' + encodeURIComponent(startDate) + '&';
            }
            if (endDate) {
                url += 'end-date=' + encodeURIComponent(endDate) + '&';
            }

            window.location.href = url;
        }

        function resetFilters() {
            window.location.href = '../index.php';
        }
    </script>
</body>
</html>
