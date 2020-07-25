<?php
include '../firewall.php';
?>
<?php 
	$idEquipment = $_GET['idEquipment'];
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
	$eqId = $_GET['eqId'];
	$eqStr=$_GET['eqStr'];
	$current=$_GET['current'];
	$priceTest=$_GET['priceTest'];
	$offTest=$_GET['offTest'];
	$uandd=$_GET['uandd'];
	$ladnr=$_GET['ladnr'];
			$nadb_max=$_GET['nadb_max'];
			$total_min=$_GET['total_min'];
				$nadb_min=$_GET['nadb_min'];	
				$total_max=$_GET['total_max'];
    include_once '../db.php';
$query = "UPDATE equipment SET 
			EQ_NAME = '{$eqName}',
			MAX_SIZE = '{$eqMaxSize}',
			MIN_SIZE = '{$eqMinSize}',
			FORMAT = '{$eqFormat}',
			EQ_UNIT = '{$eqUnit}',
			EQ_KOL_OPER = '{$eqKolOper}',
			MAKEREADY_TIME = '{$eqMakeReadyTime}',
			EQ_AMMOR = '{$eqAmmor}',
			SROK_AMMOR = '{$eqSrokAmmor}',
			EQ_PRICE = '{$eqPriceOper}',
			EQ_SQ = '{$eqSq}',
			PRICE_ARENDA = '{$eqPriceArenda}',
			mater = '{$current}',
			oper = '{$eqStr}',
			l_use = '{$priceTest}',
			l_offset = '{$offTest}',
			uandd  = '{$uandd}',
			ladnr = '{$ladnr}'	,
nadb_max = '{$nadb_max}'	,
total_min = '{$total_min}'	,
nadb_min = '{$nadb_min}'	,
total_max = '{$total_max}'				
		WHERE id='{$idEquipment}';";
	mysql_query($query) or die($query);
	mysql_close($connection);
	
	
?>
<script >
	window.close();
	window.opener.location.reload();
</script>
