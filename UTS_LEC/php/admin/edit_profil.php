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

$admin_id = $_SESSION['admin_id']; 

$sql = "SELECT * FROM admin WHERE id = ?";
$stmt = $db->prepare($sql);
$stmt->execute([$admin_id]);
$admin_data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$admin_data) {
    echo "<p>No user found with this ID.</p>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $current_password = $_POST['current_password'];

    if (password_verify($current_password, $admin_data['password'])) {
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
            $file_tmp = $_FILES['profile_picture']['tmp_name'];
            $file_name = $_FILES['profile_picture']['name'];
            
            $file_ext = explode(".", $file_name);
            $file_ext = end($file_ext);
            $file_ext = strtolower($file_ext);

            switch ($file_ext) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                    move_uploaded_file($file_tmp, "../uploads/{$file_name}");
                    $file_path = "../uploads/{$file_name}";
                    if (!empty($admin_data['filepath']) && file_exists($admin_data['filepath'])) {
                        unlink($admin_data['filepath']);
                    }
                    break;
                default:
                    echo "<script>alert('You can only upload .jpg, .jpeg, or .png files.');</script>";
                    exit();
            }
        } else {
            $file_path = $admin_data['filepath'];
        }

        $sql = "UPDATE admin SET username = ?, email = ?, date = ?, filepath = ? WHERE id = ?";
        $stmt = $db->prepare($sql);

        if ($stmt->execute([$username, $email, $birthday, $file_path, $admin_id])) {
            echo "<script>
                    alert('Profile updated successfully!');
                    window.location.href = 'profile_admin.php?admin_id=$admin_id';
                  </script>";
        } else {
            echo "<p>Error updating profile!</p>";
        }
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
<body class="p-6 bg-cyan-900 poppins-regular">
<a href="../admin/profile_admin.php" class="text-white hover:text-white font-bold text-lg hover:underline">&larr; Back</a>
    <div class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Edit Your Profile</h1>

        <form action="edit_profil.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username :</label>
                <input type="text" id="username" name="username" value="<?= htmlspecialchars($admin_data['username']) ?>" required 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($admin_data['email']) ?>" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="birthday">Birthday :</label>
                <input type="date" id="birthday" name="birthday" value="<?= htmlspecialchars($admin_data['date']) ?>" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Current Profile Photo :</label>
                <?php if (!empty($admin_data['filepath'])): ?>
                    <img src="<?= htmlspecialchars($admin_data['filepath']) ?>" alt="Profile Photo" class="max-w-full h-32 mb-2 rounded shadow-md">
                <?php else: ?>
                    <p>No current profile photo.</p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">New Profile Photo (Optional) :</label>
                <input type="file" name="profile_picture" accept="image/*"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>

            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="current_password">Current Password</label>
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
