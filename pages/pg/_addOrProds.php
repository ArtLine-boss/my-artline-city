		 <?php
				$orderProd =$_GET['orderProd'];
				$orderAcct = $_GET['orderAcct'];
				IF($orderProd  != ''){
					INCLUDE '../db.php';
					$query = "select c.nadbavka from clients c, (select * from orders o where o.NUMBER = ".$orderAcct.") o where c.id = o.client_id";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) { 
						$clients_nadbavka = $row[0];
					}
					IF ($clients_nadbavka ==''){
						$clients_nadbavka = 0;
					} ELSE {
						$clients_nadbavka = str_replace(',', '.', $clients_nadbavka);
					}
					
					$query = "select val from settings s where  s.id = 11";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) { 
						$firm_nacenka = $row[0];
					}
					IF ($firm_nacenka ==''){
						$firm_nacenka = 0;
					} ELSE {
						$firm_nacenka = str_replace(',', '.', $firm_nacenka);
					}
					
					$query = "select val from settings s where  s.id = 2";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) { 
						$kurs = $row[0];
					}
					IF ($kurs ==''){
						$kurs = 0;
					} ELSE {
						$kurs = str_replace(',', '.', $kurs);
					}
					
					$query = "select val from settings s where  s.id = 4";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) { 
						$nds = $row[0];
					}
					IF ($nds ==''){
						$nds = 0;
					} ELSE {
						$nds = str_replace(',', '.', $nds);
					}
					
					$query = "select val from settings s where s.id = 3";
					$result = mysql_query($query) or die("Query failed1");
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
					$query = "select PRODUCT_NAME, PRODUCT_TEMPLATE, PRODUCT_SIZE,PRODUCT_SH,PRODUCT_SKOBA, kat_1,kat_2,prod_sh from product where id = ".$orderProd;
					$result = mysql_query($query) or die("Query failed1");
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
				echo  "<input type = 'hidden' id='price_diz' value = '".$price_diz."'>";
				echo  "<input type = 'hidden' id='kurs' value = '".$kurs."'>";
						echo  "<form  class='form-signin' method='post' action='_addOrProdsf.php' enctype='multipart/form-data' id = 'forms'>	";
						echo  " <h3 class='text-muted' id = 'pname'  >".$PRODUCT_NAME."</h3>";
						echo  "<div class='row'>
									<div class='col-md-4'>
										<div class='block1'> 
											<label>Наименование:</label>
										</div>
									</div>  
									<div class='col-md-5'>
										<div class='block2'>
											<input  type = 'text'  id = 'p_names' size='40' name = 'p_names' value = '".$PRODUCT_NAME."' >
										</div>
									</div>
									<div class='col-md-3'>
										<div class='block2'>
											<button type='button' class='btn btn-xs'    onclick='dd(1)'>Дата готовности</button>
										</div>
									</div>
								</div>";
						echo  "<div class='row'>
								<div class='col-md-4'>
									<div class='block1'> 
										<label>Размер в готовом виде, мм:</label>
									</div>
								</div>  
								<div class='col-md-5'>
									<div class='block2'>
										<input  type = 'text'  id = 'psize' size='10' name = 'psize' value = '".$PRODUCT_SIZE."' >
									</div>
								</div>
								<div class='col-md-3'>
									<div class='block2'>
				
										<div id = 'date_time'></div>
									</div>
								</div>
							</div>";
						echo  "<div class='row'>
								<div class='col-md-4'> 
									<div class='block1'> 
										<label>Добавить поля на подрезку:</label>
									</div>
								</div>  
								<div class='col-md-4'>
									<div class='block2'>
										<input  type = 'checkbox'  id = 'chek' checked>
									</div>
								</div>
								
							</div>";
								echo  "<div class='row'>
								<div class='col-md-4'> 
									<div class='block1'> 
										<label>Учитывать непечатное поле:</label>
									</div>
								</div>  
								<div class='col-md-4'>
									<div class='block2'>
										<input  type = 'checkbox'  id = 'chek1' checked>
									</div>
								</div>
								
							</div>";
							echo  "<div class='row'>
								<div class='col-md-4'> 
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
											<label>Макет:&nbsp;</label>
                                 <div class='checkbox'>
												<label>
													<input type='checkbox' name='optionsRadiosInline' id='optionsRadiosInline1' value='option1' onclick='maket(1)'>Нужен макет
												</label>
													<div id = 'op1'  style='display:none;'>
													<div class='row'>
												
														<div class='col-md-2'>
															<div class='block1'>
															<button type='button' class='btn btn-default btn-sm'  onclick='addmyModal3()'>Выбор дизайн</button>	
															</div>
														</div>
														<div class='col-md-3'>
															<div class='block2'>
															<label class='btn btn-default btn-file btn-sm'>
																	Выберете файлы...<input name='file[]' type='file' multiple='true' style='display: none;' id = 'file'/>	
																</label>	
																
															</div>
														</div>
												
													</div>
										</div>
								
                                 </div>
                                 <div class='checkbox'>
												<label>
													<input type='checkbox' name='optionsRadiosInline' id='optionsRadiosInline2' value='option2' onclick='maket(2)'>Макет готов
												</label>
												<div id = 'op2'  style='display:none;'><div class='row'>
										
												<div class='col-md-2'>
													<div class='block1'>
													<button type='button' class='btn btn-default btn-sm' onclick='addmyModal4()'>Выбор препресс</button>	
													</div>
												</div>
												<div class='col-md-3'>
													<div class='block2'>
														<label class='btn btn-default btn-file btn-sm'>
															Выберете файлы...<input name='file1[]' type='file' multiple='true' style='display: none;' id = 'file1'/>	
														</label>									
													</div>
												</div>";	
												
												$query="select * from PR_OPER";
											$result = mysql_query($query) or die($query);
											$iddd = '';
											while ($row = mysql_fetch_row($result)) { 
											if ($row[3] == 1){
												$iddd = $iddd.$row[0].",";
											}}
											$iddd  = substr($iddd, 0, -1);
											echo "					</div>
											</div>";		
											echo "</div>
                                 <div class='checkbox'>
												<label>
													<input type='checkbox' name='optionsRadiosInline' id='optionsRadiosInline3' value='option3' onclick='maket(3)' >Макет разложен на лист
													<div id = 'op3'  style='display:none;'>
														<div class='row'>
															<div class='col-md-12'>
																	<div class='block2'>
																		<label class='btn btn-default btn-file btn-sm'>
																			Выберете файлы...<input name='file2[]' type='file' multiple='true' style='display: none;' id = 'file2'/>	
																		</label>									
																	</div>
																</div>
														</div>
										</div>	
											</div>
                              </div>
									</div>
								</div>";		

							        // <div class="form-group">
                                            // <label>Checkboxes</label>
                                            // <div class="checkbox">
                                                // <label>
                                                    // <input type="checkbox" value="">Checkbox 1
                                                // </label>
                                            // </div>
                                            // <div class="checkbox">
                                                // <label>
                                                    // <input type="checkbox" value="">Checkbox 2
                                                // </label>
                                            // </div>
                                            // <div class="checkbox">
                                                // <label>
                                                    // <input type="checkbox" value="">Checkbox 3
                                                // </label>
                                            // </div>
                                        // </div>
							
					
					
							
					
						
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
							switch ($PRODUCT_SH)
								{
									case 1:		echo  "
									<option value='1' selected>по короткой стороне</option>	
									<option value='2' >по длинной стороне</option>";    break;
									default:   	echo  "
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
											$query = "select OPERATION_NAME from operations where OPERATION_NAME = '".$PRODUCT_TEMPLATE_TYPE[1]."' and par is not null ORDER BY OPERATION_NAME;";
									IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
										$query = "select OPERATION_NAME from operations where id = '".substr($PRODUCT_TEMPLATE_TYPE[1],1)."' and par is not null ORDER BY OPERATION_NAME;";
									}
									$result = mysql_query($query) or die("Query failed2");
									WHILE ($row = mysql_fetch_row($result)) { 
									$name_operrra = $row [0];
									}
										

