<?
include '../firewall.php';
$id = $_POST['id'];
$login = $_SESSION['login'];
include_once '../db.php';



$query="select status from order_product where id =".$id ;
$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) 
	{ 
		$status = $row[0];
	}

$query = "UPDATE lock_task SET flags='0' WHERE  id_prod=".$id."  AND oper = ". $status  ;	
	
mysql_query($query) or die($query);
mysql_close($connection);

?>