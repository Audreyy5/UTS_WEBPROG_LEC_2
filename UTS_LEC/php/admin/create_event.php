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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_event = $_POST['nama_event'];
    $lokasi = $_POST['lokasi'];
    $jam = $_POST['jam'];
    $tanggal = $_POST['tanggal'];
    $participant = $_POST['participant'];
    $description = $_POST['description'];
    $is_main_event = isset($_POST['is_main_event']) ? 1 : 0; 

    if (strlen($description) > 300) {
        echo "<script>
                alert('Description cannot exceed 300 characters.');
                window.location.href = 'dashboard_admin.php';
              </script>";
    } else {
        $uploaded_files = $_FILES['photos'];
        if (count($uploaded_files['name']) > 3) {
            echo "<script>
                    alert('You can upload a maximum of 3 photos.');
                    window.location.href = 'dashboard_admin.php';
                  </script>";
            exit();
        }

        $sql_insert = "INSERT INTO detail_event (nama_event, lokasi, jam, tanggal, participant, description, is_main_event) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert = $db->prepare($sql_insert);

        if ($stmt_insert->execute([$nama_event, $lokasi, $jam, $tanggal, $participant, $description, $is_main_event])) {
            $event_id = $db->lastInsertId();

            $allowed_extensions = ['jpg', 'jpeg', 'png'];
            $errors = [];

            for ($i = 0; $i < count($uploaded_files['name']); $i++) {
                $file_name = $uploaded_files['name'][$i];
                $file_tmp = $uploaded_files['tmp_name'][$i];

                $file_ext = explode(".", $file_name);
                $file_ext = end($file_ext);
                $file_ext = strtolower($file_ext);

                if (in_array($file_ext, $allowed_extensions)) {
                    move_uploaded_file($file_tmp, "../uploads/{$file_name}");
                    $file_path = "../uploads/{$file_name}";
                    $sql_photo_insert = "INSERT INTO event_photos (event_id, filepath) VALUES (?, ?)";
                    $stmt_photo_insert = $db->prepare($sql_photo_insert);
                    $stmt_photo_insert->execute([$event_id, $file_path]);
                } else {
                    $errors[] = "Only .jpg, .jpeg, .png files are allowed.";
                }
            }

            if (empty($errors)) {
                echo "<script>
                        alert('Event successfully created.');
                        window.location.href = 'dashboard_admin.php';
                      </script>";
            } else {
                echo "<script>
                        alert('" . implode("\\n", $errors) . "');
                        window.location.href = 'dashboard_admin.php';
                      </script>";
            }
        } else {
            echo "<script>
                    alert('Error creating event. Please try again.');
                    window.location.href = 'dashboard_admin.php';
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
    <title>Create Event</title>
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
        <h1 class="text-3xl font-bold mb-10 text-center text-white ">Create New Event</h1>
        <form method="POST" action="create_event.php" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_event">Nama Event</label>
                <input type="text" name="nama_event" id="nama_event" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                <textarea name="description" id="description" rows="4" maxlength="300" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Describe the event (max 300 characters)"></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="lokasi">Lokasi</label>
                <input type="text" name="lokasi" id="lokasi" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="jam">Jam</label>
                <input type="time" name="jam" id="jam" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="tanggal">Tanggal</label>
                <input type="date" name="tanggal" id="tanggal" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="participant">Participant</label>
                <input type="text" name="participant" id="participant" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="photos">Upload Photos (1-3)</label>
                <input type="file" name="photos[]" id="photos" accept=".jpg, .jpeg, .png" multiple required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" />
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Create Event</button>
                <a href="dashboard_admin.php" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>