<?php
include '../firewall.php';
?>
<?php 



$id = $_POST['id'];
$val = $_POST['val'];
include_once'../db.php';		
$date = date("Y-m-d");


$query = "	UPDATE settings SET VAL= '{$val}' WHERE ID ='{$id}';";

mysql_query($query) or die("Query failed");

$query = "INSERT INTO settings_attr (SET_ID, DATE_VAL,VAL) VALUES ({$id},'{$date}','{$val}');";
print $query;
mysql_query($query) or die("Query failed1");
mysql_close($connection);		
	
?>

<script >
	window.close();
	window.opener.location.reload();
</script>
