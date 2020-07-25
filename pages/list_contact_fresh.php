<?php
	
	include 'firewall1.php';
	session_start();
	$login = $_SESSION['login'];
	$query = "select user_per from users where user_login = '".$login ."' LIMIT 1";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) { 
		$admin = $row[0];
	}
	
	
	if ($admin == "7" OR  $admin == "6" OR $admin == "7" ){
		header("Location: task_p_d.php"); 
	}
	if ($admin == "8" ){
		header("Location: task.php"); 
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
		<!-- jQuery -->
		<script src="../vendor/jquery/jquery.min.js"></script>
		<script src="../js/funJs.js"></script>
		<!-- Bootstrap Core JavaScript -->
		<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
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
		
	</head>
	
	<body>
		<div id="wrapper">
			<?php
				include_once("menu.php");
				
			?>
			
			<div id="page-wrapper">
				<div class="row">
					<div class="col-md-12">
						<h2 class="page-header">Неперенесенные контакты</h2>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<!-- /.panel-heading -->
							<div class="panel-body">
								
								<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables">	
									<thead>
										<tr>
											<th>Контакт</th>
											<th>Ответственный</th>
											<th>Компания</th>
											<th>Должность</th>
											<th>Телефон</th>
											<th>Email</th>		
											<th></th>											
										</tr>
									</thead>
									
									<tbody>
									<?
										$select = "SELECT ID,contact,responsible,name_partner,contact_position,contact_phone,contact_email FROM contact_fresh WHERE FLAG=0";
										$query = mysql_query($select) or die($select);
										while($row = mysql_fetch_array($query)) {
											$str = "<tr>";
											$str .= "<td>".$row['contact']."</td>";
											$str .= "<td>".$row['responsible']."</td>";
											$str .= "<td>".$row['name_partner']."</td>";
											$str .= "<td>".$row['contact_position']."</td>";
											$str .= "<td>".$row['contact_phone']."</td>";
											$str .= "<td>".$row['contact_email']."</td>";
											$str .= "<td data-toggle='tooltip' data-original-title='Пометить как перенесенный'><i class='fa fa-check' onclick='checkContact(this,".$row['ID'].")'></i></td>";
											$str .= "</tr>";
											echo $str;
										}
									?>
									</tbody>
									
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
			</div>
		</div>
		
		<script>
			//при загрузке документа
			$(document).ready(function() {
				$('#dataTables').DataTable({
					responsive: true,
					"iDisplayLength": 50
				});
			});
			
			function checkContact(elem, id) {
				var target = elem.parentNode;
				while(target.nodeName !== 'TR') {
					if(target.nodeName === 'TABLE')
						return;
					target = target.parentNode;
				}
				if(!target)
					return;
				if(!id)
					return;
				$.ajax({
					type: "GET",
					url: "/pages/pg/modeler.php",
					data: {'CheckTransferContact': JSON.stringify(id)},
					cache: false,
					async: false,
					success: function(respond) {
						if(respond) {
							var answer = JSON.parse(respond);
							if(answer) {
								if(!answer.error) {
									remove(target);
								}
								else
									alert(answer.message);
							}
							else
								alert("Пустой JSON-ответ");
						}
						else
							alert("Пустой ответ");
					},
					error: function( jqXHR, textStatus, errorThrown ){
						alert('ОШИБКИ AJAX запроса: ' + errorThrown);
					}
				});
			}
			
		</script>
	</body>
</html>