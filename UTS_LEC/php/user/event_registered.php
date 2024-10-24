<?php
session_start();
require_once('../db.php');

$user_id = $_SESSION['user_id'];

$sql_registered_events = "
    SELECT ep.id, e.nama_event, e.lokasi, e.jam, e.tanggal, e.participant
    FROM event_participant ep
    INNER JOIN detail_event e ON ep.event_id = e.id
    WHERE ep.user_id = :user_id
    ORDER BY e.tanggal ASC
";
$stmt_registered_events = $db->prepare($sql_registered_events);
$stmt_registered_events->execute(['user_id' => $user_id]);
$registered_events = $stmt_registered_events->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Events</title>
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
    <a href="dashboard_user.php" class="text-blue-500 hover:text-blue-700 font-bold text-lg hover:underline">&larr; Back</a>
    <h3 class="font-bold text-3xl text-center mt-5">Registered Events</h3>

    <div class="mt-4 max-w-screen-lg mx-auto bg-white p-6 md:p-8 rounded-lg shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white">
                <thead>
                    <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-4 text-left">No</th>
                        <th class="py-3 px-4 text-left">Nama Event</th>
                        <th class="py-3 px-4 text-left">Lokasi</th>
                        <th class="py-3 px-4 text-left">Jam</th>
                        <th class="py-3 px-4 text-left">Tanggal</th>
                        <th class="py-3 px-4 text-center">Check</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    <?php if (empty($registered_events)): ?>
                        <tr>
                            <td colspan="6" class="py-3 px-4 text-center">You have not registered for any events.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($registered_events as $index => $event): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-4"><?= $index + 1 ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($event['nama_event']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($event['lokasi']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars($event['jam']) ?></td>
                                <td class="py-3 px-4"><?= htmlspecialchars(date('Y-m-d', strtotime($event['tanggal']))) ?></td>

                                <td class="py-3 px-4 text-center">
                                    <?php
                                    $sql_event_id = "SELECT event_id FROM event_participant WHERE id = :participant_id";
                                    $stmt_event_id = $db->prepare($sql_event_id);
                                    $stmt_event_id->execute(['participant_id' => $event['id']]);
                                    $result = $stmt_event_id->fetch(PDO::FETCH_ASSOC);
                                    $event_id = $result['event_id'] ?? null;
                                    ?>

                                    <?php if ($event_id): ?>
                                        <div class="flex space-x-2 justify-center">
                                            <a href="detail_event_user.php?event_id=<?= $event_id ?>"
                                                class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 text-sm md:text-base">Detail</a>
                                            <a href="delete_event.php?event_id=<?= $event_id ?>"
                                                class="bg-red-500 text-white px-2 py-1 rounded hover:bg-red-600 text-sm md:text-base">Delete</a>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-red-500">Event not found</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>