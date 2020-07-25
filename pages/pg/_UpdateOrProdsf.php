<?php
include '../firewall.php';


$size = $_POST['psize'];
$kol = $_POST['kol1'];
$price = $_POST['price1'];
$sum = $_POST['price_1'];
$Template = $_POST['Template'];
$TempPR = $_POST['Template1'];
$id_orderProd = $_POST['id_orderProd'];
$p_names = $_POST['p_names'];
$selSh = $_POST['selSh'];

include "../db.php";	
$query = "UPDATE order_product 
			SET TOTAL='{$kol}', 
			PRICE='{$price}', 
			SUMM='{$sum}', 
			TEMPLATE='{$Template}',
			TEMP_PR	= '{$TempPR}',
			SIZE	= '{$size}',
			p_names = '{$p_names}',
			cshivka = '{$selSh}'
			WHERE ID={$id_orderProd}";
	
mysql_query($query) or die($query);
mysql_close($connection);
?>
<script >
    window.close();
	window.opener.location.reload();
</script>




