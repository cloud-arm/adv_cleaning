<?php
session_start();
include('../../config.php');


$id = $_GET['id'];
$job_no=$_GET['job_no'];


update('sales_list', ['status'=>'delete'], 'id='.$id, '../../');


// Redirect back to the job details page
header("location: ../../job_view.php?id=" . base64_encode($job_no));

exit;
