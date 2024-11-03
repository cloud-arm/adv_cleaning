<!DOCTYPE html>
<html>
<?php
include("head.php");
include_once("auth.php");
$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_FORM'] = 'grn_payment';
date_default_timezone_set("Asia/Colombo");
?>

<body class="hold-transition skin-blue skin-orange sidebar-mini">

    <?php include_once("start_body.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Supplier
                <small>Payment</small>
            </h1>

        </section>

        <!-- add item -->
        <section class="content">

            <div class="row">
                <div class="col-md-12">

                    <div class="box box-info">
                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-md-3">
                                    <h3 class="box-title">Payment</h3>
                                </div>
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <label>Supplier</label>
                                        </div>
                                        <?php
                                        $result = select_query("SELECT * FROM supplier ");?>
                                        <select class="form-control select2" id="supply" onchange="invo_get()" style="width: 100%;" tabindex="1">
                                            <option value="0" selected disabled> Select Supplier </option>
                                            <?php for ($i = 0; $row = $result->fetch(); $i++) {  ?>
                                                <option value="<?php echo $row['id']; ?>"> <?php echo $row['name']; ?> </option>
                                            <?php  } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="box-body d-block">
                                <form method="POST" action="grn_payment_save.php">
                                    <div class="row">

                                        <div class="col-md-12">
                                            <div class="form-group" id="bill"></div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Supply Invoice</label>
                                                <select class="form-control select2" name="invoice" onchange="tbl_get()" id="invo" style="width: 100%;" tabindex="1">
                                                    <option value="0" selected disabled> select invoice </option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label>Pay Type</label>
                                                <select class="form-control" onchange="select_pay()" name="pay_type" id="method">
                                                    <option>Cash</option>
                                                    <option>Card</option>
                                                    <option>Bank</option>
                                                    <option>Chq</option>
                                                    <option>Credit_Note</option>
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

                                        <div class="col-md-4" id="slt-credit" style="display:none;">
                                            <div class="form-group">
                                                <label>Credit Invoice</label>
                                                <select class="form-control" name="credit_note" id="credit_note">
                                                    <option value="0" selected></option>
                                                </select>
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
                                                <input class="form-control" id="datepicker1" type="text" name="chq_date" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Pay Amount</label>
                                                <input class="form-control" type="number" name="amount" id="pay_txt" onkeyup="checking()" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Note</label>
                                                <input class="form-control" type="text" name="note" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-md-1 ps-0" style="height: 70px; display: flex; align-items: end;">
                                            <div class="form-group">
                                                <input type="hidden" name="id" id="invo_no">
                                                <input type="hidden" name="sup_id" id="sup_id">
                                                <input class="btn btn-success" type="submit" id="submit" value="Submit" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Payment List</h3>
                            <!-- /.box-header -->
                        </div>

                        <div class="box-body d-block">
                            <table id="example1" class="table table-bordered table-hover" style="border-radius: 0;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Invoice</th>
                                        <th>Date</th>
                                        <th>Type</th>
                                        <th>Chq No</th>
                                        <th>Chq Date</th>
                                        <th>Bank Name</th>
                                        <th>Acc No</th>
                                        <th>Amount (Rs.)</th>
                                        <th>#</th>
                                    </tr>
                                </thead>

                                <tbody id="tbl">

                                </tbody>
                            </table>

                        </div>

                    </div>
                </div>
            </div>

        </section>
        <!-- /.content -->
    </div>

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
        function checking() {

            let val = $("#credit_note").val();
            var info = 'type=cred_set&id=' + val;
            $.ajax({
                type: "GET",
                url: "grn_payment_get.php",
                data: info,
                success: function(res) {
                    $("#cr_blc").val(res);
                }
            });

            let am = $("#pay_txt").val();
            let pt = $("#method").val();
            let blc = parseInt($("#blc").val());
            let cr_blc = parseInt($("#cr_blc").val());

            if (0 >= am) {
                $('#submit').attr("disabled", "");
            } else

            if (pt == 'Credit_Note') {

                if (am <= cr_blc) {
                    $('#submit').removeAttr("disabled");
                } else {
                    $('#submit').attr("disabled", "");
                }

            } else

            if (am <= blc) {
                $('#submit').removeAttr("disabled");
            } else {
                $('#submit').attr("disabled", "");
            }
        }

        function invo_get() {
            let val = $("#supply").val();
            var info = 'type=inv_get&id=' + val;
            $.ajax({
                type: "GET",
                url: "grn_payment_get.php",
                data: info,
                success: function(res) {
                    $("#invo").empty();
                    $("#invo").append(res);
                }
            });

            $("#sup_id").val(val);
        }

        function tbl_get() {
            let val = $("#invo").val();

            var info = 'type=bill_get&id=' + val;
            $.ajax({
                type: "GET",
                url: "grn_payment_get.php",
                data: info,
                success: function(res) {
                    $("#bill").empty();
                    $("#bill").append(res);
                }
            });

            info = 'type=tbl_get&id=' + val;
            $.ajax({
                type: "GET",
                url: "grn_payment_get.php",
                data: info,
                success: function(res) {
                    $("#tbl").empty();
                    $("#tbl").append(res);
                }
            });

            $("#invo_no").val(val);
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

            if (val == "Credit_Note") {
                $('#slt-credit').css("display", "block");

                let val = $("#supply").val();
                var info = 'type=cred_get&id=' + val;
                $.ajax({
                    type: "GET",
                    url: "grn_payment_get.php",
                    data: info,
                    success: function(res) {
                        $("#credit_note").empty();
                        $("#credit_note").append(res);
                    }
                });

            } else {
                $('#slt-credit').css("display", "none");
            }
        }

        function dll_btn(id) {
            var info = 'id=' + id;
            if (confirm("Sure you want to delete this Collection? There is NO undo!")) {

                $.ajax({
                    type: "GET",
                    url: "grn_payment_dll.php",
                    data: info,
                    success: function(res) {
                        tbl_get();
                        invo_get();
                    }
                });

            }
            return false;
        }

        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": true
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


            //Date picker
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

        });
    </script>


</body>

</html>