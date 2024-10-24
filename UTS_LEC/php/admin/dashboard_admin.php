<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['admin_id'])) {
    echo "<script>
                alert('Session has expired or you are not logged in. Please login again.');
                window.location.href = 'login_admin.php';
            </script>";
    exit();
}

$admin_id = $_SESSION['admin_id'];
$sql_admin = "SELECT username, filepath FROM admin WHERE id = ?";
$stmt_admin = $db->prepare($sql_admin);
$stmt_admin->execute([$admin_id]);
$admin = $stmt_admin->fetch(PDO::FETCH_ASSOC);

if (!$admin) {
    echo "admin not found!";
    exit();
}

$sql_events = "
    SELECT e.id, e.nama_event, e.lokasi, e.jam, e.tanggal, e.participant AS max_participants, e.is_main_event,
           e.registration_status, COUNT(ep.id) AS registered_count
    FROM detail_event e
    LEFT JOIN event_participant ep ON e.id = ep.event_id
    GROUP BY e.id
    ORDER BY e.tanggal ASC
";
$stmt_events = $db->prepare($sql_events);
$stmt_events->execute();
$events = $stmt_events->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .hidden {
            display: none;
        }

        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>

<body class="p-6 bg-sky-900 poppins-regular">
    <div class="mb-5 flex flex-col sm:flex-row items-center justify-between">
        <img class="w-32 h-22" src="../photo/uvent-logo-putih.png"></img>
        <h1 class="text-3xl font-bold text-white text-center mb-5 mt-4">Welcome, <?= htmlspecialchars($admin['username']) ?>! What do you want to do today?</h1>
        <div class="flex items-center space-x-4">
            <a href="../admin/create_event.php">
                <span class="material-icons-outlined text-white mt-2 ml-2">add_circle</span>
            </a>
            <a href="../admin/create_event.php" class="bg-blue-200 text-black font-bold px-5 py-2 rounded-md hover:bg-blue-400 transition">Create Event</a>
            <a href="profile_admin.php?admin_id=<?= htmlspecialchars($_SESSION['admin_id']) ?>">
                <img src="<?= htmlspecialchars($admin['filepath']) ?>" class="hover:animation-spin mr-6 w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24 rounded-full cursor-pointer object-cover hover:opacity-50"
                    alt="Profile Photo">
            </a>
        </div>
    </div>

    <div class="mb-5 flex space-x-2">
        <input type="text" id="searchInput" placeholder="Search event..."
            class="w-full px-4 py-2 border-blue-100 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button id="searchButton" class="bg-sky-500 text-white px-4 py-2 rounded hover:bg-sky-600">Search</button>
        <button id="resetButton" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Reset</button>
    </div>

    <div id="eventGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (count($events) > 0): ?>
            <?php foreach ($events as $event): ?>
                <div class="event-card bg-white p-4 rounded-lg shadow-md"
                    data-title="<?= strtolower(htmlspecialchars($event['nama_event'])) ?>">
                    <h2 class="text-xl font-bold mb-2"><?= htmlspecialchars($event['nama_event']) ?></h2>
                    <p class="text-gray-600 mb-2">Location : <?= htmlspecialchars($event['lokasi']) ?></p>
                    <p class="text-gray-600 mb-2">Time : <?= htmlspecialchars($event['jam']) ?></p>
                    <p class="text-gray-600 mb-2">Date : <?= htmlspecialchars($event['tanggal']) ?></p>
                    <p class="text-gray-600">Participants :
                        <?= htmlspecialchars($event['registered_count']) . ' / ' . htmlspecialchars($event['max_participants']) ?>
                    </p>

                    <form action="update_registration_status.php" method="POST" class="inline text-gray-600">
                        <label for="registration_status_<?= $event['registration_status'] ?>">Registration : </label>
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <select name="registration_status" class="bg-white border rounded px-2 py-1"
                            onchange="this.form.submit()">
                            <option value="yes" <?= $event['registration_status'] == 'yes' ? 'selected' : '' ?>>Open</option>
                            <option value="no" <?= $event['registration_status'] == 'no' ? 'selected' : '' ?>>Closed</option>
                        </select>
                    </form>

                    <form action="update_main_event.php" method="POST" class="text-gray-600 main-event-form">
                        <label for="main_event_<?= $event['id'] ?>">Main Event : </label>
                        <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                        <input type="radio" name="is_main_event" id="main_event_<?= $event['id'] ?>" class="main-event-radio"
                            value="<?= $event['id'] ?>" <?= $event['is_main_event'] ? 'checked' : '' ?>
                            data-id="<?= $event['id'] ?>">
                    </form>

                    <div class="flex flex-col md:flex-row justify-between items-center mt-4 space-y-2 md:space-y-0 md:space-x-4">
                        <a href="detail_event.php?id=<?= $event['id'] ?>"
                            class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 text-center w-full md:w-auto">
                            Participant
                        </a>
                        <a href="edit_event.php?id=<?= $event['id'] ?>"
                            class="bg-yellow-500 text-white px-6 py-2 rounded hover:bg-yellow-600 text-center w-full md:w-auto">
                            Edit
                        </a>
                        <a href="delete_event.php?id=<?= $event['id'] ?>"
                            class="bg-red-500 text-white px-6 py-2 rounded hover:bg-red-600 text-center w-full md:w-auto"
                            onclick="return confirm('Are you sure you want to delete this event?');">
                            Delete
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-center col-span-1 sm:col-span-2 lg:col-span-3">No events found.</p>
        <?php endif; ?>
    </div>

    <script>
        $(document).on("change", ".main-event-radio", function() {
            $(this).closest(".main-event-form").trigger("submit");
        });

        function filterEvents() {
            const searchValue = document.getElementById("searchInput").value.toLowerCase();
            const eventCards = document.querySelectorAll(".event-card");

            eventCards.forEach(card => {
                const title = card.getAttribute("data-title");
                if (title.includes(searchValue)) {
                    card.classList.remove("hidden");
                } else {
                    card.classList.add("hidden");
                }
            });
        }

        function resetSearch() {
            document.getElementById("searchInput").value = "";
            const eventCards = document.querySelectorAll(".event-card");
            eventCards.forEach(card => card.classList.remove("hidden"));
        }
        document.getElementById("searchButton").addEventListener("click", filterEvents);
        document.getElementById("resetButton").addEventListener("click", resetSearch);
        document.getElementById("searchInput").addEventListener("keyup", function(event) {
            if (event.key === "Enter") {
                filterEvents();
            }
        });
    </script>
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
</body>

</html>