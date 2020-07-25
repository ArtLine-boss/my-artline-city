<?php

$name = $_GET['name']; 

$id = $_GET['id'];

include_once '../db.php';

$query = "UPDATE units SET name='{$name}' WHERE  ID=".$id;
mysql_query($query) or die($query);
mysql_close($connection);
?>
<script >
location.href = '../units.php';
</script>
