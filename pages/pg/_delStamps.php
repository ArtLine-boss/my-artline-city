<?php
include '../firewall.php';
?>
<?php
	$id = $_GET['id'];
	include '../db.php';
	$query = "DELETE FROM stamps WHERE  ID = ".$id;
	mysql_query($query) or die("Query failed");
?>
<script >
	window.close();
	window.location.href = '../stamps.php';
</script>
