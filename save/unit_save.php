<?php
session_start();
include('../config.php');
include('../connect.php');


$product_id=$_POST['product'];
$unit_id=$_POST['unit'];
$value=$_POST['value'];

$product=select_item('materials','name','id='.$product_id,'../');
$unit=select_item('unit','name','id='.$unit_id,'../');


$insertData = array(
    "data" => array(
        "unit" => $unit,
        "unit_id" => $unit_id,
        "product" => $product,
        "product_id" => $product_id,
        "unit_value"=>$value,
    ),
    "other" => array(
    ),
);
$result=insert("unit_record", $insertData,'../');


//print_r( $result);
header("location: ../unit");

?>