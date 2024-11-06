<?php
session_start();
include('../config.php');
include('../connect.php');

$id=$_POST['id'];
$id2=$_POST['id2'];
$name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
$category=$_POST['category'];
$qty= floatval ($_POST['qty']);
$unit_price= floatval ($_POST['unit_price']);
$code=$_POST['code'];
$re_order=$_POST['re_order'];

if($_POST['unit']=='non'){
  $unit='';
  $unit_id=0;
}else{
  $unit_id=$_POST['unit'];
  $unit=select_item('unit','name','id='.$unit_id,'../'); 
}

echo  $unit_id;

if($id2 == '0'){
    $result = update('materials', 
    [
    'name' => $name ,
     'category' => $category,
     'unit_price' => $unit_price,
     'code' => $code ,
     're_order' => $re_order, 
     'available_qty' => $qty,
     'unit_id'=> $unit_id,
     'unit' => $unit
    ], 'id='.$id, '../');
}
else{

$insertData = array(
    "data" => array(
        "name" => $name,
      // "open_stock" => $open_stock,
        "category" => select_item("material_category", "name", "id=".$category,'../'),
        "code" => $code,
        "unit_price" => $unit_price,
       "available_qty" => $qty,
        "unit_id" => $unit_id,
        "unit" => $unit,
        "re_order" => $re_order,
    ),
    "other" => array(
    ),
);
$result=insert("materials", $insertData,'../');
}

echo $result['status'];
header("location: ../material");

?>