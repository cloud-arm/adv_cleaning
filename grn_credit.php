<!DOCTYPE html>
<html>
<?php
include("head.php");
include_once("auth.php");
$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_FORM'] = 'grn_credit';
date_default_timezone_set("Asia/Colombo");
?>

<body class="hold-transition skin-blue skin-orange sidebar-mini ">

  <?php include_once("start_body.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        GRN Credit
        <small>Report</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">


      <!-- All jobs -->

      <?php
      include("connect.php");
      date_default_timezone_set("Asia/Colombo");






      ?>
      <div class="box box-info">

        <div class="box-header with-border">
          <h3 class="box-title" style="text-transform: capitalize;">GRN credit</h3>
        </div>

        <div class="box-body d-block">
          <table id="example" class="table table-bordered table-striped">
          <thead>
    <tr>
        <th>NO</th>
        <th>Invoice</th>
        <th>Supplier</th>
        <th>PO No</th>
        <th>Supplier Invoice</th>
        <th>Bill Amount</th>
        <th>Pay amount</th>
        <th>Balance</th>
        <th>#</th>
    </tr>
</thead>
<tbody>
  <?php
  $a=0;
  $balance = 0;
  $pay_total = 0;
  $bill_total = 0;

  // Query to get supply payment data
  $re = select_query("SELECT * FROM `supply_payment` WHERE amount > pay_amount AND pay_type='Credit'");
  while ($r0 = $re->fetch()) {
      $bill = $r0['amount'];
      $pay = $r0['pay_amount'];
      $invoice_no = $r0['invoice_no'];
      

      // Initialize $po to avoid undefined variable issues
        $po = '';
      // Query to get PO from purchases table using invoice_no
      $re1 = select_query("SELECT supplier_invoice FROM `purchases` WHERE invoice_no='$invoice_no'");
      if ($r1 = $re1->fetch()) {
          $po = $r1['supplier_invoice']; // Assign the supplier_invoice value to $po
      }

      // Output table row
  ?>
      <tr>
          <td><?php echo ++$a; ?></td>
          <td><?php echo $r0['invoice_no']; ?></td>
          <td><?php echo $r0['supply_name']; ?></td>
          <td><?php echo $po; ?></td> <!-- Display PO -->
          <td><?php echo $r0['supplier_invoice']; ?></td>
          <td><?php echo $bill; ?></td>
          <td><?php echo $pay  ?></td>
          <td><?php echo $bill-$pay; ?></td>
          <td><a href="grn_bill.php?id=<?php echo $r0['invoice_no']  ?>"><button class="btn btn-info">View</button></a></td>
      </tr>
  <?php 
  $bill_total += $bill;
  $pay_total += $pay;
  $balance +=($bill-$pay);
 } ?>
</tbody>

              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                  <h4 style="margin-bottom: 0;"> Total</h4>
                </td>
                <td>
                  <h4><small>Rs.</small><?php echo $bill_total; ?></h4>
                </td>
                <td>
                  <h4><small>Rs.</small><?php echo $pay_total; ?></h4>
                </td>
                <td>
                  <h4><small>Rs.</small><?php echo $balance; ?></h4>
                </td>
                <td></td>

              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </section>

  </div>

  <!-- /.content-wrapper -->
  <?php
  include("dounbr.php");
  ?>
  <div class="control-sidebar-bg"></div>
  </div>

  <?php include_once("script.php"); ?>

  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
  <!-- InputMask -->
  <script src="../../plugins/input-mask/jquery.inputmask.js"></script>
  <script src="../../plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
  <script src="../../plugins/input-mask/jquery.inputmask.extensions.js"></script>
  <!-- date-range-picker -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
  <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap datepicker -->
  <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
  <!-- SlimScroll 1.3.0 -->
  <script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <!-- iCheck 1.0.1 -->
  <script src="../../plugins/iCheck/icheck.min.js"></script>
  <!-- FastClick -->
  <script src="../../plugins/fastclick/fastclick.js"></script>

  <script type="text/javascript">
    $(function() {
      $("#example").DataTable();
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false
      });
    });
  </script>



  <!-- Page script -->
  <script>
    $(function() {
      //Initialize Select2 Elements
      $(".select2").select2();

      //Datemask dd/mm/yyyy
      $("#datemask").inputmask("YYYY/MM/DD", {
        "placeholder": "YYYY/MM/DD"
      });
      //Datemask2 mm/dd/yyyy
      $("#datemask2").inputmask("YYYY/MM/DD", {
        "placeholder": "YYYY/MM/DD"
      });
      //Money Euro
      $("[data-mask]").inputmask();

      //Date range picker
      $('#reservation').daterangepicker();
      //Date range picker with time picker
      //$('#datepicker').datepicker({datepicker: true,  format: 'yyyy/mm/dd '});
      //Date range as a button
      $('#daterange-btn').daterangepicker({
          ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function(start, end) {
          $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
      );

      //Date picker
      $('#datepicker').datepicker({
        autoclose: true,
        datepicker: true,
        format: 'yyyy/mm/dd '
      });
      $('#datepicker').datepicker({
        autoclose: true
      });



      $('#datepickerd').datepicker({
        autoclose: true,
        datepicker: true,
        format: 'yyyy/mm/dd '
      });
      $('#datepickerd').datepicker({
        autoclose: true
      });


      //iCheck for checkbox and radio inputs
      $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
      });
      //Red color scheme for iCheck
      $('input[type="checkbox"].minimal-red, input[type="radio"].minimal-red').iCheck({
        checkboxClass: 'icheckbox_minimal-red',
        radioClass: 'iradio_minimal-red'
      });
      //Flat red color scheme for iCheck
      $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass: 'iradio_flat-green'
      });


    });
  </script>

</body>

</html>