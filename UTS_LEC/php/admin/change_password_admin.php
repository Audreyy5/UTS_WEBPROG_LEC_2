<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['admin_id'])) {
    echo "<script>
            alert('Access denied! Please log in again.');
            window.location.href = '../login/login_admin.php';
          </script>";
    exit();
}

$admin_id = $_SESSION['admin_id']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_password) || empty($confirm_password)) {
        echo "<script>alert('Both password fields are required!');</script>";
        exit();
    }
    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $new_password)) {
        echo "<script>alert('Password must be 8-16 characters long, include at least one uppercase letter and one number.');</script>";
        exit();
    }
    if ($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!');</script>";
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $sql = "UPDATE admin SET password = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute([$hashed_password, $admin_id])) {
        echo "<script>
                alert('Password successfully updated!');
                window.location.href = 'profile_admin.php';
              </script>";
    } else {
        echo "<script>alert('Error updating password! Please try again.');</script>";
    }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 8px;
            cursor: pointer;
        }
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
<body class="bg-cyan-900 flex items-center justify-center h-screen p-4 poppins-regular">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm text-center">
        <a href="../admin/profile_admin.php" class="text-blue-500 hover:text-blue-700 font-bold mb-4 inline-block">&larr; Back</a>
        <h2 class="text-3xl font-bold mb-6">Change Password</h2>

        <form action="../admin/change_password_admin.php" method="POST" onsubmit="return validatePassword()">
            <div class="mb-4 relative">
                <label for="new_password" class="block text-gray-700 text-left mb-2 font-bold">New Password :</label>
                <input type="password" name="new_password" id="new_password" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <span class="toggle-password pt-8" onclick="togglePassword('new_password')">🙈</span>
            </div>
            <div class="mb-6 relative">
                <label for="confirm_password" class="block text-gray-700 text-left mb-2 font-bold">Confirm Password :</label>
                <input type="password" name="confirm_password" id="confirm_password" class="w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                <span class="toggle-password pt-8" onclick="togglePassword('confirm_password')">🙈</span>
            </div>
            <button type="submit" class="bg-green-500 text-white w-full py-2 rounded hover:bg-green-600">Reset Password</button>
        </form>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordField = document.getElementById(fieldId);
            const togglePassword = passwordField.nextElementSibling;

            if (passwordField.type === "password") {
                passwordField.type = "text";
                togglePassword.textContent = "🙉";
            } else {
                passwordField.type = "password";
                togglePassword.textContent = "🙈";
            }
        }

        function validatePassword() {
            const password = document.getElementsByName('new_password')[0].value;
            const confirmPassword = document.getElementsByName('confirm_password')[0].value;
            const passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/;

            if (!passwordPattern.test(password)) {
                alert('Password must be 8-16 characters long, include at least one uppercase letter and one number.');
                return false;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return false;
            }

            return true;
        }
    </script>
</body>
</html>