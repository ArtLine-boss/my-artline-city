<?php
$off_name = $_GET['off_name'];
$off_format = $_GET['off_format'];
$off_color = $_GET['off_color'];
$off_total = $_GET['off_total'];
$off_price_total = $_GET['off_price_total'];
$off_nadbavka = $_GET['off_nadbavka'];
$off_date_rdy = $_GET['off_date_rdy'];
$off_date_ = date("Y-m-d");

include '../db.php';
$query = "INSERT offcet (NAME, FORMAT, COLOR, TOTAL, PRICE_TOTAL, NADBAVKA, DATE_RDY, DATE_) 
VALUES ('{$off_name}', '{$off_format}', '{$off_color}', '{$off_total}', '{$off_price_total}', '{$off_nadbavka}', '{$off_date_rdy}', '{$off_date_}');";
mysql_query($query) or die($query);
mysql_close($connection);


?>
<script >
	location.href = '../offset.php';
</script>