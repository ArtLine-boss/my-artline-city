<?
$id = $_GET['id'];
$qw = "select count(*) from equipment e,
(select o.ID from operations o, (select distinct operation_name from operations where  FIND_IN_SET(id,(select oper from equipment where id = ".$id." ))) o1
where o.OPERATION_NAME = o1.operation_name and NOT FIND_IN_SET(o.id,(select oper from equipment where id = ".$id." ))) o1 where e.id <> ".$id." and FIND_IN_SET(o1.ID,e.oper) and l_use = '1'";

	include "../db.php";
	$result = mysql_query($qw) or die($qw);
	WHILE ($row = mysql_fetch_row($result)) { 
	echo $row[0];
	}
	mysql_close($connection);


?>