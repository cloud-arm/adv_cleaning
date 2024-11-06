<?php
session_start();
include('../config.php');




$insertData = array(
    "data" => array(
        "name" => $_POST['name'],

    ),
    "other" => array(
    ),
);
insert("material_category", $insertData,'../');


header("location: ../material");
 