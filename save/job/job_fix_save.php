<?php
session_start();
$fn = $_SESSION['SESS_FIRST_NAME'];
$r = $_SESSION['SESS_LAST_NAME'];
$emp_id = $_SESSION['USER_EMPLOYEE_ID'];
include('../../config.php');

// Get the ID from the query string and validate it
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
} else {
    die('Invalid ID');
}


// Check if form data is sent
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id2 = $_POST['id2']; 

    // If id2 is 0, mark the sale as complete and redirect
    if ($id2 == 0) {
        $result = update('sales_list', ['status' => 'complete','status_id'=> '5'], 'id=' . $id, '../../');

        $salesResult = select('sales_list', '*', 'id=' . $id, '../../');
        if ($salesResult) {
            $salesRow = $salesResult->fetch();
            if ($salesRow) {
                $job_no = $salesRow['job_no'];  // Job number
                $product_name = $salesRow['name'];  // Product name
            } else {
                die('Sales record not found.');
            }
        } 

        $insertData1 = array(
            "data" => array(
                "user_id" =>$emp_id,
                "user_name" => $fn,
                "job_no" => $job_no,
                "note" => "fix done by  $fn $r for $product_name",
                "type" => 'job',
                "source_id" => $id, 
                "action" => 0,
                "activity" => 'fix',
                "date" => date('Y-m-d'),
                "time" => date('H.i.s'),
        
            ),
            "other" => array(
            ),
        );
        $result=insert("user_activity", $insertData1,'../../');

        if ($result) {
            header("Location: ../../job_summery?id=" . base64_encode($id));
            exit; // Ensure no further code is executed after the redirect
        } else {
            die('Failed to update sales list status.');
        }
    } else {
        // Retrieve and validate form inputs
        if (isset($_POST['mat_id']) && isset($_POST['qty'])) {
            $m_id = $_POST['mat_id'];  // Material ID
            $qty = $_POST['qty'];  // Quantity

            // Fetch the material details
            $result = select('materials', '*', 'id=' . $m_id, '../../');
            if ($result) {
                $row = $result->fetch();
                if ($row) {
                    $available_qty = (int)$row['available_qty'];  // Available quantity
                    $material_name = $row['name'];  // Material name
                }
            } 

                        // Fetch sales list to get the job number
                        $salesResult = select('sales_list', '*', 'id=' . $id, '../../');
                        if ($salesResult) {
                            $salesRow = $salesResult->fetch();
                            if ($salesRow) {
                                $job_no = $salesRow['job_no'];  // Job number
                                $product_name = $salesRow['name'];  // Product name
                            }
                        } 

            $unit_id= $_POST['unit'];
            if($unit_id==0){
                $unit=select_item('materials','unit','id='.$pro, '../../');
            }else{
                $unit= select_item('unit_record','unit','id='.$unit_id, '../../');
            }

            if($unit_id==0){
                $unit_qty=1;
            }else{
                $unit_qty = select_item('unit_record','unit_value','id='.$unit_id, '../../');
            }
    
                if ($unit_qty > 0) {
                    $qty = $qty * $unit_qty;
                }
    
               // echo 'unit_qty'.$unit_qty;


        // Insert into 'fix_materials' table
        $insertData = [
            "mat_name" => $material_name,
            "qty" => $qty,  // Quantity used
            "sales_list_id" => $id,
            "mat_id" => $m_id,
            "type" => 'fix',
            "job_no" => $job_no,
            "unit" => $unit,
            "unit_id" => $unit_id,
        ];
        $result = insert("fix_materials", ["data" => $insertData], '../../');
        if (!$result) {
            die('Failed to insert into fix_materials.');
        }




        
        // Check if enough quantity is available
        if ($available_qty < $qty) {
            echo "<script>
                alert('Insufficient quantity available.');
                window.location.href = '../../material.php';
            </script>";
            exit; // Ensure no further code executes
        }
        
        

            // Update the materials table to reduce available quantity
            $updateMaterials = update('materials', ['available_qty' => $available_qty - $qty], 'id=' . $m_id, '../../');
            if (!$updateMaterials) {
                die('Failed to update materials.');
            }


            

            // Insert into 'inventory' table for outgoing materials
            $date = date('Y-m-d');  // Get current date
            $time = date('H:i:s');  // Get current time

            $inventoryData = [
                'material_id' => $m_id,
                'name' => $material_name,
                'job_no' => $job_no,
                'type' => 'out',  // Outgoing transaction
                'balance' => $available_qty - $qty,  // Balance after deduction
                'qty' => $qty,  // Quantity used
                'date' => $date,
                'time' => $time,
                'sales_list_id' => $id  // Link to the sales list
            ];
            $inventoryResult = insert("inventory", ["data" => $inventoryData], '../../');
            if (!$inventoryResult) {
                die('Failed to insert into inventory.');
            }



            // Success, redirect to the job summary page
           header("Location: ../../job_view?id=" . base64_encode($id));
            exit; // Ensure no further code is executed after the redirect
        } else {
            echo "Form data missing.";
        }
    }
} else {
    echo "Invalid request.";
}
?>
