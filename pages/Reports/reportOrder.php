<?php
include '../firewall1.php';
session_start();
$login = $_SESSION['login'];
$query = "select user_per from users where user_login = '".$login ."' LIMIT 1";
$result = mysql_query($query) or die($query);
if ($row = mysql_fetch_row($result)) { 
	$admin = $row[0];
}

if(empty($admin))
	die(null);

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
		<link rel="icon" href="../../favicon.png" type="image/png">
		<!-- Bootstrap Core CSS -->
		<link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- MetisMenu CSS -->
		<link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		<!-- Custom CSS -->
		<link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
		<!-- Morris Charts CSS -->
		<link href="../../vendor/morrisjs/morris.css" rel="stylesheet">
		<!-- Custom Fonts -->
		<link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<!-- jQuery -->
		<script src="../../vendor/jquery/jquery.min.js"></script>
		<script src="../../js/funJs.js"></script>
		<!-- Bootstrap Core JavaScript -->
		<script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<!-- Metis Menu Plugin JavaScript -->
		<script src="../../vendor/metisMenu/metisMenu.min.js"></script>
		<!-- Morris Charts JavaScript -->
		<script src="../../vendor/raphael/raphael.min.js"></script>
		<script src="../../vendor/morrisjs/morris.min.js"></script>
		<script src="../../data/morris-data.js"></script>
		<!-- Custom Theme JavaScript -->
		<script src="../../dist/js/sb-admin-2.js"></script>
		<!-- DataTables JavaScript -->
		<script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
		<script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
		<!-- Page-Level Demo Scripts - Tables - Use for reference -->
		<!-- js класс -->
		<script src="js/reportOrder.js"></script>
		<style>
			.row {
				margin: 0;
			}
		</style>
	</head>

	<body>
		<div id="wrapper">
			<?php
				include_once("../menu1.php");
			?>
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h2 class="page-header">Отчет в XLS</h2>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<label>Клиент</label>
						<select id="clientname" class="js-example-basic-single js-states form-control">
							<?php
								$select = "SELECT ID,CLIENT_NAME FROM clients";
								$query = mysql_query($select) or die(null);
								while($row = mysql_fetch_array($query)) {
									echo '<option value="'.$row['ID'].'">'.$row['CLIENT_NAME'].'</option>';
								}
							?>
						</select>
						<script>
							$('#clientname').select2({
								width: '80%',
								placeholder: 'Выберите клиента',
							});
							$('#clientname').val(-1).trigger('change');
						</script>
					</div>
					<div class="col-md-4">
						<label>Период с </label>
						<input type="date" id="startDate">
						<label> по </label>
						<input type="date" id="endDate">
					</div>
					<div class="col-md-2">
						<button class="btn btn-success" onclick="createReport()">Создать</button>
						<button class="btn btn-warning" onclick="createReport(true)">Создать и скачать</button>
					</div>
				</div>
				
				<div id="containerTable" style="margin-top: 50px; padding: 5px;">
				
				</div>
				
			</div>
		</div>
	</body>
</html>