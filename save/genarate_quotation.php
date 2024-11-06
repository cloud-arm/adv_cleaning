<?php
session_start();
include('../config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id']; 
} 

$totalAmount = 0;
$result = select('sales_list', '*', 'job_no = ' . $id . " AND  status !='delete' ", '../');

while ($row = $result->fetch()) { 
    $totalAmount += $row['amount']; // Sum the amounts
}

$invoiceNumber = 'QT'.date('ymdHis'); 

$insertData = array(
    "data" => array(
        "customer_id" => $id,
       "customer_name" => select_item('job', 'name', 'id=' . $id, '../'),
        "invoice_number" => $invoiceNumber,
        "cashier" => $_SESSION['SESS_MEMBER_ID'],
        "action" => '20',
        "date" => date('Y-m-d'), 
        "pay_type" => 'credit',
        "amount" => $totalAmount,
        "comment" => 'No',
        "type" => 'Quotation',
        "job_no" => $id,
    ),
    "other" => array(),
);
print_r($insertData);
$result5 = insert("sales", $insertData, '../');

if(!$result5) {
    die("Failed to insert into sales table.");
}




//$result2 = update('sales_list', ['invoice_no' => $invoiceNumber], 'job_no='.$id, '../');

$result22 = update('job', ['action' => '2'], 'id='.$id, '../');


//Uncomment the line below to redirect after completion
header("location: print?id=$id");

?>
