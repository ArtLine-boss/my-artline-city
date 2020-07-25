<?php
include 'firewall1.php';
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
		<!-- Page-Level Demo Scripts - Tables - Use for reference -->
		<!-- js класс -->
		<script src="js/artliner_report.js?version=2"></script>
		<style>
			.row {
				margin: 0;
			}
		</style>
	</head>

	<body>
		<div id="wrapper">
			<?php
				include_once("menu.php");
			?>
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h2 class="page-header">Отчет по артлайнерам</h2>
					</div>
				</div>
				<div class="row">
					<label>Отображать:</label>
					<select id="listFields" class="js-example-basic-multiple" multiple="multiple" onchange="changeSelectedFields(this)">
					
						<?php 
							$fields = array(
								array('name'=>"Номер"),
								array('name'=>"Номер счета"),
								array('name'=>"Дата"),
								array('name'=>"Заказчик"),
								array('name'=>"E-Mail"),
								array('name'=>"Телефон"),
								array('name'=>"Почтовый индекс"),
								array('name'=>"Адрес"),
								array('name'=>"Продукция"),
								array('name'=>"Размер"),
								array('name'=>"Количество"),
								array('name'=>"Количество страниц"),
								array('name'=>"Ламинация"),
								array('name'=>"Стоимость"),
								array('name'=>"Тип оплаты"),
								array('name'=>"Оплата"),
								array('name'=>"Дата оплаты"),
								array('name'=>"Способ доставки"),
								array('name'=>"Код доставки"),
								array('name'=>"Дата доставки"),
								array('name'=>"Заказчик по сайту"),
								array('name'=>"E-Mail по сайту"),
								array('name'=>"Телефон по сайту"),
								array('name'=>"Почтовый индекс по сайту"),
								array('name'=>"Адрес по сайту"),
							);
							
							//по умолчанию колонки
							$default = [0,2,3,8,9,10,11,13];
							//определяем по юзеру колонки
							if(isset($_SESSION['login'])) {
								$select = "SELECT array_fields FROM artliner_report_userfields WHERE user_login='".$_SESSION['login']."'";
								$query = mysql_query($select) or die($select);
								if($row = mysql_fetch_array($query)) {
									$default = json_decode($row['array_fields']);
								}
							}
							
							//выбираем колонки
							for($i = 0; $i < count($default); $i++) {
								$index = intval($default[$i]);
								if(!empty($fields[$index])) {
									$fields[$index]['selected'] = "selected";
								}
							}
							
							for($i = 0; $i < count($fields); $i++) {
								echo "<option value='$i' ".$fields[$i]['selected'].">".$fields[$i]['name']."</option>";
							}
						?>
						
					</select>
				</div>
				<div class="row" style="padding:10px">
					<div class="col-md-6">
						<label>Количество на странице: </label>
						<select id="quentityPages" onchange="loadData()">
							<option value="10">10</option>
							<option value="20" selected>20</option>
							<option value="50">50</option>
							<option value="100">100</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4">
						<label>Период: с </label>
						<input type="date" id="startDate">
						<label> по </label>
						<input type="date" id="endDate">
					</div>
					<div class="col-md-2">
						<input type="checkbox" id="isERIP"><label>ЕРИП</label>
					</div>
					<div class="col-md-3">
						<label>Поиск по отправке: </label>
						<input type="text" id="searchData">
					</div>
					<div class="col-md-3">
						<button class="btn btn-success" onclick="loadData(true)">Фильтр</button>
						<button class="btn btn-secondary" onclick="clearFilter()">Очистить</button>
					</div>
				</div>
				<div class="row">
					<div class="panel panel-default">
						<div class="panel-body">
							<table id="tableArtliner" class="table table-striped table-bordered table-hover" width="100%">
								<thead>
									<tr>
										<th onclick="sortHeard(this)" data-name-field="id_order_artliner">Номер<i id="sortMarker" class="fa fa-sort-desc fa-2x"></i></th>
										<th onclick="sortHeard(this)" data-name-field="id_order" style="display:none">Номер счета</th>
										<th onclick="sortHeard(this)" data-name-field="date_artliner">Дата</th>
										<th onclick="sortHeard(this)" data-name-field="clientname">Заказчик</th>
										<th onclick="sortHeard(this)" data-name-field="clientemail" style="display:none">E-Mail</th>
										<th onclick="sortHeard(this)" data-name-field="clientphone" style="display:none">Телефон</th>
										<th onclick="sortHeard(this)" data-name-field="clientpostindex" style="display:none">Почтовый индекс</th>
										<th onclick="sortHeard(this)" data-name-field="clientaddress" style="display:none">Адрес</th>
										<th onclick="sortHeard(this)" data-name-field="product_name">Продукция</th>
										<th onclick="sortHeard(this)" data-name-field="product_size">Размер</th>
										<th onclick="sortHeard(this)" data-name-field="product_count">Количество</th>
										<th onclick="sortHeard(this)" data-name-field="product_pages">Количество страниц</th>
										<th onclick="sortHeard(this)" data-name-field="product_laminat" style="display:none">Ламинация</th>
										<th onclick="sortHeard(this)" data-name-field="product_summa">Стоимость</th>
										<th onclick="sortHeard(this)" data-name-field="payment_type" style="display:none">Тип оплаты</th>
										<th onclick="sortHeard(this)" data-name-field="payment" style="display:none">Оплата</th>
										<th onclick="sortHeard(this)" data-name-field="payment_date" style="display:none">Дата оплаты</th>
										<th onclick="sortHeard(this)" data-name-field="carriers_type" style="display:none">Способ отправки</th>
										<th onclick="sortHeard(this)" data-name-field="carriers_code" style="display:none">Код отправки</th>
										<th onclick="sortHeard(this)" data-name-field="carriers_date" style="display:none">Дата отправки</th>
										<th onclick="sortHeard(this)" data-name-field="post_username" style="display:none">Заказчик по сайту</th>
										<th onclick="sortHeard(this)" data-name-field="post_email" style="display:none">E-Mail по сайту</th>
										<th onclick="sortHeard(this)" data-name-field="post_phone" style="display:none">Телефон по сайту</th>
										<th onclick="sortHeard(this)" data-name-field="post_index" style="display:none">Почтовый индекс по сайту</th>
										<th onclick="sortHeard(this)" data-name-field="post_address" style="display:none">Адрес по сайту</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
							<nav>
								<ul class="pagination" id="navPage"></ul>
							</nav>
						</div>
					</div>
				</div>
			</div>
		</div>	
		<!-- Site footer -->
		<footer class="footer">
			<p>&copy; ArtLineCity 2019</p>
		</footer>
	</body>
</html>