<?php
// Include your database connection file
include('connect.php');
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the approved photo details from the img_hub table
    $result = select('img_hub', '*', 'job_no=' . $id . ' AND type = "approve_img"');
    $row = $result->fetch();

    if ($row) {
        $photo_name = $row['name'];  // Get the photo name from the 'name' column
        $img_hub_id = $row['id'];  // Get the img_hub table ID
        //$name = $row['name'];
    }

    // Handle photo re-upload
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['new_photo'])) {
        $newPhoto = $_FILES['new_photo']['name'];
        $uploadDir = 'app/save/uploads/product_img/';
        $uploadFile = $uploadDir . basename($newPhoto);

        // Unlink the old photo
        if (!empty($photo_name)) {
            unlink($uploadDir . $photo_name); // Delete the old file
        }

        // Upload the new photo
        if (move_uploaded_file($_FILES['new_photo']['tmp_name'], $uploadFile)) {
            // Update the database with the new photo name
            $sql = "UPDATE img_hub SET name = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$newPhoto, $img_hub_id]);

            

            // Redirect to the same page to view the updated photo
            header("location:job_view.php?id=" . base64_encode($id));
            exit();
        } else {
            echo "Error uploading new photo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View and Re-upload Approved Photo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 20px;
        }
        h3 {
            color: #333;
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .photo-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }
        img {
            border: 4px solid #ddd;
            border-radius: 10px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin: 5px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #218838;
        }
        .upload-form {
            text-align: center;
            margin-top: 20px;
        }
        label {
            font-size: 16px;
            color: #555;
        }
        input[type="file"] {
            display: inline-block;
            margin-top: 10px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0069d9;
        }
        .no-photo {
            color: #ff4d4f;
            text-align: center;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>View Approved Photo</h3>
        
        <?php if (!empty($photo_name)) { ?>
        <div class="photo-wrapper">
            <!-- Display the large photo -->
            <img src="app/save/uploads/product_img/<?php echo $photo_name; ?>" alt="Uploaded Photo" style="max-width: 600px; height: auto;"><br>
            <!-- Download Button -->
            <a href="app/save/uploads/product_img/<?php echo $photo_name; ?>" class="btn" download>Download Photo</a>
        </div>
        
        <!-- Re-upload Form -->
        <div class="upload-form">
            <form method="POST" enctype="multipart/form-data">
                <label for="new_photo">Re-upload new photo:</label><br>
                <input type="file" name="new_photo" id="new_photo" required><br><br>
                <button type="submit">Upload New Photo</button>
            </form>
        </div>
        <?php } else { ?>
        <p class="no-photo">No photo available.</p>
        <?php } ?>
    </div>
</body>
</html>
