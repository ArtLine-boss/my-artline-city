<?

$id = $_GET['id'];
include_once '../db.php';


		$query = "UPDATE order_product SET  status = '2' WHERE FIND_IN_SET(id , '".$id."') " ;	


mysql_query($query) or die($query);




mysql_close($connection);

?>