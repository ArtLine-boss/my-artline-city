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
			<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="index.html">Система управления заказами</a>
					</div>
					<!-- /.navbar-header -->
					
					<ul class="nav navbar-top-links navbar-right">
					</li>
					<!-- /.dropdown -->
					<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
					</a>
					<ul class="dropdown-menu dropdown-user">
					<li><a href="#"><i class="fa fa-user fa-fw"></i> Профиль</a>
					</li>
					<li><a href="#"><i class="fa fa-gear fa-fw"></i> Настройки</a>
					</li>
					<li class="divider"></li>
					<li><a href="exit.php"><i class="fa fa-sign-out fa-fw"></i> Выход</a>	
					</li>
					</ul>
					<!-- /.dropdown-user -->
					</li>
					<!-- /.dropdown -->
					</ul>
					<!-- /.navbar-top-links -->
					
					<div class="navbar-default sidebar" role="navigation">
					<div class="sidebar-nav navbar-collapse">
					<ul class="nav" id="side-menu">
					
					<li>
					<a href="index.php"><i class="fa fa-calendar fa-fw"></i> Задачи</a>
					</li>
					<li>
					<a href="orders.php"><i class="fa fa-shopping-cart fa-fw"></i> Заказы</a>
					</li>
					<li>
					<a href="clients.php"><i class="fa fa-align-justify fa-fw"></i> Клиенты</a>
					</li>
					<!-- <li>
					<a href="stock.php"><i class="fa fa-file-text-o fa-fw"></i> Склад</a>
					</li>
					<li>
					<a href="offset.php"><i class="fa fa-file-text-o fa-fw"></i> Офсетная печать</a>
					</li> -->
					<li>
					<a href="oplati.php"><i class="fa fa-money fa-fw"></i> Оплаты</a>
					</li>
					<?
					if ($admin == '4'){
					
					echo ' <li>
					<a href="#" ><i class="fa fa-gear fa-fw"></i> Настройки <span class="fa arrow"></span></a>
					<ul class="nav nav-second-level">
					<li>
					<a href="product.php">Продукты</a>
					</li>
					<li>
					<a href="equipment.php">Оборудование</a>
					</li>
					<li>
					<a href="operation.php">Операции</a>
					</li>
					<li>
					<a href="calendar.php">Календарь</a>
					</li>
					<li>
					<a href="#">Справочники<span class="fa arrow"></span></a>
					<ul class="nav nav-third-level">
					<li>
					<a href="users.php">Пользователи</a>
					</li>
					<li>
					<a href="material.php">Материалы</a>
					</li>
					<li>
					<a href="stamps.php">Штампы</a>
					</li>
					<li>
					<a href="params.php">Параметры</a>
					</li>
					<li>
					<a href="design.php">Дизайн и Препресс</a>
					</li>
					</ul>
					<!-- /.nav-third-level -->
					</li>
					</ul>
					<!-- /.nav-second-level -->
					</li>';
					}
					?>
					<li>
					<a href="#"><i class="fa fa-wechat fa-fw"></i> Чат </a>
					</li>
					
					</ul>
					</div>
					<!-- /.sidebar-collapse -->
					</div>
					<!-- /.navbar-static-side -->
					</nav>
					
					<div id="page-wrapper">
					<div class="row">
					<div class="col-lg-12">
					<h2 class="page-header">Офсетная печать</h2>
					
					</div>
					<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->
					<div class="row">
					<div class="col-lg-12">
					<div class="panel panel-default">
					<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal12'>Добавить</button>
					<!-- /.panel-heading -->
					<div class="panel-body">
					<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
					<thead>
					<tr>
					<th>Название</th>
					<th>Формат</th>
					<th>Красочность</th>
					<th>Тираж</th>
					<th>Стоимость</th>
					<th>Дата изготовления</th>
					</tr>
					</thead>
					<?php
					include "db.php";
					$query="select * from offcet";
					$result = mysql_query($query) or die($query);
					print "<tbody>";
					while ($row = mysql_fetch_row($result)) { 
					
					print "<tr class='odd gradeX'>";
					print "<td>$row[1]</td>";
					print "<td>$row[2]</td>";
					print "<td>$row[3]</td>";
					print "<td>$row[4]</td>";
					print "<td>$row[5]</td>";
					print "<td>$row[7]</td>";
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
					<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->
					<!-- /.row -->
					
					
					
					
					
					
					<div id="myModal12" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
					<div class="modal-dialog">
					<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
					<h4>Заказ офсета</h4>
					</div>
					<div class="modal-body">
					<div class="col-lg-12">
					<div class='row'>
					<!--<div class='col-md-1'>
					<div class='block1'> 
					<label>Препресс:</label>
					</div></div>
					<div class='col-md-2'>
					<div class='block1'>
					<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal4'>Выбор препресс</button>	
					</div>
					</div>
					<div class='col-md-3'>
					<div class='block2'>
					<input name='file1[]' type='file' multiple='true'/>		
					</div>
					</div>
					<div class='col-md-2'>
					<div class='block2'>
					<label> Стоимость:&nbsp; </label><input  type = 'text' size='5' value = '0' id = 'press_sum' name = 'press_sum'>"; 
					<?
					INCLUDE 'db.php';
					$query="select * from PR_OPER";
					$result = mysql_query($query) or die($query);
					$iddd = '';
					while ($row = mysql_fetch_row($result)) { 
					if ($row[3] == 1){
					$iddd = $iddd.$row[0].",";
					}}
					$iddd  = substr($iddd, 0, -1);
					
					echo "<input type = 'hidden'  id = 'view_press' name = 'view_press' size='10' value = '".$iddd."'>";
					?>
					</div>
					</div>-->
					
					
					
					<div class='row'>
					<div class='col-md-3'><div class='block1'><label>Наименования</label> </div></div>
					<div class='col-md-6'><div class='block2'> <input type="text" id = "off_name">	</div></div>
					</div>
					<div class='row'>
					<div class='col-md-3'><div class='block1'><label>Формат</label> </div></div>
					<div class='col-md-6'><div class='block2'> <input type="text" id = "off_format">	</div></div>
					</div>
					<div class='row'>
					<div class='col-md-3'><div class='block1'><label>Красочность</label> </div></div>
					<div class='col-md-6'><div class='block2'> <input type="text" id = "off_color">	</div></div>
					</div>
					<div class='row'>
					<div class='col-md-3'><div class='block1'><label>Тираж</label> </div></div>
					<div class='col-md-6'><div class='block2'> <input type="text" id = "off_total">	</div></div>
					</div>
					
					<div class='row'>
					<div class='col-md-3'><div class='block1'><label>Стоимость за тираж</label> </div></div>
					<div class='col-md-6'><div class='block2'> <input type="text" id = "off_price_total">	</div></div>
					</div>
					
					<div class='row'>
					<div class='col-md-3'><div class='block1'><label>% надбавки к цене</label> </div></div>
					<div class='col-md-6'><div class='block2'> <input type="text" id = "off_nadbavka">	</div></div>
					</div>
					
					<div class='row'>
					<div class='col-md-3'><div class='block1'><label>Срок изготовления</label> </div></div>
					<div class='col-md-6'><div class='block2'> <input type='date' id='off_date_rdy' name='off_date_rdy' > <!--<input type="text" id = "off_date_rdy">-->	</div></div>
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
					var off_name 			= document.getElementById("off_name").value;
					var off_format 		= document.getElementById("off_format").value;
					var off_color 			= document.getElementById("off_color").value;
					var off_total 			= document.getElementById("off_total").value;
					var off_price_total 	= document.getElementById("off_price_total").value;
					var off_nadbavka 		= document.getElementById("off_nadbavka").value;
					var off_date_rdy 		= document.getElementById("off_date_rdy").value;
					location.href = 'pg/_add_off.php?off_name='+off_name+'&off_format='+off_format+'&off_color='+off_color+'&off_total='+off_total+'&off_price_total='+off_price_total+'&off_nadbavka='+off_nadbavka+'&off_date_rdy='+off_date_rdy;	  
					}
					</script>
					
					
					
					<footer class="footer">
					<p>&copy; Company 2016</p>
					</footer>
					</body>
					
					</html>
										