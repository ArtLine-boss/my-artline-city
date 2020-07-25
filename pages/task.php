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
		.modal-lg
		{
		height:90%;
		width:90%;
		margin-left: 5%; 
		
		}
		</style>
		<script>
		window.addEventListener("DOMContentLoaded", function() {
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
		});
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
		<h2 class="page-header">Производство</h2>
		</div>
		</div>
		
		<ul class="nav nav-tabs" id="myTab">
		<li class="active"><a href="#home" data-toggle="tab">Заявки</a></li>
		<li><a href="#profile" data-toggle="tab">Заказы менеджеров</a></li>
		
		</ul>
		<div class="tab-content">
		<div class="tab-pane active" id="home"> 	
		<div class="row">
		<div class="col-md-4">
		<button type='button' class='btn btn-default'  onclick='_prod_rdy()'>Готово</button>
		</div>
		</div>
		<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		
		<div id="baseDateControl">
		
		<div class="panel-body">
		<table class="table"  data-show-toggle="false" data-paging="true" data-filtering="true" data-sorting="true"  >
		<thead>
		<tr>
		<th></th>
		<th>Дата</th>
		<th>Номер счета</th>
		<th>Наименование клиента</th>
		<th>Продукт</th>
		<th>Тираж</th>
		<th>Размер</th>
		<th>Срочность</th>
		<th>Менеджер</th>
		<th>Статус</th>
		<th></th>
		<th></th>
		<th></th>
		
		<th data-breakpoints="all" data-title="ОПИСАНИЕ:">ОПИСАНИЕ</th>
		</tr>
		</thead>
		<?php
		include "db.php";
		$query="select DATE_FORMAT(op.dates_rdy, '%Y.%m.%d %H:%i') dt, op.ORDER_ID , (select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,(select (select u.USER_FIO from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) men,op.p_names, op.total, op.size,op.cshivka, op.fast,op.template , op.status , id, num_prod_ord from order_product op where status = 11 OR status = 1 OR status = 12";
		$result = mysql_query($query) or die($query);
		echo "<tbody>";
		while ($row = mysql_fetch_row($result)) { 
		$list_prod = '';
		$list_prod1 = '';
		echo "<td><input type='checkbox' name='chjob' value='".$row[11]."'></td>";
		echo "<td>$row[0]</td>";
		echo "<td>".$row[1]."_".$row[12]."</td>";
		echo "<td>$row[2]</td>";
		echo "<td>$row[4]</td>";
		echo "<td>$row[5]</td>";
		echo "<td>$row[6]</td>";
		switch ($row[8]) {
		case '1': echo '<td>ОБЫЧНО</td>'; break;
		case '1.2': echo '<td>СРОЧНО</td>'; break;
		case '1.5': echo '<td>ОЧЕНЬ СРОЧНО</td>'; break;
		}
		echo "<td>$row[3]</td>";
		IF($row[10] == "11"){
		$pree = "На препрессе";
		}
		IF($row[10] == "12"){
		$pree = "На печати";
		}
		IF($row[10] == "1"){
		$pree = "В работе";
		}
		echo "<td>$pree</td>";
		$list_prod.=  "<div class = 'row'><div class='col-md-2'>Наименование: </div><div class='col-md-8'>".$row[4]."</div> </div> ";
		$list_prod.=  "<div class = 'row'><div class='col-md-2'>Тираж: </div><div class='col-md-8'>".$row[5]."</div> </div> ";
		$list_prod.=  "<div class = 'row'><div class='col-md-2'>Размер: </div><div class='col-md-8'>".$row[6]." </div> </div> ";
		$cshivka = explode("|", $row[7]);
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
		$temp = explode("^", $row[9]);
		FOR ($i = 0; $i < count($temp); $i++){
		$z = $i + 1;
		$part = explode("|", $temp[$i]);
		
		$list_prod.=  "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>
		<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div>";
		IF ($part[0] != "" AND  $part[0] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-8'>".$part[0].'</div> </div>';	} else { $list_prod.= "<div class = 'row'><div class='col-md-2'>Наим. части: </div><div class='col-md-8'> Часть_".$z.'</div> </div>';}
		IF ($part[1] != "" AND  $part[1] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Размер изделия: </div><div class='col-md-1'>".$part[1].'</div> </div>';	}
		IF ($part[2] != "" AND  $part[2] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Кол-во стр: </div><div class='col-md-1'>".$part[2].'</div> </div>';	}
		IF ($part[3] != "" AND  $part[3] != '0' AND  $part[3] != 'Выберите') { $list_prod.=  "<div class = 'row'><div class='col-md-6'>Оборудование: ".$part[3].'; Цвет: '.$part[4].'</div> </div>';	}
		IF ($part[6] != "" AND  $part[6] != '0') { $mat = explode(":",$part[6]); $list_prod.=  "<div class = 'row'><div class='col-md-6'>Бумага: ".$mat[0].'; '.$part[5].'; Кол-во листов : '.$mat[1].'</div> </div>';	}
		IF ($part[7] != "" AND  $part[7] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Резка</div> </div>";	}
		IF ($part[8] != "" AND  $part[8] != '0') { $list_prod.=  "<div class = 'row'><div class='col-md-2'>Ламинирование: </div><div class='col-md-1'>".$part[8].'</div> </div>';	}
		IF ($part[9] != "" AND  $part[9] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Биговка: </div><div class='col-md-1'>".$part[9]."</div><div class='col-md-1'>".$part[9] * $row[5].'</div> </div>';	}
		IF ($part[10] != "" AND  $part[10] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Перфорация: </div><div class='col-md-1'>".$part[10]."</div><div class='col-md-1'>".$part[10] * $row[5].'</div> </div>';	}
		IF ($part[11] != "" AND  $part[11] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Скругление углов: </div><div class='col-md-1'>".$part[11]."</div><div class='col-md-1'>".$part[11] * $row[5].'</div> </div>';	}
		IF ($part[12] != "" AND  $part[12] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Отверстия: ".$part[13]." </div><div class='col-md-1'>".$part[12]."</div><div class='col-md-1'>".$part[12] * $row[5].'</div> </div>';	}
		IF ($part[14] != "" AND  $part[14] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Люверс: ".$part[15]."</div><div class='col-md-1'>".$part[14]."</div><div class='col-md-1'>".$part[14] * $row[5].'</div> </div>';	}
		IF ($part[16] != "" AND  $part[16] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Вырубка: </div><div class='col-md-1'>".$part[16]."</div><div class='col-md-1'>".$part[16] * $row[5].'</div> </div>';	}
		IF ($part[17] != "" AND  $part[17] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Конгрев: </div><div class='col-md-1'>".$part[17]."</div><div class='col-md-1'>".$part[17] * $row[5].'</div> </div>';	}	
		IF ($part[18] != "" AND  $part[18] != '0') { $list_prod1.=  "<div class = 'row'><div class='col-md-2'>Тиснение: </div><div class='col-md-1'>".$part[18]."</div><div class='col-md-1'>".$part[18] * $row[5].'</div> </div>';	}
		IF ($list_prod1 != "") {
		$list_prod  .= "<div class = 'row'><div class='col-md-5'>&nbsp;</div> </div><div class = 'row'><div class='col-md-2'></div><div class='col-md-1'>На 1 изд.</div><div class='col-md-1'>На тираж</div> </div>";
		$list_prod  .= $list_prod1;
		
		}
		}
		echo "<td ><a href = 'pg/proc/plan_job2.php?id=".$row[11] ."' target='_blank'><span class = 'pull-right'><i class='glyphicon glyphicon-tasks'></i></<span></a>	</td>";
		echo "<td ><a onclick = rdy_back(".$row[11] .")><button type='button' class='btn btn-danger btn-sm '>Возврат</button></a>	</td>";
		echo "<td ><a onclick = rdy1(".$row[11] .")><button type='button' class='btn btn-success btn-sm '>Готово</button></a>	</td>";
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
		<div class="tab-pane" id="profile"> 
		<div class="row">
		<div class="col-md-4">
		<button type='button' class='btn btn-default'  onclick='_prod_rdy_men()'>Готово</button>
		</div>
		</div>
		<div class="row">
		<div class="col-lg-12">
		<div class="panel panel-default">
		
		<div id="baseDateControl">
		
		<div class="panel-body">
		<table class="table"  data-show-toggle="false" data-paging="true" data-filtering="true" data-sorting="true"  >
		<thead>
		<tr>
		<th></th>
		<th>Дата</th>
		<th>Номер счета</th>
		<th>Контрагент</th>
		<th>Продукт</th>
		<th>Тираж</th>
		<th></th>
		<th></th>
		</tr>
		</thead>
		<?php
		include "db.php";
		$query="select pj.dates_, 
		op.ORDER_ID idd_order,
		(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = (select op.ORDER_ID from order_product op where op.ID = pj.id_prod)) name_cl,
		op.p_names  p_names,
		op.num_prod_ord  num_prod_ord,
		pj.total_prod, pj.id, pj.id_prod from plan_job pj, order_product op  where pj.status = 1 AND op.ID = pj.id_prod and ( op.status <> 2 AND op.status <> 3)";
		$result = mysql_query($query) or die($query);
		echo "<tbody>";
		while ($row = mysql_fetch_row($result)) { 
		$list_prod = '';
		$list_prod1 = '';
		echo "<td><input type='checkbox' name='chjob3' value='".$row[6]."'></td>";
		echo "<td>$row[0]</td>";
		echo "<td>".$row[1]."_".$row[4]."</td>";
		echo "<td>$row[2]</td>";
		echo "<td>$row[3]</td>";
		echo "<td>$row[5]</td>";
		echo "<td ><a href = 'pg/proc/plan_job2.php?id=".$row[7] ."' target='_blank'><span class = 'pull-right'><i class='glyphicon glyphicon-tasks'></i></<span></a>	</td>";
		echo "<td><a onclick = rdy2('$row[6]')><button type='button' class='btn btn-success btn-sm '>Готово</button></a>	</td>";
		
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
		
		<div id="savetask" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" data-keyboard="true">
		<div class="modal-dialog modal-lg1">
		<div class="modal-content">
		<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		Передача </h4>
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
		<option value= '2' >ГОТОВО</option>
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
		
		<div class='row'><div class='col-md-2'><div class='block1'><label >Комментарий:</label></div></div>
		<div class='col-md-9'><textarea class="form-control" rows="3" id = 'list_comm' name = 'list_comm' ></textarea>	</div></div>
		
		
		
		</form>
		
		</div> <!-- modal-body -->
		<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		<a onclick="save_tst()"><button type="button" class="btn btn-primary" >Передать</button></a>
		</div> <!-- modal-footer -->
		</div>
		</div>
		</div>
		</div>
		
		<!-- /#Модальные окна -->
		<div id="mydf" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
		<div class="modal-dialog modal-sm">
		<div class="modal-content ">
		<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		<h4 class="modal-title">Изменения даты заказа</h4>
		</div>
		<div class="modal-body">
		<div class="row">
		<div class="col-md-1">  </div>
		<div class="col-md-2"><label>Дата:</label> </div>
		<div class="col-md-3"><input type='date' id='date_' name='date_'>
		<input type='hidden' id='id_' name='id_'>	</div>
		</div>
		</div>	
		<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		<a onclick="Upddt()"><button type="button" class="btn btn-primary">Изменить</button></a>
		</div>
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
		Работа действительно выполнена?
		<input id='id_back' type='hidden' value=''>
		</div> <!-- modal-body -->
		
		<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		<a onclick="rdy()"><button type="button" class="btn btn-primary" >Да</button></a>
		</div> <!-- modal-footer -->
		</div>
		</div>
		</div>
		
		<div id="baskt2ask" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" data-keyboard="true">
		<div class="modal-dialog">
		<div class="modal-content">
		<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		<h4 class="modal-title">ВНИМАНИЕ!</h4>
		</div>
		<div class="modal-body">
		Кол-во
		
		<input id='total_rd' type='text' value='0'>
		<input id='id_back1' type='hidden' value=''>
		</div> <!-- modal-body -->
		
		<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		<a onclick="rdy4()"><button type="button" class="btn btn-primary" >Готово</button></a>
		</div> <!-- modal-footer -->
		</div>
		</div>
		</div>
		
		<!-- /#Модальные окна -->
		<div id="myModal" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
		<div class="modal-dialog  modal-lg">
		<div class="modal-content ">
		<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		<h4 class="modal-title">Добавление клиента</h4>
		</div>
		<div class="modal-body">
		<div class="alert alert-danger" id = 'err' style="display: none;">
		<a href="#" class="close">×</a>
		<strong>Ошибка!</strong> Выберите клиента!
		</div>
		<div class="row"><div class="col-md-2"><label>Валюта:</label> </div>
		<div class="col-md-5">
		<select id = 'val_'>
		<?
		$query="select id,name,code from currency";
		$result = mysql_query($query) or die($query);
		while ($row = mysql_fetch_row($result)) { 
		IF ($row[2] == "974" || $row[2] == "933"){
		echo "<option value='$row[2]' SELECTED>".$row[1]."</option>";
		} ELSE {
		echo "<option value='$row[2]'>".$row[1]."</option>";
		} 
		}
		
		?>
		</select> 
		</div>
		</div>
		<div class="row">
		<div class="col-md-2"><div class="block1"><label>Поиск:</label></div></div>
		<div class="col-md-9"><div class="block1"><input type="text" id="search" class="inputSearch" ></div></div>
		<div class='col-md-1'><div class='block1'><a onClick="window.open('pg/_addClients.php', 'Добавление нового клиента', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');"><button type="button" class="btn btn-sm"><span class="glyphicon glyphicon-plus-sign"></span></button></a></div></div>
		</div>
		<div class="row">
		<div class="col-md-12"> </div>
		</div>
		<div class="row">
		<div class="col-md-2"><div class="block1"><label>Клиент:</label></div></div>
		<div class='col-md-10'>
		<div class='block1'>
		<?php
		echo "<select id = 'orderClient' size='10' style='overflow:auto;'>";
		echo " <optgroup label=''>";
		$query="select id,client_name, unp from clients";
		$result = mysql_query($query) or die($query);
		while ($row = mysql_fetch_row($result)) { 
		echo "<option value='$row[0]'>".$row[1]." (".$row[2].")</option>"; }
		echo "</select> &nbsp";
		?>
		</div>
		</div>
		</div>
		</div>
		<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		<a onclick="addorders()"><button type="button" class="btn btn-primary" >Добавить</button></a>
		</div>
		</div>
		</div>
		</div>
		
		
		<div id="planJob1" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
		<div class="modal-dialog modal-lg">
		<div class="modal-content ">
		<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
		<h4 class="modal-title">Заказ</h4>
		</div>
		<div class="modal-body">
		
		<div id = 'pj1'>
		</div>
		
		<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
		<a onclick="_add_plan_job5()"><button type="button" class="btn btn-primary">Готово</button></a>
		</div>
		</div>
		</div>
		</div>
		</div>
		
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
		<script>
		function rdy_back(id){
		document.getElementById("id_savetask").value = id;
		$('#savetask').modal('show');		
		}
		
		function save_tst(){
		user_perm = '1';
		
		
		var form = $('#forms')[0];
		var datass = new FormData(form);
		$.ajax({
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
		});
		
	   } 
		
		function rdy(){
		id = document.getElementById("id_back").value;
		$.ajax({
		type: "GET",
		url: 'pg/_rdy_task.php',
		data: {
		id : id
		},  success:function (data) {//возвращаемый результат от сервера
		location.reload(true)
		
		}
		});
		}
		function rdy2(id){
		document.getElementById("id_back1").value = id;
		$('#baskt2ask').modal('show');	
		}
		function rdy4(){
		id = document.getElementById("id_back1").value;
		total_rd = document.getElementById("total_rd").value;
		$.ajax({
		type: "GET",
		url: 'pg/_rdy_task1.php',
		data: {
		id : id, 
		total_rd : total_rd
		},  success:function (data) {//возвращаемый результат от сервера
		location.reload(true)
		
		}
		});
		}
		
		
		
		
		function rdy1(id){
		document.getElementById("id_back").value = id;
		$('#basktask').modal('show');	
		}
		
		
		function	_prod_rdy(){
		var nodeList = document.getElementsByName('chjob');
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
		url: 'pg/_rdy_task.php',
		data: {
		id : str_id
		},  success:function (data) {//возвращаемый результат от сервера
		location.reload(true)
		
		}
		
		});
		
		
		}else {
		alert('не выбраны продукты!')
		}
		}
		
		
		
		function	_prod_rdy_men(){
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
		url: 'pg/_listplanjob3.php',
		data: {
		id : str_id
		},  success:function (data) {//возвращаемый результат от сервера
		
		document.getElementById('pj1').innerHTML = "";
		document.getElementById('pj1').innerHTML = data;
		$('#planJob1').modal('show');
		
		}
		
		});
		
		
		}else {
		alert('не выбраны продукты!')
		}
		}
		
		function _add_plan_job5(){
	 	var nodeList = document.getElementsByName('chjob6');
		var array = Array.prototype.slice.call(nodeList);
		srt_pj = "";
		for (var i = 0; i < array.length; i++) {
		if (array[i].checked) {
		srt_pj =	srt_pj + document.getElementById('id_zakaz'+array[i].value).value + "^" +  array[i].value + "^" + document.getElementById('total'+array[i].value).value + "|";
		}
		
		}
		srt_pj = srt_pj.slice(0, -1);
		
		if (srt_pj != ""){
		
		$.ajax({
		type: "GET",
		url: 'pg/_addplanjob3.php',
		data: {
		
		id1 : srt_pj
		},  success:function (data) {//возвращаемый результат от сервера
		
		
		location.reload(true)
		}
		
		});
		
		}else {
		alert('не выбраны продукты!')
		}
		}
		
		</script>
		
		
		<script>
		jQuery(function($){
		
		$('.table').footable({
		"paging": {
		"size": 20
		},
		"sorting": {
		"enabled": true
		}
		});
		});
		</script>
		
		<script type="text/javascript">
		$(".close").click(function(){document.getElementById('err').style.display = 'none';});
		</script>
		
		<footer class="footer">
		<p>&copy; Company 2016</p>
		</footer>
		</body>
		
		</html>
				