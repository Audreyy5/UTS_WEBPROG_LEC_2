<?php
session_start();
require_once('../db.php');

if (!isset($_GET['event_id'])) {
    echo "<script>
            alert('Event not found.');
            window.location.href = 'dashboard_user.php';
          </script>";
    exit();
}

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

$sql_event = "SELECT nama_event FROM detail_event WHERE id = :event_id";
$stmt_event = $db->prepare($sql_event);
$stmt_event->execute(['event_id' => $event_id]);
$event = $stmt_event->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "<script>
            alert('Event not found.');
            window.location.href = 'dashboard_user.php';
          </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    $sql_insert = "INSERT INTO event_participant (user_id, username, email, event_id) VALUES (:user_id, :username, :email, :event_id)";
    $stmt_insert = $db->prepare($sql_insert);
    $stmt_insert->execute([
        'user_id' => $user_id,
        'username' => $username,
        'email' => $email,
        'event_id' => $event_id
    ]);

    echo "<script>
            alert('You have successfully registered for the event.');
            window.location.href = 'event_registered.php';
          </script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for <?= htmlspecialchars($event['nama_event']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">

</head>

<body class="bg-sky-100 p-6 flex items-center justify-center h-screen poppins-regular">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <a href="javascript:void(0)" onclick="window.history.back();" class="text-blue-500 hover:text-blue-700 font-bold hover:underline text-lg mb-8">&larr; Back</a>
    <h2 class="text-2xl font-bold text-center mb-4 mt-2">Event Register</h2>
        <p class="text-center mb-4">You are registering for <?= htmlspecialchars($event['nama_event']) ?></p>

        <form method="POST" action="">
            <div class="mb-4">
                <label for="username" class="block text-gray-700 font-bold">Full Name :</label>
                <input type="text" id="username" name="username"
                    class="w-full p-2 border border-gray-300 rounded mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Enter your Full Name" required
                    pattern="[A-Za-z\s]+" title="Only letters and spaces are allowed">
            </div>

            <div class="mb-6">
                <label for="email" class="block text-gray-700 font-bold">Email :</label>
                <input type="email" id="email" name="email" class="w-full p-2 border border-gray-300 rounded mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter your Email" required>
            </div>

            <button type="submit" class="bg-blue-500 text-white py-2 w-full rounded font-bold hover:bg-blue-700">Register</button>
        </form>
    </div>

</body>

</html>