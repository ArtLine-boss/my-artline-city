<?php
	$unp = $_GET['unp'];
	include '../db.php';
	$flags = false;
	$query = "select * from clients where unp = ".$unp;
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) { 
		$flags = true;
	}
	
	IF ($flags) {
		echo "1";
	}
	ELSE {
		echo "0";
	}
	
?>
