<?

$dt1  = $_GET["dt1"];
$dt2  = $_GET["dt2"];

$re = ""; 
$summ = 0; 
$summ1 = 0; 
$summ2 = 0; 
$summ3 = 0; 
$summ4 = 0; 
$summ5 = 0; 
$date2 = date('Y-m-d', strtotime('+1 day', strtotime($dt2)));
$json = Array();
include "../db.php";


$qt = "select  (SELECT u.USER_FIO from users u where u.USER_login = tb.men) names,  SUM(tb.sums) sm, SUM(tb.kol), SUM(tb.jobs)
from 
	(select 
		(select o.USER_ID from orders o where o.NUMBER = op.ORDER_ID) men ,  
		ROUND((ROUND((op.price / 1.2) ,2) * op.total * 1.2) ,2)  sums ,
		1 kol,
		IF(op.status <> '', 1, 0) jobs
	from order_product op 
	where DATE_FORMAT(op.add_date,'%Y-%m-%d') >= '".$dt1."' AND DATE_FORMAT(op.add_date,'%Y-%m-%d') <= '".$dt2."' ) tb
	GROUP BY tb.men ";
	echo '<h3>Кол-во заказов по дням для менеджеров</h3><hr>';
	echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-2"><b>ФИО</div>
			<div class="col-md-2">На сумму, BYN</div>
			<div class="col-md-2">Всего</div>
			<div class="col-md-2">В отдано в работу</div>
			<div class="col-md-2">Средний чек</div>
		</div></b>';
	
	$result = mysql_query($qt);
while ($row = mysql_fetch_row($result)) {
 
echo '<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-2">'.$row[0].'</div>
			<div class="col-md-2">'.$row[1].'</div>
			<div class="col-md-2">'.$row[2].'</div>
			<div class="col-md-2">'.$row[3].'</div>
			<div class="col-md-2">'.ROUND($row[1] /$row[2],2) .'</div>
		</div>';
 
 }
	echo '<h3>Счета, тн, оплаты по счетам</h3><hr>';
$qt = 'select tt1.*,   ROUND((sum_job + sum_rdy) / count_sum_job,2) sull
from 
(select tt.NAME,  
	IF (sum(tt.sum_all) IS NULL  , 0 , sum(tt.sum_all) ) sum_all, 
	IF (sum(tt.sum_no) IS NULL  , 0 , sum(tt.sum_no) ) sum_no, 
	IF (sum(tt.sum_job) IS NULL , 0 , sum(tt.sum_job) ) sum_job, 
	IF (sum(tt.sum_rdy) IS NULL , 0 , sum(tt.sum_rdy) ) sum_rdy, 
   IF (sum(tt.tn) IS NULL , 0 , sum(tt.tn) ) tn, 
   IF (sum(tt.opl) IS NULL , 0 , sum(tt.opl) ) opl, 
   tt.user_id, 
   IF (sum(tt.count_all) IS NULL , 0 , sum(tt.count_all) ) count_all, 
	IF ( sum(tt.count_sum_no) IS NULL , 0 , sum(tt.count_sum_no) ) count_sum_no, 
   IF (sum(tt.count_sum_job) IS NULL , 0 , sum(tt.count_sum_job) ) count_sum_job
from (

select 
(SELECT u.USER_FIO FROM users u WHERE u.USER_LOGIN = o.user_id) NAME, 
o.user_id,
ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER GROUP BY op.ORDER_ID ),2) sum_all,
IF (ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2)  IS NULL AND 
ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = o.NUMBER GROUP BY opl.ORDER_NUM),2) IS NULL  , 
IF (ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND op.status = ""  GROUP BY op.ORDER_ID ),2) IS NULL,0,
ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND op.status = ""  GROUP BY op.ORDER_ID ),2)) , 0) sum_no,
ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND op.status <> ""  AND op.status <> "2" AND op.status <> "3" GROUP BY op.ORDER_ID ),2) sum_job,
IF (ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND (op.status = "2" OR op.status = "3") GROUP BY op.ORDER_ID ),2) IS NULL,
/*IF (ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2) IS NULL,
ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = o.NUMBER GROUP BY opl.ORDER_NUM),2),
ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2)),*/ 0,
ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND (op.status = "2" OR op.status = "3") GROUP BY op.ORDER_ID ),2)) sum_rdy,
ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2) tn ,
ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = o.NUMBER GROUP BY opl.ORDER_NUM),2) opl,

