<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CLOUD ARM</title>

    <?php
    include("head.php");
    include("../connect.php");
    date_default_timezone_set("Asia/Colombo");

    $user_id = $_SESSION['SESS_MEMBER_ID'];
    $date = date("Y-m-d");

    $emp = 0;
    $result = $db->prepare("SELECT * FROM user WHERE id = '$user_id' ");
    $result->bindParam(':id', $res);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $user = $row['employee_id'];
        $pos = $row['position'];
    }

    $result = $db->prepare("SELECT * FROM attendance WHERE emp_id =:id AND date = '$date' ");
    $result->bindParam(':id', $user);
    $result->execute();
    for ($i = 0; $row = $result->fetch(); $i++) {
        $emp = $row['emp_id'];
    }

    if ($emp == 0 && $pos != 'admin') {
        header("location: attendance.php?id=$user");
    }

    

    
        $collection = $user;
    
    ?>
</head>

<body class="bg-light">

    <header>
        <nav class="navbar navbar-expand-lg bg-none mt-3">
            <div class="container-fluid">
                <div class="navbar-brand ms-2" style="--bs-navbar-brand-font-size: 1.5rem">Hi <span class="ms-1" style="font-weight: 600;"><?php echo $_SESSION['SESS_FIRST_NAME']; ?></span></div>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a href="order.php" class="d-none"><span class="navbar-toggler border-0"><i id="icon" class="fa-solid fa-bell"></i></span></a>
                <div class="collapse navbar-collapse" id="nav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="inquiries_customer_checking.php">Inquiries</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="booking.php">Booking List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="cost_analyser.php">Meter Reading</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="expenses.php">Expenses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="assign_job.php">Assign List</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="report.php">Reports</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <?php if ($pos == 'admin') { ?>
        <div class="container-fluid mt-3" style="overflow-x: scroll;">
            <div class="container" style="width: max-content;">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <div class="small-box">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="content asn">
                                                <i class="fa-solid fa-cart-shopping"></i>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="content aud">
                                                <h4>Sales</h4>
                                                <p>
                                                    Rs.<?php $date = date("Y-m-d");
                                                        $result = $db->prepare("SELECT sum(amount)  FROM sales  WHERE action='active' AND date = '$date' ORDER BY transaction_id DESC");
                                                        $result->bindParam(':userid', $date);
                                                        $result->execute();
                                                        for ($i = 0; $row = $result->fetch(); $i++) {
                                                            echo number_format($row['sum(amount)'], 2);
                                                        } ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small-box">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="content asn">
                                                <i class="fa-solid fa-chart-simple"></i>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="content aud">
                                                <h4>Expenses</h4>
                                                <p>
                                                    Rs.<?php
                                                        $result = $db->prepare("SELECT sum(amount)  FROM expenses_records  WHERE date = '$date' ");
                                                        $result->bindParam(':userid', $date);
                                                        $result->execute();
                                                        for ($i = 0; $row = $result->fetch(); $i++) {
                                                            echo number_format($row['sum(amount)'], 2);
                                                        } ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small-box">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="content asn">
                                                <i class="fa-solid fa-user-group"></i>
                                            </div>
                                        </div>
                                        <div class="col-8">
                                            <div class="content aud">
                                                <h4>Visitors</h4>
                                                <p class="text-center">
                                                    <?php $result = $db->prepare("SELECT count(id)  FROM job  WHERE date = '$date' AND (action = 'active' OR action = 'close') ");
                                                    $result->bindParam(':userid', $date);
                                                    $result->execute();
                                                    for ($i = 0; $row = $result->fetch(); $i++) {
                                                        echo $row['count(id)'];
                                                    } ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    <?php } ?>



   


    <div class="container room-container">
        <div class="row">
            <?php 
            $result = $db->prepare("SELECT * FROM job_team JOIN job ON job.id=job_team.job_id WHERE  job_team.emp_id='$user' AND job.status !='finish' ");
            $result->bindParam(':userid', $date);
            $result->execute();
            for ($i = 0; $row = $result->fetch(); $i++) { $con=$row['status'] ?>
        <div class="col-12 col-sm-6 col-md-6 col-lg-4">
        <div class="ajk_ady ">
            <div class="info-box" style="border: 2px solid rgb(var(--bg-theme));<?php if ($con == 'Started') { ?> background: rgba(var(--bg-theme), 0.25);<?php } ?>">
                <div class="row w-100">
                    <div class="col-12 p-0">
                        <div class="inb_num">
                            <span class="num" <?php if ($con == 'Started') { ?> style="color: rgb(var(--bg-black));" <?php } ?>><?php // echo $num; ?></span>
                            
                            <span class="head"><?php echo $row['name']; ?></span>
                        </div>
                    </div>
                    
                </div>
                <div class="row w-100">
                    <div class="col-12 p-0">
                        <div class="inb_num">
                            <span class="num" <?php if ($con == 'active') { ?> style="color: rgb(var(--bg-black));" <?php } ?>><?php // echo $num; ?></span>
                            
                            <span class="type"><?php echo  $row['note']; ?> </span>
                        </div>
                    </div>
                    <div class="col-12 as_jdk">
                        <div class="info-foot">
                            <div class="qty-box">
                                <span class="time"><?php // echo  $deff_time; ?> </span>
                            </div>
                            <div class="app">
                                
                                <a class="nav-link" style="align-self: end;"  href="job_view.php?id=<?php echo $row['id'] ?>">
                                    <span <?php if ($con == 'active') { ?> style="color: rgb(var(--bg-black));" <?php } ?> class="bin btn">View</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php // echo $badge; ?>
        </div>
    </div>
    <?php } ?>
        </div>
    </div>


    <!-- Bootstrap 5.3.2-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <!-- Jquery 3.7.1 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>


    <script>
        $(document).ready(function() {
            var xmlhttp;
            if (window.XMLHttpRequest) {
                xmlhttp = new XMLHttpRequest();
            } else {
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    document.getElementById("room-box").innerHTML = xmlhttp.responseText;
                }
            }

            xmlhttp.open("GET", "appointment_get.php", true);
            xmlhttp.send();

            $('#float_btn').click(function() {

                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xmlhttp.onreadystatechange = function() {
                    if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                        document.getElementById("room-box").innerHTML = xmlhttp.responseText;
                    }
                }

                xmlhttp.open("GET", "appointment_get.php", true);
                xmlhttp.send();
            });

            $(".click_fun").click(function() {
                $(".click_fun").removeClass("active");
                $(this).addClass("active");
            });
        });
    </script>
</body>

</html>