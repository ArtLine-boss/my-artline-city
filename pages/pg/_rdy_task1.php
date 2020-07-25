<?

$id = $_GET['id'];
$total_rd = $_GET['total_rd'];
include_once '../db.php';
	$total = str_replace(",", ".", $total_rd );

		$query = "UPDATE plan_job SET  status = '2' ,FINAL_TOTAL = '".$total."' WHERE  id=".$id  ;	


mysql_query($query) or die($query);




mysql_close($connection);

?>