<?php
include '../firewall.php';
?>
<?php
	$id = $_GET['id'];
	print  $id_Equipment;
	include '../db.php';
	$query = "DELETE FROM clients WHERE  ID = ".$id;
	mysql_query($query) or die("Query failed");
?>
<script >
	window.close();
	window.location.href = '../clients.php'; 

	</script>
