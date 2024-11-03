<?php
session_start();
include('config.php');
include('sub_class/cash_transaction.php');

$user_id = $_SESSION['SESS_MEMBER_ID'];
$user_name = $_SESSION['SESS_FIRST_NAME'];

$type = $_POST['type'];

$from = $_POST['acc_from'];
$to = $_POST['acc_to'];
$amount = $_POST['amount'];

$date = date("Y-m-d");
$time = date('H:i:s');


$status = cash_transaction($from, "Debit", $amount, "acc_transfer", $to);
echo '<br>Line: 20 ' .  $status['status'] . ' / ' . $status['message'];



$status = cash_transaction($to, "Credit", $amount, "acc_transfer", $from);
echo '<br>Line: 25 ' .  $status['status'] . ' / ' . $status['message'];



header("location: acc_transfer.php");
