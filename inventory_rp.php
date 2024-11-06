<!DOCTYPE html>
<html>
<?php
include("head.php");
include("connect.php");
include_once("auth.php");
$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_FORM'] = 'inventory_rp';
date_default_timezone_set("Asia/Colombo");
?>

<body class="hold-transition skin-blue skin-orange sidebar-mini ">
<?php
	 include_once("start_body.php"); 


	if ($r == 'Cashier') {

		header("location:./../../../index.php");
	}
	if ($r == 'admin') {

		include_once("sidebar.php");
	}
    ?>

    	<!-- /.sidebar -->
	</aside>


    <div class="content-wrapper">
		<!-- Content Header (Page header) -->
		<section class="content-header">
			<h1>
				Inventory Report
				<small>Preview</small>
			</h1>
			<ol class="breadcrumb">
				<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
				<li><a href="#">Forms</a></li>
				<li class="active">Advanced Elements</li>
			</ol>
		</section>
<?php
if (isset($_GET['d1'])) {
    $d1 = $_GET['d1'];
} else {
    $d1 = date("Y-m-d");
}
?>

<div class="row">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <div class="box">
                            <div class="box-header with-border">
                                <h3 class="box-title">Date Selector</h3>
                            </div>
                            <?php
                            include("connect.php");
                            date_default_timezone_set("Asia/Colombo");

                           
                           

                            $dates = $_GET['dates'];
                            $dateRange = explode(" - ", $dates);
                            $d1 = date('Y-m-d', strtotime($dateRange[0]));
                            $d2 = date('Y-m-d', strtotime($dateRange[1]));

                            ?>
                            <div class="box-body">
                                <form action="" method="GET">
                                    <div class="row" style="margin-top: 10px;">
                                        
                                        
                                        <div class="col-md-9">
                                            <div class="form-group">
                                            <input type="text" class="form-control pull-right" id="reservation" name="dates" value="<?php echo isset($_GET['dates']) ? $_GET['dates'] : ''; ?>">
                                       
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input class="btn btn-info" type="submit" value="Search">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

<section class="content">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Inventory Report</h3>
        </div>

        <div class="box-body">
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Name</th>
                        <th>Open</th>
                        <th>OUT</th>
                        <th>IN</th>
                        <th>Close</th>
                        <th>#</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    $tot = 0;
                    $labor = 0;

                    // Fetching the stock information along with related sales and purchase quantities
					$result = $db->query("SELECT *,
					 SUM(CASE WHEN type = 'out' THEN qty ELSE 0 END) AS `out`, 
					 SUM(CASE WHEN type = 'in' THEN qty ELSE 0 END) AS `in` ,
					 MAX(`balance`) AS close,
					 (balance + qty) AS open

					 FROM inventory WHERE date BETWEEN '$d1' AND '$d2' GROUP BY material_id ORDER BY id DESC");

                    // Loop through the results and display them
                    for ($i = 0; $row = $result->fetch(); $i++) {
                    ?>
                        <tr>
                            <td><?php echo $row['material_id']; ?></td>
                            <td><?php echo $row['name']; ?></td>
                            <td><?php echo $row['open']; ?></td> <!-- Opening Stock -->
                            <td><?php echo $row['out']; ?></td> <!-- Sales Quantity -->
                            <td><?php echo $row['in']; ?></td> <!-- Purchase Quantity -->
                            <td><?php echo $row['open']-($row['out']-$row['in']); ?></td> <!-- Closing Stock -->
                            <td></td>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
	</div>
<?php
	include("dounbr.php");
	?>
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="../../bootstrap/js/bootstrap.min.js"></script>
	<!-- DataTables -->
	<script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
	<!-- SlimScroll -->
	<script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
	<!-- FastClick -->
	<script src="../../plugins/fastclick/fastclick.js"></script>
	<!-- AdminLTE App -->
	<script src="../../dist/js/app.min.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="../../dist/js/demo.js"></script>
	<script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
	<!-- date-range-picker -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
	<!-- page script -->
	<script>
		

		$(function() {

			
			 // Initialize date range picker
			 $('#reservation').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                }
            });


			$("#example1").DataTable();
			$('#example2').DataTable({
				"paging": true,
				"lengthChange": false,
				"searching": false,
				"ordering": true,
				"info": true,
				"autoWidth": false
			});
		});


		$('#datepicker').datepicker({
			autoclose: true,
			datepicker: true,
			format: 'yyyy-mm-dd '
		});
		$('#datepicker').datepicker({
			autoclose: true
		});



		$('#datepickerd').datepicker({
			autoclose: true,
			datepicker: true,
			format: 'yyyy-mm-dd '
		});
		$('#datepickerd').datepicker({
			autoclose: true
		});
	</script>
</body>
</html>