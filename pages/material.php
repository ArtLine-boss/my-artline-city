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
		<style>
			.modal-lg
			{
			height:90%;
			width:90%;
			margin-left: 5%; 
			
			}
			.block1 { 
			border: solid 1px black; 
			}
			
		</style>
	</head>
	
	<body>
		<div id="wrapper">
			<!-- Navigation -->
			<?php
				include_once("menu.php");
			?>
			
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h2 class="page-header">Материалы</h2>
					</div>
					<!-- /.col-lg-12 -->
				</div>
            <!-- /.row -->
				<div class="row">
					
					
					<div class="col-lg-12">
						<div class="panel panel-default">
							
							<div class="row">
								<div class="col-md-2">
									<a onClick="window.open('pg/_addMaterial.php', 'Добавление нового материала', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');">
									<button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span>Добавить</button></a>
								</div>
								<div class="col-md-2">
									<button type='button' class='btn btn-default'  onClick='_addMat()'>Добавить ver 2.0 </button>
								</div>
							</div>  
							<!-- /.panel-heading -->
							<div class="panel-body">
								
								<table width="100%" class="table table-striped table-bordered table-hover" id="dataTables-example">
									
									<thead>
										<tr>
											<th></th>
											<th>Тип материала</th>
											<th>Название материала</th>
											<th>Ед. измерения</th>
											<th>Размер</th>
											<th>Стоимость</th>
											<th>Кол-во</th>
											<th></th>
										</tr>
									</thead>
									<?php
										
										include "db.php";
										$query="select  m.ID, m2.MT_TYPE, m.M_NAME , m.M_UNIT , m.M_SIZE , m.M_PRICE ,m.M_AVA, m.M_KOL_ALL from material_attr m, material m2 where m.ID_M = m2.ID";
										
										$result = mysql_query($query) or die("Query failed");
										print "<tbody>";
										while ($row = mysql_fetch_row($result)) { 
											
											print "<tr class='odd gradeX'  id='st".$row[0]."'>";
											print "<td><a class='preview' href='pg/image/mat/$row[6]' width='350px' ><img src='pg/image/mat/$row[6]' class='img-rounded center-block' alt='...' width='100' ></a></td>";
											print "<td><span class = 'pull-left' onclick='_reviewMaterial($row[0])'>$row[1]</td>";
											print "<td onclick='_reviewMaterial($row[0])'>$row[2]</td>";
											print "<td onclick='_reviewMaterial($row[0])'>$row[3]</td>";
											print "<td onclick='_reviewMaterial($row[0])'>$row[4]</td>";
											print "<td onclick='_reviewMaterial($row[0])'>$row[5]</td>";
											print "<td onclick='_reviewMaterial($row[0])'>$row[7]</td>";
											print "<td><span class = 'pull-right'><a  class='glyphicon glyphicon-trash' onClick='_deletemat($row[0])'></a></span></td>";
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
										
										<div id="_addMat" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
										<div class="modal-dialog  modal-lg">
										<div class="modal-content ">
										<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
										<h4 class="modal-title">Материал</h4>
										</div>
										<div class="modal-body">
										
										<?
										$line ="<select id = 'type_mat'> <option value='' selected >Выберите тип</option>"; 
										include 'db.php';
										$query="select ID,MT_TYPE from material";
										$result = mysql_query($query) or die($query);
										while ($row = mysql_fetch_row($result)) 
										{ 
										$line =$line."<option value='$row[0]'>$row[1]</option>"; 
										}
										$line = $line."</select><br/>";
										
										?>
										
										<div class="row">
										<div class="col-md-2"><label >Тип материала:</label> </div>
										<div class="col-md-2"><?echo $line;?> </div>
										</div>
										<div class="row">
										<div class="col-md-2"><label >Наименование:</label> </div>
										<div class="col-md-10"><input type='text' id='nameMaterial' size='100'> </div>
										</div>
										<div class="row">
										<div class="col-md-2"><label >Ед. измерения:</label> </div>
										<div class="col-md-2"><select id = 'izmMaterial'><option value=''></option>
										<?
										$query="select  * from units";
										$result = mysql_query($query) or die($query);
										
										while ($row = mysql_fetch_row($result)) { 
										
										echo "<option value='".$row[1]."'> ".$row[1]."</option>";
										
										}
										
										
										?>
										</select> </div>
										</div>
										
										<div class="row">
										<div class="col-md-1 "></div>
										<div class="col-md-1 ">Размер</div>
										<div class="col-md-1 ">Цена</div>
										<?
										$i = 0;
										$query="select id, EQ_NAME from equipment where l_use = 1";
										$result = mysql_query($query) or die($query);
										$array = array();
										
										while ($row = mysql_fetch_row($result)) { 
										$i++;
										echo '	<div class="col-md-1 ">'.$row[1].'</div>';
										$array[$i] = $row[0];
										}
										echo '</div>';
										// print_r($array);
										$query1="select SIZE from size_print ";
										$result = mysql_query($query1) or die($query1);
										$u = 0;
										while ($row = mysql_fetch_row($result)) { 
										$u++;
										echo "<div class='row'>
										<div class='col-md-1 '><input type='checkbox' name='iir' value='".$u."' ></div>
										<div class='col-md-1' id='size".$u."'>".$row[0]."</div>
										<div class='col-md-1 '><input type='text' name='' size = 5 id = 'price_".$u."'> </div>";
										for($y = 1; $y <= $i; $y++){
										
										echo "<div class='col-md-1 '><input type='checkbox' name='eq_".$u."' value = '".$array[$y]."'></div>";
										}
										echo "</div>";
										
										}
										echo "<input type='hidden' id='kol_str' value='".$u."'>";
										
										?>
										
										
										
										
										
										
										</div>
										<div class="modal-footer">
										<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
										<a onclick="sscm()"><button type="button" class="btn btn-primary" >Добавить</button></a>
										</div>
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
										responsive: true,
										"iDisplayLength": 100
										});
										});
										
										function sscm(){
										if ($('#type_mat').val() == ""){
										alert("Выберете тип материала")
										return;
										}
										if ($('#nameMaterial').val() == ""){
										alert("Введите наименование материала")
										return;
										}
										if ($('#izmMaterial').val() == ""){
										alert("Выберете ед. измерения")
										return;
										}
										
										var nodeList = document.getElementsByName('iir');
										var arrays = Array.prototype.slice.call(nodeList);
										
										for (var i = 0; i < arrays.length; i++) {
										if (arrays[i].checked) {
										
										if ($('#price_' + arrays[i].value).val() == ""){
										alert("Не задана цена для размера " + document.getElementById('size' + arrays[i].value).innerHTML)
										return;
										}
										size = document.getElementById('size' + arrays[i].value).innerHTML; // размер
										price = document.getElementById('price_' + arrays[i].value).value;
										price = price.replace(",",".");
										
										var nodeList1 = document.getElementsByName('eq_' + arrays[i].value);
										var array1 = Array.prototype.slice.call(nodeList1);
										srt_pj = "";
										for (var r = 0; r < array1.length; r++) {
										if (array1[r].checked) {
										srt_pj =	srt_pj + array1[r].value + ",";
										}
										
										}
										srt_pj = srt_pj.slice(0, -1);
										
										$.ajax({
										type: "GET",
										url: 'pg/_addMaterials1.php',
										data: {
										size :  size,
										units :  $('#izmMaterial').val(),
										type :  $('#type_mat').val(),
										name :  $('#nameMaterial').val(),
										price :  price,
										eq : srt_pj
										},  success:function (data) {//возвращаемый результат от сервера
										//alert(data)
										location.reload();
										}
										
										});
										
										
										}
										}
										
										
										}
										function _addMat(){
										$('#_addMat').modal('show');
										}
										function _deletemat(id){
										$.ajax({
										type: "GET",
										url: 'ajax_php_sql.php',
										data: {
										id : id,
										flag : '21'
										},  success:function (data) {//возвращаемый результат от сервера
										
										document.getElementById('st' + id).style.display = 'none';
										}
										});
										}
										</script>
										<footer class="footer">
										<p>&copy; Company 2016</p>
										</footer>
										</body>
										
										</html>
																				