<?php
session_start();
include('config.php');
include('connect.php');
include('sub_class/cash_transaction.php');
include('sub_class/bank_transaction.php');
//include_once("start_body.php");

$ui = $_SESSION['SESS_MEMBER_ID'];
$un = $_SESSION['SESS_FIRST_NAME'];

$type = $_POST['type'];
$date = date("Y-m-d");
$time = date('H:i:s');
$transaction_type = date("YmdHis");

//
if ($type == 'deposit') {

    $amount = $_POST['amount'];
    $bank = $_POST['bank'];
    $acc_no = $_POST['cash'];

    $status = cash_transaction($acc_no, "Debit", $amount, "cash_deposit", $bank);
    echo '<br>Line: 22 ' .  $status['status'] . ' / ' . $status['message'];

    $status = bank_transaction($bank, "Credit", $amount, "cash_deposit", $acc_no);
    echo '<br>Line: 25 ' .  $status['status'] . ' / ' . $status['message'];

    //header("location: acc_bank_transfer.php");
}
//bank_transaction($id, $type, $amount, $transaction_type, $record_no, $action = 0,$chq = ["no"=>"","date"=>"","bank"=>""])
//
if ($type == 'chq') {

    $id = $_POST['id'];
    $bank = $_POST['bank'];
    $amount = $_POST['amount'];
    $record_no = date("YmdHis");

    // Fetch bank name
    $result = select("bank", "name", "id = '" . $bank . "' ");
    for ($k = 0; $row = $result->fetch(); $k++) {
        $bank_name = $row['name'];
    }

    // Update payment details
    $updateData = array(
        "action" => 1,
        "deposit_date" => $date,
        "bank_id" => $bank,
        "bank_name" => $bank_name,
        "memo" => "chq_deposited"
    );
    echo 'Line: 50 ' . update("payment", $updateData, "id = " . $id);

    echo $id;

    // Call the bank_transaction function to handle the rest
    $chq_details = array(
        "no" => $_POST['chq_no'],
        "date" => $_POST['chq_date'],
        "bank" => $_POST['chq_bank']
    );

    // Call the function for saving transaction
    bank_transaction($id, $type, $amount, "chq_deposited", $record_no, 2, $chq_details);
}

if ($type == 'dep_realize') {

    $id = $_POST['id'];

    $re = $db->prepare("SELECT * FROM payment WHERE id = :id ");
    $re->bindParam(':id', $id);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $chq_no = $r['chq_no'];
        $amount = $r['amount'];
    }

    $re = $db->prepare("SELECT * FROM bank_record WHERE chq_no = :id ");
    $re->bindParam(':id', $chq_no);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $bank = $r['debit_acc_id'];
    }

    $sql = "UPDATE  payment SET action=?, reserve_date = ? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(2, $date, $id));

    $mn_blc = 0;
    $b_blc = 0;
    $re = $db->prepare("SELECT * FROM bank WHERE id =:id ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $b_blc = $r['amount'];
    }

    $mn_blc = $b_blc + $amount;

    $sql = "UPDATE  bank SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($mn_blc, $bank));

    $sql = "UPDATE  bank_record SET action=? WHERE chq_no=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(2, $chq_no));


    echo $id;
}

