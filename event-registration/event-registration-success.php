<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Created</title>
    <link href="../style/output.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <!-- Success Message Container -->
    <div class="bg-white p-10 rounded-lg shadow-lg max-w-md w-full text-center">

        <!-- Success Icon -->
        <div class="mb-6">
            <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13l2 2l4-8m1-1a8 8 0 11-7.5 0"></path>
            </svg>
        </div>

        <!-- Success Title -->
        <h1 class="text-2xl font-bold text-green-600 mb-4">Event Created Successfully!</h1>

        <!-- Success Message -->
        <p class="text-gray-600 mb-6">Your event has been successfully added to the system.</p>

        <!-- Back to Dashboard Button -->
        <form action="../event-browsing/event-browsing.php" method="GET">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300">
                Back to Event Browsing
            </button>
        </form>
    </div>

</body>
</html>
