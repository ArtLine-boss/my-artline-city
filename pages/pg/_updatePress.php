<?php

$name = $_GET['name']; 
$times = $_GET['times'];
$use = $_GET['use'];
$id = $_GET['id'];

include_once '../db.php';

$query = "UPDATE PR_OPER SET NAME='{$name}', TIME_='{$times}', DEFAULT_={$use} WHERE  ID=".$id;
mysql_query($query) or die("Query failed");
mysql_close($connection);
?>
<script >
location.href = '../design.php';
</script>
