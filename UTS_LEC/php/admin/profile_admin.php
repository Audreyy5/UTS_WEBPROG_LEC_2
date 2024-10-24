<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['admin_id'])) {
    echo "<script>
            alert('You need to login first.');
            window.location.href = '../login/login_admin.php';
          </script>";
    exit();
}

$admin_id = isset($_GET['admin_id']) ? $_GET['admin_id'] : $_SESSION['admin_id'];
if ($admin_id) {
    $sql = "SELECT * FROM admin WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$admin_id]);
    $admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin_data) {
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
    <title>Admin Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
</head>

<body class="bg-cyan-950 p-8 flex items-center justify-center h-screen poppins-regular">
    <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg w-full text-center">
        <a href="dashboard_admin.php" class="text-blue-500 hover:text-blue-700 font-bold text-lg hover:underline">&larr; Back</a>
        
        <div class="mt-4 mb-4">
            <img src="<?= htmlspecialchars($admin_data['filepath'] ?? 'default-profile.png') ?>" class="w-32 h-32 rounded-full mx-auto" alt="Profile Photo">
        </div>
        
        <h1 class="text-2xl font-bold mb-2"><?= htmlspecialchars($admin_data['username']) ?></h1>
        <p class="text-gray-600 mb-6"><?= htmlspecialchars($admin_data['email']) ?></p>

        <div class="flex justify-center space-x-4 mb-4">
            <a href="../admin/edit_profil.php" class="bg-yellow-500 text-white py-2 px-4 rounded hover:bg-yellow-700">Edit Profile</a>
            <a href="../admin/change_password_admin.php" class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-700">Change Password</a>
            <a href="../firstpage.php"
                class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-700 transition mx-4">Logout</a>
        </div>
    </div>
</body>

</html>
