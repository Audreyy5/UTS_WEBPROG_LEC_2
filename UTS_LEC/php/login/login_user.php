<?php
session_start();
require_once('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login_input = htmlspecialchars($_POST['login_input'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    if (empty($login_input) || empty($password)) {
        echo "Username/email and password are required!";
        exit();
    }

    if (filter_var($login_input, FILTER_VALIDATE_EMAIL)) {
        $sql = "SELECT * FROM users WHERE email = ?";
    } else {
        $sql = "SELECT * FROM users WHERE username = ?";
    }

    $stmt = $db->prepare($sql);
    $stmt->execute([$login_input]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        echo "<script>
                alert('Welcome, " . $user['username'] . "!');
                window.location.href = '../user/dashboard_user.php';
              </script>";
        exit();
    } else {
        echo "<strong class='text-red-500'>Invalid username/email or password!</strong>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login User</title>
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

        .gallery img {
            width: 100%;
            height: auto;
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body class="p-6 bg-[#0c4a6e] poppins-regular">
    <a href="../main_page.php" class="absolute top-4 left-6 text-5xl text-black hover:text-gray-300 transition duration-300">
        <span class="material-symbols-outlined">
            arrow_back
        </span>
    </a>
    <h1 class="mt-10 text-3xl font-bold mb-6 text-center italic text-sky-900">Welcome back to UVENT! Glad to see you again!</h1>
    <div class="w-100 mx-auto mt-10 bg-white shadow-md rounded px-8 pt-6 pb-8">
        <h1 class="text-3xl font-bold mb-6 text-center">User Login</h1>
        <form action="login_user.php" method="POST">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="login_input">Username/Email</label>
                <input type="text" name="login_input" id="login_input" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4 relative">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input type="password" name="password" id="password" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
                <span class="toggle-password pt-6" onclick="togglePassword()">🙈</span>
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Login
                </button>
                <a href="forgot_password.php"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                    Forgot Password?
                </a>
            </div>
        </form>
        <p class="mt-6 text-center text-gray-700">Don't have an account?
            <a href="register.php" class="text-blue-500 hover:text-blue-800 font-bold">Register here</a>
        </p>
    </div>

    <h1 class="text-3xl text-sky-900 font-bold mt-10 mb-8 text-center">Past Events</h1>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 poppins-regular text-sky-900">
        <div class="relative">
            <img src="loginphoto/event1.jpeg" alt="Event 1" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">Computer Science Shelter (CSS) 2024</p>
        </div>
        <div class="relative">
            <img src="loginphoto/event2.jpeg" alt="Event 2" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">Maxima 2024
            <p>
        </div>
        <div class="relative">
            <img src="loginphoto/event3.jpeg" alt="Event 3" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">Perkenalan Prodi Informatika (PPIF) 2023</p>
        </div>
        <div class="relative">
            <img src="loginphoto/event4.jpeg" alt="Event 4" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">Garuda Hacks 5.0 @UMN </p>
        </div>
        <div class="relative">
            <img src="loginphoto/event5.jpeg" alt="Event 5" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">Perkenalan Prodi Informatika (PPIF) 2024</p>
        </div>
        <div class="relative">
            <img src="loginphoto/event9.jpeg" alt="Event 6" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">UMN Tech Festival 2023/2024</p>
        </div>
        <div class="relative">
            <img src="loginphoto/event7.jpeg" alt="Event 7" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">Pekan Kreativitas Nusantara UMN 2024</p>
        </div>
        <div class="relative">
            <img src="loginphoto/event8.jpeg" alt="Event 8" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">Codexpo 2024</p>
        </div>
        <div class="relative">
            <img src="loginphoto/event6.jpeg" alt="Event 9" class="rounded-lg object-cover h-56 w-full">
            <p class="text-center mt-4">Orientasi Mahasiswa Baru (OMB) 2023</p>
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
                togglePassword.textContent = "🙉";
            } else {
                passwordField.type = "password";
                togglePassword.textContent = "🙈";
            }
        }
    </script>
</body>

</html>