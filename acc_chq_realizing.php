<!DOCTYPE html>
<html>
<?php
include("head.php");
include("connect.php");
?>


<script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>


<body class="hold-transition skin-blue sidebar-mini">
    <?php
    include_once("auth.php");
    $r = $_SESSION['SESS_LAST_NAME'];
    if ($r == 'Cashier') {
        include_once("sidebar2.php");
    }
    if ($r == 'admin') {
        //include_once("sidebar.php");
        include_once("start_body.php");

    }

    ?>
    <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Chq Realizing
                <small>Preview</small>
            </h1>

        </section>
        <!-- Main content -->
        <section class="content">
            <!-- SELECT2 EXAMPLE -->
            <div class="row">

                <div class="col-md-12" id="chq_box">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Chq Realize</h3>
                            <!-- /.box-header -->
                        </div>
                        <div class="form-group">
                            <div class="box-body d-block">
                                <table id="example2" class="table table-bordered table-hover" style="border-radius: 0;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Chq No</th>
                                            <th>Chq Bank</th>
                                            <th>Chq Date</th>
                                            <th>Amount (Rs.)</th>
                                            <th>Realize</th>
                                            <th>Return</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total = 0;
                                        $style = "";
                                        $result = $db->prepare("SELECT * FROM payment WHERE action=1 AND pay_type='chq' ");
                                        $result->bindParam(':userid', $res);
                                        $result->execute();
                                        for ($i = 0; $row = $result->fetch(); $i++) {
                                        ?>
                                            <tr id="re_<?php echo $row['id']; ?>">
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['chq_no']; ?></td>
                                                <td><?php echo $row['chq_bank']; ?></td>
                                                <td><?php echo $row['chq_date']; ?></td>
                                                <td><?php echo $row['amount']; ?></td>
                                                <td align="center"> <a id="<?php echo $row['id']; ?>" onclick="chq_action(<?php echo $row['id']; ?>)" class="realize_btn btn btn-success" title="Click to Realize"> <i class="fa-solid fa-money-bill-transfer"></i></a></td>
                                                <td align="center"> <a id="<?php echo $row['id']; ?>" class="return_btn btn btn-danger" title="Click to Return"> <i class="fa-solid fa-rotate-left"></i> </a></td>
                                                <?php $total += $row['amount']; ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>

                                </table>
                                <h4>Total Rs <b><?php echo number_format($total, 2); ?></h4>

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

    <!-- jQuery 2.2.3 -->
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
    <!-- Select2 -->
    <script src="../../plugins/select2/select2.full.min.js"></script>
    <!-- date-range-picker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
    <script src="../../plugins/daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap datepicker -->
    <script src="../../plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- bootstrap color picker -->



    <script type="text/javascript"> GRANT ALL PRIVILEGES ON *.auto_expert TO 'chalitha'@'%' WITH GRANT OPTION;

               function chq_action(id){
            var info = 'type=realize&id=' + id;

            $.ajax({
                type: "POST",
                url: "acc_bank_transfer_save.php",
                data: info,
                success: function(res) {
                    $('#re_' + res).animate({
                            backgroundColor: "#fbc7c7"
                        }, "fast")
                        .animate({
                            opacity: "hide"
                        }, "slow");
                }
            });
        }
        $(".realize_btn").click(function() {
            var element = $(this);
            var id = element.attr("id");
            var info = 'type=realize&id=' + id;

            $.ajax({
                type: "POST",
                url: "acc_bank_transfer_save.php",
                data: info,
                success: function(res) {
                    $('#re_' + res).animate({
                            backgroundColor: "#fbc7c7"
                        }, "fast")
                        .animate({
                            opacity: "hide"
                        }, "slow");
                }
            });

        });



        $(".return_btn").click(function() {
            var element = $(this);
            var id = element.attr("id");
            var info = 'type=return&id=' + id;

            $.ajax({
                type: "POST",
                url: "acc_bank_transfer_save.php",
                data: info,
                success: function(res) {
                    $('#re_' + res).animate({
                            backgroundColor: "#fbc7c7"
                        }, "fast")
                        .animate({
                            opacity: "hide"
                        }, "slow");
                }
            });

        });

        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": false,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": false,
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