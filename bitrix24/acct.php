<?php
	include "../pages/db.php";
	include "../pages/pg/utility.php";
	
	if(!isset($_GET['dataLoad'])):
		die(null);
	else:
		$data  = json_decode($_GET['dataLoad']);
		if(empty($data->user_id))
			die(null);
		//проверяем или заполнен ид юзера в бд
		$select = "SELECT * FROM users WHERE id_bitrix24=".$data->user_id;
		$query = mysql_query($select) or die(null);
		if(!$row = mysql_fetch_array($query)) {
			die("Не задан ИД пользователя во внутренней системе. ИД в Битрикс24 - ".$data->user_id);
		}
?>
<!doctype html>
	<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <title>Создать расчет</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="../favicon.png" type="image/png">

        <!-- Stylesheet -->
        <link rel="stylesheet" href="css/main.css">
		<script src="js/main.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<!-- DataTables JavaScript -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
		
        <link rel="stylesheet" href="css/acct.css">
		<script src="/bitrix24/controller/controller_bitrix.js"></script>
		<script src="js/acct.js"></script>
    </head>
    <body class="o-page o-page--center">
		<div id="body_content">
			<script>
				document.getElementById("body_content").setAttribute('data-attr-full', JSON.stringify(<? echo json_encode($data) ?>));
			</script>
			<form class='form-signin' method='post' action='' enctype='multipart/form-data' id='forms'>				
				<div class='row'>
					<div class='col-md-3'><label>Название расчета: </label></div>
					<div class='col-md-4'><input type='text' id='p_names' size='100%' style='width:100%' name='p_names' value='' onblur="get_price()" tabindex='1'></div>
				</div>
				
				<div class='row'>								
					<div class='col-md-3'>
						<div class="form-group"><label>Кол-во. изделий:</label><br>
						<label>Размер готового изделия:</label></div>
					</div>
					<div class='col-md-3'>
						<div class="form-group"><input type='text' id='p_cir' size='20' name='p_cir' value='' onblur="get_price()" tabindex='1'>
							<select id='unit_prod1' name='unit_prod1' tabindex='1' onblur="get_price()">
								<option value=''></option>
								<?
									$query = "select id,name from units";
									$result = mysql_query($query) or die($query);
									
									while ($row = mysql_fetch_array($result)) {
										
										echo "<option value='" . $row['name'] . "'> " . $row['name'] . "</option>";
										
									}
								?>
							</select><br><input type='text' id='p_size' size='20' name='p_size' value='' onblur="get_price()" tabindex='1'></div>
					</div>							
					<div class='col-md-2'>
						<div class="form-group"><label>Переплет</label><br><label>Сторона переплета:</label><br><label>Материал переплета:</label>
						</div>
					</div>
					<div class='col-md-3'>
						<div class="form-group">
							<select id='p_per_i' name="p_per" style="width:90%;" tabindex='1'></select><br>
							<select id='p_stor_i' name="p_stor" style="width:90%;" onblur="get_price()" tabindex='1'>
								<option value="0" style="display:block;"></option>
								<option value="1" style="display:block;">узкой</option>
								<option value="2" style="display:block;">широкой</option>
							</select><br>
							<select id='p_per_mat_i' name="p_per_mat" style="width:90%;" onblur="get_price()" tabindex='1'>
								<option style="display:block;" ></option>
									<?
										$query = "SELECT 
											ttr.ID, ttr.title, ttr.m_price, ttr.m_size, '8,9,10,11' idd, ttr.flags , ttr.parent   
											FROM
											(SELECT 
											kl.ID, kl.title, '' m_price, '' m_size, kl.flags , kl.parent  
											FROM  
											kl_mat kl where kl.id = 428 OR kl.id = 429 OR kl.id = 430
											ORDER BY kl.title) ttr
											GROUP BY ttr.ID ORDER BY ttr.title";
										$result = mysql_query($query) or die($query);
										$flags == "-1";
										WHILE ($row = mysql_fetch_array($result)) {
											$title = array(
												'm_price' => $row['m_price'],
												'm_size' => $row['m_size']
											);
											
											IF ($flags == "-1") {
												$flags = fun_group($row['ID']);
												$namess = fun_names($row['ID']);
												
												echo "<optgroup label='".$flags."' name = 'optgr' style='display: block;'><option value='".$row['ID']."' data-opt_gr = '".$flags."' data-attr = '".$row['idd']."' data-attr-size = '".$row['m_size']."' title='".json_encode($title)."' " . " style='display:none;'" . ">".$namess."</option>";
											} 
											ELSE {
												IF ($flags != fun_group($row['ID'])) {
													$flags = fun_group($row['ID']);											
													$namess = fun_names($row['ID']);
													
													echo "</optgroup> <optgroup label='".$flags."' name = 'optgr' style='display: block;'>";
													echo "<option value='".$row['ID']."' data-opt_gr = '$flags' data-attr = '".$row['idd']."' data-attr-size = '".$row['m_size']."' title='".json_encode($title)."' " . " style='display:block;'" . ">$namess</option>";
													
													} 
												ELSE {												
													$namess = fun_names($row['ID']);
													echo "<option value='".$row['ID']."'  data-opt_gr = '".$flags."' data-attr = '".$row['idd']."' data-attr-size = '".$row['m_size']."' title='".json_encode($title)."' " . ' style="display:block;"' . ">$namess</option>";
												}
											}
										}
										echo "</optgroup>";
									?>
							</select><br>
							<div id = 'err_chk'></div>
						</div>
					</div>
				</div>
				
				<?		
					$query = "select id, name, time_,default_ from PR_OPER";
					$result = mysql_query($query) or die($query);
					$iddd = '';
					while ($row = mysql_fetch_row($result)) {
						if ($row["default_"] == 1) {
							$iddd = $iddd . $row["id"] . ",";
						}
					}
					$iddd = substr($iddd, 0, -1);	
				?>
				<br>
				
				<div class='row'>
					<div class='col-md-3'>
						<div class='checkbox'>
							<label>
								<input type='checkbox' name='optionsRadiosInline' id='optionsRadiosInline1' value='option1' onchange='maket(this.checked)'>Дизайн
							</label>
						</div>
					</div>
					<div class='col-md-3'>
						<div class='checkbox'>
							<label>
								<input type='checkbox' name='optionsRadiosInline' id='optionsRadiosInline2' value='option2' onchange="prepressInit(this.checked)" checked>Препресс
							</label>
						</div>
					</div>
				</div>
				
				<div class='row'>
					<div class='col-md-3'>
						<div id='op1' style='display:none;'>
							<button type='button' class='c-btn c-btn--secondary c-btn--sm' onclick='addViewDesign()'>Выбор дизайн</button>
						</div>
					</div>
					<div class='col-md-3'>
						<div id='op2' style='display:none;'>
							<!--<button type='button' class='c-btn c-btn--secondary btn--sm' onclick='addViewPrepress()'>Выбор препресс</button>-->
						</div>
					</div>
					<div class='col-md-2'></div>
					<div class='col-md-4'>
						<div id="listss"></div>
					</div>	
				</div>
				
				<div class="row" id="div_p_press_">
					<div class='col-md-3'>
						<label>Стоимость препресса:</label>
					</div>
					<div class="col-md-9">
						<input type='text' tabindex='tabkol' id='p_press_' style='width:100%;' onblur="get_price()" onclick="get_price()" value="0.46">
					</div>
				</div>
				<div class="row" id="div_p_prdiz_" style="display: none">
					<div class='col-md-3'>
						<label>Стоимость дизайна:</label>
					</div>
					<div class="col-md-9">
						<input type='text' tabindex='tabkol' id='p_prdiz_' style='width:100%;' onblur="get_price()" onclick="get_price()">
					</div>
				</div>
				
				<hr>			
				<div class='row'>
					<div class='col-md-12'>			
						<div class='row'>
							<div class='col-md-3'>
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="" name="qwerty"
											onClick="checks('check1','')" id="check1_">Перфорация
										</label>
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" value="" name="qwerty"
											onClick="checks('check2','')" id="check2_">Скругление углов
										</label>
									</div>
								</div>
							</div>
							<div class='col-md-3'>
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="" name="qwerty"
											onClick="checks('check13','')" id="check13_">Биговка
										</label>
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" value="" name="qwerty"
											onClick="checks('check4','check3')" id="check4_">Отверстия
										</label>
									</div>
								</div>
							</div>
							<div class='col-md-3'>
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="" name="qwerty"
											onClick="checks('check5','check6')" id="check5_">Люверс
										</label>
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" value="" name="qwerty"
											onClick="checks('check7','check10')" id="check7_">Вырубка
										</label>
									</div>
								</div>
							</div>
							<div class='col-md-2'>
								<div class="form-group">
									<div class="checkbox">
										<label>
											<input type="checkbox" value="" name="qwerty"
											onClick="checks('check8','check11')" id="check8_">Конгрев
										</label>
									</div>
									<div class="checkbox">
										<label>
											<input type="checkbox" name="qwerty" value=""
											onClick="checks('check9','check12')" id="check9_">Тиснение
										</label>
									</div>
								</div>
							</div>
							
							<div class='col-md-1'>
								<button type="button" class="btn btn-default btn-circle" onclick='rowTable()'
								id="bnrr"><i class="fa fa-plus"></i></button>
								<button type="button" class="btn btn-default btn-circle" onclick='delOneRowTable()'
								id="bnrr"><i class="fa fa-minus"></i></button>
							</div>
						</div>
						
						<table class='table' id='dynamic'>
							<tr class='odd gradeX' id='tr1'>
								<td><label>Наим. части:</label></td>
							</tr>
							<tr class='odd gradeX'>
								<td><label>Размер изделия:</label></td>
							</tr>
							<tr class='odd gradeX'>
								<td><label>Кол-во стр:</label></td>
							</tr>
							<tr class='odd gradeX'>
								<td><label>Оборудование:</label></td>
							</tr>
							<tr class='odd gradeX'>
								<td><label>Цвет:</label></td>
							</tr>
							<tr class='odd gradeX'>
								<td><label>Бумага:</label></td>
							</tr>
							<tr class='odd gradeX'>
								<td><label>Размер бумаги:</label></td>
							</tr>
							<tr class='odd gradeX'>
								<td><label>Резка:</label></td>
							</tr>
							<tr class='odd gradeX'>
								<td><label>Ламинирование:</label></td>
							</tr>
							<tr class='odd gradeX' hidden id="check13">
								<td><label>Кол-во биговок:</label></td>
							</tr>
							<div>
								<tr class='odd gradeX' hidden id="check1">
									<td><label>Кол-во перф.:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check2">
									<td><label>Кол-во скр. углов:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check3">
									<td><label>Кол-во отверстий:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check4">
									<td><label>Диаметр отверстий:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check5">
									<td><label>Кол-во люверсов:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check6">
									<td><label>Цвет, диам. люверса:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check7">
									<td><label>Вырубка:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check8">
									<td><label>Конгрев:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check9">
									<td><label>Тиснение:</label></td>
								</tr>
								<tr class='odd gradeX'>
									<td><label>Работы на стороне:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check10">
									<td><label>Цена штампа:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check11">
									<td><label>Цена клише, конгрев:</label></td>
								</tr>
								<tr class='odd gradeX' hidden id="check12">
									<td><label>Цена клише, тиснение:</label></td>
								</tr>
								<tr class='odd gradeX'>
									<td><label>Параметры:</label></td>
								</tr>
							</div>
						</table>
							
						<hr>
						<div class='row'>
							<div class='col-md-4'>
								<div class='row'>
									<div class='col-md-3'><label>Стоимость:</label></div>
									<div class='col-md-9'>
										<input style="width: 100%;" type='text' id='p_sum_all' readonly>
										<input type='hidden' id='kol' value="0" name='kol'>
									</div>
								</div>

								<div class='row'>
									<div class='col-md-3'><label>Сумма(без расчета):</label></div>
									<div class='col-md-9'>
										<input style="width: 100%;" type='number' id='p_sum_all_hand'>
									</div>
								</div>								
								
								<div class='row'>
									<div class='col-md-3'><label>Срочность:</label></div>
									<div class='col-md-9'>
										<select style="width: 100%;" id='p_fast' name='p_fast' onblur="get_price()">
											<option value="1" selected>ОБЫЧНО</option>
											<option value="1.2">СРОЧНО</option>
											<option value="1.5">ОЧЕНЬ СРОЧНО</option>
										</select>
									</div>
								</div>								
							</div>
							<div class='col-md-8'>
								<label>Комментарий:</label>
								<textarea rows="5" id='list_comm' name='list_comm' style="width: 100%;"></textarea>
							</div>
						</div>
					</div>
				</div>
			</form>
			<div class="row" style="margin-top: 20px;">
				<!-- Кнопка сохранить -->
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<a class="c-btn c-btn--success c-btn--fullwidth" onclick="clickSave()">Сохранить</a>
						</div>
					</div>
				</div>
				<!-- Кнопка просмотра расчета -->
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<a class="c-btn c-btn--fancy c-btn--fullwidth" onclick="viewResultCalc()">Просмотр расчета</a>
						</div>
					</div>
				</div>
				<!-- Кнопка отмена -->
				<div class="col-md-4">
					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
							<a class="c-btn c-btn--secondary c-btn--fullwidth" onclick="clickCancel()">Отмена</a>
						</div>
					</div>
				</div>
			</div>
		</div>
    </body>
</html>
<? endif; ?>