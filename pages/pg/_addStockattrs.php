<?php
include '../firewall.php';
?>
<?
	$name = $_POST['name'];
	$kol = $_POST['kol'];
	$price = $_POST['price'];
	$id_stock = $_POST['id_stock'];
	$size = $_POST['size'];
	include_once '../db.php';
	$query = "INSERT INTO stock_attr (STOCK_ID, NAME, TOTAL, PRICE,SIZE) VALUES ({$id_stock}, {$name}, {$kol}, '{$price}', '{$size}');";
	mysql_query($query) or die("Query failed");
	$insert_ID = mysql_insert_id();
	mysql_close($connection);

?>
<script >
	var id  = '<?php echo $id_stock;?>';
	window.close();
    window.opener.location.href = '_addviewstock.php?id='+id;
</script>