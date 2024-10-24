<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['admin_id'])) {
    echo "<script>
            alert('Session has expired or you are not logged in. Please login again.');
            window.location.href = '../login/login_admin.php';
          </script>";
    exit();
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];
    $sql_delete = "DELETE FROM detail_event WHERE id = ?";
    $stmt_delete = $db->prepare($sql_delete);

    if ($stmt_delete->execute([$event_id])) {
        echo "<script>
                alert('Event successfully deleted.');
                window.location.href = 'dashboard_admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting event. Please try again.');
                window.location.href = 'dashboard_admin.php';
              </script>";
    }
} else {
    echo "<script>
            alert('Invalid event ID.');
            window.location.href = 'dashboard_admin.php';
          </script>";
    exit();
}
?>
