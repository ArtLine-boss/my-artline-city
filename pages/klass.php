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
		<link href="../vendor/bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css">
		
	 	<script src="../vendor/jquery/jquery.min.js"></script> 
		<script src="../js/jquery-ui.min.js"></script>
		<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
		<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
		<script src="../js/funJs.js"></script>
		<script src="../vendor/bootstrap/js/bootstrap-select.js" type = "text/javascript"></script>
		<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="../vendor/metisMenu/metisMenu.min.js"></script>
		<script src="../dist/js/sb-admin-2.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jstree/3.2.1/themes/default/style.min.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.1/jquery.min.js"></script>
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
						</head>
						
						<body>
						<div id="wrapper">
						<?php
						include_once("menu.php");
						
						?>
						<div id="page-wrapper">
						<div class="row">
						<div class="col-lg-12">
						<h2 class="page-header">Классификатор</h2>
						</div>
						</div>
						
						<div class = 'row'>
						<div class="col-lg-12">
						&nbsp;
						</div>
						
						</div>
						<div class = 'row'>
						<div class="col-lg-12">
						&nbsp;
						</div>
						
						</div>
						
						<div class = 'row'>
						<div class="col-lg-4">
						<label>Поиск: <input type="text" id="plugins4_q" value="" class="input" style="margin:0em auto 1em auto; display:block; padding:4px; border-radius:4px; border:1px solid silver;"></label>
						<div  id="SimpleJSTree"></div>
						</div>
						
						<div class="col-lg-4" id="list_tree"> </div>
						
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
						<div class='row'>
						<div class="col-lg-3"><label>Использовать в наименование:</label> </div>
						<div class="col-lg-5"><input type="checkbox" id = "chek"  />
						</div>	</div>
						<div class='row'>
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
						<!----------------------------------------------------------------------------------------------------------->
						
						<script>
						
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
						
						
						<script src="../vendor/bootstrap/js/bootstrap-select.js" type = "text/javascript"></script>
						<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
						<script>
						//Добавить новый узел
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
						</body>
						
						</html>
												