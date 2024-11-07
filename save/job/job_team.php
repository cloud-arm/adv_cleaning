<?php
session_start();
include('../../config.php');


foreach($_POST['location_id'] as $id){
$com_id=$_POST['company_id'];
$insertData = array(
    "data" => array(
        "name" => select_item('employee','username','id='.$id,'../../'),
        "emp_id" => $id,
        "company_id" => $_POST['company_id'],
        "company_name" => select_item('customer','name','id='.$com_id,'../../'),
        "job_id" => $_POST['job_id'],
        "user_id" => $_SESSION['SESS_MEMBER_ID'],
        "action" => '1',
        "status" => 'pending',
    ),
    "other" => array( 
    ),
);
$result=insert("job_team", $insertData,'../../');
}

$updateData = ["action" => 3];
update("job", $updateData, "id= '" . $_POST['job_id'] . "'",'../../');


$job_id=base64_encode($_POST['job_id']);

//echo $result['status'];
header("location: ../../job_view.php?id=$job_id");
