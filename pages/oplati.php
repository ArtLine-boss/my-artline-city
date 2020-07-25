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
		
		<!-- Bootstrap Core CSS -->
		<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		
		<!-- MetisMenu CSS -->
		<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		
		<!-- Custom CSS -->
		<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
		
		<!-- Morris Charts CSS -->
		<link href="../vendor/morrisjs/morris.css" rel="stylesheet">
		
		<!-- Custom Fonts -->
		<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../vendor/bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css">
	</head>
	
	<body>
   	<?php
			include_once("menu.php");
			
		?>
		
		<div id="page-wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h2 class="page-header">Оплаты</h2>
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<div id='row'>
				<div class="col-md-6">
					C <input type='date' id='date1' name='date'>	 
					ПО <input type='date' id='date2' name='date'>
					
					<button type="button" class="btn btn-info btn-circle" onclick='opl_sum()' >
						
						<i class="fa fa-search"></i> 
					</button>
					
					<button type='button' class='btn btn-info btn-circle'  onclick='downloadCSV({ filename: "stock-data.csv"});'>
						<i class="fa fa-th-list  "></i> 
					</button>
					
				</div>  
			</div>
			<div class="row">
				<div class="col-lg-12">
					<div class="panel panel-default">
						<a onClick="">
						<button type="button" class="btn btn-default"   onclick="edit_opl('0','1')"><span class="glyphicon glyphicon-plus-sign"></span>Приход</button></a>
						<a onClick="">
						<button type="button" class="btn btn-default" onclick="edit_opl('0','5')"><span class="glyphicon glyphicon-plus-sign"></span>Расход</button></a>
						<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
							
							<thead>
								<tr>
									<th></th>
									<th>Клиент</th>
									<th>Номер заказа</th>
									<th>Расход</th>
									<th>Приход</th>
									<th>Дата оплаты</th>
									<th>Вид</th>
									<th>Comment</th>
								</tr>
							</thead>
							
							
						</table>
					</div>
					<!-- /.panel -->
				</div>
				<!-- /.col-lg-12 -->
			</div>
			<!-- /.row -->
			<!-- /.row -->
			
			
			
			<!-- /#wrapper -->
			
			<div id="myModal" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title">Добавление оплаты</h4>
						</div>
						
						<div class="modal-body">
							
							<div class="row"><div class="col-md-3"><div class="block1">
								<?php
									
									print  "<label>Клиент:</label></div></div><div class='col-md-6'><div class='block1'>";
									
									print "<select id = 'client' class='selectpicker' data-live-search='true'>";
									print "<option value=''></option>"; 
									include 'db.php';
									$query="select id,client_name from clients";
									$result = mysql_query($query) or die("Query failed");
									while ($row = mysql_fetch_row($result)) { 
									print "<option value='$row[0]'>$row[1]</option>"; }
									print "</select></div></div></div>";
								?>
								
								<div class="row"><div class="col-md-3"><div class="block1"><label>№ заказа:</label></div></div>
								<div class="col-md-3"><div class="block1"><input  id = 'num' type='text' value = '0'/></div></div></div>
								<div class="row"><div class="col-md-3"><div class="block1"><label>Сумма:</label></div></div>
								<div class="col-md-3"><div class="block1"><input  id = 'sum' type='text' value = '0'/></div></div></div>
								<div class='row'><div class='col-md-3'><div class='block1'><label >Дата:</label></div></div>
								<div class='col-md-2'><div class='block1'><input type='date' id='date' name='date'>		</div></div></div>
								<div class='row'><div class='col-md-3'><div class='block1'><label >Вид:</label></div></div>
									<div class='col-md-9'><div class='block1'>
										<div class="form-group"  id ='radio_chk'>
											<label class="radio-inline" >
												<input type="radio" name="view_opl" id="view_opl1" value="1" checked>Касса
											</label>
											<label class="radio-inline">
												<input type="radio" name="view_opl" id="view_opl2" value="2">Терминал
											</label>
											<label class="radio-inline">
												<input type="radio" name="view_opl" id="view_opl3" value="3">безнал
											</label>
											<label class="radio-inline">
												<input type="radio" name="view_opl" id="view_opl4" value="4">Наличные
											</label>
										</div>
									</div></div></div>
									<div class='row'><div class='col-md-3'><div class='block1'><label >Комментарий:</label></div></div>
										<div class='col-md-9'><textarea class="form-control" rows="3" id = 'list_comm'></textarea>	<input type='hidden' id='id_opl' value = '0'></div>
									</div>
							</div>
							
							
							
							<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
							<a onclick="save_opl(1)"><button type="button" class="btn btn-primary" >Сохранить</button></a></div>
							</div>
							</div>
						</div>
						
						<div id="myModal1" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
										<h4 class="modal-title">Добавление оплаты</h4>
									</div>
									
									<div class="modal-body">
										
										<div class="row"><div class="col-md-3"><div class="block1">
											<?php
												
												print  "<label>Клиент:</label></div></div><div class='col-md-6'><div class='block1'>";
												
												print "<select id = 'client1' class='selectpicker' data-live-search='true'>";
												print "<option value=''></option>"; 
												include 'db.php';
												$query="select id,FIRM_NAME from firms";
												$result = mysql_query($query) or die("Query failed");
												while ($row = mysql_fetch_row($result)) { 
												print "<option value='$row[0]'>$row[1]</option>"; }
												print "</select></div></div></div>";
											?>
											
											
											<div class="row"><div class="col-md-3"><div class="block1"><label>Сумма:</label></div></div>
											<div class="col-md-3"><div class="block1"><input  id = 'sum1' type='text' value = '0'/></div></div></div>
											<div class='row'><div class='col-md-3'><div class='block1'><label >Дата:</label></div></div>
											<div class='col-md-2'><div class='block1'><input type='date' id='daterrr' name='date'>		</div></div></div>
											
											<div class='row'><div class='col-md-3'><div class='block1'><label >Комментарий:</label></div></div>
											<div class='col-md-9'><textarea class="form-control" rows="3" id = 'list_comm1'></textarea><input type='hidden' id='id_opl1' value = '0'>	</div></div>
											
											
											
											
											<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
											<a onclick="save_opl(2)"><button type="button" class="btn btn-primary" >Сохранить</button></a></div>
										</div>
										</div>
										</div>
										
										<!-- jQuery-->
										
										<script src="../vendor/jquery/jquery.min.js"></script> 
										<script src="../js/funJs.js"></script>
										<script src="../vendor/bootstrap/js/bootstrap-select.js" type = "text/javascript"></script>
										<!-- Bootstrap Core JavaScript -->
										<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
										
										<!-- Metis Menu Plugin JavaScript -->
										<script src="../vendor/metisMenu/metisMenu.min.js"></script>
										
										
										<!-- Custom Theme JavaScript -->
										<script src="../dist/js/sb-admin-2.js"></script>
										
										<!-- DataTables JavaScript -->
										<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
										<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
										<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
										<!-- Page-Level Demo Scripts - Tables - Use for reference -->
										
										<script>
											
											//обнуление ид
											$(document).ready(function(){
												$('#myModal').on('hidden.bs.modal', function (e) {
													document.getElementById('id_opl').value = 0;
												});
												
												$('#myModal1').on('hidden.bs.modal', function (e) {
													document.getElementById('id_opl1').value = 0;
												});
											});
											
											var default_options = {
												"responsive": true,
												"paging:": true,								
												
												"iDisplayLength": 25,
												
												
												"ajax" : {
													"url" : 'ajax_php_sql.php?flag=36',
													"type" : "GET",
													"dataSrc": ""
												},
												"columns": [
												{ "data": "flags"},
												{ "data": "td2"},
												{ "data": "td3" },
												{ "data": "td4"},
												{ "data": "td5" },
												{ "data": "td6" 	, "sType": "ruDate"},
												{ "data": "td7" },
												{ "data": "td8" }
												]  ,
												"aaSorting": [[5,'desc']]
											};
											
											var dTable= $('#dataTables-example').dataTable( default_options);
											
											
											
											function downloadCSV(args) {
												var data, filename, link;
												var csv = "";
												
												csv  =  csv  + "Номер заказа; Сумма оплаты;Дата оплаты; Вид оплаты;Наименование продукта;Кол-во;Ед. Изм.;Цена Без ндс;Сумма;\n";	
												date1 = document.getElementById('date1').value;
												date2 = document.getElementById('date2').value;
												$.getJSON("opl_json.php?dt1=" + date1 + "&dt2= " + date2, function(data) {
													
													for (var i in data) {
														
														csv = csv + 	data[i].str ;
														
													}
													if (csv == null) return;
													
													filename = args.filename || 'export.csv';
													
													if (!csv.match(/^data:text\/csv/i)) {
														csv = 'data:text/csv;charset=utf-8,\uFEFF' + csv;
														
													}
													
													data = encodeURI(csv);
													
													link = document.createElement('a');
													link.setAttribute('href',data);
													link.setAttribute('download', filename);
													link.click();
													
												});
												
											}
											
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
											
										</script>
										
										<script>
									/*		$(document).ready(function(){
												$("#date").val('<? echo date("Y-m-d")?>');
												$("#daterrr").val('<? echo date("Y-m-d")?>');
											});
										*/
										
											function edit_opl(id , flag){
												console.log(id + " " + flag)
												if(id == '0' || id == 0){
													if(flag == '5'){
														document.getElementById('client1').value = '';
														$('.selectpicker').selectpicker('refresh'); 
														document.getElementById('sum1').value = '';
														document.getElementById('daterrr').value = '<? echo date("Y-m-d")?>';
														document.getElementById('list_comm1').value = '';
														$('#myModal1').modal('show');
														$('#id_opl1').value = id; 
														}else {
														document.getElementById('client').value  = '';
														$('.selectpicker').selectpicker('refresh'); 
														document.getElementById('num').value  = '';
														document.getElementById('sum').value  = '';
														document.getElementById('date').value  = '<? echo date("Y-m-d")?>';
														document.getElementById('list_comm').value  = '';
														
														$('#myModal').modal('show');
														$('#id_opl').value = id; 
													}
												}
												
												if(id != '0' || id != 0){
													$.ajax({
														type: "GET",
														url: 'ajax_php_sql.php',
														data: {
															flag : 224, 	
															id : id
															},  success:function (data) {//возвращаемый результат от сервера
															str = data.split('|')
															console.log(data);
															if(str[5] != '5'){
																document.getElementById('client').value  = str[0];
																document.getElementById('num').value  = str[1];
																document.getElementById('sum').value  = str[3];
																document.getElementById('date').value  = str[4];
																document.getElementById('list_comm').value  = str[6];
																var tableElem = document.getElementById('radio_chk');
															 	var elements = tableElem.getElementsByTagName('input');
																for (var i = 0; i < elements.length; i++) {
																	if ( elements[i].value == str[5] ){
																		elements[i].checked = true;
																	}
																}
																$('.selectpicker').selectpicker('refresh'); 
																document.getElementById('id_opl').value = id; 
																$('#myModal').modal('show');
															}
															else {
																document.getElementById('client1').value = str[0];
																document.getElementById('sum1').value = str[2];
																document.getElementById('daterrr').value = str[4];
																document.getElementById('list_comm1').value = str[6];
																
																$('.selectpicker').selectpicker('refresh');
																document.getElementById('id_opl1').value = id; 
																$('#myModal1').modal('show');
															}
															
															//select o.CLIENT_ID, o.ORDER_NUM, o.OST_SUM,o.ALL_SUM, o.DATE_, o.view_opl, o.Comments from oplati o where o.id 
														}
													});
													
												}
												
												
											}
											
											// function fun(flags){
											// client=document.getElementById('client').value;
											// num=document.getElementById('num').value;
											// sum=document.getElementById('sum').value;
											// date=document.getElementById('date').value;
											// var tableElem = document.getElementById('radio_chk');
											// var elements = tableElem.getElementsByTagName('input');
											// var list_comm = document.getElementById('list_comm').value;
											
											// for (var i = 0; i < elements.length; i++) {
											// if (elements[i].checked == true ){
											// var view_opl1 = elements[i].value 
											// }
											// }
											// location.href = 'pg/_addOplata.php?client='+client+'&num='+num+'&sum='+sum+'&date='+date+'&view_opl='+view_opl1+'&list_comm='+list_comm+'&flags='+flags;	 	
											// }
											
											function opl_sum(){
												date1 = document.getElementById('date1').value;
												date2 = document.getElementById('date2').value;
												
												$.get( 'Reports/opl_sql.php', {dt1: date1 ,dt2: date2 }, function (data) { alert(data) });
												
												
											}
											$("#date1").val('<? echo date("Y-m-d")?>');
											$("#date2").val('<? echo date("Y-m-d")?>');
										</script>
										<script>
											//function fun1(flags){
											// client=document.getElementById('client1').value;
											// num=0;
											// sum=document.getElementById('sum1').value;
											// date=document.getElementById('daterrr').value;
											
											// var list_comm = document.getElementById('list_comm1').value;
											
											// var view_opl1 = 5;
											
											
											// location.href = 'pg/_addOplata.php?client='+client+'&num='+num+'&sum='+sum+'&date='+date+'&view_opl='+view_opl1+'&list_comm='+list_comm+'&flags='+flags;	 	
											// }
											
											function save_opl(flags){
												if(flags == 1 || flags == '1'){
													client=document.getElementById('client').value;
													num=document.getElementById('num').value;
													sum=document.getElementById('sum').value;
													date=document.getElementById('date').value;
													var tableElem = document.getElementById('radio_chk');
													var elements = tableElem.getElementsByTagName('input');
													var list_comm = document.getElementById('list_comm').value;
													
													for (var i = 0; i < elements.length; i++) {
														if (elements[i].checked == true ){
															var view_opl1 = elements[i].value 
														}
													}
													var id = document.getElementById('id_opl').value;
													
													} else {
													client=document.getElementById('client1').value;
													num=0;
													sum=document.getElementById('sum1').value;
													date=document.getElementById('daterrr').value;
													var list_comm = document.getElementById('list_comm1').value;
													var view_opl1 = 5;
													var id = document.getElementById('id_opl1').value;
												}
												
												
												$.ajax({
													type: "GET",
													url: 'ajax_php_sql.php',
													data: {
														flag : 225, 	
														id : id,
														client : client,
														num : num,
														sum : sum, 
														date : date, 
														view_opl : view_opl1,
														list_comm : list_comm,
														flags : flags
														},  success:function (data) {//возвращаемый результат от сервера
														dTable.DataTable().ajax.reload();
														$('#myModal').modal('hide');
														$('#myModal1').modal('hide');
													}
												});
												
												
												
											}
										</script>
										<!-- Site footer -->
										<footer class="footer">
											<p>&copy; Company 2016</p>
										</footer>
									</body>
									
								</html>
														