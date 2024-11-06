<?php
session_start();
include('../../config.php');

$id=$_GET['id'];
$app=$_GET['app'];

if($app==1){
    $result2 = update('sales_list', ['status' => 'approved','status_id' => '1'], 'job_no='.$id." AND status !='delete' ", '../../');
    $result22 = update('job', ['action' => '2'], 'id='.$id, '../../');
    $result22 = update('sales', ['comment' => 'approved'], ' job_no='.$id." AND type='Quotation' ", '../../');
}else{
    $result22 = update('job', ['action' => '0'], 'id='.$id, '../../');
}

header("location: ../../job_view?id=".base64_encode($id));
