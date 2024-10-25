<?php 
session_start();
include '../db_conn.php';

$user = $_GET['id_user'];

// Retrieve form data
$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];
$favorite_thing = $_POST['favorite_thing'] ?? ''; // optional favorite thing
$foto = $_FILES['profile_pic'];

// Password confirmation and hashing
if (!empty($password)) {
    if ($password === $confirm_password) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT); 
    } else {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Mismatch</title>
            <script src="https://cdn.tailwindcss.com"></script>
        </head>
        <body class="bg-gray-200 flex items-center justify-center h-screen">
            <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-md w-full">
                <p class="text-red-600 font-bold text-xl mb-4">Password Mismatch</p>
                <p class="text-gray-700 mb-8">The password and confirmation do not match. Please try again.</p>
                <a href="edit_profile.php?id_user=<?= htmlspecialchars($user) ?>" 
                   class="text-white bg-slate-800 hover:bg-slate-700 font-semibold py-2 px-4 rounded-lg transition duration-300">
                   Return to Edit Profile
                </a>
            </div>
        </body>
        </html>
        <?php
        exit;
    }
} else {
    // Fetch existing password if no new password is provided
    $sql = "SELECT password FROM user WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $hashed_password = $result['password'];
}

// File upload handling
$filename = null;
if ($foto && $foto['error'] == UPLOAD_ERR_OK) {
    $filename = $foto['name'];
    $temp_file = $foto['tmp_name'];

    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'svg', 'webp', 'bmp', 'gif'];

    if (in_array($file_ext, $allowed_extensions)) {
        $upload_path = "../uploads/profile_photo/{$filename}";

        if (!move_uploaded_file($temp_file, $upload_path)) {
            echo "<p style='color: red;'>Error uploading file.</p>";
            exit;
        }
    } else {
        echo "<p style='color: red;'>Invalid file format. Only images are allowed.</p>";
        exit;
    }
} else {
    // Retrieve existing profile picture if no new file is uploaded
    $sql = "SELECT profile_pic FROM user WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = $profile['profile_pic'];
}

// Update query including favorite_thing
$sql = "UPDATE user SET nama = ?, email = ?, password = ?, profile_pic = ?, favorite_thing = ? WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$data = [$nama, $email, $hashed_password, $filename, $favorite_thing, $user];

if ($stmt->execute($data)) {
    // Success message
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Updating Profile Details</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-200">
    <div class="fixed inset-0 flex items-center justify-center">
        <div class="bg-white p-16 rounded-lg shadow-lg text-center">
            <p class="text-4xl mb-10">Data Updated Successfully.</p>
            <a href="view_profile.php?id_user=<?= htmlspecialchars($user) ?>" class="text-2xl bg-slate-800 text-white font-semibold py-2 px-4 rounded-lg shadow hover:bg-slate-700 transition duration-300">Return</a>
        </div>
    </div>
    </body>
    </html>
    <?php
} else {
    echo "<p style='color: red;'>Error updating data. Please try again.</p>";
}
?>
