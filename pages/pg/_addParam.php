<?php
include '../firewall.php';
?>
<?php 

$name = $_POST['name'];
$val = $_POST['val'];
$date = date("Y-m-d");

include_once '../db.php';

$query = "INSERT INTO settings (NAME, VAL) VALUES ('{$name}', '{$val}');";
mysql_query($query) or die("Query failed");




	$insert_ID = mysql_insert_id();
$query = "INSERT INTO settings_attr (SET_ID, DATE_VAL,VAL) VALUES ({$insert_ID},'{$date}','{$val}');";
print $query;
mysql_query($query) or die("Query failed1");
mysql_close($connection);

?>
<script >
	window.close();
	window.opener.location.reload();
</script>

