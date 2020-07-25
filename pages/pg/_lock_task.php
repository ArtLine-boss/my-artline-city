<?
include '../firewall.php';
$id = $_GET['id'];
$user = $_GET['user'];
$login = $_SESSION['login'];
include_once '../db.php';


$query="select status,id from order_product where FIND_IN_SET( id , '".$id."') ";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) 
	{ 
    IF ($login != ""){
		 $query1 = "INSERT INTO lock_task  (id_prod, users,flags,oper) 
				VALUES(	'{$row[1]}','{$login}','1','{$row[0]}');";
		mysql_query($query1) or die($query1);
	 }
		
	}


mysql_close($connection);

?>