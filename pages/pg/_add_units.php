<?php
include '../firewall.php';

$name = $_GET['name'];
include '../db.php';

$query = "INSERT INTO units (name) VALUES ('{$name}');";

mysql_query($query) or die($query);
mysql_close($connection);

?>
<script >
	location.href = '../units.php';
</script>