<?php

session_start();
include('config.php');

$id = $_GET['id'];
$id2 = $_GET['id2'];

// Sanitize input values to prevent SQL injection
//$id = intval($id);
//$id2 = intval($id2);

echo $id2;
echo '<br>';

if ($id2 == 5) {
    // Update approval status to '5' if $id2 equals 5
    $result = update('purchases_list', ['approve' => '5'], 'id=' . $id, '');

        $re = select_query("SELECT * FROM purchases_list WHERE id='$id' ");
        if ($row = $re->fetch()) {
            $amount1 = $row['amount'];
            $approve = $row['approve'];
            $invo = $row['invoice_no'];
        }

        $rs = select_query("SELECT * FROM purchases WHERE invoice_no='$invo' ");
        if ($row = $rs->fetch()) {
            $amount2 = $row['amount'];
        }

        if ($approve == 5) {
            $re = update('purchases', ['amount' => $amount2-$amount1], "invoice_no='$invo'", '');
        }

} else {
    // Update approval status to '1' otherwise
    $result = update('purchases_list', ['approve' => '1'], 'id=' . $id, '');


}

    // Fetch the invoice number for the given purchase list item
    $r1 = select('purchases_list', '*', 'id=' . $id);

    // Fetch the invoice number from the result
    if ($row = $r1->fetch()) {
        $invo = $row['invoice_no'];
    }

    // Count the number of 'approve=0' for the same invoice number
    $re = select_query("SELECT COUNT(approve) AS count_approve FROM purchases_list WHERE invoice_no='$invo' AND approve='0'");
    if ($row = $re->fetch()) {
        $count = $row['count_approve'];
    }
echo $count;
    // If no rows have 'approve=0', update the 'purchases' table
    if ($count == 0) {
        $result = update('purchases', ['approve' => 'approve'], "invoice_no='$invo'", '');
        echo $invo;


    }

$r1 = select_query("SELECT * FROM purchases_list WHERE id='$id' ");
$i = 1; // Initialize counter for numbering rows
while ($row = $r1->fetch()) {

    $invo = $row['invoice_no'];
}

// Redirect back to the order view page after the process is complete
header("location:grn_order_view.php?id=$invo");
exit;

?>
