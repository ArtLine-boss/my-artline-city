<?php
	
	include 'firewall1.php';
	session_start();
	$login = $_SESSION['login'];
	$query = "select user_per from users where user_login = '".$login ."' LIMIT 1";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) { 
		$admin = $row[0];
	}
	
	if ($admin == "5" OR  $admin == "6" OR $admin == "7" ){
		header("Location: task_p_d.php"); 
	}
	if ($admin == "8" ){
		header("Location: task.php"); 
	}
	
	if($admin == '9'){
		header("Location: stock.php"); 								
		
	}	
	header("Location: orders.php"); 
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
	
	<!--   <li>
	<a href="index.php"><i class="fa fa-calendar fa-fw"></i> Задачи</a>
	</li> 
	<li>
	<a href="orders.php"><i class="fa fa-shopping-cart fa-fw"></i> Заказы</a>
	</li>
	<li>
	<a href="clients.php"><i class="fa fa-align-justify fa-fw"></i> Клиенты</a>
	</li>
	<!--     <li>
	<a href="stock.php"><i class="fa fa-file-text-o fa-fw"></i> Склад</a>
	</li>
	<!--		    <li>
	<a href="offset.php"><i class="fa fa-file-text-o fa-fw"></i> Офсетная печать</a>
	</li>
	<li>
	<a href="oplati.php"><i class="fa fa-money fa-fw"></i> Оплаты</a>
	</li>-->
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
	<h2 class="page-header">Задачи</h2>
	</div>
	<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	<div class="row">
	<div class="col-lg-12">
	<div class="panel panel-default">
	<div class="panel-body">
	<table width="100%" class="table table-hover" id="dataTables-example"  onselectstart="return false" onmousedown="return false">
	
	<thead>
	<tr>
	<th>Начало</th>
	<th>Окончание</th>
	<th>Продукт</th>
	<th>Оборудование</th>
	<th>Операция</th>
	<th>Материал</th>
	<th>Кол-во </th>
	<th></th>
	</tr>
	</thead>
	<tbody>
	<?php
	
	// Инициируем сессию
	/*<span class="label label-danger">+200</span>*/
	
	
	include "db.php";
	$query = "SELECT (select o1.OPERATION_NAME from operations o1 where o1.ID = o.operation) o_name,
	(select o1.PAR from operations o1 where o1.ID = o.operation) o_par, 
	o.kol, 
	o.comment , 
	o.file,
	o.Numder_order,
	(select f.p_names from order_product f where f.id = o.acct_or) p_names , 
	(select e.EQ_NAME from equipment e where e.ID = o.oborud) o_eq	, 
	(select m.M_NAME from material_attr m where m.id = o.mater) mat , t.per_id, t.id,	DATE_FORMAT(t.date_time_beg, '%d.%m.%Y %H:%i:00') date_time_beg, DATE_FORMAT(t.date_time_end, '%d.%m.%Y %H:%i:00') date_time_end, o.part FROM 	(SELECT id,	num_order_per per_id, eq_id,date_time_beg, date_time_end FROM time_eq WHERE user_id = 
	(SELECT id FROM users WHERE user_login = '".$login ."' LIMIT 1) AND date_time_beg > CURDATE()) t , order_per o where 
	o.ID = t.per_id ORDER BY t.date_time_beg;
	
	";
	
	$result = mysql_query($query) or die($query);
	
	while ($row = mysql_fetch_row($result)) { 
	
	echo "<tr class='odd gradeX' onclick='acct($row[10])'>";
	echo "<td>$row[11]</td>";
	echo "<td>$row[12]</td>";
	echo "<td>$row[6]</td>";
	echo "<td>$row[7]</td>";
	echo "<td>$row[0]: ";
	IF (strcasecmp($row[13],'Препресс')  == 0 OR strcasecmp($row[13],'Дизайн')  == 0){
	
	$srtrrr = '';
	IF (strcasecmp( $row[13],'Препресс')  == 0){
	$qr = "select o1.NAME from (select oper_all from order_per  where id = (select num_order_per from time_eq where id = ".$row[9]." limit 1)) o, PR_OPER o1 where LOCATE(o1.id,o.oper_all)";
	}
	IF (strcasecmp( $row[13],'Дизайн')  == 0){
	$qr = "select o1.NAME from (select oper_all from order_per  where id = (select num_order_per from time_eq where id = ".$row[9]." limit 1)) o, DIZ_OPER o1 where LOCATE(o1.id,o.oper_all)";
	}
	$result1 = mysql_query($qr) or die($qr);
	while ($row1 = mysql_fetch_row($result1)) {
	$srtrrr = $srtrrr.$row1[0].",";
	}
	
	echo substr($srtrrr, 0, -1) ;
	}
	
	IF ( strcasecmp($row[1],'Да')  == 0  OR strcasecmp($row[1],'номер клише')  == 0 OR strcasecmp( $row[1],'номер клише')  == 0)	{ 
	echo $row[3];
	} 
	ELSE {
	echo $row[1].' ('.$row[3].')';
	}
	
	
	echo "</td>";
	echo "<td>$row[8]</td>";
	echo "<td>$row[2]</td>";
	echo "<td >";
	$pathdir="pg/files/prod/".$row[4]."/";
	//название архива
	$nameArhive = "backup_".date('j_m_Y_h_i_s').".zip";
	$fileName = 'pg/files/zipp/' .$nameArhive;
	// класс для работы с архивами
	$zip = new ZipArchive;
	// создаем архив, если все прошло удачно продолжаем
	if ( $zip->open($fileName, ZipArchive:: CREATE) === true ){
	// открываем папку с файлами
	$dir = opendir($pathdir) ;
	// перебираем все файлы из нашей папки
	while($file = readdir($dir)){
	// проверяем файл ли мы взяли из папки
	if (is_file($pathdir. $file)){
	// архивируем
	$zip->addFile($pathdir. $file, $file);
	// выводим название
	// заархивированного файла
	}
	}
	$zip->close(); // закрываем архив.
	//	}
	echo "<a href='".$fileName."' download><span class = 'pull-right'><i class='glyphicon glyphicon-floppy-save'></i></<span></a>";
	
	echo "</td>";
	
	}
	}
	print "</tbody>"; 
	
	
	?>
	</table>
	</div>
	<!-- /.panel-body -->
	</div>
	<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
	</div>
	<!-- /.row -->
	
	<div id="myModal_acct" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
	<div class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
	<h4 class="modal-title">ПРОСМОТР</h4>
	</div>
	<div class="modal-body">
	
	<div id = "hh">	
	
	</div>
	
	
	
	
	</div>
	<div class="modal-footer">
	<button type="button" data-dismiss="modal" class="btn btn-danger btn-xs">Отмена</button>
	</div>
	
	</div>
	</div>
	</div>	
	<script>
	function acct(id){
	
	$.get( 'pg/view_oper.php', {id: id}, function (data) {
	var theElement = document.getElementById("hh");
	theElement.innerHTML = data;
	
	document.getElementById("userrr_id").value = '';
	});
	
	$("#myModal_acct").modal('show');
	}
	
	</script>
	<!-- /#wrapper -->
	<script src="../../vendor/bootstrap/js/bootstrap.js" type = "text/javascript"></script>
	<!-- jQuery -->
	<script src="../vendor/jquery/jquery.min.js"></script>
	
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
	"searching": false,
	responsive: true,
	"iDisplayLength": 1000,
	"bPaginate": false,
	"bInfo": false
	});
	});
	</script>
	
	<footer class="footer">
	<p>&copy; Company 2016</p>
	</footer>
	</body>
	
	</html>
		