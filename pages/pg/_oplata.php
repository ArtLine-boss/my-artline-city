<?php
include '../firewall.php';
?>
<?php
include "../db.php";
$id = $_GET['id'] ;
$sum = $_GET['sum'];
$client = $_GET['client'];
$sumOpl = $_GET['sumOpl'];
$flag = $_GET['flag'];
$pred = $_GET['pred'];
$view_opl = $_GET['view_opl'];
IF($view_opl == ''){
	$view_opl = 0;
}
$date_ = date("Y-m-d");
$query= "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, OST_SUM, ALL_SUM,DATE_,view_opl) VALUES ({$client}, {$id}, {$sum} , {$sumOpl},'{$date_}',{$view_opl});";

if (flag != 0 && flag == 2) {
	$query= "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, OST_SUM, ALL_SUM,DATE_,view_opl) VALUES ({$client},{$id}, 0 , {$sumOpl} ,'{$date_}',{$view_opl});";
}

mysql_query($query) or die($query);

if (($sumOpl == 0)||(flag == 0 || $sumOpl > pred)){
$query= "UPDATE orders SET STATUS_ID = 1 WHERE NUMBER = ".$id;
mysql_query($query) or die($query);
}


mysql_close($connection);



?>
<script>

	var sumOpl = "<? echo $sumOpl ?>";
	var flag = "<? echo $flag ?>";
	var id = "<? echo $id ?>";
	var pred = "<? echo $pred ?>";
	
	
if (flag == 2){
	location.href = '_tacks.php?id_order='+id+'&flag=1';
}
if ((flag == 1 && sumOpl > 0)||(flag == 0 && sumOpl >= pred)) {
	location.href = '_tacks.php?id_order='+id+'&flag=1';
}
if ((flag == 0 && sumOpl < pred)||(flag == 1 && sumOpl == 0)){
	location.href = '../orders.php';
}
   	

</script>