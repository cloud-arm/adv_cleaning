<?php
session_start();
include('config.php');
date_default_timezone_set("Asia/Colombo");

$userid = $_SESSION['SESS_MEMBER_ID'];
$username = $_SESSION['SESS_FIRST_NAME'];
$date = date("Y-m-d");
$time = date('H:i:s');

$id = $_POST['id'];
$new_stock = $_POST['new_stock'];
$note = $_POST['note'];

$result = select("materials", "*", "id = '" . $id . "' ");
for ($i = 0; $row = $result->fetch(); $i++) {
    $name = $row['name'];
    $qty = $row['available_qty'];
    //$cost = $row['cost_price'];
}

$stock_id = 0;
$result = select("stock", "id", "product_id = '" . $id . "' ");
for ($i = 0; $row = $result->fetch(); $i++) {
    $stock_id = $row['id'];
}


if ($new_stock != $qty) {
    if ($new_stock > $qty) {
        $wastage = $new_stock - $qty;
    }
    if ($qty > $new_stock) {

        $wastage = $qty - $new_stock;
    }

   // $wastage_amount = $wastage * $cost;

    query("UPDATE  materials SET available_qty = '$new_stock' WHERE id = '$id' ");

   query("UPDATE  stock SET qty_balance = '$new_stock' WHERE id = '$stock_id' ");
}


$insertData = array(
    "data" => array(
        "product_id" => $id,
        "product_name" => $name,
        "stock_id" => $stock_id,
        "pr_stock" => $qty,
        "new_stock" => $new_stock,
        "note" => $note,
        "cost" => '',
        "wastage" => '',
        "wastage_amount" => '',
        "date" => $date,
        "time" => $time,
        "userid" => $userid,
        "username" => $username,
    ),
    "other" => array(
        "data_id" => $id,
        "data_name" => "stock_adjustment",
    ),
);
$status = insert("stock_adjustment", $insertData);
echo '<br>Line 70 => Status: ' . $status['status'] . ' || Message: ' . $status['message'];

header("location: stock_adjustment.php");
