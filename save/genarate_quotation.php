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

$old_id=select_item('sales','transaction_id',"job_no='$id' AND type='Quotation'", '../');

echo $old_id;
if($old_id > 0){
update('sales',['amount'=>$totalAmount,"comment" => 'No'],"transaction_id='$old_id'",'../');
}else{

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
//print_r($insertData);
$result5 = insert("sales", $insertData, '../');

if(!$result5) {
    die("Failed to insert into sales table.");
}
}



//$result2 = update('sales_list', ['invoice_no' => $invoiceNumber], 'job_no='.$id, '../');

//$result22 = update('job', ['action' => '2'], 'id='.$id, '../');

$cus_name=select_item('sales','customer_name',"job_no='$id' AND type='Quotation'", '../');
$text="Dear  $cus_name,Thank you for choosing Advanced Cleaning Services! Please find your quote attached. We look forward to your feedback and are excited to provide you with our top-quality service.Best regards,The Advanced Cleaning Services Team";
header("location: ../pdf/invoice.php?id=$id&type=2&text=$text");


