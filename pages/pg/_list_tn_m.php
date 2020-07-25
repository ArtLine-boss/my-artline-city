<?php
	include '../firewall.php';
?>    
<html>
	<head>
		<link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- Bootstrap Core CSS -->
		
		
		<!-- MetisMenu CSS -->
		<link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		
		<!-- Custom Fonts -->
		<link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../../vendor/bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css">
		
		<script src="../../vendor/bootstrap/js/bootstrap.min.js" type = "text/javascript"></script>
		<script src="../../vendor/jquery/jquery.js"></script>
	</head>
	<body>
		
		<div class='masthead'>
			<div class='row'><div class='col-md-1'>	
			</div>
			<div class='col-md-5'>	
				
				<?
					$idAcct = $_GET['id'];
					
					//echo '<a href="_addAcct.php?id='.$idAcct.'">
				//  <button type="button" class="btn btn-primary"><i class="fa fa-reply fa-fw"></i> к заказу</button></a>' ?>
				
				<?
					include "../db.php";
			 		$query = "Select (select num_doc_m from clients where id = client_id) num_doc, (select dover from clients where id = client_id) dover, (select id from clients where id = client_id) num_doc, (select UNP from clients where id = client_id) UNP from orders where number = ".$idAcct;
					$result = mysql_query($query) or die($query);
					IF ($row = mysql_fetch_array($result)) { 
						$num_doc_m = $row[0];
						$dover = $row[1];
						$client_id = $row[2];
						$UNP = $row[3];
						
					}
					
				?>
				<br><br>
				
				
				<div class="alert alert-danger" id = "tn_err1" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close1">×</a>
					<strong>Ошибка!</strong> Выберите продукты!
				</div>
				<div class="alert alert-danger" id = "tn_err2" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close2">×</a>
					<strong>Ошибка!</strong> Введите № бланка нашей ТН!
				</div>
				<div class="alert alert-danger" id = "tn_err3" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close3">×</a>
					<strong>Ошибка!</strong> Введите должность получающего!
				</div>
				<div class="alert alert-danger" id = "tn_err4" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close4">×</a>
					<strong>Ошибка!</strong> Введите ФИО получающего!
				</div>
				<div class="alert alert-danger" id = "tn_err5" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close5">×</a>
					<strong>Ошибка!</strong> Введите номер доверенности!
				</div>
				<div class="alert alert-danger" id = "tn_err6" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close6">×</a>
					<strong>Ошибка!</strong> Введите дату доверенности!
				</div>
				<div class="alert alert-danger" id = "tn_err7" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close7">×</a>
					<strong>Ошибка!</strong> Введите серию!
				</div>
				<div class="alert alert-danger" id = "tn_err8" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close8">×</a>
					<strong>Ошибка!</strong> Нет договора с клиентом! <br/>
					<a href = '_reviewClient.php?id_client=<?echo $client_id;?>'  target="_parent">Карточка клиента</a>							
				</div>
				<div class="alert alert-danger" id = "tn_err9" style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close9">×</a>
					<strong>Ошибка!</strong> Не задан УНП! <br/>
					<a href = '_reviewClient.php?id_client=<?echo $client_id;?>'  target="_parent">Карточка клиента</a>							
				</div>
				
				<div class='row'>	<div class='col-md-2'>	</div>
					<div class='col-md-6'>	</div>
				</div>
				<div class="row">
					<div class="col-md-5">Без НДС:   </div>
					<div class="col-md-3"><input type='checkBox' id='no_nds' name='no_nds' checked disabled>	</div>
				</div>
				<div class="row">
					<div class="col-md-5">Дата ТН:   </div>
					<div class="col-md-3"><input type='date' id='date_tn' name='date_tn'>	</div>
				</div>
				<div class="row">
					<div class="col-md-5">№ бланка нашей ТН	</div>
					<div class="col-md-3"><input type="text" id="number" name="number" value = "" placeholder="0123456"></div>
				</div>
				<div class="row">
					<div class="col-md-5">Серия	</div>
					<div class="col-md-3"><input type="text" id="seriass" name="seriass" onKeyUp="this.value = this.value.replace (/[^А-Я]/, '')" size = '2'  value = "" ></div>
				</div>
				<div class="row">
					<div class="col-md-5">Должность получающего</div>
					<div class="col-md-3"><input type="text" size = '40'   id="face_to" name="face_to" value = ""></div>
				</div>
				<div class="row">
					<div class="col-md-5">ФИО получающего</div>
					<div class="col-md-3"><input type="text" size = '40' onKeyUp="this.value = this.value.replace (/[^a-zA-Zа-яА-ЯёЁ .]/, '')"  id="to_face" name="to_face" value = ""></div>
				</div>
				<div class="row">
					<div class="col-md-5">C доверенностью	</div>
					<div class="col-md-3"><input  type = 'checkbox'  id = 'chek'></div>
				</div>
				<div class="row">
					<div class="col-md-5">Номер доверенности	</div>
					<div class="col-md-3"><input type="text" size = '40'  id="num_dover" name="num_dover" value = ""></div>
				</div>
				<div class="row">
					<div class="col-md-5">Дата доверенности	</div>
					<div class="col-md-3"><input type="date" id="date_dover" name="date_dover" data-date-format="DD.MM.YYYY"></div>
				</div> 
				<div class="row">
					<div class="col-md-5">Выписана бухгалтером	</div>
					<div class="col-md-3"><input  type = 'checkbox'  id = 'is_not_exp'></div>
				</div>
				<br>
				
				<table class='table  table-bordered' ><thead>
				<tr><td></td><td>Наименование продукта</td><td>Кол-во</td><td>Ед. Изм.</td><td>Цена</td><td>Цена без. Ндс</td><td>Сумма НДС</td><td>Цена с НДС</td></tr></thead>
				
				<?		
					
					$query = "select val from settings s where s.id  = 4";
					$result = mysql_query($query) or die("Query failed1");
					
					IF ($row = mysql_fetch_row($result)) { 
						$nds = $row[0];
						
					}
					
					IF ($nds ==''){
						$nds = 0;
						} ELSE {
						$nds = (double)str_replace(',', '.', $nds);
					}
					
					$nds_ = 1 + $nds/100;
					
					
					
					$dover1 = (substr($dover ,0 , strrpos($dover , 'от') - 1));
					$dover_1 = (substr($dover ,strrpos($dover , ' ') + 1));
					IF ($dover_1 != ''){
						$date_begf = new DateTime($dover_1) ;
						$date_rrr = $date_begf->format('Y-m-d');
					}
					$dolj_pol = "";
					$fio_pol = "";
					$query = "select t.dolj_pol, t.fio_pol from tn_list_par t, (select o.NUMBER from orders o where o.CLIENT_ID = ".$client_id." AND  o.NUMBER <> ".$idAcct." ) o1 where t.order_id = o1.NUMBER AND t.dolj_pol <> '' AND t.fio_pol <> '' ORDER BY t.order_id DESC  LIMIT 1;";
					$result = mysql_query($query) or die($query);
					IF ($row = mysql_fetch_array($result)) { 
						$dolj_pol = $row[0];
						$fio_pol = $row[1];
					}	
					
					//$query="select id,p_names, total, price, summ,product_id,ORDER_ID,DIZ,sum_press from order_product where order_id =".$idAcct;
					/*$query = "SELECT o.id,o.p_names, o.total, o.price, o.summ,o.product_id,o.ORDER_ID,o.DIZ,o.sum_press ,
					COALESCE(t.tt,0) , o.total - COALESCE(t.tt,0) sumt, o.units FROM order_product o
					LEFT JOIN  (select t.prod_id, COALESCE(sum(t.total),0) tt from  tn_list t GROUP BY t.prod_id) t
					ON  o.ID = t.prod_id where o.ORDER_ID =".$idAcct;*/
					$query = "SELECT *
								FROM
									(SELECT o.id,o.p_names, o.total, o.price, o.summ,o.product_id,o.ORDER_ID,o.DIZ,o.sum_press,COALESCE(t.tt,0) , o.total - COALESCE(t.tt,0) sumt, o.units, o.code_stat
									FROM 
										order_product o
									LEFT JOIN  
										(select t.prod_id, COALESCE(sum(t.total),0) tt from  tn_list t GROUP BY t.prod_id) t
									ON  o.ID = t.prod_id where o.ORDER_ID=".$idAcct.") t1
								LEFT JOIN
									(SELECT ID, code_stat FROM directoryCodeStat) t2
								ON t1.code_stat=t2.ID
								WHERE t2.code_stat<>'18.14.10' AND t2.code_stat<>'18.13.30'";
					
					$result = mysql_query($query) or die($query);
					echo "<tbody>";
					while ($row = mysql_fetch_array($result)) { 
						$osr = $row[10];
						if ($osr < 0 AND $osr < 1  ){
							$osr = 0;
						}
						$osr = abs($osr );
						$osr = round($osr,4);
						echo "<tr class='odd gradeX' id = $row[0] >";
						echo "<td><input type='checkbox' name='ch11Del' checked ></td>";
						echo "<td>$row[1]</td>";
						echo "<td> <input name='total$row[0]' id = 'total$row[0]' type='text' value='$osr' size='7'/></td>";
						echo "<td>$row[11]</td>";
						echo "<td>".ROUND($row[3],2)."</td>";
						echo "<td>".(ROUND(($row[4]/ $nds_),2))."</td>";
						echo "<td>".(ROUND($row[4],2) - ROUND(($row[4]/ $nds_),2))."</td>";
						echo "<td>".ROUND($row[4],2)."</td>";
						
						echo"</td></tr>";
					}
					
					
					
					
				?>
				</tbody></table>
				
				<a onclick="addtn()"><button type="button" class="btn btn-default" >Создать</button></a>	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;	<button type="button" id = 'confirm' class="btn btn-default" disabled>Печать</button>
				<br/><br/><br/><br/>
				<p><b><i>Инструкция по эксплуатации:</b></i>
					<ul>
						<li>Заполните поля</li>
						<li>Нажмите "Создать", в окне справа появиться накладная</li>
						<li>Если после проверки накладной, есть неточности то повторите действия предыдущих пунктов</li>
						<li>Если все верно нажмите печать, не забудьте указать нужное кол-во копий при печати</li>
						<li>После печати, нажмите к заказу</li>
					</ul>
				</p>
			</div>
			<div class='col-md-6'>	
				<iframe  id = 'fr' src="" width="100%" height="100%" align="left">
					Ваш браузер не поддерживает плавающие фреймы!
				</iframe>
				
			</div>
			
		</div></div>		
		
		
 		
		<script type="text/javascript" src="http://yandex.st/jquery/1.6.0/jquery.min.js"></script>
	 	<script src="../../js/jquery.maskedinput-1.2.2.js"></script>
		<script type="text/javascript">
			
			jQuery(function($) {
				
				$.mask.definitions['~']='[+-]';
				
				$('#number').mask('9999999');
				
				
			});</script> 
			<script>
				$(document).ready(function(){
					$("#date_tn").val('<? echo date("Y-m-d")?>');
					
					num_doc = '<? echo $num_doc_m ?>';
					if (num_doc == ''){
						document.getElementById('tn_err8').style.display = 'block';
						
					}
					UNP = '<? echo $UNP ?>';
					if (UNP == '0'){
						document.getElementById('tn_err9').style.display = 'block';
						
					}	
					
					
					
				});
			</script>
			<script>
				function ffa(){
					var getMyFrame = document.getElementById('fr');
					getMyFrame.focus();
					getMyFrame.contentWindow.print();
				}
			</script>
			
			
			<script type="text/javascript">
				$("#close1").click(function(){
					document.getElementById('tn_err1').style.display = 'none';
					
				});
			</script>
			<script type="text/javascript">
				$("#close2").click(function(){
					document.getElementById('tn_err2').style.display = 'none';
					
				});
				$("#close7").click(function(){
					document.getElementById('tn_err7').style.display = 'none';
					
				});
				$("#close8").click(function(){
					document.getElementById('tn_err8').style.display = 'none';
					
					});  $("#close9").click(function(){
					document.getElementById('tn_err9').style.display = 'none';
					
				});
			</script>
			<script type="text/javascript">
				$("#close3").click(function(){
					document.getElementById('tn_err3').style.display = 'none';
					
				});
			</script>
			<script type="text/javascript">
				$("#close4").click(function(){
					document.getElementById('tn_err4').style.display = 'none';
					
				});
			</script>
			<script type="text/javascript">
				$("#close5").click(function(){
					document.getElementById('tn_err5').style.display = 'none';
					
				});
			</script>
			<script type="text/javascript">
				$("#close6").click(function(){
					document.getElementById('tn_err6').style.display = 'none';
				});
			</script>
			
			<script>
				function addtn() {
					
					
					var 	i_err = 0;
					num_doc = '<? echo $num_doc_m ?>';
					if (num_doc == ''){
						document.getElementById('tn_err8').style.display = 'block';
						document.getElementById('tn_err7').style.display = 'block';
						i_err = i_err + 1;
					}
					UNP = '<? echo $UNP ?>';
					if (UNP == '0'){
						document.getElementById('tn_err9').style.display = 'block';
						document.getElementById('tn_err7').style.display = 'block';
						i_err = i_err + 1;
					}	
					var idAcct = "<? echo $idAcct ?>";
					no_nds = 0;
					if (document.getElementById('no_nds').checked) {
						no_nds = 1;
					}
					number = document.getElementById('number').value;
					if (number == ""){
						document.getElementById('tn_err2').style.display = 'block';
						i_err = i_err + 1;
					}
					face_to = document.getElementById('face_to').value;
					if (face_to == ""){
						document.getElementById('tn_err3').style.display = 'block';
						i_err = i_err + 1;
					}
					to_face = document.getElementById('to_face').value;
					if (to_face == ""){
						document.getElementById('tn_err4').style.display = 'block';
						i_err = i_err + 1;
					}
					seriass = document.getElementById('seriass').value;
					if (seriass == ""){
						document.getElementById('tn_err7').style.display = 'block';
						i_err = i_err + 1;
					}
					
					flafff = 0;	
					if (document.getElementById('chek').checked){
						flafff = 1;
						num_dover = document.getElementById('num_dover').value;
						if (num_dover == ""){
							document.getElementById('tn_err5').style.display = 'block';
							i_err = i_err + 1;
						}
						date_dover = document.getElementById('date_dover').value;
						if (date_dover == ""){
							document.getElementById('tn_err6').style.display = 'block';
							i_err = i_err + 1;
						}
					}
					
					var srt = "";
					//total
					var nodeList = document.getElementsByName('ch11Del');
					var array = Array.prototype.slice.call(nodeList);
					for (var i = 0; i < array.length; i++) {
						if (array[i].checked) {
							var tr = array[i].parentNode.parentNode;
							srt =	srt  + tr.id + "^" + document.getElementById('total'+tr.id).value + "|";
							
						}
					}
					
					srt = srt.substring(0, srt.length-1);
					if (srt == ""){
						document.getElementById('tn_err1').style.display = 'block';
						i_err = i_err + 1;
					}
					dates = document.getElementById('date_tn').value;
					if (i_err == 0){
						num_doc = '<? echo $num_doc_m ?>';
						if (num_doc != ''){
							document.getElementById('confirm').disabled = false;
							document.getElementById('fr').src = 'proc/tn_m.php?id='+idAcct+'&face_to='+face_to+'&to_face='+to_face+'&num_dover='+num_dover+'&date_dover='+date_dover+'&number='+number+'&seriass='+seriass+'&srt='+srt+'&flafff='+flafff+'&dates='+dates+'&no_nds='+no_nds;
							} else {
							document.getElementById('tn_err8').style.display = 'block';
						}
						
					}
					
				}
			</script>
			
			
			<link rel="stylesheet" href="../../js/jquery.alertable.css">
			<!--<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>-->
			<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
			<script src="../../js/jquery.alertable.min.js"></script>
			<script>
				$(function() {
					// Confirm
					$('#confirm').on('click', function() {
						$.alertable.confirm('Внимание! При печати бланк сохраниться в базу! Если вы действительно уверены, что ввели все верно того подтвердите печать! Иначе отмените! Не забывайте проставить кол-во копий 2!!!').then(function() {
							var 	i_err = 0;
							num_doc = '<? echo $num_doc_m ?>';
							if (num_doc == ''){
								document.getElementById('tn_err8').style.display = 'block';
								document.getElementById('tn_err7').style.display = 'block';
								i_err = i_err + 1;
							}
							UNP = '<? echo $UNP ?>';
							if (UNP == '0'){
								document.getElementById('tn_err9').style.display = 'block';
								document.getElementById('tn_err7').style.display = 'block';
								i_err = i_err + 1;
							}	
							
							var idAcct = "<? echo $idAcct ?>";
							if (document.getElementById('no_nds').checked) {
								no_nds = 1;
							}
							number = document.getElementById('number').value;
							if (number == ""){
								document.getElementById('tn_err2').style.display = 'block';
								i_err = i_err + 1;
							}
							seriass = document.getElementById('seriass').value;
							if (seriass == ""){
								document.getElementById('tn_err7').style.display = 'block';
								i_err = i_err + 1;
							}
							
							face_to = document.getElementById('face_to').value;
							if (face_to == ""){
								document.getElementById('tn_err3').style.display = 'block';
								i_err = i_err + 1;
							}
							to_face = document.getElementById('to_face').value;
							if (to_face == ""){
								document.getElementById('tn_err4').style.display = 'block';
								i_err = i_err + 1;
							}
							
							//не экспортировать в 1С
							var is_not_exp = 0;
							if(document.getElementById("is_not_exp").checked)
								is_not_exp = 1;
							
							flafff = 0;	
							if (document.getElementById('chek').checked){
								flafff = 1;
								
								
								num_dover = document.getElementById('num_dover').value;
								if (num_dover == ""){
									document.getElementById('tn_err5').style.display = 'block';
									i_err = i_err + 1;
								}
								date_dover = document.getElementById('date_dover').value;
								if (date_dover == ""){
								document.getElementById('tn_err6').style.display = 'block';
								i_err = i_err + 1;
								}
								}
								var srt = "";
								//total
								var nodeList = document.getElementsByName('ch11Del');
								var array = Array.prototype.slice.call(nodeList);
								for (var i = 0; i < array.length; i++) {
								if (array[i].checked) {
								var tr = array[i].parentNode.parentNode;
								srt =	srt  + tr.id + "^" + document.getElementById('total'+tr.id).value + "|";	
								document.getElementById('total'+tr.id).value = "0";
								}
								}
								
								srt = srt.substring(0, srt.length-1);
								if (srt == ""){
								document.getElementById('tn_err1').style.display = 'block';
								i_err = i_err + 1;
								}
								dates = document.getElementById('date_tn').value;
								if (i_err == 0){
								num_doc = '<? echo $num_doc_m ?>';
								if (num_doc != ''){
								document.getElementById('confirm').disabled = false;
								
								document.getElementById('fr').src = 'proc/tn__m.php?id='+idAcct+'&face_to='+face_to+'&to_face='+to_face+'&num_dover='+num_dover+'&date_dover='+date_dover+'&number='+number+'&srt='+srt+'&flafff='+flafff+'&seriass='+seriass+'&dates='+dates+'&no_nds='+no_nds+'&is_not_exp='+is_not_exp;
								
								
								
								window.setTimeout(ffa,2000);
								document.getElementById('confirm').disabled = true;
								
								} else {
								document.getElementById('tn_err8').style.display = 'block';
								}
								
								
								}
								
								
								
								}, function() {
								});
								});
								});
								</script>
								<script>
								function rwe(){
								var idAcct = "<? echo $idAcct ?>";
								location.href = '_addAcct.php?id='+idAcct;
								
								}
								</script>
								<script>
								$(document).ready(function(){
								$("#face_to").val('<? echo $dolj_pol?>');
								$("#to_face").val('<? echo $fio_pol?>');
								if ('<? echo $dover?>' != ""){
								document.getElementById('chek').checked=true;
								$("#num_dover").val('<? echo $dover1?>');
								$("#date_dover").val('<? echo $date_rrr?>');
								
								}
								
								});
								</script>
								
								
								
								</body>
								</html>								