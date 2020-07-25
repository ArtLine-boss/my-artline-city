<?php

	$id = $_GET['id'];
	include '../db.php';
	$query = "DELETE FROM operations WHERE  ID = ".$id;
	mysql_query($query) or die("Query failed");
?>
<script >
	window.close();
	window.location.href = '../operation.php'; 

	</script>
