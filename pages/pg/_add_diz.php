<?php
include '../firewall.php';
?>
<?php
$diz_time = $_GET['diz_time'];
$diz_name = $_GET['diz_name'];
$diz_def = $_GET['diz_def'];
include '../db.php';

$query = "INSERT INTO DIZ_OPER (NAME, TIME_ , DEFAULT_) VALUES ('{$diz_name}', '{$diz_time}',{$diz_def});";
print $query;
mysql_query($query) or die("Query failed");
mysql_close($connection);

?>
<script >
	location.href = '../design.php';
</script>