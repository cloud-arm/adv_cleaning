<?php
$pos = $_SESSION['SESS_LAST_NAME'];
//include_once("perloader.php");

echo '<div class="wrapper">';

if ($pos == 'admin') {
    include_once("sidebar.php");
}
