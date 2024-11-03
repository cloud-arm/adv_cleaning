<?php
include("connect.php");

// Function to compress and save the image
function compressImage($source, $destination, $quality)
{
    // Get image info
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file 
    switch ($mime) {
        case 'image/jpeg':
        case 'image/jpg':
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality); // Quality for JPEG (0-100)
            break;
        case 'image/png':
        case 'image/PNG':
            $image = imagecreatefrompng($source);
            // PNG compression level is from 0 (no compression) to 9 (maximum compression)
            $pngCompressionLevel = 9 - floor($quality / 10); // Convert JPEG quality (0-100) to PNG compression (0-9)
            imagepng($image, $destination, $pngCompressionLevel);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            imagegif($image, $destination);
            break;
        default:
            return false; // Invalid image type
    }

    return $destination;
}

// Set the upload path for images
$uploadPath = "uploads/product_img/";
if (!file_exists($uploadPath)) {
    mkdir($uploadPath, 0777, true); // Create the folder if it doesn't exist
}

// Check if a file is uploaded
if (!empty($_FILES["fileToUpload"]["name"])) {
    $fileName = date('ymdHis') . '.' . pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
    $imageUploadPath = $uploadPath . $fileName;
    $fileType = strtolower(pathinfo($imageUploadPath, PATHINFO_EXTENSION));

    // Allow certain file formats
    $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
    if (in_array($fileType, $allowTypes)) {
        $imageTemp = $_FILES["fileToUpload"]["tmp_name"];
        
        // Compress the image and upload
        $compressedImage = compressImage($imageTemp, $imageUploadPath, 60);

        if ($compressedImage) {
            // Update the image name for the selected ID in the sales_list table
            $sql = 'UPDATE sales_list SET m_img = ? WHERE id = ?';
            $stmt = $db->prepare($sql);
            $stmt->execute([$fileName, $_POST['id']]);

            echo "Image uploaded and updated successfully.";
        } else {
            echo "Failed to compress and upload image.";
        }
    } else {
        echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
} else {
    echo "Please upload an image.";
}
?>
