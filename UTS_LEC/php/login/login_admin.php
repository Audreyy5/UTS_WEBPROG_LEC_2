<?php
session_start();
require_once('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = htmlspecialchars($_POST['login_input'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    if (empty($login_input) || empty($password)) {
        echo "<script>
                alert('Username/email and password are required!');
                window.location.href = 'login_admin.php';
              </script>";
        exit();
    }

    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM admin WHERE email = ?";
    } else {
        $sql = "SELECT * FROM admin WHERE username = ?";
    }

    $stmt = $db->prepare($sql);
    $stmt->execute([$login_input]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];

        echo "<script>
                alert('Welcome, Admin " . $admin['username'] . "!');
                window.location.href = '../admin/dashboard_admin.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Invalid username/email or password!');
                window.location.href = 'login_admin.php';
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
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

        .content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">
</head>

<body class="bg-gray-100 bg-opacity-80">
    <a href="../main_page.php" class="absolute top-4 left-6 text-5xl text-black hover:text-gray-300 transition duration-300">
        <span class="material-symbols-outlined">
            arrow_back
        </span>
    </a>

    <div class="content">
        <div class="max-w-md w-full bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-4 mt-20 bg-opacity-90">
            <h1 class="text-3xl font-bold text-center mb-6 text-blue-600 poppins-regular">Uvent Admin Login</h1>
            <form action="login_admin.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2 poppins-regular" for="login_input">Username/Email</label>
                    <input type="text" name="login_input" id="login_input" required
                        class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-blue-500 transition duration-300" />
                </div>
                <div class="relative mb-4 poppins-regular">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                    <input type="password" name="password" id="password" required
                        class="shadow appearance-none border rounded w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:shadow-outline hover:border-blue-500 transition duration-300" />
                    <span class="toggle-password pt-6 mt-1" onclick="togglePassword()">🙈</span>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline transition duration-300">Login</button>
                    <a href="../main_page.php"
                        class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800 transition duration-300">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-gray-50 text-black py-6 mt-20 poppins-regular">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="mb-4">
                <h3 class="font-bold text-lg">Contact Us</h3>
                <p>Email: creatorofuvent@gmail.com</p>
                <p>Phone: +1 234 567 890</p>
            </div>
            <a href="../profil.php" class="bg-blue-900 text-white px-3 py-2 md:px-4 rounded hover:text-blue-300 ">Our Profile</a>
            <div class="text-sm text-gray-400 mt-5">
                &copy; 2024 Uvent Website. Made with Love.
            </div>
        </div>
    </footer>

    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var togglePassword = document.querySelector(".toggle-password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                togglePassword.innerHTML = "🙉";
            } else {
                passwordField.type = "password";
                togglePassword.innerHTML = "🙈";
            }
        }
    </script>
</body>

</html>
