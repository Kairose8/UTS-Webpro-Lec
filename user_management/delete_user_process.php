<?php
include '../db_conn.php';

$user = $_GET['id_user'];

$sql = "DELETE FROM user WHERE id_user = ?;";
$stmt = $conn->prepare($sql);

if ($stmt->execute([$user])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
} else {
    echo "Error deleting user.";
}
?>
