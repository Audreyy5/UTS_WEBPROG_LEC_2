<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['admin_id'])) {
    echo 'error: not logged in';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = $_POST['event_id'];

    try {
        $sql_reset = "UPDATE detail_event SET is_main_event = 0";
        $stmt_reset = $db->prepare($sql_reset);
        $stmt_reset->execute();

        $sql_set_main = "UPDATE detail_event SET is_main_event = 1 WHERE id = ?";
        $stmt_set_main = $db->prepare($sql_set_main);
        $stmt_set_main->execute([$event_id]);

        header("Location: dashboard_admin.php");
        exit();

    } catch (Exception $e) {
        echo 'error: ' . $e->getMessage();
    }
} else {
    echo 'error: invalid request method';
}
