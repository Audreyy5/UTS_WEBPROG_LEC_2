<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You need to login first.');
            window.location.href = '../login/login_user.php';
          </script>";
    exit();
}

$user_id = isset($_GET['user_id']) ? $_GET['user_id'] : $_SESSION['user_id'];
if ($user_id) {
    $sql = "SELECT * FROM users WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$user_id]);
    $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user_data) {
        echo "<p>No user found with this ID.</p>";
        exit();
    }
} else {
    echo "<p>Invalid user ID.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            animation: backgroundChange 15s ease infinite;
        }

        @keyframes backgroundChange {
            0% {
                background-color: #f0f9ff;
            }

            25% {
                background-color: #eff6ff;
            }

            50% {
                background-color: #e0f2fe;
            }

            75% {
                background-color: #dbeafe;
            }

            100% {
                background-color: #bae6fd;
            }
        }
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
</head>

<body class="bg-sky-100 p-8 flex items-center justify-center h-screen poppins-regular">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full text-center">
        <a href="dashboard_user.php" class="text-blue-500 hover:text-blue-700 font-bold text-lg hover:underline">&larr; Back</a>

        <div class="mt-4 mb-4">
            <img src="<?= htmlspecialchars($user_data['filepath'] ?? 'default-profile.png') ?>" class="w-32 h-32 rounded-full mx-auto" alt="Profile Photo">
        </div>

        <h1 class="text-2xl font-bold mb-2"><?= htmlspecialchars($user_data['username']) ?></h1>
        <p class="text-gray-600 mb-6"><?= htmlspecialchars($user_data['email']) ?></p>

        <div class="flex justify-center space-x-4 mb-4">
            <a href="../user/edit_profile_user.php" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-700">Edit Profile</a>
            <a href="../user/change_password_user.php" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Change Password</a>
            <a href="../firstpage.php"
                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-700 transition mx-4">Logout</a>
        </div>
    </div>
</body>

</html>