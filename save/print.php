<!DOCTYPE html>
<html>

<head>
    <?php
    session_start();
    include("../connect.php");
    include("../config.php");

    $invo = $_GET['id'];
    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Grafix | Invoice</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <style>
        /* Print-specific styles */
        @media print {
            

            .print-hide {
                display: none;
            }

            body {
                font-size: 12pt;
            }

            header {
                text-align: center;
                background-color: #007bff;
                padding: 10px 0;
                border-radius: 8px;
            }

            table,
            th,
            td {
                border: 1px solid black;
                border-collapse: collapse;
                padding: 10px;
            }

            footer {
                page-break-after: avoid;
            }
        }
    </style>

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body onload="window.print();" style="font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9;">
    <?php
    $sec = "1";
    $_SESSION['page'] = "END";
    $admin = $_SESSION['SESS_LAST_NAME'];
    $invo = $_GET['id'];
    if ($admin == "admin") {
        ?>
        <meta http-equiv="refresh" id="print"
              content="<?php echo $sec; ?>;URL='../job_view.php?id=<?php echo base64_encode($_GET['id']); ?>'">
        <?php
    } else {
        ?>
        <meta http-equiv="refresh" id="print"
              content="<?php echo $sec; ?>;URL='../job_view?id=<?php echo base64_encode($_GET['id']); ?>'">
        <br>
        <?php
    }
    ?>

    <!-- Print button to manually trigger print -->
    <div class="print-hide" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print();" class="btn btn-primary">Print Invoice</button>
    </div>

    <div class="invo_hed">
        <header>
        <h3 style="margin: 0; color: #28a745;">Final Bill</h3>
        </header>

        <section style="margin-top: 20px;">
            <div
                style="display: flex; justify-content: space-between; background-color: #e9ecef; padding: 10px; border-radius: 8px;">
                <div>
                <img src="../img/logo/logo.jpg" width="140" alt="Logo" style="margin-bottom: 10px;">
            <h4 style="margin: 5px 0;">Invoice No: <?php echo select_item('sales','invoice_number',"job_no='$invo'",'../'); ?></h4>
            <p style="margin: 0; font-size: 14px;">Date:
                <?php date_default_timezone_set("Asia/Colombo"); echo date("Y-m-d"); ?> Time:
                <?php echo date("h:ia"); ?></p>
                
                    
                </div>
                <div>
                <h4>BILL TO <br>
                <?php
                        
                        $result = $db->prepare("SELECT * FROM sales WHERE job_no='$invo'");
                        $result->execute();
                        while ($row = $result->fetch()) {
                            echo $row['customer_name'];
                            echo "<br>";
                            echo "<b>Customer ID:</b> " . $row['customer_id'];
                        }
                        ?>
                </h4>
                
                </div>
            </div>
        </section>

        <section style="margin-top: 20px;">
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <thead style="background-color: #007bff; color: white;">
            <tr>
                <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">#</th>
                <th style="border: 1px solid #ddd; padding: 12px; text-align: left;">Name</th>
                <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Unit Price</th>
                <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Qty</th>
                <th style="border: 1px solid #ddd; padding: 12px; text-align: right;">Amount</th>
            </tr>
        </thead>
        <tbody>
    <?php
    $tot_amount = 0;
    $num = 0;

    if(!isset($_GET['type'])){

    $result = $db->prepare("SELECT name FROM sales_list WHERE job_no='$invo' AND status != 'reject' AND status != 'delete' GROUP BY name ORDER BY id ASC");
    $result->execute();

    while ($row = $result->fetch()) {
        $productName = $row['name'];
        $sizesResult = $db->prepare("SELECT width, height, about, SUM(qty) as total_qty, SUM(amount) as total_amount FROM sales_list WHERE job_no='$invo' AND name='$productName' AND status != 'reject' AND status != 'delete' GROUP BY width, height, about");
        $sizesResult->execute();
        $firstRow = true;
        ?>
        <tr>
            <td style='border: 1px solid #ddd; padding: 12px;'></td>
            <th style='border: 1px solid #ddd; padding: 12px;'><?php echo $productName ?></th>
            <td style='border: 1px solid #ddd; padding: 12px;'></td>
            <td style='border: 1px solid #ddd; padding: 12px;'></td>
            <td style='border: 1px solid #ddd; padding: 12px;'></td>
            
        </tr>

         <?php
        while ($sizeRow = $sizesResult->fetch()) {
            $num += 1; ?>
            <tr>
                <td style='border: 1px solid #ddd; padding: 12px;'><?= $num ; ?></td>
                <td style='border: 1px solid #ddd; padding: 12px;'>
                    <?= '<small style="color: #555;">' . $sizeRow['about'] . '</small>'; ?>
                </td>
                
                <td style='border: 1px solid #ddd; padding: 12px; text-align: right;'>Rs. <?= number_format($sizeRow['total_amount'] / $sizeRow['total_qty'], 2); ?></td>
                <td style='border: 1px solid #ddd; padding: 12px; text-align: right;'><?= $sizeRow['total_qty']; ?></td>
                <td style='border: 1px solid #ddd; padding: 12px; text-align: right;'>Rs. <?= number_format($sizeRow['total_amount'], 2); ?></td>
            </tr>
            <?php
            $firstRow = false;
            $tot_amount += $sizeRow['total_amount'];
        }
    }
}else{
    $result = select('sales_list','SUM(amount) AS total,location' ,"job_no='$invo' GROUP BY location_id ASC",'../');
    while ($row = $result->fetch()) {
        $num += 1;
        $tot_amount +=$row['total'];
    ?>
    <tr>
            <td style='border: 1px solid #ddd; padding: 12px;'><?= $num ; ?></td>
            <th style='border: 1px solid #ddd; padding: 12px;'><?php echo $row['location']  ?></th>
            <td style='border: 1px solid #ddd; padding: 12px;'></td>
            <td style='border: 1px solid #ddd; padding: 12px;'></td>
            <td style='border: 1px solid #ddd; padding: 12px; text-align: right;'>Rs. <?php echo $row['total']  ?></td>
            
        </tr>

<?php } } ?>
</tbody>

        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; border: 1px solid #ddd; padding: 12px;"><strong>Total Amount:</strong></td>
                <td style="border: 1px solid #ddd; padding: 12px; text-align: right;"><strong>Rs. <?= number_format($tot_amount, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</section>



        <footer style="margin-top: 20px; text-align: center;">Make all Cheques payable to "Advanced Cleaners" <br>
 FOR ENQUIRIES Negombo -031228645 COLOMBO BRANCH - 112690944 <br>
 Thank you for your business!
            </footer>
    </div>
</body>

</html>
