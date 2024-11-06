<?php

function select($table, $columns = "*", $where = "", $path = "")
{
    include($path . 'connect.php');

    $sql = "SELECT $columns FROM $table";

    if (!empty($where)) {
        $sql .= " WHERE " . $where;
    }

    try {
        $result = $db->prepare($sql);
        $result->execute();
        return $result;
    } catch (PDOException $e) {
        echo "Selection failed: " . $e->getMessage();
        return false;
    }
}


function select_item($table, $columns, $where = "", $path = "")
{
    include($path . 'connect.php');

    $sql = "SELECT $columns FROM $table";

    if (!empty($where)) {
        $sql .= " WHERE " . $where;
    }

    try {
        $result = $db->prepare($sql);
        $result->execute();
        $count=0;
        
        for ($i = 0; $row = $result->fetch(); $i++) { $count+=1; $retun=$row[$columns]; }
        if($count > 0){
        return $retun;
        }else{
            return false;
        }

    } catch (PDOException $e) {
        echo "Selection failed: " . $e->getMessage();
        return false;
    }
}

function select_query($sql, $path = "")
{
    include($path . 'connect.php');

    try {
        $result = $db->prepare($sql);
        $result->execute();
        return $result;
    } catch (PDOException $e) {
        echo "Selection failed: " . $e->getMessage();
        return false;
    }
}