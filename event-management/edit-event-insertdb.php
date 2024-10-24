<?php
include '../db_conn.php'; // Database connection

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Function to sanitize input data
    function sanitize_input($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    // Sanitize and validate inputs
    $id_event = filter_var($_POST['id_event'], FILTER_SANITIZE_NUMBER_INT);
    $event_name = sanitize_input($_POST['event_name']);
    $event_date = sanitize_input($_POST['event_date']);
    $event_location = sanitize_input($_POST['event_location']);
    $event_description = sanitize_input($_POST['event_description']);
    $event_capacity = filter_var($_POST['event_capacity'], FILTER_SANITIZE_NUMBER_INT);
    $event_status = sanitize_input($_POST['event_status']);

    // Validate event_capacity is a valid number
    if (!filter_var($event_capacity, FILTER_VALIDATE_INT)) {
        echo "Invalid capacity value.";
        exit;
    }

    // Handle the banner upload (if provided)
    if (isset($_FILES['event_banner']) && $_FILES['event_banner']['error'] == 0) {
        // Validate the file is an image
        $check = getimagesize($_FILES['event_banner']['tmp_name']);
        if ($check === false) {
            echo "File is not a valid image.";
            exit;
        }

        $banner_name = sanitize_input($_FILES['event_banner']['name']);
        $banner_tmp = $_FILES['event_banner']['tmp_name'];
        $upload_dir = '../uploads/' . basename($banner_name);

        // Move the uploaded file and update event with new banner
        if (move_uploaded_file($banner_tmp, $upload_dir)) {
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
            echo "Error uploading banner.";
            exit;
        }
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

    // Redirect to success page
    header("Location: ./edit-event-success.php");
    exit;
}
?>
