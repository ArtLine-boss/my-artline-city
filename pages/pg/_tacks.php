<?php
include '../firewall.php';

include "../db.php";
$idAcct = $_GET['id_order'];

session_start();
$login = $_SESSION['login'];


$query = "select id from operations s where OPERATION_NAME = 'Дизайн' LIMIT 1";
$result = mysql_query($query) or die($query);echo "<br>";

WHILE ($row = mysql_fetch_row($result)) { 
	$diz_id = $row[0];
	}
$query = "select id from operations s where OPERATION_NAME = 'Препресс' LIMIT 1";
$result = mysql_query($query) or die($query);echo "<br>";
WHILE ($row = mysql_fetch_row($result)) { 
	$pres_id = $row[0];
}
$GTYRRD  = true;
$flags = true;
$_kol = 0;
$date_pos   =  date("Y-m-d H:i:s");
	$query="select * from order_product where order_id =".$idAcct." and flags <> 1";
	$result = mysql_query($query) or die($query);echo "<br>";
$otrab = array();

while ($row = mysql_fetch_row($result))
{
	$otrab[] = array($row[0],$row[1],$row[2],$row[3],$row[4],$row[5],$row[6],$row[7],$row[8],$row[9],$row[10],$row[11],$row[12],$row[13],$row[14],$row[15],$row[16],$row[17]);
}
	
	
for ($ttt = 0 ; $ttt < count($otrab); $ttt++){
$_kol = 0;
$row = $otrab[$ttt];

		$temp_pr = '';
		$or_pr= '';
		$dizzzzzzz= '';
		$preeeeeee = '';
		$SHABLON_CL= '';
		$press_diz= '';
		$print_diz= '';
		$view_diz= '';
		$view_press= '';
		$prod_yay = '';
		$temp_lll = '';
		$prod_yay1 = '';
		$or_pr = $row[2].'$'.$row[0].'^'.$row[7].'?'.$row[3];
		$dizzzzzzz = $row[9];
		$preeeeeee = $row[15];
		$SHABLON_CL = $row[10]; 
		$press_diz = $row[13]; 
		$print_diz = $row[14];
		$view_diz = $row[16]; 
		$view_press = $row[17];
		$prod_yay = $row[2];
		$temp_lll = $row[6];
		$prod_yay1 = $row[0];
		$size_prod = $row[8];
	IF($prod_yay <> 0){
	
	/*$or_pr_row = explode("&", $or_pr);/*Продукты в заказе*/
	
		$or_pr_row1 = explode("$", $or_pr);/*Продукты в заказе*/
		$id_prod = $or_pr_row1[0];
		$query="select * from product where id =".$or_pr_row1[0];
		$result = mysql_query($query) or die($query);echo "<br>";
		while ($row_pr = mysql_fetch_row($result)) { 
			$PRODUCT_TEMPLATE_ROW = explode("!", $row_pr[2]);
			
			IF (count($PRODUCT_TEMPLATE_ROW) == 1){
				
				$PRODUCT_TEMPLATE_PART = explode("^", $PRODUCT_TEMPLATE_ROW[0]);
				
				$or_row = explode("^", $or_pr_row1[1]); // id продукта в счете;
			
				$id_acct_or = $or_row[0];
				$kollaa = 0;
				IF ($dizzzzzzz != 0){
					$kollaa++;
				$query = "INSERT INTO order_per (Numder_order	,num_prod, acct_or, num_oper	,part, operation,date_pos, status,file , oper_all, oborud) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$kollaa},'Дизайн', '{$diz_id}','{$date_pos}', '1','{$SHABLON_CL}', '{$view_diz}',29);";
										
				mysql_query($query) or die($query);
					$kollaa++;
				$query = "INSERT INTO order_per (Numder_order	,num_prod, acct_or, num_oper	,part, operation,status, oper_all,oborud) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$kollaa},'Препресс', '{$pres_id}','0', '{$view_press}',30);";
				mysql_query($query) or die($query);	
				$flags = FALSE;
				
				} else {
					
					IF ($preeeeeee != 0){
						$kollaa++;
						$query = "INSERT INTO order_per (Numder_order	,num_prod, acct_or, num_oper	,part, operation,date_pos,status,file, oper_all,oborud) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$kollaa},'Препресс', '{$pres_id}','{$date_pos}','1','{$press_diz}', '{$view_press}',30);";
					mysql_query($query) or die($query);	
					$flags = FALSE;
					}	ELSE {
						$flags = TRUE;
					}
				}
				
				$_kol = $kollaa;
				$or_row5 = explode("?", $or_row[1]); // кол-во
				$kol_ = $or_row5[1];
				
				$or_row4= explode("!", $or_row5[0]); // 
				for ($t = 0; $t < count($or_row4); $t++){	
			
					$or_row6 = explode("|", $or_row4[$t]); // 
					
					$OPER =  $or_row6[0]; // ОПЕРАЦИЯ 
					$MATER = $or_row6[2]; //МАТЕРИАЛ
					$eqqqq = $or_row6[3]; // оборудование
					$off_set = $or_row6[4]; // оффсет
					$off_set_I = explode("`", $off_set);
					$off_set_date = $off_set_I[0];
					$off_set_kol = $off_set_I[2];
					$off_set_size = $off_set_I[4];
					$stamp = $or_row6[5]; // штамп
						$comentsss = $or_row6[6]; // коммент
						
					IF($stamp== ''){
							$stamp= 0;
						}
					IF($eqqqq== ''){
							$eqqqq= 0;
						}
					IF($OPER == ''){
							$OPER = 0;
						}
						IF($MATER == ''){
							$MATER = 0;
						}
						
						if ($stamp != 0 AND $stamp != '0'){
							$query = "UPDATE stamps SET STAMP_NEW=1, STAMP_TEST=0 WHERE ID =".$stamp;
							mysql_query($query) or die($query);
						}
						$num_stamp = '';
						if ($stamp != 0 AND $stamp != '0'){
							
							$query = "select STAMP_NAME,STAMP_NUMDER from stamps where id = ".$stamp;
							$result = mysql_query($query) or die($query);echo "<br>";
							WHILE ($row = mysql_fetch_row($result)) { 
								$num_stamp = $row[0]. " " . $row[1];
							}	
						}
						
						
					$query = "select l_offset from equipment where id =  ".$eqqqq;
					$result = mysql_query($query) or die($query);echo "<br>";
					WHILE ($row = mysql_fetch_row($result)) { 
						$off_eq = $row[0];
					}		
						
					$or_row7 =  explode(";", $or_row6[1]);
					
					
					for ($x = 0; $x < count($or_row7 ); $x++){	
						$or_row8 = explode(",", $or_row7[$x]);
							if ($or_row8[1] == $kol_){
								$kol_vo = $or_row8[0];//кол-во операций
								
									
								if($flags){
									$status = '1';
									$flags = false;
									IF (($PRODUCT_TEMPLATE_PART[0] == 'Сборка в готовое изделие')OR ($PRODUCT_TEMPLATE_PART[0] == 'Сборка в готовое изделия') ) {
									$status = '0';
								} 
								/*Формирование резки*/
									
									IF ($off_eq != "1"){
									$size_m = "";
										$total_m = "";
										$name_m = "";
									$query = "select m.size, s.M_KOL_ALL k1, m.name from material_attr s, 	(select m_size size,m_name_ name from material_attr where id = ".$MATER.") m where s.m_NAME_ = m.name and s.m_SIZE = m.size";
									$result = mysql_query($query) or die($query);echo "<br>";
									WHILE ($row = mysql_fetch_row($result)) { 
										$size_m = $row[0];
										$total_m = $row[1];
										$name_m = $row[2];
									}
									if (count(explode("*", $size_m ))>1){
										if ($total_m == 0 or $total_m < $kol_vo ){
										
											IF ($size_m == '360*510'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1020'";
													$result = mysql_query($query) or die($query);echo "<br>";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/2);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
												$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
												VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*510');";
												mysql_query($query) or die($query);echo "<br>";
												$status = '0';
												}
										
											IF ($size_m == '360*660'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1040'";
													$result = mysql_query($query) or die($query);echo "<br>";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/3);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
												$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
												VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*660');";
												mysql_query($query) or die($query);echo "<br>";
												$status = '0';
												}
										
											IF ($size_m == '360*520'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1040'";
													$result = mysql_query($query) or die($query);
													echo "<br>";
												WHILE ($row = mysql_fetch_row($result)) { 
												
												$MATER1 = $row[0];
												}
												
												
												echo "$MATER1".$MATER1;
												
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
														echo "$MATER1".$MATER1;
												$kol_vo1 = ceil($kol_vo/4);
												//id = 30 подезка
												
												$_kol++;
												echo "e4e<br>";
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*520');";
													mysql_query($query) or die($query);echo "2322<br>";
													$status = '0';
												}
												
											
											
											IF ($size_m == '320*450'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '630*940'";
													$result = mysql_query($query) or die($query);echo "<br>";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/4);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												   $_kol++;
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'320*450');";
													mysql_query($query) or die($query);echo "<br>";
													$status = '0';
												}
											
												}
											}
									
									/*-----------------*/
									}
									IF ($OPER == '30' ) {
										if ($comentsss == ''){
												$num_stamp = $size_prod ;
										}
										else {
												$num_stamp = $comentsss ;
										}
									
									} 
									ELSE {
										$num_stamp = 	$comentsss ;
									}
									$_kol++;
																echo "<br><br><br>".$comentsss.' '.$num_stamp."<br><br><br>";
										IF ($off_eq == "1"){
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status, oborud,off_set_date,comment) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '{$OPER}', {$kol_vo}, {$MATER},'{$date_pos}', '{$status}',{$eqqqq},'{$off_set_date}','{$num_stamp}');";
										} else {
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status, oborud,comment) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '{$OPER}', {$kol_vo}, {$MATER},'{$date_pos}', '{$status}',{$eqqqq},'{$num_stamp}');";
										
										}
										
											mysql_query($query) or die($query);echo "<br>";
									IF ($off_eq == "1"){
											
											$size_m = "";
											$total_m = "";
											$name_m = "";
											$query = "select m.size, s.M_KOL_ALL k1, m.name from material_attr s, 	(select m_size size,m_name_ name from material_attr where id = ".$MATER.") m where s.m_NAME_ = m.name and s.m_SIZE = m.size";
											$result = mysql_query($query) or die($query);echo "<br>";
											WHILE ($row = mysql_fetch_row($result)) { 
												$size_m = $row[0];
												$total_m = $row[1];
												$name_m = $row[2];
											}
											
											$off_set_size_W =  explode("*", $off_set_size);
											
											IF ($size_m <> $off_set_size AND $size_m <> $off_set_size_W[1]."*".$off_set_size_W[0] ){
													$_kol++;											
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$off_set_kol}, {$MATER},'{$date_pos}', '{$status}',18,'{$off_set_size}');";
													mysql_query($query) or die($query);echo "<br>";
											}
											
									}		
											
											
								}
								else{
									$status = '0';
									
									/*Формирование резки*/
									IF ($off_eq != "1"){
									$size_m = "";
										$total_m = "";
										$name_m = "";
									$query = "select m.size, s.M_KOL_ALL k2, m.name from material_attr s, 	(select m_size size,m_name_ name from material_attr where id = ".$MATER.") m where s.m_NAME_ = m.name and s.m_SIZE = m.size";
									$result = mysql_query($query) or die($query);echo "<br>";
									WHILE ($row = mysql_fetch_row($result)) { 
										$size_m = $row[0];
										$total_m = $row[1];
										$name_m = $row[2];
									}
									if (count(explode("*", $size_m ))>1){
										if ($total_m == 0 or $total_m < $kol_vo ){
												IF ($size_m == '360*510'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1020'";
													$result = mysql_query($query) or die($query);echo "<br>";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/2);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
												$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
												VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*510');";
												mysql_query($query) or die($query);echo "<br>";
												$status = '0';
												}
											IF ($size_m == '360*660'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1040'";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/3);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*660');";
													mysql_query($query) or die($query);echo "<br>";
													$status = '0';
												}
										
											IF ($size_m == '360*520'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1040'";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/4);
												//id = 30 подезка
												
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
												 echo "e31e<br>";
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*520');";
													mysql_query($query) or die($query);echo "222<br>";
													$status = '0';
												}
												
											
											
											IF ($size_m == '320*450'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '630*940'";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/4);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,320*450);";
													mysql_query($query) or die($query);echo "<br>";
													$status = '0';
													
												}
											
												}
											}
										}
									/*-----------------*/
									$_kol++;
								IF ($OPER == '30' ) {
										if ($comentsss == ''){
												$num_stamp = $size_prod ;
										}
										else {
												$num_stamp = $comentsss ;
										}
									
									} 
									ELSE {
										$num_stamp = 	$comentsss ;
									}
									
									echo $off_eq ;
									IF ($off_eq == "1"){

											$query = "INSERT INTO order_per (Numder_order	,num_prod, acct_or, num_oper	,part, operation, kol, mater,status,oborud,off_set_date,comment) 
																VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '{$OPER}', {$kol_vo}, {$MATER}, '{$status}',{$eqqqq},'{$off_set_date}','{$num_stamp}');";
										} else {
												$query = "INSERT INTO order_per (Numder_order	,num_prod, acct_or, num_oper	,part, operation, kol, mater,status,oborud,comment) 
																VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '{$OPER}', {$kol_vo}, {$MATER}, '{$status}',{$eqqqq},'{$num_stamp}');";
										
										}
					
									mysql_query($query) or die($query);echo "<br>";
									
									IF ($off_eq == "1"){
											
									$size_m = "";
									$total_m = "";
									$name_m = "";
									$query = "select m.size, s.M_KOL_ALL k1, m.name from material_attr s, 	(select m_size size,m_name_ name from material_attr where id = ".$MATER.") m where s.m_NAME_ = m.name and s.m_SIZE = m.size";
									$result = mysql_query($query) or die($query);echo "<br>";
									WHILE ($row = mysql_fetch_row($result)) { 
										$size_m = $row[0];
										$total_m = $row[1];
										$name_m = $row[2];
									}
											
									$off_set_size_W =  explode("*", $off_set_size);
											
									IF ($size_m <> $off_set_size AND $size_m <> $off_set_size_W[1]."*".$off_set_size_W[0] ){
									$_kol++;											
									$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
									VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$off_set_kol}, {$MATER},'{$date_pos}', '{$status}',18,'{$off_set_size}');";
									mysql_query($query) or die($query);echo "<br>";
											}
											
									}		
									
									
								}
							
								/*echo "Номер заказа ".$idAcct." Номер ПРОД. в заказе ".$id_acct_or." Часть ".$PRODUCT_TEMPLATE_PART[0]." Операция ".$OPER." Кол-во ".$kol_vo." Материал ".$MATER.'<br>';*/
								}
							}
						}
				}
									
			ELSE {
				$flag = 0;
				for ($t = 0; $t < COUNT($PRODUCT_TEMPLATE_ROW); $t++){	
				$flags = TRUE;
				
					$PRODUCT_TEMPLATE_PART = explode("^", $PRODUCT_TEMPLATE_ROW[$t]);
					$or_row = explode("^", $or_pr_row1[1]); // id продукта в счете;
					$id_acct_or = $or_row[0];
					$kollaa = 0;
						IF ($GTYRRD){
							
						IF ($dizzzzzzz != 0){
							$kollaa++;
						$query = "INSERT INTO order_per (Numder_order	,num_prod, acct_or, num_oper	,part, operation,date_pos, status, oper_all,oborud) 
												VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$kollaa},'Дизайн', '{$diz_id}','{$date_pos}', '1', '{$view_diz}',29);";
						mysql_query($query) or die($query);echo "<br>";
									$kollaa++;
						$query = "INSERT INTO order_per (Numder_order	,num_prod, acct_or, num_oper	,part, operation,status, oper_all,oborud) 
												VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$kollaa},'Препресс', '{$pres_id}','0', '{$view_press}',30);";
						mysql_query($query) or die($query);echo "<br>";	
						$flags = FALSE;
						$GTYRRD = FALSE;
						} else {
							
							IF ($preeeeeee != 0){
										$kollaa++;
								$query = "INSERT INTO order_per (Numder_order	,num_prod, acct_or, num_oper	,part, operation,status, oper_all,oborud) 
												VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$kollaa},'Препресс', '{$pres_id}','1', '{$view_press}',30);";
							mysql_query($query) or die($query);	echo "<br>";
							$flags = FALSE;
							}	ELSE {
								$flags = TRUE;
							}
							$GTYRRD = FALSE;
						}
						}
					$_kol = $kollaa;	
					$or_row5 = explode("?", $or_row[1]); // кол-во
					$kol_ = $or_row5[1];
					//$PRODUCT_TEMPLATE_PART[0] часть
					$PRODUCT_TEMPLATE_T = explode(";", $PRODUCT_TEMPLATE_PART[1]);
					for ($x = 0; $x < count($PRODUCT_TEMPLATE_T); $x++){
						$or_row4= explode("!", $or_row5[0]); // 
						$or_row6 = explode("|", $or_row4[$flag]); // 	
						$OPER =  $or_row6[0]; // ОПЕРАЦИЯ 
						$MATER = $or_row6[2]; //МАТЕРИАЛ
						$eqqqq = $or_row6[3];
						$off_set = $or_row6[4]; // оффсет
						$off_set_I = explode("`", $off_set);
						$off_set_date = $off_set_I[0];
						$off_set_kol = $off_set_I[2];
						$off_set_size = $off_set_I[4];
					$eqqqq = $or_row6[3]; // оборудование
					$stamp = $or_row6[5]; // штамп
					$comentsss = $or_row6[6]; // коммент
					IF($stamp== ''){
							$stamp= 0;
						}
						IF($OPER == ''){
							$OPER = 0;
						}
						IF($MATER == ''){
							$MATER = 0;
						}
						IF($eqqqq== ''){
							$eqqqq= 0;
						}
						if ($stamp != 0 AND $stamp != '0'){
							$query = "UPDATE stamps SET STAMP_NEW=1, STAMP_TEST=0 WHERE ID =".$stamp;
							mysql_query($query) or die($query);
						}
						$num_stamp = '';
						if ($stamp != 0 AND $stamp != '0'){
							$query = "select STAMP_NAME,STAMP_NUMDER from stamps where id = ".$stamp;
							$result = mysql_query($query) or die($query);echo "<br>";
							WHILE ($row = mysql_fetch_row($result)) { 
								$num_stamp = $row[0]. " " . $row[1];
							}	
						}
						
						
						
						$query = "select l_offset from equipment where id =  ".$eqqqq;
						$result = mysql_query($query) or die($query);echo "<br>";
						WHILE ($row = mysql_fetch_row($result)) { 
							$off_eq = $row[0];
						}		
						
						$or_row7 =  explode(";", $or_row6[1]);
						for ($y = 0; $y < count($or_row7 ); $y++){	
							$or_row8 = explode(",", $or_row7[$y]);
							if ($or_row8[1] == $kol_){
								$kol_vo = $or_row8[0];//кол-во операций
							}
						}
						if($flags){
							$status = '1';
								$art1 = explode(" ",$PRODUCT_TEMPLATE_PART[0]);
								$art2 = explode(" ",'Сборка в готовое изделие');
							IF (($art1[2] == $art2[2]) && ($art1[3] == $art2[3])) {
								echo $status.'<br>';
								$status = '0';
							} 
				
							$flags = false;
							/*Формирование резки*/
									IF ($off_eq != "1"){
									$size_m = "";
									$total_m = "";
									$name_m = "";
									$query = "select m.size, s.M_KOL_ALL k3 , m.name from material_attr s, 	(select m_size size,m_name_ name from material_attr where id = ".$MATER.") m where s.m_NAME_ = m.name and s.m_SIZE = m.size";
									$result = mysql_query($query) or die($query);echo "<br>";
									WHILE ($row = mysql_fetch_row($result)) { 
										$size_m = $row[0];
										$total_m = $row[1];
										$name_m = $row[2];
									}
									if (count(explode("*", $size_m ))>1){
										if ($total_m == 0 or $total_m < $kol_vo ){
											$MATER1 = '';
												IF ($size_m == '360*510'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1020'";
													$result = mysql_query($query) or die($query);echo "<br>";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/2);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
												$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
												VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*510');";
												mysql_query($query) or die($query);echo "<br>";
												$status = '0';
												}
											
											IF ($size_m == '360*660'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1040'";
												$result = mysql_query($query) or die($query);
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/3);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*660');";
													mysql_query($query) or die($query);echo "<br>";
													$status = '0';
												}
										
											IF ($size_m == '360*520'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1040'";
												$result = mysql_query($query) or die($query);
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/4);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
												echo "e3e<br>";
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*520');";
													mysql_query($query) or die($query);echo "33222<br>";
													$status = '0';
												}
												
											
											
											IF ($size_m == '320*450'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '630*940'";
												$result = mysql_query($query) or die($query);
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/4);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'320*450');";
													mysql_query($query) or die($query);echo "<br>";
													$status = '0';
													
												}
											
												}
											}
									}
									/*-----------------*/

								$_kol++;
							IF ($OPER == '30' ) {
										if ($comentsss == ''){
												$num_stamp = $size_prod ;
										}
										else {
												$num_stamp = $comentsss ;
										}
									
									} 
									ELSE {
										$num_stamp = 	$comentsss ;
									}
													echo "<br><br><br>".$comentsss.' '.$num_stamp."<br><br><br>";
									
										IF ($off_eq == "1"){
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status, oborud,off_set_date,comment) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '{$OPER}', {$kol_vo}, {$MATER},'{$date_pos}', '{$status}',{$eqqqq},'{$off_set_date}','{$num_stamp}');";
										} else {
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status, oborud,comment) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '{$OPER}', {$kol_vo}, {$MATER},'{$date_pos}', '{$status}',{$eqqqq},'{$num_stamp}');";
										
										}
										
											mysql_query($query) or die($query);echo "<br>";
									IF ($off_eq == "1"){
											
											$size_m = "";
											$total_m = "";
											$name_m = "";
											$query = "select m.size, s.M_KOL_ALL k1, m.name from material_attr s, 	(select m_size size,m_name_ name from material_attr where id = ".$MATER.") m where s.m_NAME_ = m.name and s.m_SIZE = m.size";
											$result = mysql_query($query) or die($query);echo "<br>";
											WHILE ($row = mysql_fetch_row($result)) { 
												$size_m = $row[0];
												$total_m = $row[1];
												$name_m = $row[2];
											}
											
											$off_set_size_W =  explode("*", $off_set_size);
											
											IF ($size_m <> $off_set_size AND $size_m <> $off_set_size_W[1]."*".$off_set_size_W[0] ){
													$_kol++;											
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$off_set_kol}, {$MATER},'{$date_pos}', '{$status}',18,'{$off_set_size}');";
													mysql_query($query) or die($query);echo "<br>";
											}
											
									}	
						
						}
						else{
							$status = '0';
							/*Формирование резки*/
									IF ($off_eq != "1"){
									$size_m = "";
										$total_m = "";
										$name_m = "";
									$query = "select m.size, s.M_KOL_ALL k4, m.name from material_attr s, 	(select m_size size,m_name_ name from material_attr where id = ".$MATER.") m where s.m_NAME_ = m.name and s.m_SIZE = m.size";
									$result = mysql_query($query) or die($query);echo " <br>";
									WHILE ($row = mysql_fetch_row($result)) { 
										$size_m = $row[0];
										$total_m = $row[1];
										$name_m = $row[2];
									}
									if ((count(explode("*", $size_m ))>1) AND (($size_m == '360*660') OR ($size_m == '360*520') OR ($size_m == '320*430')) AND (stristr($name_m, 'Картон') === FALSE )){
										if ($total_m == 0 or $total_m < $kol_vo ){
								$MATER1  = '';
									IF ($size_m == '360*510'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1020'";
													$result = mysql_query($query) or die($query);echo "<br>";
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/2);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
												$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
												VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*510');";
												mysql_query($query) or die($query);echo "<br>";
												$status = '0';
												}
											IF ($size_m == '360*660'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1040'";
												$result = mysql_query($query) or die($query);
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/3);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*660');";
													mysql_query($query) or die($query);echo "<br>";
													$status = '0';
												}
										
											IF ($size_m == '360*520'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '720*1040'";
												$result = mysql_query($query) or die($query);
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/4);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
												echo "ee<br>";
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'360*520');";
													mysql_query($query) or die($query);
													$status = '0';
												}
												
											
											
											IF ($size_m == '320*450'){
												$query = "select id from material_attr where m_name_ = '".$name_m ."' and m_size = '630*940'";
												$result = mysql_query($query) or die($query);
												WHILE ($row = mysql_fetch_row($result)) { 
												$MATER1 = $row[0];
												}
												$kol_vo1 = ceil($kol_vo/4);
												//id = 30 подезка
												IF($MATER1 == ''){
													$MATER1 = 0;
												}
												$_kol++;
													$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$kol_vo1}, {$MATER1},'{$date_pos}', '{$status}',18,'320*450');";
													mysql_query($query) or die($query);echo "<br>";
													$status = '0';
													
												}
											
												}
											}
									}
									/*-----------------*/
							
									
								$_kol++;
							IF ($OPER == '30' ) {
										if ($comentsss == ''){
												$num_stamp = $size_prod ;
										}
										else {
												$num_stamp = $comentsss ;
										}
									
									} 
									ELSE {
										$num_stamp = 	$comentsss ;
									}
										IF ($off_eq == "1"){
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status, oborud,off_set_date,commet) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '{$OPER}', {$kol_vo}, {$MATER},'{$date_pos}', '{$status}',{$eqqqq},'{$off_set_date}','{$num_stamp}');";
										} else {
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status, oborud,comment) 
										VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '{$OPER}', {$kol_vo}, {$MATER},'{$date_pos}', '{$status}',{$eqqqq},'{$num_stamp}');";
										
										}
										
											mysql_query($query) or die($query);echo "<br>";
									IF ($off_eq == "1"){
											
											$size_m = "";
											$total_m = "";
											$name_m = "";
											$query = "select m.size, s.M_KOL_ALL k1, m.name from material_attr s, 	(select m_size size,m_name_ name from material_attr where id = ".$MATER.") m where s.m_NAME_ = m.name and s.m_SIZE = m.size";
											$result = mysql_query($query) or die($query);echo "<br>";
											WHILE ($row = mysql_fetch_row($result)) { 
												$size_m = $row[0];
												$total_m = $row[1];
												$name_m = $row[2];
											}
											
											$off_set_size_W =  explode("*", $off_set_size);
											
											IF ($size_m <> $off_set_size AND $size_m <> $off_set_size_W[1]."*".$off_set_size_W[0] ){
													$_kol++;											
											$query = "INSERT INTO order_per (Numder_order,num_prod, acct_or, num_oper	,part, operation, kol, mater, date_pos,status,oborud,comment) 
													VALUES ({$idAcct}, {$id_prod} , {$id_acct_or}, {$_kol},'{$PRODUCT_TEMPLATE_PART[0]}', '30', {$off_set_kol}, {$MATER},'{$date_pos}', '{$status}',18,'{$off_set_size}');";
													mysql_query($query) or die($query);echo "<br>";
											}
											
									}		
									
						}
						
					
					
						/*echo "Номер заказа ".$idAcct." Номер ПРОД. в заказе ".$id_acct_or." Часть ".$PRODUCT_TEMPLATE_PART[0]." Операция ".$OPER." Кол-во ".$kol_vo." Материал ".$MATER.'<br>';*/
					$flag++;
					}
					$_kol = $kollaa;
					
				}
			}
		}

	} else{
		$OPER = '';
		$MATER = '';
		$flag = 0;
		$kill = 0;
		$flags = true;
		$temp_l1 =  explode("!", $temp_lll); 
		FOR ($a = 0; $a < count($temp_l1); $a++ ){
			$temp_l2 =  explode("$", $temp_l1[$a]); 
			if($flags){
			$flag++;
			$id_acct_or =  $prod_yay1;
			$comment = $temp_l2[1]; 
			$OPER = $temp_l2[0];
			$MATER = $temp_l2[2];
			$kill = $temp_l2[3];
			$ffile = $temp_l2[4];
			$status = '1';
			$query = "INSERT INTO order_per (Numder_order, 		acct_or	,	num_oper		,		comment	, 					operation	,      mater,	  date_pos,status,kol,file) 
											VALUES ({$idAcct}, 		{$id_acct_or},{$flag}, '{$comment}', '{$OPER}',  {$MATER}, '{$date_pos}', '{$status}', {$kill},'{$ffile}');";
	
		$flags = false;								
			}
			else {
				$flag++;
			$id_acct_or =  $prod_yay1;
			$comment = $temp_l2[1]; 
			$OPER = $temp_l2[0];
			$MATER = $temp_l2[2];
			$kill = $temp_l2[3];
			$ffile = $temp_l2[4];
			$status = '0';
			$query = "INSERT INTO order_per (Numder_order, 		acct_or	,	num_oper			,	comment	, 					operation	,      mater,	 status,kol,file) 
											VALUES ({$idAcct}, 		{$id_acct_or},{$flag}, '{$comment}', '{$OPER}',  {$MATER}, '{$status}', {$kill},'{$ffile}');";
			}
mysql_query($query) or die($query);			
		}
					
		}						
		
	}

IF ($_GET['flag'] != '0'){	
$query= "UPDATE orders SET STATUS_ID = 2, USER_ID ='".$login ."' WHERE NUMBER = ".$idAcct;

mysql_query($query) or die($query);echo "<br>";
}
	mysql_close($connection);
?>

<script >
location.href = 'time_eq.php';

</script>




