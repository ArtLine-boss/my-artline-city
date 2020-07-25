<?

$id1 = $_GET['id1']; 
include_once '../db.php';

$query = "UPDATE order_product SET status='3' WHERE  FIND_IN_SET(ID , '".$id1."') ";
 mysql_query($query) or die($query);
mysql_close($connection);



?>