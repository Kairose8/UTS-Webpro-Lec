<?php
include '../db_conn.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $favorite = $_POST['favorite'];

    // Default profile picture
    $profilePic = 'default/default-profile-picture.jpg';

    // Check if a profile picture was uploaded
    if (isset($_FILES['profilepic']) && $_FILES['profilepic']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profilepic']['tmp_name'];
        $fileName = $_FILES['profilepic']['name'];
        $fileSize = $_FILES['profilepic']['size'];
        $fileType = $_FILES['profilepic']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Specify allowed file types
        $allowedFileTypes = array('jpg', 'gif', 'png', 'jpeg');

        if (in_array($fileExtension, $allowedFileTypes) && $fileSize < 5000000) { // Limit to 5MB
            $uploadFileDir = '../uploads/profile_photo/';
            $dest_path = $uploadFileDir . $fileName;

            // Move the file to the upload directory
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $profilePic = $fileName; // Save only the file name
            } else {
                echo 'There was an error moving the uploaded file.';
            }
        } else {
            echo 'Upload failed. Only JPG, GIF, PNG files under 5MB are allowed.';
        }
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare and execute the insert query
        $stmt = $conn->prepare("INSERT INTO user (nama, email, password, favorite_thing, profile_pic) VALUES (:username, :email, :password, :favorite, :profilePic)");
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':favorite', $favorite);
        $stmt->bindParam(':profilePic', $profilePic);

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Registration successful!";
            header("Location: signup.php");
            exit();
        } else {
            echo "Error: Could not execute the statement.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }

    // Close the database connection
    $conn = null;
}
?>
