<?php
include '../firewall.php';

$name = $_GET['name'];
include '../db.php';

$query = "INSERT INTO size_print (SIZE) VALUES ('{$name}');";

mysql_query($query) or die($query);
mysql_close($connection);

?>
<script >
	location.href = '../units.php';
</script>