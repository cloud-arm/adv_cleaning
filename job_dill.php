<?php
include 'connect.php'; // include your database connection
include 'config.php'; // include your config file

if (isset($_GET['id'])) {
    $job_id = $_GET['id'];

   /* $r=select_item('job','id','id='.$job_id);
    $id=$r['id'];
    */

    $result = update('job', ['dll' => '1'], 'id='.$job_id, '');

    $r2 = update('sales_list', ['action' => '1'], 'job_no='.$job_id, '');

    header("Location: index.php");


    
    
}
?>
