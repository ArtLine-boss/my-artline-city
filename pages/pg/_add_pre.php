<?php
include '../firewall.php';
?>
<?php
$pre_time = $_GET['pre_time'];
$pre_name = $_GET['pre_name'];
$pre_def = $_GET['pre_def'];
include '../db.php';

$query = "INSERT INTO PR_OPER (NAME, TIME_, DEFAULT_) VALUES ('{$pre_name}', '{$pre_time}', {$pre_def});";
mysql_query($query) or die("Query failed");
mysql_close($connection);

?>
<script >
	location.href = '../design.php';
</script>