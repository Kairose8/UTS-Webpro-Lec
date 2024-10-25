<?php
session_start();
include '../db_conn.php'; // Adjust the path if necessary

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the submitted data
    $username = $_POST['username'] ?? ''; 
    $email = $_POST['email'] ?? '';
    $favorite = $_POST['favorite'] ?? '';

    try {
        // Prepare and execute the query to find the user
        $stmt = $conn->prepare("SELECT * FROM user WHERE nama = :nama AND email = :email AND favorite_thing = :favorite");
        $stmt->bindValue(':nama', $username); 
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':favorite', $favorite);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch();

        // Check if user was found
        if ($user) {
            $_SESSION['id_user'] = $user['id_user']; // Save user id in session for later use
            header('Location: forget_password_newpass.php');
            exit;
        } else {
            // Redirect back to the form with an error
            header('Location: forget_password.php?error=Invalid submitted data');
            exit;
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit; // Stop execution to see the error message
    }
} else {
    header('Location: forget_password.php'); // Redirect if accessed directly
    exit;
}
?>
