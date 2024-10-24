<?php 

include '../db_conn.php';

$user = $_GET['id_user'];

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$foto = $_FILES['profile_pic'];

if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT); 
} else {
    $sql = "SELECT password FROM user WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $hashed_password = $result['password']; 
}

$filename = null;
if ($foto && $foto['error'] == UPLOAD_ERR_OK) {
    $filename = $foto['name'];
    $temp_file = $foto['tmp_name'];

    $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $allowed_extensions = ['jpg', 'jpeg', 'png', 'svg', 'webp', 'bmp', 'gif'];

    if (in_array($file_ext, $allowed_extensions)) {
        $upload_path = "../uploads/profile_photo/{$filename}";

        if (!move_uploaded_file($temp_file, $upload_path)) {
            echo "Terjadi kesalahan saat mengunggah file.";
            exit;
        }
    } else {
        echo "Anda hanya bisa upload file gambar dengan format yang diizinkan.";
        exit;
    }
} else {
    $sql = "SELECT profile_pic FROM user WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user]);
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    $filename = $profile['profile_pic']; 
}

$sql = "UPDATE user SET nama = ?, email = ?, password = ?, profile_pic = ? WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$data = [$nama, $email, $hashed_password, $filename, $user];

if ($stmt->execute($data)) { ?>
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
<?php } else {
    echo "Terjadi kesalahan saat memperbarui data.";
}
?>
