<?php
session_start();
include('../../config.php');

$id=$_GET['id'];
$app=$_GET['app'];

if($app==1){
    $date=$_GET['date'];
    if(strlen($_GET['date']) < 4){ $date=date('Y-m-d'); }
    $result2 = update('sales_list', ['status' => 'approved','status_id' => '1'], 'job_no='.$id." AND status !='delete' ", '../../');
    $result22 = update('job', ['action' => '2','app_date'=>$date], 'id='.$id, '../../');
    $result22 = update('sales', ['comment' => 'approved'], ' job_no='.$id." AND type='Quotation' ", '../../');
}else{
    $result22 = update('job', ['action' => '0'], 'id='.$id, '../../');
}

$text='Welcome to Advanced Cleaning Services! Your job has been booked, and our team will contact you before arrival. Thank you for choosing us!';
header("location: ../../pdf/invoice.php?id=$id&type=2&text=$text");