<?php
session_start();
include('../config.php');

$insertData = array(
    "data" => array(
        "name" => $_POST['name'],
        "address" => $_POST['address'],
        "type" => 'retail',
        "email" => $_POST['email'],
        "contact" => $_POST['contact'],
    ),
    "other" => array(
    ),
);
insert("company", $insertData,'../');


header("location: ../customer");
 