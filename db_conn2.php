<?php
$host = 'localhost';
$dbname = 'utslec';
$username = 'root'; 
$password = ''; 

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!$conn) {
    echo "Connection Failed";
}