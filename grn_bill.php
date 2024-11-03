<!DOCTYPE html>
<html>

<head>
    <?php
    date_default_timezone_set("Asia/Colombo");
    session_start();
    include("config.php");

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
            .invo_hed {
                border: 2px solid #007bff;
                padding: 20px;
                border-radius: 8px;
                background-color: #ffffff;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

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

<body  style="font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9;">
    <?php
    $sec = "1";
    $_SESSION['page'] = "END";
    $admin = $_SESSION['SESS_LAST_NAME'];
    $invo=$_GET['id'];
  
    $result=select('purchases','*',"invoice_no='$invo' AND type='Order'");
    if ($row = $result->fetch()) {
        $supplier=$row['supplier_name'];
        $supplier_invoice=$row['supplier_invoice'];
        $date=$row['date'];
    }
    ?>

    <!-- Print button to manually trigger print -->
    <div class="print-hide" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print();" class="btn btn-primary">Print Invoice</button>
    </div>

    <div class="invo_hed">
        <header>
            
            <h2 style="margin: 0;">GRN RECORD</h2>
            <h4 style="margin: 5px 0;">Invoice No: <?php echo $_GET['id']; ?></h4>
            <h4 style="margin: 5px 0;">PO No: <?php echo $supplier_invoice; ?></h4>
            <p style="margin: 0; font-size: 14px;">Date:
                <?php  echo $date; ?> </p>
        </header>

        <section style="margin-top: 20px;">
            <div
                style="display: flex; justify-content: space-between; background-color: #e9ecef; padding: 10px; border-radius: 8px;">
                <div>
                    <h5 style="margin: 0; color: #343a40;"><b>
                        <?php
                            echo $supplier;
                        
                        ?>
                    </h5>
                </div>
            </div>
        </section>

        <section style="margin-top: 20px;">
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px; background-color: #ffffff; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <thead style="background-color: #007bff; color: white;">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
    <?php
    $tot_amount = 0;
    $num = 0;
    

        $result = select('purchases_list','*',"invoice_no='$invo'");
        while ($row = $result->fetch()) {
            $num += 1; ?>
            <tr>
                <td style='border: 1px solid #ddd; padding: 12px;'><?php echo $row['id']  ?></td>
                <td style='border: 1px solid #ddd; padding: 12px;'><?php echo $row['name']  ?></td>
                
                <td style='border: 1px solid #ddd; padding: 12px; text-align: right;'><?php echo $row['sell']  ?></td>
                <td style='border: 1px solid #ddd; padding: 12px; text-align: right;'><?php echo $row['qty']  ?></td>
                <td style='border: 1px solid #ddd; padding: 12px; text-align: right;'>Rs. <?= number_format($row['amount'], 2); ?></td>
            </tr>
            <?php
            $firstRow = false;
            $tot_amount += $row['amount'];
        }
    
    ?>
</tbody>

        <tfoot>
            <tr>
                <td colspan="4" style="text-align: right; border: 1px solid #ddd; padding: 12px;"><strong>Total Amount:</strong></td>
                <td style="border: 1px solid #ddd; padding: 12px; text-align: right;"><strong>Rs. <?= number_format($tot_amount, 2); ?></strong></td>
            </tr>
        </tfoot>
    </table>
</section>



        
    </div>
</body>

</html>
