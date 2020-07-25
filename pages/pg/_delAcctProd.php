<?php
include '../firewall.php';
?>
<?php
	$id_orderProd = $_GET['id_orderProd'];
	$id_Acct = $_GET['id_Acct'];
	include_once '../db.php';
	$query = "DELETE FROM order_product WHERE  ID=".$id_orderProd;
	mysql_query($query) or die("Query failed");
?>
<script >
	var id = <?php echo $id_Acct?>;
	window.close();
	window.location.href = '_addAcct.php?id='+id;
</script>
