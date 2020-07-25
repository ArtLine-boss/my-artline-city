<?php

$dizz_sum = $_POST['dizz_sum'];
$press_sum = $_POST['press_sum'];
$size = $_POST['psize'];
$price = $_POST['price1'];
$sum = $_POST['price_1'];
$orderProd = $_POST['orderProd'];
$orderAcct = $_POST['orderAcct'];
$kol = $_POST['kol1'];
$Template = $_POST['Template'];
$TempPR = $_POST['Template1'];
$p_names = $_POST['p_names'];
$view_diz = $_POST['view_diz'];
$view_press = $_POST['view_press'];

$query = "INSERT INTO order_product (ORDER_ID, PRODUCT_ID, TOTAL, PRICE, SUMM,TEMPLATE,TEMP_PR,SIZE,DIZ,SHABLON_CL,p_names,PR,sum_press,press_diz,view_press,view_diz) 
				VALUES(	{$orderAcct},{$orderProd},'{$kol}','{$price}','{$sum}','{$Template}','{$TempPR}','{$size}','{$dizz_sum}','{$link1}','{$p_names}','1', '{$press_sum}','{$link2}','{$view_press}','{$view_diz}');";
include_once "../db.php";

mysql_query($query) or die(query);

echo mysql_insert_id();
mysql_close($connection);


?>