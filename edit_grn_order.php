<?php

session_start();
include('config.php');

$id=$_POST['id'];
$qty = $_POST['qty'];
$price = $_POST['sell'];

echo $id;

$result = update('purchases_list', ['qty' => $qty , 'sell' => $price,'amount' => $price*$qty], 'id='.$id, '');

$r1 = select_query("SELECT * FROM purchases_list WHERE id='$id' ");
$i = 1; // Initialize counter for numbering rows
while ($row = $r1->fetch()) {

    $invo = $row['invoice_no'];
}

header("location:grn_order_view.php?id=$invo");

?>