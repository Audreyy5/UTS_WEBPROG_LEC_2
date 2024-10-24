<?php
session_start();
require_once('../db.php');

if (!isset($_SESSION['admin_id'])) {
    echo "<script>
            alert('Session has expired or you are not logged in. Please login again.');
            window.location.href = '../login/login_admin.php';
          </script>";
    exit();
}

if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    $sql_event = "SELECT id, nama_event, lokasi, jam, tanggal, participant, description FROM detail_event WHERE id = ?";
    $stmt_event = $db->prepare($sql_event);
    $stmt_event->execute([$event_id]);
    $event = $stmt_event->fetch(PDO::FETCH_ASSOC);

    if (!$event) {
        echo "<script>
                alert('Event not found.');
                window.location.href = 'dashboard_admin.php';
              </script>";
        exit();
    }

    $sql_photos = "SELECT id, filepath FROM event_photos WHERE event_id = ?";
    $stmt_photos = $db->prepare($sql_photos);
    $stmt_photos->execute([$event_id]);
    $photos = $stmt_photos->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<script>
            alert('Invalid event ID.');
            window.location.href = 'dashboard_admin.php';
          </script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_event = $_POST['nama_event'];
    $lokasi = $_POST['lokasi'];
    $jam = $_POST['jam'];
    $tanggal = $_POST['tanggal'];
    $participant = $_POST['participant'];
    $description = $_POST['description'];
    $existing_photo_ids = $_POST['existing_photos'] ?? [];

    if (strlen($description) > 300) {
        echo "<script>
                alert('Description cannot exceed 300 characters.');
              </script>";
    } else {
        $sql_update = "UPDATE detail_event SET nama_event = ?, lokasi = ?, jam = ?, tanggal = ?, participant = ?, description = ? WHERE id = ?";
        $stmt_update = $db->prepare($sql_update);

        if ($stmt_update->execute([$nama_event, $lokasi, $jam, $tanggal, $participant, $description, $event_id])) {

            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            $uploaded_files = $_FILES['photos'];
            $errors = [];

            $total_uploaded = count($uploaded_files['name']);
            $existing_count = count($photos);

            if ($total_uploaded + $existing_count > 3) {
                echo "<script>
                        alert('You can upload a maximum of 3 photos in total, including existing ones.');
                        window.location.href = 'dashboard_admin.php';
                      </script>";
            }

            if (!empty($uploaded_files['name'][0])) {
                for ($i = 0; $i < $total_uploaded; $i++) {
                    $file_name = $uploaded_files['name'][$i];
                    $file_tmp = $uploaded_files['tmp_name'][$i];

                    $file_ext = explode(".", $file_name);
                    $file_ext = end($file_ext);
                    $file_ext = strtolower($file_ext);

                    switch ($file_ext) {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                            move_uploaded_file($file_tmp, "../uploads/{$file_name}");
                            $file_path = "../uploads/{$file_name}";
                            $sql_photo_insert = "INSERT INTO event_photos (event_id, filepath) VALUES (?, ?)";
                            $stmt_photo_insert = $db->prepare($sql_photo_insert);
                            $stmt_photo_insert->execute([$event_id, $file_path]);
                            break;
                        default:
                            echo "Anda hanya bisa upload file .jpg .jpeg .png";
                            exit();
                    }
                }
            }

            if (!empty($_POST['delete_photos'])) {
                foreach ($_POST['delete_photos'] as $photo_id) {
                    $sql_delete_photo = "SELECT filepath FROM event_photos WHERE id = ?";
                    $stmt_delete_photo = $db->prepare($sql_delete_photo);
                    $stmt_delete_photo->execute([$photo_id]);
                    $photo_to_delete = $stmt_delete_photo->fetch(PDO::FETCH_ASSOC);

                    if ($photo_to_delete) {
                        if (unlink($photo_to_delete['filepath'])) {
                            $sql_remove_photo = "DELETE FROM event_photos WHERE id = ?";
                            $stmt_remove_photo = $db->prepare($sql_remove_photo);
                            $stmt_remove_photo->execute([$photo_id]);
                        } else {
                            echo "<script>alert('Error deleting the photo from the server.');</script>";
                        }
                    }
                }
            }

            if (count($existing_photo_ids) + $total_uploaded < 1) {
                echo "<script>
                        alert('You must have at least one photo.');
                      </script>";
            } else {
                echo "<script>
                        alert('Event successfully updated.');
                        window.location.href = 'dashboard_admin.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error updating event. Please try again.');
                  </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .poppins-regular {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>

<body class="p-6 bg-sky-700 poppins-regular">
    <a href="../admin/dashboard_admin.php" class="text-white hover:text-blue-500 ml-6 font-bold text-lg hover:underline">&larr;
        Back</a>
    <div class="mb-5">
        <h1 class="text-3xl text-white font-bold mb-10 text-center">Edit Event</h1>
        <form method="POST" action="edit_event.php?id=<?= $event['id'] ?>" enctype="multipart/form-data"
            class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_event">Nama Event</label>
                <input type="text" name="nama_event" id="nama_event" required
                    value="<?= htmlspecialchars($event['nama_event']) ?>"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                <textarea name="description" id="description" rows="4" maxlength="300" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    placeholder="Describe the event (max 300 characters)"><?= htmlspecialchars($event['description']) ?></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" required value="<?= htmlspecialchars($event['lokasi']) ?>"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="jam">Jam</label>
                <input type="time" name="jam" id="jam" required value="<?= htmlspecialchars($event['jam']) ?>"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" required
                    value="<?= htmlspecialchars($event['tanggal']) ?>"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="participant">Participant</label>
                <input type="text" name="participant" id="participant" required
                    value="<?= htmlspecialchars($event['participant']) ?>"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="photos">Upload New Photos (Max 3)</label>
                <input type="file" name="photos[]" id="photos" accept=".jpg, .jpeg, .png" multiple
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Existing Photos</label>
                <div>
                    <?php foreach ($photos as $photo): ?>
                        <div class="flex items-center mb-2">
                            <img src="<?= htmlspecialchars($photo['filepath']) ?>" alt="Photo"
                                class="w-20 h-20 mr-2 object-cover">
                            <input type="checkbox" name="delete_photos[]" value="<?= $photo['id'] ?>" class="mr-2">
                            <span>Delete</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="existing_photos[]" value="<?= implode(',', array_column($photos, 'id')) ?>">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Update
                    Event</button>
                <a href="dashboard_admin.php"
                    class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>