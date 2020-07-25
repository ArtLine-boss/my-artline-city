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
					<div class="col-lg-12">
						<h2 class="page-header">Единицы измерения</h2>
						<div class="col-lg-12">
							<div class="panel panel-default">
								<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal11'>Добавить</button>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
									
									<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
										
										<thead>
											<tr>
												
												<th>Наименования</th>
												
											</tr>
										</thead>
										<?php
										 	$json_diz = Array();
											include "db.php";
											$query="select  * from units";
											$result = mysql_query($query) or die("Query failed");
											print "<tbody>";
											while ($row = mysql_fetch_row($result)) { 
												$json_diz[] = array(
											'id' => $row[0],
											'name' => $row[1]
											);
											print "<tr class='odd gradeX'>";
											print "<td onclick='updd($row[0])' >$row[1]</td>";
											
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
											
											<div class="row">
											<div class="col-lg-12">
											<h2 class="page-header">Размеры</h2>
											<div class="col-lg-12">
											<div class="panel panel-default">
											<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal13'>Добавить</button>
											<!-- /.panel-heading -->
											<div class="panel-body">
											
											<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
											
											<thead>
											<tr>
											
											<th>Размер</th>
											
											</tr>
											</thead>
											<?php
										 	$json_di1 = Array();
											include "db.php";
											$query="select  id, SIZE from size_print";
											$result = mysql_query($query) or die("Query failed");
											print "<tbody>";
											while ($row = mysql_fetch_row($result)) { 
											$json_di1[] = array(
											'id' => $row[0],
											'SIZE' => $row[1]
											);
											print "<tr class='odd gradeX'>";
											print "<td onclick='updd1($row[0])' >$row[1]</td>";
											
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
											<h4 class="modal-title">Добавление единицы измерения</h4>
											</div>
											<div class="modal-body">
											<div class="row">
											<div class="col-md-12">
											<div class="row"><div class="col-md-3"><label>Наименование</div><div class="col-md-2"><input type="text" id="name" name="name" value = ""></div></div>
											</div>
											</div>
											</div>
											<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
											<a onclick="fun()"><button type="button" class="btn btn-primary">Добавить</button></a></div>
											</div>
											</div>
											</div>
											
											<div id="myModal13" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
											<div class="modal-dialog">
											<div class="modal-content">
											<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
											<h4 class="modal-title">Добавление размера</h4>
											</div>
											<div class="modal-body">
											<div class="row">
											<div class="col-md-12">
											<div class="row"><div class="col-md-3"><label>Размер</div><div class="col-md-2"><input type="text" id="name_size" name="name_size" value = ""></div></div>
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
											<div class="row"><div class="col-md-3"><label>Наименование</div><div class="col-md-2"><input type="text" id="name1" name="name1" value = ""><input type="hidden" id="id" name="id" ></div></div>
											</div>
											</div>
											
											
											
											</div>
											<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
											<a onclick="funUpdDiz()"><button type="button" class="btn btn-primary">Изменить</button></a></div>
											</div>
											</div>
											</div>'; ?>
											
											<?php
											
											print'	<div id="myModal14" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
											<div class="modal-dialog">
											<div class="modal-content">
											<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
											<h4 class="modal-title">Измениние размера</h4>
											</div>
											<div class="modal-body">
											<div class="row">
											<div class="col-md-12">
											<div class="row"><div class="col-md-3"><label>Размеры</div><div class="col-md-2"><input type="text" id="name_size1" name="name_size1" value = ""><input type="hidden" id="id1" name="id1" ></div></div>
											</div>
											</div>
											
											
											
											</div>
											<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
											<a onclick="funUpdSize()"><button type="button" class="btn btn-primary">Изменить</button></a></div>
											</div>
											</div>
											</div>'; ?>
											
											
											
											
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
											var name = document.getElementById('name').value;
											location.href = 'pg/_add_units.php?name='+name;	 
											}
											
											function fun1(){
											var name = document.getElementById('name_size').value;
											location.href = 'pg/_add_size.php?name='+name;	 
											}
											
											
											
											
											</script>
											
											
											<script>
											function updd(id){
											//
											array = JSON.parse('<?echo json_encode($json_diz);?>');
											
											for (var i = 0; i < array.length; i++){
											if (array[i].id == id){
											document.getElementById('name1').value = array[i].name
											document.getElementById('id').value = array[i].id
											$('#myModal12').modal('show');
											break;
											}
											}
											}
											
											
											
											</script>
											<script>
											function updd1(id){
											//
											array = JSON.parse('<?echo json_encode($json_di1);?>');
											
											for (var i = 0; i < array.length; i++){
											if (array[i].id == id){
											document.getElementById('name_size1').value = array[i].SIZE
											document.getElementById('id1').value = array[i].id
											$('#myModal14').modal('show');
											break;
											}
											}
											}
											</script>
											
											<script>
											function funUpdDiz(){
											
											var name1 = document.getElementById('name1').value 
											var id  = document.getElementById('id').value;
											
											location.href = 'pg/_updateunits.php?name='+name1+'&id='+id;	 
											
											}
											
											function funUpdSize(){
											
											var name1 = document.getElementById('name_size1').value 
											var id  = document.getElementById('id1').value;
											
											location.href = 'pg/_updateunits.php?name='+name1+'&id='+id;	 
											
											}
											</script>
											
											
											
											<footer class="footer">
											<p>&copy; Company 2016</p>
											</footer>
											</body>
											
											</html>
																						