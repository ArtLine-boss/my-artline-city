<?php
include '../firewall.php';
?>
<?
	$name = $_POST['name'];
	$num = $_POST['num'];
	$date = $_POST['date'];
	$users = $_POST['users'];
	include_once '../db.php';
	$query = "INSERT stock (NUM, DATE_, PROVIDER, USERS) VALUES ('{$num}', '{$date}', '{$name}', '{$users}');";
	mysql_query($query) or die("Query failed");
	$insert_ID = mysql_insert_id();
	mysql_close($connection);

?>
<script >
	var id  = '<?php echo $insert_ID;?>';
	window.close();
    window.opener.location.href = '_addviewstock.php?id='+id;
</script>