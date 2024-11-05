<?php
session_start();
include('../config.php');



$id=$_POST['product_id'];
$from=$_POST['from'];
$to=$_POST['to'];
$price=$_POST['price'];



$insertData = array(
    "data" => array(
        "product" => select_item('products','product_name',"id='$id'",'../'),
        "product_id" => $id,
        "slb_from" => $from,
        "slb_to" => $to,
        "price" => $price,
        "date"=>date("Y-m-d"),
    ),
    "other" => array(
    ),
);
$result=insert("price_fm", $insertData,'../');


echo $result['status'];
header("location: ../price_fm");
