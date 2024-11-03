<?php
include("connect.php");
date_default_timezone_set("Asia/Colombo");

$id = $_GET['id'];
$type = $_GET['type'];
$a = $_GET['ac'];
$sell = '';
$cost = '';
$date = '';
$qty = '';
$s_qty = '';

if ($type == 'Return') {

    $result = $db->prepare("SELECT * FROM stock WHERE id=:id ");
    $result->bindParam(':id', $id);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $sell = $row['sell'];
        $cost = $row['cost'];
        $s_qty = $row['qty_balance'];
        $p_id = $row['product_id'];
    }

    $result = $db->prepare("SELECT * FROM purchases_list WHERE product_id=:id GROUP BY id DESC LIMIT 1");
    $result->bindParam(':id', $p_id);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $date = $row['date'];
        $qty = $row['qty'];
    }
} else {

    $result = $db->prepare("SELECT * FROM stock WHERE product_id=:id ");
    $result->bindParam(':id', $id);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $sell = $row['sell'];
        $cost = $row['cost'];
        $s_qty = $row['qty_balance'];
    }

    $result = $db->prepare("SELECT * FROM purchases_list WHERE product_id=:id GROUP BY id DESC LIMIT 1");
    $result->bindParam(':id', $id);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $date = $row['date'];
        $qty = $row['qty'];
    }
}

if ($a == '0') { ?>
    <div class="row">
        <?php if ($sell != '') { ?>
            <div class="col-md-2">
                <label class="me-2">Sell Price :<span id="sell0"> <?php echo $sell; ?> </span> </label>
            </div>
        <?php }
        if ($cost != '') { ?>
            <div class="col-md-3">
                <label class="mx-2">Cost Price :<span id="cost0"> <?php echo $cost; ?> </span> </label>
            </div>
        <?php }
        if ($s_qty != '') { ?>
            <div class="col-md-2">
                <label class="mx-2">Stock Qty :<span> <?php echo $s_qty; ?> </span> </label>
            </div>
        <?php }
        if ($date != '') { ?>
            <div class="col-md-3">
                <label class="mx-2">Last Date :<span> <?php echo $date; ?> </span> </label>
            </div>
        <?php }
        if ($qty != '') { ?>
            <div class="col-md-2">
                <label class="mx-2">Qty :<span> <?php echo $qty; ?> </span> </label>
            </div>
        <?php } ?>
    </div>
<?php }
if ($a == '1') {
    printf($sell);
}
if ($a == '2') {
    printf($cost);
}
?>