<!DOCTYPE html>
<html>
<?php
include("head.php");
?>

<body class="hold-transition skin-yellow skin-orange sidebar-mini">
    <?php
    include_once("auth.php");
    $r = $_SESSION['SESS_LAST_NAME'];
    $_SESSION['SESS_DEPARTMENT'] = 'accounting';
    $_SESSION['SESS_FORM'] = 'acc_bank_transfer';

    //include_once("sidebar.php");
    include_once("start_body.php");

    ?>
    <!-- /.sidebar -->
    </aside>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                BANK TRANSFER
                <small>Preview</small>
            </h1>

        </section>
        <!-- Main content -->
        <section class="content">
            <!-- SELECT2 EXAMPLE -->
            <div class="row">
                <div class="col-md-6">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Transfer Select</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <div class="row">

                                            <div class="col-md-9" style="display: block;" id="err">
                                                <h4 class="text-danger " style="margin-left: 50px; text-align: center;">Select transfer method</h4>
                                            </div>

                                            <div class="col-md-10 mt-2" style="display:flex; justify-content: center; margin: 25px 0 0 20px; ">
                                                <div class="form-group ">
                                                    <span class="btn btn-primary btn-lg m-0 me-2 acc" id="cash" onclick="acc_type('cash')">Cash Deposit</span>
                                                    <span class="btn btn-warning btn-lg m-0 ms-2 acc" id="chq" onclick="acc_type('chq')">Chq Deposit</span>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="cash_box" style="display: none;">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Cash Deposit</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <form method="POST" action="acc_bank_transfer_save.php">
                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label>Select Bank</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <label>Bank Accounts</label>
                                                            </div>

                                                            <select class="form-control select2 hidden-search" name="bank" style="width: 100%;" tabindex="1" autofocus>
                                                                <?php
                                                                $result = select("bank");
                                                                for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                                    <option value="<?php echo $row['id']; ?>">
                                                                        <?php echo $row['name']; ?> - <?php echo $row['ac_no']; ?>
                                                                    </option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label>Select Cash</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <label>Cash Accounts</label>
                                                            </div>

                                                            <select class="form-control select2 hidden-search" name="cash" onchange="cash_deposit(this.options[this.selectedIndex].getAttribute('amount'),this.value)" style="width: 100%;" tabindex="1" autofocus>
                                                                <option value="0" selected disabled>Select Account</option>
                                                                <?php
                                                                $result = select("cash");
                                                                for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                                    <option value="<?php echo $row['id']; ?>" amount="<?php echo $row['amount']; ?>">
                                                                        <?php echo $row['name']; ?>
                                                                    </option>
                                                                <?php  } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <h5 style="text-align: center;margin-top: 0; display: none;" id="cash_h5">Account Bal: <small>Rs.</small> <span id="cash_amount"></span> </h5>
                                                        <input type="hidden" id="cash_blc">
                                                    </div>
                                                </div>

                                                <div class="col-md-6 pay_sec">
                                                    <div class="form-group ">
                                                        <label>Transfer Amount</label>
                                                        <input class="form-control" step=".01" type="number" name="amount" onkeyup="check_deposit(this.value)" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-5" style="display:flex; justify-content: center; margin-top: 20px;">
                                                    <input type="hidden" name="type" id="txt_acc" value="deposit">
                                                    <input class="btn btn-danger" type="submit" id="btn_de" value="Deposit" disabled>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="chq_box" style="display: none;">
                            <div class="box box-warning">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Chq Deposit</h3>
                                    <!-- /.box-header -->
                                </div>

                                <div class="box-body d-block">
                                    <div class="row">

                                        <div class="col-md-1"></div>
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label>Select Bank</label>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <label>Bank Accounts</label>
                                                    </div>

                                                    <select class="form-control select2 hidden-search" name="bank" id="bank_sel0" style="width: 100%;" tabindex="1" autofocus>
                                                        <?php
                                                        $result = select("bank");
                                                        for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                            <option value="<?php echo $row['id']; ?>">
                                                                <?php echo $row['name']; ?> - <?php echo $row['ac_no']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table id="example2" class="table table-bordered table-hover" style="border-radius: 0;">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Chq No</th>
                                                <th>Chq Bank</th>
                                                <th>Chq Date</th>
                                                <th>Amount (Rs.)</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $total = 0;
                                            $style = "";
                                            $result = select("payment", "*", "action=0 AND dll = 0 AND pay_type='chq' ");
                                            for ($i = 0; $row = $result->fetch(); $i++) {
                                            ?>
                                                <tr id="re_<?php echo $row['id']; ?>">
                                                    <td><?php echo $row['id']; ?></td>
                                                    <td><?php echo $row['chq_no']; ?></td>
                                                    <td><?php echo $row['chq_bank']; ?></td>
                                                    <td><?php echo $row['chq_date']; ?></td>
                                                    <td><?php echo $row['amount']; ?></td>
                                                    <td> <span onclick="deposit_chq(<?php echo $row['id']; ?>)" class="btn btn-success" title="Click to Deposit"> <i class="fa-solid fa-money-bill-transfer"></i></span></td>
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

                <div class="col-md-6">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="box box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Withdraw Section</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <form method="POST" action="acc_bank_transfer_save.php">
                                            <div class="row">

                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <label>Bank Accounts</label>
                                                            </div>

                                                            <select class="form-control select2 hidden-search" name="bank" id="bank_sel1" onchange="bank_select(this.options[this.selectedIndex].getAttribute('amount'),'1')" style="width: 100%;" tabindex="1" autofocus>
                                                                <option value="0" selected disabled>Select Account</option>
                                                                <?php
                                                                $result = select("bank");
                                                                for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                                    <option value="<?php echo $row['id']; ?>" amount="<?php echo $row['amount']; ?>">
                                                                        <?php echo $row['name']; ?> -<?php echo $row['ac_no']; ?>
                                                                    </option>
                                                                <?php    } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12" id="bank_blc_sec1" style="display: none;">
                                                    <div class="form-group acc-cont">
                                                        <h5 style="text-align: center;margin-top: 0;">Account Bal: <small>Rs.</small> <span id="bank_blc_span1"></span> </h5>
                                                        <input type="hidden" id="bank_blc_txt1" value="0">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <label>Select Cash</label>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <label>Cash Accounts</label>
                                                            </div>

                                                            <select class="form-control select2 hidden-search" name="cash" style="width: 100%;" tabindex="1" autofocus>
                                                                <option value="0" selected disabled>Select Account</option>
                                                                <?php
                                                                $result = select("cash");
                                                                for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                                    <option value="<?php echo $row['id']; ?>" amount="<?php echo $row['amount']; ?>">
                                                                        <?php echo $row['name']; ?>
                                                                    </option>
                                                                <?php  } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-md-1"></div>
                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Transfer Amount</label>
                                                        <input class="form-control" step=".01" type="number" name="amount" id="with_txt" onkeyup="check_withdraw(this.value)" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-5" style="display:flex; justify-content: center; margin-top: 20px;">
                                                    <input type="hidden" name="type" value="withdraw">
                                                    <input class="btn btn-danger" type="submit" id="btn_wi" value="Withdraw" disabled>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title">Bank Charges</h3>
                                    <!-- /.box-header -->
                                </div>
                                <div class="form-group">
                                    <div class="box-body d-block">
                                        <form method="POST" action="acc_bank_transfer_save.php">
                                            <div class="row">

                                                <div class="col-md-1"></div>
                                                <div class="col-md-10">
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <label>Bank Accounts</label>
                                                            </div>

                                                            <select class="form-control select2 hidden-search" name="bank" id="bank_sel2" onchange="bank_select(this.options[this.selectedIndex].getAttribute('amount'),'2')" style="width: 100%;" tabindex="1" autofocus>
                                                                <option value="0" selected disabled>Select Account</option>
                                                                <?php
                                                                $result = select("bank");
                                                                for ($i = 0; $row = $result->fetch(); $i++) { ?>
                                                                    <option value="<?php echo $row['id']; ?>" amount="<?php echo $row['amount']; ?>">
                                                                        <?php echo $row['name']; ?> -<?php echo $row['ac_no']; ?>
                                                                    </option>
                                                                <?php    } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-12" id="bank_blc_sec2" style="display: none;">
                                                    <div class="form-group acc-cont">
                                                        <h5 style="text-align: center;margin-top: 0;">Account Bal: <small>Rs.</small> <span id="bank_blc_span2"></span> </h5>
                                                        <input type="hidden" id="bank_blc_txt2" value="0">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Description</label>
                                                        <input class="form-control" type="text" name="desc" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Date</label>
                                                        <input class="form-control" id="datepicker" type="text" name="date" value="<?php echo date('Y-m-d'); ?>" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group ">
                                                        <label>Amount</label>
                                                        <input class="form-control" type="number" step=".01" name="amount" id="char_txt" onkeyup="check_charge(this.value)" autocomplete="off">
                                                    </div>
                                                </div>

                                                <div class="col-md-6" style="margin-top: 20px;">
                                                    <input type="hidden" name="type" value="chargers">
                                                    <input class="btn btn-info" type="submit" id="btn_charge" value="Save" disabled>
                                                </div>

                                            </div>
                                        </form>

                                    </div>
                                </div>
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

    <?php include_once('script.php'); ?>

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
        function check_charge(txt) {
            let blc = parseFloat($("#bank_blc_txt2").val());

            if (0 >= txt) {
                $('#btn_charge').attr("disabled", "");
            } else {
                $('#btn_charge').removeAttr('disabled');
            }
        }

        function bank_select(amount, i) {
            $('#bank_blc_span' + i).text(amount);
            $('#bank_blc_txt' + i).val(amount);
            $('#bank_blc_sec' + i).css('display', 'block');
        }

        function check_withdraw(txt) {
            let blc = parseFloat($("#bank_blc_txt").val());

            if (0 >= txt) {
                $('#btn_wi').attr("disabled", "");
            } else {
                $('#btn_wi').removeAttr('disabled');
            }
        }

        function acc_type(type) {
            $(".acc").addClass("hover disabled");
            $("#" + type).removeClass("hover disabled");
            $('#err').css('display', 'none');

            if (type == 'cash') {
                $('#cash_box').css('display', 'block')
            } else {
                $('#cash_box').css('display', 'none')
            }
            if (type == 'chq') {
                $('#chq_box').css('display', 'block')
            } else {
                $('#chq_box').css('display', 'none')
            }
        }

        function check_deposit(txt) {
            let blc = parseFloat($("#cash_blc").val());

            if (0 >= txt || blc < txt) {
                $('#btn_de').attr("disabled", "");
            } else {
                $('#btn_de').removeAttr('disabled');
            }

        }

        function deposit_chq(id) {
            let bank = $('#bank_sel0').val();
            var info = 'type=chq&id=' + id + '&bank=' + bank;

            $.ajax({
                type: "POST",
                url: "acc_bank_transfer_save.php",
                data: info,
                success: function(res) {
                    console.log(res);
                }
            });
            $('#re_' + id).animate({
                    backgroundColor: "#fbc7c7"
                }, "fast")
                .animate({
                    opacity: "hide"
                }, "slow");
        }

        function cash_deposit(val, id) {
            $("#cash_blc").val(val);
            $("#cash_amount").text(val);
            $('#cash_h5').css('display', 'block');
        }

        $(function() {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
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
            $('.select2.hidden-search').select2({
                minimumResultsForSearch: -1
            });

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
                autoclose: true,
                datepicker: true,
                format: 'yyyy-mm-dd '
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