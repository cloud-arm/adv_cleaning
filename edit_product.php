<?php

session_start();
include('config.php');

$id=$_POST['id'];
$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');

//$name = $_POST['name'];
$category = $_POST['category'];
$type = $_POST['type'];

echo $id;

$result = update('products', ['product_name' => $name , 'cat' => $category,'type' => $type], 'id='.$id, '');

header("location:product.php");

?>