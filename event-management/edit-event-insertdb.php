<?php
include '../db_conn.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_event = $_POST['id_event'];
    $event_name = $_POST['event_name'];
    $event_date = $_POST['event_date'];
    $event_location = $_POST['event_location'];
    $event_description = $_POST['event_description'];
    $event_capacity = $_POST['event_capacity'];
    $event_status = $_POST['event_status'];

    // Handle the banner upload (if provided)
    if (isset($_FILES['event_banner']) && $_FILES['event_banner']['error'] == 0) {
        $banner_name = $_FILES['event_banner']['name'];
        $banner_tmp = $_FILES['event_banner']['tmp_name'];
        $upload_dir = '../uploads/' . $banner_name;

        // Move the uploaded file
        move_uploaded_file($banner_tmp, $upload_dir);

        // Update event with new banner
        $stmt = $conn->prepare("UPDATE event SET nama_event = :name, tanggal = :date, lokasi = :location, deskripsi = :description, jumlah_maksimum = :capacity, status = :status, banner = :banner WHERE id_event = :id_event");
        $stmt->execute([
            'name' => $event_name,
            'date' => $event_date,
            'location' => $event_location,
            'description' => $event_description,
            'capacity' => $event_capacity,
            'status' => $event_status,
            'banner' => $upload_dir,
            'id_event' => $id_event
        ]);
    } else {
        // Update event without changing the banner
        $stmt = $conn->prepare("UPDATE event SET nama_event = :name, tanggal = :date, lokasi = :location, deskripsi = :description, jumlah_maksimum = :capacity, status = :status WHERE id_event = :id_event");
        $stmt->execute([
            'name' => $event_name,
            'date' => $event_date,
            'location' => $event_location,
            'description' => $event_description,
            'capacity' => $event_capacity,
            'status' => $event_status,
            'id_event' => $id_event
        ]);
    }

    echo "Event updated successfully.";
    header("Location: ../admin-dashboard/admin-dashboard-index.php");
    exit;
}
?>
