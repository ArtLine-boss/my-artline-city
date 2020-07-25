<?php
include '../firewall.php';
$client = $_GET['client'];
$num = $_GET['num'];
$sum = $_GET['sum'];
$sum = str_replace(",", ".", $sum );
$sum  = round($sum ,2);
$date_ = $_GET['date']; 
$view_opl = $_GET['view_opl']; 
$list_comm = $_GET['list_comm']; 
$flags = $_GET['flags']; 
include '../db.php';

if ($flags == "1"){
	$query = "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, ALL_SUM, DATE_,view_opl,Comments) VALUES ('{$client}', '{$num}', '{$sum}', '{$date_}',{$view_opl},'{$list_comm}');";
} else {
	$query = "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, OST_SUM, DATE_,view_opl,Comments) VALUES ('{$client}', '{$num}', '{$sum}', '{$date_}',{$view_opl},'{$list_comm}');";
}

mysql_query($query) or die($query);
mysql_close($connection);

?>
<script >
	location.href = '../oplati.php';
</script>