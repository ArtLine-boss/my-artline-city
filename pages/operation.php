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
		<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		
		<!-- Custom CSS -->
		<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
		
		<!-- Morris Charts CSS -->
		<link href="../vendor/morrisjs/morris.css" rel="stylesheet">
		
		<!-- Custom Fonts -->
		<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		
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
					<h2 class="page-header">Операции</h2>
					</div>
					<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->
					<div class="row">
					<div class="col-lg-12">
					<div class="panel panel-default">
					<a onClick="window.open('pg/_addOperationss.php', 'Добавление нового продукта', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');">
					<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span>Добавить</button></a>
					<!-- /.panel-heading -->
					<div class="panel-body">
					
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
					
					<thead>
					<tr>
					<th>Наименование</th>
					<th>Параметр</th>
					<th>Ед. изм.</th>
					<th>Кол-во операций(мин)</th>
					<th>Время приладки(мин)</th>
					<th>Стоимость приладки</th>
					<th>Стоимость операции</th>
					
					<th></th>
					</tr>
					</thead>
					
					<?php
					include_once 'db.php';
					$query="select * from operations";
					$result = mysql_query($query) or die("Query failed");
					print "<tbody>";
					while ($row = mysql_fetch_row($result)) { 
					print "<tr class='odd gradeX' >";
					if ($row[8]!= ""){
					print "<td onClick='_reviewOper($row[0])'>$row[1] ($row[8])</td>";
					}else {
					print "<td onClick='_reviewOper($row[0])'>$row[1]</td>";
					}
					
					print "<td onClick='_reviewOper($row[0])'>$row[2]</td>";
					print "<td onClick='_reviewOper($row[0])'>$row[3]</td>";
					print "<td onClick='_reviewOper($row[0])'>$row[4]</td>";
					print "<td onClick='_reviewOper($row[0])'>$row[5]</td>";
					print "<td onClick='_reviewOper($row[0])'>$row[6]</td>";
					print "<td onClick='_reviewOper($row[0])'>$row[7]</td>";
					print "<td><span class = 'pull-left'><a  class='glyphicon glyphicon-copy  ' onClick='_copyOper($row[0])'></a>
					&nbsp;<a  class='glyphicon glyphicon-trash' href = 'pg/_deloper.php?id=$row[0]'></a></span></td></tr>";
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
					<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->
					<!-- /.row -->
					
					
					
					<!-- /#wrapper -->
					
					<!-- jQuery -->
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
					responsive: true,
					"iDisplayLength": 100
					});
					});
					
					</script>
					
					<!-- Site footer -->
					<footer class="footer">
					<p>&copy; Company 2016</p>
					</footer>
					</body>
					
					</html>
										