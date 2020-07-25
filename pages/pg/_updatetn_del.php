<?php 
$id = $_POST['id'];
$id_nak = $_POST['id_nak'];
			include "../db.php";
$query = "UPDATE tn_list_par SET del = 1  WHERE id in (".$id_nak .") AND  order_id = ".$id  ;
mysql_query($query) or die($query);
$query = "select  id from tn_list_par  where id in (".$id_nak .") and order_id = ".$id." and exp_1c = 1" ;
$result = mysql_query($query) or die($query);
while($row = mysql_fetch_row($result)){
$query = "UPDATE tn_list_par SET exp_1c = 2  WHERE id = ".$row[0];
mysql_query($query) or die($query);
}


$query = "select  tn1.id from tn_list_par tn, tn_list tn1 where tn.del = 1 AND tn.order_id = ".$id." AND tn1.num_tn = tn.num_tm";
$result = mysql_query($query) or die($query);
while($row = mysql_fetch_row($result)){
$query = "DELETE FROM tn_list WHERE id = ".$row[0];
mysql_query($query) or die($query);
}

mysql_close($connection);
?>
