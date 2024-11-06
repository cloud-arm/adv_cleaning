<?php
session_start();
include("../connect.php");
include("../config.php");
date_default_timezone_set("Asia/Colombo");

$date = date("Y-m-d");
$time = date("H.i");
$path="../";

$job_id=$_GET['id'];
$action=$_GET['type'];

$result = query("SELECT * FROM info ",$path);
for ($i = 0; $row = $result->fetch(); $i++) {
    $info_name = $row['name'];
    $info_add = $row['address'];
    $info_vat = $row['vat_no'];
    $info_con = $row['phone_no'];
}

$result = $db->prepare("SELECT * FROM sales WHERE   job_no='$job_id'");
$result->bindParam(':userid', $date);
$result->execute();
for ($i = 0; $row = $result->fetch(); $i++) {
	$name = $row['customer_name'];
    $cus_id=$row['customer_id'];
    $address=$row['address'];
    $invo=$row['invoice_number'];
    $total=$row['amount'];
}


    if ($action == 1) {

        $in_type = "INVOICE";
    } else {
        $in_type = "QUESTION";
    }

    $tot_row = '
                <tr>
                    <td align="center"><img src="icon/r.png" width="40" alt=""></td>
                    <td style="font-size:18px" colspan="3" align="right"><h3>Total:</h3></td>
                    <td style="font-size:18px" align="right"><h3>Rs.' . number_format($total, 2) . '</h3></td>
                </tr>
                <tr>
                    <td align="center">CLOUD ARM</td>
                    <td style="font-size:18px" colspan="3" align="right"><h3></h3></td>
                    <td style="font-size:18px" align="right"><h3></h3></td>
                </tr>
                ';


    $sales_list = "";
    $result = select("sales_list","*","job_no='$job_id' ",$path);
    
    for ($i = 0; $row = $result->fetch(); $i++) {

        $price = $row['price'] - ($row['dic'] / $row['qty']);
        $amount = $price * $row['qty'];

        $sales_list .= '
                 <tr>
                    <td style="border-bottom: 1px solid #ccc;">' . ($i + 1) . '</td>
                    <td style="border-bottom: 1px solid #ccc;">' . $row["name"] . '</td>
                    <td align="center" style="border-bottom: 1px solid #ccc;">' . $row["qty"] . '</td>
                    <td align="right" style="border-bottom: 1px solid #ccc;">' . number_format($price, 2) . '</td>
                    <td align="right" style="border-bottom: 1px solid #ccc;">' . number_format($amount, 2) . '</td>
                 </tr> ';
    }

    $output = '
<html>
<head>
<style>
body {
font-family: Poppins;
}
</style>
</head>
<body>
<table style="font-size: 12px;"  cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
        <td>
            <table  cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <td valign="top" width="60%">
                        <img src="../img/logo/logo.jpg" alt="Logo" style="max-width:150px;"><br>
                         <b style="font-family: Poppins; font-size:17px">' . $info_name . '</b><br>
                         <b style="font-family: Poppins; font-size:15px">' . $info_add . '</b><br>
                         <b style="font-family: Poppins; font-size:15px">' . $info_con . '</b><br>
                    </td>
                    <td align="right" valign="top" width="40%">
                        <b style="font-family:Poppins; font-size:30px">' . $in_type . '</b><br><br>
                        <b style="font-family: Poppins; font-size:14px"> ' . $name . '</b><br>
                        <b style="font-family: Poppins; font-size:14px">' . $address . '</b><br>
                        <b style="font-family: Poppins; font-size:14px">#' . $invo . '</b><br>
                        <p>Date: ' . date('Y-M-d') . ' Time:' . date('H:m') . '</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
    <tr>
        <td>

        </td>
    </tr>
    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <th style="border-bottom: 1px solid #ccc;">ID</th>
                    <th style="border-bottom: 1px solid #ccc;">Description</th>
                    <th style="border-bottom: 1px solid #ccc;">Quantity</th>
                    <th align="right" style="border-bottom: 1px solid #ccc;">Unit Price</th>
                    <th align="right" style="border-bottom: 1px solid #ccc;">Total</th>
                </tr>

                ' . $sales_list . '

                <tr>
                    <td colspan="4" align="right"><h3><br></h3></td>
                    <td align="right"><h3><br></h3></td>
                </tr>

                ' . $tot_row . '
            </table>
        </td>
    </tr>
</table>
</body>
</html>
';

echo $output; 

//$contact = '0779252594';

    $text = 'Dear '. $name.',Thank you for choosing Advanced Cleaning Services! Please find your quote attached. We look forward to your feedback and are excited to provide you with our top-quality service.Best regards,The Advanced Cleaning Services Team';
    $url = get_pdf($output, 'invoice', 'bin/');
    $url = 'https://adcleaning.colorbiz.org/main/pages/forms/pdf/' . $url;
    echo "URL is".$url;
    whatsApp($contact, $text,$url);
    

$return = $_SESSION['SESS_BACK'];



//header("location: ../$return");
