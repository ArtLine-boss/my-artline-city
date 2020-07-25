<?
include '../db.php';
$qr = "select 
	o.id, 
	SUM(o.TIME_) times, 
	(select u.USER_FIO from users u where u.USER_LOGIN = o.usr ) usr ,  
	o.ORDER_ID , 
	o.num_prod_ord,  
	 o.sum_press
from (
		select 
		op.id, 
		d_p.TIME_ , 
		(select l_t.users from lock_task l_t where l_t.id_prod = op.ID and l_t.oper = 10 and l_t.flags = 1 limit 1) usr,
	 op.ORDER_ID , op.num_prod_ord , op.sum_press
from 
	(select op.ID, op.view_diz , op.ORDER_ID , op.num_prod_ord, op.sum_press from order_product op where op.status = 10) op LEFT JOIN  
	DIZ_OPER d_p ON FIND_IN_SET(d_p.id,op.view_diz)  ) o GROUP BY o.id, o.usr ORDER BY o.usr

";
$re = "";
echo "<label>По заявкам:</label><br><br>";
$rs =  mysql_query($qr) or die($qr);
while ($row = mysql_fetch_row($rs)) { 
 
$price = 0;
if ($row[5] == ""){
	$price = 0;
} else {
	$price = $row[5];
}
 $re .= '<div class="row" ><div class="col-md-3" style="outline: 0.5px solid black">'.$row[2].'</div>
									<div class="col-md-2" style="outline: 0.5px solid black">'.$row[3]."_".$row[4].'</div>	
									<div class="col-md-1" style="outline: 0.5px solid black">'.$row[1]." мин. ".'</div>	
									<div class="col-md-2" style="outline: 0.5px solid black">'.$price .' BYN</div>	
									
									</div>';
	
} 

echo '<div class="row">
			<div class="col-md-3"><label>Дизайнер</label></div>
			<div class="col-md-2"><label>Номер заявки</label></div>	
			<div class="col-md-1"><label>Время</label></div>	
			<div class="col-md-2"><label>Стоимость</label></div>	
		</div>
		<div class="row">
			<div class="col-md-3">&nbsp;</div>
			<div class="col-md-2">&nbsp;</div>	
			<div class="col-md-1">&nbsp;</div>	
			<div class="col-md-2">&nbsp;</div>	
		</div>';
									
echo $re ;


echo "<hr><br><label>Всего на тек. момент:</label><br><br>";

$qr = "SELECT t1.usr, ROUND(sum(t1.times),0), ROUND(sum(t1.sum_press),2)  FROM 

(select 
	o.id, 
	SUM(o.TIME_) times, 
	(select u.USER_FIO from users u where u.USER_LOGIN = o.usr ) usr ,  
	o.ORDER_ID , 
	o.num_prod_ord,  
	 o.sum_press,
	 o.usr u1
from (
	select 
		op.id, 
		d_p.TIME_ , 
		(select l_t.users from lock_task l_t where l_t.id_prod = op.ID and l_t.oper = 10 and l_t.flags = 1 LIMIT 1) usr,
	 op.ORDER_ID , op.num_prod_ord , op.sum_press
from 
	(select op.ID, op.view_diz , op.ORDER_ID , op.num_prod_ord, op.sum_press from order_product op where op.status = 10) op LEFT JOIN  
	DIZ_OPER d_p ON FIND_IN_SET(d_p.id,op.view_diz)  ) o GROUP BY o.id, o.usr ORDER BY o.usr)  t1 GROUP BY t1.u1



";
$re = "";
$rs =  mysql_query($qr) or die($qr);
while ($row = mysql_fetch_row($rs)) { 
 
 $price = 0;
if ($row[2] == ""){
	$price = 0;
} else {
	$price = $row[2];
}

 $re .= '<div class="row"><div class="col-md-3">'.$row[0].'</div>
			
									<div class="col-md-2">'.$row[1]." мин. ".'</div>	
									<div class="col-md-2">'.$price .' BYN</div>	
									
									</div>';
	
} 


echo '<div class="row">
			<div class="col-md-3"><label>Дизайнер</label></div>
			<div class="col-md-2"><label>Время</label></div>	
			<div class="col-md-2"><label>Стоимость</label></div>	
		</div>
		<div class="row">
			<div class="col-md-3">&nbsp;</div>
			<div class="col-md-2">&nbsp;</div>	
			<div class="col-md-2">&nbsp;</div>	
		</div>';
									
echo $re ;



?>

