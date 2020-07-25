<?php

include_once '../db.php';


$sizeMaterial = $_GET['size'];
$izmMaterial =$_GET['units'];
$idMaterial = $_GET['type'];
$nameMaterial = $_GET['name'];
$priceMaterial = $_GET['price'];
$eq = $_GET['eq'];
$tolMaterial = "-";



$query = "INSERT INTO material_attr (ID_M, M_NAME, M_PRICE, M_SIZE, M_UNIT,M_TOL)
				VALUES({$idMaterial},'{$nameMaterial}', '{$priceMaterial}','{$sizeMaterial}','{$izmMaterial}','{$tolMaterial}');";
mysql_query($query) or die($query);
	$last_id = mysql_insert_id();

$query = "select id, mater from equipment where FIND_IN_SET(id,'".$eq."')";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
	$id =$row[0];
	$mater =$row[1].",".$last_id ;
$query1 = "UPDATE equipment SET 
				mater = '{$mater}'
		WHERE id='{$id}';";
mysql_query($query1) or die($query1);
}
?>
