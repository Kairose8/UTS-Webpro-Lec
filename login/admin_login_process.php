<?php
// Start the session to store user session data
session_start();

// Include database connection
include "../db_conn.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(":username", $username);
$stmt->execute();

$user = $stmt->fetch();

if ($user) {
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: admin-dashboard-index.php");
        exit;
    } else {
        echo "Invalid Password.";
    }
}
?>