IF ((select count(*) from order_product op where op.ORDER_ID = o.NUMBER GROUP BY op.ORDER_ID ) IS NULL, 0 , (select count(*) from order_product op where op.ORDER_ID = o.NUMBER GROUP BY op.ORDER_ID ))  count_all,

IF (ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2)  IS NULL AND 
ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = o.NUMBER GROUP BY opl.ORDER_NUM),2) IS NULL  , 
(select count(*) from order_product op where op.ORDER_ID = o.NUMBER AND op.status = ""  GROUP BY op.ORDER_ID ) , 0) count_sum_no,

(select count(*) from order_product op where op.ORDER_ID = o.NUMBER AND op.status <> ""  ) count_sum_job


from
(select  o.user_id , o.NUMBER
from 
	orders o 
where DATE_OR >= "'.$dt1.'" and  DATE_OR <= "'.$date2.'"   ) o ) tt  GROUP BY tt.user_id) tt1';

//ECHO $qt;
$result = mysql_query($qt);
while ($row = mysql_fetch_row($result)) {
  $re .= '<div class="row"><div class="col-md-1"></div>
									<div class="col-md-1">'.$row[7].'</div>	
									<div class="col-md-2">'.$row[0].'</div>	
									<div class="col-md-1">'.$row[1].'</div>	
									<div class="col-md-1">'.$row[2].'</div>
									<div class="col-md-1">'.$row[3].'</div>
									<div class="col-md-1">'.$row[4].'</div>
									<div class="col-md-1">'.$row[5].'</div>
									<div class="col-md-1">'.$row[6].'</div>
									
									</div>';
	$summ  += $row[1];
	$summ1  += $row[2];
	$summ2  += $row[3];
	$summ3  += $row[4];
	$summ4  += $row[5];
	$summ5  += $row[6];
}	
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-1"><b>ID</div>
			<div class="col-md-2">ФИО</div>
			<div class="col-md-1">Все</div>
			<div class="col-md-1">Не в работе</div>
			<div class="col-md-1">В работе</div>
			<div class="col-md-1">Готовы</div>
			<div class="col-md-1">ТН</div>
			<div class="col-md-1">Оплаты</div>
			
	
		</div></b>';
echo $re;
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
//echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"><b>Всего:</b></div>	<div class="col-md-2">'.$summ.'</div><div class="col-md-2">'.$summ1.'</div></div>';

echo '<hr/><div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-1"><b></div>
			<div class="col-md-2">Всего:</div>
			<div class="col-md-1">'.$summ.'</div>
			<div class="col-md-1">'.$summ1.'</div>
			<div class="col-md-1">'.$summ2.'</div>
			<div class="col-md-1">'.$summ3.'</div>
			<div class="col-md-1">'.$summ4.'</div>
			<div class="col-md-1">'.$summ5.'</div>
		</div></b>';

echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<hr/><div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';


	echo '<h3>Оплаты за текущий период</h3><hr>';

