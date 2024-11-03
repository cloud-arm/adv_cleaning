<!DOCTYPE html>
<html>
<?php
include("head.php");
include_once("auth.php");
$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_FORM'] = 'stock_adjustment';
date_default_timezone_set("Asia/Colombo");
?>

<body class="hold-transition skin-blue skin-orange sidebar-mini">

    <?php include_once("start_body.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                STOCK ADJUSTMENT
                <small>Preview</small>
            </h1>

        </section>
        <!-- Main content -->
        <section class="content">

            <div class="box box-warning">
                <div class="box-header with-border">
                    <h3 class="box-title">Stock Adjustment</h3>
                    <!-- /.box-header -->
                </div>

                <div class="box-body d-block">
                    <form method="POST" action="stock_adjustment_save.php" onsubmit="return validateForm()">

                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Materials</label>
                                    <select class="form-control select2" name="id" style="width: 100%;" autofocus>
                                        <?php
                                                                        $result1 = select("materials", "*");
                                                                        for ($i = 0; $row1 = $result1->fetch(); $i++) {
                                                                        ?>
                                        <option value="<?php echo $row1['id']; ?>"
                                            <?php if($row['category']==$row1['name']){echo "selected";} ?>>
                                            <?php echo $row1['name']; ?>
                                        </option>
                                        <?php } ?>
                                    </select>
                                </div>

                            </div>



                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>New stock</label>
                                    <input type="number" step=".01" class="form-control" name="new_stock" tabindex="3"
                                        autocomplete="off" min="0" required>
                                </div>
                            </div>


                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input type="text" class="form-control" name="note" tabindex="4" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group" style="margin-top: 23px;">
                                    <input class="btn btn-warning" type="submit" value="Save">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="box-body d-block">
                    <table id="example1" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Previous stock</th>
                                <th>New stock</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $date = date('Y-m-d');
                            $result = select("stock_adjustment", "*", "date = '" . $date . "' ");
                            for ($i = 0; $row = $result->fetch(); $i++) {
                            ?>
                            <tr class="record">
                                <td><?php echo $i + 1; ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['pr_stock']; ?></td>
                                <td><?php echo $row['new_stock']; ?></td>
                                <td>
                                    <a href="#" onclick="dll_row(<?php echo $row['id']; ?>)"
                                        class="btn btn-danger btn-sm" title="Click to Delete"> x</a>
                                    <form action="stock_adjustment_dll.php" method="GET"
                                        id="dll_<?php echo $row['id']; ?>">
                                        <input type="hidden" value="<?php echo $row['id']; ?>" name="id">
                                    </form>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

            </div>

        </section>

        <section class="content">

            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Stock Adjustment List</h3>
                </div>

                <form action="" method="GET">
                    <div class="row" style="margin-top: 20px;display: flex;align-items: center;">
                        <div class="col-lg-3"></div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control select2 hidden-search" name="year" style="width: 100%;"
                                    tabindex="1">
                                    <option> <?php echo date('Y') - 1 ?> </option>
                                    <option selected> <?php echo date('Y') ?> </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select class="form-control select2 hidden-search" name="month" style="width: 100%;"
                                    tabindex="1">
                                    <?php for ($x = 1; $x <= 12; $x++) {
                                        $mo = sprintf("%02d", $x);
                                        $month = date('m');
                                        if (isset($_GET['month'])) {
                                            $month = $_GET['month'];
                                        } ?>
                                    <option <?php if ($mo == $month) {
                                                    echo 'selected';
                                                } ?>> <?php echo $mo; ?> </option>
                                    <?php  } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="btn btn-info btn-sm" for="">
                                    <i class="fa fa-search"></i>
                                    <input style="border: 0;background-color: transparent;" type="submit"
                                        value="Search">
                                </label>
                            </div>
                        </div>
                    </div>
                </form>

                <?php
                include("connect.php");
                date_default_timezone_set("Asia/Colombo");

                $d1 = date('Y-m') . '-01';
                $d2 = date('Y-m') . '-31';

                if (isset($_GET['year'])) {

                    $d1 = $_GET['year'] . '-' . $_GET['month'] . '-01';
                    $d2 = $_GET['year'] . '-' . $_GET['month'] . '-31';
                }

                $sql = " SELECT * FROM stock_adjustment  WHERE  date BETWEEN '$d1' AND '$d2' ";

                ?>

                <div class="box-body d-block">
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Previous stock</th>
                                <th>New stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = select_query($sql);
                            for ($i = 0; $row = $result->fetch(); $i++) {
                            ?>
                            <tr class="record">
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['product_name']; ?></td>
                                <td><?php echo $row['date']; ?></td>
                                <td><?php echo $row['pr_stock']; ?></td>
                                <td><?php echo $row['new_stock']; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

    </div>
    <!-- /.content-wrapper -->
    <?php include("dounbr.php"); ?>

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
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap datepicker -->
    <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- bootstrap color picker -->


    <script type="text/javascript">
    function dll_row(id) {
        if (confirm("Sure you want to delete this row? There is NO undo!")) {
            $('#dll_' + id).submit();
        }
        return false;
    }

    $(function() {
        $('#example1').DataTable();
        $('#example2').DataTable();
        $('#example3').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": true
        });
    });
    </script>

    <script>
    function validateForm() {
        const newStock = document.querySelector('input[name="new_stock"]').value;

        if (newStock < 0) {
            alert("New stock cannot be less than 0.");
            return false;
        }

        return true;
    }
    </script>


    <!-- Page script -->
    <script>
    $(function() {
        //Initialize Select2 Elements
        $(".select2").select2();
        $(".select2.hidden-search").select2({
            minimumResultsForSearch: -1
        });

        //Date range picker
        $('#reservation').daterangepicker();
        //Date range picker with time picker
        //$('#datepicker').datepicker({datepicker: true,  format: 'yyyy/mm/dd '});
        //Date range as a button


        //Date picker
        $('#datepic').datepicker({
            autoclose: true,
            datepicker: true,
            format: 'yyyy-mm-dd '
        });

    });
    </script>

</body>

</html>