<?php
session_start();
include('../../config.php');


$id = $_GET['id'];


$rs=update('price_fm', ['action'=>'5'], 'id='.$id, '../../');

print_r($rs);
// Redirect back to the job details page
header("location: ../../price_fm");

exit;
