<?php
include '../db_conn.php'; 
require './vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$event = $_GET['id_event'];

$sql = "SELECT user.id_user, user.nama, user.email, user.profile_pic
        FROM user
        LEFT JOIN daftar ON user.id_user = daftar.id_user
        LEFT JOIN event ON daftar.id_event = event.id_event
        WHERE event.id_event = ?;";

$stmt = $conn->prepare($sql);
$stmt->execute([$event]);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Name');
$sheet->setCellValue('C1', 'Email');
$sheet->setCellValue('D1', 'Profile Picture');

$row = 2;
foreach ($users as $user) {
    $sheet->setCellValue('A' . $row, $user['id_user']);
    $sheet->setCellValue('B' . $row, $user['nama']);
    $sheet->setCellValue('C' . $row, $user['email']);
    $sheet->setCellValue('D' . $row, $user['profile_pic']);
    $row++;
}

$filename = "event_{$event}_users.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output'); 

exit;
?>
