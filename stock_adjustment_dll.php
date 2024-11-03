<?php
session_start();
include('config.php');
date_default_timezone_set("Asia/Colombo");

$userid = $_SESSION['SESS_MEMBER_ID'];
$username = $_SESSION['SESS_FIRST_NAME'];

$id = $_GET['id'];


$result = select("stock_adjustment", "*", "id = '" . $id . "' ");
for ($i = 0; $row = $result->fetch(); $i++) {
    $stock_id = $row['stock_id'];
    $product_id = $row['product_id'];
    $pr_stock = $row['pr_stock'];
    $new_stock = $row['new_stock'];
    $name = $row['product_name'];
   // $cost = $row['cost'];
   // $wastage = $row['wastage'];
    //$wastage_amount = $row['wastage_amount'];
}

$date = date("Y-m-d");
$time = date('H:i:s');

/*    
    if ($new_stock > $pr_stock) {
       // $wastage = $new_stock - $pr_stock;
    }
    if ($pr_stock > $new_stock) {

        $wastage = $pr_stock - $new_stock;
    }
        */

   // $wastage_amount = $wastage * $cost;

    query("UPDATE  materials SET available_qty = '$pr_stock' WHERE id = '$product_id' ");

    query("UPDATE  stock SET qty_balance = '$pr_stock' WHERE id = '$stock_id' ");


$note = 'Stock Adjustment Delete';

/*
$insertData = array(
    "data" => array(
        "product_id" => $product_id,
        "product_name" => $name,
        "stock_id" => $stock_id,
        "pr_stock" => $new_stock,
        "new_stock" => $pr_stock,
        "note" => $note,
        "cost" => $cost,
        "wastage" => $wastage,
        "wastage_amount" => $wastage_amount,
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
*/

// Now, proceed to delete the original stock adjustment record
$queryResult = query("DELETE FROM stock_adjustment WHERE id = '$id'");
if ($queryResult) {
    echo "Stock adjustment record deleted successfully!";
} else {
    echo "Error: Could not delete the stock adjustment record!";
}


header("location: stock_adjustment.php");