$kol11 = $kol + 1;
				
											echo '<h4>&nbsp;&nbsp;<a href="#" onclick="toggle(this,'."'os".$kol."'".')" id="oper_name'.$kol11.'">'.$name_operrra.':</a></h4>' ;
													
												if ( $name_operrra != 'Печать' AND $name_operrra !=  'Широкоформатная печать'){
											echo '<div id="os'.$kol.'" class="closed" value="os'.$kol.'">'; 
										} else {
											echo '<div id="os'.$kol.'" class="opened" value="os'.$kol.'">'; 
										}
										
								
								/*Оборудование*/
								IF ($PRODUCT_TEMPLATE_TYPE[4]  == 1){
									IF($PRODUCT_TEMPLATE_TYPE[5] != ''){
										 echo  "<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> 
															<label id = 'cch".++$kol."8'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Оборудование: </label>
														</div>
													</div>";
											echo  "<div class='col-md-10'>
													<div class='block2'>
														<select  id = 'ch".$kol."8' name = ''  onchange = " . '"'."rts('ch".$kol."8', 'ch".$kol."1','ch".$kol."3',".$kol.")".'" '."> <option value='0' title='0^0|0'>Выберите</option>"; 
										$ID_EQ = explode(",", $PRODUCT_TEMPLATE_TYPE[5]);
										FOR ($g = 0; $g < count($ID_EQ); $g++){
											$query = "select id, eq_name, l_use, l_offset, ladnr, uandd from equipment where id = ".$ID_EQ[$g]." and  eq_name is not null ORDER BY eq_name;";
											$result = mysql_query($query) or die($query);
											WHILE ($row = mysql_fetch_row($result)) { 
												if($row[2] == '1'){
													$eq_id_entr = $row[0];
													echo  "<option value='$row[0]' title='$row[3]^$row[4]|$row[5]' selected >$row[1]</option>"; 
												} else {
													echo  "<option value='$row[0]'  title='$row[3]^$row[4]|$row[5]'>$row[1]</option>"; 
												}
											}
										}
										echo  "</select>
													</div>
													</div>
												</div>";
									}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".++$kol."8' size='10' value = '0' title='0^0|0'>";
									}
								}ELSE {
									IF($PRODUCT_TEMPLATE_TYPE[5] != ''){
										if ($kol == $lastkol) {
										$kol++;
											
									}	
									$query = "select id, eq_name,l_use, l_offset, ladnr, uandd  from equipment where id = ".$PRODUCT_TEMPLATE_TYPE[5]." and  eq_name is not null ORDER BY eq_name;";
									$result = mysql_query($query) or die("Query failed4");	
									WHILE ($row = mysql_fetch_row($result)) { 
										echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '$row[0]' title='0^$row[4]|$row[5]'>";

										$eq_id_entr = $row[0];
									}
									}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '0'  title='0^0|0'>";
									}
								}
								
								IF ($PRODUCT_TEMPLATE_TYPE[1] == 'Офсетная печать'){
									
									echo  "<input  type = 'hidden'  id = 'ch".++$kol."8' size='10' value = '0'  title='0^0|0'>";
										echo  "<div class='row'>
										<div class='col-md-2'>
											<div class='block1'> 
												<label id = 'cch".$kol."1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$PRODUCT_TEMPLATE_TYPE[1]."</label>
											</div>
										</div>  
									   <div class='col-md-10'>
										<div class='block2'>
											<select id = 'ch".$kol."1'  >";
											echo  "<option value='' >Выберите</option>"; 
								$query = "select id, name, price_total,nadbavka, total from offcet ORDER BY name;";
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
												echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '0'  title='0^0|0'>";
									}									
								$query = "select o.id, o.par, o.OPERATION_PRICE,o.MAKEREADY_PRICE,o.OPERATION_NAME, e.id from  equipment e, (select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where OPERATION_NAME = '".$PRODUCT_TEMPLATE_TYPE[1]."' and par is not null) o where FIND_IN_SET(o.id ,e.oper) ORDER BY par;";
								IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
										$query = "select o.id, o.par, o.OPERATION_PRICE,o.MAKEREADY_PRICE,o.OPERATION_NAME, e.id from  equipment e, (select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where id = '".substr($PRODUCT_TEMPLATE_TYPE[1],1)."' and par is not null) o where FIND_IN_SET(o.id ,e.oper) ORDER BY par;";
									}
									
								$result = mysql_query($query) or die("Query failed2");
								IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) != 'i'){
										$str_param1 = '';
									$str_param = '';
									$kol_param = 0;
											WHILE ($row = mysql_fetch_row($result)) { 
								$name_oper = $row[4];
								if ($row[3] == ''){
									$prol = 0;
								}else{
									$prol = $row[3];
								}
								if ($row[5] == $eq_id_entr){
										$str_param = $str_param. "<option title = '$row[5]' value='$row[0]#$row[2]^$prol'".' style="display:block;"'." >$row[1]</option>"; 
										$str_param1 = $str_param1. "<option title = '$row[5]' value='$row[0]#$row[2]^$prol'".' style="display:block;"'." selected>$row[1]</option>"; 
										$kol_param ++;
								} else {
									$str_param = $str_param. "<option title = '$row[5]' value='$row[0]#$row[2]^$prol' ".' style="display:none;"'.">$row[1]</option>"; 
									$str_param1 = $str_param1.  "<option title = '$row[5]' value='$row[0]#$row[2]^$prol' ".' style="display:none;"'.">$row[1]</option>"; 
								}
								
									
									}
									if ($kol_param == 1){
										$str_param = '';
									
																echo  "<div class='row' ".' style="display:none;"'.">
												<div class='col-md-2'>
													<div class='block1'> 
														<label id = 'cch".$kol."1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Параметры: </label>
													</div>
												</div>  
														<div class='col-md-10'>
														<div class='block2'>";
														
													echo  "<select id = 'ch".$kol."1' >";
		
												
												echo $str_param1 ;
												
												echo  "</select></div>
												</div>
											</div>";
												}
										ELSE {
														echo  "<div class='row'>
										<div class='col-md-2'>
											<div class='block1'> 
												<label id = 'cch".$kol."1'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Параметры: </label>
											</div>
										</div>  
									   <div class='col-md-10'>
										<div class='block2'>";
												
											echo  "<select id = 'ch".$kol."1' >";
													
										echo  "<option title = '0'  value='' selected>Выберите</option>"; 
										
										echo $str_param ;
										
										echo  "</select></div>
										</div>
									</div>";
										}
												
									/*if (mysql_num_rows($result)>1){*/
					
						
								
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
												<div class='col-md-3'>
													<div class='block1'> ";
													IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
															 echo "<label id = 'cch".$kol."2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$name_type."</label>";
														}else {
															if ($name_oper == 'Подрезка материала') {
																echo "<label id = 'cch".$kol."2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Заготовок на листе: </label>";
															} else {
																echo "<label id = 'cch".$kol."2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Количество: </label>";
															}
														}
														
													echo "</div>
												</div> 
												<div class='col-md-9'>
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
															 echo "<label id = 'cch".$kol."3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$name_type."</label>";
														}else {
															 echo "<label id = 'cch".$kol."3'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Материал: </label>";
														}
														
															
													 echo "</div>
													</div>";
										echo  "<div class='col-md-10'>
													<div class='block2'>
														<select  id = 'ch".$kol."3' name = '' ><option title = '0' value='0@0!^0' selected>Выберите материал</option>"; 
										$TEMPLATE_TYPE = explode(",", $PRODUCT_TEMPLATE_TYPE[8]);
										
										
											FOR ($z = 0; $z < count($TEMPLATE_TYPE); $z++){
											$query = "select  ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE , ma.m_tol, eq.id from equipment eq, (select ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE , ma.m_tol 
											from  material_attr ma where ma.ID = '".$TEMPLATE_TYPE[$z]."' and ma.M_NAME is not null) ma where FIND_IN_SET(ma.id ,eq.mater) ORDER BY ma.M_NAME;";
											$result = mysql_query($query) or die("Query failed3");
											WHILE ($row = mysql_fetch_row($result)) { 
												if ($row[5] == $eq_id_entr){
													echo  "<option title = '$row[5]' value='$row[0]@$row[2]!$row[3]^$row[4]' ".' style="display:block;" >' ."$row[1]</option>"; 
											} else {
													echo  "<option title = '$row[5]' value='$row[0]@$row[2]!$row[3]^$row[4]' ".' style="display:none;">' ."$row[1]</option>"; 
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
									$query = "select ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE, ma.m_tol  from  material_attr ma where ma.ID = '".$PRODUCT_TEMPLATE_TYPE[8]."' and ma.M_NAME is not null ORDER BY ma.M_NAME;";
									$result = mysql_query($query) or die("Query failed4");	
									WHILE ($row = mysql_fetch_row($result)) { 
										echo  "<input  type = 'hidden'  id = 'ch".$kol."3' size='10' value = '$row[0]@$row[2]!$row[3]^$row[4]'>";
									}
									}ELSE {
										echo  "<input  type = 'hidden'  id = 'ch".$kol."3' size='10' value = '0@0!^0''>";
									}
								}
								IF($PRODUCT_TEMPLATE_TYPE[9] == 1){
									echo 	"<div class='row'>
													<div class='col-md-3'>
														<div class='block1'>  
															<label id = 'cch".$kol."4'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Количество материала: </label>
														</div>
													</div>
													<div class='col-md-9'>
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
															<label id = 'cch".$kol."5'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Количество страниц: </label>
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
															<label id = 'cch".$kol."6'> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Размер: </label>
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
															 echo "<label id = 'cch".$kol."7'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$name_type."</label>";
														}else {
															 echo "<label id = 'cch".$kol."7'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Номер штампа: </label>";
														}
														
															
													 echo "</div>
													</div>";
										echo  "<div class='col-md-3'>
													<div class='block2'>
														<select  id = 'ch".$kol."7' name = '' onchange=".'"img('."'ch".$kol."7','img".$kol."')".'"'."><option  title = '' value='0?0^0*0/1`0' selected>Выберите номер</option>"; 
										$TEMPLATE_TYPEr = explode(",", $PRODUCT_TEMPLATE_TYPE[16]);
										
										FOR ($z = 0; $z < count($TEMPLATE_TYPEr); $z++){
										 $query = "select ID, STAMP_TYPE , STAMP_NAME, STAMP_KOL,STAMP_SIZE,STAMP_PRICE,STAMP_NEW from stamps WHERE  ID = '".$TEMPLATE_TYPEr[$z]."' AND STAMP_NAME  <> '' ORDER BY STAMP_TYPE ";
										$result = mysql_query($query) or die($query);
										WHILE ($row = mysql_fetch_row($result)) { 
											echo  "<option value='$row[0]?$row[3]^$row[4]/$row[6]`$row[5]'>$row[2]</option>"; 
											}
										}
										echo  "</select>";
										echo  " &nbsp;<i class='glyphicon glyphicon-plus  ' onclick=".'"'."ref(".$kol.", '".$name_operrra."' )".'"'."></i>";
										echo  "			</div>
													</div>";
											echo  "<div class='col-md-3'>
													<div class='block2'>	<img  class='rounded ' alt='' id = 'img".$kol."' width = ' 100'" .' style="display:none;"'."></div>
													</div></div>
												";
												
									echo  "<input  type = 'hidden'  id = 'ch".$kol."9' size='10' value = '0'>";
									}ELSE {
									
										IF($PRODUCT_TEMPLATE_TYPE[16] != ''){
									$query = "select ID, STAMP_TYPE , STAMP_NAME, STAMP_KOL, STAMP_SIZE ,STAMP_PRICE,STAMP_NEW from stamps WHERE  ID = '".$PRODUCT_TEMPLATE_TYPE[16]."' AND STAMP_NAME  <> '' ORDER BY STAMP_TYPE ";
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
								
									
									}
								echo 	"<div id = 'ch".$kol."19' style=".'"display:none;"'.">";
								echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Тираж</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."22' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Стоимость(руб.)</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."21' size='10' value = '0'></div>
													</div>
												</div>";
													echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Наценка (%)</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."25' size='10' value = '0'></div>
													</div>
												</div>";
										echo 	"<div class='row'>
													<div class='col-md-2'>
														<div class='block1'><label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Дата поставки</label></div>
													</div> 
													<div class='col-md-10'>
														<div class='block2'>
															<input type='date' id = 'ch".$kol."20'>
														</div>
													</div>
												</div>";
												
												
												
												echo 	"<div class='row' style='display:none;'>
												<div class='col-md-2'>
													<div class='block1'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Кол-во NEXT </label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."23' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Размер </label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."24' size='10' value = '0*0'> </div>
													</div>
												</div>";
										
											echo "</div>";
													
								echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block2'>
														<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Комментарии </label>
														</div> 	</div>   
																<div class='col-md-6'>
													<div class='block2'>
                                            <textarea class='form-control' rows='2' id = 'ch".$kol."10'></textarea>
														</div>     
                                        </div>
													</div>
												</div>";
											echo  "</div>";
								$lastkol = $kol;
						

				
							}ELSE{
								$name_operrra = "";
									// будет ли один виден тогда выводить
									$query = "select OPERATION_NAME from operations where OPERATION_NAME = '".$PRODUCT_TEMPLATE_TYPE[1]."' and par is not null ORDER BY OPERATION_NAME;";
									IF (substr($PRODUCT_TEMPLATE_TYPE[1],0,1) == 'i'){
										$query = "select OPERATION_NAME from operations where id = '".substr($PRODUCT_TEMPLATE_TYPE[1],1)."' and par is not null ORDER BY OPERATION_NAME;";
									}
									$result = mysql_query($query) or die("Query failed2");
									WHILE ($row = mysql_fetch_row($result)) { 
									$name_operrra = $row [0];
									}
									$kol11 = $kol++;
									IF (COUNT(explode(",", $PRODUCT_TEMPLATE_TYPE[5])) > 1) {
											
											echo '<h4>&nbsp;&nbsp;<a href="#" onclick="toggle(this,'."'os".$kol."'".')" id="oper_name'.$kol11.'">'.$name_operrra.':</a></h4>' ;
		
										if ( $name_operrra != 'Печать' AND $name_operrra !=  'Широкоформатная печать'){
											echo '<div id="os'.$kol.'" class="closed" value="os'.$kol.'">'; 
										} else {
											echo '<div id="os'.$kol.'" class="opened" value="os'.$kol.'">'; 
										}
												
									}
	
								IF($PRODUCT_TEMPLATE_TYPE[1] != ''){
									$query = "select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where OPERATION_NAME = '".$PRODUCT_TEMPLATE_TYPE[1]."' and par is not null ORDER BY par;";
								
									
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
															<label id = 'cch".$kol."8'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Оборудование: </label>
														</div>
													</div>";
											echo  "<div class='col-md-10'>
													<div class='block2'>
														<select  id = 'ch".$kol."8' name = '' > <option value='0'  title='0^0|0' selected>Выберите</option>"; 
										$ID_EQ = explode(",", $PRODUCT_TEMPLATE_TYPE[5]);
										FOR ($g = 0; $g < count($ID_EQ); $g++){
											$query = "select id, eq_name, l_use, ladnr, uandd from equipment where id = ".$ID_EQ[$g]." and  eq_name is not null ORDER BY eq_name;";
											$result = mysql_query($query) or die($query);
											WHILE ($row = mysql_fetch_row($result)) { 
													if($row[2] == '1'){
													$eq_id_entr = $row[0];
													echo  "<option value='$row[0]' title='0^$row[4]|$row[5]' >$row[1]</option>"; 
												} else {
													echo  "<option value='$row[0]'  title='0^$row[4]|$row[5]'  uandd ='$row[5]' >$row[1]</option>"; 
												}
												
											}
										}
										echo  "</select>
													</div>
													</div>
												</div>";
									}
									ELSE{
										$query = "select id, eq_name, l_use, ladnr, uandd from equipment where id = ".$PRODUCT_TEMPLATE_TYPE[5]." and  eq_name is not null ORDER BY eq_name;";
										$result = mysql_query($query) or die($query);	
										WHILE ($row = mysql_fetch_row($result)) { 
											echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '$row[0]' title='0^$row[4]|$row[5]'>";
											$eq_id_entr = $row[0];
										}
									}
									}ELSE {
											echo  "<input  type = 'hidden'  id = 'ch".$kol."8' size='10' value = '0' title='0^0|0'>";
										}
								IF($PRODUCT_TEMPLATE_TYPE[8] != ''){
									$query = "select ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE, ma.m_tol from  material_attr ma where ma.ID = '".$PRODUCT_TEMPLATE_TYPE[8]."' and ma.M_NAME is not null ORDER BY ma.M_NAME;";
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
								echo 	"<input  type = 'hidden'  id = 'ch".$kol."10' size='10' value = ''>";
								
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
												echo 	"<div id = 'ch".$kol."19' style=".'"display:none;"'.">";
								echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Тираж</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."22' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Стоимость(руб.)</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."21' size='10' value = '0'></div>
													</div>
												</div>";
													echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Наценка (%)</label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."25' size='10' value = '0'></div>
													</div>
												</div>";
										echo 	"<div class='row'>
													<div class='col-md-2'>
														<div class='block1'><label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Дата поставки</label></div>
													</div> 
													<div class='col-md-10'>
														<div class='block2'>
															<input type='date' id = 'ch".$kol."20'>
														</div>
													</div>
												</div>";
												
												
												
												echo 	"<div class='row' style='display:none;' >
												<div class='col-md-2'>
													<div class='block1'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Кол-во NEXT </label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."23' size='10' value = '0'></div>
													</div>
												</div>";
												echo 	"<div class='row'>
												<div class='col-md-2'>
													<div class='block1'><label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Размер </label></div>
												</div> 
												<div class='col-md-10'>
													<div class='block2'>
														<input  type = 'text'  id = 'ch".$kol."24' size='10' value = '0*0'> </div>
													</div>
												</div>";
										
											echo "</div>";
																$lastkol = $kol;	
											IF (COUNT(explode(",", $PRODUCT_TEMPLATE_TYPE[5])) > 1) {
							echo  "</div>"; 
										}		
									echo  "</div>";
										
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
					echo  "<br><div class='row'>
										<div class='col-md-2'>
											<div class='block1'>
												<label>Дизайн: </label> 
											</div>
										</div>
										<div class='col-md-1'>
											<div class='block2'>
												<input  type = 'text' size='5' value = '0' id = 'dizz_sum' name = 'dizz_sum'> 
									<input  type = 'hidden'  id = 'view_diz'  name = 'view_diz'  size='10' value = '0'>
											</div>
										</div>
								</div>";
						echo  "<div class='row'>
										<div class='col-md-2'>
											<div class='block1'>
												<label>Препресс : </label> 
											</div>
										</div>
										<div class='col-md-1'>
											<div class='block2'>
													<input  type = 'text' size='5' value = '0' id = 'press_sum' name = 'press_sum'>
												<input type = 'hidden'  id = 'view_press' name = 'view_press' size='10' value = '".$iddd."'>
											</div>
										</div>
								</div>";
					echo  "<br><div class='row'>
							<div class='col-md-2'>
								<div class='block1'>
									<label>Кол-во : </label> 
								</div>
							</div>
							<div class='col-md-1'>
								<div class='block2'>
									<input  type = 'hidden'  id = 'par1' name = 'kol1' size='10' value = '0'>
									<input class = '_kol' name = 'kol1' type = 'text' size='5' value = '0' id = 'kol1'  disabled> 
								</div>
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
								<div class='block9'><input  type = 'hidden'  id = 'kol' size='10' value = '".$kol."'><input  type = 'hidden'  id = 'flag' size='10' value = '0'>";
					echo 	'<input type="button" class="btn btn-default" onclick="amCal';
					echo 	"($kol,";
					echo  " $summMat,$orderProd, $orderAcct,$summMR,$prod_size_1,$prod_size_2,$PRODUCT_SH,$PRODUCT_SKOBA,$clients_nadbavka,$kurs,$nds,1,$firm_nacenka)";
					echo  '" value="Расчитать"/>	</div>
							</div>	</div>					<div id = "raschet">					</div>';
					echo  "		<input  type = 'hidden'  id = 'id_del' name = 'id_del' value = ''>";
					echo  "</form>";
	
					}
				
                ?>

   
				<div id = "raschet2">
					<!-- <a onclick = 'excel()'>
        <button type="button" class="btn btn-default"><span class="glyphicon glyphicon-plus-sign"></span>Получить Excel</button></a> 
	<a href='#' onclick='downloadCSV({ filename: "stock-data.csv" });'>Download CSV</a>-->
				</div>
        </div>
    </div>
	
	<?php
		
		echo '	
		<div id="myModal3" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"><button class="close" type="button" onclick="hidemyModal3()" >×</button>
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
					<div class="modal-footer"><button type="button" onclick="hidemyModal3()" class="btn btn-default">Отмена</button>
						<button type="button" class="btn btn-primary"    onclick="diz_fun('.$price_diz.','.$kurs.') ">Добавить</button>
					</div>
				</div>
			</div>
		</div>';

	echo '		 
		<div id="myModal4" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="false" >
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header"><button class="close" type="button"  onclick="hidemyModal4()">×</button>
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
					<div class="modal-footer"><button type="button"  onclick="hidemyModal4()"  class="btn btn-default">Отмена</button>
						<button type="button" class="btn btn-primary"    onclick="pre_fun('.$price_diz.','.$kurs.') ">Добавить</button>	
					</div>
				</div>
			</div>
		</div>';
	?>
