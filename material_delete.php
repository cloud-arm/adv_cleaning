<?php
include 'connect.php'; // include your database connection
include 'config.php'; // include your config file

if (isset($_GET['id'])) {
    $location_id = $_GET['id'];

    $r=select_item('materials','id','id='.$location_id);
    $id=$r['id'];
    
    
    try {
        // Prepare the delete statement
        $stmt = $db->prepare("DELETE FROM materials WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Redirect back to the list page after deletion
        header("Location: material.php");
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
