<?php
include '../firewall.php';
?>
<?php
	if(!empty($_GET["productName"]))
	{
		$kat1=$_GET["kat1"];
		$kat2=$_GET["kat2"];
		$productName=$_GET["productName"];
		$productSize=$_GET["productSize"];
		$productStr=$_GET["productStr"];
		$selSh=$_GET["selSh"];
		$skoba = $_GET["skoba"];
		$productStr = str_replace("?","&",$productStr);
			$selSh1 = $_GET["selSh1"];
		
		
		include_once'../db.php';
		$query = "INSERT INTO product (PRODUCT_NAME, PRODUCT_TEMPLATE, PRODUCT_SIZE,PRODUCT_SH,PRODUCT_SKOBA,kat_1,kat_2,prod_sh) VALUES('{$productName}','{$productStr}','{$productSize}','{$selSh}','{$skoba}','{$kat1}','{$kat2}',{$selSh1});";
		mysql_query($query) or die("Query failed");
		header("location:../product.php");
	}
	else
	{
		header("location:_addProduct.php");
	}
?>

<script >
	window.close();
	window.opener.location.href = '../product.php';
</script>
