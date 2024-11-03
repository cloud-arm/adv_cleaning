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
?>

<body class="hold-transition skin-blue skin-orange sidebar-mini ">

<style>
        /* Initially hide the form */
        #orderForm {
            display: none;
        }
    </style>

    <?php include_once("start_body.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Order
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
            <?php
    // Fetch purchase list data based on invoice number
    $r1 = select_query("SELECT * FROM purchases_list WHERE invoice_no='$invo' AND type='Order'");
    $i = 1; // Initialize counter for numbering rows
    while ($row = $r1->fetch()) {
        $invo = $row['invoice_no'];
    }?>

            <?php
    // Fetch purchase list data based on invoice number
    $r1 = select_query("SELECT * FROM purchases WHERE invoice_no='$invo' AND type='Order'");
    $i = 1; // Initialize counter for numbering rows
    while ($row = $r1->fetch()) {
        $sup_invo = $row['supplier_invoice'];
        $remark = $row['remarks'];
    }?>


            <div class="box box-info">

            <div class="box-header with-border">
    <h3 class="box-title" style="text-transform: capitalize;">Order</h3>
    <h5>
        <strong style="color: #007bff;">Invoice no:</strong> <?php echo $invo; ?>
        <span style="margin-left: 20px;">
            <strong style="color: #007bff;">PO No:</strong> <?php echo $sup_invo; ?>
        </span>
        <span style="margin-left: 20px;">
            <strong style="color: #007bff;">Note:</strong> <?php echo $remark; ?>
        </span>
    </h5>
    <button class="btn btn-primary" onclick="toggleForm()" style="float: right;">Add Items</button>
</div>



                <div class="box-body d-block">
                    <table id="example" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Price</th>
                                <th>Action</th>
                                <th>Unit</th>
                                
                                <th>#</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch purchase list data based on invoice number
                            $r1 = select_query("SELECT * FROM purchases_list WHERE invoice_no='$invo' AND type='Order'");
                            $i = 1; // Initialize counter for numbering rows
                            while ($row = $r1->fetch()) {
                                $invo = $row['invoice_no'];
                                $type = $row['type'];
                                $date = $row['date'];
                                $qty = $row['qty'];
                                $action = $row['action'];
                                //$amount = $row['amount'];
                                $price = $row['sell'];
                                $id = $row['id'];
                                $approved = $row['approve'];
                                $name = $row['name'];


                                // Only display rows where approval is not equal to 5
                                if ($approved != 5) {
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td> <!-- Increment row number -->
                                <td><?php echo $name; ?></td>
                                <td><?php echo $type; ?></td>
                                <td><?php echo $date; ?></td>
                                <td><?php echo $qty; ?></td>
                                <td><?php echo $price*$qty; ?></td>
                                <td><?php echo $price; ?></td>

                                <td><?php if($approved == 1){
                                    echo "Approved";   
                                } 
                                    if($approved == 0){
                                        echo "Pending";
                                    }
                                 ?></td>
                                 <td><?php echo $row['unit']?></td>
                                <td>
                                    <?php if ($user_level == 1): ?>
                                    <?php if ($approved != 1 && $approved != 5): ?>
                                    <!-- Hide buttons if approved is 1 or 5 -->
                                    <a class="btn btn-danger" onclick="confirmApp(<?php echo $id; ?>)">
                                        <i class="fas fa-check-circle"></i>
                                    </a>
                                    <a class="btn btn-danger" onclick="confirmDelete(<?php echo $id; ?>)">
                                        <i class="fas fa-times-circle"></i>
                                    </a>
                                    <?php endif; ?>

                                    <?php if ($approved != 5): ?>
                                    <!-- Hide edit button only when approved is 5 -->
                                    <a class="btn btn-danger" onclick="edit_note(<?php echo $row['id']; ?>)">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php endif; ?>
                                </td>


                            </tr>
                            <div class="container-up d-none" id="edit_popup_<?php echo $row['id']; ?>">
                                <div class="row w-70">
                                    <div class="box box-success popup" style="width: 50%;">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">Edit details</h3>
                                            <small onclick="edit_close(<?php echo $row['id']; ?>)"
                                                class="btn btn-sm btn-success pull-right"><i
                                                    class="fa fa-times"></i></small>
                                            <i class="fa fa-times"></i>
                                            </small>
                                        </div>
                                        <div class="box-body d-block">
                                            <form method="POST" action="edit_grn_order.php">
                                                <div class="row" style="display: block;">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>qty</label>
                                                            <input type="text" name="qty" class="form-control"
                                                                value="<?php echo $row['qty']; ?>" required>

                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label>Price</label>
                                                            <input type="text" name="sell" class="form-control"
                                                                value="<?php echo $row['sell']; ?>" required>

                                                        </div>
                                                    </div>


                                                </div>







                                                <div class="col-md-12">

                                                </div>

                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <input type="hidden" name="unit" value="1">
                                                        <input type="hidden" name="id"
                                                            value="<?php echo $row['id']; ?>">

                                                        <input type="submit" style="margin-top: 23px; width: 100%;"
                                                            value="Save" class="btn btn-info btn-sm">
                                                    </div>
                                                </div>

                                        </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                </div>
                <?php
                        } 
                        ?>
                <?php
        }
    
    ?>
                </tbody>

                </table>
            </div>

    </div>

    <div id="orderForm" class="col-md-12">
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
                                    <input type="number" class="form-control" value="1" name="qty" tabindex="2">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <label>Cost Price</label>
                                    </div>
                                    <input type="number" class="form-control" name="sell" id="sell" step=".01"
                                        tabindex="2" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo $invo; ?>">
                                <input type="hidden" name="id2" value="0">

                                <input type="hidden" name="type" value="Order">
                                <input class="btn btn-warning" type="submit" value="Save">
                            </div>
                        </div>
                    </div>
                </form>
            </div>



        </div>
    </div>

    <?php
       $r1 = select_query("SELECT * FROM purchases WHERE invoice_no='$invo'");
       $i = 1; // Initialize counter for numbering rows
       while ($row = $r1->fetch()) {
           $supplier_id = $row['supplier_id'];
           $approved = $row['approve'];

   
       }
       if ($approved == 'approve') {
       ?>


    <div class="col-md-12">
        <div class="box box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">GRN Save</h3>
                <!-- /.box-header -->
            </div>
            <div class="form-group">
                <div class="box-body d-block">
                    <form method="POST" action="grn_save.php">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Supplier</label>
                                    <select class="form-control select2" name="supply" style="width: 100%;" tabindex="1"
                                        autofocus>
                                        <?php
                                                    $result = select_query("SELECT * FROM supplier WHERE id = $supplier_id");
                                                    for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                                        <?php    } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Pay Type</label>
                                    <select class="form-control" name="pay_type" onchange="select_pay()" id="method">
                                        <option>Cash</option>
                                        <option>Card</option>
                                        <option>Bank</option>
                                        <option>Chq</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 slt-bank" style="display:none;">
                                <div class="form-group">
                                    <label>Account No</label>
                                    <input class="form-control" type="text" name="acc_no" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3 slt-bank" style="display:none;">
                                <div class="form-group">
                                    <label>Bank Name</label>
                                    <input class="form-control" type="text" name="bank_name" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3 slt-chq" style="display:none;">
                                <div class="form-group">
                                    <label>Chq Number</label>
                                    <input class="form-control" type="text" name="chq_no" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3 slt-chq" style="display:none;">
                                <div class="form-group">
                                    <label>Chq Bank</label>
                                    <input class="form-control" type="text" name="chq_bank" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3 slt-chq" style="display:none;">
                                <div class="form-group">
                                    <label>Chq Date</label>
                                    <input class="form-control" id="datepicker1" type="text" name="chq_date"
                                        autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Pay Amount</label>
                                    <input class="form-control" type="number" name="amount" autocomplete="off" min="0"
                                        required>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Supplier Order Number(optional)</label>
                                    <input class="form-control" type="text" name="sup_invoice" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Note</label>
                                    <input class="form-control" type="text" name="note" autocomplete="off">
                                </div>
                            </div>

                            <div class="col-md-3" style="height: 75px;display: flex; align-items: end;">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="<?php echo $invo; ?>">
                                    <input type="hidden" name="type" value="GRN">
                                    <input class="btn btn-success" type="submit" value="Submit">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php
       }
       ?>
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




