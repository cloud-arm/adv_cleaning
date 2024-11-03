<!DOCTYPE html>
<html>
<?php
include("head.php");
include_once("auth.php");
$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_FORM'] = 'grn_order';
include('connect.php');

$u = $_SESSION['SESS_MEMBER_ID'];
$invo = $_GET['id'];
$new='ORD'.date('YmdHis');
if(!isset($_GET['id'])){
    header('Location:grn_order.php?id='.$new);
}

// Assuming you already have a PDO connection ($db)
$stmt = $db->prepare("SELECT * FROM purchases_list WHERE user_id = :user_id AND action = '' AND type = 'Return'");
$stmt->execute(['user_id' => $u]);

// Loop through the results
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $invo = $row['invoice_no'];
    // Do something with $invo if needed
}
?>


<body class="hold-transition skin-blue skin-orange sidebar-mini">

    <?php include_once("start_body.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Purchases Order
                <small>Preview</small>
            </h1>

        </section>
        <!-- Main content -->
        <section class="content">
            <!-- SELECT2 EXAMPLE -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Add</h3>
                            <!-- /.box-header -->
                        </div>

                        <div class="box-body d-block">
                            <form method="POST" action="grn_list_save.php">

                                <div class="row">

                                    <div class="col-md-12 m-0">
                                        <div class="form-group" id="status"></div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <label>Product</label>
                                                </div>
                                                <select class="form-control select2" name="pr" id="p_sel"
                                                    onchange="pro_select()" style="width: 100%;" tabindex="1" autofocus>
                                                    <?php
                                                        // Fetch and display product options from the database
                                                        $result = select_query("SELECT * FROM materials");
                                                        while ($row = $result->fetch()) {
                                                            $mat_id = $raw['id'] ?>
                                                                        <option value="<?php echo $row['id']; ?>">
                                                                            <?php echo $row['name']; ?>
                                                                        </option>
                                                                        <?php } ?>
                                                </select> 
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <label>Unit</label>
                                                </div>
                                                <select class="form-control select2" name="unit" id="unit"
                                                    style="width: 100%;" tabindex="1" autofocus>
                                                    <!-- Options will be populated dynamically by JavaScript -->
                                                    <option value="0">Default</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <label>Qty</label>
                                                </div>
                                                <input type="number" class="form-control" value="1" name="qty"
                                                    tabindex="2">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <label>Cost Price</label>
                                                </div>
                                                <input type="number" class="form-control" name="sell" id="sell"
                                                    step=".01" tabindex="2" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="hidden" name="id" value="<?php echo $invo; ?>">
                                            <input type="hidden" name="id2" value="1">

                                            <input type="hidden" name="type" value="Order">
                                            <input class="btn btn-warning" type="submit" value="Save">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <div class="box-body d-block">
                            <table id="example2" class="table table-bordered table-hover" style="border-radius: 0;">
                                <tr>
                                    <th>Product Name</th>
                                    <th>Unit</th>
                                    <th>QTY</th>
                                    <th>Cost (Rs.)</th>
                                    <th>Amount (Rs.)</th>
                                    <th>#</th>
                                </tr>
                                <?php $total = 0;
                                $style = "";
                                $result = select_query("SELECT * FROM purchases_list WHERE invoice_no = '$invo' ");
                                for ($i = 0; $row = $result->fetch(); $i++) {
                                    $pro_id = $row['product_id'];

                                    $re = select_query("SELECT * FROM materials WHERE id = '$pro_id' ");
                                    for ($i = 0; $rw = $re->fetch(); $i++) {
                                        $stock = $rw['available_qty'];
                                    }
                                    if ($stock < 0) {
                                        $style = 'style="color:red" ';
                                    }

                                ?>
                                <tr <?php echo $style; ?> class="record">
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['unit']; ?></td>
                                    <td><?php echo $row['qty']; ?></td>
                                    <td><?php echo $row['sell']; ?></td>
                                    <td><?php echo $row['amount']; ?></td>
                                    <td> <a href="#" id="<?php echo $row['id']; ?>" class="dll_btn btn btn-danger"
                                            title="Click to Delete"> X</a></td>
                                    <?php $total += $row['amount']; ?>
                                </tr>
                                <?php
                                }
                                ?>

                            </table>
                            <h4>Total Rs <b><?php echo number_format($total, 2); ?></h4>

                        </div>

                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Order Save</h3>
                            <!-- /.box-header -->
                        </div>
                        <div class="form-group">
                            <div class="box-body d-block">
                                <form method="POST" action="grn_save.php">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Supplier</label>
                                                <select class="form-control select2" name="supply" style="width: 100%;"
                                                    tabindex="1" autofocus>
                                                    <?php
                                                    $result = select_query("SELECT * FROM supplier ");
                                                    for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                    <option value="<?php echo $row['id']; ?>">
                                                        <?php echo $row['name']; ?></option>
                                                    <?php    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>PO Number(optional)</label>
                                                <input class="form-control" type="text" name="sup_invoice"
                                                    autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Note</label>
                                                <input class="form-control" type="text" name="note" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-2" style="height: 75px;display: flex; align-items: end;">
                                            <div class="form-group">
                                                <input type="hidden" name="id" value="<?php echo $invo; ?>">
                                                <input type="hidden" name="type" value="Order">
                                                <input class="btn btn-success" type="submit" value="Submit">
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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
    function pro_select() {
        let productId = $('#p_sel').val();

        // Existing AJAX calls for other functionality
        $.ajax({
            type: "GET",
            url: "grn_status.php",
            data: {
                id: productId,
                type: "Order",
                ac: 0
            },
            success: function(res) {
                $("#status").empty();
                $("#status").append(res);
            }
        });

        $.ajax({
            type: "GET",
            url: "grn_status.php",
            data: {
                id: productId,
                type: "Order",
                ac: 1
            },
            success: function(res) {
                $("#sell1").val(parseFloat(res));
            }
        });

        $.ajax({
            type: "GET",
            url: "grn_status.php",
            data: {
                id: productId,
                type: "Order",
                ac: 2
            },
            success: function(res) {
                $("#cost1").val(parseFloat(res));
            }
        });

        // New AJAX call to fetch units for selected product
        $.ajax({
            type: "GET",
            url: "get_units.php",
            data: {
                mat_id: productId
            },
            success: function(response) {
                // Populate the Unit selector with the received options
                $("#unit").empty();
                $("#unit").append(response);
            },
            error: function() {
                alert("Error fetching unit data");
            }
        });
    }


    $(".dll_btn").click(function() {
        var element = $(this);
        var id = element.attr("id");
        var info = 'id=' + id;
        if (confirm("Sure you want to delete this Collection? There is NO undo!")) {

            $.ajax({
                type: "GET",
                url: "grn_list_dll.php",
                data: info,
                success: function() {

                }
            });
            $(this).parents(".record").animate({
                    backgroundColor: "#fbc7c7"
                }, "fast")
                .animate({
                    opacity: "hide"
                }, "slow");
        }
        return false;
    });

    $(function() {
        $(".select2").select2();


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


        //Date picker
        $('#datepicker1').datepicker({
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


    });
    </script>

</body>

</html>