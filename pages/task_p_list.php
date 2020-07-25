<?php
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
	include 'firewall1.php';
	session_start();
	$login = $_SESSION['login'];
	$query = "select user_per from users where user_login = '".$login ."' LIMIT 1";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) { 
		$admin = $row[0];
	}
	
	
	// получение времени по операциям
	
	//iGen 150
	$iGen_kol_oper = 0;
	$iGen_makeready_time = 0;
	$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 1";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
		$iGen_kol_oper = $row[0];
		$iGen_makeready_time = $row[1];
	}
	$pr_tm_iGen = 0;
	//
	//Xerox 1000
	$x1000_kol_oper = 0;
	$x1000_makeready_time = 0;
	$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 3";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
		$x1000_kol_oper = $row[0];
		$x1000_makeready_time = $row[1];
	}
	$pr_tm_x1000 = 0;
	//
	//Oki
	$oki_kol_oper = 0;
	$oki_makeready_time = 0;
	$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 39";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
		$oki_kol_oper = $row[0];
		$oki_makeready_time = $row[1];
	}
	$pr_tm_oki = 0;
	//
	
	//Xerox 7142
	$x7142_kol_oper = 0;
	$x7142_makeready_time = 0;
	$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 24";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
		$x7142_kol_oper = $row[0];
		$x7142_makeready_time = $row[1];
	}
$pr_tm_x7142 = 0;
//
//Xerox 4590
$x4590_kol_oper = 0;
$x4590_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 4";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$x4590_kol_oper = $row[0];
$x4590_makeready_time = $row[1];
}
$pr_tm_x4590 = 0;
//Шелкография
$shel_kol_oper = 0;
$shel_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 28";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$shel_kol_oper = $row[0];
$shel_makeready_time = $row[1];
}
$pr_tm_shel = 0;
//

//mimaki
$mima_kol_oper = 0;
$mima_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 9";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$mima_kol_oper = $row[0];
$mima_makeready_time = $row[1];
}
$pr_tm_mima = 0;
//
//ламинирование
$lam_kol_oper = 0;
$lam_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 41";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$lam_kol_oper = $row[0];
$lam_makeready_time = $row[1];
}
$pr_tm_lam = 0;
//
//Биговка
$big_kol_oper = 0;
$big_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 42";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$big_kol_oper = $row[0];
$big_makeready_time = $row[1];
}
$pr_tm_big = 0;
//
//Люверс
$luv_kol_oper = 0;
$luv_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 43";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$luv_kol_oper = $row[0];
$luv_makeready_time = $row[1];
}
$pr_tm_luv = 0;

//
//Вырубка
$vir_kol_oper = 0;
$vir_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 44";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$vir_kol_oper = $row[0];
$vir_makeready_time = $row[1];
}
$pr_tm_vir = 0;
//
//Конгрев

$kong_kol_oper = 0;
$kong_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 45";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$kong_kol_oper = $row[0];
$kong_makeready_time = $row[1];
}
$pr_tm_kong = 0;

//
//Тиснение

$tis_kol_oper = 0;
$tis_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 46";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$tis_kol_oper = $row[0];
$tis_makeready_time = $row[1];
}

//
//Пружина
$pr_kol_oper = 0;
$pr_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 47";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$pr_kol_oper = $row[0];
$pr_makeready_time = $row[1];
}
//
//Пружина(2)
$pr2_kol_oper = 0;
$pr2_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 48";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$pr2_kol_oper = $row[0];
$pr2_makeready_time = $row[1];
}
//
//Пружина(3)
$pr3_kol_oper = 0;
$pr3_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 49";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$pr3_kol_oper = $row[0];
$pr3_makeready_time = $row[1];
}
//
//Скоба
$sk_kol_oper = 0;
$sk_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 50";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$sk_kol_oper = $row[0];
$sk_makeready_time = $row[1];
}
//
//Термоклей
$term_kol_oper = 0;
$term_makeready_time = 0;
$query="select EQ_KOL_OPER, MAKEREADY_TIME from equipment where id = 51";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$term_kol_oper = $row[0];
$term_makeready_time = $row[1];
}
//