<script>
    // Function to toggle form visibility
    function toggleForm() {
        var form = document.getElementById("orderForm");
        form.style.display = form.style.display === "none" ? "block" : "none";
    }
</script>

    <script>
    function confirmApp(id) {
        if (confirm('Are you sure you want to Approve this item?')) {
            // Redirect to a PHP page that handles the deletion
            window.location.href = 'grn_order_app_save.php?id=' + id + '&id2=1';
        }
    }

    function pro_select() {
        let productId = $('#p_sel').val();

        

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
    </script>

    <script>
    function confirmDelete(id) {
        if (confirm('Are you sure you want to reject this item?')) {
            // Redirect to a PHP page that handles the deletion
            window.location.href = 'grn_order_app_save.php?id=' + id + '&id2=5';
        }
    }


    function select_pay() {
        var val = $('#method').val();
        if (val == "Bank") {
            $('.slt-bank').css("display", "block");
        } else {
            $('.slt-bank').css("display", "none");
        }

        if (val == "Chq") {
            $('.slt-chq').css("display", "block");
        } else {
            $('.slt-chq').css("display", "none");
        }
    }
    </script>

    <script>
    function edit_note(i) {
        //  $(".popup").addClass("d-none");
        $("#edit_popup_" + i).removeClass("d-none");
    }

    function edit_close(i) {
        //  $(".popup").addClass("d-none");
        $("#edit_popup_" + i).addClass("d-none");
    }
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
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                        'month').endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            },
            function(start, end) {
                $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'));
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