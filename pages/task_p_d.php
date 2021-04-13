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
		<link href="../vendor/footable/css/footable.standalone.min.css" rel="stylesheet" type="text/css">
		<style>
			.content {
			padding: 3px;
			margin-bottom: 5px;
			}
			.MYlistbox
			{
			display: block;
			width: 100%;
			padding-left: 0;
			padding-right: 0;
			}
			.btn-block {
			display: block;
			width: 100%;
			padding-left: 0;
			padding-right: 0;
			}
			.inputSearch{
			display: block;
			width: 100%;
			padding-left: 0;
			padding-right: 0;
			}
			select {
			width: 100%; /* Ширина списка в пикселах */
		}
		.modal-lg1
		{
		height:50%;
		width:50%;
		margin-left: 25%; 
		
		}
		</style>
		<script>
		/*window.addEventListener("DOMContentLoaded", function() {
			var inpMY = document.getElementById("search");
			var sMY = document.getElementById("orderClient");
			var grMY = sMY.querySelectorAll("optgroup");
			var oMY = [].map.call(grMY, function(el) {
				return [].slice.call(el.querySelectorAll("option"))
			});
			
			function FiltrGoAnother() {
				var regMY = new RegExp(inpMY.value, "ig");
				oMY.forEach(function(node, i) {
				node.forEach(function(op, a) {
				regMY.lastIndex = 0;
				regMY.test(op.text) ? grMY[i].appendChild(op) : op.parentNode && grMY[i].removeChild(op)
				});
				grMY[i].children.length ?
				sMY.appendChild(grMY[i]) : grMY[i].parentNode && sMY.removeChild(grMY[i])
				})
			}
			inpMY.addEventListener("input", FiltrGoAnother, false)
		});*/
		</script>
		</head>
		
		<body>
		<div id="wrapper">
		<?php
		include_once("menu.php");
		
		?>
		<div id="page-wrapper">
		<div class="row">
		<div class="col-lg-12">
		<h2 class="page-header">Заказы</h2>
		</div>
		</div>
		<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#home">В работе</a></li>
		<li><a data-toggle="tab" href="#menu1">Список работы</a></li>
		
		</ul>
		
		<div class="tab-content">
		<div id="home" class="tab-pane fade in active">
		<div class="row"><div class="col-md-5">
		<?if ($admin == '4' OR   $admin == '6' ){
		
		echo "<button type='button' class='btn btn-default'  onclick='_prod_job_ceh()'>В цех</button>";
		}?>	
		<?if ($admin == '4' OR   $admin == '6' OR   $admin == '7' ){
		
		echo "<button type='button' class='btn btn-default'  onclick='_prod_job_rdy()'>Готово</button>";
		}?>	
			<?if (   $admin == '7' ){
		
		echo "<button type='button' class='btn btn-default'  onclick='_prod_job_print()'>Передать на печать без файлов</button>";
		echo "<button type='button' class='btn btn-default'  onclick='_prod_job_print_cech()'>Передать в цех без файлов</button>";
		echo "<button type='button' class='btn btn-default'  onclick='_prod_job_return()'>Возврат</button>";
		}?>	
			 
		</div>  </div>      
		<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div id="baseDateControl">
		
		<div class="panel-body">
		<table id="tableListTask1" class="table"  data-paging-size="50" data-show-toggle="false" data-paging="true" data-filtering="true" data-sorting="true"  >
		<thead>
		<tr>	<th></th><th data-sortable="false"><input type="checkbox" id="checkSelectAllListTask1" onchange="selectAll(this.checked, this.id)"><label for="checkSelectAllListTask1">Все</label></th>
		<th>Дата сдачи</th>
		<th>Дата отправки в работу</th>
		<th>Номер счета</th>
		<th>Стоимость дизайна</th>
		<th>Наименование клиента</th>
		<th>Продукт</th>
		<th>Размер</th>
		<th>Менеджер</th>
		<!--	<th>Причина</th>-->
		<th>Комментарий</th> 
		<th></th>
		<th></th>
		<th></th>
		<th data-breakpoints="all" data-title="ОПИСАНИЕ:">ОПИСАНИЕ</th>
		</tr>
		</thead>
		<?php
		if ($admin == 5){
		$srttt = "10";
		}
		if ($admin == 7){
		$srttt = "11";
		}
		if ($admin == 6){
		$srttt = "12";
		}
		if ($admin == 4){
		$srttt = "12";
		}
		
		$query="select DATE_FORMAT(op.dates_rdy, '%Y-%m-%d %H:%i') dt, 
		op.ORDER_ID ,
		(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
		(select (select u.USER_FIO from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) men, 
		op.p_names,  op.size, op.fast,op.cl_file, op.print_diz, op.view_diz, op.view_press, op.status,op.p_names, op.total, op.size,op.cshivka,op.template, press_diz, id ,num_prod_ord,
		(select (select c.EMAIL from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) mail,
		(select (select c.PHONE_CITY from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) tel,
		(select (select c.PHONE_MOB from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) tel2,
		(select tt2.prob from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) prob,
		(select tt2.comm from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) comm 	,
		(select u.USER_FIO from users u where u.USER_LOGIN = (select lt.users from lock_task lt where lt.id_prod = op.id AND lt.flags = 1 AND lt.oper = op.status  LIMIT 1) LIMIT 1)  name_user,
		op.comment	,
		(select (select c.temp from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) temps		,
		op.units			,
		(select  DATE_FORMAT(lk.datetime, '%Y-%m-%d %H:%i') from log_task lk where lk.id_prod = op.id and lk.status_old = '' ORDER BY lk.id LIMIT 1) date_ot, op.TEMP_PR								
		from order_product op where status in (".$srttt.") AND EXISTS (select lt.flags from lock_task lt where lt.id_prod = op.id AND lt.flags = 1 AND lt.users = '".$login."')";
		
		
		
		if ($login == "015"){
		$query="select DATE_FORMAT(op.dates_rdy, '%Y-%m-%d %H:%i') dt, 
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
		(select  DATE_FORMAT(lk.datetime, '%Y-%m-%d %H:%i') from log_task lk where lk.id_prod = op.id and lk.status_old = '' ORDER BY lk.id LIMIT 1) date_ot, op.TEMP_PR
		from order_product op where status in (".$srttt.") AND EXISTS (select lt.flags from lock_task lt where lt.id_prod = op.id AND lt.flags = 1)";
		}
		
		include "db.php";
		
		$result = mysql_query($query) or die($query);
		echo "<tbody>";
		while ($row = mysql_fetch_array($result)) {
		$eqq = "";
		$list_prod = '';
		$list_prod1 = '';
		if ($row[11] != "12"){
		
		if ($row[11] == '10'){
		IF ($row[20] != ""){
		$list_prod.=  "	<div class = 'row'><div class='col-md-2'>Email</div> <div class='col-md-10'>".$row[20]."</div></div> ";						
		}
		IF ($row[21] != ""){
		$list_prod.=  "	<div class = 'row'><div class='col-md-2'>Телефон#1</div> <div class='col-md-10'>".$row[21]."</div></div> ";						
		}
		IF ($row[22] != ""){
		$list_prod.=  "	<div class = 'row'><div class='col-md-2'>Телефон#2</div> <div class='col-md-10'>".$row[22]."</div></div> ";						
		}												
		
		$list_prod.=  "	<div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> ";
		$list_prod.=  "	<div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> ";
		
		IF ( $row[27] != "" AND  $row[27] != "$$$$$" AND  $row[27] != "$$$$$!$$$$$" AND  $row[27] != "$$$$$!$$$$$!$$$$"){
		
		$list_prod.=  "	<div class = 'row'>
		<div class='col-md-2'>FIO</div>
		<div class='col-md-2'>email</div> 
		<div class='col-md-2'>phone#1</div> 
		<div class='col-md-2'>phone#2</div>
		<div class='col-md-2'>Skype</div>
		<div class='col-md-2'>Viber</div>																			
		</div> 										
		";
		
		$list_prod.=  "	<hr>";
		
		
		$line_1 = explode("!", $row[27]);
		
		FOR ($x = 0; $x < count($line_1); $x++){
		$line_2 = explode("$", 	$line_1[$x]);
		
		$list_prod.=  "	<div class = 'row'>
		<div class='col-md-2'>".$line_2[0]."</div>
		<div class='col-md-2'>".$line_2[1]."</div> 
		<div class='col-md-2'>".$line_2[2]."</div> 
		<div class='col-md-2'>".$line_2[3]."</div>
		<div class='col-md-2'>".$line_2[4]."</div>
		<div class='col-md-2'>".$line_2[5]."</div>																			
		</div> 										
		";
		
		
		}
		
		
		
		
		
		
		}
		
		
		$list_prod.=  "	<div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> 
		<div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> ";
		$list_prod.=  "	<hr>";
		IF ($row[26] != "") {
		$list_prod.= "<B>Комментарий: </B>".$row[26]."<hr>";
		}
		
		
		$query1="select Name from DIZ_OPER where  FIND_IN_SET(ID,'".$row[9]."')";
		$link = $row[7];
		$result1 = mysql_query($query1) or die($query1);
		$list_prod.=  "<div class = 'row'><div class='col-md-4'>";
		while ($row1 = mysql_fetch_row($result1)) { 
		$list_prod.=  "<div class = 'row'><div class='col-md-12'>".$row1[0]."</div> </div> ";
		$list_prod.= "</div>
		</div> <div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> 
		</div>
		</div><div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> 
		</div>
		</div> "	;	
		}	
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Наименование: </div><div class='col-md-9'>".$row[12]."</div> </div> ";
		IF ($row[28] == 'тыс.шт.') {
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".((double)$row[13] * 1000)."</div> </div> ";
		}
		ELSE {
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".$row[13]."</div> </div> ";
		}
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Размер: </div><div class='col-md-9'>".$row[14]." </div> </div> ";
		$cshivka = explode("|", $row[15]);
		IF ($cshivka[0] != "" AND $cshivka[0] != "0") {
		$list_prod.=  "<div class = 'row'><div class='col-md-5'>Переплет по ".$cshivka[1]." стороне; "; 
		
		switch ($cshivka[0]) {
		case '1': $list_prod.= 'пружина 6,4 мм'; break;
		case '2': $list_prod.=  'пружина 8,0 мм'; break;
		case '3': $list_prod.=  'пружина 9,5 мм'; break;
		case '4': $list_prod.=  'пружина 11,0 мм'; break;
		case '5': $list_prod.=  'пружина 12,7 мм'; break;
		case '6': $list_prod.=  'пружина 14,3 мм'; break;
		case '7': $list_prod.=  'скоба'; break;
		case '8': $list_prod.=  'Твердая обложка (PUR)'; break;
		case '9': $list_prod.=  'Твердая обложка (скобы)'; break;
		case '10': $list_prod.=  'Твердая обложка'; break;
		case '11': $list_prod.=  'Твердая обложка (пружина)'; break;
		case '12': $list_prod.=  'термоклей'; break;
		case '13': $list_prod.=  'нитка'; break;
		}
		
		}
		$list_prod.=  "</div> </div>";
		$temp = explode("^", $row[16]);
		$eqq = "";
		FOR ($i = 0; $i < count($temp); $i++){
		$z = $i + 1;
		$part = explode("|", $temp[$i]);
		$mn = 1;
		IF ($row[28] == 'тыс.шт.') {
		$mn = 1000;
		}
		
		$size_mat = $part[5];
		IF ($part[28] != ""){
		$size_mat = $part[28];
		}
		$list_prod.=  "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>
		<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>";
		IF ($part[0] != "" AND  $part[0] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'>".$part[0].'</div> </div>';	} else { $list_prod.= "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'> Часть_".$z.'</div> </div>';}
		IF ($part[1] != "" AND  $part[1] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Размер изделия: </div><div class='col-md-10'>".$part[1].'</div> </div>';	}
		IF ($part[2] != "" AND  $part[2] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Кол-во стр: </div><div class='col-md-10'>".$part[2].'</div> </div>';	}
		IF ($part[3] != "" AND  $part[3] != '0' AND  $part[3] != 'Выберите') {  $eqq .= $part[3]."<br>"; $list_prod.=  "<div class = 'row'><div class='col-md-2'>Оборудование: </div><div class='col-md-4'>".$part[3]."</div> </div> <div class = 'row'><div class='col-md-2'>Цвет: </div><div class='col-md-4'>".$part[4].'</div> </div>';	}
		IF ($part[6] != "" AND  $part[6] != '0') { $mat = explode(":",$part[6]); $list_prod.=  "<div class = 'row'><div class='col-md-2'>Бумага: </div><div class='col-md-10'>".$mat[0].' '.$size_mat."</div> </div> <div class = 'row'><div class='col-md-2'>Кол-во листов : </div><div class='col-md-4'> ".$mat[1].'</div> </div>';	}
		IF ($part[7] != "" AND  $part[7] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Резка</div> </div>";	}
		IF ($part[8] != "" AND  $part[8] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Ламинирование: </div><div class='col-md-10'>".$part[8].'</div> </div>';	}
		IF ($part[9] != "" AND  $part[9] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Биговка: </div><div class='col-md-1'>".$part[9]."</div><div class='col-md-1'>".$part[9] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[10] != "" AND  $part[10] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Перфорация: </div><div class='col-md-1'>".$part[10]."</div><div class='col-md-1'>".$part[10] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[11] != "" AND  $part[11] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Скругление углов: </div><div class='col-md-1'>".$part[11]."</div><div class='col-md-1'>".$part[11] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[12] != "" AND  $part[12] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Отверстия: ".$part[13]." </div><div class='col-md-1'>".$part[12]."</div><div class='col-md-1'>".$part[12] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[14] != "" AND  $part[14] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Люверс: ".$part[15]."</div><div class='col-md-1'>".$part[14]."</div><div class='col-md-1'>".$part[14] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[16] != "" AND  $part[16] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Вырубка: </div><div class='col-md-1'>".$part[16]."</div><div class='col-md-1'>".$part[16] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[17] != "" AND  $part[17] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Конгрев: </div><div class='col-md-1'>".$part[17]."</div><div class='col-md-1'>".$part[17] * $row[13] * $mn.'</div> </div>';	}	
		IF ($part[18] != "" AND  $part[18] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Тиснение: </div><div class='col-md-1'>".$part[18]."</div><div class='col-md-1'>".$part[18] * $row[13] * $mn.'</div> </div>';	}
		IF ($list_prod1 != "") {
		$list_prod  .= "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div><div class = 'row'><div class='col-md-2'></div><div class='col-md-1'>На 1 изд.</div><div class='col-md-1'>На тираж</div> </div>";
		$list_prod  .= $list_prod1;
		
		}
		}
		
		
		}
		if ($row[11] == '11'){
		$query1="select Name from PR_OPER where  FIND_IN_SET(ID,'".$row[10]."')";
		$link = $row[8];
		$result1 = mysql_query($query1) or die($query1);
		$list_prod.=  "<div class = 'row'><div class='col-md-4'>";
		$list_prod.= "</div>
		</div> "	;	
		while ($row1 = mysql_fetch_row($result1)) { 
		$list_prod.=  "<div class = 'row'><div class='col-md-12'>".$row1[0]."</div> </div> ";
		$list_prod.= "</div>
		</div> <div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> 
		</div>
		</div><div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> 
		</div>
		</div> "	;	
		}	
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Наименование: </div><div class='col-md-9'>".$row[12]."</div> </div> ";
		IF ($row[28] == 'тыс.шт.') {
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".((double)$row[13] * 1000)."</div> </div> ";
		}
		ELSE {
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".$row[13]."</div> </div> ";
		}
		
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Размер: </div><div class='col-md-9'>".$row[14]." </div> </div> ";
		$cshivka = explode("|", $row[15]);
		IF ($cshivka[0] != "" AND $cshivka[0] != "0") {
		$list_prod.=  "<div class = 'row'><div class='col-md-5'>Переплет по ".$cshivka[1]." стороне; "; 
		
		switch ($cshivka[0]) {
		case '1': $list_prod.= 'пружина 6,4 мм'; break;
		case '2': $list_prod.=  'пружина 8,0 мм'; break;
		case '3': $list_prod.=  'пружина 9,5 мм'; break;
		case '4': $list_prod.=  'пружина 11,0 мм'; break;
		case '5': $list_prod.=  'пружина 12,7 мм'; break;
		case '6': $list_prod.=  'пружина 14,3 мм'; break;
		case '7': $list_prod.=  'скоба'; break;
		case '8': $list_prod.=  'Твердая обложка (PUR)'; break;
		case '9': $list_prod.=  'Твердая обложка (скобы)'; break;
		case '10': $list_prod.=  'Твердая обложка'; break;
		case '11': $list_prod.=  'Твердая обложка (пружина)'; break;
		case '12': $list_prod.=  'термоклей'; break;
		case '13': $list_prod.=  'нитка'; break;
		}
		
		}
		$list_prod.=  "</div> </div>";
		$temp = explode("^", $row[16]);
		$eqq = "";
		FOR ($i = 0; $i < count($temp); $i++){
		$z = $i + 1;
		$part = explode("|", $temp[$i]);
		
		$mn = 1;
		IF ($row[28] == 'тыс.шт.') {
		$mn = 1000;
		}
		$size_mat = $part[5];
		IF ($part[28] != ""){
		$size_mat = $part[28];
		}
		$list_prod.=  "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>
		<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>";
		IF ($part[0] != "" AND  $part[0] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'>".$part[0].'</div> </div>';	} else { $list_prod.= "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'> Часть_".$z.'</div> </div>';}
		IF ($part[1] != "" AND  $part[1] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Размер изделия: </div><div class='col-md-10'>".$part[1].'</div> </div>';	}
		IF ($part[2] != "" AND  $part[2] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Кол-во стр: </div><div class='col-md-10'>".$part[2].'</div> </div>';	}
		IF ($part[3] != "" AND  $part[3] != '0' AND  $part[3] != 'Выберите') {  $eqq .= $part[3]."<br>"; $list_prod.=  "<div class = 'row'><div class='col-md-2'>Оборудование: </div><div class='col-md-4'>".$part[3]."</div> </div> <div class = 'row'><div class='col-md-2'>Цвет: </div><div class='col-md-4'>".$part[4].'</div> </div>';	}
		IF ($part[6] != "" AND  $part[6] != '0') { $mat = explode(":",$part[6]); $list_prod.=  "<div class = 'row'><div class='col-md-2'>Бумага: </div><div class='col-md-10'>".$mat[0].' '.$size_mat."</div> </div> <div class = 'row'><div class='col-md-2'>Кол-во листов : </div><div class='col-md-4'> ".$mat[1].'</div> </div>';	}
		IF ($part[7] != "" AND  $part[7] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Резка</div> </div>";	}
		IF ($part[8] != "" AND  $part[8] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Ламинирование: </div><div class='col-md-10'>".$part[8].'</div> </div>';	}
		IF ($part[9] != "" AND  $part[9] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Биговка: </div><div class='col-md-1'>".$part[9]."</div><div class='col-md-1'>".$part[9] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[10] != "" AND  $part[10] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Перфорация: </div><div class='col-md-1'>".$part[10]."</div><div class='col-md-1'>".$part[10] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[11] != "" AND  $part[11] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Скругление углов: </div><div class='col-md-1'>".$part[11]."</div><div class='col-md-1'>".$part[11] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[12] != "" AND  $part[12] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Отверстия: ".$part[13]." </div><div class='col-md-1'>".$part[12]."</div><div class='col-md-1'>".$part[12] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[14] != "" AND  $part[14] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Люверс: ".$part[15]."</div><div class='col-md-1'>".$part[14]."</div><div class='col-md-1'>".$part[14] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[16] != "" AND  $part[16] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Вырубка: </div><div class='col-md-1'>".$part[16]."</div><div class='col-md-1'>".$part[16] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[17] != "" AND  $part[17] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Конгрев: </div><div class='col-md-1'>".$part[17]."</div><div class='col-md-1'>".$part[17] * $row[13] * $mn.'</div> </div>';	}	
		IF ($part[18] != "" AND  $part[18] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Тиснение: </div><div class='col-md-1'>".$part[18]."</div><div class='col-md-1'>".$part[18] * $row[13] * $mn.'</div> </div>';	}
		IF ($list_prod1 != "") {
		$list_prod  .= "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div><div class = 'row'><div class='col-md-2'></div><div class='col-md-1'>На 1 изд.</div><div class='col-md-1'>На тираж</div> </div>";
		$list_prod  .= $list_prod1;
		
		}
		}
		
		
		}
		
		} else {
		
		if ($row[11] == '12'){
		$link = $row[17];
		}
		
		
		
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Наименование: </div><div class='col-md-9'>".$row[12]."</div> </div> ";
		IF ($row[28] == 'тыс.шт.') {
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".((double)$row[13] * 1000)."</div> </div> ";
		}
		ELSE {
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".$row[13]."</div> </div> ";
		}
		
		$list_prod.=  "<div class = 'row'><div class='col-md-3'>Размер: </div><div class='col-md-9'>".$row[14]." </div> </div> ";
		$cshivka = explode("|", $row[15]);
		IF ($cshivka[0] != "" AND $cshivka[0] != "0") {
		$list_prod.=  "<div class = 'row'><div class='col-md-5'>Переплет по ".$cshivka[1]." стороне; "; 
		
		switch ($cshivka[0]) {
		case '1': $list_prod.= 'пружина 6,4 мм'; break;
		case '2': $list_prod.=  'пружина 8,0 мм'; break;
		case '3': $list_prod.=  'пружина 9,5 мм'; break;
		case '4': $list_prod.=  'пружина 11,0 мм'; break;
		case '5': $list_prod.=  'пружина 12,7 мм'; break;
		case '6': $list_prod.=  'пружина 14,3 мм'; break;
		case '7': $list_prod.=  'скоба'; break;
		case '8': $list_prod.=  'Твердая обложка (PUR)'; break;
		case '9': $list_prod.=  'Твердая обложка (скобы)'; break;
		case '10': $list_prod.=  'Твердая обложка'; break;
		case '11': $list_prod.=  'Твердая обложка (пружина)'; break;
		case '12': $list_prod.=  'термоклей'; break;
		case '13': $list_prod.=  'нитка'; break;
		}
		
		}
		$list_prod.=  "</div> </div>";
		$temp = explode("^", $row[16]);
		$eqq = "";
		FOR ($i = 0; $i < count($temp); $i++){
		$z = $i + 1;
		$part = explode("|", $temp[$i]);
		
		$mn = 1;
		IF ($row[28] == 'тыс.шт.') {
		$mn = 1000;
		}
		$size_mat = $part[5];
		IF ($part[28] != ""){
		$size_mat = $part[28];
		}
		$list_prod.=  "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>
		<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>";
		IF ($part[0] != "" AND  $part[0] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'>".$part[0].'</div> </div>';	} else { $list_prod.= "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'> Часть_".$z.'</div> </div>';}
		IF ($part[1] != "" AND  $part[1] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Размер изделия: </div><div class='col-md-10'>".$part[1].'</div> </div>';	}
		IF ($part[2] != "" AND  $part[2] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Кол-во стр: </div><div class='col-md-10'>".$part[2].'</div> </div>';	}
		IF ($part[3] != "" AND  $part[3] != '0' AND  $part[3] != 'Выберите') {  $eqq .= $part[3]."<br>"; $list_prod.=  "<div class = 'row'><div class='col-md-2'>Оборудование: </div><div class='col-md-4'>".$part[3]."</div> </div> <div class = 'row'><div class='col-md-2'>Цвет: </div><div class='col-md-4'>".$part[4].'</div> </div>';	}
		IF ($part[6] != "" AND  $part[6] != '0') { $mat = explode(":",$part[6]); $list_prod.=  "<div class = 'row'><div class='col-md-2'>Бумага: </div><div class='col-md-10'>".$mat[0].' '.$size_mat."</div> </div> <div class = 'row'><div class='col-md-2'>Кол-во листов : </div><div class='col-md-4'> ".$mat[1].'</div> </div>';	}
		IF ($part[7] != "" AND  $part[7] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Резка</div> </div>";	}
		IF ($part[8] != "" AND  $part[8] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Ламинирование: </div><div class='col-md-10'>".$part[8].'</div> </div>';	}
		IF ($part[9] != "" AND  $part[9] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Биговка: </div><div class='col-md-1'>".$part[9]."</div><div class='col-md-1'>".$part[9] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[10] != "" AND  $part[10] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Перфорация: </div><div class='col-md-1'>".$part[10]."</div><div class='col-md-1'>".$part[10] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[11] != "" AND  $part[11] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Скругление углов: </div><div class='col-md-1'>".$part[11]."</div><div class='col-md-1'>".$part[11] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[12] != "" AND  $part[12] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Отверстия: ".$part[13]." </div><div class='col-md-1'>".$part[12]."</div><div class='col-md-1'>".$part[12] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[14] != "" AND  $part[14] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Люверс: ".$part[15]."</div><div class='col-md-1'>".$part[14]."</div><div class='col-md-1'>".$part[14] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[16] != "" AND  $part[16] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Вырубка: </div><div class='col-md-1'>".$part[16]."</div><div class='col-md-1'>".$part[16] * $row[13] * $mn.'</div> </div>';	}
		IF ($part[17] != "" AND  $part[17] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Конгрев: </div><div class='col-md-1'>".$part[17]."</div><div class='col-md-1'>".$part[17] * $row[13] * $mn.'</div> </div>';	}	
		IF ($part[18] != "" AND  $part[18] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Тиснение: </div><div class='col-md-1'>".$part[18]."</div><div class='col-md-1'>".$part[18] * $row[13] * $mn.'</div> </div>';	}
		IF ($list_prod1 != "") {
		$list_prod  .= "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div><div class = 'row'><div class='col-md-2'></div><div class='col-md-2'>На 1 изд.</div><div class='col-md-2'>На тираж</div> </div>";
		$list_prod  .= $list_prod1;
		
		}
		}
		}

		$temp_pr_list = explode('|', $row['TEMP_PR']);

		echo "<td> $row[25]</td>";
		echo "<td> <input type='checkbox' name='chjob3' value='".$row[18]."'>" . $eqq." </td>";
		echo "<td>$row[0]</td>";
		echo "<td>$row[29]</td>";	
		echo "<td>".$row[1]."_".$row[19]."</td>";
		echo '<td>' . (isset($temp_pr_list[23]) && !empty($temp_pr_list[23]) ? $temp_pr_list[23] : 0) . '</td>';
		echo "<td>$row[2]</td>";
		echo "<td>$row[4]</td>";
		echo "<td>$row[5]</td>";
		echo "<td>$row[3]</td>";
		// echo "<td>$row[23]</td>";
		echo "<td>$row[24]</td>";
		echo "<td ><a onclick = down('$link','$row[1]_$row[19]')><span class = 'pull-right'><i class='glyphicon glyphicon-floppy-save'></i></<span></a>	</td>";
		echo "<td ><a onclick = back('$row[18]')><span class = 'pull-right'><i class='glyphicon glyphicon-tasks'></i></<span></a>	</td>";
		echo "<td ><a onclick = save('$row[18]','$link')><span class = 'pull-right'><i class='glyphicon glyphicon-paperclip'></i></<span></a>	</td>";
		
		echo "<td >".$list_prod."</td>";	
		echo "</tr>";
		}
		echo "</tbody>"; 
		?>
		
		</table>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>
		<div id="menu1" class="tab-pane fade">
		<div class="row"><div class="col-md-2">
		<button type='button' class='btn btn-default'  onclick='_prod_job()'>В работу</button>
		<button type='button' class='btn btn-default'  onclick='_prod_job_vozv()'>Возврат</button>
		</div>
		<?php if($admin != 5): ?>
			<div class="col-md-4">
				<label>Оборудование</label>
				<select style="position: absolute" onchange="EquipmentChange(this.value)">
					<option value="" selected>Все</option>
					<?php
						$sel_eq = "SELECT eq.ID, eq.EQ_NAME, eq.oper FROM equipment eq WHERE eq.oper<>'' AND eq.l_use=1 ORDER BY eq.EQ_NAME";
						$query_eq = mysql_query($sel_eq) or die($sel_eq);
						while($row_eq = mysql_fetch_array($query_eq)) {
							$sel_op = "SELECT op.ID, op.OPERATION_NAME FROM operations op WHERE op.ID in (".$row_eq["oper"].") and op.OPERATION_NAME like '%печать%'";
							$query_op = mysql_query($sel_op) or die($sel_op);
							if($row_op = mysql_fetch_array($query_op)) {
								echo "<option value='".$row_eq["EQ_NAME"]."'>".$row_eq["EQ_NAME"]."</option>";
							}
						}
					?>
				</select>
			</div>
		<?php endif; ?>
		
		
		
		<div class="col-lg-12">
		<div class="panel panel-default">
		<div id="baseDateControl">
		
		<div class="panel-body">
		<table id="tableListTask" class="table"  data-paging-size="50" data-show-toggle="false" data-paging="true" data-filtering="true" data-sorting="true"  >
		<thead>
		<tr><th data-sortable="false"><p style="width: 150px"><input type="checkbox" id="checkSelectAllListTask" name="checkSelectAllListTask" onchange="selectAll(this.checked, this.id)"><label for="checkSelectAllListTask">Все</label></p></th>
		<th>Дата сдачи</th>
		<th>Дата отправки в работу</th>
		<th>Номер счета</th>
		<th>Стоимость дизайна</th>
		<th>Наименование клиента</th>
		<th>Продукт</th>
		<th>Размер</th>
		<th>Менеджер</th>
		<!--	<th>Причина</th>-->
		<th>Комментарий</th> 
		<th></th>
		<th data-breakpoints="all" data-title="ОПИСАНИЕ:">ОПИСАНИЕ</th>
		</tr>
		</thead>
		<?php
		if ($admin == 5){
		$srttt = "10";
		}
		if ($admin == 7){
		$srttt = "11";
		}
		if ($admin == 6){
		$srttt = "12";
		}
		if ($admin == 4){
		$srttt = "12";
		}
		include "db.php";
		$query="select DATE_FORMAT(op.dates_rdy, '%Y-%m-%d %H:%i') dt, 
		op.ORDER_ID ,
		(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
		(select (select u.USER_FIO from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) men, 
		op.p_names,  op.size, op.fast,op.cl_file, op.print_diz, op.view_diz, op.view_press, op.status,op.p_names, op.total, op.size,op.cshivka,op.template, press_diz, id,num_prod_ord ,
		(select tt2.prob from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) prob,
		(select tt2.comm from log_task tt2 where tt2.id_prod = op.ID and tt2.status_new = op.status ORDER BY tt2.id DESC LIMIT 1) comm,
		op.units ,
		(select  DATE_FORMAT(lk.datetime, '%Y-%m-%d %H:%i') dt from log_task lk where lk.id_prod = op.id and lk.status_old = '' ORDER BY lk.id LIMIT 1) date_ot,
		op.comment,
		(select (select u.USER_LOGIN from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) login_,
		(select (select u.USER_PER from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) group_, op.TEMP_PR
		from order_product op where status in (".$srttt.") AND NOT EXISTS (select lt.flags from lock_task lt where lt.id_prod = op.id AND lt.flags = 1)";
		$result = mysql_query($query) or die($query);
		echo "<tbody>";
		while ($row = mysql_fetch_array($result)) { 
			$eqq = "";
			$list_prod = '';
			$list_prod1 = '';
			
			if ($row[11] != "12"){
			
			if ($row[11] == '10'){
				$query1="select Name from DIZ_OPER where  FIND_IN_SET(ID,'".$row[9]."')";
				$link = $row[7];
				$result1 = mysql_query($query1) or die($query1);
				$list_prod.=  "<div class = 'row'><div class='col-md-4'>";
				while ($row1 = mysql_fetch_row($result1)) { 
					$list_prod.=  "<div class = 'row'><div class='col-md-12'>".$row1[0]."</div> </div> ";
					if(!empty($row[24]))
						$list_prod.=  "<div class = 'row'><div class='col-md-12'><b>Комментарий: </b>".$row[24]."</div> </div> ";
				}	
			}
			if ($row[11] == '11'){
			$query1="select Name from PR_OPER where  FIND_IN_SET(ID,'".$row[10]."')";
			$link = $row[8];
			$result1 = mysql_query($query1) or die($query1);
			$list_prod.=  "<div class = 'row'><div class='col-md-4'>";
			$list_prod.= "</div>
			</div> "	;	
			while ($row1 = mysql_fetch_row($result1)) { 
			$list_prod.=  "<div class = 'row'><div class='col-md-12'>".$row1[0]."</div> </div> ";
			$list_prod.= "</div>
			</div> <div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> 
			</div>
			</div><div class = 'row'><div class='col-md-12'>&nbsp;</div> </div> 
			</div>
			</div> "	;	
			}	
			$list_prod.=  "<div class = 'row'><div class='col-md-3'>Наименование: </div><div class='col-md-9'>".$row[12]."</div> </div> ";
			IF ($row[22] == 'тыс.шт.') {
			$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".((double)$row[13] * 1000)."</div> </div> ";
			}
			ELSE {
			$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".$row[13]."</div> </div> ";
			}
			$list_prod.=  "<div class = 'row'><div class='col-md-3'>Размер: </div><div class='col-md-9'>".$row[14]." </div> </div> ";
			$cshivka = explode("|", $row[15]);
			IF ($cshivka[0] != "" AND $cshivka[0] != "0") {
			$list_prod.=  "<div class = 'row'><div class='col-md-5'>Переплет по ".$cshivka[1]." стороне; "; 
			
			switch ($cshivka[0]) {
			case '1': $list_prod.= 'пружина 6,4 мм'; break;
			case '2': $list_prod.=  'пружина 8,0 мм'; break;
			case '3': $list_prod.=  'пружина 9,5 мм'; break;
			case '4': $list_prod.=  'пружина 11,0 мм'; break;
			case '5': $list_prod.=  'пружина 12,7 мм'; break;
			case '6': $list_prod.=  'пружина 14,3 мм'; break;
			case '7': $list_prod.=  'скоба'; break;
			case '8': $list_prod.=  'Твердая обложка (PUR)'; break;
			case '9': $list_prod.=  'Твердая обложка (скобы)'; break;
			case '10': $list_prod.=  'Твердая обложка'; break;
			case '11': $list_prod.=  'Твердая обложка (пружина)'; break;
			case '12': $list_prod.=  'термоклей'; break;
			case '13': $list_prod.=  'нитка'; break;
			}
			
			}
			$list_prod.=  "</div> </div>";
			$temp = explode("^", $row[16]);	$eqq = "";
			FOR ($i = 0; $i < count($temp); $i++){
			
			$z = $i + 1;
			$part = explode("|", $temp[$i]);
			
			$mn = 1;
			IF ($row[22] == 'тыс.шт.') {
			$mn = 1000;
			}
			$size_mat = $part[5];
			IF ($part[28] != ""){
			$size_mat = $part[28];
			}
			$list_prod.=  "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>
			<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>";
			IF ($part[0] != "" AND  $part[0] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'>".$part[0].'</div> </div>';	} else { $list_prod.= "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'> Часть_".$z.'</div> </div>';}
			IF ($part[1] != "" AND  $part[1] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Размер изделия: </div><div class='col-md-10'>".$part[1].'</div> </div>';	}
			IF ($part[2] != "" AND  $part[2] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Кол-во стр: </div><div class='col-md-10'>".$part[2].'</div> </div>';	}
			IF ($part[3] != "" AND  $part[3] != '0' AND  $part[3] != 'Выберите') {  $eqq .= $part[3]."<br>"; $list_prod.=  "<div class = 'row'><div class='col-md-2'>Оборудование: </div><div class='col-md-4'>".$part[3]."</div> </div> <div class = 'row'><div class='col-md-2'>Цвет: </div><div class='col-md-4'>".$part[4].'</div> </div>';	}
			IF ($part[6] != "" AND  $part[6] != '0') { $mat = explode(":",$part[6]); $list_prod.=  "<div class = 'row'><div class='col-md-2'>Бумага: </div><div class='col-md-10'>".$mat[0].' '.$size_mat."</div> </div> <div class = 'row'><div class='col-md-2'>Кол-во листов : </div><div class='col-md-4'> ".$mat[1].'</div> </div>';	}
			IF ($part[7] != "" AND  $part[7] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Резка</div> </div>";	}
			IF ($part[8] != "" AND  $part[8] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Ламинирование: </div><div class='col-md-10'>".$part[8].'</div> </div>';	}
			IF ($part[9] != "" AND  $part[9] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Биговка: </div><div class='col-md-1'>".$part[9]."</div><div class='col-md-1'>".$part[9] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[10] != "" AND  $part[10] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Перфорация: </div><div class='col-md-1'>".$part[10]."</div><div class='col-md-1'>".$part[10] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[11] != "" AND  $part[11] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Скругление углов: </div><div class='col-md-1'>".$part[11]."</div><div class='col-md-1'>".$part[11] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[12] != "" AND  $part[12] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Отверстия: ".$part[13]." </div><div class='col-md-1'>".$part[12]."</div><div class='col-md-1'>".$part[12] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[14] != "" AND  $part[14] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Люверс: ".$part[15]."</div><div class='col-md-1'>".$part[14]."</div><div class='col-md-1'>".$part[14] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[16] != "" AND  $part[16] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Вырубка: </div><div class='col-md-1'>".$part[16]."</div><div class='col-md-1'>".$part[16] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[17] != "" AND  $part[17] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Конгрев: </div><div class='col-md-1'>".$part[17]."</div><div class='col-md-1'>".$part[17] * $row[13] * $mn.'</div> </div>';	}	
			IF ($part[18] != "" AND  $part[18] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Тиснение: </div><div class='col-md-1'>".$part[18]."</div><div class='col-md-1'>".$part[18] * $row[13] * $mn.'</div> </div>';	}
			IF ($list_prod1 != "") {
			$list_prod  .= "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div><div class = 'row'><div class='col-md-2'></div><div class='col-md-2'>На 1 изд.</div><div class='col-md-2'>На тираж</div> </div>";
			$list_prod  .= $list_prod1;
			
			}
			}
			
			
			}
			if ($row[11] == '12'){
			$link = $row[17];
			}
			
			
			
			
			
			} else {
			
			
			
			
			$list_prod.=  "<div class = 'row'><div class='col-md-3'>Наименование: </div><div class='col-md-9'>".$row[12]."</div> </div> ";
			IF ($row[22] == 'тыс.шт.') {
			$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".((double)$row[13] * 1000)."</div> </div> ";
			}
			ELSE {
			$list_prod.=  "<div class = 'row'><div class='col-md-3'>Тираж: </div><div class='col-md-9'>".$row[13]."</div> </div> ";
			}
			$list_prod.=  "<div class = 'row'><div class='col-md-3'>Размер: </div><div class='col-md-9'>".$row[14]." </div> </div> ";
			$cshivka = explode("|", $row[15]);
			IF ($cshivka[0] != "" AND $cshivka[0] != "0") {
			$list_prod.=  "<div class = 'row'><div class='col-md-5'>Переплет по ".$cshivka[1]." стороне; "; 
			
			switch ($cshivka[0]) {
			case '1': $list_prod.= 'пружина 6,4 мм'; 					break;
			case '2': $list_prod.= 'пружина 8,0 мм'; 					break;
			case '3': $list_prod.= 'пружина 9,5 мм'; 					break;
			case '4': $list_prod.= 'пружина 11,0 мм'; 				break;
			case '5': $list_prod.= 'пружина 12,7 мм'; 				break;
			case '6': $list_prod.= 'пружина 14,3 мм'; 				break;
			case '7': $list_prod.= 'скоба'; 								break;
			case '8': $list_prod.= 'Твердая обложка (PUR)'; 		break;
			case '9': $list_prod.= 'Твердая обложка (скобы)'; 		break;
			case '10': $list_prod.= 'Твердая обложка'; 				break;
			case '11': $list_prod.= 'Твердая обложка (пружина)'; 	break;
			case '12': $list_prod.= 'термоклей'; 						break;
			case '13': $list_prod.= 'нитка'; 							break;
			}
			
			}
			$list_prod.=  "</div> </div>";
			$temp = explode("^", $row[16]);
			$eqq = "";
			FOR ($i = 0; $i < count($temp); $i++){
			$z = $i + 1;
			$part = explode("|", $temp[$i]);
			
			$mn = 1;
			IF ($row[22] == 'тыс.шт.') {
			$mn = 1000;
			}
			$size_mat = $part[5];
			IF ($part[28] != ""){
			$size_mat = $part[28];
			}
			$list_prod.=  "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>
			<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>";
			IF ($part[0] != "" AND  $part[0] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'>".$part[0].'</div> </div>';	} else { $list_prod.= "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-10'> Часть_".$z.'</div> </div>';}
			IF ($part[1] != "" AND  $part[1] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Размер изделия: </div><div class='col-md-10'>".$part[1].'</div> </div>';	}
			IF ($part[2] != "" AND  $part[2] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Кол-во стр: </div><div class='col-md-10'>".$part[2].'</div> </div>';	}
			IF ($part[3] != "" AND  $part[3] != '0' AND  $part[3] != 'Выберите') {  $eqq .= $part[3]."<br>"; $list_prod.=  "<div class = 'row'><div class='col-md-2'>Оборудование: </div><div class='col-md-4'>".$part[3]."</div> </div> <div class = 'row'><div class='col-md-2'>Цвет: </div><div class='col-md-4'>".$part[4].'</div> </div>';	}
			IF ($part[6] != "" AND  $part[6] != '0') { $mat = explode(":",$part[6]); $list_prod.=  "<div class = 'row'><div class='col-md-2'>Бумага: </div><div class='col-md-10'>".$mat[0].' '.$size_mat."</div> </div> <div class = 'row'><div class='col-md-2'>Кол-во листов : </div><div class='col-md-4'> ".$mat[1].'</div> </div>';	}
			IF ($part[7] != "" AND  $part[7] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Резка</div> </div>";	}
			IF ($part[8] != "" AND  $part[8] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Ламинирование: </div><div class='col-md-10'>".$part[8].'</div> </div>';	}
			IF ($part[9] != "" AND  $part[9] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Биговка: </div><div class='col-md-1'>".$part[9]."</div><div class='col-md-1'>".$part[9] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[10] != "" AND  $part[10] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Перфорация: </div><div class='col-md-1'>".$part[10]."</div><div class='col-md-1'>".$part[10] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[11] != "" AND  $part[11] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Скругление углов: </div><div class='col-md-1'>".$part[11]."</div><div class='col-md-1'>".$part[11] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[12] != "" AND  $part[12] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Отверстия: ".$part[13]." </div><div class='col-md-1'>".$part[12]."</div><div class='col-md-1'>".$part[12] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[14] != "" AND  $part[14] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Люверс: ".$part[15]."</div><div class='col-md-1'>".$part[14]."</div><div class='col-md-1'>".$part[14] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[16] != "" AND  $part[16] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Вырубка: </div><div class='col-md-1'>".$part[16]."</div><div class='col-md-1'>".$part[16] * $row[13] * $mn.'</div> </div>';	}
			IF ($part[17] != "" AND  $part[17] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Конгрев: </div><div class='col-md-1'>".$part[17]."</div><div class='col-md-1'>".$part[17] * $row[13] * $mn.'</div> </div>';	}	
			IF ($part[18] != "" AND  $part[18] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Тиснение: </div><div class='col-md-1'>".$part[18]."</div><div class='col-md-1'>".$part[18] * $row[13] * $mn.'</div> </div>';	}
			IF ($list_prod1 != "") {
			$list_prod  .= "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div><div class = 'row'><div class='col-md-2'></div><div class='col-md-1'>На 1 изд.</div><div class='col-md-1'>На тираж</div> </div>";
			$list_prod  .= $list_prod1;
			
			}
			}
			}
			
			$class = "";
			if($row['login_'] == 'admin' && $admin == 7) {
				$class = " style='background-color:#ffb4b4'";
			}

            $temp_pr_list = explode('|', $row['TEMP_PR']);

			echo "<tr".$class."><td><input type='checkbox' name='chjob5' value='".$row[18]."' onchange='ChangeOneEquipment()'>".$eqq."</td>";
			echo "<td>$row[0]</td>";
			echo "<td>$row[23]</td>";
			echo "<td>".$row[1]."_".$row[19]."</td>";
            echo '<td>' . (isset($temp_pr_list[23]) && !empty($temp_pr_list[23]) ? $temp_pr_list[23] : 0) . '</td>';
			echo "<td>$row[2]</td>";
			echo "<td>$row[4]</td>";
			echo "<td>$row[5]</td>";
			echo "<td>$row[3]</td>";
			// echo "<td>$row[20]</td>";
			echo "<td>$row[21]</td>";
			echo "<td ><a onclick = lock_task('$row[18]')><button type='button' class='btn btn-success btn-sm '>Взять в работу</button></a>	</td>";
			//echo "<td ><a onclick = down('$link')><span class = 'pull-right'><i class='glyphicon glyphicon-floppy-save'></i></<span></a>	</td>";
			echo "<td >".$list_prod."</td>";	
			echo "</tr>";
		}
		echo "</tbody>"; 
		?>
		
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
		
		
		
		
		
		<div id="downfile" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" data-keyboard="true">
		<div class="modal-dialog modal-lg">
		<div class="modal-content">
		<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		<h4 class="modal-title">Файлы</h4>
		</div>
		<div class="modal-body">
		
		<div id = "hh1" >	
		
		</div>
		<div id = "hh" >	
		
		</div>
		<button onclick = 'all_file_down()'  type="button" class="btn btn-default">Скачать Все</button>
		</div> <!-- modal-body -->
		
		<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		</div> <!-- modal-footer -->
		</div>
		</div>
		</div>
		
		<div id="savetask" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" data-keyboard="true">
		<div class="modal-dialog modal-lg1">
		<div class="modal-content">
		<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		Передача файлов</h4>
		</div>
		<div class="modal-body">
		<form  class='form-signin' method='post' action='pg/.php' enctype='multipart/form-data' id = 'forms'>
		<input id='id_savetask'  name='id_savetask' type='hidden' value=''>
		<div id="container">
		<div class='row' >
		<div class="col-md-2"><label >Кому:</label></div>
		<div class="col-md-3">
		<select id = 'stats' name='stats'>
		<option value= '20' >Менеджер</option>
		<option value= '12' >Печатник</option>
		<option value= '11' >Препресс</option>
		<option value= '1' >Цех</option>
		<option value= '10' >Дизайнер</option>
		<option value= '2' >ГОТОВО</option>
		<option value= '0' >Менеджер_</option>
		<option value= '21' >Cогласование с клиентом</option>
		</select>
		</div>
		</div>
		<div class='row' >
		<div class="col-md-2"><label >Причина:</label></div>
		<div class="col-md-3">
		<select id = 'stats1' name='stats1'>
		<option>ГОТОВО</option>
		<option>Нет файла</option>
		<option>Ошибки в заказе</option>
		<option>Брак</option>
		<option>Другое</option>
		</select>
		</div>
		</div>
		<div class='row'><div class="col-md-12">		<div class="checkbox">
		<label>
		<input type="checkbox" name="chDel2" id = "chDel2">Просто передача!
		</label>
		</div>					</div></div>	
		<div class='row'><div class='col-md-2'><div class='block1'><label >Комментарий:</label></div></div>
		<div class='col-md-9'><textarea class="form-control" rows="3" id = 'list_comm' name = 'list_comm' ></textarea>	</div></div>
		<div class='row'><div class="col-md-2"><label >Файлы:</label></div><div class="col-md-4"><label class='btn btn-default btn-file btn-sm'>
		Выберете файлы...<input name='file[]' type='file' multiple='true' style='display: none;' id = 'filea'/>	
		</div>
		</label></div>	
		
		<div class='row' id="listss"></div>	
		
		<div class='row' ><b>Дизайнера файлы:</b></div>	
		<div class='row' id="info_prod"></div>	
		<div class='row' ><b>Препресса файлы:</b></div>	
		<div class='row' id="info_prod1"></div>	
		</div>
		
		</form>
		
		</div> <!-- modal-body -->
		<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		<a onclick="save_tst()"><button type="button" class="btn btn-primary" >Передать</button></a>
		</div> <!-- modal-footer -->
		</div>
		</div>
		</div>
		<div id="basktask" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" data-keyboard="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		<h4 class="modal-title">ВНИМАНИЕ!</h4>
		</div>
		<div class="modal-body">
		Вы действительно хотите отказаться от работы?
		<input id='id_back' type='hidden' value=''>
		</div> <!-- modal-body -->
		
		<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		<a onclick="back_tst()"><button type="button" class="btn btn-primary" >Да</button></a>
		</div> <!-- modal-footer -->
		</div>
		</div>
		</div>
		
		
		
		<footer class="footer">
		<p>&copy; Company 2016</p>
		</footer>
		<script src="../vendor/jquery/jquery.min.js"></script> 
		<script src="../js/jquery-ui.min.js"></script>
		<!--<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
		<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script> -->
		<script src="../js/funJs.js"></script>
		<script src="../vendor/bootstrap/js/bootstrap-select.js" type = "text/javascript"></script>
		<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="../vendor/metisMenu/metisMenu.min.js"></script>
		<script src="../dist/js/sb-admin-2.js"></script>
		<script src="../vendor/footable/js/footable.min.js"></script>
		<script type="text/javascript">
		$(document).ready(function() {
		user_per = '<?echo $admin;?>';
		
		switch(user_per) {
		case '3':  break;
		case '4': 
		$("#stats [value='20']").attr("selected", "selected"); 
		break;
		case '5':  	
		$("#stats [value='12']").attr("disabled", "disabled");
		$("#stats [value='11']").attr("disabled", "disabled");
		$("#stats [value='1']").attr("disabled", "disabled");
		$("#stats [value='10']").attr("disabled", "disabled");
		$("#stats [value='0']").attr("selected", "selected");
		$("#stats [value='2']").attr("disabled", "disabled");
		$("#stats [value='20']").attr("disabled", "disabled");
		
		break;
		case '6':  	
		$("#stats [value='0']").attr("disabled", "disabled");
		$("#stats [value='1']").attr("selected", "selected");
		break;
		case '7':  	
		
		//$("#stats [value='1']").attr("disabled", "disabled");
		$("#stats [value='11']").attr("disabled", "disabled");
		$("#stats [value='12']").attr("selected", "selected");
		$("#stats [value='10']").attr("disabled", "disabled");
		//	$("#stats [value='2']").attr("disabled", "disabled");
		$("#stats [value='0']").attr("disabled", "disabled");
		$("#stats [value='21']").attr("disabled", "disabled");
		
		break;
		}
		
		});
		</script>
		<script>
		jQuery(function($){
		$('.table').footable({
		"paging": {
		"size": 1000
		}
		});
		});
		</script>
		<script>
		
		//Выбрать все работы
		function selectAll(e, id) {
			if(e) {
				var table = null;
				if(id == "checkSelectAllListTask")
					table = document.getElementById("tableListTask");
				else
					table = document.getElementById("tableListTask1");
				
				if(table == null)
					return;
				
				var tds = table.getElementsByTagName('td');
				
				if(tds.length <= 0) return;
				for(var i = 0; i < tds.length; i++) {
					var input = tds[i].getElementsByTagName('input');
					var tr = tds[i].parentNode;
					if(input[0] && input[0].hasAttribute('type') && input[0].getAttribute('type') == "checkbox" && tr.style.display != 'none') {
						input[0].checked = true;
						input[0].dispatchEvent(new Event('change'));
					}
				}
			}
			else {
				var table = null;
				if(id == "checkSelectAllListTask")
					table = document.getElementById("tableListTask");
				else
					table = document.getElementById("tableListTask1");
				
				var tds = table.getElementsByTagName('td');
				
				if(tds.length <= 0) return;
				for(var i = 0; i < tds.length; i++) {
					var input = tds[i].getElementsByTagName('input');
					var tr = tds[i].parentNode;
					if(input[0] && input[0].hasAttribute('type') && input[0].getAttribute('type') == "checkbox" && tr.style.display != 'none') {
						input[0].checked = false;
						input[0].dispatchEvent(new Event('change'));
					}
				}
			}
		}
		
		function handleFileSelect(evt) {
		var files = evt.target.files; // FileList object
		
		// files is a FileList of File objects. List some properties.
		var output = [];
		for (var i = 0, f; f = files[i]; i++) {
		
		
      /*output.push('<li><strong>', f.name, '</strong> (', f.type || 'n/a', ') - ',
		f.size, ' bytes, last modified: ',
		f.lastModifiedDate.toLocaleDateString(), '</li>');*/
		
		var lastModifiedDate_int = f.lastModified;
		var lastModifiedDate = new Date(lastModifiedDate_int);

	output.push('<li><strong>', f.name, '</strong> (', f.type || 'n/a', ') - ',
		f.size, ' bytes, last modified: ',
		lastModifiedDate.toLocaleString(), '</li>');
		}
		document.getElementById('listss').innerHTML = '';
		document.getElementById('listss').innerHTML = '<ul>' + output.join('') + '</ul>';
		}
		
		document.getElementById('filea').addEventListener('change', handleFileSelect, false);
		</script>
		<script type="text/javascript">
		$(".close").click(function(){document.getElementById('err').style.display = 'none';});
		</script>
		<script>
		function down(name,name_ord){
		
		$.ajax({
		type: "POST",
		url: 'pg/_list_file.php',
		data: {
		name : name
		},  success:function (data) {//возвращаемый результат от сервера
		document.getElementById('hh1').innerHTML = '';
		document.getElementById('hh1').innerHTML = '<h4>' + name_ord + '</h4><hr>';
		document.getElementById('hh').innerHTML = data;
		$('#downfile').modal('show'); 
		}
		});
		}
		function back(id){
		document.getElementById("id_back").value = id;
		$('#basktask').modal('show');	
		
	   } 
		
		function back_tst(){
		id = document.getElementById("id_back").value;
		$.ajax({
		type: "POST",
		url: 'pg/_back_down.php',
		data: {
		id : id
		},  success:function (data) {//возвращаемый результат от сервера
		location.reload(true)
		}
		});
	   } 
		function save_tst(){
			user_perm = '<?echo $srttt;?>';			
			if (user_perm != '12'  && user_perm != "1,11,10,12" && document.getElementById('chDel2').checked == false){
				if ( $("#filea").val() == '' ){
					alert ( "Не выбраны файлы!" ) ;
					return;
				}
			}
			
			var form = $('#forms')[0];
			var datass = new FormData(form);
			
			//создаем форму и процент загрузки
			var mod_div = document.createElement('div');
			mod_div.setAttribute('id', "modal_upload");
			mod_div.setAttribute('style', "position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 2000;background: rgba(29,37,49,.9);");
			document.getElementsByTagName('body')[0].appendChild(mod_div);
			var head = document.createElement('h3');
			head.setAttribute('style', "color: #f7e946;top: 40%;position: relative;left: 25%;");
			head.setAttribute('id', "progress_label");
			head.appendChild(document.createTextNode("Загрузка 0%"));
			mod_div.appendChild(head);
			var pr_div = document.createElement('div');
			pr_div.setAttribute('class', "progress");
			pr_div.setAttribute('style', "top: 40%;position: relative;width: 50%;left: 25%;background-color: black;");
			mod_div.appendChild(pr_div);
			var progressbar_div = document.createElement('div');
			progressbar_div.setAttribute('id', "_progressbar");
			progressbar_div.setAttribute('class', "progress-bar");
			progressbar_div.setAttribute('role', "progressbar");
			progressbar_div.setAttribute('style', "width: 0;background-color:#f7e946;");
			progressbar_div.setAttribute('aria-valuenow', "0");
			progressbar_div.setAttribute('aria-valuemin', "0");
			progressbar_div.setAttribute('aria-valuemax', "100");
			pr_div.appendChild(progressbar_div);
			
			var xhr = new XMLHttpRequest();
			// обработчик для закачки
			xhr.upload.onprogress = function(event) {
				var pr = Math.floor(parseFloat(event.loaded) * 100 / parseFloat(event.total));
				removeAllTextNodes(document.getElementById("progress_label"));
				document.getElementById("progress_label").appendChild(document.createTextNode("Загрузка " + pr + "%"));
				document.getElementById("_progressbar").setAttribute('style', "width:" + pr + "%;background-color:#f7e946;");
				document.getElementById("_progressbar").setAttribute('aria-valuenow', pr);
			}

			// обработчики успеха и ошибки
			// если status == 200, то это успех, иначе ошибка
			xhr.onload = xhr.onerror = function() {
				//remove(document.getElementById("modal_upload"));
				if (this.status == 200) {
					location.reload(true);
				} else {
					alert("error " + this.status);
				}
			};

			xhr.open("POST", "pg/_save_task.php", true);
			xhr.send(datass);
				
				/*$.ajax({
					type: "POST",
					enctype: 'multipart/form-data',
					url: 'pg/_save_task.php',
					data: datass,
					processData: false,
					contentType: false,
					cache: false,	 
					success: function(data) {
						location.reload(true)
					}
				});*/
		
	    } 
		
		
		function save(id, str){
		document.getElementById('listss').innerHTML = '';
		document.getElementById("id_savetask").value = id;
		//console.log(str);
		str1 = str.split('/');
		//console.log(str1);
		
		str_new = str1[0] + "/" + str1[1] + "/" + str1[2] + "/" + str1[3] + "/press";	
		
		str_new1 = str1[0] + "/" + str1[1] + "/" + str1[2] + "/" + str1[3] + "/diz";	
		//console.log(str_new);
		//	console.log(str_new1);
		
		$.ajax({
			type: "GET",
			url: 'ajax_php_sql.php',
			data: {
				link : str_new1,
				flag : '34'
			},  
			success:function (data) {//возвращаемый результат от сервера
			
				document.getElementById('info_prod').innerHTML = "";
				document.getElementById('info_prod').innerHTML = data;
				$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					link : str_new,
						flag : '34'
					},  
					success:function (data) {//возвращаемый результат от сервера
						
						document.getElementById('info_prod1').innerHTML = "";
						document.getElementById('info_prod1').innerHTML = data;
						$('#savetask').modal('show');		
					}
				});
			}
		});

	   }

		function removeAllTextNodes(node) {
			if(!node)
				return;
			if (node.nodeType === 3) {
				node.parentNode.removeChild(node);
			} else if (node.childNodes) {
				for (var i = node.childNodes.length; i--;) {
					removeAllTextNodes(node.childNodes[i]);
				}
			}
		}	
		
		function del_file(put,pol){
		$.ajax({
		type: "GET",
		url: 'ajax_php_sql.php',
		data: {
		put :  put,
		flag : '5'
		},  success:function (data) {//возвращаемый результат от сервера
		
		document.getElementById(pol).style.display = 'none';
		}
		});
		}
		function lock_task(id){
		users = '<?echo $login?>';
		$.ajax({
		type: "GET",
		url: 'pg/_lock_task.php',
		data: {
		id : id,
		user : users
		},  success:function (data) {//возвращаемый результат от сервера
		location.reload(true)
		
		}
		});
		
		}
		function _prod_job(){
		var nodeList = document.getElementsByName('chjob5');
		var array = Array.prototype.slice.call(nodeList);
		str_id = "";
		for (var i = 0; i < array.length; i++) {
		if (array[i].checked) {
		str_id = str_id + array[i].value  + ",";
		}
		}
		str_id = str_id.slice(0, -1);
		
		if (str_id != ""){
		users = '<?echo $login?>';
		$.ajax({
		type: "GET",
		url: 'pg/_lock_task.php',
		data: {
		id : str_id,
		user : users
		},  success:function (data) {//возвращаемый результат от сервера
		
		location.reload(true)
		
		}
		
		});
		
		
		}else {
		alert('не выбраны продукты!')
		}
		} 
		function _prod_job_vozv(){
		var nodeList = document.getElementsByName('chjob5');
		var array = Array.prototype.slice.call(nodeList);
		str_id = "";
		for (var i = 0; i < array.length; i++) {
		if (array[i].checked) {
		str_id = str_id + array[i].value  + ",";
		}
		}
		str_id = str_id.slice(0, -1);
		
		if (str_id != ""){
		users = '<?echo $login?>';
		$.ajax({
		type: "GET",
		url: 'ajax_php_sql.php',
		data: {
		id : str_id,
		flag: '218',
		},  success:function (data) {//возвращаемый результат от сервера
		
		location.reload(true)
		
		}
		
		});
		
		
		}else {
		alert('не выбраны продукты!')
		}
		}
		function _prod_job_ceh(){
		var nodeList = document.getElementsByName('chjob3');
		var array = Array.prototype.slice.call(nodeList);
		str_id = "";
		for (var i = 0; i < array.length; i++) {
		if (array[i].checked) {
		str_id = str_id + array[i].value  + ",";
		}
		}
		str_id = str_id.slice(0, -1);
		
		if (str_id != ""){
		
		$.ajax({
		type: "GET",
		url: 'ajax_php_sql.php',
		data: {
		id : str_id,
		flag: '20'
		},  success:function (data) {//возвращаемый результат от сервера
		location.reload(true)
		
		}
		});
		
		
		}else {
		alert('не выбраны продукты!')
		}
		}	 	 
		function _prod_job_rdy(){
		var nodeList = document.getElementsByName('chjob3');
		var array = Array.prototype.slice.call(nodeList);
		str_id = "";
		for (var i = 0; i < array.length; i++) {
		if (array[i].checked) {
		str_id = str_id + array[i].value  + ",";
		}
		}
		str_id = str_id.slice(0, -1);
		
		if (str_id != ""){
		
		$.ajax({
		type: "GET",
		url: 'ajax_php_sql.php',
		data: {
		id : str_id,
		flag: '217'
		},  success:function (data) {//возвращаемый результат от сервера
		location.reload(true)
		
		}
		});
		
		
		}else {
		alert('не выбраны продукты!')
		}
		}
		
			function _prod_job_return(){
		var nodeList = document.getElementsByName('chjob3');
		var array = Array.prototype.slice.call(nodeList);
		str_id = "";
		for (var i = 0; i < array.length; i++) {
		if (array[i].checked) {
		str_id = str_id + array[i].value  + ",";
		}
		}
		str_id = str_id.slice(0, -1);
		
		if (str_id != ""){
		
		$.ajax({
		type: "GET",
		url: 'ajax_php_sql.php',
		data: {
		id : str_id,
		flag: '230'
		},  success:function (data) {//возвращаемый результат от сервера
		location.reload(true)
		
		}
		});
		
		
		}else {
		alert('не выбраны продукты!')
		}
		}
		function _prod_job_print(){
			var nodeList = document.getElementsByName('chjob3');
			var array = Array.prototype.slice.call(nodeList);
			str_id = "";
			for (var i = 0; i < array.length; i++) {
				if (array[i].checked) {
					str_id = str_id + array[i].value  + ",";
				}
			}
			str_id = str_id.slice(0, -1);
			
			if (str_id != "") {
			
				$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id : str_id,
					flag: '228'
					},  success:function (data) {//возвращаемый результат от сервера
						location.reload(true)
					
					}
				});
			
			
			}else {
				alert('не выбраны продукты!')
			}
		}
		
		function _prod_job_print_cech(){
			var nodeList = document.getElementsByName('chjob3');
			var array = Array.prototype.slice.call(nodeList);
			str_id = "";
			for (var i = 0; i < array.length; i++) {
				if (array[i].checked) {
					str_id = str_id + array[i].value  + ",";
				}
			}
			str_id = str_id.slice(0, -1);
			
			if (str_id != "") {
			
				$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id : str_id,
					flag: '228_1'
					},  success:function (data) {//возвращаемый результат от сервера
						location.reload(true)
					
					}
				});
			
			
			}else {
				alert('не выбраны продукты!')
			}
		}
		
		function all_file_down(){
		var nodeList = document.getElementsByName('down_all');
		var array = Array.prototype.slice.call(nodeList); 
		for (var i = 0; i < array.length; i++) {
		//console.log(array[i]);
		array[i].click();
		/*var link = document.createElement('a');
		link.setAttribute('href',array[i].href);
		link.setAttribute('download',array[i].text);
		onload=link.click();*/
		}
		
		
		}
		
		//выбор оборудования
		function EquipmentChange(val) {
			var value = val.toLowerCase();
			$("#tableListTask tbody tr").filter(function() {
			  $(this).toggle($(this).text().toLowerCase().indexOf(value) == 0)
			});
		}
		
		//когда выбрана строка, то смотрим или все элеметы в таблице выделены
		function ChangeOneEquipment() {
			var table = document.getElementById("tableListTask");
			if(!table)
				return;
			var tbody = table.getElementsByTagName('tbody')[0];
			if(!tbody)
				return;
			var tds = tbody.getElementsByTagName('td'); 
			if(tds.length <= 0) return;
			var flag = false;
			for(var i = 0; i < tds.length; i++) {
				var input = tds[i].getElementsByTagName('input');
				var tr = tds[i].parentNode;
				if(input[0] && input[0].hasAttribute('type') && input[0].getAttribute('type') == "checkbox" && input[0].checked == false) {
					flag = true;
					break;
				}
			}
			console.log(document.getElementsByName("checkSelectAllListTask"));
			if(flag)
				document.getElementsByName("checkSelectAllListTask")[1].checked = false;
			else
				document.getElementsByName("checkSelectAllListTask")[1].checked = true;
		}
		
		</script>
		
		</body>
		
		</html>
				