<?php
session_start();
require_once('../db.php');

if (!isset($_GET['event_id'])) {
    echo "<script>
            alert('Event not found.');
            window.location.href = 'event_registered.php';
          </script>";
    exit();
}

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

$sql_delete = "DELETE FROM event_participant WHERE event_id = :event_id AND user_id = :user_id";
$stmt_delete = $db->prepare($sql_delete);
$stmt_delete->execute([
    'event_id' => $event_id,
    'user_id' => $user_id
]);

if ($stmt_delete->rowCount() > 0) {
    echo "<script>
            alert('Event registration deleted successfully.');
            window.location.href = 'event_registered.php';
          </script>";
} else {
    echo "<script>
            alert('Failed to delete the event registration.');
            window.location.href = 'event_registered.php';
          </script>";
}
?>
