<?php
// Include your database connection file
include('connect.php');
include('config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the photo details from the sales_list table
    $result = select('sales_list', '*', 'id=' . $id);
    $row = $result->fetch();

    if ($row) {
        $approvel_doc = $row['approvel_doc'];
        $job_no = $row['job_no'];
    }
//echo $approvel_doc;
$result = update('img_hub', ['action' => 'dll'], "name='" . $approvel_doc . "'", '');


    // Handle photo re-upload
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['new_photo'])) {
        $fileExtension = pathinfo($_FILES['new_photo']['name'], PATHINFO_EXTENSION); // Get file extension
        $newPhotoName = date('YmdHis') . '.' . $fileExtension; // Filename based on timestamp
        $uploadDir = 'app/save/uploads/product_img/';
        $uploadFile = $uploadDir . $newPhotoName;

        // Unlink the old photo
        if (!empty($approvel_doc)) {
            unlink($uploadDir . $approvel_doc); // Delete the old file
        }

        // Upload the new photo
        if (move_uploaded_file($_FILES['new_photo']['tmp_name'], $uploadFile)) {
            // Update the database with the new photo name
            $sql = "UPDATE sales_list SET approvel_doc = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$newPhotoName, $id]);

            // Insert into img_hub table
            $insertData = array(
                "data" => array(
                    "type" => 'product_preview',
                    "job_no" => $job_no,
                    "source_id" => $id,
                    "date" => date('Y-m-d'),
                    "time" => date('H.i.s'),
                    "name" => $newPhotoName, // Save new photo with timestamp-based name
                    "action" => '',
                ),
                "other" => array(),
            );
            $result = insert("img_hub", $insertData, '');

            // Redirect to job view page
            header("location:job_view.php?id=" . base64_encode($job_no));
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
    <title>View and Re-upload Photo</title>
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
        <h3>Product Photo View</h3>
        
        <?php if (!empty($approvel_doc)) { ?>
        <div class="photo-wrapper">
            <!-- Display the large photo -->
            <img src="app/save/uploads/product_img/<?php echo $approvel_doc; ?>" alt="Uploaded Photo" style="max-width: 600px; height: auto;"><br>
            <!-- Download Button -->
            <a href="app/save/uploads/product_img/<?php echo $approvel_doc; ?>" class="btn" download>Download Photo</a>
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
        <p class="no-photo">A picture is not available at this time.</p>
        <div class="upload-form">
            <form method="POST" enctype="multipart/form-data">
                <label for="new_photo">Re-upload new photo:</label><br>
                <input type="file" name="new_photo" id="new_photo" required><br><br>
                <button type="submit">Upload New Photo</button>
            </form>
        </div>
        <?php } ?>
    </div>
</body>
</html>
