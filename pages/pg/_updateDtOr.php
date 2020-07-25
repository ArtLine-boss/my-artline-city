<?php

$id = $_GET['id']; 
$dt = $_GET['dt'];
include_once '../db.php';
$query = "UPDATE orders SET DATE_OR='{$dt}' WHERE NUMBER=".$id;
mysql_query($query) or die($query);
mysql_close($connection);
?>
<script >
location.href = '../orders.php';
</script>
