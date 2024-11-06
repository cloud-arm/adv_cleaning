<?php
session_start();
include('../config.php');




$insertData = array(
    "data" => array(
        "name" => $_POST['name'],
        "address" => $_POST['address'],
        "type" => $_POST['type'],
        "email" => $_POST['email'],
    ),
    "other" => array(
    ),
);
insert("company", $insertData,'../');


header("location: ../company");
 