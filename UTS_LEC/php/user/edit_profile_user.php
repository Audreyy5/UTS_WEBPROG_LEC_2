<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Session has expired or you are not logged in. Please login again.');
            window.location.href = '../login/login_user.php';
          </script>";
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT username, email, date, filepath FROM users WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "User not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];

    $sql_password = "SELECT password FROM users WHERE id = ?";
    $stmt_password = $db->prepare($sql_password);
    $stmt_password->execute([$user_id]);
    $stored_password = $stmt_password->fetchColumn();

    if (password_verify($current_password, $stored_password)) {
        $filepath = $user['filepath'];

        if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
            $filename = $_FILES['foto']['name'];
            $temp_file = $_FILES['foto']['tmp_name'];

            $file_ext = explode(".", $filename);
            $file_ext = end($file_ext);
            $file_ext = strtolower($file_ext);

            switch ($file_ext) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                    move_uploaded_file($temp_file, "../uploads/{$filename}");
                    $filepath = "../uploads/{$filename}";
                    break;
                default:
                    echo "<script>alert('You can only upload .jpg, .jpeg, or .png files.');</script>";
                    exit();
            }
        }

        $new_username = $_POST['username'];
        $new_email = $_POST['email'];
        $new_birthday = $_POST['date'];

        $update_sql = "UPDATE users SET username = ?, email = ?, date = ?, filepath = ? WHERE id = ?";
        $stmt_update = $db->prepare($update_sql);
        $stmt_update->execute([$new_username, $new_email, $new_birthday, $filepath, $user_id]);

        echo "<script>
                alert('Profile updated successfully!');
                window.location.href = 'profile_user.php';
              </script>";
        exit();
    } else {
        echo "<script>alert('Incorrect password. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
      <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script>
        function togglePassword() {
            const passwordField = document.getElementById("current_password");
            const togglePassword = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                togglePassword.textContent = "🙉"; 
            } else {
                passwordField.type = "password";
                togglePassword.textContent = "🙈";
            }
        }
    </script>
</head>
<body class="p-6 bg-blue-200 poppins-regular">
    <div class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
    <a href="../user/profile_user.php" class="text-blue-500 hover:text-blue-700 font-bold text-lg hover:underline">&larr; Back</a>

        <h1 class="text-3xl font-bold mt-4 mb-6 text-center">Edit Your Profile</h1>

        <form method="POST" action="edit_profile_user.php" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username :</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="date">Birthday :</label>
                <input type="date" id="date" name="date" value="<?= htmlspecialchars($user['date']) ?>" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Current Profile Photo :</label>
                <?php if (!empty($user['filepath'])): ?>
                    <img src="<?= htmlspecialchars($user['filepath']) ?>" alt="Profile Photo" class="max-w-full h-32 mb-2 rounded shadow-md">
                <?php else: ?>
                    <p>No current profile photo.</p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">New Profile Photo (Optional) :</label>
                <input type="file" name="foto" accept="image/*"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="current_password">Current Password :</label>
                <input type="password" id="current_password" name="current_password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                <span class="toggle-password pt-6" onclick="togglePassword()">🙈</span>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Save Changes</button>
            </div>
        </form>
    </div>
</body>
</html>
