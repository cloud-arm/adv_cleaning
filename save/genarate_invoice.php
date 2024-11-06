<?php
session_start();
include('../config.php');

if (isset($_GET['id'])) {
    $id = $_GET['id']; 
} 

$totalAmount = 0;
$result = select('sales_list', '*', 'job_no = ' . $id . ' AND amount > 0', '../');

while ($row = $result->fetch()) { 
    $totalAmount += $row['amount']; // Sum the amounts
}

$invoiceNumber = date('YmdHis'); 

$insertData = array(
    "data" => array(
        "customer_id" => $id,
       "customer_name" => select_item('job', 'name', 'id=' . $id, '../'),
        "invoice_number" => $invoiceNumber,
        "cashier" => $_SESSION['SESS_MEMBER_ID'],
        "action" => '1',
        "date" => date('Y-m-d'), 
        "pay_type" => 'credit',
        "amount" => $totalAmount,
        "comment" => 'No',
        "job_no" => $id,
    ),
    "other" => array(),
);
print_r($insertData);
$result5 = insert("sales", $insertData, '../');

if(!$result5) {
    die("Failed to insert into sales table.");
}


$insertData2 = array(
    "data" => array(
      "invoice_no" => $invoiceNumber,
        "type" => '1',
        "action" => '1',
       "date" => date('Y-m-d'), 
        "pay_type" => 'credit',
        "amount" => $totalAmount,
        "time" => date('H:i:s'),
        "job_no" => $id,
    ),
    "other" => array(),
);

$result3 = insert("payment", $insertData2, '../');

update('payment', ['credit_balance' => $totalAmount], "job_no = $id AND pay_type = 'credit'", '../');


$result2 = update('sales_list', ['invoice_no' => $invoiceNumber], 'job_no='.$id, '../');

$result22 = update('job', ['action' => '5'], 'id='.$id, '../');


//Uncomment the line below to redirect after completion
header("location: print?id=$id");

?>
