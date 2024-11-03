<?php
session_start();
include('connect.php');
date_default_timezone_set("Asia/Colombo");

$id = $_POST['id'];

$userid = $_SESSION['SESS_MEMBER_ID'];
$username = $_SESSION['SESS_FIRST_NAME'];

$err = 0;

$result = $db->prepare("SELECT * FROM purchases WHERE transaction_id = :id ");
$result->bindParam(':id', $id);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $invoice = $row['invoice_no'];
    $pay_type = $row['pay_type'];
    $amount = $row['amount'];
}

$result = $db->prepare("SELECT * FROM purchases_list WHERE invoice_no = :id ");
$result->bindParam(':id', $invoice);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
    $pid = $row['product_id'];
    $qty = $row['qty'];

    $result1 = $db->prepare("SELECT * FROM inventory WHERE invoice_no = :id AND product_id = '$pid' ");
    $result1->bindParam(':id', $invoice);
    $result1->execute();
    for ($i = 0; $row1 = $result1->fetch(); $i++) {
        $st_id = $row1['stock_id'];
    }

    $qty_blc = 0;
    $result1 = $db->prepare("SELECT * FROM stock WHERE id = :id  ");
    $result1->bindParam(':id', $st_id);
    $result1->execute();
    for ($i = 0; $row1 = $result1->fetch(); $i++) {
        $qty_blc = $row1['qty_balance'];
    }

    if ($qty > $qty_blc) {

        //$err = 1;
        $y = date("Y");
        $m = date("m");

        // header("location: grn_rp.php?year=$y&month=$m&err=$err");
    }
}

if ($err == 0) {

    $sql = "UPDATE purchases SET dll=? WHERE transaction_id=?";
    $q = $db->prepare($sql);
    $q->execute(array(1, $id));

    $sql = "UPDATE purchases_list SET action=? WHERE invoice_no=?";
    $q = $db->prepare($sql);
    $q->execute(array('', $invoice));

    $sql = "UPDATE supply_payment SET dll=? WHERE invoice_no=?";
    $q = $db->prepare($sql);
    $q->execute(array(1, $invoice));

    $date = date("Y-m-d");
    $time = date('H:i:s');

    $result = $db->prepare("SELECT * FROM inventory WHERE invoice_no = :id ");
    $result->bindParam(':id', $invoice);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $pid = $row['product_id'];
        $qty = $row['qty'];
        $cost = $row['cost'];
        $sell = $row['sell'];
        $name = $row['name'];
        $st_id = $row['stock_id'];

        $qty_blc = 0;
        $st_qty = 0;
        $re = $db->prepare("SELECT * FROM products WHERE product_code = :id ");
        $re->bindParam(':id', $pid);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $st_qty = $r['qty'];
            $code = $r['product_code'];
        }

        $qty_blc = $st_qty - $qty;

        $sql = "UPDATE  products SET qty = qty - ? WHERE product_code=?";
        $ql = $db->prepare($sql);
        $ql->execute(array($qty, $pid));

        $sql = "UPDATE  stock SET qty_balance = qty_balance - ? WHERE product_id=? AND invoice_no=?";
        $ql = $db->prepare($sql);
        $ql->execute(array($qty, $pid, $invoice));

        $sql = "INSERT INTO inventory (product_id,name,invoice_no,type,balance,qty,date,cost,sell,stock_id) VALUES (?,?,?,?,?,?,?,?,?,?)";
        $ql = $db->prepare($sql);
        $ql->execute(array($pid, $name, $invoice, 'out', $qty_blc, $qty, $date, $cost, $sell, $st_id));
    }

    if ($pay_type == 'Cash') {

        $cr_id = 2;

        $de_blc = 0;
        $blc = 0;
        $re = $db->prepare("SELECT * FROM cash WHERE id = $cr_id ");
        $re->bindParam(':userid', $res);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $blc = $r['amount'];
            $cr_name = $r['name'];
        }

        $de_blc = $blc + $amount;


        $cr_type = 'grn_payment_delete';

        $sql = "INSERT INTO transaction_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $ql = $db->prepare($sql);
        $ql->execute(array('grn_delete', 'Credit', $invoice, $amount, 0, $cr_id, $cr_type, $cr_name, $de_blc, $cr_type, 'GRN Delete', 0, 0, $date, $time, $userid, $username));

        $sql = "UPDATE  cash SET amount=amount+? WHERE id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array($amount, $cr_id));
    }

    $y = date("Y");
    $m = date("m");

    header("location: grn_rp.php?year=$y&month=$m");
}
