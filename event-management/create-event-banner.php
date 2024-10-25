<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['event_banner'])) {
    $target_dir = "../uploads/banner/";

    // Sanitize the file name
    $file_name = basename($_FILES["event_banner"]["name"]);
    $file_name = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $file_name); // Allow only certain characters
    $target_file = $target_dir . $file_name;

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file is an actual image
    $check = getimagesize($_FILES["event_banner"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists (optional, handle appropriately)
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (limit to 2MB)
    if ($_FILES["event_banner"]["size"] > 2000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    $allowed_types = ['jpg', 'png', 'jpeg', 'gif'];
    if (!in_array($imageFileType, $allowed_types)) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Rename the file to avoid conflicts (timestamp + sanitized file name)
        $new_file_name = time() . "_" . $file_name;
        $final_path = $target_dir . $new_file_name;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES["event_banner"]["tmp_name"], $final_path)) {
            // Store only the file name in session (to save in the database later)
            $_SESSION['event_banner'] = htmlspecialchars($new_file_name, ENT_QUOTES, 'UTF-8');

            // Redirect to the review page
            header('Location: create-event-review.php');
            exit();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="../style/output.css" rel="stylesheet">
    <title>Upload Event Banner</title>
    <style>
        /* Adjust the form layout */
        form {
            width: 100%;
            background-color: white;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        /* Styling the form inputs */
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        input[type="file"] {
            display: block;
            padding: 10px;
            margin-bottom: 20px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #1d4ed8;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
            width: 100%;
        }

        button:hover {
            background-color: #3b82f6;
        }

        .progress-tracker {
            margin-bottom: 40px; 
        }
    </style>

</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">

    <div class="bg-white p-10 rounded-lg shadow-lg max-w-2xl min-h-screen/2 w-full">
        <!-- Progress Tracker -->
        <div class="progress-tracker mb-8">
            <ul class="flex items-center justify-between">
                <li class="flex-1 text-center relative">
                    <span class="step">1</span>
                    <p class="step-label">Edit Event Details</p>
                </li>
                <li class="active-step flex-1 text-center relative">
                    <span class="step">2</span>
                    <p class="step-label">Upload Banner</p>
                </li>
                <li class="flex-1 text-center relative">
                    <span class="step">3</span>
                    <p class="step-label">Review</p>
                </li>
            </ul>
            <div class="progress-bar bg-gray-300 w-full h-1 rounded-full relative">
                <div class="active-bar bg-slate-800 h-1 rounded-full" style="width:69%"></div>
            </div>
        </div>

        <!-- Upload Form -->
        <h1 class="text-2xl font-bold text-slate-800 mb-6 text-center">Upload Event Banner</h1>

        <form action="create-event-banner.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div>
                <label for="event_banner" class="block text-lg font-semibold mb-2">Select Banner Image:</label>
                <input type="file" name="event_banner" id="event_banner" required class="w-full border border-gray-300 p-2 rounded-md">
            </div>

            <!-- Submit Button -->
            <div class="mt-6">
                <button type="submit" class="bg-slate-800 text-white w-full py-2 rounded-lg hover:bg-slate-700 transition">Next</button>
            </div>
        </form>

        <!-- Back Button -->
        <form action="create-event-details.php" method="GET" class="mt-4">
            <button type="submit" class="bg-gray-500 text-white w-full py-2 rounded-lg hover:bg-gray-600 transition">Back to Event Details</button>
        </form>
    </div>

</body>
</html>
