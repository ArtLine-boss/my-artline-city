<?php
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
		
		<!-- Bootstrap Core CSS -->
		<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		
		<!-- MetisMenu CSS -->
		
		
		<!-- Custom CSS -->
		<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
		
		<!-- Morris Charts CSS -->
		<link href="../vendor/morrisjs/morris.css" rel="stylesheet">
		
		<!-- Custom Fonts -->
		<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="../vendor/img/styles.css" />
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		
	</head>
	
	<body>
		<div id="wrapper">
		
		<?php
			include_once("menu.php");
		?>
					
					<div id="page-wrapper">
					<div class="row">
					<div class="col-lg-6">
					<h2 class="page-header">Дизайн</h2>
					<div class="col-lg-12">
					<div class="panel panel-default">
					<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal11'>Добавить</button>
					<!-- /.panel-heading -->
					<div class="panel-body">
					
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
					
					<thead>
					<tr>
					<th>Операция</th>
					<th>Время</th>
					</tr>
					</thead>
					<?php
					$json_diz = Array();
					include "db.php";
					$query="select  * from DIZ_OPER";
					$result = mysql_query($query) or die("Query failed");
					print "<tbody>";
					while ($row = mysql_fetch_row($result)) { 
					$json_diz[] = array(
					'id' => $row[0],
					'name' => $row[1],
					'time'  => $row[2],
					'deff'  => $row[3]
					);
					print "<tr class='odd gradeX'>";
					print "<td><a  onclick='updd($row[0])' >$row[1]</a></td>";
					print "<td>$row[2]</td>";
					print "</tr>";
					
					}print "</tbody>"; 
					mysql_close($connection);
					?>
					
					
					</table>
					<!-- /.table-responsive -->
					
					</div>
					<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
					</div>
					
					</div>
					<div class="col-lg-6">
					<h2 class="page-header">Препресс</h2>
					<div class="col-lg-12">
					<div class="panel panel-default">
					<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal21'>Добавить</button>		
					<!-- /.panel-heading -->
					<div class="panel-body">
					
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example1">
					
					<thead>
					<tr>
					<th>Операция</th>
					<th>Время</th>
					</tr>
					</thead>
					<?php
					$json_press = Array();
					include "db.php";
					$query="select  * from PR_OPER";
					$result = mysql_query($query) or die("Query failed");
					print "<tbody>";
					while ($row = mysql_fetch_row($result)) { 
					$json_press[] = array(
					'id' => $row[0],
					'name' => $row[1],
					'time'  => $row[2],
					'deff'  => $row[3]
					);
					print "<tr class='odd gradeX'>";
					print "<td><a onclick='updp($row[0])'>$row[1]</a></td>";
					print "<td>$row[2]</td>";
					print "</tr>";
					
					}print "</tbody>"; 
					mysql_close($connection);
					?>
					
					</table>
					<!-- /.table-responsive -->
					
					</div>
					<!-- /.panel-body -->
					</div>
					<!-- /.panel -->
					</div>
					
					</div>
					
					</div>
					
					
					
					<!--	<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal3'>Выбор дизайн</button>		
					<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal4'>Выбор препресс</button>-->					
					
					<!-- /.row -->
					<!-- /.row -->
					
					<div id="myModal11" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Добавление операции дизайн</h4>
					</div>
					<div class="modal-body">
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label>Наименование</div><div class="col-md-2"><input type="text" id="diz_name" name="diz_name" value = ""></div></div>
					</div>
					</div>
					
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label> Время(мин)</div><div class="col-md-2"><input type="text" id="diz_time" name="diz_time" value = "0"></div></div>
					</div>
					</div>
					
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-12"> <div class="checkbox"><label><input type="checkbox" id = 'diz_def'  name="diz_def"> <b>По умолчанию</label></div></div></div>
					</div>
					</div>
					</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="fun()"><button type="button" class="btn btn-primary">Добавить</button></a></div>
					</div>
					</div>
					</div>
					
					<div id="myModal21" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Добавление операции препресс</h4>
					</div>
					<div class="modal-body">
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label>Наименование</div><div class="col-md-2"><input type="text" id="pre_name" name="pre_name" value = ""></div></div>
					</div>
					</div>
					
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label> Время(мин)</div><div class="col-md-2"><input type="text" id="pre_time" name="pre_time" value = "0"></div></div>
					</div>
					</div>
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-12"> <div class="checkbox"><label><input type="checkbox" id = 'pre_def'  name="pre_def"> <b>По умолчанию</label></div></div></div>
					</div>
					</div>
					</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="fun1()"><button type="button" class="btn btn-primary">Добавить</button></a></div>
					</div>
					</div>
					</div>
					<?php
					
					print'	<div id="myModal12" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Изменение операции дизайн</h4>
					</div>
					<div class="modal-body">
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label>Наименование</div><div class="col-md-2"><input type="text" id="diz_name1" name="diz_name" value = ""></div></div>
					</div>
					</div>
					
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label> Время(мин)</div><div class="col-md-2"><input type="text" id="diz_time1" name="diz_time" value = "0">
					<input type="hidden" id="id_diz" name="id_diz" ></div></div>
					</div> 
					</div>
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-12"> <div class="checkbox"><label><input type="checkbox" id = "diz_def1"  name="diz_def1"> <b>По умолчанию</label></div></div></div>						</div>
					</div>
					</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="funUpdDiz()"><button type="button" class="btn btn-primary">Изменить</button></a></div>
					</div>
					</div>
					</div>';
					
					print '	<div id="myModal22" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Изменение операции препресс</h4>
					</div>
					<div class="modal-body">
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label>Наименование</div><div class="col-md-2"><input type="text" id="pre_name1" name="pre_name" value = ""></div></div>
					</div>
					</div>
					
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label> Время(мин)</div><div class="col-md-2"><input type="text" id="pre_time1" name="pre_time" value = "0">
					<input type="hidden" id="id_pr" name="id_pr" ></div></div>
					</div>
					</div>
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-12"> <div class="checkbox"><label><input type="checkbox" id = "pre_def1"  name="pre_def1"> <b>По умолчанию</label></div></div></div>
					</div>
					</div>
					
					</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="funUpdPr()"><button type="button" class="btn btn-primary">Изменить</button></a></div>
					</div>
					</div>
					</div>';
					include "db.php";
					$query = "select val from settings s where s.NAME = 'Стоимость дизайна'";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) { 
					$price_diz = $row[0];
					}
					IF ($price_diz ==''){
					$price_diz = 0;
					} ELSE {
					$price_diz = str_replace(',', '.', $price_diz);
					}
					$query = "select val from settings s where s.NAME = 'Курс'";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) { 
					$kurs = $row[0];
					}
					IF ($kurs ==''){
					$kurs = 0;
					} ELSE {
					$kurs = str_replace(',', '.', $kurs);
					}
					print'	
					<div id="myModal3" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Изменение операции дизайн</h4>
					</div>
					<div class="modal-body">';
					print'<div class="row">
					<div class="col-md-6"> 
					<label>Наименование</label>
					</div>
					<div class="col-md-3"> 
					<label> Время(мин)</label>
					</div>
					<div class="col-md-3">
					<label>Стоимость</label>
					</div>
					</div>';
					$total_time = 0;
					$total_sum = 0;
					$query="select  * from DIZ_OPER";
					$result = mysql_query($query) or die("Query failed");
					while ($row = mysql_fetch_row($result)) { 
					$sums = 0;
					/*print'	<div class="row"><div class="col-md-1"> <input  type = "checkbox"  id = '.$row[0].' > </div><div class="col-md-5"> <label>'.$row[1].'</label></div> </div>';*/
					if ($row[3] == 1){
					$total_time = $total_time  + $row[2];
					print' <div class="row">
					<div class="col-md-6"> 
					<div class="checkbox">
					<label><input type="checkbox" id = d'.$row[0].' value=" '.$row[2].'"  name="diz" checked onclick="fun_view_diz('.$row[0].')">'.$row[1].'</label>
					</div>
					</div>
					<div id=elemm'.$row[0].'  style="display:block;">
					<div class="col-md-3"> 
					<label id=elemm'.$row[0].'1>'.$row[2].'</label>
					</div>
					<div class="col-md-3">';
					$sums = ($row[2]/60) * $price_diz * $kurs;
					$total_sum =  $total_sum + $sums;
					print '<label id=elemm'.$row[0].'2>'.round($sums, 2).'</label>
					</div>
					</div>
					</div>';
					} else {
					print' <div class="row">
					<div class="col-md-6"> 
					<div class="checkbox">
					<label><input type="checkbox" id = d'.$row[0].' value=" '.$row[2].'"  name="diz" onclick="fun_view_diz('.$row[0].')">'.$row[1].'</label>
					</div>
					</div>
					<div id=elemm'.$row[0].'  style="display:none;">
					<div class="col-md-3"> 
					<label id=elemm'.$row[0].'1>'.$row[2].'</label>
					</div>
					<div class="col-md-3">';
					$sums = ($row[2]/60) * $price_diz * $kurs;
					print '<label id=elemm'.$row[0].'2>'.round($sums, 2).'</label>
					</div>
					</div>
					
					</div>';
					}
					
					}
					print'<div class="row">
					<div class="col-md-6"> 
					<label>Итого:</label>
					</div>
					<div class="col-md-3"> 
					<label id = total_time_diz>'.$total_time.'</label>
					</div>
					<div class="col-md-3">
					<label id = total_sum_diz>'.round($total_sum, 2).'</label>
					</div>
					</div>	';					
					
					
					print'	</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<button type="button" class="btn btn-primary"  onclick="diz_fun() ">Добавить</button>
					</div>
					</div>
					</div>
					</div>';
					
					print'	
					<div id="myModal4" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Изменение операции дизайн</h4>
					</div>
					<div class="modal-body">';
					print'<div class="row">
					<div class="col-md-6"> 
					<label> Наименование</label>
					</div>
					<div class="col-md-3"> 
					<label> Время(мин)</label>
					</div>
					<div class="col-md-3">
					<label>Стоимость</label>
					</div>
					</div>';
					
					$total_time = 0;
					$total_sum = 0;
					$query="select  * from PR_OPER";
					$result = mysql_query($query) or die("Query failed");
					while ($row = mysql_fetch_row($result)) { 
					$sums = 0;
					if ($row[3] == 1){
					$total_time = $total_time  + $row[2];
					print'<div class="row">
					<div class="col-md-6"> 
					<div class="checkbox">
					<label><input type="checkbox" id = p'.$row[0].' value=" '.$row[2].'"  name="pre" checked onclick="fun_view_pre('.$row[0].')">'.$row[1].'</label>
					</div>
					</div>
					<div id=elem'.$row[0].'  style="display:block;">
					<div class="col-md-3"> 
					<label id=elem'.$row[0].'1>'.$row[2].'</label>
					</div>
					<div class="col-md-3">';
					$sums = ($row[2]/60) * $price_diz * $kurs;
					$total_sum =  $total_sum + $sums;
					print '<label id=elem'.$row[0].'2>'.round($sums, 2).'</label>
					</div>
					</div>
					</div>';
					} else {
					print'<div class="row">
					<div class="col-md-6"> 
					<div class="checkbox">
					<label><input type="checkbox" id = p'.$row[0].' value=" '.$row[2].'"  name="pre" onclick="fun_view_pre('.$row[0].')">'.$row[1].'</label>
					</div>
					</div>
					<div id=elem'.$row[0].'  style="display:none;">
					<div class="col-md-3"> 
					<label id=elem'.$row[0].'1>'.$row[2].'</label>
					</div>
					<div class="col-md-3">';
					$sums = ($row[2]/60) * $price_diz * $kurs;
					print '<label id=elem'.$row[0].'2>'.round($sums, 2).'</label>
					</div>
					</div>
					</div>';
					}
					
					}
					print'<div class="row">
					<div class="col-md-6"> 
					<label>Итого:</label>
					</div>
					<div class="col-md-3"> 
					<label id = total_time_pre>'.$total_time.'</label>
					</div>
					<div class="col-md-3">
					<label id = total_sum_pre>'.round($total_sum, 2).'</label>
					</div>
					</div>	';			
					mysql_close($connection);					
					
					
					
					print'	</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<button type="button" class="btn btn-primary"  onclick="pre_fun() ">Добавить</button>	
					</div>
					</div>
					</div>
					</div>';
					?>
					
					
					<!-- /#wrapper -->
					<script type="text/javascript" src="../vendor/img/jquery-1.4.4.min.js"></script>
					<script type="text/javascript" src="../vendor/img/tooltip.js"></script>
					
					<!-- jQuery-->
					<script src="../vendor/jquery/jquery.min.js"></script> 
					<script src="../js/funJs.js"></script>
					<!-- Bootstrap Core JavaScript -->
					<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
					
					<!-- Metis Menu Plugin JavaScript -->
					<script src="../vendor/metisMenu/metisMenu.min.js"></script>
					
					<!-- Morris Charts JavaScript -->
					<script src="../vendor/raphael/raphael.min.js"></script>
					<script src="../vendor/morrisjs/morris.min.js"></script>
					<script src="../data/morris-data.js"></script>
					
					<!-- Custom Theme JavaScript -->
					<script src="../dist/js/sb-admin-2.js"></script>
					
					<!-- DataTables JavaScript -->
					<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
					<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
					<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
					<!-- Page-Level Demo Scripts - Tables - Use for reference -->
					<script>
					$(document).ready(function() {
					$('#dataTables-example').DataTable({
					responsive: true
					});
					});
					
					$(document).ready(function() {
					$('#dataTables-example1').DataTable({
					responsive: true
					});
					});
					/*
					$('#myModal22').on('shown.bs.modal', function (e) {
					$.get( "design.php?id=1", function( data ) {
					$( "#result" ).html("asd");
					});
					});*/
					
					</script>
					
					<script>
					function fun(){
					var diz_time = document.getElementById('diz_time').value;
					var diz_name = document.getElementById('diz_name').value;
					var diz_def = 0;
					if (document.getElementById('diz_def').checked){
					diz_def = 1;
					}
					
					location.href = 'pg/_add_diz.php?diz_time='+diz_time+'&diz_name='+diz_name+'&diz_def='+diz_def;	 
					}
					function fun1(){
					var pre_time = document.getElementById('pre_time').value;
					var pre_name = document.getElementById('pre_name').value;
					var pre_def = 0;
					if (document.getElementById('pre_def').checked){
					pre_def = 1;
					}
					location.href = 'pg/_add_pre.php?pre_time='+pre_time+'&pre_name='+pre_name+'&pre_def='+pre_def;	 
					}
					
					function diz_fun() {
					var str = '', str1 = '', sum = 0;
					var nodeList = document.getElementsByName('diz');
					var array = Array.prototype.slice.call(nodeList);
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					str = str + String(array[i].id) + " ";
					str1 = str1 + String(array[i].value) + " ";
					sum = Number(sum) + Number(array[i].value);
					/*var tr = array[i].id;
					str = str + tr + " ";*/
					/*var parent = tr.parentNode
					parent.removeChild(tr);*/
					}
					}
					}
					function pre_fun() {
					var str = '', str1 = '', sum = 0;
					var nodeList = document.getElementsByName('pre');
					var array = Array.prototype.slice.call(nodeList);
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					str = str + String(array[i].id) + " ";
					str1 = str1 + String(array[i].value) + " ";
					sum = Number(sum) + Number(array[i].value);
					/*var tr = array[i].id;
					str = str + tr + " ";*/
					/*var parent = tr.parentNode
					parent.removeChild(tr);*/
					}
					}
					}
					
					function fun_view_pre(id) {
					var str = 'elem'+id;
					var str1 = 'p'+id;
					var str3 = 'elem'+id+'1';
					var str4 =  'elem'+id+'2';
					var time, sum, time1 , sum1,total_time, total_sum;
					time1 =  document.getElementById(str3).innerHTML;
					sum1 =  document.getElementById(str4).innerHTML;
					time =  document.getElementById('total_time_pre').innerHTML;
					sum =  document.getElementById('total_sum_pre').innerHTML;
					
					time1 =  Number(time1);
					sum1 = Number(sum1);
					time =  Number(time);
					sum =  Number(sum);
					
					if (document.getElementById(str1).checked){
					document.getElementById(str).style.display = 'block';
					total_time = time + time1
					total_sum = sum + sum1
					document.getElementById('total_time_pre').innerHTML = total_time ;
					document.getElementById('total_sum_pre').innerHTML =  (total_sum ?? 0).toFixed(2) ;
					
					}else {
					document.getElementById(str).style.display = 'none';
					total_time = time - time1
					total_sum = sum - sum1
					document.getElementById('total_time_pre').innerHTML = total_time ;
					document.getElementById('total_sum_pre').innerHTML =  (total_sum ?? 0).toFixed(2) ;;
					}
					}
					function fun_view_diz(id) {
					var str = 'elemm'+id;
					var str1 = 'd'+id;
					var str3 = 'elemm'+id+'1';
					var str4 =  'elemm'+id+'2';
					var time, sum, time1 , sum1,total_time, total_sum;
					time1 =  document.getElementById(str3).innerHTML;
					sum1 =  document.getElementById(str4).innerHTML;
					time =  document.getElementById('total_time_diz').innerHTML;
					sum =  document.getElementById('total_sum_diz').innerHTML;
					
					time1 =  Number(time1);
					sum1 = Number(sum1);
					time =  Number(time);
					sum =  Number(sum);
					
					if (document.getElementById(str1).checked){
					document.getElementById(str).style.display = 'block';
					total_time = time + time1
					total_sum = sum + sum1
					document.getElementById('total_time_diz').innerHTML = total_time ;
					document.getElementById('total_sum_diz').innerHTML =  (total_sum ?? 0).toFixed(2) ;
					}else {
					document.getElementById(str).style.display = 'none';
					total_time = time - time1
					total_sum = sum - sum1
					document.getElementById('total_time_diz').innerHTML = total_time ;
					document.getElementById('total_sum_diz').innerHTML =  (total_sum ?? 0).toFixed(2);
					}
					
					}
					</script>
					
					<script>
					function updp(id){
					
					//		myModal22  pre_def1 pre_time1 pre_name1 id_pr
					
					array = JSON.parse('<?echo json_encode($json_press);?>');
					
					for (var i = 0; i < array.length; i++){
					if (array[i].id == id){
					//alert(array[i].id  + " " + " " + array[i].name  + " " +  array[i].time + " " + array[i].deff)
					//diz_name diz_time diz_def1 id_diz
					document.getElementById('pre_name1').value = array[i].name
					document.getElementById('pre_time1').value = array[i].time
					document.getElementById('id_pr').value = array[i].id
					if (array[i].deff == 1){
					document.getElementById('pre_def1').checked = true
					}
					$('#myModal22').modal('show');
					break;
					}
					}
					}
					
					</script>
					<script>
					function updd(id){
					//
					array = <?echo json_encode($json_diz);?>;
					
					for (var i = 0; i < array.length; i++){
					if (array[i].id == id){
					//alert(array[i].id  + " " + " " + array[i].name  + " " +  array[i].time + " " + array[i].deff)
					//diz_name diz_time diz_def1 id_diz
					document.getElementById('diz_name1').value = array[i].name
					document.getElementById('diz_time1').value = array[i].time
					document.getElementById('id_diz').value = array[i].id
					if (array[i].deff == 1){
					document.getElementById('diz_def1').checked = true
					}
					$('#myModal12').modal('show');
					break;
					}
					}
					}
					
					</script>
					
					<script>
					function funUpdDiz(){
					var pre_time = document.getElementById('diz_time1').value 
					var pre_name = document.getElementById('diz_name1').value 
					var id  = document.getElementById('id_diz').value;
					var pre_def = 0;
					if (document.getElementById('diz_def1').checked){
					pre_def = 1;
					}
					location.href = 'pg/_updateDiz.php?times='+pre_time+'&name='+pre_name+'&use='+pre_def+'&id='+id;	 
					
					}
					</script>
					
					<script>
					function funUpdPr(){
					var pre_time = document.getElementById('pre_time1').value 
					var pre_name = document.getElementById('pre_name1').value 
					var id  = document.getElementById('id_pr').value;
					var pre_def = 0;
					if (document.getElementById('pre_def1').checked){
					pre_def = 1;
					}
					location.href = 'pg/_updatePress.php?times='+pre_time+'&name='+pre_name+'&use='+pre_def+'&id='+id;	 
					}
					</script>
					
					
					<footer class="footer">
					<p>&copy; Company 2016</p>
					</footer>
					</body>
					
					</html>
										