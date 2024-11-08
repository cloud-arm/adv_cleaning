<?php

function query($sql, $path = "")
{
    include($path . 'connect.php');

    try {
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt;
    } catch (PDOException $e) {
        return "Update failed: " . $e->getMessage();
    }
}
