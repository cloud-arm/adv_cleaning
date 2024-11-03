<?php
session_start();
include('connect.php');
include('config.php');

$u = $_SESSION['SESS_MEMBER_ID'];

$invo = $_POST['id'];
$type = $_POST['type'];
$qty = $_POST['qty'];
$id2 = $_POST['id2'];
$sell = $_POST['sell'];

$pro = $_POST['pr'];
$unit_id= $_POST['unit'];
if($unit_id==0){
    $unit=select_item('materials','unit','id='.$pro);
}else{
    $unit= select_item('unit_record','unit','id='.$unit_id);
}
echo $unit;


$dic = 0;

$stock = 0;



if($dic==''){$dic = 0;}




    $result = $db->prepare("SELECT * FROM materials WHERE id=:id ");
    $result->bindParam(':id', $pro);
    $result->execute();
    for($i=0; $row = $result->fetch(); $i++){$pro_name = $row['name']; }


$amount = $sell*$qty;
//$dic = $amount*$dic/100;
$date = date("Y-m-d");


if($invo!=''){
    $sql = "INSERT INTO purchases_list (invoice_no,name,qty,date,product_id,sell,type,user_id,stock_id,amount,unit,unit_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)";
    $re = $db->prepare($sql);
    $re->execute(array($invo,$pro_name,$qty,$date,$pro,$sell,$type,$u,$stock,$amount,$unit,$unit_id));
}

if($type == 'GRN'){
    header("location: grn.php?id=$invo");
}



if ($type == 'Order') {
    if ($id2 == 1) {
        header("location: grn_order.php?id=$invo");
    } else {
        // Fetch the total amount for the specified invoice
        $result = $db->prepare("SELECT SUM(amount) FROM purchases_list WHERE invoice_no = :id AND approve != '5'");
        $result->bindParam(':id', $invo);
        $result->execute();
        $row = $result->fetch();
        $amount = $row['SUM(amount)'];
        
        echo $amount;

        // Update the action to 'pending' in the purchases_list table
       /* $action = 'pending';
        $updateAction = $db->prepare("UPDATE purchases_list SET action = :action WHERE invoice_no = :invoice_no");
        $updateAction->bindParam(':action', $action);
        $updateAction->bindParam(':invoice_no', $invo);
        $updateAction->execute();
        */

        // Update the amount in the purchases table
        $updateAmount = $db->prepare("UPDATE purchases SET amount = :amount WHERE invoice_no = :invoice_no");
        $updateAmount->bindParam(':amount', $amount);
        $updateAmount->bindParam(':invoice_no', $invo);
        $updateAmount->execute();
        
        // Redirect to grn_order_view.php with the invoice ID
        header("location: grn_order_view.php?id=$invo");
    }
}


?>