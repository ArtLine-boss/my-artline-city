<?php
include '../firewall.php';
PRINT_R($_GET);

	$eqName = $_GET['eqName'];
	$eqMaxSize = $_GET['eqMaxSize'];
	$eqMinSize = $_GET['eqMinSize'];
	$eqFormat = $_GET['eqFormat'];
	$eqUnit = $_GET['eqUnit'];
	$eqKolOper = $_GET['eqKolOper'];
	$eqMakeReadyTime = $_GET['eqMakeReadyTime'];
	$eqAmmor = $_GET['eqAmmor'];
	$eqSrokAmmor = $_GET['eqSrokAmmor'];
	$eqPriceOper = $_GET['eqPriceOper'];
	$eqSq = $_GET['eqSq'];
	$eqPriceArenda = $_GET['eqPriceArenda'];
	$eqStr=$_GET['eqStr'];
	$current=$_GET['current'];
	$offTest=$_GET['offTest'];
	$ladnr=$_GET['ladnr'];
	$uandd=$_GET['uandd'];
		$nadb_max=$_GET['nadb_max'];
			$total_min=$_GET['total_min'];
				$nadb_min=$_GET['nadb_min'];	
				$total_max=$_GET['total_max'];
   include_once '../db.php';
	
	
	
	
$query = "INSERT INTO equipment (EQ_NAME,MAX_SIZE,MIN_SIZE,FORMAT,EQ_UNIT,EQ_KOL_OPER,MAKEREADY_TIME,EQ_AMMOR,SROK_AMMOR,EQ_PRICE,EQ_SQ,PRICE_ARENDA,mater,oper,l_offset,ladnr,uandd,nadb_max,total_min,nadb_min,total_max) 
				VALUES('{$eqName}','{$eqMaxSize}','{$eqMinSize}','{$eqFormat}','{$eqUnit}','{$eqKolOper}','{$eqMakeReadyTime}','{$eqAmmor}','{$eqSrokAmmor}','{$eqPriceOper}','{$eqSq}','{$eqPriceArenda}','{$current}','{$eqStr}','{$offTest}','{$ladnr}','{$uandd}','{$nadb_max}','{$total_min}','{$nadb_min}','{$total_max}');";
mysql_query($query) or die($query);
	
	mysql_close($connection);
?>
<script >
	window.close();
	window.opener.location.reload();

	</script>
