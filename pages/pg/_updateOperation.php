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
	$operId = $_POST['operId'];
	$operPar = $_POST['operPar']; 
		$opercom = $_POST['opercom']; 

    include_once '../db.php';
$query = "UPDATE operations SET 
	OPERATION_NAME = '{$operName}', 
	PAR = '{$operPar}', 
	UNITS = '{$operUnits}',
	UNIT_MIN = '{$operUnitMin}', 
	MAKEREADY_TIME = '{$operTime}',
	MAKEREADY_PRICE = '{$operTimeP}',
	OPERATION_PRICE = '{$operPrice}',
	comments = '{$opercom}'
	WHERE id='{$operId}';";
mysql_query($query) or die("Query failed");



$query = "SELECT * FROM (select id , oper from equipment where EQ_NAME ='".$opercom."') e WHERE   NOT FIND_IN_SET(".$operId.",e.oper)" ;

   $result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) { 
	$ID_UP = $row[0];		
	if ($row[1] == ''){
		$LIST_OPER = $operId;
	} ELSE {
   $LIST_OPER = $row[1].','.$operId;
	}
	$query = "UPDATE equipment SET oper = '{$LIST_OPER}' WHERE id='{$ID_UP}';";
	mysql_query($query) or die($query);
	
	}

mysql_close($connection);

?>
<script >
	window.close();
	window.opener.location.reload();
</script>




