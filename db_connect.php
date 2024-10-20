<?php 
$user = $_GET['id_user'];

$dsn = "mysql:host=localhost;dbname=utslec";
$kunci = new PDO($dsn, "root", "");

$sql = "SELECT * FROM user WHERE id_user = ?";
$stmt = $kunci->prepare($sql);
$stmt->execute([$user]);

$hasil = $stmt->fetch(PDO::FETCH_ASSOC);
?>