if ($type == 'iss_realize') {

    $id = $_POST['id'];
    $unit = $_POST['unit'];

    if ($unit == 'grn') {

        $re = $db->prepare("SELECT * FROM supply_payment WHERE id = :id ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $chq_no = $r['chq_no'];
            $chq_date = $r['chq_date'];
            $amount = $r['amount'];
            $bank = $r['bank_id'];
            $chq_bank = $r['chq_bank'];
        }

        $cr_name = 'GRN';

        $sql = "UPDATE  supply_payment SET action = ?, reserve_date = ? WHERE id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(2, $date, $id));

        $cr_type = 'grn_payment';
    } else

    if ($unit == 'exp') {

        $re = $db->prepare("SELECT * FROM payment WHERE id = :id ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $chq_no = $r['chq_no'];
            $chq_date = $r['chq_date'];
            $amount = $r['amount'];
            $bank = $r['bank_id'];
            $chq_bank = $r['chq_bank'];
        }

        $re = $db->prepare("SELECT * FROM expenses_records WHERE acc_id = '$bank' AND acc_name = '$chq_bank' ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $cr_name = $r['type'];
        }

        $sql = "UPDATE  payment SET action = ?, reserve_date = ? WHERE id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(2, $date, $id));

        $cr_type = 'expenses_payment';
    }

    $mn_blc = 0;
    $b_blc = 0;
    $re = $db->prepare("SELECT * FROM bank WHERE id =:id ");
    $re->bindParam(':id', $bank);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $b_blc = $r['amount'];
        $cr_name = $r['name'];
    }

    $mn_blc = $b_blc - $amount;

    $sql = "UPDATE  bank SET amount=? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array($mn_blc, $bank));

    $sql = "INSERT INTO bank_record (transaction_type,type,record_no,amount,action,credit_acc_no,credit_acc_type,credit_acc_name,credit_acc_balance,debit_acc_type,debit_acc_name,debit_acc_id,debit_acc_balance,date,time,user_id,user_name,chq_no,chq_bank,chq_date) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
    $ql = $db->prepare($sql);
    $ql->execute(array('chq_issue', 'Debit', $id, $amount, 2, $id, $cr_type, $cr_name, 0, 'chq_issue', $chq_bank, $bank, $mn_blc, $date, $time, $ui, $un, $chq_no, $chq_bank, $chq_date));

    echo $id;
}

if ($type == 'dep_return') {

    $id = $_POST['id'];

    $re = $db->prepare("SELECT * FROM payment WHERE id = :id ");
    $re->bindParam(':id', $id);
    $re->execute();
    for ($k = 0; $r = $re->fetch(); $k++) {
        $chq_no = $r['chq_no'];
    }

    $sql = "UPDATE  payment SET action=?, reserve_date = ? WHERE id=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(3, $date, $id));

    $sql = "UPDATE  bank_record SET action=? WHERE chq_no=?";
    $ql = $db->prepare($sql);
    $ql->execute(array(3, $chq_no));

    echo $id;
}

if ($type == 'iss_return') {

    $id = $_POST['id'];
    $unit = $_POST['unit'];

    if ($unit == 'grn') {

        $re = $db->prepare("SELECT * FROM supply_payment WHERE id = :id ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $chq_no = $r['chq_no'];
        }

        $sql = "UPDATE  supply_payment SET action=?, reserve_date = ? WHERE id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(3, $date, $id));

        $sql = "UPDATE  bank_record SET action=? WHERE chq_no=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(3, $chq_no));
    }

    if ($unit == 'exp') {

        $re = $db->prepare("SELECT * FROM payment WHERE id = :id ");
        $re->bindParam(':id', $id);
        $re->execute();
        for ($k = 0; $r = $re->fetch(); $k++) {
            $chq_no = $r['chq_no'];
        }

        $sql = "UPDATE  payment SET action=?, reserve_date = ? WHERE id=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(3, $date, $id));

        $sql = "UPDATE  bank_record SET action=? WHERE chq_no=?";
        $ql = $db->prepare($sql);
        $ql->execute(array(3, $chq_no));
    }

    echo $id;
}

//
if ($type == 'withdraw') {

    $bank = $_POST['bank'];
    $acc_no = $_POST['cash'];
    $amount = $_POST['amount'];


    $status = bank_transaction($bank, "Debit", $amount, "cash_withdraw", $acc_no);
    echo '<br>Line: 255 ' .  $status['status'] . ' / ' . $status['message'];

    $status = cash_transaction($acc_no, "Credit", $amount, "cash_withdraw", $bank);
    echo '<br>Line: 260 ' .  $status['status'] . ' / ' . $status['message'];

    //header("location: acc_bank_transfer.php");
}

//
if ($type == 'chargers') {

    $bank = $_POST['bank'];
    $desc = $_POST['desc'];
    $chr_date = $_POST['date'];
    $amount = $_POST['amount'];

    $acc_no = 0;
    $result = select("bank_record","count(id)","transaction_type = 'bank_charges' ");
    for ($k = 0; $row = $result->fetch(); $k++) {
        $acc_no = $row['count(id)'];
    }

    $acc_no = $acc_no + 1;

    $status = bank_transaction($bank, "Debit", $amount, "bank_charges", $acc_no);
    echo '<br>Line: 280 ' .  $status['status'] . ' / ' . $status['message'];

   // header("location: acc_bank_transfer.php");
}
