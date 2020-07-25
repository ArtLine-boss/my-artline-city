<?php
include '../firewall.php';
?>
<?php 
$id = $_GET['id'];
$login = $_GET['login'];
$date_pos   =  date("Y-m-d H:i:s");

include "../db.php";

$query= " UPDATE order_per SET status ='2', user_='".$login."', date_beg='".$date_pos."' WHERE ID=".$id;
mysql_query($query) or die("Query failed");


mysql_close($connection);

?>
<script >
	location.href = '../index.php';
</script>
