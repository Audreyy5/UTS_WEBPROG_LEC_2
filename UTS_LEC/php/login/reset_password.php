<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['reset_username'])) {
    echo "Access denied!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($new_password) || empty($confirm_password)) {
        echo "<script>
                alert('Both password fields are required!');
              </script>";
        exit();
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $new_password)) {
        echo "<script>
                alert('Password must be 8-16 characters long, include at least one uppercase letter and one number.');
              </script>";
        exit();
    }

    if ($new_password !== $confirm_password) {
        echo "<script>
                alert('Passwords do not match!');
              </script>";
        exit();
    }

    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    $username = $_SESSION['reset_username'];
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt = $db->prepare($sql);
    
    if ($stmt->execute([$hashed_password, $username])) {
        unset($_SESSION['reset_username']);
        echo "<script>
                alert('Password successfully updated!');
                window.location.href = 'login_user.php';
              </script>";
    } else {
        echo "<script>
                alert('Error updating password!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
        }
    </style>
    <script>
        function togglePassword(inputId) {
            const passwordField = document.getElementById(inputId);
            const togglePassword = document.querySelector(`#${inputId}-toggle`);
            if (passwordField.type === "password") {
                passwordField.type = "text";
                togglePassword.textContent = "🙈"; 
            } else {
                passwordField.type = "password";
                togglePassword.textContent = "👁️";
            }
        }
    </script>
</head>
<body class="p-6 bg-gray-100">
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md rounded px-8 pt-6 pb-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Reset Password</h1>
        <form method="POST" action="reset_password.php">
            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="new_password">New Password</label>
                <input type="password" name="new_password" id="new_password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                <span class="toggle-password pt-6" id="new_password-toggle" onclick="togglePassword('new_password')">👁️</span>
            </div>
            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                <span class="toggle-password pt-6" id="confirm_password-toggle" onclick="togglePassword('confirm_password')">👁️</span>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Reset Password</button>
                <a href="login_user.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
