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
			<!-- Navigation -->
			<?php
				include_once("menu.php");
			?>
					
					<div id="page-wrapper">
					<div class="row">
					<h2 class="page-header">Дизайн</h2>
					<div class="col-lg-12">
					<div class="panel panel-default">
					<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal11'>Добавить</button>
               <!-- /.panel-heading -->
					<div class="panel-body">
					
               <table width="100%" class="table  table-hover" id="dataTables-example">
					<thead>
					<tr>
					<th>Дата</th>
					<th>Параметр</th>
					</tr>
					</thead>
					<?php
					include "db.php";
					$query="select id, DATE_FORMAT(date_, '%d.%m.%Y') date_ , STATUS from calendar";
					$result = mysql_query($query) or die("Query failed");
					print "<tbody>";
					while ($row = mysql_fetch_row($result)) { 
					
					print "<tr class='odd gradeX'>";
					print "<td>$row[1]</td>";
					IF ($row[2] == '1'){
					print "<td>Выходной</td>";
					}
					else {
					print "<td>Рабочий</td>";
					}
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
					<h4 class="modal-title">Добавление даты</h4>
					</div>
					<div class="modal-body">
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"><label>Дата</div><div class="col-md-2"><input type="date" id="dates" name="dates" value = ""></div></div>
					</div>
					</div>
					
					<div class="row">
					<div class="col-md-12">
					<div class="row"><div class="col-md-3"></div><div class="col-md-2">
					<select id = 'date_status'>
					<option value = '0'>Рабочий</option>
					<option value = '1'>Выходной</option>
					</select>
					</div></div>
					</div>
					</div>
					
					</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
					<a onclick="fun()"><button type="button" class="btn btn-primary">Добавить</button></a></div>
					</div>
					</div>
					</div>
					
					
					
					
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
					
					
					</script>
					
					<script>
					function fun(){
					var dates = document.getElementById('dates').value;
					var date_status = document.getElementById('date_status').value;
					
					location.href = 'pg/_add_calendar.php?dates='+dates+'&date_status='+date_status;	 
					}
					</script>
					
					<footer class="footer">
					<p>&copy; Company 2016</p>
					</footer>
					</body>
					
					</html>
										