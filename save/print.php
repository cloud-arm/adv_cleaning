<!DOCTYPE html>
<html>

<head>
	<?php
	session_start();
	include("../connect.php");
	include("../config.php");
	date_default_timezone_set("Asia/Colombo");
	?>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>CLOUD ARM | Invoice..</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="../../../bootstrap/css/bootstrap.min.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

	<style>
		@media print {
			h5 {
				line-height: 1.5;
				margin-bottom: 0;
			}

			h4 span {
				float: right;
			}

			h3 {
				line-height: 1.5;
				font-weight: 600;
				text-decoration: underline;
			}

			#btn-box {
				display: none !important;
			}

			a {
				color: #3c8dbc !important;
				text-decoration: underline;
			}

			hr {
				border-color: #000 !important;
				text-decoration: underline;
				margin: 0 !important;
			}

			table thead tr th {
				text-align: center;
			}
		}
	</style>
</head>

<body>
	<?php
	$sec = "1";

	$return =  $_SESSION['SESS_BACK'];

    
	?>
	<?php if (isset($_GET['print'])) { ?>

		<body onload="window.print()" style=" font-size: 13px;font-family: arial;">
		<?php } else { ?>

			<body style=" font-size: 13px; font-family: arial;margin: 0 10px;overflow-x: hidden;">
			<?php } ?>

			<?php if (isset($_GET['print'])) { ?>
				<meta http-equiv="refresh" content="<?php echo $sec; ?>;URL='<?php echo $return; ?>'">
			<?php } ?>
			<div class="wrapper">
				<!-- Main content -->
				<section class="invoice">

					<?php $path="../";
					$result = query("SELECT * FROM info ",$path);
					
					for ($i = 0; $row = $result->fetch(); $i++) {
						$info_name = $row['name'];
						$info_add = $row['address'];
						$info_vat = $row['vat_no'];
						$info_con = $row['phone_no'];
						$info_mail = $row['email'];
					}

                    $job_id=$_GET['id'];
                    
                    $action=$_GET['type'];
                    


					?>


					<div class="row">
						<!-- accepted payments column -->
						 
						<div class="col-xs-6">
							<div class="col-xs-4">
							<img src="../img/logo/logo.jpg" width="140" alt="Logo" style="margin-bottom: 10px;">
							</div>
							<div class="col-xs-8">
							<h5 >
								<b><?php echo $info_name; ?></b> <br>
								
								<?php echo $info_add; ?> <br>
								<?php echo $info_con; ?> <br>
								<a href="#" style="color:blue"><?php echo $info_mail; ?></a>
							</h5>
							</div>
							
						</div>

						<div class="col-xs-12">
							<hr>
						</div>

						<div class="col-xs-12">
							<h3 style="text-align: center;">
								<?php if ($action == 1) {
									echo "INVOICE";
								} else {
									echo "QUTATION";
								} ?>
							</h3>
						</div>
						<!-- /.col -->
						<div class="col-xs-7">
							<h5>

								<?php
								$result = $db->prepare("SELECT * FROM sales WHERE   job_no='$job_id'");
								$result->bindParam(':userid', $date);
								$result->execute();
								for ($i = 0; $row = $result->fetch(); $i++) {

									$cus_name = $row['customer_name'];
                                    $cus_id=$row['customer_id'];
                                    $address=$row['address'];
                                   
                                    $invo=$row['invoice_number'];
								} ?>

								<b>INVOICE TO:</b> <br>
								<?php echo $cus_name; ?> <br>
								<?php echo $address; ?> <br>
								<b>Customer id: </b> <?php echo $cus_id; ?> <br>
								<b>Job Number: </b> <?php echo $job_id; ?>
							</h5>
						</div>
						<!-- /.col -->

						<div class="col-xs-5 pull-right">
							<h5 style="float:right">
								<b> Date:</b> <?php echo $date; ?> <br>
								<b>Invoice:</b> <?php echo $invo; ?> <br>
								<br>
								<small>
									Print Date: <?php echo date('Y-m-d'); ?> <br>
									Print Time- <?php echo date('H:i:s'); ?>
								</small>
							</h5>
						</div>

					</div>


					<div class="box-body">
						<table id="example1" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Qty</th>
									<th>Price </th>
									<th>Amount </th>
								</tr>
							</thead>
							<tbody>
								<?php
								date_default_timezone_set("Asia/Colombo");
								$hh = date("Y/m/d");
								$tot_amount = 0;
								$num = 0;
								$result = $db->prepare("SELECT * FROM sales_list WHERE   job_no='$job_id'");
								$result->bindParam(':userid', $date);
								$result->execute();
								for ($i = 0; $row = $result->fetch(); $i++) {
									$num += 1;

									$price = $row['price'] - ($row['dic'] / $row['qty']);
									$amount = $price * $row['qty'];
								?>
									<tr>
										<td><?php echo $num; ?></td>
										<td><?php echo $row['name']; ?></td>
										<td><?php echo $row['qty']; ?></td>
										<td>Rs.<?php echo $price; ?></td>
										<td>Rs.<?php 	echo number_format($amount, 2); ?></td>
										<?php $tot_amount += $amount; ?>
									</tr>
								<?php } ?>
								<tr>
									<td></td>
									<td></td>
									<td></td>
									<td>Total: </td>
									<td>Rs.<?php echo number_format($tot_amount, 2); ?></td>
								</tr>
							</tbody>
							<tfoot>
							</tfoot>
						</table>

						<div class="row">
							

							<div class="col-xs-4" id="btn-box" style="display: flex;gap: 15px;justify-content: center;">
								<a href="quotation_print?id=<?php echo $job_id.'&type='.$action; ?>&print" class="btn btn-danger"> <i class="fa fa-print"></i> Print</a>
								<a href="../pdf/invoice.php?id=<?php echo $job_id.'&type='.$action; ?>&text='' " class="btn btn-success"> <i class="fa fa-whatsapp"></i> Whatsapp</a>
								<a href="../job_view?id=<?php echo base64_encode($job_id); ?>" class="btn btn-warning"> <i class="fa fa-home"></i> Home</a>
							</div>
						</div>

					</div>

					<br><br><br>
					<div class="row">
						<div class="col-md-12">Make all Cheques payable to "Advanced Cleaners" <br>
 FOR ENQUIRIES Negombo -031228645 COLOMBO BRANCH - 112690944 <br>
 Thank you for your business!
						</div>
					</div>

					<br><br><br>
					<div class="row">
						<div class="col-xs-12" style="text-align: center;">
							This is a system generated document and signature is not required
						</div>
					</div>
				</section>
			</div>
			</body>

</html>