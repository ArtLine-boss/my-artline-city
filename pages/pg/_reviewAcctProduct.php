
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Добавление продукта</title>
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
   
    
    <script src="js204.js"></script>
    
    <script>
       /* window.onblur = function () {window.close()}*/
    </script>
	 <script type="text/javascript">
	function dynamicSelect(id1, id2) {

		// Сперва необходимо проверить поддержку W3C DOM в браузере
		if (document.getElementById && document.getElementsByTagName) {
			// Определение переменных, ссылающихся на списки
			var sel1 = document.getElementById(id1);
			var sel2 = document.getElementById(id2);
			// Клонирование динамического списка
			var clone = sel2.cloneNode(true);
			// Определение переменных для клонированных элементов списка
			var clonedOptions = clone.getElementsByTagName("option");
			// Вызов функции собирающей вызываемый список
			refreshDynamicSelectOptions(sel1, sel2, clonedOptions);
			// При изменении выбранного элемента в первом списке: // вызов функции пересобирающей вызываемый список
			sel1.onchange = function() {
				refreshDynamicSelectOptions(sel1, sel2, clonedOptions);
			}
		}
	}
 
// Функция для сборки динамического списка
 
function refreshDynamicSelectOptions(sel1, sel2, clonedOptions) {
 
// Удаление всех элементов динамического списка
 
 while (sel2.options.length) {
  sel2.remove(0);
 }
 var pattern1 = /( |^)(select)( |$)/;
 var pattern2 = new RegExp("( |^)(" + sel1.options[sel1.selectedIndex].value + ")( |$)");
 
// Перебор клонированных элементов списка
 
 for (var i = 0; i < clonedOptions.length; i++) {
 
// Если название класса клонированного option эквивалентно "select" // либо эквивалентно значению option первого списка
 
  if (clonedOptions[i].className.match(pattern1) ||
  clonedOptions[i].className.match(pattern2)) {
 
// его нужно клонировать в динамически создаваемый список
 
   sel2.appendChild(clonedOptions[i].cloneNode(true));
  }
 }
}
</script>
		
