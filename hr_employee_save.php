<?php
session_start();
include('connect.php');
date_default_timezone_set("Asia/Colombo");

$name = $_POST['name'];
$nickname = $_POST['nickname'];
$phone_no = $_POST['phone_no'];
$address = $_POST['address'];
$nic = $_POST['nic'];
$etf_no = $_POST['epf_no'];
$etf_amount = $_POST['epf_amount'];
$des_id = $_POST['des'];
$rate = $_POST['rate'];
$well = $_POST['well']; 
$ot = $_POST['ot'];
$id = $_POST['id'];

/*if($_POST['des'] == '3'){
    $position = 'measurer';
} else {
    $position = 'admin';
}*/
$position = '';
$user_name = '';
$password = '';

if ($nickname == '') {
    $nickname = explode(' ', trim($name))[0];
}

 
    $user_name = $_POST['username'];
    $password = $_POST['password'];

    $user_name = $nickname;


$user_name = strtolower($user_name);

$attend_date = date('Y-m-d');
$type = '1';

$result = $db->prepare("SELECT * FROM employees_des WHERE id=:id ");
$result->bindParam(':id', $des_id);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $des_id = $row['id'];
    $des = $row['name'];
    $type = $row['type'];
    $position = $row['position'];
}

//$position = $des;

$imageUploadPath = '';

function compressImage($source, $destination, $quality)
{
    // Get image info 
    $imgInfo = getimagesize($source);
    $mime = $imgInfo['mime'];

    // Create a new image from file 
    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);
            break;
        case 'image/png':
            $image = imagecreatefrompng($source);
            imagepng($image, $destination, $quality);
            break;
        case 'image/gif':
            $image = imagecreatefromgif($source);
            imagegif($image, $destination, $quality);
            break;
        default:
            $image = imagecreatefromjpeg($source);
            imagejpeg($image, $destination, $quality);
    }

    // Return compressed image 
    return $destination;
}

// File upload path 
$uploadPath = "user_pic/";

// Check if the directory exists, if not, create it
if (!file_exists($uploadPath)) {
    mkdir($uploadPath, 0777, true); // Create the directory with full permissions (0777)
}


// If file upload form is submitted 
$status = $statusMsg = '';
if (!empty($_FILES["image"]["name"])) {
    $status = 'error';

    // File info 
    $fileName = $user_name . '.' . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $imageUploadPath = $uploadPath . $fileName;
    $fileType = pathinfo($imageUploadPath, PATHINFO_EXTENSION);

    // Allow certain file formats 
    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    if (in_array($fileType, $allowTypes)) {
        // Image temp source 
        $imageTemp = $_FILES["image"]["tmp_name"];

        // Compress size and upload image 
        $compressedImage = compressImage($imageTemp, $imageUploadPath, 60);

        if ($compressedImage) {
            $status = 'success';
            $statusMsg = "Image compressed successfully.";
        } else {
            $statusMsg = "Image compress failed!";
        }
    } else {
        $statusMsg = 'Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.';
    }
} else {
    $statusMsg = 'Please select an image file to upload.';
}
echo $statusMsg;
echo $id;
echo $des;
echo $des_id;

/*$result = $db->prepare("SELECT * FROM employees_des WHERE id=:id ");
$result->bindParam(':id', $des_id);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $des_id = $row['id'];
    $des = $row['name'];
    $type = $row['type'];
    $position = $row['position'];
}
*/

if ($id == 0) {
    // Check if position is Welder or Electrician
    if ($position == 'measurer') {
        $finalPosition = 'measurer';
    } else {
        $finalPosition = 'admin';
    }
    echo $des;
    echo $finalPosition;

    // Insert into employee table
    $sql = "INSERT INTO employee (name, type, phone_no, nic, address, attend_date, hour_rate, des, des_id, epf_no, epf_amount, ot, well, action, username, password, pic, position) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $q = $db->prepare($sql);
    $q->execute(array($name, $type, $phone_no, $nic, $address, $attend_date, $rate, $des, $des_id, $etf_no, $etf_amount, $ot, $well, 1, $user_name, $password, $imageUploadPath, $finalPosition));

    // Get the last inserted employee id and position
    $result = $db->prepare("SELECT * FROM employee ORDER BY id DESC LIMIT 1");
    $result->execute();
    $row = $result->fetch();
    //$position = $row['position']; 
    $employee_id = $row['id'];     

    echo $position;

  
        // Insert into user table
        $sql = 'INSERT INTO user (username, password, name, position, employee_id, upic) 
                VALUES (?, ?, ?, ?, ?, ?)';
        $q = $db->prepare($sql);
        $q->execute(array($user_name, $password, $name, $finalPosition, $employee_id, $imageUploadPath));
    
}
    else {

    $sql = "UPDATE employee SET name = ?, address = ?, nic = ?, phone_no = ?, hour_rate = ?, des = ?, des_id = ?, epf_amount = ?, epf_no = ?, ot = ?, well = ?, username = ?, password = ?, type = ?, pic = ? WHERE id = ? ";
    $q = $db->prepare($sql);
    $q->execute(array($name, $address, $nic, $phone_no, $rate, $des, $des_id, $etf_amount, $etf_no, $ot, $well, $user_name, $password, $type, $imageUploadPath, $id));
}

if (isset($_POST['end'])) {

   header("location: hr_employee_profile.php?id=$id");
} else {

   header("location: hr_employee.php");
}
