<?php
session_start();
include('../../config.php');

$contact=$_POST['phone'];

if($_POST['cus_id'] == '0'){
    $insertData = array(
        "data" => array(
            "name" => $_POST['name'],
            "address" => $_POST['address'],
            "contact" => $_POST['phone']
        ),
        "other" => array(
        ),
    );
    $result=insert("customer", $insertData,'../../');
    $cus_id=select_item("customer",'id',"contact='$contact' ",'../../');

}else{
    $cus_id=$_POST['cus_id'];
}

 

$invo=date('ymdHis');
$insertData = array(
    "data" => array(
        "name" => select_item('customer','name','id='.$cus_id,'../../'),
        "note" => $_POST['note'],
        "company_id" => $cus_id,
        "date" => date('Y-m-d'),
        "time" => date('H.i.s'),
        "invoice_no" => $invo,
        "user_id" => $_SESSION['SESS_MEMBER_ID'],
        "action" => '1',
        "status" => 'pending',
        "dll" => '0',
    ),
    "other" => array(
    ),
);
$result=insert("job", $insertData,'../../');
$id=base64_encode(select_item('job','id','invoice_no='.$invo,'../../'));


whatsApp($contact,
'Welcome to Advanced Cleaning Services! Your job has been booked, and our team will contact you before arrival. Thank you for choosing us!',
'https://adcleaning.colorbiz.org/main/pages/forms/img/logo/logo.jpg');

//echo $result['status'];
header("location: ../../job_view.php?id=$id");