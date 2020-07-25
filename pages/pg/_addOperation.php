<?php
include '../firewall.php';
?>
<?php 
	$operName = $_POST['operName'];
	$operUnits = $_POST['operUnits'];
	$operUnitMin = $_POST['operUnitMin'];
	$operTime = $_POST['operTime'];
	$operTimeP = $_POST['operTimeP'];
	$operPrice = $_POST['operPrice'];
	$operPar = $_POST['operPar'];
	$operTimeP = str_replace(',', '.', $operTimeP);
	$operPrice = str_replace(',', '.', $operPrice);
	$opercom = $_POST['opercom'];
    include_once '../db.php';
	$query = "INSERT INTO operations (OPERATION_NAME,PAR, UNITS,UNIT_MIN, MAKEREADY_TIME,MAKEREADY_PRICE,OPERATION_PRICE,comments) 
				VALUES(	'{$operName}',	'{$operPar}','{$operUnits}','{$operUnitMin}','{$operTime}','{$operTimeP}','{$operPrice}','{$opercom}');";
	mysql_query($query) or die($query);
		$id = mysql_insert_id();

	
$query = "SELECT * FROM (select id , oper from equipment where EQ_NAME ='".$opercom."') e WHERE   NOT FIND_IN_SET(".$id.",e.oper)" ;
   $result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) { 
	$ID_UP = $row[0];		
   $LIST_OPER = $row[1].','.$id;
	$query = "UPDATE equipment SET oper = '{$LIST_OPER}' WHERE id='{$ID_UP}';";
	mysql_query($query) or die($query);
	
	}
	mysql_close($connection);
	
?>
<script >
	window.close();
	window.opener.location.reload();
</script>

