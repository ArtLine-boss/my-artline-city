<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

include '../firewall.php';

$id = $_GET['id'];



$query = "select id,TOTAL, p_names , fast,DATE_FORMAT(dates_rdy, '%Y-%m-%dT%H:%i') dates_rdy,SUMM, size, cshivka, TEMP_PR, template, price, view_diz, view_press, flags,units, cl_file,print_diz,press_diz,sum_press,comment,code_stat from order_product where id = ". $id ;

$result = mysql_query($query) or die($query);

if ($row = mysql_fetch_row($result)){
	
$count = 0;
$count1 = 0;
$count2 = 0;

if ($row[15] != ''){
	$count = count(array_filter(glob($row[15].'/*'), 'is_file'));
}	
if ($row[16] != ''){
	$count1 = count(array_filter(glob($row[16].'/*'), 'is_file'));
}
if ($row[17] != ''){
	$count2 = count(array_filter(glob($row[17].'/*'), 'is_file'));
}



	
	$srr = $row[0]."$".$row[1]."$".$row[2]."$".$row[3]."$".$row[4]."$".$row[5]."$".$row[6]."$".$row[7]."$".$row[8]."$".$row[9]."$".$row[10]."$".$row[11]."$".$row[12]."$".$row[13]."$".$row[14]."$".$row[15]."$".$row[16]."$".$row[17]."$".$count."$".$count1."$".$count2."$".$row[18]."$".$row[19]."$".$row[20]."$";
}

echo $srr;

mysql_close($connection);



?>

 

	
