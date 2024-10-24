<?php
session_start();
require_once('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $email = $_POST['email'];
    $password = $_POST['password'];
    $date = $_POST['date'];

    if (empty($username) || empty($password) || empty($date) || empty($email)) {
        echo "Required to fill all the data";
        exit();
    }

    if (!preg_match('/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/', $password)) {
        echo "Password must be 8-16 characters long, include at least one uppercase letter and one number.";
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (username, email, password, date, filepath) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    if ($stmt->execute([$username, $email, $hashed_password, $date, $filepath])) {
        echo "<script>
                alert('Account is successfully made');
                window.location.href = 'login_user.php';
              </script>";
        exit();
    } else {
        echo "Error registering user!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                background-color: #e0f2fe;
            }
        }

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
    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const passwordPattern = /^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,16}$/;

            if (!passwordPattern.test(password)) {
                alert('Password must be 8-16 characters long, include at least one uppercase letter and one number.');
                return false;
            }
            return true;
        }

        function togglePassword() {
            const passwordField = document.getElementById("password");
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body class="p-6 bg-gray-100 poppins-regular">
    <a href="login_user.php" class="absolute top-4 left-6 text-5xl text-black hover:text-gray-300 transition duration-300">
        <span class="material-symbols-outlined">
            arrow_back
        </span>
    </a>
    <h1 class="mt-10 text-3xl font-bold text-center italic text-sky-900 poppins-regular">Hello, Welcome to UVENT! 🤝 </h1>
    <h1 class="text-3xl font-bold mb-6 text-center italic text-sky-900 poppins-regular">We're so excited to welcome you here!</h1>
    <div class="max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <h1 class="text-3xl font-bold mb-6 text-center">Register Here</h1>
        <form action="register.php" method="POST" enctype="multipart/form-data" onsubmit="return validatePassword()">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <input type="text" name="username" id="username" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                <input type="email" name="email" id="email" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                <span class="toggle-password pt-6" onclick="togglePassword()">🙈</span>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="date">Birthday Date</label>
                <input type="date" name="date" id="date" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="foto">Profile Photo</label>
                <input type="file" name="foto" id="foto" accept="image/*" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Register
                </button>
                <a href="login_user.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Already have an account?
                </a>
            </div>
        </form>
    </div>
</body>

</html>