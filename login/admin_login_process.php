<?php
// Start the session to store user session data
session_start();

// Include database connection
include "../db_conn.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM admin WHERE username = :username";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":username", $username);
$stmt->execute();

$user = $stmt->fetch();

if ($user) {
    if ($password === $user['password']) {
        $_SESSION['admin'] = $user['username'];
        header("Location: ../admin-dashboard/admin-dashboard-index.php");
        exit;
    } else {
        echo "Invalid Password.";
    }
}
?>