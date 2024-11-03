<!DOCTYPE html>
<html>
<?php
include("head.php");
include_once("auth.php");
$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_FORM'] = 'grn_rp';
$user_level = $_SESSION['USER_LEWAL'];

date_default_timezone_set("Asia/Colombo");
?>

<body class="hold-transition skin-blue skin-orange sidebar-mini ">

  <?php include_once("start_body.php"); ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        GRN
        <small>Report</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <form action="" method="GET">
        <div class="row" style="margin-bottom: 20px;display: flex;align-items: center;">
          <div class="col-lg-1"></div>
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control " name="year" style="width: 100%;" tabindex="1" autofocus>
                <option> <?php echo date('Y') - 1 ?> </option>
                <option selected> <?php echo date('Y') ?> </option>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <select class="form-control " name="month" style="width: 100%;" tabindex="1" autofocus>
                <?php for ($x = 1; $x <= 12; $x++) { ?>
                  <option> <?php echo sprintf("%02d", $x); ?> </option>
                <?php  } ?>
              </select>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <input class="btn btn-info" type="submit" value="Search">
            </div>
          </div>
        </div>
      </form>
      <!-- All jobs -->

      <?php
      include("connect.php");
      date_default_timezone_set("Asia/Colombo");
if(!isset($_GET['year'])){
  $d1=date('Y-M-')."01";
  $d2=date('Y-M-')."31";
}else{
      $d1 = $_GET['year'] . '-' . $_GET['month'] . '-01';
      $d2 = $_GET['year'] . '-' . $_GET['month'] . '-31';
}

      $sql = " SELECT * FROM `purchases_list` WHERE type='GRN' AND date BETWEEN '$d1' AND '$d2' GROUP BY `invoice_no` ASC ";


      ?>
      <div class="box box-info">

        <div class="box-header with-border">
          <h3 class="box-title" style="text-transform: capitalize;">Purchases</h3>
        </div>

        <div class="box-body d-block">
          <table id="example" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>NO</th>
                <th>Invoice</th>
                <th>Supplier</th>
                <th>Supplier Invoice</th>
                <th>Pay Type</th>
                <th>Payment</th>
                <th>Discount</th>
                <th>Amount</th>
                <th>#</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $pay_total = 0;
              $bill_total = 0;
              $result = select_query($sql);
              for ($i = 0; $row = $result->fetch(); $i++) {
                $invo = $row['invoice_no'];

                $re = select_query("SELECT * FROM `purchases` WHERE invoice_no='$invo' AND dll=0 AND type='GRN' ");
                for ($i = 0; $r0 = $re->fetch(); $i++) {
                  $bill = $r0['amount'];
                  $pay = $r0['pay_amount'];
                  $bill_total += $bill;
              ?>
                  <tr>
                    <td><?php echo $r0['transaction_id'];  ?></td>
                    <td><?php echo $invo;  ?></td>
                    <td><?php echo $r0['supplier_name'];  ?></td>
                    <td><?php echo $r0['supplier_invoice'];  ?></td>
                    <td><?php echo $r0['pay_type'];  ?></td>
                    <td><?php echo $pay;
                        $pay_total += $pay; ?></td>
                    <td><?php echo $r0['discount'];  ?></td>
                    <td><?php echo $bill; ?></td>
                    <td>
                  <?php if ($user_level == 1) { ?>
                    <div style="display: inline-block;">
                      <a href="#" onclick="invo_dll()" class="btn btn-primary btn-sm">
                        <i class="fa fa-trash"></i>
                      </a>
                      <form action="grn_dll.php" method="POST" id="grn_dll" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $r0['transaction_id']; ?>">
                      </form>
                    </div>
                  <?php } ?>
                  
                  <a href="grn_bill.php?id=<?php echo $r0['invoice_no']; ?>">
                    <button class="btn btn-primary btn-sm">
                      <i class="fa fa-eye"></i>
                    </button>
                  </a>
                </td>

                  </tr>
              <?php }
              } ?>

            </tbody>
            <tbody>
              <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>
                  <h4> Total</h4>
                </td>
                <td>
                  <h4><?php echo $pay_total; ?>.00</h4>
                </td>
                <td></td>
                <td>
                  <h4><?php echo $bill_total; ?>.00</h4>
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
    function invo_dll() {
      if (confirm("Sure you want to delete this Invoice? There is NO undo!")) {
        $('#grn_dll').submit();
      }
      return false;
    }

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