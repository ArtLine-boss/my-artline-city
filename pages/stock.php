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
<html lang="en">
	
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Система управления заказами</title>
		<link rel="icon" href="../favicon.png" type="image/png">
		<link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
		<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
		
		<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" type="text/css" href="../vendor/img/styles.css" />
		<script src="../vendor/jquery/jquery.min.js"></script> 
		<script src="../js/jquery-ui.min.js"></script>
		
		<link href="../../vendor/bootstrap/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
		
		<script type="text/javascript" src="../../vendor/bootstrap/js/bootstrap-multiselect.js"></script>
		<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap-multiselect.css" type="text/css"/>
		<link rel="stylesheet" href="../vendor/chosen.min.css">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
		
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/jstree.min.js"></script>
		<script type="text/javascript">
			
			function context_menu(node){
				var tree = $('#SimpleJSTree').jstree(true);
				
				// The default set of all items
				var items = {
					"Create": {
						"separator_before": false,
						"separator_after": false,
						"label": "Создать",
						"action": function (obj) { 
							newTree(node.id);
						}
					},
					"Edit": {
						"separator_before": false,
						"separator_after": false,
					"label": "Изменить",
					"action": function (obj) { 
					_editklass(node.id)
					
					}
					},                         
					"Remove": {
					"separator_before": true,
					"separator_after": false,
					"label": "Удалить",
					"action": function (obj) { 
					_delklass(node.id)
					}
					}
					};
					return items;
					}
					
					$(function () {
					$.ajax({
					async: true,
					type: "GET",
					url: "ajax_php_sql.php?flag=31",
					dataType: "json",
					success: function (json) {
					createJSTree(json);
					},
					
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
					}
					});            
					});
					
					function createJSTree(jsondata) {            
					$('#SimpleJSTree').jstree({ 
					"core" : {
					// so that create works
					"check_callback" : true
					},
					'plugins': ["contextmenu" ,"search", "sort" ],
					contextmenu: {items: context_menu},
					'core': {
					'data': jsondata
					}
					});
					
					
					
					var to = false;
					$('#plugins4_q').keyup(function () {
					if(to) { clearTimeout(to); }
					to = setTimeout(function () {
					var v = $('#plugins4_q').val();
					$('#SimpleJSTree').jstree(true).search(v);
					}, 250);
					});
					
					
					}
					
					</script>
					
					<style>
					.modal-lg
					{
					height:90%;
					width:90%;
					margin-left: 5%; 
					overflow-y: scroll;
					overflow-x: auto;
					}
					.modal-lg1
					{
					height:70%;
					width:70%;
					margin-left: 15%; 
					
					
					}
					
					.modal-lg2
					{
					height:70%;
					width:70%;
					margin-left: 15%; 
					overflow-y: scroll;
					overflow-x: auto;
					
					}
					.vakata-context {
					z-index: 1100
					}
					</style>
					</head>
					
					<body>
					<div id="wrapper">
					<!-- Navigation -->
					<?php
					include_once("menu.php");
					
					?>
					
					<div id="page-wrapper">
					<div class="row">
					<div class="col-lg-12">
					<h2 class="page-header">Склад</h2>
					</div>
					</div>
					
					<ul class="nav nav-tabs" id="myTab">
					<li class="active"><a href="#home" data-toggle="tab">Склад</a></li>
					<li><a href="#profile" data-toggle="tab">Приходы</a></li>
					<li><a href="#profile1" data-toggle="tab">Поставщики</a></li>
					<li><a href="#profile2" data-toggle="tab">Списание за период</a></li>
					</ul>
					<div class="tab-content">
					<div class="tab-pane active" id="home"> 
					<button type='button' class='btn btn-default'  onClick='_rev_mat()'>Приход/Списание</button> 	<button type='button' class='btn btn-default'  onClick='_matArh()'>Архив</button>
					<div class="panel-body">
					<table width = '100%' class="table table-striped table-bordered table-hover responsive nowrap " cellspacing="0" id="tb_mat"  >
					<thead>
					<tr>
					<th></th>
					<th></th>
					<th></th>
					<th>Тип материала</th>
					<th>Название материала</th>
					<th>Ед. измерения</th>
					<th>Размер</th>
					<th>Кол-во</th>
					<th>Цена, $</th>
					<th>Узел</th>
					</tr>
					</thead>
					</table>
					</div>
					</div>
					<div class="tab-pane" id="profile"> 
					<button type='button' class='btn btn-default'  onClick='addtn()'>Добавить</button>
					<div class="panel-body">
					<table   width = '100%'   class="table table-striped table-bordered table-hover responsive nowrap " cellspacing="0" id="tb_ttn"  >
					<thead>
					<tr>
					<th></th>
					<th></th>
					<th>Дата Прихода</th>
					<th>Номер ТН</th>
					<th>Серия</th>
					<th>Наименование клиента</th>
					<th>Сумма</th>
					<th></th>
					</tr>
					</thead>
					</table>
					</div>
					</div>
					<div class="tab-pane" id="profile1"> 
					<button type='button' class='btn btn-default'  onClick='addfirm()'>Добавить</button>
					<div class="panel-body">
					<table width = '100%' class="table table-striped table-bordered table-hover responsive nowrap " cellspacing="0" id="tb_firms"  >
					<thead>
					<tr>
					<th></th>
					<th>Наименование</th>
					<th>УНП</th>
					</tr>
					</thead>
					</table>
					</div>
					</div>	
					<div class="tab-pane" id="profile2"> 
					<div class="panel-body">	
					<div id='row'>
					<div class="col-md-6">
					C <input type='date' id='date1' name='date'>	 
					ПО <input type='date' id='date2' name='date'>
					
					<button type="button" class="btn btn-info btn-circle" onclick='spis()' >
					
					<i class="fa fa-search"></i> 
					</button>
					
					
					</div>  
					</div>
					<div id='row'>
					<div class="col-md-12" > &nbsp;
					</div>
					</div>
					<div id='row'>
					<div class="col-md-12" id = 'lists'>
					</div>
					</div>
					</div>
					</div>					
					</div>		
					</div>        
					</div>  
					
					<!-- /#Добавление ТН -->
					<div id="add_tn" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog modal-lg">
					<div class="modal-content ">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Добавление ТН</h4>
					</div>
					<div class="modal-body">
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-2"><label>Поставщик:</label> </div>
					<div class="col-md-3" id = 'list_firm'>
					</div>
					</div>
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-2"><label>Номер ТН:</label> </div>
					<div class="col-md-2"><input type='number'  id = "num_ttn" disabled></div>
					</div>
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-2"><label>Серия:</label> </div>
					<div class="col-md-2"><input type="text" id="seriass" name="seriass" onKeyUp="this.value = this.value.replace (/[^А-Я]/, '')" size = '2'  value = "" disabled></div>
					</div>
					
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-2"><label>Дата:</label> </div>
					<div class="col-md-2"><input type='date' id='date_' name='date_' disabled></div>
					</div>
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-2"><label>Сумма к оплате:</label> </div>
					<div class="col-md-2"><input type='text' id = 'all_sm' disabled></div>		
					</div>
					<hr>
					<button type='button' class='btn btn-default' id = 'add_bnt' onClick='add_mat_ttn()' disabled>Добавить</button>
					<table width = '100%' class="table table-striped table-bordered table-hover responsive nowrap " cellspacing="0" id="tb_list_mat" >
					<thead>
					<tr>
					<th></th>
					<th>Наименование</th>
					<th>Название ТТН</th>
					<th>Ед. изм.</th>
					<th>Кол-во Склад</th>
					<th>Кол-во ТТН</th>
					<!--    <th>Цена</th> -->
					<th>Сумма</th>
					<th>Код 1С</th>
					</tr>
					</thead>
					</table>
					
					
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<button type="button" class="btn btn-primary"  onclick="add_ttn_base()" id = 'add_bnt1' >Добавить</button>
					<input type='hidden' id='id_rev' value=''>
					<button type="button" class="btn btn-primary"  onclick="edit_ttn_base()" id = 'edit_bnt' disabled >Изменить</button>
					</div>
					</div>
					</div>
					</div>
					</div>
					<!-- /#Добавление нового поставщтка -->
					<div id="add_firm" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog modal-lg">
					<div class="modal-content ">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Добавление ТН</h4>
					</div>
					<div class="modal-body">
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-2"><label>Наименование:</label> </div>
					<div class="col-md-3"> <input type='text' id = 'name_firm' size = 100>
					</div></div>
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-2"><label>УНП:</label> </div>
					<div class="col-md-3"> <input type='text' id = 'unp_firm' size = 100>
					<input type='hidden' id='id_firm' value = '0'>
					</div></div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="add_new_firm()"><button type="button" class="btn btn-primary">Сохранить</button></a>
					</div>
					</div>
					</div>
					</div>
					</div>
					
					<!-- /#Добавление нового поставщтка -->
					<div id="add_ttn_mat" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog modal-lg2">
					<div class="modal-content ">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Добавление Материала</h4>
					</div>
					<div class="modal-body">
					<div class="form-group">
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-6">	<label class="radio-inline">
					<input type="radio" name="r2" id="op1"  value="s"  checked onclick = "fun2()">Существующий
					
					</label>
					<label class="radio-inline">
					<input type="radio" name="r2" id="op2"  value="n"  onclick = "fun2()">Новый
					</label></div>
					</div>	
					
					</div>
					
					<?	$line =""; 
					$query="select ma.id, ma.m_name, ma.m_size, m.MT_TYPE, ma.M_KOL_ALL from material m, material_attr ma where ma.ID_M = m.ID";
					$flag = '';
					$ko = 0;
					$result = mysql_query($query) or die($query);
					while ($row = mysql_fetch_row($result))
					{ 
					
					if ($flag != $row[3]){
					if ($ko != 0) {
					$line =$line." </optgroup>";
					}
					$flag = $row[3];
					$line =$line."<optgroup label='$row[3]'><option value='$row[0]'>$row[1] ($row[2]  КОЛ-ВО:$row[4])</option>"; 
					$ko = 1;
					} else { $line =$line."<option value='$row[0]'>$row[1] ($row[2]  КОЛ-ВО:$row[4])</option>";
					$flag = $row[3];
					$ko = 1;
					}
					
					}
					$line = $line.""; ?>		
					<div id = "block1" style="display:block;">
					<div class="row">
					<div class="col-md-1">  </div>
					<div class="col-md-3"><label >Материал:</label> </div>
					<div class="col-md-3"><select  id = 'add_mat' name = 'add_mat' class='selectpicker' data-live-search='true'> <option value='' selected >Выберите материал</option>
						<? echo  $line; ?>
						</select>
						</div>
					</div>	
					
					</div>
					<div id = "she"></div>
					<div id = "she1" style="display: none;">
					<div class = "row">
					<div class="col-lg-4">
					<label>Поиск: <input type="text" id="plugins4_q" value="" class="input" style="margin:0em auto 1em auto; display:block; padding:4px; border-radius:4px; border:1px solid silver;"></label>
					<div  id="SimpleJSTree"></div>
					</div>
					
					<div class="col-lg-4" id="list_tree"> </div>
					
					</div>
					</div>
					
					</div>
					
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="add_mat_tn()"><button type="button" class="btn btn-primary">Добавить</button></a> 
					</div>
					</div>
					</div>
					</div>
					</div>
					
					<!-- /#Модальные окна -->
					<div id="list_mat" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
					<div class="modal-dialog modal-lg">
					<div class="modal-content ">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Материалы</h4>
					</div>
					<div class="modal-body">
					<div class="row">
					
					<div id = 'pj'>
					</div>
					</div>	
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="_add_plan_job2()"><button type="button" class="btn btn-warning">Списать</button></a>
					<a onclick="_add_plan_job3()"><button type="button" class="btn btn-primary">Оприходовать</button></a>
					</div>
					</div>
					</div>
					</div>
					</div>
					<!-- /#Модальные окна -->
					<div id="edit_mat" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
					<div class="modal-dialog modal-lg2">
					<div class="modal-content ">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Материалы</h4>
					</div>
					<div class="modal-body">
					<div class='row'>
					<div class='col-md-2'> </div>
					<div class='col-md-2'><label >Имя материала: </label></div>
					<div class='col-md-6'><input type='text' class="form-control" id='nameMaterial_edit' value=''></div>
					</div>        
					<div class='row'> 
					<div class='col-md-2'> </div>
					<div class='col-md-2'> <label >Ед. измерения: </label></div>
					<div class='col-md-6' ><input type='text' class="form-control" id='izmMaterial_edit' value=''></div>
					</div>    
					<div class='row'>
					<div class='col-md-2'> </div>
					<div class='col-md-2'><label >Размер, мм </label></div>
					<div class='col-md-6'><input type='text' class="form-control" id='sizeMaterial_edit' value=''></div>
					</div>   
					<div class='row'>
					<div class='col-md-2'> </div>
					<div class='col-md-2'><label>Стоимость, $ </label></div>
					<div class='col-md-6'><input type='text' class="form-control" id='priceMaterial_edit' value=''></div>
					</div>    
					<div class='row'>
					<div class='col-md-2'> </div>
					<div class='col-md-2'><label>Толщина, мм </label></div>
					<div class='col-md-6'><input type='text' class="form-control" id='tolMaterial_edit' value=''></div>
					</div> 
					<div class='row'>
					<div class='col-md-2'> </div>
					<div class='col-md-2'><label>Размер для резки </label></div>
					<div class='col-md-6'>	<select  id = 'eqFormat'  data-style='btn-default' multiple  name='eqFormat[]' >
					<?
					
					$query="select o.id, o.size from size_print o ";
					$result = mysql_query($query) or die($query);
					
					while ($row = mysql_fetch_row($result)){
					ECHO "<option value='$row[0]'>".$row[1]."</option>";  
					}
					
					?>
					
					</select></div>
					</div>    
					
					<div class='row'>
					<div class='col-md-2'> </div>
					<div class='col-md-2'> </div>
					<div class='col-md-6'><input type='hidden' value='0' id='idMaterial'></div>
					</div>    
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="save_mat()"><button type="button" class="btn btn-primary">Сохранить</button></a>
					</div>
					</div>
					</div>
					</div>
					</div>
					<div id="add_opl" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Добавление оплаты</h4>
					</div>
					<div class="modal-body">
					
					<div class="row"><div class="col-md-3"><div class="block1">
					<?php
					
					echo  "<label>Клиент:</label></div></div><div class='col-md-6'><div class='block1'>";
					
					echo "<select id = 'client' disabled>";
					echo "<option value=''></option>"; 
					include 'db.php';
					$query="select id, FIRM_NAME from firms";
					$result = mysql_query($query) or die($query);
					while ($row = mysql_fetch_row($result)) { 
					echo "<option value='$row[0]'>$row[1]</option>"; }
					echo "</select></div></div></div>";
					?>
					<div class="row"><div class="col-md-3"><div class="block1"><label>№ заказа:</label></div></div>
					<div class="col-md-3"><div class="block1"><input  id = 'num' type='text' disabled value = '0'/></div></div></div>
					<div class="row"><div class="col-md-3"><div class="block1"><label>Сумма:</label></div></div>
					<div class="col-md-3"><div class="block1"><input  id = 'sum' type='text' value = '0'/></div></div></div>
					<div class='row'><div class='col-md-3'><div class='block1'><label >Дата:</label></div></div>
					<div class='col-md-2'><div class='block1'><input type='date' id='date_opl' name='date_opl'>		</div></div></div>
					<div class='row'><div class='col-md-3'><div class='block1'><label >Вид:</label></div></div>
					<div class='col-md-9'><div class='block1'>
					<div class="form-group"  id ='radio_chk'>
					<label class="radio-inline" >
					<input type="radio" name="view_opl" id="view_opl1" value="5" checked>	безнал
					</label>
					
					</div>
					</div></div></div>
					<div class='row'><div class='col-md-3'><div class='block1'><label >Комментарий:</label></div></div>
					<div class='col-md-9'><textarea class="form-control" rows="3" id = 'list_comm'></textarea>	</div></div>
					</div>
					
					
					
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="fun_opl()"><button type="button" class="btn btn-primary" >Добавить</button></a></div>
					</div>
					</div>
					</div>
					
					<!----------------------------------------------------------------------------------------------------------->
					
					<div id="addTree" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog modal-lg">
					<div class="modal-content ">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">Узел</h4>
					</div>
					<div class="modal-body">
					<div class='row'>
					<div class="col-lg-2"><label>Наименование:</label> </div>
					<div class="col-lg-5"><input type='text' id = "name_tree" size = '70'><input type='hidden' id = "id_tree">  </div>
					</div>
					<div class='row'>
					<div class="col-lg-2"><label>Узел:</label> </div>
					<div class="col-lg-5">
					<div id = 'viewtree'></div>
					</div>
					</div>
					<div class='row' >
					<div class="col-lg-3"><label>Использовать в наименование:</label> </div>
					<div class="col-lg-5"><input type="checkbox" id = "chek"  />
					</div>	</div>
					<div class='row' style="display: none;">
					<div class="col-lg-2"><label>Материалы:</label> </div>
					<div class="col-lg-5">			
					<div id='list_mat'>
					
					</div>
					<div id='list_mat1'>
					
					</div>
					
					</div>
					</div>	
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="vi_tree()"><button type="button" class="btn btn-primary">Добавить</button></a>
					</div>
					</div>
					</div>
					</div>
					</div>	
					<!-- /#wrapper -->
					
					<script type="text/javascript" src="../vendor/img/tooltip.js"></script>
					
					<!-- jQuery-->
					
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
					$('#eqFormat').multiselect({
					enableClickableOptGroups: true
					});
					});
					$("#SimpleJSTree").bind(
					"select_node.jstree", function(evt, data){
					document.getElementById("list_tree").innerHTML = "";
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id : data.node.id,
					flag : '30'
					},  success:function (datsa) {//возвращаемый результат от сервера
					document.getElementById("list_tree").innerHTML =  "<h3>" + data.node.text + "</h3><hr>" + datsa;
					
					}
					});
					}
					);
					</script>
					
					<script>
					$(document).on('input', '[id="nameMaterial"]', function () {
					var $item = $(this),
					value = $item.val();
					
					document.getElementById('plugins4_q').value = value
					$('#SimpleJSTree').jstree(true).search(value);
					});
					
					var default_tb_firms = {
					"iDisplayLength": 25,
					"ajax" : {
					"url" : 'ajax_php_sql.php?flag=7',
					"type" : "GET",
					"dataSrc": ""
					},
					"columns": [
					{ "data": "rev"},
					{ "data": "name"},
					{ "data": "unp"},
					]  ,
					"aaSorting": [[0,'abs']]
					};
					
					var tb_firms = $('#tb_firms').dataTable(default_tb_firms)	;
					
					
					var default_tb_mat = {
					"iDisplayLength": 25,
					"ajax" : {
					"url" : 'ajax_php_sql.php?flag=9',
					"type" : "GET",
					"dataSrc": ""
					},
					
					"columns": [
					{ "data": "CH"},
					{ "data": "q",  "className":	'details-control'},
					{ "data": "edit"},
					{ "data": "MT_TYPE"},
					{ "data": "M_NAME"},
					{ "data": "M_UNIT"},
					{ "data": "M_SIZE"},
					{ "data": "M_KOL_ALL"},
					{ "data": "M_PRICE"},
					{ "data": "YZEL"},
					
					]  ,
					"aaSorting": [[0,'abs']]
					};
					
					var tb_mat = $('#tb_mat').dataTable(default_tb_mat)	;
					
					
					var default_tb_ttn = {
					"iDisplayLength": 25,
					"ajax" : {
					"url" : 'ajax_php_sql.php?flag=14',
					"type" : "GET",
					"dataSrc": ""
					},
					
					"columns": [
					{ "data": "q",  "className":	'details-control'},
					{ "data": "rev"},
					{ "data": "date" , "sType": "ruDate"},
					{ "data": "num"},
					{ "data": "seria"},
					{ "data": "name_f"},
					{ "data": "sums"},
					{ "data": "eur"},
					]  ,
					"aaSorting": [[2,'desc']]
					};
					
					var tb_ttn = $('#tb_ttn').dataTable(default_tb_ttn)	;
					
					function  edit_mat(id){
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id : id,
					flag : '220'
					},  success:function (data) {//возвращаемый результат от сервера
					par = data.split('|');
					$("#nameMaterial_edit").val(par[0]);
					$("#izmMaterial_edit").val(par[1]);
					$("#sizeMaterial_edit").val(par[2]);
					$('#priceMaterial_edit').val(par[3]);
					$('#tolMaterial_edit').val(par[4]);
					arrpar = par[5].split(',');
					for(i = 0; i < arrpar.length; i++ ){
					console.log(arrpar[i]);
					}
					
					var select = document.getElementById( 'eqFormat' );
					
					for ( var i = 0, l = select.options.length, o; i < l; i++ )
					{
					o = select.options[i];
					if ( arrpar.indexOf( o.value ) != -1 )
					{
					o.selected = true;
					}
					}
					$('#eqFormat').multiselect('refresh');
					$('#idMaterial').val(id);
					$('#edit_mat').modal('show');
					}
					});
					
					}
					
					function save_mat(){
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					nameMaterial_edit 	:	$("#nameMaterial_edit").val(),
					izmMaterial_edit 		:	$("#izmMaterial_edit").val(),
					sizeMaterial_edit 	: 	$("#sizeMaterial_edit").val(),
					priceMaterial_edit 	: 	$('#priceMaterial_edit').val().replace(',', '.'),
					tolMaterial_edit 		: 	$('#tolMaterial_edit').val(),
					idMaterial 				: 	$('#idMaterial').val(),
					eqFormat             :  $('#eqFormat').val().toString(),
					flag 						: 	'221'
					},  success:function (data) {//возвращаемый результат от сервера
					$('#edit_mat').modal('hide');
					tb_mat.fnClearTable();
					tb_mat.DataTable().ajax.reload();
					}
					});
					
					}
					function fun2() {
					var rad=document.getElementsByName('r2');
					for (var i=0;i<rad.length; i++) {
					var input = rad[i];
					ref_tree();
					document.getElementById('list_tree' ).innerHTML = "";
					if(rad[i].checked){
					if(input.value == 'n'){
					document.getElementById('block1' ).style.display = 'none';
					document.getElementById('block2' ).style.display = 'block'; 
					document.getElementById('she1' ).style.display = 'block'; 
					} else {
					document.getElementById('block1' ).style.display = 'block';
					document.getElementById('block2' ).style.display = 'none'; 
					document.getElementById('she1' ).style.display = 'none'; 
					}
					}
					}
					}
					function addtn(){
					$("#add_bnt1").attr('disabled', false);
					$("#edit_bnt").attr('disabled', 'disabled');	
					for (var i = document.getElementById('tb_list_mat').getElementsByTagName('tr').length -1; i; i--) {
					document.getElementById('tb_list_mat').deleteRow(i);
					}
					$("#all_sm").val('');
					$("#cod_firm").val('0') ;
					$("#num_ttn").val('');
					$("#seriass").val('');
					
					$("#date_").val('');
					$("#num_ttn").attr('disabled', 'disabled');
					$("#date_").attr('disabled', 'disabled');
					$("#add_bnt").attr('disabled', 'disabled');
					$("#seriass").attr('disabled', 'disabled');
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag : '8'
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById('list_firm').innerHTML = data;
					$("#cod_firm").chosen();
					
					document.getElementById('cod_firm_chosen').style.width = '500px';
					f_lock();
					$('#add_tn').modal('show');
					
					}
					});
					
					
					}
					
					
					function addfirm(){
					$("#name_firm").val('')
					$("#unp_firm").val('')
					$("#id_firm").val(0)
					$('#add_firm').modal('show');
					}
					function edit_firm(id){
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag : '219',
					id : id
					},  success:function (data) {//возвращаемый результат от сервера
					par = data.split('|');
					
					$("#name_firm").val(par[0])
					$("#unp_firm").val(par[1])
					$("#id_firm").val(id)
					$('#add_firm').modal('show');
					}
					});
					
					}
					function add_new_firm(){

					
					
					$.get( 'ajax_php_sql.php', {unp: $("#unp_firm").val(), flag: '227'}, function (data) {
					 data1 =  data.replace(/^\s+|\s+$/gm,'');
					console.log( $("#unp_firm").val() + " " + typeof data + " |"  + data + "|" + " |"  + data1 + "|")
					console.log(data1 != '0')
					if(data1 != '0' && $("#id_firm").val() == '0'){
				 
					alert("Внимание контрогент с таким УНП уже существует!");
					return;
			 
					} else {
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					name : $("#name_firm").val(),
					unp_firm : $("#unp_firm").val(),
					id : $("#id_firm").val() ,
					flag : '6'
					},  success:function (data) {//возвращаемый результат от сервера
					$('#add_firm').modal('hide');
					tb_firms.fnClearTable();
					tb_firms.DataTable().ajax.reload();
					}
					});
					
					}
					});
					
					
					}
					
					function _matArh(){
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
					url: 'ajax_php_sql.php',
					data: {
					id1 : str_id,
					flag: '222'
					},  success:function (data) {//возвращаемый результат от сервера
					
					tb_mat.fnClearTable();
					tb_mat.DataTable().ajax.reload();
					
					}
					});
					}else {
					alert('не выбраны материалы для архива!')
					}
					
					}
					
					function _rev_mat(){
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
					url: 'ajax_php_sql.php',
					data: {
					id1 : str_id,
					flag: '10'
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById('pj').innerHTML = data;
					
					$('#list_mat').modal('show');
					}
					});
					}else {
					alert('не выбраны материалы!')
					}
					}
					
					
					function _add_plan_job2(){
					var nodeList = document.getElementsByName('chjob2');
					var array = Array.prototype.slice.call(nodeList);
					srt_pj = "";
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					kol = 0;
					kol = Number(document.getElementById('total_'+array[i].value).innerHTML) - Number(document.getElementById('total'+array[i].value).value) ;
					srt_pj =	srt_pj  + array[i].value + "^" + kol +  "^" + document.getElementById('total'+array[i].value).value +   "^r|";
					}
					}
					srt_pj = srt_pj.slice(0, -1);
					if (srt_pj != ""){
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id1 : srt_pj,
					flag: '11'
					},  success:function (data) {//возвращаемый результат от сервера
					$('#list_mat').modal('hide');
					$("input[name='chjob']").prop('checked', false);
					tb_mat.fnClearTable();
					tb_mat.DataTable().ajax.reload();
					}
					});
					
					}else {
					alert('не выбраны материалы!')
					}
					}
					
					
					function _add_plan_job3(){
					var nodeList = document.getElementsByName('chjob2');
					var array = Array.prototype.slice.call(nodeList);
					srt_pj = "";
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					kol = 0;
					kol = Number(document.getElementById('total'+array[i].value).value) + Number(document.getElementById('total_'+array[i].value).innerHTML);
					srt_pj =	srt_pj  + array[i].value + "^" + kol +  "^" + document.getElementById('total'+array[i].value).value +   "^p|";
					}
					}
					srt_pj = srt_pj.slice(0, -1);
					if (srt_pj != ""){
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id1 : srt_pj,
					flag: '11'
					},  success:function (data) {//возвращаемый результат от сервера
					$('#list_mat').modal('hide');
					$("input[name='chjob']").prop('checked', false);
					tb_mat.fnClearTable();
					tb_mat.DataTable().ajax.reload();
					}
					});
					
					}else {
					alert('не выбраны материалы!')
					}
					}
					
					function add_mat_ttn(){
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag: '12'
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById("she").innerHTML = data;
					she1
					$("#op1").prop('checked', true);
					fun2();
					$('#add_ttn_mat').modal('show');
					}
					});
					
					} 
					
					
					
					
					function add_mat_tn(){
					var rad=document.getElementsByName('r2');
					for (var i=0;i<rad.length; i++) {
					var input = rad[i];
					if(rad[i].checked){
					if(input.value == 'n'){
					/*Добавляем новый материал */
					
					if ($("#type_mat").val() == ''){
					alert("Не выбран тип материала!")
					return;
					} 
					if ($("#nameMaterial").val() == ''){
					alert("Не введено наименования материала!")
					return;
					} 
					if ($("#priceMaterial").val() == ''){
					alert("Не введена цена!")
					return;
					} 
					if ($("#izmMaterials").val() == ""){
					alert('У материала не выбрана единица измерения!')
					return
					}
					
					if ($("#SimpleJSTree").jstree("get_selected") == "")	{
					alert('Веберите узел. Если нету, то создать!')
					return
					}
					
	
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					type_mat : $("#type_mat").val(),
					nameMaterial : $("#nameMaterial").val(),
					izmMaterial : $("#izmMaterial").val(),
					sizeMaterial : $("#sizeMaterial").val(),
					priceMaterial : $("#priceMaterial").val(),
					tree_id      : $("#SimpleJSTree").jstree("get_selected")[0],
					flag: '15'
					},  success:function (data) {//возвращаемый результат от сервера
					max = 9;
					min = 1;
					flafs = Math.round(Math.random()*(max-min)+min);
					addRow(data, $("#nameMaterial").val(),flafs);
					}
					});
					
					
					
					/*-------------------------*/
					} else {
					if ($("#add_mat").val() == ''){
					alert("Выберите материал!")
					return;
					} else {
					max = 9;
					min = 1;
					flafs = Math.round(Math.random()*(max-min)+min);
					addRow($("#add_mat").val(), $("#add_mat :selected").text(),flafs);
					}
					
					}
					}
					}
					}
					
					
					 

					function addRow(id, name, flafs)  // функция для добавления строки к части шаблона
					{
					console.log(id + " " + name  + " " + flafs)
					id =  id.replace(/^\s+|\s+$/gm,'');
//flafs =  flafs.replace(/^\s+|\s+$/gm,'');
					
					//alert(id + " " + flafs)
					var tbl = document.getElementById ('tb_list_mat');                   // таблица, с которой работаем
					var rws = tbl.rows;                                            // коллекция существующих строк таблицы
					var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
					var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
					//console.log(cls);
					var ro = tbl.insertRow (-1);                                   // добавляем снизу ОБРАЗ ещё одной строки
					var ce = ro.insertCell (-1);
					ce.innerHTML = "<input type='checkbox' checked name='chDel' value='" + id + flafs + "'>";
					ce = ro.insertCell (-1);
					ce.innerHTML = "<label>" + name + "</label>";
					ce = ro.insertCell (-1);
					ce.innerHTML = '	<div class="row"><div class="col-md-12"><input type="text" id="name_ttn_' + id + flafs + '" style="width:100%;"></div></div>	';
					ce = ro.insertCell (-1);
					
					
					ce.innerHTML = "<select id='izmMaterials" + id + flafs + "' name = 'chk_unit' style='width:50%;'>" + document.getElementById('izmMaterial').innerHTML + "</select>";
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input type='text' id='totals_" + id + flafs + "'  value='' name = 'chk_total' style='width:50%;'>";
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input type='text' id='totals_ttn" + id + flafs + "'  value='' name = 'chk_total' style='width:50%;'>";
					ce = ro.insertCell (-1);
					// ce.innerHTML = "<input type='text'  id='prices_" + id + "' value='' name = 'chk_price'>";
					// ce = ro.insertCell (-1);
					ce.innerHTML = "<input type='text' id='all_sums_" + id + flafs + "' value='' name = 'chk_summs' onblur='comp_sum()' style='width:50%;'>";
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input type='text' id='cod_mat" + id + flafs + "'  value='' name = 'chk_total' style='width:50%;'>";
					$('#add_ttn_mat').modal('hide');
					comp_sum();
					}
					function f_lock(){
					/*if ($("#cod_firm").val() == "0"){
					alert('Не выбран поставщик!')
					
					return
					}
					else {*/
					$("#num_ttn").attr('disabled', false);
					$("#date_").attr('disabled', false);
					$("#seriass").attr('disabled', false);
					$("#add_bnt").attr('disabled', false);
					//}
					}
					
					function comp_sum(){
					summ = 0;
					var nodeList = document.getElementsByName('chDel');
					var array = Array.prototype.slice.call(nodeList);
					console.log(array)
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					srt = $("#all_sums_" +array[i].value ).val();
							console.log("srt" +  srt)
					srt = srt.replace(",",".");
					summ = Number(summ) + Number(srt);
					summ = summ.toFixed(2);
					}
					}
					
					document.getElementById('all_sm').value = summ;
					}
					
					function add_ttn_base(){
					
					/*Проверка основыных параметров ТТН*/
					if ($("#cod_firm").val() == "0"){
					alert('Не выбран поставщик!')
					return
					}
					if ($("#num_ttn").val() == ""){
					alert('Не введен номер ТТН!')
					return
					}
					if ($("#seriass").val() == ""){
					alert('Не введена серия ТН!')
					return
					}
					if (document.getElementById('date_').value == "0" || document.getElementById('date_').value == "" ) { 
					alert("Не задана дата готовности!") 
					return
					} 
					
					/*Проверка материалов*/
					
					var nodeList = document.getElementsByName('chDel');
					var array = Array.prototype.slice.call(nodeList);
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					if ($("#izmMaterials" +array[i].value ).val() == ""){
					alert('У материала не выбрана единица измерения!')
					return
					}
					if ($("#totals_" +array[i].value ).val() == ""){
					alert('У материала не введено кол-во!')
					return
					}
					// if ($("#prices_" +array[i].value ).val() == ""){
					// alert('У материала не введена сумма за единицу!')
					// return
					// }
					if ($("#all_sums_" +array[i].value ).val() == ""){
					alert('У материала не введена общая сумма')
					return
					}
					}
					}
					
					comp_sum();
					/*Если все проверки прошли*/
					srt_pj = "";
					var nodeList = document.getElementsByName('chDel');
					var array = Array.prototype.slice.call(nodeList);
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					srt_pj1 = array[i].value.slice(0, -1);
					srt_pj =	srt_pj  + srt_pj1 + "^" + $("#izmMaterials" +array[i].value ).val() + "^" + $("#totals_" +array[i].value ).val() + "^" + $("#all_sums_" +array[i].value ).val()  + "^" + $("#name_ttn_" +array[i].value ).val() +   "^" + $("#totals_ttn" +array[i].value ).val() +  "^" + $("#cod_mat" +array[i].value ).val() + "|"; 
					
					}
					}
					srt_pj = srt_pj.slice(0, -1);
					
					if (srt_pj != ""){
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					cod_firm : document.getElementById('cod_firm').value,
					num_ttn  : document.getElementById('num_ttn').value,
					date_    : document.getElementById('date_').value,
					all_sm   : document.getElementById('all_sm').value,
					seria    : document.getElementById('seriass').value,
					id1      : srt_pj,
					flag     : '13'
					},  success:function (data) {//возвращаемый результат от сервера
					tb_ttn.fnClearTable();
					tb_ttn.DataTable().ajax.reload();
					tb_mat.fnClearTable();
					tb_mat.DataTable().ajax.reload();
					$('#add_tn').modal('hide');
					}
					});
					
					}else {
					alert('не выбраны материалы!')
					}
					
					
					
					
					}
					
					/*$(document).ready(function() {
					$('#add_mat').multiselect({
					enableClickableOptGroups: true
					});
					});*/
					
					function edit_ttn(id){
					$("#edit_bnt").attr('disabled', false);
					$("#add_bnt1").attr('disabled', 'disabled');			
					for (var i = document.getElementById('tb_list_mat').getElementsByTagName('tr').length -1; i; i--) {
					document.getElementById('tb_list_mat').deleteRow(i);
					}
					$("#all_sm").val('');
					$("#cod_firm").val('0') ;
					$("#num_ttn").val('');
					$("#seriass").val('');
					$("#date_").val('');
					$("#num_ttn").attr('disabled', false);
					$("#date_").attr('disabled', false);
					$("#add_bnt").attr('disabled', false);			
					$("#seriass").attr('disabled', false);
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag : '8'
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById('list_firm').innerHTML = data;
					
					}
					});
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag: '12'
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById("she").innerHTML = data;
					
					}
					});
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag : '16',
					id : id
					},  success:function (data) {//возвращаемый результат от сервера
					
					
					
					var arr = data.split('!');
					
					attr_ttn = arr[0].split('^');
					//id, num,cod_firm,dt,all_sum, seria
					$("#id_rev").val(attr_ttn[0]);
					$("#num_ttn").val(attr_ttn[1]);
					$("#cod_firm").val(attr_ttn[2]);
					$("#cod_firm").chosen();
					document.getElementById('cod_firm_chosen').style.width = '500px';
					
					$("#date_").val(attr_ttn[3]);
					$("#all_sm").val(attr_ttn[4]);
					$("#seriass").val(attr_ttn[5]);
					if (arr[1] != ""){
					
					str_id = arr[1].slice(0, -1);
					attr_ttn_mat = str_id.split('|');
					
					console.log(attr_ttn_mat)
					
					for (i = 0; i < attr_ttn_mat.length; i ++){
					attr_mat = attr_ttn_mat[i].split('^');
					max = 9;
					min = 1;
					flafs = Math.round(Math.random()*(max-min)+min);
					addRow(attr_mat[0],attr_mat[1],flafs);
					$("#izmMaterials" + attr_mat[0] + flafs).val(attr_mat[3]) ;
					$("#totals_" + attr_mat[0] + flafs).val(attr_mat[2]);
					$("#all_sums_" + attr_mat[0] + flafs).val(attr_mat[4]) ;
					$("#name_ttn_" + attr_mat[0] + flafs).val(attr_mat[5]) ;
					$("#totals_ttn" + attr_mat[0] + flafs).val(attr_mat[6]) ;
					$("#cod_mat" + attr_mat[0] + flafs).val(attr_mat[7]) ;
					
					}
					}
					comp_sum();
					$('#add_tn').modal('show');
					}
					
					
					});
					
					
					
					}
					
					
					function edit_ttn_base(){
					
					/*Проверка основыных параметров ТТН*/
					if ($("#cod_firm").val() == "0"){
					alert('Не выбран поставщик!')
					return
					}
					if ($("#num_ttn").val() == ""){
					alert('Не введен номер ТТН!')
					return
					}
					if ($("#seriass").val() == ""){
					alert('Не введена серия ТН!')
					return
					}
					if (document.getElementById('date_').value == "0" || document.getElementById('date_').value == "" ) { 
					alert("Не задана дата готовности!") 
					return
					} 
					
					/*Проверка материалов*/
					
					var nodeList = document.getElementsByName('chDel');
					var array = Array.prototype.slice.call(nodeList);
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					if ($("#izmMaterials" +array[i].value ).val() == ""){
					alert('У материала не выбрана единица измерения!')
					return
					}
					if ($("#totals_" +array[i].value ).val() == ""){
					alert('У материала не введено кол-во!')
					return
					}
					// if ($("#prices_" +array[i].value ).val() == ""){
					// alert('У материала не введена сумма за единицу!')
					// return
					// }
					if ($("#all_sums_" +array[i].value ).val() == ""){
					alert('У материала не введена общая сумма')
					return
					}
					}
					}
					
					comp_sum();
					/*Если все проверки прошли*/
					srt_pj = "";
					var nodeList = document.getElementsByName('chDel');
					var array = Array.prototype.slice.call(nodeList);
					for (var i = 0; i < array.length; i++) {
					if (array[i].checked) {
					srt_pj1 = array[i].value.slice(0, -1);
					srt_pj =	srt_pj  + srt_pj1 + "^" + $("#izmMaterials" +array[i].value ).val() + "^" + $("#totals_" +array[i].value ).val() + "^" + $("#all_sums_" +array[i].value ).val() + "^" + $("#name_ttn_" +array[i].value ).val() + "^" + $("#totals_ttn" +array[i].value ).val() + "^" + $("#cod_mat" +array[i].value ).val() + "|"; 
					}
					}
					srt_pj = srt_pj.slice(0, -1);
					
					if (srt_pj != ""){
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					cod_firm : document.getElementById('cod_firm').value,
					num_ttn  : document.getElementById('num_ttn').value,
					date_    : document.getElementById('date_').value,
					all_sm   : document.getElementById('all_sm').value,
					seria    : document.getElementById('seriass').value,
					id_rev    : document.getElementById('id_rev').value,
					id1      : srt_pj,
					flag     : '17'
					},  success:function (data) {//возвращаемый результат от сервера
					tb_ttn.fnClearTable();
					tb_ttn.DataTable().ajax.reload();
					tb_mat.fnClearTable();
					tb_mat.DataTable().ajax.reload();
					$('#add_tn').modal('hide');
					}
					});
					
					}else {
					alert('не выбраны материалы!')
					}
					
					
					
					
					}
					
					
					function add_oplat(id_ord, id_cl , id){
					
					$('#client').val(id_cl); 
					$('#num').val(id); 
					$('#sum').val(""); 
					$("#date_opl").val('<? echo date("Y-m-d")?>');
					document.getElementById("view_opl1").checked = true;
					document.getElementById("list_comm").value = "";
					
					$('#add_opl').modal('show');
					}
					function fun_opl(){
					if (document.getElementById('sum').value == "0" || document.getElementById('sum').value == "" ) { 
					alert("Внесите сумму оплаты!") 
					return
					}
					client=document.getElementById('client').value;
					num=document.getElementById('num').value;
					sum=document.getElementById('sum').value;
					date=document.getElementById('date_opl').value;
					list_comm = document.getElementById('list_comm').value;
					var tableElem = document.getElementById('radio_chk');
					var elements = tableElem.getElementsByTagName('input');
					
					for (var i = 0; i < elements.length; i++) {
					if (elements[i].checked == true ){
					var view_opl1 = elements[i].value 
					}
					}
					
					$.ajax({
					type: "GET",
					url: 'pg/_addOplata.php',
					data: {
					client : client,
					num : num,
					sum : sum,
					date : date,
					view_opl : view_opl1 
					},  success:function (data) {//возвращаемый результат от сервера
					$('#add_opl').modal('hide');
					dTable.DataTable().ajax.url('orders_json.php?dt1=' + parseDateValue2($("#dateStart").val()) + "&" +' dt2=' + parseDateValue2($("#dateEnd").val()));
					dTable.DataTable().ajax.reload();
					}
					});
					
					
					
					
					}
					
					$(function() {
					
					// Add event listener for opening and closing details
					$('#tb_ttn tbody').on('click', 'td.details-control', function () {
					var table = $('#tb_ttn').DataTable();
					
					var tr = $(this).closest('tr');
					
					var tdi = tr.find("i.fa");
					
					var row = table.row(tr);
					
					if (row.child.isShown()) {
					// This row is already open - close it
					row.child.hide();
					tr.removeClass('shown');
					tdi.first().removeClass('fa-minus-square');
					tdi.first().addClass('fa-plus-square');
					}
					else {
					// Open this row
					// alert(table.row( this ).data().id)
					
					id = format(row.data());
					list_prod_num = "";
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id : id,
					flag : '22'
					},  success:function (data) {//возвращаемый результат от сервера
					list_prod_num = data;
					row.child(list_prod_num).show();
					tr.addClass('shown');
					tdi.first().removeClass('fa-plus-square');
					tdi.first().addClass('fa-minus-square');
					}
					});
					
					
					
					}
					});
					
					$('#tb_mat tbody').on('click', 'td.details-control', function () {
					var table = $('#tb_mat').DataTable();
					
					var tr = $(this).closest('tr');
					
					var tdi = tr.find("i.fa");
					
					var row = table.row(tr);
					
					if (row.child.isShown()) {
					// This row is already open - close it
					row.child.hide();
					tr.removeClass('shown');
					tdi.first().removeClass('fa-minus-square');
					tdi.first().addClass('fa-plus-square');
					}
					else {
					// Open this row
					// alert(table.row( this ).data().id)
					
					id = format(row.data());
					list_prod_num = "";
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id : id,
					flag : '49'
					},  success:function (data) {//возвращаемый результат от сервера
					list_prod_num = data;
					row.child(list_prod_num).show();
					tr.addClass('shown');
					tdi.first().removeClass('fa-plus-square');
					tdi.first().addClass('fa-minus-square');
					}
					});
					
					
					
					}
					});
					function format(d){
					
					// `d` is the original data object for the row
					return d.id;  
					}
					
					
					});
					
					jQuery.extend( jQuery.fn.dataTableExt.oSort, {
					"ruDate-asc": function ( a, b ) {
					var ruDatea = $.trim(a).split('.');
					var ruDateb = $.trim(b).split('.');
					
					if(ruDatea[2]*1 < ruDateb[2]*1)
					return 1;
					if(ruDatea[2]*1 > ruDateb[2]*1)
					return -1;
					if(ruDatea[2]*1 == ruDateb[2]*1)
					{
					if(ruDatea[1]*1 < ruDateb[1]*1)
					return 1;
					if(ruDatea[1]*1 > ruDateb[1]*1)
					return -1;
					if(ruDatea[1]*1 == ruDateb[1]*1)
					{
					if(ruDatea[0]*1 < ruDateb[0]*1)
					return 1;
					if(ruDatea[0]*1 > ruDateb[0]*1)
					return -1;
					}
					else
					return 0;
					}				
					},
					
					"ruDate-desc": function ( a, b ) {
					var ruDatea = $.trim(a).split('.');
					var ruDateb = $.trim(b).split('.');
					
					if(ruDatea[2]*1 < ruDateb[2]*1)
					return -1;
					if(ruDatea[2]*1 > ruDateb[2]*1)
					return 1;
					if(ruDatea[2]*1 == ruDateb[2]*1)
					{
					if(ruDatea[1]*1 < ruDateb[1]*1)
					return -1;
					if(ruDatea[1]*1 > ruDateb[1]*1)
					return 1;
					if(ruDatea[1]*1 == ruDateb[1]*1)
					{
					if(ruDatea[0]*1 < ruDateb[0]*1)
					return -1;
					if(ruDatea[0]*1 > ruDateb[0]*1)
					return 1;
					}
					else
					return 0;
					}
					}
					});
					
					function spis(){
					
					
					$.ajax({
					type: "GET",
					url: 'pg/proc/spisanie.php',
					data: {
					dt1 : $("#date1").val(),
					dt2 : $("#date2").val()
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById("lists").innerHTML = data;
					}
					});
					
					
					}
					
					
					function newTree(id){
					
					document.getElementById("list_mat1").innerHTML = "";
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag : '24'
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById("viewtree").innerHTML = '';
					document.getElementById("viewtree").innerHTML = data;
					$('#name_tree').val('');
					$('#uz_tree').attr('disabled','false');
					$('#uz_tree').val('');
					$('#id_tree').val('');
					$("#chek").prop('checked', false);
					$('#optgroup').val('');
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag : '29'
					},  success:function (data) {//возвращаемый результат от сервера
					
					document.getElementById("list_mat").innerHTML = "<select  id = 'optgroup'  data-style='btn-default' multiple  name='optgroup[]' style='height: 300px;width 300px'>" + data + "</select>";
					$('#uz_tree').val(id);	
					$('#uz_tree').attr('disabled','disabled');
					$('#addTree').modal('show');	
					}
					});
					
					
					
					}
					});
					}
					
					function vi_tree(){
					
					if ($('#id_tree').val() == ""){
					add_tree();
					} else {
					edit_tree();
					}
					}
					//добавть узел
					function add_tree(){
					
					
					chek = 0;
					if(document.getElementById('chek').checked){
					chek = 1;
					}
					//document.getElementById("tree").innerHTML = "";
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					name : $('#name_tree').val(),
					uz_num : $('#uz_tree').val(),
					chek : chek,
					option : $('#optgroup').val(),
					flag : '25'
					},  success:function (data) {//возвращаемый результат от сервера
					//	document.getElementById("tree").innerHTML = data;
					$('#addTree').modal('hide');	
					ref_tree();
					}
					});
					
					
					
					
					}
					//сохранить узел
					function edit_tree(){
					chek = 0;
					if(document.getElementById('chek').checked){
					chek = 1;
					}
					
					//document.getElementById("tree").innerHTML = "";
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					name : $('#name_tree').val(),
					uz_num : $('#uz_tree').val(),
					id_tree : $('#id_tree').val(),
					chek : chek,
					option : $('#optgroup').val(),
					flag : '26'
					},  success:function (data) {//возвращаемый результат от сервера
					//	document.getElementById("tree").innerHTML = data;
					$('#addTree').modal('hide');
					ref_tree();
					}
					});
					}
					// удалить материал с узла
					function _delgoods(id){
					
					//document.getElementById("tree").innerHTML = "";
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id : id,
					flag : '32'
					},  success:function (data) {//возвращаемый результат от сервера
					//		 document.getElementById("tree").innerHTML = data;
					document.getElementById(id).style.display = 'none'; 
					}
					});
					}
					
					// удалить узел
					function _delklass(id){
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id_tree : id,
					flag : '28'
					},  success:function (data) {//возвращаемый результат от сервера
					ref_tree();
					}
					});
					}
					
					// редактировать узел
					function _editklass(id){
					flafa = "";
					$('#id_tree').val(id)
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag : '24'
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById("viewtree").innerHTML = '';
					document.getElementById("viewtree").innerHTML = data;
					$('#name_tree').val('');
					$('#uz_tree').attr('disabled','false');
					$('#uz_tree').val('');
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id_tree : id,
					flag : '27'
					},  success:function (data) {//возвращаемый результат от сервера
					flafa = data.split('`');
					$('#name_tree').val(flafa[0]);
					$('#uz_tree').val(flafa[1]);
					$("#chek").prop('checked', false);
					if(flafa[2] == '1'){
					$("#chek").prop('checked', true);
					}
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					flag : '29'
					},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById("list_mat").innerHTML = "<select  id = 'optgroup'  data-style='btn-default' multiple  name='optgroup[]' style='height: 300px;width 300px'>" + data + "</select>";
					
					$.ajax({
					type: "GET",
					url: 'ajax_php_sql.php',
					data: {
					id : id,
					flag : '30'
					},  success:function (data) {//возвращаемый результат от сервера
					//alert(data);
					document.getElementById("list_mat1").innerHTML = "";
					document.getElementById("list_mat1").innerHTML = data;
					
					$('#uz_tree').attr('disabled','disabled');
					$('#addTree').modal('show');	
					}
					}); 
					}
					});
					}
					});
					}
					});
					}
					
					function ref_tree() {
					
					$.ajax({
					async: true,
					type: "GET",
					url: "ajax_php_sql.php?flag=31",
					dataType: "json",
					success: function (json) {
					$('#SimpleJSTree').jstree(true).settings.core.data = json;
					$('#SimpleJSTree').jstree(true).refresh();	
					
					},
					
					error: function (xhr, ajaxOptions, thrownError) {
					alert(xhr.status);
					alert(thrownError);
					}
					});  
					
					
					
					}
					</script>	
					
					<script src="../vendor/chosen.jquery.min.js"></script>
					
					
					
					
					
					
					
					
					
					</body>
					
					</html>
										