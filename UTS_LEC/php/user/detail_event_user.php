<?php
session_start();
require_once('../db.php');

if (!isset($_GET['event_id'])) {
    echo "<script>
            alert('Event not found.');
            window.location.href = 'dashboard_user.php';
          </script>";
    exit();
}

$event_id = $_GET['event_id'];
$user_id = $_SESSION['user_id'];

$sql_event = "SELECT * FROM detail_event WHERE id = :event_id";
$stmt_event = $db->prepare($sql_event);
$stmt_event->execute(['event_id' => $event_id]);
$event = $stmt_event->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "<script>
            alert('Event not found.');
            window.location.href = 'dashboard_user.php';
          </script>";
    exit();
}

$sql_photos = "SELECT filepath FROM event_photos WHERE event_id = :event_id";
$stmt_photos = $db->prepare($sql_photos);
$stmt_photos->execute(['event_id' => $event_id]);
$event_photos = $stmt_photos->fetchAll(PDO::FETCH_COLUMN);

$main_photo = isset($event_photos[0]) ? $event_photos[0] : 'default_image_path.jpg';

$sql_check_registration = "SELECT COUNT(*) FROM event_participant WHERE event_id = :event_id AND user_id = :user_id";
$stmt_check_registration = $db->prepare($sql_check_registration);
$stmt_check_registration->execute([
    'event_id' => $event_id,
    'user_id' => $user_id
]);
$is_registered = $stmt_check_registration->fetchColumn() > 0;

$sql_registered_count = "SELECT COUNT(*) AS registered_count FROM event_participant WHERE event_id = :event_id";
$stmt_registered_count = $db->prepare($sql_registered_count);
$stmt_registered_count->execute(['event_id' => $event_id]);
$registered_count = $stmt_registered_count->fetchColumn();

$is_event_full = $registered_count >= $event['participant'];
$registration_status = $event['registration_status'] === 'yes';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($event['nama_event']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
</head>

<body class="bg-blue-100 p-6 poppins-regular">

    <div class="mb-5">
        <a href="dashboard_user.php" class="text-blue-500 hover:text-blue-700 font-bold text-lg hover:underline">&larr; Back</a>
    </div>

    <div class="container mx-auto bg-white rounded-lg shadow-lg p-6 mt-20">
        <h2 class="text-3xl font-bold text-center mb-6"><?= htmlspecialchars($event['nama_event']) ?></h2>

        <div class="flex flex-col md:flex-row">

            <div class="md:w-1/2 relative">
                <img id="eventImage" src="<?= htmlspecialchars($main_photo) ?>" class="w-full h-80 object-cover rounded-lg" alt="<?= htmlspecialchars($event['nama_event']) ?>">

                <button id="prevImage" class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-70 p-2 rounded-full shadow-md hover:bg-opacity-100 focus:outline-none">
                    <span class="text-2xl">‹</span>
                </button>

                <button id="nextImage" class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-white bg-opacity-70 p-2 rounded-full shadow-md hover:bg-opacity-100 focus:outline-none">
                    <span class="text-2xl">›</span>
                </button>
            </div>

            <div class="md:w-1/2 md:ml-8 mt-6 md:mt-0">
                <p class="mb-2 mt-6"><strong>Date :</strong> <?= htmlspecialchars(date('d/m/Y', strtotime($event['tanggal']))) ?></p>
                <p class="mb-2"><strong>Time :</strong> <?= htmlspecialchars($event['jam']) ?></p>
                <p class="mb-2"><strong>Max Participants :</strong> <?= htmlspecialchars($event['participant']) ?></p>
                <p class="mb-2"><strong>Location :</strong> <?= htmlspecialchars($event['lokasi']) ?></p>
                <p class="mt-2"><strong>ABOUT <?= htmlspecialchars($event['nama_event']) ?> :</strong></p>
                <p class="text-gray-700"><?= htmlspecialchars($event['description']) ?></p>

                <p class="mt-2"><strong>Registration Status :</strong> <?= $registration_status ? 'Open' : 'Close' ?></p>

                <a href="<?= ($is_event_full || $is_registered || !$registration_status) ? 'javascript:void(0)' : 'register_event_form.php?event_id=' . $event_id ?>"
                    class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 mt-4 rounded block text-center <?= ($is_event_full || $is_registered || !$registration_status) ? 'opacity-50 cursor-not-allowed' : '' ?>"
                    onclick="<?= ($is_event_full) ? 'alert(\'This event is already at maximum numbers of participants\')' : ($is_registered ? 'alert(\'You already registered in this event\')' : (!$registration_status ? 'alert(\'Registration for this event is closed\')' : '')) ?>">
                    Register Here
                </a>
            </div>
        </div>
    </div>

    <script>
        const images = <?= json_encode($event_photos) ?>;
        let currentIndex = 0;

        const eventImage = document.getElementById('eventImage');
        const prevImageBtn = document.getElementById('prevImage');
        const nextImageBtn = document.getElementById('nextImage');

        function updateImage(index) {
            eventImage.src = images[index];
        }

        prevImageBtn.addEventListener('click', function () {
            currentIndex = (currentIndex === 0) ? images.length - 1 : currentIndex - 1;
            updateImage(currentIndex);
        });

        nextImageBtn.addEventListener('click', function () {
            currentIndex = (currentIndex === images.length - 1) ? 0 : currentIndex + 1;
            updateImage(currentIndex);
        });
    </script>

</body>

</html>
