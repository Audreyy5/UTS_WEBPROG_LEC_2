<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['admin_id'])) {
    echo 'error: not logged in';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];
    $registration_status = $_POST['registration_status'];

    try {
        $sql_update = "UPDATE detail_event SET registration_status = ? WHERE id = ?";
        $stmt_update = $db->prepare($sql_update);
        $stmt_update->execute([$registration_status, $event_id]);

        header("Location: dashboard_admin.php");
        exit();
    } catch (Exception $e) {
        // In case of an error, return error message
        echo 'error: ' . $e->getMessage();
    }
} else {
    // If the request is not a POST request, return an error
    echo 'error: invalid request method';
}
