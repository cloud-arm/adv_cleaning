<!DOCTYPE html>
<html>
<?php
include("head.php");
include_once("auth.php");

$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_FORM'] = 'price_fm';
?>

<body class="hold-transition skin-blue skin-orange sidebar-mini">

    <?php include_once("start_body.php"); 
  ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                PRICEING
                <small>Preview</small>
            </h1>
        </section>

        <section class="content">
            <div class="box">
                <div class="box-header">

                    <div class="row">
                        <div class="col-md-3">
                            <h3 class="box-title">Price Data</h3>
                            <span onclick="click_open(1)" class="btn btn-primary btn-sm pull-right mx-2">Add
                                Price</span>
                        </div>


                    </div>

                </div>
                <!-- /.box-header -->

                <div class="box-body">


                    <table id="example1" class="table table-bordered table-striped">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Slab</th>
                                <th>Price</th>
                            </tr>

                        </thead>
                        <tbody>
                            <?php $result = select('price_fm','*');
    for ($i = 0; $row = $result->fetch(); $i++) { ?>
                            <tr>
                                <td><?php echo $row['id']  ?></td>
                                <td><?php echo $row['date']  ?></td>
                                <td><?php echo $row['product']  ?></td>
                                <td><?php echo number_format($row['slb_from']).'-'.number_format($row['slb_to'])   ?></td>
                                <td><?php echo $row['price']  ?></td>
                                

                            </tr>
                           
                </div>
                <?php } ?>
                </tbody>
                </table>

            </div>



        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php
    $con = 'd-none';
    ?>

    <div class="container-up <?php echo $con; ?>" id="container_up">
        <div class="row w-70">
            <div class="box box-success popup" id="popup_1" style="width: 80%;">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Customer save
                    </h3>
                    <small onclick="click_close(1)" class="btn btn-sm btn-success pull-right"><i
                            class="fa fa-times"></i></small>
                </div>

                <div class="box-body d-block">
                    <form method="POST" action="save/price_save.php">

                        <div class="row" style="display: block;">



                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Service</label>
                                    <select class="form-control select2 " id="name"
                                                name="product_id" style="width: 100%;" autofocus>
                                                <?php 
                                        $result = select('products', '*');
                                        for ($i = 0; $row = $result->fetch(); $i++) {  
                                        ?>
                                                <option value="<?php echo $row['id']; ?>">
                                                    <?php echo $row['product_name'].' ['.$row['cat'].']'; ?>
                                                </option>
                                                <?php 
                                        } 
                                        ?>
                                            </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label> Start</label>
                                    <input type="number" name="from" value="" class="form-control" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>End</label>
                                    <input type="number" name="to" value="" class="form-control" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label> Price</label>
                                    <input type="number" name="price" value="" class="form-control" autocomplete="off" >
                                </div>
                            </div>



                            <div class="col-md-5">
                                <div class="form-group">
                                    <input type="hidden" name="unit" value="1">
                                    <input type="submit" style="margin-top: 23px; width: 100%;" value="Save"
                                        class="btn btn-info btn-sm">
                                </div>
                            </div>


                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
  include("dounbr.php");
  ?>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
    </div>
    <!-- ./wrapper -->
    <?php include_once("script.php"); ?>

    <!-- DataTables -->
    <script src="../../plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../../plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="../../plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="../../plugins/fastclick/fastclick.js"></script>

    <script>
    get_value('product-box', 'product_table.php?str=all')
    </script>

    <!-- page script -->
    <script>
    function edit_note(i) {
        //  $(".popup").addClass("d-none");
        $("#edit_popup_" + i).removeClass("d-none");
    }

    function edit_close(i) {
        //  $(".popup").addClass("d-none");
        $("#edit_popup_" + i).addClass("d-none");
    }

    function click_open(i) {
        $(".popup").addClass("d-none");
        $("#popup_" + i).removeClass("d-none");
        $("#container_up").removeClass("d-none");

        if (i == 2) {
            $('#txt_icon').focus();
        }
        if (i == 3) {
            $('#txt_sec').focus();
        }
        if (i == 4) {
            $('#txt_perm').focus();
            $('#txt_perm').select();
        }
    }

    function click_close(i) {
        if (i) {
            $(".popup").addClass("d-none");
            $("#container_up").addClass("d-none");
        } else {
            $(".popup").addClass("d-none");
            $("#popup_1").removeClass("d-none");
        }
    }
    $(function() {
        $("#example1").DataTable({
            "autoWidth": false
        });
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });

    });

    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            // Redirect to a PHP page that handles the deletion
            window.location.href = 'product_dll.php?id=' + id;
        }
    }
    </script>
</body>

</html>