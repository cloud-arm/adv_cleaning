<!DOCTYPE html>

<html>

<?php
include("head.php");
include_once("auth.php");
$r = $_SESSION['SESS_LAST_NAME'];
$_SESSION['SESS_DEPARTMENT'] = 'management';
$_SESSION['SESS_FORM'] = 'index';
$user_level = $_SESSION['USER_LEWAL'];

?>

<body class="hold-transition skin-yellow skin-orange sidebar-mini">

    <?php include_once("start_body.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        <!-- Content Header (Page header) -->
        <section class="content-header">

            <h1>
                Home
                <small>Preview</small>
            </h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <?php

            include('connect.php');
            date_default_timezone_set("Asia/Colombo");
            $cash = $_SESSION['SESS_FIRST_NAME'];
            $date =  date("Y-m-d");
            ?>


            <div class="row">
                <div class="col-sm-6 col-md-4 col-xs-12">

                    <div class="info-box">
                        <span class="info-box-icon"><i class="fa fa-file-text"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">JOB COUNT</span>
                            <span class="info-box-number"><?php echo $tot_job=select_item('job','COUNT(id)','action < 5'); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: 100%"></div>
                            </div>
                            <span class="progress-description">
                                Total running jobs
                            </span>
                        </div>

                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-xs-12">

                    <div class="info-box">
                        <span class="info-box-icon"><i class="fa fa-building"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">NEXT</span>
                            <span class="info-box-number"><?php echo $cop_job=select_item('job','COUNT(id)',"action < 5 AND customer_type = 'corporate' "); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?php echo ($cop_job/$tot_job)*100 ?>%"></div>
                            </div>
                            
                        </div>

                    </div>
                </div>

                <div class="col-sm-6 col-md-4 col-xs-12">

                    <div class="info-box">
                        <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">NEXT</span>
                            <span class="info-box-number"><?php echo $ret_job=select_item('job','COUNT(id)',"action < 5 AND customer_type = 'retail' "); ?></span>
                            <div class="progress">
                                <div class="progress-bar" style="width: <?php echo ($ret_job/$tot_job)*100 ?>%"></div>
                            </div>
                            
                        </div>

                    </div>
                </div>

                
            </div>

   
  

            <div class="box">
    <div class="box-header">
        <div class="row">
            <div class="col-md-9">
                <h3 class="box-title">JOB LIST</h3>
            </div>
            <div class="col-md-3 text-right">
                <span onclick="click_open('add')" class="btn btn-primary btn-sm">Add New JOB</span>
            </div>
        </div>



        <!-- Search Bar -->
        <?php if($user_level != 5){ ?>
<div class="row mt-2">
    <small>
        <form method="get">
            <div class="col-md-2">
                <select name="type" id="type" class="form-control select2">
                    <option value="all" <?= (!isset($_GET['type']) || $_GET['type'] == 'all') ? 'selected' : ''; ?>>All</option>
                    <option value="corporate" <?= (isset($_GET['type']) && $_GET['type'] == 'corporate') ? 'selected' : ''; ?>>Corporate</option>
                    <option value="retail" <?= (isset($_GET['type']) && $_GET['type'] == 'retail') ? 'selected' : ''; ?>>Retail</option>
                </select>
            </div>
            <div class="col-md-1">
                <input type="submit" value="Filter job type" class="btn btn-primary">
            </div>
        </form>
    </small>
</div>
<?php }?>

    </div>

    <!-- Job List Table -->
    <div class="box-body">
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Job Number</th>
                    <th>Customer</th>
                    <th>Member</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Prograss</th>

                    <th>#</th>

                </tr>
            </thead>
            <tbody>
            <?php
                if ($user_level != 5) {
                    // For non-level 5 users, show all jobs or filter by customer type
                    
                    if (!isset($_GET['type']) ) {
                        $result = select('job');
                    } else{
                        $result = select('job');
                        if($_GET['type'] == 'all') {
                            $result = select('job');
                        }
                    }
                    
                } else if ($user_level == 5) {
                    // For level 5 users, default to showing retail jobs
                    if (!isset($_GET['type']) || $_GET['type'] == 'retail') {
                        // If no type is set or type is 'retail', show retail jobs
                        $result = select('job', '*', "customer_type='retail'");
                    } else {
                        // In case someone manually tries to set another type, force retail jobs
                        $result = select('job', '*', "customer_type='retail'");
                    }
                }

                for ($i = 0; $row = $result->fetch(); $i++) {
                    $id = $row['id'];
                    $dll = $row['dll'];

                    if ($dll != 1) {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td>
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <span><?php echo $row['all_job_no']; ?></span>
                            <div align="center">
                                <?php 
                                    if ($row['customer_type'] == 'corporate') {
                                        echo '<div class="badge bg-blue"><i class="fas fa-building"></i>';
                                    } elseif ($row['customer_type'] == 'retail') {
                                        echo '<div class="badge bg-yellow"><i class="fas fa-shopping-cart"></i>';
                                    }
                                ?>
                            </div>
                            <br>
                        </div> 
                    </td>
                    <td><?php echo $row['name']; ?></td>
                    <td>
                        <?php
                        $r = select('job_team', '*', 'job_id=' . $id);

                        while ($locationRow = $r->fetch()) {
                        ?>
                        <div align="center">
                            <div class="badge bg-green">
                                <i class="fas fa-user" style="margin-right: 5px;"></i>
                                <?php echo $locationRow['name']; ?>
                            </div>
                            <br>
                        </div>
                        <?php } ?>
                    </td>
                    <td><?php echo $row['date']; ?></td>
 

                    <td>
                        <?php if ($row['status'] == 'running') { ?>
                        <div align="center">

                        <?php if($row['action'] == 5){ ?>
                            <div class="badge bg-yellow">
                                <?php }else{ ?>
                                    <div class="badge bg-blue">
                            <?php } ?>
                                <i class="fas fa-circle-o-notch fa-spin icon " style="margin-right: 5px;"></i>
                                <?php echo $row['status']; ?>
                            </div>
                        </div>
                        <?php } elseif ($row['status'] == 'pending') { ?>
                        <div align="center">
                            <div class="badge bg-blue" >
                                <i class="fas fa-wrench" style="margin-right: 5px;"></i>
                                <?php echo $row['status']; ?>
                            </div>
                        </div>
                        <?php } elseif ($row['status'] == 'finish') { ?>
                        <div align="center">
                            <div class="badge bg-green" >
                                <i class="fas fa-wrench" style="margin-right: 5px;"></i>
                                <?php echo $row['status']; ?>
                            </div>
                        </div>
                        <?php } ?>
                    </td>
                    <td>
                        <?php
                        // Define status weights
                        $statusWeights = [
                            'measure' => 0,
                            'artwork' => 1,
                            'on_aprove' => 2,
                            'printing' => 3,
                            'fix' => 4,
                            'complete' => 5
                        ];

                        // Count total jobs for the job_no
                        $countQuery = $db->prepare("SELECT COUNT(id) FROM sales_list WHERE job_no = :id");
                        $countQuery->bindParam(':id', $id, PDO::PARAM_INT);
                        $countQuery->execute();
                        $totalJobs = $countQuery->fetchColumn();



                        // Ensure we have jobs under the job_no
                        if ($totalJobs > 0) {
                            // Count jobs by status for the job_no
                            $statusQuery = $db->prepare("SELECT status, COUNT(id) as job_count FROM sales_list WHERE job_no = :id GROUP BY status");
                            $statusQuery->bindParam(':id', $id, PDO::PARAM_INT);
                            $statusQuery->execute();

                            $totalProgress = 0;

                            // Loop through statuses and calculate total progress
                            while ($statusRow = $statusQuery->fetch(PDO::FETCH_ASSOC)) {
                                $status = $statusRow['status'];
                                $jobCount = $statusRow['job_count'];

                                // Multiply job count by the weight for the status
                                if (isset($statusWeights[$status])) {
                                    $totalProgress += $jobCount * $statusWeights[$status];
                                }
                            }

                            // Calculate the maximum possible score (each job can have a weight up to 5)
                            $maxScore = $totalJobs * 5;

                            // Calculate the progress percentage
                            if ($maxScore > 0) {
                                $progressPercentage = ($totalProgress / $maxScore) * 100;
                            } else {
                                $progressPercentage = 0;
                            }

                             // Display the progress percentage and the progress bar
                           //  echo '<div style="margin-bottom: 10px; font-weight: bold; color: #333;">Progress: ' . round($progressPercentage, 2) . '%</div>';

                            // Display a more stylish progress bar with transition effects
                            echo '<div style="width: 100%; background-color: #e0e0e0; border-radius: 25px; height: 25px; position: relative; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); overflow: hidden;">';

                            // Inner progress bar
                            echo '<div style="width: ' . $progressPercentage . '%; background-color: #76c7c0; height: 100%; border-radius: 25px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 14px; transition: width 0.4s ease;">';
                            echo round($progressPercentage, 2) . '%'; // Show percentage inside the bar
                            echo '</div>';

                            // Optional: Add a tooltip for more detailed progress information
                            echo '<div style="position: absolute; top: -30px; left: ' . ($progressPercentage - 10) . '%; background-color: #333; color: white; padding: 5px 10px; border-radius: 5px; font-size: 12px; white-space: nowrap; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">';
                            echo 'Current Progress: ' . round($progressPercentage, 2) . '%';
                            echo '</div>';

                            echo '</div>';
                            } else {
                                // No jobs found for the job_no
                                echo '<div style="color: #ff0000; font-weight: bold;">No jobs available.</div>';
                            }

                        ?>
                    </td>




                    <td>
                        <a href="job_view?id=<?php echo base64_encode($row['id']); ?>">
                            <button class="btn btn-sm btn-info">View</button>
                        </a>
                        <?php if ($user_level == 1): ?>
                        <a class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</a>
                        <button onclick="click_open('edit', <?php echo $row['id']; ?>)" class="btn btn-sm btn-warning">Edit</button>
                        <?php endif; ?>
                    </td>
                </tr>

                <!-- Edit Popup for each job -->
                <div class="container-up d-none" id="edit_popup_<?php echo $id; ?>">
                    <div class="row justify-content-center">
                        <div class="box box-success popup" style="width: 180%; max-width: 800px;">
                            <div class="box-header with-border d-flex justify-content-between align-items-center">
                                <h3 class="box-title">Edit Job: <?php echo $row['id']; ?></h3>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="click_close('<?php echo $id; ?>')">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                            <div class="box-body">
                                <form method="POST" action="edit_job.php">
                                    <div class="form-group mb-4">
                                        <label for="job-number">Job Number</label>
                                        <input type="text" name="all_job_no" id="job-number" class="form-control" 
                                            value="<?php echo $row['all_job_no']; ?>" placeholder="Enter job number" required>
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="note">Note</label>
                                        <textarea name="note" id="note" class="form-control" cols="30" rows="5" 
                                                placeholder="Enter any notes about the job"><?php echo $row['note']; ?></textarea>
                                    </div>

                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <input type="hidden" name="id2" value="0">

                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary mr-2">Save</button>
                                        <button type="button" class="btn btn-secondary" onclick="click_close('<?php echo $id; ?>')">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>


    </div>


        <!-- Add New Job Popup -->
        <div class="container-up d-none" id="add_job_popup">
            <div class="row w-70">
                <div class="box box-success popup" style="width: 50%;">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add New Job</h3>
                        <small onclick="click_close('add')" class="btn btn-sm btn-success pull-right">
                            <i class="fa fa-times"></i>
                        </small>
                    </div>

                <div class="box-body d-block">
                    <form method="POST" action="save/job/job_save.php">

                        <div class="row" style="display: block;">





                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Phone No.</label>
                                    <input type="text" class="form-control" onchange="find_cus()" id="phone" name="phone"> 
                                    
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <input type="text" id="cus_name" name="name" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" id="cus_address" name="address" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-10">
                                <div class="form-group">
                                    <label>Note</label>
                                    <textarea name="note" cols="70" rows="10" class="form-control"></textarea>
                                </div>
                            </div>

                            

                           

                            

                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="hidden" name="cus_id" value="0" id="cus_id">

                                    <input type="submit"  onclick="click_close('add')"  style="margin-top: 23px; width: 100%;" id="u1" value="Save"
                                        class="btn btn-info btn-sm pull-right">
                                </div>
                            </div>


                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content-wrapper -->

    <?php include("dounbr.php"); ?>

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

<script> 
function find_cus(){
    var contact = document.getElementById('phone').value;
   
    var data = 'ur';
        fetch("customer_data_get.php?contact=" + contact)
            .then((response) => response.json())
            .then((json) => fill(json));
}

function fill(json) {
        console.log(json);

        if (json.action == "true") {
            console.log("old patient");
            document.getElementById('cus_name').value = json.cus_name;
            document.getElementById('cus_address').value = json.cus_address;
            document.getElementById('phone').value = json.cus_phone_no;
            document.getElementById('cus_id').value = json.cus_id;


            document.getElementById('cus_name').disabled = true;
            document.getElementById('cus_address').disabled = true;
            

            document.getElementById('cus_name').style= 'border: 1px solid #0cc40f';
            document.getElementById('cus_address').style= 'border: 1px solid #0cc40f';
            document.getElementById('phone').style= 'border: 1px solid #0cc40f';


        } else {
            console.log("new patient");
            document.getElementById('cus_name').value = '';
            document.getElementById('cus_address').value = '';
            document.getElementById('cus_id').value = '0';


            document.getElementById('cus_name').disabled = false;
            document.getElementById('cus_address').disabled = false;
            document.getElementById('phone').disabled = false;

            document.getElementById('cus_name').style= 'border: 1px solid ';
            document.getElementById('cus_address').style= 'border: 1px solid ';
            document.getElementById('phone').style= 'border: 1px solid ';

        }
    }

 function confirmDelete(id) {
     if (confirm('Are you sure you want to delete this item?')) {
         // Redirect to a PHP page that handles the deletion
         window.location.href = 'job_dill.php?id=' + id;
     }
 }

        </script>





  
<script>
    function click_open(type, id = null) {
        // Hide all popups initially
        document.querySelectorAll('.container-up').forEach(function(popup) {
            popup.classList.add('d-none');
        });

        // Open the Add New Job popup
        if (type === 'add') {
            document.getElementById('add_job_popup').classList.remove('d-none');
        }
        
        // Open the Edit Job popup for the given ID
        if (type === 'edit') {
            document.getElementById('edit_popup_' + id).classList.remove('d-none');
        }
    }

    function click_close(type) {
        // Close the Add Job popup
        if (type === 'add') {
            document.getElementById('add_job_popup').classList.add('d-none');
        } 
        // Close the Edit Job popup for the given ID
        else {
            document.getElementById('edit_popup_' + type).classList.add('d-none');
        }
    }
</script>






</body>

</html>
