<?
	$dt1 = $_GET['dt1'];
	$dt2 = $_GET['dt2'];
	
	include "db.php";
	
	$query="SELECT * FROM 
	(select   	
	opl.ORDER_NUM,
	round(opl.ALL_SUM,2) ALL_SUM, 
	opl.DATE_, 
	(IF (opl.view_opl = '1', 'Касса', if (opl.view_opl = '2', 'Терминал', ''))) view_opl , 
	op.p_names , 
	op.total, 
	op.units , 
	round(op.PRICE / 1.2, 2) PRICE_no_nds, 
	round((round(op.PRICE / 1.2, 2) * 1.2 * total ),2) SUMM,
	opl.ID 
	from 
	(select o.ID , o.ORDER_NUM, o.ALL_SUM, o.DATE_, o.view_opl from oplati o where o.date_ >= '".$dt1."' and o.date_ <= '".$dt2."' AND (o.view_opl = '2' OR o.view_opl = '1')) opl
	LEFT JOIN order_product op  ON op.ORDER_ID = opl.ORDER_NUM ORDER BY op.ORDER_ID) t1
	
	WHERE t1.ALL_SUM =  t1.SUMM";
	$json = Array();
	$str = "";
	$str1 = "0";
	$str2 = "0";
	$old = "";
	$str_old = "\n";
	$str = "\n\nПодобрано\n";
	$json[] = array(
	'str' =>   $str,
	);	
	//echo "Подобрано<br><br><br>";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
		$str = $row[0].";".str_replace(".", ",", $row[1] ).";".$row[2].";".$row[3].";".$row[4].";".str_replace(".", ",", $row[5] ).";".$row[6].";".str_replace(".", ",", $row[7] ).";".str_replace(".", ",", $row[8] ).";\n";
		IF($old <> $row[0]){
			$json[] = array(
		   'str' =>   $str_old,
			);	
			$old = $row[0];	
		}
		$str1 .= $row[9].",";
		$json[] = array(
		'str' =>   $str,
		);	
	}
	
   $str1 = substr($str1, 0, -1);
	
	$str = "\n\nОплачен счет полностью \n";
	$json[] = array(
	'str' =>   $str,
	);	
	

//echo   "Оплачен счет полностью <br><br><br>";
if($str1 == ""){
$str1 = '0';
}	
$query="SELECT id FROM (select   	
opl.ORDER_NUM,
round(	opl.ALL_SUM,2) ALL_SUM , 
opl.DATE_, 
(IF (opl.view_opl = '1', 'Касса', if (opl.view_opl = '2', 'Терминал', ''))) view_opl , 
op.p_names , 
op.total, 
op.units , 
round(op.PRICE / 1.2, 2) PRICE_no_nds, 
SUM(round((round(op.PRICE / 1.2, 2) * 1.2 * total ),2)) SUMM,
opl.ID 
from 
(select o.ID , o.ORDER_NUM, o.ALL_SUM, o.DATE_, o.view_opl from oplati o where o.date_ >= '".$dt1."' and o.date_ <= '".$dt2."' AND (o.view_opl = '2' OR o.view_opl = '1') AND 
NOT 	o.ID IN (".$str1.")) opl
,  order_product op  WHERE op.ORDER_ID = opl.ORDER_NUM GROUP BY op.ORDER_ID)q WHERE q.ALL_SUM = q.SUMM";

$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {		
$str2 .= $row[0].",";
}
$str2 = substr($str2, 0, -1);
if($str2 == ""){
$str2 = '0';
}

$query="select   	
opl.ORDER_NUM,
round(	opl.ALL_SUM,2) ALL_SUM , 
opl.DATE_, 
(IF (opl.view_opl = '1', 'Касса', if (opl.view_opl = '2', 'Терминал', ''))) view_opl , 
op.p_names , 
op.total, 
op.units , 
round(op.PRICE / 1.2, 2) PRICE_no_nds, 
round((round(op.PRICE / 1.2, 2) * 1.2 * total ),2) SUMM,
opl.ID 
from 
(select o.ID , o.ORDER_NUM, o.ALL_SUM, o.DATE_, o.view_opl from oplati o where o.date_ >= '".$dt1."' and o.date_ <= '".$dt2."' AND (o.view_opl = '2' OR o.view_opl = '1') AND 
o.ID IN (".$str2.")) opl
LEFT JOIN order_product op  ON op.ORDER_ID = opl.ORDER_NUM ORDER BY op.ORDER_ID";



$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$str = $row[0].";".str_replace(".", ",", $row[1] ).";".$row[2].";".$row[3].";".$row[4].";".str_replace(".", ",", $row[5] ).";".$row[6].";".str_replace(".", ",", $row[7] ).";".str_replace(".", ",", $row[8] ).";\n";
IF($old <> $row[0]){
$json[] = array(
'str' =>   $str_old,
);	
$old = $row[0];	
}

$json[] = array(
'str' =>   $str,
);	

}
//echo   "<br>".$str1 ."<br>".$str2 ."<br>Не распределены<br><br><br>";

$str = "\n\nНе распределены\n";
$json[] = array(
'str' =>   $str,
);	
if($str2 == ""){
$str2 = '0';
}
if($str1 == ""){
$str1 = '0';
}


$query="		select   	
opl.ORDER_NUM,
round(	opl.ALL_SUM,2) ALL_SUM , 
opl.DATE_, 
(IF (opl.view_opl = '1', 'Касса', if (opl.view_opl = '2', 'Терминал', ''))) view_opl , 
op.p_names , 
op.total, 
op.units , 
round(op.PRICE / 1.2, 2) PRICE_no_nds, 
round((round(op.PRICE / 1.2, 2) * 1.2 * total ),2) SUMM,
opl.ID 
from 
(select o.ID , o.ORDER_NUM, o.ALL_SUM, o.DATE_, o.view_opl from oplati o where o.date_ >= '".$dt1."' and o.date_ <=  '".$dt2."' AND (o.view_opl = '2' OR o.view_opl = '1') AND 
NOT o.ID IN (".$str1.",".$str2.")) opl
LEFT JOIN order_product op  ON op.ORDER_ID = opl.ORDER_NUM ORDER BY op.ORDER_ID";




$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$str = $row[0].";".str_replace(".", ",", $row[1] ).";".$row[2].";".$row[3].";".$row[4].";".str_replace(".", ",", $row[5] ).";".$row[6].";".str_replace(".", ",", $row[7] ).";".str_replace(".", ",", $row[8] ).";\n";

IF($old <> $row[0]){
$json[] = array(
'str' =>   $str_old,
);	
$old = $row[0];	
}
$json[] = array(
'str' =>   $str,
);	


}


echo json_encode($json);
?>