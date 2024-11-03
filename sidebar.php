<?php
include_once("sidebar_header.php");
?>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">

            <?php if ($dep == 'sidebar') {
            } else { ?>

                <?php
                $result = select_query("SELECT *,sys_sidebar.id AS sn FROM sys_sidebar JOIN sys_permission_arm ON sys_sidebar.id=sys_permission_arm.menu_id WHERE sys_permission_arm.user_level = '{$user_level}' AND `sys_sidebar`.`{$dep}` = 1 AND sys_permission_arm.type = 'user_level' AND sys_permission_arm.section = 'sidebar' AND sys_sidebar.type='main' AND sys_sidebar.action = 1 AND sys_permission_arm.action = 1 ORDER BY sys_sidebar.order_id ");
                for ($i = 0; $row = $result->fetch(); $i++) {
                    $linkR = explode('.', $row['link']);
                    $link = $linkR[0];
                    if ($row['sub']) {

                        $con = '';
                        $con0 = '';
                        $dis = 'none';
                        if ($main_menu_action == $row['sn']) {
                            $con = 'active';
                            $con0 = 'menu-open';
                            $dis = 'block';
                        }
                ?>

                        <li class="treeview  <?php echo $con; ?>">
                            <a href="#"><i class="<?php echo $row['icon']; ?>"></i><span><?php echo ucfirst($row['name']); ?></span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu  <?php echo $con0; ?>" style="display:  <?php echo $dis; ?>;">

                                <?php
                                $row_id = $row['sn'];
                                $result1 = select_query("SELECT *,sys_sidebar.id AS sn FROM sys_sidebar JOIN sys_permission_arm ON sys_sidebar.id=sys_permission_arm.menu_id WHERE `sys_sidebar`.`{$dep}` = 1 AND  sys_sidebar.type='sub1' AND sys_sidebar.main_id = '{$row_id}' AND sys_permission_arm.user_level = '{$user_level}' AND sys_permission_arm.type = 'user_level' AND sys_permission_arm.section = 'sidebar' AND  sys_sidebar.action = 1 AND sys_permission_arm.action = 1 ORDER BY sys_sidebar.order_id ");
                                for ($i = 0; $row1 = $result1->fetch(); $i++) {
                                    $linkR = explode('.', $row1['link']);
                                    $link = $linkR[0];
                                    if ($row1['sub']) {

                                        $con = '';
                                        if ($sub1_menu_action == $row1['sn']) {
                                            $con = 'active';
                                        } ?>

                                        <li class="treeview <?php echo $con; ?>">
                                            <a href="#">
                                                <i class="<?php echo $row1['icon']; ?>"></i>
                                                <span><?php echo ucfirst($row1['name']); ?></span>
                                                <span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span>
                                            </a>

                                            <ul class="treeview-menu">
                                                <?php
                                                $row_id = $row1['sn'];
                                                $result2 = select_query("SELECT *,sys_sidebar.id AS sn FROM sys_sidebar JOIN sys_permission_arm ON sys_sidebar.id=sys_permission_arm.menu_id WHERE `sys_sidebar`.`{$dep}` = 1 AND  sys_sidebar.type='sub2' AND sys_sidebar.main_id = '{$row_id}' AND sys_permission_arm.user_level = '{$user_level}' AND sys_permission_arm.type = 'user_level' AND sys_permission_arm.section = 'sidebar' AND  sys_sidebar.action = 1 AND sys_permission_arm.action = 1 ORDER BY sys_sidebar.order_id ");
                                                for ($i = 0; $row2 = $result2->fetch(); $i++) {
                                                    $linkR = explode('.', $row2['link']);
                                                    $link = $linkR[0];
                                                ?>
                                                    <li class="<?php if ($menu_action == $row2['sn']) {
                                                                    echo 'active';
                                                                } ?>"><a href="<?php echo $row2['link']; ?>"><i class="<?php echo $row2['icon']; ?>"></i> <?php echo ucfirst($row2['name']); ?> </a></li>
                                                <?php }  ?>
                                            </ul>

                                        </li>

                                    <?php } else { ?>

                                        <li class="<?php if ($menu_action == $row1['sn']) {
                                                        echo 'active';
                                                    } ?>">
                                            <a href="<?php echo $row1['link']; ?>"><i class="<?php echo $row1['icon']; ?>"></i> <?php echo ucfirst($row1['name']); ?> </a>
                                        </li>

                                <?php }
                                } ?>
                            </ul>
                        </li>

                        <?php } else {
                        if ($row['head']) {
                        ?>
                        

                            <li class="header"><?php echo strtoupper($row['name']); ?></li>

                        <?php } else { ?>
                            <li class="<?php if ($menu_action == $row['sn']) {
                                            echo 'active';
                                        } ?>">
                                <a href="<?php echo $row['link']; ?>">
                                    <i class="<?php echo $row['icon']; ?>"></i> <span><?php echo ucfirst($row['name']); ?></span>
                                </a>
                            </li>
                <?php }
                    }
                } ?>

            <?php } ?>

        </ul>
    </section>
</aside>