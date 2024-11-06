<?php
session_start();
include('../../config.php');


$id = $_POST['id'];

$price = $_POST['price'];
$job_id=$_POST['job_no'];
$type=$_POST['type'];



if($type=='indirect'){

    $qty=select_item('sales_list','qty','id='.$id, '../../');
    $amount = $price * $qty; 
    $result = update('sales_list', ['amount'=>$amount,'price'=>$price], 'id='.$id, '../../');
}else{

$product_name = select_item('products','product_name',"id='$id'",'../../');
$date=date('Y-m-d');
$insertData = array(
    "data" => array(
        "name" => $product_name,
        "qty" => $_POST['qty'],
        "job_no" => $job_id,
        "price" => $price,
        "amount" => $price*$_POST['qty'],
        "product_id" => $id,
        "date" => $date,
        "status" =>'complete',
        "status_id" =>'5',
        "type" =>'direct'
    ),
    "other" => array(
    ),
);

// Insert the data into the sales_list table
insert("sales_list", $insertData, '../../');

}


// Redirect back to the job details page
header("location: ../../job_view.php?id=" . base64_encode($job_id));

exit;
