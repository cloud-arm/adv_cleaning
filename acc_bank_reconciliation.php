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
                Bank Reconciliation
                <small>Preview</small>
            </h1>

        </section>
        <!-- Main content -->
        <section class="content">
            <!-- SELECT2 EXAMPLE -->
            <div class="row d-flex">
                <?php
                $result = $db->prepare("SELECT * FROM bank LIMIT 4");
                $result->bindParam(':userid', $res);
                $result->execute();
                for ($i = 0; $row = $result->fetch(); $i++) {
                ?>
                    <div class="col-lg-3 col-xs-6">
                        <div class="small-box" style="border: 1px solid;padding: 15px 0; ">
                            <div class="inner">
                                <h3 style="font-size: 23px;position: relative;"><?php echo $row['amount']; ?></h3>

                                <p><?php echo $row['name']; ?></p>
                            </div>
                            <div class="icon" style="color: rgba(var(--bg-content-light),0.2);">
                                <i class="fa fa-bank"></i>
                            </div>
                        </div>
                    </div>
                <?php    } ?>
            </div>
            <div class="row">

                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title">Select Bank</h3>
                            <!-- /.box-header -->
                        </div>
                        <div class="form-group">
                            <div class="box-body d-block">
                                <form action="" method="GET">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label>Bank Accounts</label>

                                                <select class="form-control select2" name="bank" style="width: 100%;" tabindex="1" autofocus>

                                                    <?php
                                                    $result = $db->prepare("SELECT * FROM bank ");
                                                    $result->bindParam(':userid', $res);
                                                    $result->execute();
                                                    for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                        <option value="<?php echo $row['id']; ?>">
                                                            <?php echo $row['name']; ?> -<?php echo $row['ac_no']; ?>
                                                        </option>
                                                    <?php    } ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-5">
                                            <label>Date range:</label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input type="text" class="form-control pull-right" id="reservation" name="dates" value>
                                            </div>
                                        </div>

                                        <div class="col-md-2" style="height: 75px; display:flex; align-items:center;">
                                            <input type="submit" class="btn btn-info" value="Apply">
                                        </div>

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bank Reg List</h3>
                            <!-- /.box-header -->
                        </div>

                        <?php

                        $bank = $_GET['bank'];
                        $dates = $_GET['dates'];
                        $from = date_format(date_create(explode("-", $dates)[0]), "Y-m-d");
                        $to = date_format(date_create(explode("-", $dates)[1]), "Y-m-d");


                        $sql = "SELECT * FROM bank_record WHERE debit_acc_id='$bank' AND date BETWEEN '$from' AND '$to'";

                        ?>

                        <div class="box-body d-block">
                            <table id="example2" class="table table-bordered table-hover" style="border-radius: 0;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Trans: Type</th>
                                        <th>CD: Name</th>
                                        <th>CD: Type</th>
                                        <th>CD: Balance</th>
                                        <th>DB: Name</th>
                                        <th>DB: Type</th>
                                        <th>DB: Balance</th>
                                        <th>Date</th>
                                        <th>Chq No</th>
                                        <th>Chq Bank</th>
                                        <th>Chq Date</th>
                                        <th>Amount (Rs.)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total = 0;
                                    $result = $db->prepare($sql);
                                    $result->bindParam(':userid', $res);
                                    $result->execute();
                                    for ($i = 0; $row = $result->fetch(); $i++) {
                                    ?>
                                        <tr>
                                            <td><?php echo $row['id']; ?></td>
                                            <td><?php echo $row['transaction_type']; ?></td>
                                            <td><?php echo $row['credit_acc_name']; ?></td>
                                            <td><?php echo $row['credit_acc_type']; ?></td>
                                            <td><?php echo $row['credit_acc_balance']; ?></td>
                                            <td><?php echo $row['debit_acc_name']; ?></td>
                                            <td><?php echo $row['debit_acc_type']; ?></td>
                                            <td><?php echo $row['debit_acc_balance']; ?></td>
                                            <td><?php echo $row['date']; ?></td>
                                            <td><?php echo $row['chq_no']; ?></td>
                                            <td><?php echo $row['chq_bank']; ?></td>
                                            <td><?php echo $row['chq_date']; ?></td>
                                            <td><?php echo $row['amount']; ?></td>
                                            <?php $total += $row['amount']; ?>
                                        </tr>
                                    <?php
                                    }
                                    ?>
                                </tbody>

                            </table>
                            <h4>Total Amount <small> Rs. </small><b><?php echo number_format($total, 2); ?></h4>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Un-realize Chq</h3>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $chq_un = 0;
                                        $result = $db->prepare("SELECT * FROM payment WHERE action=1 AND pay_type='Chq' ");
                                        $result->bindParam(':userid', $res);
                                        $result->execute();
                                        for ($i = 0; $row = $result->fetch(); $i++) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['chq_no']; ?></td>
                                                <td><?php echo $row['chq_bank']; ?></td>
                                                <td><?php echo $row['chq_date']; ?></td>
                                                <td><?php echo $row['amount']; ?></td>
                                                <?php $chq_un += $row['amount']; ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>

                                </table>
                                <h4>Total Rs <b><?php echo number_format($chq_un, 2); ?></h4>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box box-warning">
                        <div class="box-header with-border">
                            <h3 class="box-title">Chq In-hand</h3>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $chq_in = 0;
                                        $result = $db->prepare("SELECT * FROM payment WHERE action=0 AND pay_type='Chq' ");
                                        $result->bindParam(':userid', $res);
                                        $result->execute();
                                        for ($i = 0; $row = $result->fetch(); $i++) {
                                        ?>
                                            <tr>
                                                <td><?php echo $row['id']; ?></td>
                                                <td><?php echo $row['chq_no']; ?></td>
                                                <td><?php echo $row['chq_bank']; ?></td>
                                                <td><?php echo $row['chq_date']; ?></td>
                                                <td><?php echo $row['amount']; ?></td>
                                                <?php $chq_in += $row['amount']; ?>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </tbody>

                                </table>
                                <h4>Total Rs <b><?php echo number_format($chq_in, 2); ?></h4>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="box box-primary">
                        <div class="box-header">
                            <h3 class="box-title">Summary</h3>
                        </div>
                        <!-- /.box-header -->

                        <?php
                        $exp = 0;
                        $rq = $db->prepare("SELECT sum(amount) FROM bank WHERE id=:id");
                        $rq->bindParam(':id', $bank);
                        $rq->execute();
                        for ($i = 0; $r = $rq->fetch(); $i++) {
                            $tot = $r['sum(amount)'];
                        }
                        ?>
                        <div class="box-body d-block">
                            <table class=" table">
                                <tr>
                                    <td style="border: 0;">
                                        <h4 style="margin: 0">Chq in-hand :</h4>
                                    </td>
                                    <td style="border: 0;">
                                        <h4 style="margin: 0">Rs. <?php echo $chq_in ?>.00</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4 style="margin: 0;">Un-realize chq :</h4>
                                    </td>
                                    <td>
                                        <h4 style="margin: 0">Rs. <?php echo $chq_un ?>.00</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4 style="margin: 0">Account Balance :</h4>
                                    </td>
                                    <td>
                                        <h4 style="margin: 0">Rs. <?php echo $tot + $chq_un ?>.00</h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <h4 style="margin: 0">Available Balance :</h4>
                                    </td>
                                    <td>
                                        <h4 style="margin: 0">Rs. <?php echo $tot; ?></h4>
                                    </td>
                                </tr>
                            </table>
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



    <script type="text/javascript">
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