<?php
include '../db_conn.php'; // Adjust the path if necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the email and password are set
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        // Prepare and execute your SQL statement
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email"); // Adjust table name as necessary
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        // Fetch the user data
        $user = $stmt->fetch(PDO::FETCH_ASSOC); // Fetch associative array
        if ($user && password_verify($password, $user['password'])) { // Assuming password is hashed
            // Start a session and set user data
            session_start();
            $_SESSION['id_user'] = $user['id_user']; // Assuming you have an 'id' field
            
            // Redirect to the event browsing page
            header('Location: ../index1.php'); // Adjusted path to the correct event-browsing page
            exit;
        } else {
            // Redirect to login page with error message
            header('Location: login.php?error=Invalid email or password');
            exit;
        }
    } catch (PDOException $e) {
        // Handle potential errors in the database operations
        echo "Error: " . $e->getMessage();
    }
}
?>
