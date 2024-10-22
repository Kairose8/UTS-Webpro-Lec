<?php
session_start();
include "db_conn2.php";

if(isset($_POST['email']) && isset($_POST['password'])) {
    
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data); // Corrected function name
        $data = htmlspecialchars($data);
        return $data; // Corrected to return $data
    }

    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if(empty($email)) {
        header("Location: index.php?error=Email is required");
        exit();
    }
    elseif(empty($password)) {
        header("Location: index.php?error=Password is required");
        exit();
    }

    // Correct SQL query
    $sql = "SELECT * FROM users WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        // Use password_verify() if the password is hashed
        if($row['email'] === $email && password_verify($password, $row['password'])) {
            echo "Logged In";
            $_SESSION['user_name'] = $row['user_name'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['id'] = $row['id'];
            header("Location: home.php");
            exit();
        }
        else {
            header("Location: index.php?error=Incorrect Email or Password");
            exit();
        }
    }
    else {
        header("Location: index.php?error=Incorrect Email or Password");
        exit();
    }
}
?>
