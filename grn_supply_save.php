<?php
session_start();
include('connect.php');

$id = $_POST['id'];
$name = $_POST['name'];
$address = $_POST['address'];
$contact = $_POST['contact'];
$person = $_POST['person'];
$mobile = $_POST['mobile'];
$email = $_POST['email'];


if ($id == '0') {
    $sql = "INSERT INTO supplier (name,contact,address,contact_person,mobile,email,action) VALUES (?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array($name, $contact, $address, $person, $mobile, $email, 1));
} else {
    $sql = "UPDATE  supplier SET name =?, contact =?, address =?, contact_person =?, mobile =?, email =? WHERE id =?";
    $ql = $db->prepare($sql);
    $ql->execute(array($name, $contact, $address, $person, $mobile, $email, $id));
}

header("location: grn_supply.php?id=0");
