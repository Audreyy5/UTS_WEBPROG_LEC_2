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

$event_id = $_GET['id'] ?? null;
if (!$event_id) {
    echo "Event ID is required.";
    exit();
}

$sql_event_details = "
        SELECT e.nama_event, e.participant AS max_participants
        FROM detail_event e
        WHERE e.id = :event_id
    ";
$stmt_event_details = $db->prepare($sql_event_details);
$stmt_event_details->execute(['event_id' => $event_id]);
$event = $stmt_event_details->fetch(PDO::FETCH_ASSOC);

if (!$event) {
    echo "Event not found.";
    exit();
}

$sql_participants = "
        SELECT ep.username, u.email, ep.id AS participant_id
        FROM event_participant ep
        INNER JOIN users u ON ep.user_id = u.id
        WHERE ep.event_id = :event_id
    ";
$stmt_participants = $db->prepare($sql_participants);
$stmt_participants->execute(['event_id' => $event_id]);
$participants = $stmt_participants->fetchAll(PDO::FETCH_ASSOC);

$registered_count = count($participants);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
    .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>

<body class="bg-sky-900 p-6 poppins-regular">
    <a href="../admin/dashboard_admin.php" class="text-white hover:text-white font-bold text-lg hover:underline">&larr; Back</a>
    <div class="mt-10 container mx-auto bg-white p-8 rounded-lg shadow-lg">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
            <h1 class="text-2xl font-bold mb-4 md:mb-0"><?= htmlspecialchars($event['nama_event']) ?></h1>
            <a href="export_participants.php?event_id=<?= $event_id ?>"
                class="bg-teal-700 text-white px-4 py-2 rounded hover:bg-teal-900 text-center">
                Export to XLSX
            </a>
        </div>

        <strong class="block mb-4">Participants : <?= htmlspecialchars($registered_count) . ' / ' . htmlspecialchars($event['max_participants']) ?></strong>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white mt-4 border border-gray-200">
                <thead>
                    <tr class="w-full bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">Participant Name</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-center">Delete</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm">
                    <?php if (empty($participants)): ?>
                        <tr>
                            <td colspan="4" class="py-3 px-6 text-center">No participants has registered for this event.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($participants as $index => $participant): ?>
                            <tr class="border-b border-gray-200 hover:bg-gray-100">
                                <td class="py-3 px-6"><?= $index + 1 ?></td>
                                <td class="py-3 px-6"><?= htmlspecialchars($participant['username']) ?></td>
                                <td class="py-3 px-6"><?= htmlspecialchars($participant['email']) ?></td>
                                <td class="py-3 px-6 text-center">
                                    <a href="delete_participant.php?id=<?= $participant['participant_id'] ?>&event_id=<?= $event_id ?>"
                                        class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600"
                                        onclick="return confirm('Are you sure you want to delete this participant?');">
                                        Delete
                                    </a>
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