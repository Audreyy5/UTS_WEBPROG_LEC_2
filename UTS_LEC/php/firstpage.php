<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulist</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=arrow_back" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Spicy+Rice&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">

</head>

<body class="bg-sky-900">

    <header class="flex justify-between items-center p-6 bg-white shadow-md poppins-regular">
        <img class="w-30 h-20" src="../php/photo/uvent-logo.png"></img>
        <div class="space-x-2 md:space-x-4">
            <a href="main_page.php">
                <button class="bg-blue-600 text-white px-3 py-2 md:px-4 
            rounded hover:bg-blue-300 hover:animation-bounce">Sign In / Register</button></a>
        </div>
    </header>

    <main class="text-center mt-10 px-4 poppins-regular">
        <h1 class="text-5xl text-center text-white mt-16 spicy-rice-regular">UVENT </h1>
        <h1 class="text-2xl md:text-3xl font-bold text-blue-50 italic mb-2">"Discover Amazing Events Around You!"</h1>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 mt-10">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="../php/login/loginphoto/event3.jpeg" alt="Photo 1" class="w-full h-48 object-cover">
                <p class="text-center text-gray-700 mt-3">Perkenalan Prodi Informatika (PPIF) UMN 2023</p>
            </div>
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="../php/login/loginphoto/event6.jpeg" alt="Photo 2" class="w-full h-48 object-cover">
                <p class="text-center text-gray-700 mt-3">Orientasi Mahasiswa Baru (OMB) UMN 2023 </p>
            </div>
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <img src="../php/login/loginphoto/event5.jpeg" alt="Photo 3" class="w-full h-48 object-cover">
                <p class="text-center text-gray-700 mt-1">Kepanitiaan Perkenalan Prodi Informatika (PPIF) UMN 2024</p>
            </div>
        </div>


        <h2 class="text-xl md:text-2xl font-bold text-white mt-10 mb-7">What can you do?</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10 max-w-4xl mx-auto">
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <span class="material-icons-outlined">
                    inventory
                </span>
                <p>Participate</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <span class="material-icons-outlined">
                    event_available
                </span>
                <p>See List of Events</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <span class="material-icons-outlined">
                    checklist
                </span>
                <p>Scheduling Event and Listing</p>
            </div>
            <div class="bg-white p-6 shadow-lg rounded-lg">
                <span class="material-icons-outlined">
                    festival
                </span>
                <p>and many more, wohooo!!</p>
            </div>
        </div>

        <h3 class="text-xl md:text-2xl font-bold text-white mb-5">What does people say about this website?</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl mx-auto poppins-regular">
            <div class="bg-white p-4 shadow-md rounded-lg">
                <p>"This website really helped me a lot in ticketing! Thank you"</p>
                <p class="font-bold">- Reya, Informatics Student</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <p>"Amazing website that helps our daily uni lives!!"</p>
                <p class="font-bold">- Nada, Design Student</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <p>"The features are amazing and really helpful for me."</p>
                <p class="font-bold">- Roy, Film Student</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <p>"Would love to use it regularly and really help me during my uni lifes."</p>
                <p class="font-bold">- May, Management Student</p>
            </div>
        </div>
        <h3 class="text-5xl md:text-7xl font-bold text-white mb-5 mt-12">500+</h3>
        <h3 class="text-2xl md:text-2xl font-bold text-white mb-5 mt-4">New Users Everyday! Come Join Us Now !!</h3>

    </main>

    <footer class="bg-gray-50 text-black py-6 mt-20 poppins-regular">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <div class="mb-4">
                <h3 class="font-bold text-lg">Contact Us</h3>
                <p>Email: creatorofuvent@gmail.com</p>
                <p>Phone: +1 234 567 890</p>
            </div>
            <a href="../php/profil.php" class="bg-blue-900 text-white px-3 py-2 md:px-4 rounded hover:text-blue-300 ">Our Profile</a>
            <div class="text-sm text-gray-400 mt-5">
                &copy; 2024 Uvent Website. Made with Love.
            </div>
        </div>
    </footer>

</body>

</html>