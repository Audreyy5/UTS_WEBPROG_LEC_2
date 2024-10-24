<?php
session_start();
require_once('../db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $date = $_POST['date'];
    $email = $_POST['email'];

    if (empty($username) || empty($date) || empty($email)) {
        echo "<script>
                alert('Username, Email, and Birthday Date are required!');
              </script>";
        exit();
    }

    $sql = "SELECT * FROM users WHERE username = ? AND email = ? AND date = ?";
    $stmt = $db->prepare($sql);
    $stmt->execute([$username, $email, $date]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $_SESSION['reset_username'] = $username;
        header('Location: reset_password.php');
        exit();
    } else {
        echo "<script>
                alert('Invalid Data!');
              </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body class="p-6 poppins-regular">
    <a href="login_user.php" class="absolute top-4 left-6 text-5xl text-black hover:text-gray-300 transition duration-300">
        <span class="material-symbols-outlined">
            arrow_back
        </span>
    </a>
    <h1 class="mt-20 text-3xl font-bold text-center italic text-sky-900 poppins-regular">Don't Worry, We Got You! 🔗 </h1>
    <div class="max-w-md mx-auto mt-10 bg-white shadow-md rounded px-8 pt-6 pb-8">
        <h1 class="text-3xl font-bold mb-6 text-center">Forgot Password</h1>
        <form method="POST" action="forgot_password.php">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username :</label>
                <input type="text" name="username" id="username" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email :</label>
                <input type="email" name="email" id="email" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="date">Birthday Date :</label>
                <input type="date" name="date" id="date" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Submit</button>
                <a href="login_user.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
            </div>
        </form>
        <p class="mt-6 text-center text-gray-700">
            Remember your password?
            <a href="login_user.php" class="text-blue-500 hover:text-blue-800 font-bold">Login</a>
        </p>
    </div>
</body>

</html>