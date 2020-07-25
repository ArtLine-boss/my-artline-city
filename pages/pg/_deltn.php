<?php
include '../firewall.php';
?>
<?php
	$id = $_GET['id'];
	include '../db.php';
	$query = "DELETE FROM tn_list_par WHERE  num_tm = ".$id;
	mysql_query($query) or die($query );
?>
<script >
	window.close();
	window.location.href = '../tn_list.php';
</script>