$pr_tm_tis = 0;
$pr_tm_diz = 0;
//
$kol_pr = 0;
$all_print = 0;
$all_cech = 0;
$query="select DATE_FORMAT(op.dates_rdy, '%Y-%m-%d') dt, 
op.ORDER_ID ,
(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
(select (select u.USER_FIO from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) men, 
op.p_names,  op.size, op.fast,op.cl_file, op.print_diz, op.view_diz, op.view_press, op.status,op.p_names, op.total, op.size,op.cshivka,op.template, press_diz, id ,num_prod_ord ,
(select (select c.EMAIL from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) mail,
(select (select c.PHONE_CITY from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) tel,
(select (select c.PHONE_MOB from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) tel2,
(select tt2.prob from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) prob,
(select tt2.comm from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) comm ,
(select u.USER_FIO from users u where u.USER_LOGIN = (select lt.users from lock_task lt where lt.id_prod = op.id AND lt.flags = 1 AND lt.oper = op.status  LIMIT 1) LIMIT 1)  name_user,
op.comment,
(select (select c.temp from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) temps,
op.units,
(select  DATE_FORMAT(lk.datetime, '%Y-%m-%d') from log_task lk where lk.id_prod = op.id and lk.status_old = '' ORDER BY lk.id LIMIT 1) date_ot
from order_product op where  status in (1,11,10,12)  ";

include "db.php";

$menu2_1  = '';
$menu2_1  = '';
$menu2_2  = '';
$menu2_3  = '';
$menu2_4  = '';
$menu2_5  = '';
$menu2_6  = '';
$menu2_7  = '';
$menu2_8  = '';
$menu2_9  = '';
$tb_home  = '';
$menu1  = '';
$menu3_1_1 = '';
$menu3_1_2 = '';
$menu3_1_3 = '';
$menu3_1_4 = '';
$menu3_1_5 = '';
$menu3_1 = '';
$menu3_2 = '';
$menu3_3 = '';
$menu3_4 = '';
$menu3_5 = '';
$menu3_6 = '';
$menu3_7 = '';
$menu3_8 = '';
$menu3_9 = '';
$menu3_1 = '';
$menu3_11 = '';
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
$mn = 1;
$jons = '';
$pr_time_ = 0;
IF ($row[11] == '10'){
$query1="select Name, time_ from DIZ_OPER where  FIND_IN_SET(ID,'".$row[9]."')";
$result1 = mysql_query($query1) or die($query1);

while ($row1 = mysql_fetch_row($result1)) { 
$jons.=  $row1[0]."; ";
$pr_time_ += $row1[1];
$pr_tm_all_diz += $row1[1];
}	

$tb_home .=  "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td><td>$row[2]</td><td>$row[4]</td>";
$tb_home .=  "<td>$row[5]</td><td>$row[3]</td><td >".$jons."</td><td >".$pr_time_."</td></tr>";
}
IF ($row[11] == '11'){
$kol_pr++ ;
$menu1  .=  "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td><td>$row[2]</td>";
$menu1  .=  "<td>$row[4]</td><td>$row[5]</td><td>$row[3]</td></tr>";
}
IF ($row[11] == '12'){

$temp = explode("^", $row[16]);
$eqq = "";
FOR ($i = 0; $i < count($temp); $i++){
$z = $i + 1;
$part = explode("|", $temp[$i]);

$mn = 1;
IF ($row[28] == 'тыс.шт.') {
$mn = 1000;
}
$mat = explode(":",$part[6]);

switch ($part[3]) {
case 'Xerox iGen 150':

$tm_igen = 0;
$tm_igen =  ROUND( $mat[1] /  ((INT)$iGen_kol_oper / 60),0);
$tm_igen += $iGen_makeready_time ;
$tm_igen_all += $tm_igen ;
$all_print  += $tm_igen ;
$menu2_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td>".$tm_igen."</td></tr>";	  
break;
case 'Xerox 1000':

$tm_x1000 = 0;
$tm_x1000 =  ROUND( $mat[1] /  ((INT)$x1000_kol_oper / 60),0);
$tm_x1000 += $x1000_makeready_time ;
$tm_x1000_all += $tm_x1000 ;
$all_print += $tm_x1000 ;
$menu2_2 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_2 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td>".$tm_x1000."</td></tr>";	  	  
break;
case 'Mimaki JV33-130BS':

$tm_mima = 0;
$tm_mima =  ROUND( $mat[1] /  ((INT)$mima_kol_oper / 60),0);
$tm_mima += $mima_makeready_time ;
$tm_mima_all += $tm_mima ;
$all_print += $tm_mima ;
$menu2_3 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_3 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td>".$tm_mima."</td></tr>";	  	  
break;
case 'Oki':

$tm_oki = 0;
$tm_oki =  ROUND( $mat[1] /  ((INT)$oki_kol_oper / 60),0);
$tm_oki += $oki_makeready_time ;
$tm_oki_all += $tm_oki ;
$all_print += $tm_oki ;
$menu2_4 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_4 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td>".$tm_oki."</td></tr>";	  	  
break;
case 'Офсет_Навитех':
$menu2_5 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_5 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td></td></tr>";	  	  
break;
case 'Шелкография':

$tm_shel = 0;
$tm_shel =  ROUND( $mat[1] /  ((INT)$shel_kol_oper / 60),0);
$tm_shel += $shel_makeready_time ;
$tm_shel_all += $tm_shel ;
$all_print += $tm_shel ;
$menu2_6 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_6 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td>".$tm_shel."</td></tr>";	  	  
break;
case 'Xerox 4590':

$tm_x4590 = 0;
$tm_x4590 =  ROUND( $mat[1] /  ((INT)$x4590_kol_oper / 60),0);
$tm_x4590 += $x4590_makeready_time ;
$tm_x4590_all += $tm_x4590 ;
$all_print += $tm_x4590 ;
$menu2_7 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_7 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td>".$tm_x4590."</td></tr>";	  	  
break;
case 'Xerox 7142':

$tm_x7142 = 0;
$tm_x7142 =  ROUND( $mat[1] /  ((INT)$x7142_kol_oper / 60),0);
$tm_x7142 += $x7142_makeready_time ;
$tm_x7142_all += $tm_x7142  ;
$all_print += $tm_x7142 ;
$menu2_8 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_8 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td>".$tm_x7142."</td></tr>";	  	  
break;
default:
$menu2_9 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu2_9 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td>
<td>".$part[0]."</td><td>".$part[4]."</td><td>".$mat[0].' '.$part[5]."</td><td>".$mat[1]."</td><td></td></tr>";	  	  
}
}
}


IF ($row[11] == '1'){


$cshivka = explode("|", $row[15]);
IF ($cshivka[0] != "" AND $cshivka[0] != "0") {

switch ($cshivka[0]) {
case '1':  //пружина

$tm_pr = 0;
$tm_pr =  ROUND(($row[13] * $mn) /  ((INT)$pr_kol_oper / 60),0);
$tm_pr += $pr_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '2': //пружина

$tm_pr = 0;
$tm_pr =  ROUND(($row[13] * $mn) /  ((INT)$pr_kol_oper / 60),0);
$tm_pr += $pr_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '3': //пружина

$tm_pr = 0;
$tm_pr =  ROUND(($row[13] * $mn) /  ((INT)$pr_kol_oper / 60),0);
$tm_pr += $pr_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '4': //пружина

$tm_pr = 0;
$tm_pr =  ROUND(($row[13] * $mn) /  ((INT)$pr_kol_oper / 60),0);
$tm_pr += $pr_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '5': //пружина

$tm_pr = 0;
$tm_pr =  ROUND(( $row[13] * $mn) /  ((INT)$pr_kol_oper / 60),0);
$tm_pr += $pr_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '6': //пружина

$tm_pr = 0;
$tm_pr =  ROUND(( $row[13] * $mn) /  ((INT)$pr_kol_oper / 60),0);
$tm_pr += $pr_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '14': //пружина 2 

$tm_pr = 0;
$tm_pr =  ROUND(( $row[13] * $mn) /  ((INT)$pr2_kol_oper / 60),0);
$tm_pr += $pr2_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '15':  //пружина 3

$tm_pr = 0;
$tm_pr =  ROUND(( $row[13] * $mn) /  ((INT)$pr3_kol_oper / 60),0);
$tm_pr += $pr3_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '16':  //пружина 2

$tm_pr = 0;
$tm_pr =  ROUND(( $row[13] * $mn) /  ((INT)$pr2_kol_oper / 60),0);
$tm_pr += $pr2_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '17':  //пружина 3

$tm_pr = 0;
$tm_pr =  ROUND(( $row[13] * $mn) /  ((INT)$pr3_kol_oper / 60),0);
$tm_pr += $pr3_makeready_time ;
$tm_pr_all += $tm_pr ;
$all_cech += $tm_pr ;
$all_shib += $tm_pr ;

$menu3_1_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_pr."</td></tr>";
break; 
case '7': //скоба

$tm_sk = 0;
$tm_sk =  ROUND(($row[13] * $mn) /  ((INT)$sk_kol_oper / 60),0);
$tm_sk += $sk_makeready_time ;
$tm_sk_all += $tm_sk ;
$all_cech += $tm_sk ;
$all_shib += $tm_sk ;


$menu3_1_4 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_4 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_sk."</td></tr>";
break; 
case '8': //Твердая обложка (PUR)
$menu3_1_3 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_3 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td></tr>";
break; 
case '9': //Твердая обложка (скобы)
$menu3_1_3 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_3 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td></tr>";
break; 
case '10': //Твердая обложка
$menu3_1_3 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_3 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td></tr>";
break; 
case '11': //Твердая обложка(пружина)
$menu3_1_3 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_3 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td></tr>";
break; 
case '12': //термоклей


$tm_term = 0;
$tm_term =  ROUND(($row[13] * $mn) /  ((INT)$term_kol_oper / 60),0);
$tm_term += $term_makeready_time ;
$tm_term_all += $tm_term ;
$all_cech += $tm_term ;
$all_shib += $tm_term ;

$menu3_1_2 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_2 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$tm_term."</td></tr>";
break; 
case '13': //нитка
$menu3_1_5 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1_5 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td></tr>";
break; 
}

}

$temp = explode("^", $row[16]);
$eqq = "";
FOR ($i = 0; $i < count($temp); $i++){
$z = $i + 1;
$part = explode("|", $temp[$i]);
$mat = explode(":",$part[6]);
$mn = 1;
IF ($row[28] == 'тыс.шт.') {
$mn = 1000;
}
//Резка
IF ($part[7] != "" AND  $part[7] != '0') { 	
$menu3_1 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_1 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td></tr>";	
}
// Ламинирование
IF ($part[8] != "" AND  $part[8] != '0') {

// $lam_kol_oper 
// $lam_makeready_time 


$tm_lam = 0;
$size_l = explode("*", $part[5]);

IF ((INT)$size_l[0] < 360 AND  (INT)$size_l[1] < 360){

IF ((INT)$size_l[0] > (INT)$size_l[1]){
$tm_lam =  ROUND((((INT)$size_l[0] / 1000) * $mat[1]) /  ((INT)$lam_kol_oper / 60),0);
} else {
$tm_lam =  ROUND((((INT)$size_l[1] / 1000) * $mat[1]) /  ((INT)$lam_kol_oper / 60),0);
}

} else {

IF ((INT)$size_l[0] < 360){
$tm_lam =  ROUND((((INT)$size_l[0] / 1000) * $mat[1]) /  ((INT)$lam_kol_oper / 60),0);
} else {

$tm_lam =  ROUND((((INT)$size_l[1] / 1000) * $mat[1]) /  ((INT)$lam_kol_oper / 60),0);
}


}

$tm_lam += $lam_makeready_time ;
$tm_lam_all += $tm_lam ;
$all_cech += $tm_lam ;
$menu3_3 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_3 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$part[8]."</td><td>".$tm_lam ."</td></tr>";	
}
//Биговка
IF ($part[9] != "" AND  $part[9] != '0') { 


$tm_big = 0;
$tm_big =  ROUND(($part[9] * $row[13] * $mn) /  ((INT)$big_kol_oper / 60),0);
$tm_big += $big_makeready_time ;
$tm_big_all += $tm_big ;
$all_cech += $tm_big ;

$menu3_4 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_4 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$part[9] * $row[13] * $mn."</td><td>".$tm_big."</td></tr>";	
}
//Перфорация
IF ($part[10] != "" AND  $part[10] != '0') { 
$menu3_5 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_5 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$part[10] * $row[13] * $mn."</td></tr>";	
}
//Скругление углов
IF ($part[11] != "" AND  $part[11] != '0') { 
$menu3_6 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_6 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$part[11] * $row[13] * $mn."</td></tr>";	
}
//Отверстия
IF ($part[12] != "" AND  $part[12] != '0') { 
$menu3_7 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_7 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$part[12] * $row[13] * $mn."</td></tr>";	
}
//Люверс
IF ($part[14] != "" AND  $part[14] != '0') { 


$tm_luv = 0;
$tm_luv =  ROUND(($part[14] * $row[13] * $mn) /  ((INT)$luv_kol_oper / 60),0);
$tm_luv += $luv_makeready_time ;
$tm_luv_all += $tm_luv ;
$all_cech += $tm_luv ;


$menu3_8 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_8 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".$part[15]."</td><td>".$part[14] * $row[13] * $mn."</td><td>".$tm_luv."</td></tr>";	
}
//Вырубка
IF ($part[16] != "" AND  $part[16] != '0') { 

$tm_vir = 0;
if((INT)$vir_kol_oper > 0)
	$tm_vir =  ROUND(ROUND($row[13] * $mn / $part[16],0) /  ((INT)$vir_kol_oper / 60),0);
$tm_vir += $vir_makeready_time ;
$tm_vir_all += $tm_vir ;
$all_cech += $tm_vir ;

$menu3_9 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_9 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>". ROUND($row[13] * $mn / $part[16],0) ."</td><td>".$tm_vir."</td></tr>";	
}
//Конгрев
IF ($part[17] != "" AND  $part[17] != '0') { 

$tm_kong = 0;
$tm_kong =  ROUND(ROUND($row[13] * $mn / $part[17],0) /  ((INT)$kong_kol_oper / 60),0);
$tm_kong += $kong_makeready_time ;
$tm_kong_all += $tm_kong ;
$all_cech += $tm_kong ;
$menu3_10 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_10 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".ROUND($row[13] * $mn / $part[17],0)."</td><td>".$tm_kong."</td></tr>";	
}	
//Тиснение
IF ($part[18] != "" AND  $part[18] != '0') { 
$tm_tis = 0;
$tm_tis =  ROUND(ROUND($row[13] * $mn / floatval(str_replace(",", ".", $part[18])),0) /  ((INT)$tis_kol_oper / 60),0);
$tm_tis += $tis_makeready_time ;
$tm_tis_all += $tm_tis ;
$all_cech += $tm_tis ;
$menu3_11 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_11 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td><td>".ROUND($row[13] * $mn / floatval(str_replace(",", ".", $part[18])),0)."</td><td>".$tm_tis."</td></tr>";	
}
//Плот резка
IF ($part[26] != "" AND  $part[26] != '0') { 
$menu3_2 .= "<tr><td>$row[0]</td><td>$row[29]</td><td>".$row[1]."_".$row[19]."</td>";
$menu3_2 .= "<td>$row[2]</td><td>$row[4]</td><td>$row[5]</td><td>".($row[13] * $mn) ."</td><td>$row[3]</td></tr>";	
}


}
}


}

$pr_tm_lam =  floor($tm_lam_all / 60).":";
if (ROUND(($tm_lam_all - floor($tm_lam_all / 60) * 60),2) < 10) {  
$pr_tm_lam .= "0".ROUND(($tm_lam_all - floor($tm_lam_all / 60) * 60),0);
} else {
$pr_tm_lam .= ROUND(($tm_lam_all - floor($tm_lam_all / 60) * 60),0);
} 

$pr_tm_big =  floor($tm_big_all / 60).":";
if (ROUND(($tm_big_all - floor($tm_big_all / 60) * 60),2) < 10) {  
$pr_tm_big .= "0".ROUND(($tm_big_all - floor($tm_big_all / 60) * 60),0);
} else {
$pr_tm_big .= ROUND(($tm_big_all - floor($tm_big_all / 60) * 60),0);
} 

$pr_tm_luv =  floor($tm_luv_all / 60).":";
if (ROUND(($tm_luv_all - floor($tm_luv_all / 60) * 60),2) < 10) {  
$pr_tm_luv .= "0".ROUND(($tm_luv_all - floor($tm_luv_all / 60) * 60),0);
} else {
$pr_tm_luv .= ROUND(($tm_luv_all - floor($tm_luv_all / 60) * 60),0);
} 

$pr_tm_pres =  floor((((int)$kol_pr  / 8)  * 60 ) / 60).":";
if (ROUND(((((int)$kol_pr  / 8)  * 60 )- floor((((int)$kol_pr  / 8)  * 60 ) / 60) * 60),2) < 10) {  
$pr_tm_pres .= "0".ROUND(((((int)$kol_pr  / 8)  * 60 ) - floor((((int)$kol_pr  / 8)  * 60 ) / 60) * 60),0);
} else {
$pr_tm_pres .= ROUND(((((int)$kol_pr  / 8)  * 60 ) - floor((((int)$kol_pr  / 8)  * 60 ) / 60) * 60),0);
} 

$pr_tm_vir =  floor($tm_vir_all / 60).":";
if (ROUND(($tm_vir_all - floor($tm_vir_all / 60) * 60),2) < 10) {  
$pr_tm_vir .= "0".ROUND(($tm_vir_all - floor($tm_vir_all / 60) * 60),0);
} else {
$pr_tm_vir .= ROUND(($tm_vir_all - floor($tm_vir_all / 60) * 60),0);
} 

$pr_tm_kong =  floor($tm_kong_all / 60).":";
if (ROUND(($tm_kong_all - floor($tm_kong_all / 60) * 60),2) < 10) {  
$pr_tm_kong .= "0".ROUND(($tm_kong_all - floor($tm_kong_all / 60) * 60),0);
} else {
$pr_tm_kong .= ROUND(($tm_kong_all - floor($tm_kong_all / 60) * 60),0);
} 

$pr_tm_tis =  floor($tm_tis_all / 60).":";
if (ROUND(($tm_tis_all - floor($tm_tis_all / 60) * 60),2) < 10) {  
$pr_tm_tis .= "0".ROUND(($tm_tis_all - floor($tm_tis_all / 60) * 60),0);
} else {
$pr_tm_tis .= ROUND(($tm_tis_all - floor($tm_tis_all / 60) * 60),0);
} 

$pr_tm_igen =  floor($tm_igen_all / 60).":";
if (ROUND(($tm_igen_all - floor($tm_igen_all / 60) * 60),2) < 10) {  
$pr_tm_igen .= "0".ROUND(($tm_igen_all - floor($tm_igen_all / 60) * 60),0);
} else {
$pr_tm_igen .= ROUND(($tm_igen_all - floor($tm_igen_all / 60) * 60),0);
} 

$pr_tm_x1000 =  floor($tm_x1000_all / 60).":";
if (ROUND(($tm_x1000_all - floor($tm_x1000_all / 60) * 60),2) < 10) {  
$pr_tm_x1000 .= "0".ROUND(($tm_x1000_all - floor($tm_x1000_all / 60) * 60),0);
} else {
$pr_tm_x1000 .= ROUND(($tm_x1000_all - floor($tm_x1000_all / 60) * 60),0);
} 

$pr_tm_oki =  floor($tm_oki_all / 60).":";
if (ROUND(($tm_oki_all - floor($tm_oki_all / 60) * 60),2) < 10) {  
$pr_tm_oki .= "0".ROUND(($tm_oki_all - floor($tm_oki_all / 60) * 60),0);
} else {
$pr_tm_oki .= ROUND(($tm_oki_all - floor($tm_oki_all / 60) * 60),0);
} 

$pr_tm_shel =  floor($tm_shel_all / 60).":";
if (ROUND(($tm_shel_all - floor($tm_shel_all / 60) * 60),2) < 10) {  
$pr_tm_shel .= "0".ROUND(($tm_shel_all - floor($tm_shel_all / 60) * 60),0);
} else {
$pr_tm_shel .= ROUND(($tm_shel_all - floor($tm_shel_all / 60) * 60),0);
} 

$pr_tm_x4590 =  floor($tm_x4590_all / 60).":";
if (ROUND(($tm_x4590_all - floor($tm_x4590_all / 60) * 60),2) < 10) {  
$pr_tm_x4590 .= "0".ROUND(($tm_x4590_all - floor($tm_x4590_all / 60) * 60),0);
} else {
$pr_tm_x4590 .= ROUND(($tm_x4590_all - floor($tm_x4590_all / 60) * 60),0);
} 

$pr_tm_x7142 =  floor($tm_x7142_all / 60).":";
if (ROUND(($tm_x7142_all - floor($tm_x7142_all / 60) * 60),2) < 10) {  
$pr_tm_x7142 .= "0".ROUND(($tm_x7142_all - floor($tm_x7142_all / 60) * 60),0);
} else {
$pr_tm_x7142 .= ROUND(($tm_x7142_all - floor($tm_x7142_all / 60) * 60),0);
} 

$pr_tm_mima =  floor($tm_mima_all / 60).":";
if (ROUND(($tm_mima_all - floor($tm_mima_all / 60) * 60),2) < 10) {  
$pr_tm_mima .= "0".ROUND(($tm_mima_all - floor($tm_mima_all / 60) * 60),0);
} else {
$pr_tm_mima .= ROUND(($tm_mima_all - floor($tm_mima_all / 60) * 60),0);
} 

$pr_tm_diz =  floor($pr_tm_all_diz / 60).":";
if (ROUND(($pr_tm_all_diz - floor($pr_tm_all_diz / 60) * 60),2) < 10) {  
$pr_tm_diz .= "0".ROUND(($pr_tm_all_diz - floor($pr_tm_all_diz / 60) * 60),0);
} else {
$pr_tm_diz .= ROUND(($pr_tm_all_diz - floor($pr_tm_all_diz / 60) * 60),0);
}

$pr_all_print   =  floor($all_print / 60).":";
if (ROUND(($all_print - floor($all_print / 60) * 60),2) < 10) {  
$pr_all_print .= "0".ROUND(($all_print - floor($all_print / 60) * 60),0);
} else {
$pr_all_print .= ROUND(($all_print - floor($all_print / 60) * 60),0);
}



$pr_all_cech  =  floor($all_cech / 60).":";
if (ROUND(($all_cech - floor($all_cech / 60) * 60),2) < 10) {  
$pr_all_cech .= "0".ROUND(($all_cech - floor($all_cech / 60) * 60),0);
} else {
$pr_all_cech .= ROUND(($all_cech - floor($all_cech / 60) * 60),0);
}

$pr_all_shib  =  floor($all_shib / 60).":";
if (ROUND(($all_shib - floor($all_shib / 60) * 60),2) < 10) {  
$pr_all_shib .= "0".ROUND(($all_shib - floor($all_shib / 60) * 60),0);
} else {
$pr_all_shib .= ROUND(($all_shib - floor($all_shib / 60) * 60),0);
}


$pr_pr_all  =  floor($tm_pr_all / 60).":";
if (ROUND(($tm_pr_all - floor($tm_pr_all / 60) * 60),2) < 10) {  
$pr_pr_all .= "0".ROUND(($tm_pr_all - floor($tm_pr_all / 60) * 60),0);
} else {
$pr_pr_all .= ROUND(($tm_pr_all - floor($tm_pr_all / 60) * 60),0);
}

$pr_sk_all  =  floor($tm_sk_all / 60).":";
if (ROUND(($tm_sk_all - floor($tm_sk_all / 60) * 60),2) < 10) {  
$pr_sk_all .= "0".ROUND(($tm_sk_all - floor($tm_sk_all / 60) * 60),0);
} else {
$pr_sk_all .= ROUND(($tm_sk_all - floor($tm_sk_all / 60) * 60),0);
}

$pr_term_all  =  floor($tm_term_all / 60).":";
if (ROUND(($tm_term_all - floor($tm_term_all / 60) * 60),2) < 10) {  
$pr_term_all .= "0".ROUND(($tm_term_all - floor($tm_term_all / 60) * 60),0);
} else {
$pr_term_all .= ROUND(($tm_term_all - floor($tm_term_all / 60) * 60),0);
}





?>
<!DOCTYPE html>
<html lang="ru">

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<title>Система управления заказами</title>
<link rel="icon" href="../favicon.png" type="image/png">

<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
<link type="text/css" href="../js/jquery-ui.min.css" rel="stylesheet" />	
<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<link href="../vendor/bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css">

</head>

<body>
<div id="wrapper">
<?php
include_once("menu.php");

?>
<div id="page-wrapper">
<div class="row">
<div class="col-lg-12">
<h2 class="page-header">Загруженность</h2>
</div>
</div>
<ul class="nav nav-tabs">
<li class="active"><a data-toggle="tab" href="#home">Дизайн  <span class="label label-info"><?echo  $pr_tm_diz;?></span></a></li> 
<li><a data-toggle="tab" href="#menu1">Препресс <span class="label label-info"><?echo  $pr_tm_pres;?></span></a></li>
<li><a data-toggle="tab" href="#menu2">Печать <span class="label label-info"><?echo  $pr_all_print;?></span></a></li> 
<li><a data-toggle="tab" href="#menu3">Цех <span class="label label-info"><?echo  $pr_all_cech;?></span></a></li>
</ul>

<div class="tab-content">
<div id="home" class="tab-pane fade in active">
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_home" >
<thead>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Менеджер</th>
<th>ОПИСАНИЕ</th>
<th>Время выполнения(мин)</th>
</tr>
</thead>
<tbody> <? echo $tb_home;?></tbody>

</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu1" class="tab-pane fade">
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu1" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Менеджер</th>

</tr>
</tr>
</thead>
<tbody><? echo $menu1?></tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu2" class="tab-pane fade">
<ul class="nav nav-tabs">
<li class="active"><a data-toggle="tab" href="#menu2_1">Xerox iGen 150 <span class="label label-info"><?echo  $pr_tm_igen;?></span></a></li>
<li><a data-toggle="tab" href="#menu2_2">Xerox 1000 <span class="label label-info"><?echo  $pr_tm_x1000;?></span></a></li>
<li><a data-toggle="tab" href="#menu2_3">Mimaki JV33-130BS <span class="label label-info"><?echo  $pr_tm_mima;?></span></a></li> 
<li><a data-toggle="tab" href="#menu2_4">Oki <span class="label label-info"><?echo  $pr_tm_oki;?></span></a></li>
<li><a data-toggle="tab" href="#menu2_5">Офсет_Навитех</a></li>
<li><a data-toggle="tab" href="#menu2_6">Шелкография <span class="label label-info"><?echo  $pr_tm_shel;?></span></a></li>
<li><a data-toggle="tab" href="#menu2_7">Xerox 4590 <span class="label label-info"><?echo  $pr_tm_x4590;?></span></a></li>
<li><a data-toggle="tab" href="#menu2_8">Xerox 7142 <span class="label label-info"><?echo  $pr_tm_x7142;?></span></a></li>
<li><a data-toggle="tab" href="#menu2_9">Непонятные</a></li>
</ul>

<div class="tab-content">
<div id="menu2_1" class="tab-pane fade in active"> 
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_1" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_1;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu2_2" class="tab-pane fade"> 		<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_2" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_2;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div></div>
<div id="menu2_3" class="tab-pane fade"> 		<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_3" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_3;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div></div>
<div id="menu2_4" class="tab-pane fade"> 		<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_4" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_4;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu2_5" class="tab-pane fade"> 		
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_5" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_5;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div></div>
<div id="menu2_6" class="tab-pane fade"> 		<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_6" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_6;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu2_7" class="tab-pane fade"> 		
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_7" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_7;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div></div>
<div id="menu2_8" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_8" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_8;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div></div>
<div id="menu2_9" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu2_9" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Часть</th>
<th>Цвет</th>
<th>Материал</th>
<th>Кол-во листов</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu2_9;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3" class="tab-pane fade">
<ul class="nav nav-tabs">
<li class="active"><a data-toggle="tab" href="#menu3_1">Резка</a></li>
<li><a data-toggle="tab" href="#menu3_2">Плот. Резка</a></li>
<li><a data-toggle="tab" href="#menu3_3">Ламинирование <span class="label label-info"><?echo  $pr_tm_lam;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_4">Биговка <span class="label label-info"><?echo  $pr_tm_big;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_5">Перфорация</a></li>
<li><a data-toggle="tab" href="#menu3_6">Скругление углов</a></li>
<li><a data-toggle="tab" href="#menu3_7">Отверстия</a></li>
<li><a data-toggle="tab" href="#menu3_8">Люверс <span class="label label-info"><?echo  $pr_tm_luv;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_9">Вырубка <span class="label label-info"><?echo  $pr_tm_vir;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_10">Конгрев <span class="label label-info"><?echo  $pr_tm_kong;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_11">Тиснение <span class="label label-info"><?echo  $pr_tm_tis;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_13">Переплет <span class="label label-info"><?echo  $pr_all_shib;?></span></a></li>

</ul>

<div class="tab-content">
<div id="menu3_1" class="tab-pane fade in active"> 
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_1" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_1;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_2" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_2" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_2;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_3" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_3" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>параметр</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_3;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_4" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_4" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Кол-во</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_4;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_5" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_5" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Кол-во</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_5;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_6" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_6" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Кол-во</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_6;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_7" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_7" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Кол-во</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_7;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_8" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_8" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th></th>
<th>Кол-во</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_8;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_9" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_9" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Кол-во</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_9;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_10" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_10" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Кол-во</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_10;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_11" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_11" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Кол-во</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_11;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>

<div id="menu3_13" class="tab-pane fade"> 	
<ul class="nav nav-tabs">
<li class="active"><a data-toggle="tab" href="#menu3_1_1">Пружина <span class="label label-info"><?echo  $pr_pr_all;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_1_2">Клей <span class="label label-info"><?echo   $pr_term_all;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_1_3">Твердая облога</a></li>
<li><a data-toggle="tab" href="#menu3_1_4">Скоба <span class="label label-info"><?echo  $pr_sk_all;?></span></a></li>
<li><a data-toggle="tab" href="#menu3_1_5">Нитка</a></li>
</ul>

<div class="tab-content">
<div id="menu3_1_1" class="tab-pane fade in active"> 
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_1_1" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_1_1;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_1_2" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_1_2" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_1_2;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_1_3" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_1_3" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_1_3;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_1_4" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_1_4" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
<th>Время выполнения(мин)</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_1_4;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
<div id="menu3_1_5" class="tab-pane fade"> 	
<div class="row">
<div class="col-lg-12">
<div class="panel panel-default">
<div id="baseDateControl">

<div class="panel-body">
<table width="100%"  class="table table-striped table-bordered table-hover " cellspacing="0" id="tb_menu3_1_5" >
<thead>
<tr>
<tr>	
<th>Дата готовности</th>
<th>Дата отправки в работу</th>
<th>Номер счета</th>
<th>Наименование клиента</th>
<th>Продукт</th>
<th>Размер</th>
<th>Тираж</th>
<th>Менеджер</th>
</tr>
</tr>
</thead>
<tbody>
<? echo $menu3_1_5;?>
</tbody>
</table>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>	 
</div>



<footer class="footer">
<p>&copy; Company 2016</p>
</footer>
<script src="../vendor/jquery/jquery.min.js"></script> 
<script src="../js/jquery-ui.min.js"></script>
<script src="../vendor/bootstrap/js/bootstrap-select.js" type = "text/javascript"></script>
<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="../vendor/metisMenu/metisMenu.min.js"></script>
<script src="../dist/js/sb-admin-2.js"></script>


</body>

</html>