</head>
<body >
    <div class='container'>
        <div class='masthead'>
		 <?php
				$id_orderProd =$_GET['id_orderProd'];
				$id_prod = $_GET['id_prod'];
				$id_ord  = $_GET['id_ord'];
				$activ = $_GET['activ'];
				$summMR = 0;
		
				IF($id_orderProd != ''){
					INCLUDE '../db.php';
						$query = "select cshivka from order_product where id = ".$id_orderProd;
					$result = mysql_query($query) or die($query);
							WHILE ($row = mysql_fetch_row($result)) { 
								$cshivka= $row[0];
							}
					
					$query = "select c.nadbavka from clients c, (select o.client_id from orders o,(select order_id from order_product where id = ".$id_orderProd.") op where o.NUMBER = op.order_id) o where c.id = o.client_id";
					
					$result = mysql_query($query) or die($query);
					WHILE ($row = mysql_fetch_row($result)) { 
						$clients_nadbavka = $row[0];
					}
					IF ($clients_nadbavka ==''){
						$clients_nadbavka = 0;
					} ELSE {
						$clients_nadbavka = str_replace(',', '.', $clients_nadbavka);
					}
					$query = "select TEMPLATE,TOTAL,SIZE,p_names from order_product where id = ".$id_orderProd." and TEMPLATE is not null";
					$result = mysql_query($query) or die($query);
					WHILE ($row = mysql_fetch_row($result)) { 
						$PRODUCT_TEMP_REVIEW = $row[0];
						$PRODUCT_TEMP_REVIEW_KOL = $row[1];
						$SIZE = $row[2];
						$NAME_PRODDD = $row[3];
					}
					
					
					$query = "select val from settings s where s.id = 2";
					$result = mysql_query($query) or die($query);
					WHILE ($row = mysql_fetch_row($result)) { 
						$kurs = $row[0];
					}
					IF ($kurs ==''){
						$kurs = 0;
					} ELSE {
						$kurs = str_replace(',', '.', $kurs);
					}
					
					$query = "select val from settings s where  s.id = 4";
					$result = mysql_query($query) or die($query);
					WHILE ($row = mysql_fetch_row($result)) { 
						$nds = $row[0];
					}
					IF ($nds ==''){
						$nds = 0;
					} ELSE {
						$nds = str_replace(',', '.', $nds);
					}
					
					$query = "select val from settings s where  s.id = 3";
					$result = mysql_query($query) or die($query);
					WHILE ($row = mysql_fetch_row($result)) { 
						$price_diz = $row[0];
					}
					IF ($price_diz ==''){
						$price_diz = 0;
					} ELSE {
						$price_diz = str_replace(',', '.', $price_diz);
					}
						
					$kol = 0;  
					$summOper;
					$summMat = 0;
					$summMR = 0;
					$query = "select PRODUCT_NAME, PRODUCT_TEMPLATE, PRODUCT_SIZE,PRODUCT_SH,PRODUCT_SKOBA, kat_1,kat_2, prod_sh from product where id = ".$id_prod;
					$result = mysql_query($query) or die($query);
					WHILE ($row = mysql_fetch_row($result)) { 
					$PRODUCT_NAME = $row[0];
					$PRODUCT_TEMPLATE = $row[1];
					$PRODUCT_SIZE = $row[2];	
					$PRODUCT_SH = $row[3];
					$PRODUCT_SKOBA = $row[4];	
					$kat_1 = $row[5];
					$kat_2 = $row[6];
					$PRODUCT_Shs = $row[7];	
					}
						echo  "<form  class='form-signin' method='post' action='_UpdateOrProdsf.php' enctype='multipart/form-data' onsubmit='return validate_form ( );'>	";
						echo  " <h3 class='text-muted' id = 'pname'  >".$PRODUCT_NAME."</h3>";
						echo  "<div class='row'>
									<div class='col-md-2'>
										<div class='block1'> 
											<label>Наименование:</label>
										</div>
									</div>  
									<div class='col-md-6'>
										<div class='block2'>
											<input  type = 'text'  id = 'p_names' size='40' name = 'p_names' value = '".$NAME_PRODDD."' >
										</div>
									</div>";
					IF ($activ == '1')	{	ECHO 	"<div class='col-md-4'>
										<div class='block2'>
											<button type='button' class='btn btn-xs'    onclick='dd(1)'>Дата готовности</button>
										</div>
									</div>";}
					ECHO 	"</div>";
						echo  "<div class='row'>
								<div class='col-md-2'>
									<div class='block1'> 
										<label>Размер:</label>
									</div>
								</div>  
								<div class='col-md-6'>
									<div class='block2'>
										<input  type = 'text'  id = 'psize' size='10' name = 'psize' value = '".$SIZE."' >
									</div>
								</div>
								<div class='col-md-4'>
									<div class='block2'>
				
										<div id = 'date_time'></div>
									</div>
								</div>
							</div>";
						echo  "<div class='row'>
								<div class='col-md-2'> 
									<div class='block1'> 
										<label>Изоб-ние на вылет:</label>
									</div>
								</div>  
								<div class='col-md-4'>
									<div class='block2'>
										<input  type = 'checkbox'  id = 'chek' checked>
									</div>
								</div>
								
							</div>";
							echo  "<div class='row'>
								<div class='col-md-2'> 
									<div class='block1'> 
										<label>Надбавка клиента:</label>
									</div>
								</div>  
								<div class='col-md-4'>
									<div class='block2'>
										<input  type = 'text'  id = 'clients_nadbavka' value='$clients_nadbavka' size=5>%
									</div>
								</div>
								
							</div>";
						echo "<div class='row'>
									<div class='col-md-12'>
										
										<div class='form-group'>
                                            <label>Макет :</label>
                                            <label class='radio-inline'>
                                                <input type='radio' name='optionsRadiosInline' id='optionsRadiosInline1' value='option1' onclick='maket(1)'>Нет
                                            </label>
                                            <label class='radio-inline'>
                                                <input type='radio' name='optionsRadiosInline' id='optionsRadiosInline2' value='option2' onclick='maket(2)'>Да
                                            </label>
                                            <label class='radio-inline'>
                                                <input type='radio' name='optionsRadiosInline' id='optionsRadiosInline3' value='option3' onclick='maket(3)'>Готово
                                            </label>
                                        </div>
									</div>
								</div>";		

							
							
						echo  "<div id = 'op1'  style='display:none;'>
						<div class='row'>
						<div class='col-md-1'>
						<div class='block1'> 
							<lable>&nbsp;&nbsp; Дизайн:&nbsp; </label>
							</div></div>
							<div class='col-md-2'>
								<div class='block1'>
								<button type='button' class='btn btn-default'  data-toggle='modal' data-target='#myModal3'>Выбор дизайн</button>	
								</div>
							</div>
							<div class='col-md-3'>
								<div class='block2'>
									<input name='file[]' type='file' multiple='true'/>	
								</div>
							</div>
							<div class='col-md-2'>
								<div class='block2'>
									<lable>Стоимость:&nbsp;<input  type = 'text' size='5' value = '0' id = 'dizz_sum' name = 'dizz_sum'> 
									<input  type = 'hidden'  id = 'view_diz'  name = 'view_diz'  size='10' value = '0'>
								</div>
							</div>
						</div>
								</div>";	
								
						echo  "<div id = 'op2'  style='display:none;'><div class='row'>
						<div class='col-md-1'>
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
									<lable>Стоимость:&nbsp; </label><input  type = 'text' size='5' value = '0' id = 'press_sum' name = 'press_sum'>"; 
									$query="select * from PR_OPER";
									$result = mysql_query($query) or die($query);
									$iddd = '';
									while ($row = mysql_fetch_row($result)) { 
									if ($row[3] == 1){
										$iddd = $iddd.$row[0].",";
									}}
									$iddd  = substr($iddd, 0, -1);
									echo  "<input type = 'hidden'  id = 'view_press' name = 'view_press' size='10' value = '".$iddd."'>
								</div>
							</div>
						</div>
						</div>";		
							
							echo  "<div id = 'op3'  style='display:none;'>
										<div class='row'>
											<div class='col-md-1'>
												<input name='file2[]' type='file' multiple='true'/>		
											</div>
										</div>
									</div>";	
											
						IF ($PRODUCT_SKOBA != 0 OR $PRODUCT_Shs != 0){
						echo  "<div class='row'>
								<div class='col-md-2'>
									<div class='block1'> 
										<label> Сшивка: </label> 
									</div>
								</div>  
								<div class='col-md-10'>
									<div class='block2'>
									<select name='selSh' id='selSh' >";
							switch ($cshivka)
								{
									case "1":		echo  "<option value='0' ></option>
									<option value='1' selected>по короткой стороне</option>	
									<option value='2' >по длинной стороне</option>";    break;
									default: 	echo  "<option value='0' ></option>
									<option value='1' >по короткой стороне</option>	
									<option value='2' selected>по длинной стороне</option>";    break;
									
								}	
									
								echo  "</select></div>
								</div>
							</div>";
							} ELSE {
								echo  "<input type = 'hidden'  name='selSh' id='selSh' size='10' value = '0'>";
						} 
												echo "<h6>";
					$PRODUCT_TEMPLATE_ROW = explode("!", $PRODUCT_TEMPLATE);/*части*/
					FOR ($i = 0; $i < count($PRODUCT_TEMPLATE_ROW); $i++){		
						$PRODUCT_TEMPLATE_PART = explode("^", $PRODUCT_TEMPLATE_ROW[$i]);/*Рассмотрение частей по порядку*/;	
						IF ($PRODUCT_TEMPLATE_PART[0] != 'Сборка в готовое изделие' OR $PRODUCT_TEMPLATE_PART[0] != 'Сборка готового изделия'){
						echo  " <h4 class='text-muted'>".$PRODUCT_TEMPLATE_PART[0]."</h3>";
						}
						$PRODUCT_TEMPLATE_T = explode(";", $PRODUCT_TEMPLATE_PART[1]);
						FOR ($x = 0; $x < count($PRODUCT_TEMPLATE_T); $x++){
							$PRODUCT_TEMPLATE_TYPE = explode("$", $PRODUCT_TEMPLATE_T[$x]);	
		
	
							IF($PRODUCT_TEMPLATE_TYPE[0] == 1){
							$name_operrra = "";
											$query = "select OPERATION_NAME from operations where OPERATION_NAME = '".$PRODUCT_TEMPLATE_TYPE[1]."' and par is not null;";
									IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
										$query = "select OPERATION_NAME from operations where id = '".substr($PRODUCT_TEMPLATE_TYPE[1],1)."' and par is not null;";
									}
									$result = mysql_query($query) or die($query);
									WHILE ($row = mysql_fetch_row($result)) { 
									$name_operrra = $row [0];
									}
											
								echo "<b><i>".$name_operrra.":</i></b>";
								/*Оборудование*/
								IF ($PRODUCT_TEMPLATE_TYPE[4]  == 1){
									IF($PRODUCT_TEMPLATE_TYPE[5] != ''){
										 echo  "<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> 
															<label id = 'cch".++$kol."8'>Оборудование: </label>
														</div>
													</div>";
											echo  "<div class='col-md-10'>
													<div class='block2'>
														<select  id = 'ch".$kol."8' name = ''  onchange = " . '"'."rts('ch".$kol."8', 'ch".$kol."1','ch".$kol."3',".$kol.")".'" '."> <option value='0' title='0'>Выберите</option>"; 
										$ID_EQ = explode(",", $PRODUCT_TEMPLATE_TYPE[5]);
										FOR ($g = 0; $g < count($ID_EQ); $g++){
											$query = "select id, eq_name, l_use, l_offset from equipment where id = ".$ID_EQ[$g]." and  eq_name is not null;";
											$result = mysql_query($query) or die($query);
											WHILE ($row = mysql_fetch_row($result)) { 
												if($row[2] == '1'){
													$eq_id_entr = $row[0];
													echo  "<option value='$row[0]' title='$row[3]' selected >$row[1]</option>"; 
												} else {
													echo  "<option value='$row[0]'  title='$row[3]' >$row[1]</option>"; 
												}
											}
										}
										echo  "</select>
													</div>
													</div>
												</div>";
									}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".++$kol."8' size='10' value = '0'>";
									}
								}ELSE {
									IF($PRODUCT_TEMPLATE_TYPE[5] != ''){
										if ($kol == $lastkol) {
										$kol++;
											
									}	
									$query = "select id, eq_name,l_use from equipment where id = ".$PRODUCT_TEMPLATE_TYPE[5]." and  eq_name is not null;";
									$result = mysql_query($query) or die($query);	
									WHILE ($row = mysql_fetch_row($result)) { 
										echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '$row[0]'>";

										$eq_id_entr = $row[0];
									}
									}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '0'>";
									}
								}
								
							
								
								IF ($PRODUCT_TEMPLATE_TYPE[1] == 'Офсетная печать'){
									
									echo  "<input  type = 'hidden'  id = 'ch".++$kol."8' size='10' value = '0'>";
										echo  "<div class='row'>
										<div class='col-md-2'>
											<div class='block1'> 
												<label id = 'cch".$kol."1'>".$PRODUCT_TEMPLATE_TYPE[1]."</label>
											</div>
										</div>  
									   <div class='col-md-10'>
										<div class='block2'>
											<select id = 'ch".$kol."1'  >";
											echo  "<option value=''>Выберите</option>"; 
								$query = "select id, name, price_total,nadbavka, total from offcet";
								$result = mysql_query($query) or die($query);
								WHILE ($row = mysql_fetch_row($result)) {
									
												$off_pr = $row[2] + (($row[2] * $row[3]) /100);
												echo  "<option value='$off_pr'>$row[1]</option>"; 
												}
												echo  "</select></div>
								</div>
							</div>";
								}
								
								else {
									IF($PRODUCT_TEMPLATE_TYPE[1] != '' ){
									if ($kol == $lastkol) {
										$kol++;
												echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '0'>";
									}									
								$query = "select o.id, o.par, o.OPERATION_PRICE,o.MAKEREADY_PRICE,o.OPERATION_NAME, e.id from  equipment e, (select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where OPERATION_NAME = '".$PRODUCT_TEMPLATE_TYPE[1]."' and par is not null) o where FIND_IN_SET(o.id ,e.oper);";
								IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
										$query = "select o.id, o.par, o.OPERATION_PRICE,o.MAKEREADY_PRICE,o.OPERATION_NAME, e.id from  equipment e, (select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where id = '".substr($PRODUCT_TEMPLATE_TYPE[1],1)."' and par is not null) o where FIND_IN_SET(o.id ,e.oper);";
									}
								$result = mysql_query($query) or die($query);
								IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) != 'i'){
									if (mysql_num_rows($result)>1){
								echo  "<div class='row'>
										<div class='col-md-2'>
											<div class='block1'> 
												<label id = 'cch".$kol."1'>Параметры: </label>
											</div>
										</div>  
									   <div class='col-md-10'>
										<div class='block2'>
											<select id = 'ch".$kol."1'  >";
								echo  "<option label = '0'  value=''>Выберите</option>"; 
								
								WHILE ($row = mysql_fetch_row($result)) { 
								$name_oper = $row[4];
								if ($row[3] == ''){
									$prol = 0;
								}else{
									$prol = $row[3];
								}
								if ($row[5] == $eq_id_entr){
										echo  "<option label = '$row[5]' value='$row[0]#$row[2]^$prol'".' style="display:block;"'.">$row[1]</option>"; 
								} else {
									echo  "<option label = '$row[5]' value='$row[0]#$row[2]^$prol' ".' style="display:none;"'.">$row[1]</option>"; 
								}
								
									
									}
								
								echo  "</select></div>
								</div>
							</div>";
						
								}
								else {
										WHILE ($row = mysql_fetch_row($result)) { 
									if ($row[3] == ''){
									$prol = 0;
								}else{
									$prol = $row[3];
								}
								$name_type = $row[4];
								$name_oper = $row[4];
								echo  "<label id = 'cch".$kol."1' ".' style="display:block;">' ."</label>
								<input  type = 'hidden'  id = 'ch".$kol."1' size='10' value = '$row[0]#$row[2]^$prol'>";
								}
								}
								}
							ELSE {
									WHILE ($row = mysql_fetch_row($result)) { 
									if ($row[3] == ''){
									$prol = 0;
								}else{
									$prol = $row[3];
								}
								$name_type = $row[4];
								$name_oper = $row[4];
								echo  "<label id = 'cch".$kol."1' ".' style="display:block;">' ."</label>
								<input  type = 'hidden'  id = 'ch".$kol."1' size='10' value = '$row[0]#$row[2]^$prol'>";
								}
							}
								}ELSE {
									echo  "<label id = 'cch".$kol."1' ".' style="display:block;">' ."</label>
									<input  type = 'hidden'  id = 'ch".$kol."1' size='10' value = '0#0^0'>";
								}
								
							}
								IF($PRODUCT_TEMPLATE_TYPE[2] == 1){
									echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'> ";
													IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
															 echo "<label id = 'cch".$kol."2'>".$name_type."</label>";
														}else {
															 echo "<label id = 'cch".$kol."2'>Количество: </label>";
														}
														
													echo "</div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."2' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[3]."'></div>
													</div>
												</div>";
								} ELSE { 
									echo  "<input  type = 'hidden'  id = 'ch".$kol."2' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[3]."'>";
								}
								
								/*Материал*/
								IF($PRODUCT_TEMPLATE_TYPE[6]  == 1){
									IF($PRODUCT_TEMPLATE_TYPE[8] != ''){
										  echo   "<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> ";
														IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
															 echo "<label id = 'cch".$kol."3'>".$name_type."</label>";
														}else {
															 echo "<label id = 'cch".$kol."3'>Материал: </label>";
														}
														
															
													 echo "</div>
													</div>";
										echo  "<div class='col-md-10'>
													<div class='block2'>
														<select  id = 'ch".$kol."3' name = '' ><option label = '0' value='0@0!^0' >Выберите материал</option>"; 
										$TEMPLATE_TYPE = explode(",", $PRODUCT_TEMPLATE_TYPE[8]);
										
										
											FOR ($z = 0; $z < count($TEMPLATE_TYPE); $z++){
											$query = "select  ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE , ma.m_tol, eq.id from equipment eq, (select ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE , ma.m_tol 
											from  material_attr ma where ma.ID = '".$TEMPLATE_TYPE[$z]."' and ma.M_NAME is not null) ma where FIND_IN_SET(ma.id ,eq.mater);";
											$result = mysql_query($query) or die($query);
											WHILE ($row = mysql_fetch_row($result)) { 
												if ($row[5] == $eq_id_entr){
													echo  "<option label = '$row[5]' value='$row[0]@$row[2]!$row[3]^$row[4]' ".' style="display:block;">' ."$row[1]</option>"; 
											} else {
													echo  "<option label = '$row[5]' value='$row[0]@$row[2]!$row[3]^$row[4]' ".' style="display:none;">' ."$row[1]</option>"; 
											}
								
											
												}
											}
									
										echo  "</select>
													</div>
													</div>
												</div>";
									
									}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".$kol."3' size='10' value = '0@0!0*0^0'>";
									}
								}ELSE {
									IF($PRODUCT_TEMPLATE_TYPE[8] != ''){
									$query = "select ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE, ma.m_tol  from  material_attr ma where ma.ID = '".$PRODUCT_TEMPLATE_TYPE[8]."' and ma.M_NAME is not null;";
									$result = mysql_query($query) or die($query);	
									WHILE ($row = mysql_fetch_row($result)) { 
										echo  "<input  type = 'hidden'  id = 'ch".$kol."3' size='10' value = '$row[0]@$row[2]!$row[3]^$row[4]'>";
									}
									}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".$kol."3' size='10' value = '0@0!^0''>";
									}
								}
								IF($PRODUCT_TEMPLATE_TYPE[9] == 1){
									echo 	"<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> 
															<label id = 'cch".$kol."4'> Количество: </label>
														</div>
													</div>
													<div class='col-md-10'>
														<div class='block2'>
															<input  type = 'text'  id = 'ch".$kol."4' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[10]."'>
														</div>
													</div>
												</div>";
								}ELSE {
									echo 	"<input  type = 'hidden'  id = 'ch".$kol."4' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[10]."'>";
								}
								
								IF($PRODUCT_TEMPLATE_TYPE[11] == 1){
									echo 	"<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> 
															<label id = 'cch".$kol."5'> Количество страниц: </label>
														</div>
													</div>
													<div class='col-md-10'>
														<div class='block2'>
															<input  type = 'text'  id = 'ch".$kol."5' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[12]."'>
														</div>
													</div>
												</div>";
								}ELSE {
									echo 	"<input  type = 'hidden'  id = 'ch".$kol."5' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[12]."'>";
								}
								
								
								IF($PRODUCT_TEMPLATE_TYPE[13] == 1){
									echo 	"<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> 
															<label id = 'cch".$kol."6'> Размер: </label>
														</div>
													</div>
													<div class='col-md-10'>
														<div class='block2'>
															<input  type = 'text'  id = 'ch".$kol."6' size='10'  value = '".$PRODUCT_TEMPLATE_TYPE[14]."'>
														</div>
													</div>
												</div>";
								}ELSE {
									echo 	"<input  type = 'hidden'  id = 'ch".$kol."6' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[14]."'>";
								}
								
						
								IF($PRODUCT_TEMPLATE_TYPE[15] == 1){
									  echo   "<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> ";
														IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
															 echo "<label id = 'cch".$kol."7'>".$name_type."</label>";
														}else {
															 echo "<label id = 'cch".$kol."7'>Номер штампа: </label>";
														}
														
															
													 echo "</div>
													</div>";
										echo  "<div class='col-md-3'>
													<div class='block2'>
														<select  id = 'ch".$kol."7' name = '' onchange=".'"img('."'ch".$kol."7','img".$kol."')".'"'."><option  label = '' value='0?0^0*0/1`0'>Выберите номер</option>"; 
										$TEMPLATE_TYPEr = explode(",", $PRODUCT_TEMPLATE_TYPE[16]);
										
										FOR ($z = 0; $z < count($TEMPLATE_TYPEr); $z++){
										 $query = "select ID, STAMP_TYPE , STAMP_NAME, STAMP_KOL,STAMP_SIZE,STAMP_PRICE,STAMP_NEW from stamps WHERE  ID = '".$TEMPLATE_TYPEr[$z]."' AND STAMP_NAME  <> '' ORDER BY STAMP_TYPE";
										$result = mysql_query($query) or die($query);
										WHILE ($row = mysql_fetch_row($result)) { 
											echo  "<option value='$row[0]?$row[3]^$row[4]/$row[6]`$row[5]'>$row[2]</option>"; 
											}
										}
										echo  "</select>";
										echo  " &nbsp;<i class='glyphicon glyphicon-refresh' onclick=".'"'."ref(".$kol.", '".$name_operrra."' )".'"'."></i>";
										echo  "			</div>
													</div>";
											echo  "<div class='col-md-3'>
													<div class='block2'>	<img  class='rounded ' alt='' id = 'img".$kol."' width = ' 100'" .' style="display:none;"'."></div>
													</div></div>
												";
												
									echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '0'>";
									}ELSE {
									
										IF($PRODUCT_TEMPLATE_TYPE[16] != ''){
									$query = "select ID, STAMP_TYPE , STAMP_NAME, STAMP_KOL, STAMP_SIZE ,STAMP_PRICE,STAMP_NEW from stamps WHERE  ID = '".$PRODUCT_TEMPLATE_TYPE[16]."' AND STAMP_NAME  <> '' ORDER BY STAMP_TYPE";
									$result = mysql_query($query) or die($query);	
									WHILE ($row = mysql_fetch_row($result)) { 
										echo  "<input  type = 'hidden'  id = 'ch".$kol."7' size='10' value = '$row[0]?$row[3]^$row[4]/$row[6]`$row[5]'>";
									}
								}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".$kol."7' size='10' value = '0?0^0*0/1`0'>";
									}
						
						
										switch ($name_oper)
											{
												case 'Подрезка материала':		echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '1'>";   break;
												case 'Широкоформатная печать':		echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '10'>";   break;
												case 'Перфорация под. мет. пружину':	echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '11'>"; break;
												case 'Сшивка на скобу':			echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '12'>";  break;
												case 'Сшивка на PUR-клей':			echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '13'>";  break;
												case 'Установка мет. пружины':		echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '14'>";   break;

												default:   	echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '0'>";   break; break;
												
											}	
						
									/*IF ($name_oper == 'Подрезка материала'){
										echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '1'>";
									} else { 
									IF ($name_oper == 'Широкоформатная печать'){
										echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '10'>";
									} else {
									IF ($name_oper == 'Перфорация под. мет. пружину'){
										echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '11'>";
									} else {
									IF ($name_oper == 'Сшивка на скобу'){
										echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '12'>";
									} else {
												IF ($name_oper == 'Сшивка на PUR-клей'){
													echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '13'>";
												} else {
													echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '0'>";
												}
											}
										}
									}
								
											}*/
									}
								echo 	"<div id = 'ch".$kol."19' style=".'"display:none;"'.">";
										echo 	"<div class='row'>
													<div class='col-md-2'>
														<div class='block1'><label> Дата поставки</label></div>
													</div> 
													<div class='col-md-10'>
														<div class='block2'>
															<input type='date' id = 'ch".$kol."20'>
														</div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> Стоимость</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."21' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> Кол-во</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."22' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> Кол-во NEXT </label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."23' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> Размер </label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."24' size='10' value = '0*0'></div>
													</div>
												</div>";
										
											echo "</div>";
								$lastkol = $kol;
							echo  "<hr>";		
							}ELSE{
								$name_operrra = "";
									// будет ли один виден тогда выводить
									$query = "select OPERATION_NAME from operations where OPERATION_NAME = '".$PRODUCT_TEMPLATE_TYPE[1]."' and par is not null;";
									IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
										$query = "select OPERATION_NAME from operations where id = '".substr($PRODUCT_TEMPLATE_TYPE[1],1)."' and par is not null;";
									}
									$result = mysql_query($query) or die("Query failed2");
									WHILE ($row = mysql_fetch_row($result)) { 
									$name_operrra = $row [0];
									}
									
									IF (COUNT(explode(",", $PRODUCT_TEMPLATE_TYPE[5])) > 1) {
											
										echo "<b><i>".$name_operrra.":</i></b>";
									}
								
								IF($PRODUCT_TEMPLATE_TYPE[1] != ''){
									$query = "select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where OPERATION_NAME = '".$PRODUCT_TEMPLATE_TYPE[1]."' and par is not null;";
									IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
										$query = "select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where id = '".substr($PRODUCT_TEMPLATE_TYPE[1],1)."' and par is not null;";
									}
									$result = mysql_query($query) or die("Query failed2");
									WHILE ($row = mysql_fetch_row($result)) { 
									$name_oper = $row[4];
									if ($row[3] == ''){
									$prol = 0;
									}else{
										$prol = $row[3];
									}
						
										echo  "<input  type = 'hidden'  id = 'ch".++$kol."1' size='10' value = '$row[0]#$row[2]^$prol'>";
													
									}
								}
									echo "<input  type = 'hidden'  id = 'cch".$kol."1' size='10' value = ' '>";
								echo  "<input  type = 'hidden'  id = 'ch".$kol."2' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[3]."'>";
								IF($PRODUCT_TEMPLATE_TYPE[5] != ''){
									
									IF (COUNT(explode(",", $PRODUCT_TEMPLATE_TYPE[5])) > 1) {
										 echo  "<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> 
															<label id = 'cch".$kol."8'>Оборудование: </label>
														</div>
													</div>";
											echo  "<div class='col-md-10'>
													<div class='block2'>
														<select  id = 'ch".$kol."8' name = '' > <option value='0' title='0'>Выберите</option>"; 
										$ID_EQ = explode(",", $PRODUCT_TEMPLATE_TYPE[5]);
										FOR ($g = 0; $g < count($ID_EQ); $g++){
											$query = "select id, eq_name, l_use from equipment where id = ".$ID_EQ[$g]." and  eq_name is not null;";
											$result = mysql_query($query) or die($query);
											WHILE ($row = mysql_fetch_row($result)) { 
													if($row[2] == '1'){
													$eq_id_entr = $row[0];
													echo  "<option value='$row[0]' title='0' selected>$row[1]</option>"; 
												} else {
													echo  "<option value='$row[0]' title='0' >$row[1]</option>"; 
												}
												
											}
										}
										echo  "</select>
													</div>
													</div>
												</div>";
									}
									ELSE{
										$query = "select id, eq_name from equipment where id = ".$PRODUCT_TEMPLATE_TYPE[5]." and  eq_name is not null;";
										$result = mysql_query($query) or die($query);	
										WHILE ($row = mysql_fetch_row($result)) { 
											echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '$row[0]'>";
											$eq_id_entr = $row[0];
										}
									}
									}ELSE {
											echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '0'>";
										}
								IF($PRODUCT_TEMPLATE_TYPE[8] != ''){
									$query = "select ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE, ma.m_tol from  material_attr ma where ma.ID = '".$PRODUCT_TEMPLATE_TYPE[8]."' and ma.M_NAME is not null;";
									$result = mysql_query($query) or die("Query failed4");	
									WHILE ($row = mysql_fetch_row($result)) { 
										echo  "<input  type = 'hidden'  id = 'ch".$kol."3' size='10' value = '$row[0]@$row[2]!$row[3]^$row[4]'>";
									}
								}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".$kol."3' size='10' value = '0@0!^0'>";
									}
								echo 	"<input  type = 'hidden'  id = 'ch".$kol."4' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[10]."'>";
								echo 	"<input  type = 'hidden'  id = 'ch".$kol."5' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[12]."'>";
								echo 	"<input  type = 'hidden'  id = 'ch".$kol."6' size='10' value = '".$PRODUCT_TEMPLATE_TYPE[14]."'>";
								
									IF($PRODUCT_TEMPLATE_TYPE[16] != ''){
									$query = "select ID, STAMP_TYPE , STAMP_NAME, STAMP_KOL,STAMP_SIZE,STAMP_PRICE,STAMP_NEW from stamps WHERE  ID = '".$PRODUCT_TEMPLATE_TYPE[16]."' AND STAMP_NAME  <> '' ORDER BY STAMP_TYPE";
									$result = mysql_query($query) or die($query);	
									WHILE ($row = mysql_fetch_row($result)) { 
										echo  "<input  type = 'hidden'  id = 'ch".$kol."7' size='10' value = '$row[0]?$row[3]^$row[4]/$row[6]`$row[5]'>";
									}
								}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".$kol."7' size='10' value = '0?0^0*0/1`0'>";
									}
										
								switch ($name_oper)
								{
									case 'Подрезка материала':		echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '1'>";   break;
									case 'Широкоформатная печать':		echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '10'>";   break;
									case 'Перфорация под. мет. пружину':	echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '11'>"; break;
									case 'Сшивка на скобу':			echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '12'>";  break;
									case 'Сшивка на PUR-клей':			echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '13'>";  break;
									case 'Установка мет. пружины':		echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '14'>";   break;

									default:   	echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '0'>";   break; break;
									
								}
							
							
							/*IF ($name_oper == 'Подрезка материала'){
							
										echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '1'>";
									} else {
										IF ($name_oper == 'Перфорация под. мет. пружину'){
													echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '11'>";
													} else {
														IF ($name_oper == 'Сшивка на скобу'){
													echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '12'>";
													} else {
													IF ($name_oper == 'Сшивка на PUR-клей'){
													echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '13'>";
													} else {
													echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '0'>";
												}
												}
												}
										
									}*/
									
									echo 	"<div id = 'ch".$kol."19' style=".'"display:none;"'.">";
										echo 	"<div class='row'>
													<div class='col-md-2'>
														<div class='block1'><label> Дата поставки</label></div>
													</div> 
													<div class='col-md-10'>
														<div class='block2'>
															<input type='date' id = 'ch".$kol."20'>
														</div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> Стоимость</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."21' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> Кол-во</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."22' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> Кол-во NEXT </label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."23' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> Размер </label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."24' size='10' value = '0*0'></div>
													</div>
												</div>";
										
											echo "</div>";
																$lastkol = $kol;	
											IF (COUNT(explode(",", $PRODUCT_TEMPLATE_TYPE[5])) > 1) {
									echo  "<hr>";
										}				
							} 
						
							
						}
					}
					echo "</h6>";
					$rest = substr($summOper, 0, -1);
					$prod_siz = explode("*", $PRODUCT_SIZE);
					$prod_size_1 = $prod_siz[0];
					$prod_size_2 = $prod_siz[1];
					if ($prod_size_1 == '' || $prod_size_2 == ''){
						$prod_size_1 = 0;
						$prod_size_2 = 0;
					}
					echo  "<div class='row'>
							<div class='col-md-2'>
								<div class='block1'>
									<label>Кол-во : </label> 
								</div>
							</div>
							<div class='col-md-1'>
								<div class='block2'>
									<input  type = 'hidden'  id = 'par1' name = 'kol1' size='10'  value = '".$PRODUCT_TEMP_REVIEW_KOL."'>
									<input class = '_kol' name = 'kol1' type = 'text' size='5'  value = '".$PRODUCT_TEMP_REVIEW_KOL."' id = 'kol1'  disabled> </div>
							</div>
							<div class='col-md-1'>
								<div class='block3'>
									<input class = '_kol'  type =  'text' size='5' value = '1' id = 'kol2'> 
										</div>
							</div>
							<div class='col-md-1'>
								<div class='block4'>
									<input class = '_kol'  type =   'text' size='5' value = '10' id = 'kol3'>
								</div>
							</div>
							<div class='col-md-1'>
								<div class='block5'>
									<input class = '_kol' type =  'text' size='5' value = '100' id = 'kol4'> 
								</div>
							</div>
							<div class='col-md-1'>
								<div class='block6'>
									<input class = '_kol' type =  'text' size='5' value = '300' id = 'kol5'>
								</div>
							</div>
							<div class='col-md-1'>
								<div class='block7'>
									<input class = '_kol' type =  'text' size='5' value = '500' id = 'kol6'>
								</div>
							</div>
							<div class='col-md-1'>
								<div class='block8'>
									<input class = '_kol' type =  'text' size='5' value = '1000' id = 'kol7'>
								</div> 
							</div>
						";
					echo 	"<div class='col-md-1'>
								<div class='block9'><input  type = 'hidden'  id = 'kol' size='10' value = '".$kol."'><input  type = 'hidden'  id = 'flag' size='10' value = '0'><input  type = 'hidden'  id = 'id_orderProd' name = 'id_orderProd' size='10' value = '".$id_orderProd."'>";
					echo 	'<input type="button" class="btn btn-default" onclick="amCal';
					echo 	"($kol,";
					echo  " $summMat,$id_prod, $id_ord,$summMR,$prod_size_1,$prod_size_2,$PRODUCT_SH,$PRODUCT_SKOBA,$clients_nadbavka,$kurs,$nds,$activ)";
					echo  '" value="Расчитать"/>	</div>
							</div>	</div>					<div id = "raschet">					</div>';
					echo  "		<input  type = 'hidden'  id = 'id_del' name = 'id_del' value = ''>";
					echo  "</form>";
	
					}
				
                ?>

   
				<div id = "raschet2">
					<!-- <a onclick = 'excel()'>
        <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span>Получить Excel</button></a> -->
	<a href='#' onclick='downloadCSV({ filename: "stock-data.csv" });'>Download CSV</a>
				</div>
        </div>
    </div>
	
	<?php
		
		echo '	
		<div id="myModal3" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
						<h4 class="modal-title">Изменение операции дизайн</h4>
					</div>
					<div class="modal-body">';
					echo '<div class="row">
										<div class="col-md-6"> 
											<label>Наименование</label>
											</div>
										<div class="col-md-3"> 
											<label>Время</label>
										</div>
										<div class="col-md-3">
										<label>Стоимость</label>
										</div>
									</div>';
					$total_time = 0;
					$total_sum = 0;
					$query="select  * from DIZ_OPER";
					$result = mysql_query($query) or die($query);
					while ($row = mysql_fetch_row($result)) { 
					$sums = 0;
						
						if ($row[3] == 1){
							$total_time = $total_time  + $row[2];
							echo ' <div class="row">
										<div class="col-md-6"> 
											<div class="checkbox">
												<label><input type="checkbox" id = d'.$row[0].'  value=" '.$row[2].'"  name="diz" checked onclick="fun_view_diz('.$row[0].')">'.$row[1].'</label>
											</div>
										</div>
										<div id=elemm'.$row[0].'  style="display:block;">
											<div class="col-md-3"> 
												<label id=elemm'.$row[0].'1>'.$row[2].'</label>
											</div>
											<div class="col-md-3">';
												$sums = ($row[2]/60) * $price_diz * $kurs;
												$total_sum =  $total_sum + $sums;
												echo  '<label id=elemm'.$row[0].'2>'.round($sums, 2).'</label>
											</div>
										</div>
									</div>';
						} else {
							echo ' <div class="row">
										<div class="col-md-6"> 
											<div class="checkbox">
												<label><input type="checkbox" id = d'.$row[0].'  value=" '.$row[2].'"  name="diz" onclick="fun_view_diz('.$row[0].')">'.$row[1].'</label>
											</div>
										</div>
										<div id=elemm'.$row[0].'  style="display:none;">
											<div class="col-md-3"> 
												<label id=elemm'.$row[0].'1>'.$row[2].'</label>
											</div>
											<div class="col-md-3">';
												$sums = ($row[2]/60) * $price_diz * $kurs;
												echo  '<label id=elemm'.$row[0].'2>'.round($sums, 2).'</label>
											</div>
										</div>
										
									</div>';
						}
						
					}
					echo '<div class="row">
							<div class="col-md-6"> 
								<label>Итого:</label>
							</div>
							<div class="col-md-3"> 
								<label id = total_time_diz>'.$total_time.'</label>
								</div>
								<div class="col-md-3">
									<label id = total_sum_diz>'.round($total_sum, 2).'</label>
								</div>
							</div>	';					
				
							
		echo '	</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
						<button type="button" class="btn btn-primary"  data-dismiss="modal"  onclick="diz_fun('.$price_diz.','.$kurs.') ">Добавить</button>
					</div>
				</div>
			</div>
		</div>';

	echo '	
		<div id="myModal4" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="true" style="display: none;">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
						<h4 class="modal-title">Изменение операции дизайн</h4>
					</div>
					<div class="modal-body">';
				echo '<div class="row">
										<div class="col-md-6"> 
											<label> Наименование</label>
											</div>
										<div class="col-md-3"> 
											<label>Время</label>
										</div>
										<div class="col-md-3">
										<label>Стоимость</label>
										</div>
									</div>';
			
					$total_time = 0;
					$total_sum = 0;
					$query="select  * from PR_OPER";
					$result = mysql_query($query) or die("Query failed");
					while ($row = mysql_fetch_row($result)) { 
						$sums = 0;
					if ($row[3] == 1){
							$total_time = $total_time  + $row[2];
							echo '<div class="row">
										<div class="col-md-6"> 
											<div class="checkbox">
											<label><input type="checkbox" id = p'.$row[0].'  value=" '.$row[2].'"  name="pre" checked onclick="fun_view_pre('.$row[0].')">'.$row[1].'</label>
											</div>
										</div>
										<div id=elem'.$row[0].'  style="display:block;">
											<div class="col-md-3"> 
												<label id=elem'.$row[0].'1>'.$row[2].'</label>
											</div>
											<div class="col-md-3">';
											$sums = ($row[2]/60) * $price_diz * $kurs;
											$total_sum =  $total_sum + $sums;
											echo  '<label id=elem'.$row[0].'2>'.round($sums, 2).'</label>
											</div>
										</div>
									</div>';
						} else {
							echo '<div class="row">
										<div class="col-md-6"> 
											<div class="checkbox">
												<label><input type="checkbox" id = p'.$row[0].'  value=" '.$row[2].'"  name="pre" onclick="fun_view_pre('.$row[0].')">'.$row[1].'</label>
											</div>
										</div>
										<div id=elem'.$row[0].'  style="display:none;">
											<div class="col-md-3"> 
												<label id=elem'.$row[0].'1>'.$row[2].'</label>
											</div>
											<div class="col-md-3">';
											$sums = ($row[2]/60) * $price_diz * $kurs;
											echo  '<label id=elem'.$row[0].'2>'.round($sums, 2).'</label>
											</div>
										</div>
									</div>';
						}
						
					}
					echo '<div class="row">
							<div class="col-md-6"> 
								<label>Итого:</label>
							</div>
							<div class="col-md-3"> 
								<label id = total_time_pre>'.$total_time.'</label>
								</div>
								<div class="col-md-3">
									<label id = total_sum_pre>'.round($total_sum, 2).'</label>
								</div>
							</div>	';			
					mysql_close($connection);	
	echo '	</div>
					<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
						<button type="button" class="btn btn-primary"  data-dismiss="modal"   onclick="pre_fun('.$price_diz.','.$kurs.') ">Добавить</button>	
					</div>
				</div>
			</div>
		</div>';
	?>
	<script>
		window.onload = function () {
			document.getElementById('press_sum').value = document.getElementById('total_sum_pre').innerHTML;
		}
	</script>
	<script>
	
	function diz_fun(price_diz, kurs) {
		var str = '', str1 = '', sum = 0;
	var nodeList = document.getElementsByName('diz');
	var array = Array.prototype.slice.call(nodeList);
		for (var i = 0; i < array.length; i++) {
			if (array[i].checked) {
				str = str + array[i].id.substring(1,array[i].id.length) + ',';
				
				sum = Number(sum) + Number(array[i].value);
		
			}
		}
		document.getElementById('view_diz').value = str.substring(0, str.length - 1);
		summ = (sum/60) * price_diz * kurs
		document.getElementById('dizz_sum').value = summ.toFixed(2);

	}
	function pre_fun(price_diz, kurs) {
	var str = '', str1 = '', sum = 0;
	var nodeList = document.getElementsByName('pre');
	var array = Array.prototype.slice.call(nodeList);
		for (var i = 0; i < array.length; i++) {
			if (array[i].checked) {
				str = str + array[i].id.substring(1,array[i].id.length) + ',';
				sum = Number(sum) + Number(array[i].value);
		
			}
		}
		document.getElementById('view_press').value = str.substring(0, str.length - 1);
		summ = (sum/60) * price_diz * kurs
		document.getElementById('press_sum').value = summ.toFixed(2);
	}
	
	function fun_view_pre(id) {
		var str = 'elem'+id;
		var str1 = 'p'+id;
		var str3 = 'elem'+id+'1';
		var str4 =  'elem'+id+'2';
		var time, sum, time1 , sum1,total_time, total_sum;
		time1 =  document.getElementById(str3).innerHTML;
		sum1 =  document.getElementById(str4).innerHTML;
		time =  document.getElementById('total_time_pre').innerHTML;
		sum =  document.getElementById('total_sum_pre').innerHTML;
		
		time1 =  Number(time1);
		sum1 = Number(sum1);
		time =  Number(time);
		sum =  Number(sum);
		
		if (document.getElementById(str1).checked){
			document.getElementById(str).style.display = 'block';
			total_time = time + time1
			total_sum = sum + sum1
			document.getElementById('total_time_pre').innerHTML = total_time ;
			document.getElementById('total_sum_pre').innerHTML =  total_sum.toFixed(2) ;
			
		}else {
			document.getElementById(str).style.display = 'none';
			total_time = time - time1
			total_sum = sum - sum1
			document.getElementById('total_time_pre').innerHTML = total_time ;
			document.getElementById('total_sum_pre').innerHTML =  total_sum.toFixed(2) ;;
		}
	}
	function fun_view_diz(id) {
		var str = 'elemm'+id;
		var str1 = 'd'+id;
		var str3 = 'elemm'+id+'1';
		var str4 =  'elemm'+id+'2';
		var time, sum, time1 , sum1,total_time, total_sum;
		time1 =  document.getElementById(str3).innerHTML;
		sum1 =  document.getElementById(str4).innerHTML;
		time =  document.getElementById('total_time_diz').innerHTML;
		sum =  document.getElementById('total_sum_diz').innerHTML;
		
		time1 =  Number(time1);
		sum1 = Number(sum1);
		time =  Number(time);
		sum =  Number(sum);
		
		if (document.getElementById(str1).checked){
			document.getElementById(str).style.display = 'block';
			total_time = time + time1
			total_sum = sum + sum1
			document.getElementById('total_time_diz').innerHTML = total_time ;
			document.getElementById('total_sum_diz').innerHTML =  total_sum.toFixed(2) ;
		}else {
			document.getElementById(str).style.display = 'none';
			total_time = time - time1
			total_sum = sum - sum1
			document.getElementById('total_time_diz').innerHTML = total_time ;
			document.getElementById('total_sum_diz').innerHTML =  total_sum.toFixed(2);
		}
		
	}
	</script>
	 <!-- /#wrapper -->
   
	<!-- jQuery-->
    <script src="../../vendor/jquery/jquery.min.js"></script> 
	 <script src="../../js/funJs.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
    
    <!-- DataTables JavaScript -->
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->

		<script type="text/javascript" src="http://yandex.st/jquery/1.7.2/jquery.min.js"></script>
		<script type="text/javascript">
			<!--

			function validate_form ( )
			{
				valid = true;

					if ( document.getElementById('kol1').value == "0" )
					{
							alert ( "Выберите тираж!" );
							valid = false;
					}

					return valid;
			}

			//-->
			</script>
	<script type="text/javascript">
		$('._kol').live('click', function() {
			var kol = $('#flag').attr("value");
			if(kol != '0'){
				var str = $(this).attr("id");
				var val1 = $(this).attr("value");
				var sum1 = $('#price' + str[str.length-1] ).attr("value");
				var sum2 = $('#price_' + str[str.length-1] ).attr("value");
			
				$('#kol1').val(val1);
				$('#price1').val(sum1);
				$('#price_1').val(sum2);
				
				$('#par1').val(val1);
				$('#par2').val(sum1);
				$('#par3').val(sum2);
			}
	});
	</script>
	<script>
	function downloadCSV(args) {
        var data, filename, link;
		var ko1  = document.getElementById('kol1').value;
		var ko2  = document.getElementById('kol2').value;
		var ko3  = document.getElementById('kol3').value;
		var ko4  = document.getElementById('kol4').value;
		var ko5  = document.getElementById('kol5').value;
		var ko6  = document.getElementById('kol6').value;
		var ko7  = document.getElementById('kol7').value;
						
		var price1  = document.getElementById('price1').value;
		var price2  = document.getElementById('price2').value;
		var price3  = document.getElementById('price3').value;
		var price4  = document.getElementById('price4').value;
		var price5  = document.getElementById('price5').value;
		var price6  = document.getElementById('price6').value;
		var price7  = document.getElementById('price7').value;
					
		var kol = document.getElementById('kol').value; 
		var date_ = new Date();
		var pname = document.getElementById('pname').innerHTML 
		var csv = pname + '\n\n';
						
		/*for (var i = 1; i <= kol; i++){
			for (var y = 1; y <= 7; y++){
			//	if (document.getElementById('ch'+i+y).type != 'hidden')
					//{
						if (document.getElementById('ch'+i+y).type == 'text'){
							csv = csv + document.getElementById('cch'+i+y).innerHTML + ";" + document.getElementById('ch'+i+y).value + '\n';							
						}else {
							var sel = document.getElementById('ch'+i+y); // Получаем наш список
							var txt = sel.options[sel.selectedIndex].text; 
							var val = sel.options[sel.selectedIndex].value; 
							csv = csv + document.getElementById('cch'+i+y).innerHTML + "," + txt + ';' + val +'\n';	
						}
					}
				}
			}*/
			
			for (var i = 1 ; i <=kol; i++ ){
				var str1 = 'ch'+i + '1'; //операции
				var str2 = 'ch'+i + '2'; //кол-во операций
				var str3 = 'ch'+i + '3'; //материал
				var str4 = 'ch'+i + '4'; //кол-во материала
				var str5 = 'ch'+i + '5'; //кол-во страниц
				var str6 = 'ch'+i + '6'; //размер
				var str1_value = document.getElementById(str1).value;
				var str2_value = document.getElementById(str2).value;
				var str3_value = document.getElementById(str3).value;
				var str4_value = document.getElementById(str4).value;
				var str5_value = document.getElementById(str5).value;
				var str6_value = document.getElementById(str6).value;
			
				csv = csv + "операции " + str1_value + " ;кол-во операций " + str2_value + " ;материал " + str3_value + " ;кол-во материала " + str4_value + " ;кол-во страниц " + str5_value + " ;размер " + str6_value + '\n';
			
			}
						
			csv = csv + '\nТираж;' + ko1 +  ';' + ko2 +  ';' + ko3 +  ';' + ko4 +  ';' + ko5 +  ';' + ko6 +  ';' + ko7 +  '\n';
			csv = csv + 'Стоимость за ед.:' + String(price1) +  ';' + String(price2) +  ';' + String(price3) +  ';' + String(price4) +  ';' + String(price5) +  ';' + String(price6) +  ';' + String(price7) +  '\n';
						
      
      

		if (csv == null) return;

        filename = args.filename || 'export.csv';

        if (!csv.match(/^data:text\/csv/i)) {
            csv = 'data:text/csv;charset=utf-8,\uFEFF' + csv;
				  
        }

        data = encodeURI(csv);

        link = document.createElement('a');
        link.setAttribute('href',data);
        link.setAttribute('download', filename);
        link.click();
		
		
    }

	
	function fun_() {
    var rad=document.getElementsByName('optionsRadiosInline');
    for (var i=0;i<rad.length; i++) {
         var input = rad[i];
		 if(rad[i].checked){

		if(input.value == '1'){
			document.getElementById('diz1' ).style.display = 'block';
			document.getElementById('diz2' ).style.display = 'none';
		} else if (input.value == '2'){
			document.getElementById('diz1' ).style.display = 'none';
			document.getElementById('diz2' ).style.display = 'block';
			}
		}
    }
		}	
		
	function funs_() {
    var rad=document.getElementsByName('optionsRadiosInline_');
    for (var i=0;i<rad.length; i++) {
         var input = rad[i];
		 if(rad[i].checked){

		 
		var kursss = document.getElementById('kursss').value;
		var dizzz  = document.getElementById('dizzz').value;
		 
		 
		var sum  = (Number(input.value) *  Number(dizzz) * Number(kursss))/60 ;
		sum = sum.toFixed(2);
		document.getElementById('diz_sum').value = String(sum);	
			
		}
    }
}	
		
		</script>
	<script>
	function maket(id){
		if(id == '1'){
			document.getElementById('op1' ).style.display = 'block';
			document.getElementById('op2' ).style.display = 'none';
			document.getElementById('op3' ).style.display = 'none';
		}
		if(id == '2'){
			document.getElementById('op2' ).style.display = 'block';
			document.getElementById('op1' ).style.display = 'none';
			document.getElementById('op3' ).style.display = 'none';
		}
		if(id == '3'){
			document.getElementById('op2' ).style.display = 'none';
			document.getElementById('op1' ).style.display = 'none';
			document.getElementById('op3' ).style.display = 'block';
		}
	}
	</script>
<script>
function rts(id1, id2,id3,koa){
	var id_eq = document.getElementById(id1).value;
	var kol = document.getElementById(id2).length;
	var i, y;
	 i = 0;
	
	for ( y = 0; y < kol; y ++){
		
		if (document.getElementById(id2).options[y].label == id_eq){
			document.getElementById(id2).options[y].style.display = 'block';
			i++;
		}else {
			document.getElementById(id2).options[y].style.display = 'none';
		}
	}
		  $('#'+id2).val('')
		    $('#'+id3).val('0@0!^0')
	if (i == 1 ){
		//скрыть установать
		document.getElementById('c'+id2).style.display = 'none';
		document.getElementById(id2).style.display = 'none';
		for ( y = 0; y < kol; y ++){
			if (document.getElementById(id2).options[y].style.display == 'block'){
				document.getElementById(id2).options[y].selected = true;
				
			}
		}	
	} else {
		document.getElementById('c'+id2).style.display = 'block';
		document.getElementById(id2).style.display = 'block';
		
	}
	
	
	 kol = document.getElementById(id3).length;
	
	for ( y = 0; y < kol; y ++){
		
		if (document.getElementById(id3).options[y].label == id_eq){
			document.getElementById(id3).options[y].style.display = 'block';
		}else {
			document.getElementById(id3).options[y].style.display = 'none';
		}
	}
	
	
	
 kol = document.getElementById(id1).length;
	
	for ( y = 0; y < kol; y ++){
		
		if (document.getElementById(id1).options[y].selected == true){

			if (document.getElementById(id1).options[y].title != '0'){
				document.getElementById('ch' + koa + '19').style.display = 'block';
				document.getElementById('ch' + koa + '20').value = "";
				document.getElementById('ch' + koa + '21').value = "0";
				document.getElementById('ch' + koa + '22').value = "0";
				document.getElementById('ch' + koa + '23').value = "0";
				document.getElementById('ch' + koa + '24').value = "0*0";

				}else {
					document.getElementById('ch' + koa + '19').style.display = 'none';
						document.getElementById('ch' + koa + '20').value = "";
				document.getElementById('ch' + koa + '21').value = "0";
				document.getElementById('ch' + koa + '22').value = "0";
				document.getElementById('ch' + koa + '23').value = "0";
				document.getElementById('ch' + koa + '24').value = "0*0";

				}
		}
	}
	
	
	
}
</script>


<script>
var last_id;
function dd(ida){	
	$.ajax({
	type: "POST",
   url: "add_event.php",
   data: {
		dizz_sum: document.getElementById("dizz_sum").value,
		press_sum: document.getElementById("press_sum").value,
      psize: document.getElementById("psize").value,
      price1: document.getElementById("par2").value,
      price_1: document.getElementById("par3").value,
		orderProd: document.getElementById("orderProd").value,
		orderAcct: document.getElementById("orderAcct").value,       
		kol1: document.getElementById("par1").value,
		Template: document.getElementById("Template").value,
		Template1: document.getElementById("Template1").value,
		p_names: document.getElementById("p_names").value,
		view_diz: document.getElementById("view_diz").value,
		view_press: document.getElementById("view_press").value					
      },success: function(data) {
			last_id = data;
			}
	});
			
			window.setTimeout(dd1,3000);
			
			}


</script>
 <script>
  function dd1(){
	  	  
		$.get(
				"_tacks.php",
				{
					id_order: document.getElementById("orderAcct").value,
					flag: "0"
				}
			);
		window.setTimeout(dd2,3000);	
	
		
  }

</script>
 <script>
  function dd2(){
	  	  
	$.ajax({
			type: "POST",
			url: 'time_eq.php'
		});
		window.setTimeout(dd3,3000);	
	
		
  }

</script>
 <script>
  function dd3(){
	  	  
	$.ajax({
			type: "GET",
			  url: 'temi_ee.php',
			   data: {
 					num_prod: document.getElementById("orderProd").value,
					Numder_order: document.getElementById("orderAcct").value,
					acct_or : last_id
				},  success:function (data) {//возвращаемый результат от сервера
			
				document.getElementById("date_time").innerHTML =  data;
					}
			});	
			
			document.getElementById("id_del").value = last_id;
				window.setTimeout(dd6,5000);	

	
		
  }

</script>
 <script>
   function dd6(){
			if (document.getElementById("id_del").value != ""){
			$.ajax({
			type: "GET",
			  url: 'del_event.php',
			   data: {
 					num_prod: document.getElementById("orderProd").value,
					Numder_order: document.getElementById("orderAcct").value,
					acct_or : document.getElementById("id_del").value
				}
			});
		
	}
  }		
</script>		
	<script>
	function img(id1, id2){
		//alert(id1 + " " + id2)
		val = $("#" + id1 + " option:selected").val();
		val1 = val.split("?");
		id = val1[0]; 
		
		
	$.ajax({
			type: "GET",
			  url: 'img_stamp.php',
			   data: {
 					id: id
				},  success:function (data) {//возвращаемый результат от сервера
					if (data !=''){
						document.getElementById(id2).src = "image\\stamp\\" + data;
					document.getElementById(id2).style.display = 'block';
				
					}
					else {document.getElementById(id2).style.display = 'none';
				}}
			});	
		
		
		
	}
	</script>

	<script>
	function ref(kol, name){
			old = document.getElementById('ch' + kol + '7').innerHTML;
	$.ajax({
			type: "GET",
			  url: 'list_new_stamp.php',
			   data: {
 					name: name
				},  success:function (data) {//возвращаемый результат от сервера
				document.getElementById('ch' + kol + '7').innerHTML = old  + data;
				}
			});	
	
		
		
	}
	
	</script>
	
	<script>
	window.onload=function(){
	var temp = '<? echo $PRODUCT_TEMP_REVIEW ?>';
	
	arr = temp.split('{')
	var kol_oper = '<? echo $kol?>';
	var kol_oper1 = 0;
	for (var i = 0; i < arr.length; i++) {
		kol_oper1++;
		arr1 = arr[i].split('(^)')
		/*Оборуд */
		document.getElementById('ch'+kol_oper1 + '8').value = arr1[0] ;
		document.getElementById('ch'+kol_oper1 + '1').value = arr1[1];
		document.getElementById('ch'+kol_oper1 + '2').value = arr1[2];
		document.getElementById('ch'+kol_oper1 + '3').value = arr1[3];
		document.getElementById('ch'+kol_oper1 + '4').value = arr1[4];
		document.getElementById('ch'+kol_oper1 + '5').value = arr1[5];
		document.getElementById('ch'+kol_oper1 + '6').value = arr1[6];
		document.getElementById('ch'+kol_oper1 + '7').value = arr1[7];
		/*офсет*/
		//arr1[8]
		
	}

	
	
}
	</script>
</body>
</html>