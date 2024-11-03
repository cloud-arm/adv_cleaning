<!DOCTYPE html>

<html>

<?php
include("head.php");
include_once("auth.php");
$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_FORM'] = 'stock';
?>

<body class="hold-transition skin-blue skin-orange sidebar-mini">

  <?php include_once("start_body.php"); ?>

  <!-- Content Wrapper. Contains page content -->

  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Stock
        <small>Preview</small>
      </h1>

    </section>



    <section class="content">

      <div class="box box-success">
        <div class="box-header">
          <h3 class="box-title">STOCK Data</h3>
        </div>
        <form action="" method="GET">
                    <div class="row" style="margin-top: 20px;display: flex;align-items: center;">
                        <div class="col-lg-3"></div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="text" class="form-control" id="date_pick" name="date" value="<?php echo $_GET['date']; ?>" autocomplete="off">
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="btn btn-info btn-sm" for="">
                                    <i class="fa fa-search"></i>
                                    <input type="hidden" name="pro" value="<?php echo $_GET['pro'] ?>">
                                    <input style="border: 0;background-color: transparent;" type="submit" value="Search">
                                </label>
                            </div>
                        </div>
                    </div>
                </form>

        <div class="box-body">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>id</th>
                <th>Type</th>
                <th>Name</th>
                <th>qty</th>
                <th>Balance</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody>
              <?php $date=$_GET['date']; $pro_id=$_GET['pro'];
              $result = select_query("SELECT * FROM inventory WHERE date='$date' AND material_id='$pro_id' ORDER by id ASC  ");
              for ($i = 0; $row = $result->fetch(); $i++) {
              ?>
                <tr class="record">
                  <td><?php echo $id = $row['id']; ?></td>
                  <td><?php echo $row['type']; ?></td>
                  
                  <td><?php echo $row['name']; ?></td>
                  <td><?php echo $row['qty']; ?></td>
                  <td><?php echo $row['balance']; ?></td>
                  <th><?php echo $row['date'].' / '.$row['time']  ?></th>
                  
                  
                </tr>

              <?php } ?>

            </tbody>
            <tfoot>


            </tfoot>
          </table>
        </div>
        <!-- /.box-body -->
      </div>

    </section>
    <!-- /.content -->
  </div>

  <!-- /.content-wrapper -->

  <?php
  include("dounbr.php");
  ?>

  <!-- /.control-sidebar -->

  <!-- Add the sidebar's background. This div must be placed

       immediately after the control sidebar -->

  <div class="control-sidebar-bg"></div>
  </div>

  <?php include_once("script.php"); ?>

  <!-- DataTables -->
  <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
  <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
  <!-- SlimScroll -->
  <script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="../../plugins/fastclick/fastclick.js"></script>
  <!-- page script -->
     <!-- date picker -->
     <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>

  <script>
    $(function() {
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


    $('#date_pick').datepicker({
            autoclose: true,
            datepicker: true,
            format: 'yyyy-mm-dd '
        });
        $('#date_pick').datepicker({
            autoclose: true
        });
  </script>
</body>

</html>