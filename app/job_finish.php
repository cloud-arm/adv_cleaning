<?php 

include("../connect.php");
include("../config.php");
date_default_timezone_set("Asia/Colombo");

$id=$_GET['id'];
$type=$_GET['type']; 
if($type==1){
    $action="Runing";
    $action_id=4;
    $job_ac='Started';
    $job_ac_id=3;
} 
if($type==2){
    $action='complete';
    $action_id=5;
    $job_ac='finish';
    $job_ac_id=4;
    
}

$sql = "UPDATE  sales_list SET status_id=?,status=? WHERE job_no=?";
$ql = $db->prepare($sql);
$ql->execute(array($action_id,$action, $id));

$sql = "UPDATE  job SET status=?,action=? WHERE id=?";
$ql = $db->prepare($sql);
$ql->execute(array($job_ac,$job_ac_id, $id));



header("location: index.php");