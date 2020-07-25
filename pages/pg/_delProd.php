<?php
include '../firewall.php';
?>
<?php
	$id_Prod = $_GET['id_Prod'];
	include '../db.php';
	$query = "DELETE FROM product WHERE  ID = ".$id_Prod;
	mysql_query($query) or die("Query failed");
?>
<script >
	window.close();
	window.location.href = '../product.php';
</script>
