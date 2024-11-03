<?php
include 'connect.php'; // Include your database connection setup
include 'config.php'; // Include your configuration file


if (isset($_GET['mat_id'])) {
    $product_id = $_GET['mat_id'];


    $name=select_item('materials','unit','id='.$product_id);
    echo "<option value='0'>{$name}</option>";
    // Query to get units for the selected product
    $query = $db->prepare("SELECT * FROM unit_record WHERE product_id = ?");
    $query->execute([$product_id]);

    // Generate options for the Unit dropdown
    while ($row = $query->fetch()) {
        echo "<option value='{$row['id']}'>{$row['unit']}</option>";
    }
}
?>
