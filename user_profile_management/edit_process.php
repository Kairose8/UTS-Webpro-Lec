<?php 

include '../db_conn.php';

$user = $_GET['id_user'];

$nama = $_POST['nama'];
$email = $_POST['email'];
$password = $_POST['password'];
$foto = $_FILES['profile_pic'];;

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
$data = [$nama, $email, $password, $filename, $user];

if ($stmt->execute($data)) {
    echo "Data berhasil diperbarui.";
}