//$qt = 'select  (SELECT u.USER_FIO FROM users u WHERE u.USER_LOGIN = (select USER_ID from orders o where o.NUMBER = ORDER_NUM)) USER_ID,  ROUND(sum(all_sum),2) from oplati WHERE DATE_ >=  "'.$dt1.'" and  DATE_ <=  "'.$dt2.'" and ORDER_NUM <> 0  GROUP BY (select USER_ID from orders o where o.NUMBER = ORDER_NUM)';
$qt = 'SELECT tbl.USER_FIO, ROUND(SUM(tbl.ALL_SUM), 2)
		FROM
		(SELECT users.USER_LOGIN, users.USER_FIO, oplati.ALL_SUM
		FROM oplati
		INNER JOIN
				orders ON oplati.ORDER_NUM<>0 AND oplati.ORDER_NUM=orders.NUMBER
		INNER JOIN
				users ON orders.USER_ID=users.USER_LOGIN
		WHERE oplati.DATE_>="'.$dt1.'" AND oplati.DATE_<="'.$dt2.'" AND oplati.doc_date IS NULL
		UNION
		SELECT users.USER_LOGIN, users.USER_FIO, oplati.ALL_SUM
		FROM oplati
		INNER JOIN
				orders ON oplati.ORDER_NUM<>0 AND oplati.ORDER_NUM=orders.NUMBER
		INNER JOIN
				users ON orders.USER_ID=users.USER_LOGIN
		WHERE oplati.doc_date>="'.$dt1.'" AND oplati.doc_date<="'.$dt2.'" AND oplati.doc_date IS NOT NULL) tbl
		GROUP BY tbl.USER_LOGIN';
$result = mysql_query($qt);
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';

while ($row = mysql_fetch_row($result)) {
	
	echo '<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-2">'.$row[0].'</div>
			<div class="col-md-2">'.$row[1].'</div>

		</div>';
}
//$qt = 'select  ROUND(sum(all_sum),2) from oplati WHERE DATE_ >=  "'.$dt1.'" and  DATE_ <=  "'.$dt2.'" and ORDER_NUM = 0 and all_sum > 0';
$qt = 'SELECT ROUND(SUM(tbl.ALL_SUM), 2)
		FROM
		(SELECT oplati.ALL_SUM
		FROM oplati
		WHERE oplati.DATE_ >= "'.$dt1.'" AND oplati.DATE_ <= "'.$dt2.'" AND oplati.ORDER_NUM = 0 AND oplati.ALL_SUM > 0 AND oplati.doc_date IS NULL
		UNION
		SELECT oplati.ALL_SUM
		FROM oplati
		WHERE oplati.doc_date >= "'.$dt1.'" AND oplati.doc_date <= "'.$dt2.'" AND oplati.ORDER_NUM = 0 AND oplati.ALL_SUM > 0 AND oplati.doc_date IS NOT NULL) tbl';
$result = mysql_query($qt);
while ($row = mysql_fetch_row($result)) {
	
	echo '<div class="row">
			<div class="col-md-1"></div>
			<div class="col-md-2"><b>Нераспределенные оплаты</b></div>
<div class="col-md-2">'.$row[0].'</div>
		</div>';
}
//$qt = 'select  ROUND(sum(all_sum),2) from oplati WHERE  DATE_ >=  "'.$dt1.'" and  DATE_ <=  "'.$dt2.'"';
$qt = 'SELECT ROUND(SUM(tbl.ALL_SUM), 2)
		FROM
		(SELECT oplati.ALL_SUM
		FROM oplati
		WHERE oplati.DATE_ >= "'.$dt1.'" AND oplati.DATE_ <= "'.$dt2.'" AND oplati.doc_date IS NULL
		UNION
		SELECT oplati.ALL_SUM
		FROM oplati
		WHERE oplati.doc_date >= "'.$dt1.'" AND oplati.doc_date <= "'.$dt2.'" AND oplati.doc_date IS NOT NULL) tbl';
$result = mysql_query($qt);
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-1"></div><div class="col-md-2"></div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
while ($row = mysql_fetch_row($result)) {
	
echo '<div class="row"><div class="col-md-1"></div><div class="col-md-2"><b>ВСЕГО:</b></div><div class="col-md-2">'.$row[0].'</div>	<div class="col-md-2"></div><div class="col-md-2"></div></div>';
}

?> 