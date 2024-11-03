<?php

session_start();
include('config.php');

$id=$_POST['id'];
$note = $_POST['note'];
$all_job_no = $_POST['all_job_no'];

echo $id;

$result = update('job', ['all_job_no' => $all_job_no , 'note' => $note], 'id='.$id, '');

header("location:index.php");

?>