<header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="icon/favicon.png" alt=""></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg dark"><img src="icon/logo dark.png" alt=""></span>
        <span class="logo-lg light"><img src="icon/logo dark.png" alt=""></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">

        </a>
        <?php
        include('connect.php');
        include('config.php');
        date_default_timezone_set("Asia/Colombo");

        $date =  date("Y-m-d");
        $dep = $_SESSION['SESS_DEPARTMENT'];
        $menu_action = $_SESSION['SESS_FORM'];
        $in_page = $_SESSION['SESS_FORM'];
        $pos = $_SESSION['SESS_LAST_NAME'];
        $user_level = $_SESSION['USER_LEWAL'];
        $main_menu_action = 0;
        $sub1_menu_action = 0;

        $data = select("sys_sidebar", "main_id,type,id", " link = '{$menu_action}'"); //--------------
        foreach ($data as $row) {
            if ($row['type'] == 'sub1') {
                $main_menu_action = $row['main_id'];
            }
            if ($row['type'] == 'main') {
                $menu_action = $row['id'];
            }

            if ($row['type'] == 'sub2') {
                $sub1_menu_action = $row['main_id'];
                $data1 = select("sys_sidebar", "main_id,type", " id = '{$sub1_menu_action}'");
                foreach ($data1 as $row1) {
                    $main_menu_action = $row1['main_id'];
                }
            }
        }
        ?>
        <div class="navbar-menu">
            <ul class="nav navbar-nav">
                <?php
                $result = select_query("SELECT *,sys_section.id AS sn FROM sys_section JOIN sys_permission_arm ON sys_section.id=sys_permission_arm.menu_id WHERE sys_permission_arm.user_level = '{$user_level}' AND sys_permission_arm.type = 'user_level' AND sys_permission_arm.section = 'header' AND sys_permission_arm.action = 1 AND sys_section.action = 1  ");
                for ($i = 0; $row = $result->fetch(); $i++) {
                ?>

                    <li class="<?php if ($dep == $row['name']) {
                                    echo 'open';
                                } ?>">
                        <a href="<?php echo $row['link']; ?>"><?php echo ucfirst($row['name']); ?></a>
                    </li>

                <?php } ?>
            </ul>
        </div>


        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">

                <?php

                include('connect.php');
                date_default_timezone_set("Asia/Colombo");
                $date =  date("Y-m-d");

                $user_id = $_SESSION['SESS_MEMBER_ID'];
                $result1 = select("user", "upic", "id = '{$user_id}' ");
                for ($i = 0; $row1 = $result1->fetch(); $i++) {
                    $upic = $row1['upic'];
                }

                ?>
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" onclick="open_profile(1)">
                        <img src="<?php echo $upic; ?>" class="user-image" alt="User Image">
                        <span class="hidden-xs user-data-header"><?php echo $_SESSION['SESS_FIRST_NAME']; ?></span>
                        <span class="hidden-xs profile-data-header d-none"><i class="fa fa-times mx-2"></i></span>
                    </a>
                    <ul class="dropdown-menu user">
                        <!-- User image -->
                        <li class="user-header">
                            <div>
                                <a href="#" class="btn btn-default btn-xs user-data" onclick="open_profile(2)">Change Password</a>
                                <span class="badge"><i class="glyphicon glyphicon-user mx-2"></i><?php echo $_SESSION['SESS_LAST_NAME']; ?></span>
                                <span class="badge profile-data d-none" onclick="open_profile(3)" style="cursor: pointer;"><i class="fa fa-times mx-2"></i></span>
                            </div>
                            <img src="<?php echo $upic; ?>" class="img-circle user-data" alt="User Image">
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body user-data">
                            <p> <?php echo $_SESSION['SESS_FIRST_NAME']; ?></p>
                            <small>Member since Nov. 2023</small>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer user-data">
                            <div class="pull-right" style="width: 110px;">
                                <a href=" ../../../index.php" class="btn btn-default btn-flat">Sign out</a>
                            </div>
                        </li>

                        <!-- Menu Body -->
                        <li class="user-body profile-data d-none">
                            <form action="user_update.php" method="POST">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="password" name="old" class="form-control" placeholder="New Password">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="password" name="new" class="form-control" placeholder="Verify Password">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-default btn-flat" value="Save">
                                            <input type="hidden" name="id" value="<?php echo $userid; ?>">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
                
                <!-- Control Sidebar maintains -->
                <?php if ($user_level == 1) { ?>
                    <li>
                        <a href="sys_sidebar.php"><i class="fa-solid fa-sliders"></i></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </nav>
</header>