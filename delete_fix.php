<?php
include 'connect.php'; // include your database connection

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // Fetch the specific record from `fix_materials`
        $stmt = $db->prepare("SELECT * FROM fix_materials WHERE id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $qty = $row['qty'];
            $material_id = $row['mat_id'];
            $job_no = $row['job_no'];
            $s_id = $row['sales_list_id'];
        } else {
            die('Fix material not found.');
        }

        // Fetch material details from `materials`
        $stmtMaterial = $db->prepare("SELECT * FROM materials WHERE id = :material_id");
        $stmtMaterial->bindParam(':material_id', $material_id);
        $stmtMaterial->execute();
        $materialRow = $stmtMaterial->fetch(PDO::FETCH_ASSOC);

        if ($materialRow) {
            $available_qty = (int)$materialRow['available_qty'];
            $material_name = $materialRow['name'];
        } else {
            die('Material not found.');
        }

        // Delete record from `fix_materials`
        $stmtDelete = $db->prepare("DELETE FROM fix_materials WHERE id = :id");
        $stmtDelete->bindParam(':id', $id);
        $stmtDelete->execute();

        // Update `materials` available quantity
        $stmtUpdate = $db->prepare("UPDATE materials SET available_qty = available_qty + :qty WHERE id = :material_id");
        $stmtUpdate->bindParam(':qty', $qty);
        $stmtUpdate->bindParam(':material_id', $material_id);
        $stmtUpdate->execute();

        // Insert transaction into `inventory` table
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $stmtInventory = $db->prepare("INSERT INTO inventory (material_id, name, job_no, type, balance, qty, date, time, sales_list_id) 
                                       VALUES (:material_id, :name, :job_no, :type, :balance, :qty, :date, :time, :sales_list_id)");
        $stmtInventory->bindParam(':material_id', $material_id);
        $stmtInventory->bindParam(':name', $material_name);
        $stmtInventory->bindParam(':job_no', $job_no);
        $stmtInventory->bindValue(':type', 'in'); // Incoming transaction
        $stmtInventory->bindValue(':balance', $available_qty + $qty);
        $stmtInventory->bindParam(':qty', $qty);
        $stmtInventory->bindParam(':date', $date);
        $stmtInventory->bindParam(':time', $time);
        $stmtInventory->bindParam(':sales_list_id', $s_id);
        $stmtInventory->execute();

        // Redirect back to the list page after deletion
       header("Location: job_view?id=".base64_encode($s_id));
        exit;
    } catch (PDOException $e) {
        // Handle any errors
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
?>
