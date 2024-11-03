<?php
session_start();
include('connect.php');
include('config.php');
//$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$name = $_POST['name'];
$category_id = $_POST['category'];
$type = '';
$price = $_POST['price'];
$cost = 0;
$stock = 0;



if ($type == 'Materials') {
    $cost = $_POST['cost'];
    $stock = 1;
}

$category=select_item('category','name',"id=".$category_id);

// query
$sql = "INSERT INTO products (product_name,cat,type,cat_id,stock_action,price) VALUES (?,?,?,?,?,?)";
$ql = $db->prepare($sql);
$ql->execute(array($name, $category,  $type, $category_id, $stock, $price));




header("location: product.php");
