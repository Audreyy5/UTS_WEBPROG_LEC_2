<?php
session_start();
require_once('../db.php');
require '../../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

if (!isset($_SESSION['admin_id'])) {
    echo "<script>
                alert('Session has expired or you are not logged in. Please login again.');
                window.location.href = '../login/login_admin.php';
          </script>";
    exit();
}

$event_id = $_GET['event_id'] ?? null;
if (!$event_id) {
    echo "Event ID is required.";
    exit();
}

$sql_event_details = "
    SELECT e.nama_event
    FROM detail_event e
    WHERE e.id = :event_id
";
$stmt_event_details = $db->prepare($sql_event_details);
$stmt_event_details->execute(['event_id' => $event_id]);
$event = $stmt_event_details->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit();
}

$sql_participants = "
    SELECT u.username, u.email
    FROM event_participant ep
    INNER JOIN users u ON ep.user_id = u.id
    WHERE ep.event_id = :event_id
";
$stmt_participants = $db->prepare($sql_participants);
$stmt_participants->execute(['event_id' => $event_id]);
$participants = $stmt_participants->fetchAll(PDO::FETCH_ASSOC);

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sheet->setCellValue('A1', 'No');
$sheet->setCellValue('B1', 'Username');
$sheet->setCellValue('C1', 'Email');

$row = 2;
foreach ($participants as $index => $participant) {
    $sheet->setCellValue('A' . $row, $index + 1);
    $sheet->setCellValue('B' . $row, $participant['username']);
    $sheet->setCellValue('C' . $row, $participant['email']);
    $row++;
}
$filename = 'Participants_' . $event['nama_event'] . '.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

exit();