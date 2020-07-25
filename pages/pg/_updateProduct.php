<?php
include '../firewall.php';

	if(!empty($_GET["productId"]))
	{
		$productName=$_GET["productName"];
		$productSize=$_GET["productSize"];
		$productStr=$_GET["productStr"];
		$productId= $_GET["productId"];
		$selSh=$_GET["selSh"];
		$skoba = $_GET["skoba"];
		$selSh1 = $_GET["selSh1"];
		
		include_once'../db.php';		
		$query = "	UPDATE product SET 
				PRODUCT_NAME = '{$productName}',
				PRODUCT_TEMPLATE = '{$productStr}',
				PRODUCT_SIZE = '{$productSize}',
				PRODUCT_SH = '{$selSh}',
				PRODUCT_SKOBA = '{$skoba}',
				prod_sh = {$selSh1}
				WHERE ID ='{$productId}';";
		mysql_query($query) or die($query);
		
	}
?>

<script >
location.href = '../product.php';
</script>



