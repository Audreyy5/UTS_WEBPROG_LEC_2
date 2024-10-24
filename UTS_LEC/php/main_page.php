<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UVENT</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spicy+Rice&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=favorite" />
    <style>
        .bg-custom {
            background-image: url('photo/bg-main.png');
            background-size: cover;
            background-position: center;
        }

        .spicy-rice-regular {
            font-family: "Spicy Rice", serif;
            font-weight: 400;
            font-style: normal;
        }

        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>

<body class="flex flex-col items-center justify-center min-h-screen relative bg-custom">
    <div class="absolute inset-0 bg-black bg-opacity-80"></div>
    <a href="../php/profil.php" class="absolute top-5 right-5 text-white text-5xl hover:animate-spin transition duration-300">
        <span class="material-symbols-outlined text-5xl">
            favorite
        </span>
    </a>
    <div class="relative z-10 text-center text-border-white">    
        <a href="../php/firstpage.php" class="text-white hover:text-white font-bold text-lg hover:underline">&larr; Back</a>
        <h1 class="text-6xl italic mt-5 mb-5 text-[#eef2ff] spicy-rice-regular">
            UVENT
        </h1>
        <h3 class="text-3xl text-center text-white font-semibold mb-6 poppins-regular drop-shadow-md">
            Discover Amazing Events Around You!
        </h3>
        <h3 class="text-2xl font-semibold text-white mb-8 animate-bounce poppins-regular">
            Choose your role!
        </h3>
        <div class="space-x-8 flex justify-center">
            <a href="login/login_admin.php"
                class="bg-rose-400 poppins-regular text-white font-bold py-3 px-8 rounded-full text-xl hover:bg-rose-500 transform hover:scale-110 transition duration-300 ease-in-out shadow-lg">
                Admin
            </a>
            <a href="login/login_user.php"
                class="bg-indigo-400 text-white font-bold py-3 px-8 rounded-full text-xl hover:bg-indigo-500 transform hover:scale-110 transition duration-300 ease-in-out shadow-lg">
                User
            </a>
        </div>
    </div>
</body>


</html>