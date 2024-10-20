<?php 
$user = $_GET['id_user'];

$dsn = "mysql:host=localhost;dbname=utslec";
$kunci = new PDO($dsn, "root", "");
