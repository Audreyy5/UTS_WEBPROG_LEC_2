<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('You need to login first.');
            window.location.href = '../login/login_user.php';
         </script>";
    exit();
}

$sql_user = "SELECT id, filepath, username FROM users WHERE id = :user_id";
$user_stmt = $db->prepare($sql_user);
$user_stmt->execute(['user_id' => $_SESSION['user_id']]);
$users = $user_stmt->fetch(PDO::FETCH_ASSOC);

if (!$users) {
    echo "<script>
            alert('User not found.');
            window.location.href = '../login/login_user.php';
          </script>";
    exit();
}

$sql = "SELECT * FROM detail_event";
$stmt = $db->prepare($sql);
$stmt->execute();
$events = $stmt->fetchAll(PDO::FETCH_ASSOC);

$photos_sql = "SELECT event_id, filepath FROM event_photos";
$photos_stmt = $db->prepare($photos_sql);
$photos_stmt->execute();
$photos = $photos_stmt->fetchAll(PDO::FETCH_ASSOC);

$event_images = [];
foreach ($photos as $photo) {
    $event_images[$photo['event_id']][] = $photo['filepath'];
}

$main_event = null;
foreach ($events as $event) {
    if ($event['is_main_event'] == 1) {
        $main_event = $event;
        break;
    }
}

if (!$main_event && !empty($events)) {
    $main_event = $events[0];
}

$sql_registered_count = "SELECT COUNT(*) AS registered_count FROM event_participant WHERE event_id = :event_id";
$stmt_registered_count = $db->prepare($sql_registered_count);
$stmt_registered_count->execute(['event_id' => $main_event['id']]);
$registered_count = $stmt_registered_count->fetchColumn();

$is_event_full = $registered_count >= $main_event['participant'];

$sql_is_registered = "SELECT COUNT(*) FROM event_participant WHERE event_id = :event_id AND user_id = :user_id";
$stmt_is_registered = $db->prepare($sql_is_registered);
$stmt_is_registered->execute(['event_id' => $main_event['id'], 'user_id' => $_SESSION['user_id']]);
$is_already_registered = $stmt_is_registered->fetchColumn() > 0;

$main_event_images = isset($event_images[$main_event['id']]) ? $event_images[$main_event['id']] : ['default_image_path.jpg'];

$registration_status = $main_event['registration_status'] === 'yes';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
</head>

<body class="p-6 bg-sky-100 poppins-regular">
    <div class="mb-5 flex flex-col sm:flex-row items-center justify-between">
        <img class="ml-4 w-24 sm:w-32 h-auto" src="../photo/uvent-logo.png" alt="Logo">
        <h2 class="text-lg sm:text-xl font-bold text-center sm:text-left">Welcome,
            <?= htmlspecialchars($users['username']) ?>! What do you want to do today?</h2>
        <div class="flex items-center space-x-4 mt-4 sm:mt-0">
            <a href="../user/event_registered.php">
                <span class="material-icons-outlined mt-2">event</span>
            </a>
            <a href="../user/event_registered.php"
                class="bg-blue-200 text-black font-bold px-3 sm:px-5 py-2 mr-2 rounded-md hover:bg-blue-400 transition">Event
                Registered</a>
            <a href="profile_user.php?user_id=<?= htmlspecialchars($_SESSION['user_id']) ?>" class="ml-6 sm:ml-6">
                <img src="<?= htmlspecialchars($users['filepath']) ?>"
                    class="mr-8 w-14 sm:w-20 h-14 sm:h-20 rounded-full hover:animate-spin" alt="Profile Photo">
            </a>

        </div>
    </div>

    <div class="container mx-auto">
        <?php if ($main_event): ?>
            <div class="relative w-full h-auto bg-gray-800 rounded-lg overflow-hidden flex flex-col sm:flex-row">
                <div class="w-full h-48 sm:h-64 relative sm:w-1/2">
                    <img id="mainEventImage" src="<?= htmlspecialchars($main_event_images[0]) ?>"
                        class="w-full h-full object-cover" alt="<?= htmlspecialchars($main_event['nama_event']) ?>">

                    <button id="prevImage"
                        class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-70 p-2 rounded-full shadow-md hover:bg-opacity-100 focus:outline-none">
                        <span class="text-2xl">
                            < </span>
                    </button>

                    <button id="nextImage"
                        class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-70 p-2 rounded-full shadow-md hover:bg-opacity-100 focus:outline-none">
                        <span class="text-2xl">></span>
                    </button>
                </div>

                <div class="w-full bg-sky-900 text-white flex flex-col justify-center p-4 sm:w-1/2 text-center">
                    <h3 class="text-xl sm:text-2xl font-bold"><?= htmlspecialchars($main_event['nama_event']) ?></h3>
                    <p class="mt-2"><?= htmlspecialchars($main_event['description']) ?></p>
                    <div class="flex justify-center">
                        <a href="<?= ($is_event_full || $is_already_registered || !$registration_status) ? 'javascript:void(0)' : 'register_event_form.php?event_id=' . $main_event['id'] ?>"
                            class="bg-blue-400 hover:bg-blue-900 px-3 sm:px-4 py-2 mt-4 w-32 sm:w-40 text-white rounded <?= ($is_event_full || $is_already_registered || !$registration_status) ? 'opacity-50 cursor-not-allowed' : '' ?>"
                            onclick="<?= ($is_event_full) ? 'alert(\'This event is already at maximum numbers of participants\')' : ($is_already_registered ? 'alert(\'You already registered in this event\')' : (!$registration_status ? 'alert(\'Registration for this event is closed\')' : '')) ?>">
                            Register Now!
                        </a>
                    </div>
                </div>
            </div>


            <script>
                const images = <?= json_encode($main_event_images) ?>;
                let currentIndex = 0;

                const mainEventImage = document.getElementById('mainEventImage');
                const prevImageBtn = document.getElementById('prevImage');
                const nextImageBtn = document.getElementById('nextImage');

                function updateImage(index) {
                    mainEventImage.src = images[index];
                }

                prevImageBtn.addEventListener('click', function() {
                    currentIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
                    updateImage(currentIndex);
                });

                nextImageBtn.addEventListener('click', function() {
                    currentIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
                    updateImage(currentIndex);
                });
            </script>
        <?php endif; ?>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-10">
            <?php foreach ($events as $event): ?>
                <?php
                $grid_image = '';
                foreach ($photos as $photo):
                    if ($event['id'] == $photo['event_id']) {
                        $grid_image = $photo['filepath'];
                        break;
                    }
                endforeach;
                ?>
                <div class="text-center">
                    <a href="detail_event_user.php?event_id=<?= $event['id'] ?>">
                        <?php
                        $grid_image = !empty($grid_image) ? $grid_image : '../photo/default.jpg';
                        ?>
                        <img src="<?= htmlspecialchars($grid_image) ?>"
                            class="w-full h-32 sm:h-40 object-cover rounded-lg mb-2 hover:opacity-60"
                            alt="<?= htmlspecialchars($event['nama_event']) ?>">
                        <p class="font-bold"><?= htmlspecialchars($event['nama_event']) ?></p>
                    </a>
                </div>

            <?php endforeach; ?>
        </div>
    </div>

</body>

</html>