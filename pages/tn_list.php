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
					<div class="col-lg-12">
					<h2 class="page-header">Товарные Накладные</h2>
					</div>
					<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->
					<div class="row">
					<div class="col-lg-12">
					<div class="panel panel-default">
					<?
					if ($login == 'buh1' OR $login == 'admin' OR $login == 'admins'){
					echo "<button type='button' class='btn btn-default' id='confirm' onclick = 'fun_w(1)' >Выгрузка ТН</button>";
					}
					?>
					
					<div class="panel-body">
					<table class="table table-striped table-bordered table-hover " id="dataTables-example"  >
					
					<thead>
					<tr>
					<th></th>
					<th>Дата Накладной</th>
					<th>Номер накладной</th>
					<th>Номер заказа</th>
					<th>UNP</th>
					<th>Пользователь</th>
					<th>Испорчена</th>
					<th>Выгружена</th>
					<th>Тип</th>
					<th>Сумма</th>
					</tr>
					</thead>
					
					</table>
					
					
					</div>
					
					</div>
					<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->
					<!-- /.row -->
					</div>
					</div>	 
					<!-- /#Модальные окна -->
					<div id="myModal" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">ПРОСМОТР</h4>
					</div>
					<div class="modal-body">
					<div id = "hh1">	
					<button id = 'deltn' type="button" class="btn btn-default" >Удалить</button>
					</div>
					<div id = "hh">	
					
					</div>
					</div>
					<div class="modal-footer">
					<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					</div>
					
					</div>
					</div>
					</div> 
					<!------------------------------------------act --------------------------------------------------------------->
					<div id="myacct" class="modal fade bd-example-modal-lg"  tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
					<div class="modal-dialog  modal-lg">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4 class="modal-title">ТН</h4>
					</div>
					<div class="modal-body" >
					<div class="row">
					
					<div class="col-md-12">  <iframe  id = 'fr' src="" width="100%" height="700" align="left">
					Ваш браузер не поддерживает плавающие фреймы!
					</iframe></div>
					</div>
					
					
					
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					</div>
					</div>
					</div>
					</div>
					</div>
					
					<!--------------------------------------------------------------------------------------------------------->
					
					<!-- /#wrapper -->
					
					<!-- jQuery-->
					<script src="../vendor/jquery/jquery.min.js"></script> 
					<script src="../js/funJs.js"></script>
					
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
					
					var default_options = {
					"responsive": true,
					"paging:": true,								
					
					"iDisplayLength": 25,
					
					
					"ajax" : {
					"url" : 'ajax_php_sql.php?flag=37',
					"type" : "GET",
					"dataSrc": ""
					},
					"columns": [
					{ "data": "flags"},
					{ "data": "td2" 			, "sType": "ruDate"},
					{ "data": "td3" },
					{ "data": "td1" },
					{ "data": "td4"},
					{ "data": "td5" },
					{ "data": "td6" },
					{ "data": "td7" },
					{ "data": "td8" },
					{ "data": "td9" }
					]  ,
					"aaSorting": [[1,'desc']]
					};
					
					var dTable= $('#dataTables-example').dataTable( default_options);
					
					
					function view_tn(id, type) {

					    switch (type) {
                            case 'tn':
                                document.getElementById('fr').src = 'pg/proc/tn1.php?id=' + id;
                                break;
                            case 'act':
                                document.getElementById('fr').src = 'pg/proc/act1.php?id=' + id;
                                break;
                            case 'ttn':
                                document.getElementById('fr').src = 'pg/proc/ttn_edit.php?id=' + id;
                                break;
                            case 'tn_m':
                                document.getElementById('fr').src = 'pg/proc/tn1_m.php?id=' + id;
                                break;
                            case 'act_m':
                                document.getElementById('fr').src = 'pg/proc/act1_m.php?id=' + id;
                                break;
                            case 'ttn_m':
                                document.getElementById('fr').src = 'pg/proc/ttn_edit_m.php?id=' + id;
                                break;
                        }

                        $('#myacct').modal('show');
					
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
					
					function fun_w(fp = 1){
                        $.ajax({
                            type: "GET",
                            url: 'exp_tn.php',
                            data:  {id : "1", firm_parent: fp},
                            success:function (data) {//возвращаемый результат от сервера
                                if(fp == 1) {
                                    alert("АРТЛАЙНСИТИ: " + data);
                                    fun_w(2);
                                } else if(fp == 2) {
                                    alert("МЕЧТА КЛИЕНТА: " + data);
                                } else {
                                    alert("Не известная фирма");
                                }
                            }
                        });
                        document.getElementById('confirm').disabled = true;
					}
					</script>
					<!-- Site footer -->
					<footer class="footer">
					<p>&copy; Company 2016</p>
					</footer>
					</body>
					
					</html>
										