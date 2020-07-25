<?php
include '../firewall.php';
?>
<?php
	$id_Equipment = $_GET['id_Equipment'];
	print  $id_Equipment;
	include '../db.php';
	$query = "DELETE FROM equipment WHERE  ID = ".$id_Equipment;
	mysql_query($query) or die("Query failed");
?>
<script >
	window.close();
	window.location.href = '../equipment.php'; 

	</script>
