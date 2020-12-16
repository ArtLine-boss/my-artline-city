<?php
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
    include_once '../firewall.php';

	session_start();
	$login = $_SESSION['login'];
	$query = "select user_per from users where user_login = '" . $login . "' LIMIT 1";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
		$admin = $row[0];
	}
	$line = "<select name = 'typeStamp'> <option value='' selected >Выберите материал</option>";
	include '../db.php';
	$query = "select DISTINCT STAMP_TYPE from stamps";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
		$line = $line . "<option value='$row[0]'>$row[0]</option>";
	}
	$line = $line . "</select>";
	
	
	function fun_name($id)
	{
		$result = mysql_query("select title from kl_mat where id =" . $id);
		while ($cat = mysql_fetch_row($result)) {
			return $cat[0];
		}
	}
	
	
	function fun_flag($id)
	{
		$result = mysql_query("select flags from kl_mat where id =" . $id);
		while ($cat = mysql_fetch_row($result)) {
			return $cat[0];
		}
	}
	
	function fun_parent($id)
	{
		$result = mysql_query("select parent from kl_mat where id =" . $id);
		while ($cat = mysql_fetch_row($result)) {
			return $cat[0];
		}
	}
	
	function fun_names($id)
	{
		
		$result = mysql_query("SELECT kl.ID, kl.title, kl.flags , kl.parent  FROM  kl_mat kl WHERE kl.ID = " . $id);
		while ($cat = mysql_fetch_row($result)) {
			
			$name = $cat[1];
			$id_pr = $cat[3];
			$flags = $cat[2];
			while ($flags == "0") {
            $names = fun_name($id_pr);
            $name = $names . " " . $name;
            $flags = fun_flag($id_pr);
				
				$id_new = fun_parent($id_pr);
				$id_pr = $id_new;
			}
		}
		
		return $name;
	}
	
	function fun_group($id)
	{
		
		$result = mysql_query("SELECT kl.ID, kl.title, kl.flags , kl.parent  FROM  kl_mat kl WHERE kl.ID = " . $id);
		while ($cat = mysql_fetch_row($result)) {
			
			$name = $cat[1];
			$id_pr = $cat[3];
			$flags = $cat[2];
			
			while ($flags == "0") {
				$names = fun_name($id_pr);
				$name = $names;
				$flags = fun_flag($id_pr);
				
				$id_new = fun_parent($id_pr);
				$id_pr = $id_new;
			}
		}
		
		return $name;
	}



    // для плоттерной резки формируем список операций
    $sql = "SELECT GROUP_CONCAT(operations.ID) IDs, operations.OPERATION_NAME 
            FROM operations WHERE operations.OperationType=" . CONSTANTS::OPERATIONS_TYPE_PLOTTER_CUT . " 
            GROUP BY operations.OPERATION_NAME";
    $plottCut = new classes_NotVariable($sql);
    $str_plottCut = "<option value=''>Выберите</option>";
    if(!empty($plottCut->List)) {
        foreach ($plottCut->List as $k => $v) {
            $str_plottCut .= "<option value='" . $v->IDs . "'>" . $v->OPERATION_NAME . "</option>";
        }
    }
	
	
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<link rel="icon" href="../../favicon.png" type="image/png">
		<title>Добавление нового заказа</title>
		<link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		<link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<link href="../../vendor/bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css">
		<link href="../../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
		<script src="../../vendor/jquery/jquery.js"></script>
		<script src="../../js/funJs.js?version=2"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<style>
			.modal-lg {
			height: 90%;
			width: 90%;
			margin-left: 5%;
			overflow-y: auto;
			}
			
			.modal-lgd {
			height: 100%;
			width: 70%;
			margin-left: 15%;
			overflow-y: auto;
			}
			
			.modal-lg1 {
			height: 50%;
			width: 50%;
			margin-left: 25%;
			overflow-y: auto;
			}
		</style>
	</head>
	<body>
		<a href="../orders.php">
			<button type="button" class="btn btn-default"><i class="fa fa-reply fa-fw"></i> К ЗАКАЗАМ</button>
		</a>

        <script>
            var str_plottCut = "<?php echo $str_plottCut; ?>";
        </script>

		<div class='container'>
			<div class='masthead'>
				<div class="alert alert-danger" id='err' style="display: none;">
					<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
					<a href="#" class="close" id="close">×</a>
					<strong>Ошибка!</strong> Не выбран ни один продукта в счете или уже в работе!
				</div>
				<?php
					include '../db.php';
					
					$query = "select * from PR_OPER";
					$result = mysql_query($query) or die($query);
					$iddd = '';
					while ($row = mysql_fetch_row($result)) {
						if ($row[3] == 1) {
							$iddd = $iddd . $row[0] . ",";
						}
					}
					$iddd = substr($iddd, 0, -1);
					$query = "select val from settings s where  s.id = 11";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) {
						$nadb_f = $row[0];
					}
					
					$query = "select val from settings s where  s.id = 2";
					$result = mysql_query($query) or die($query);
					WHILE ($row = mysql_fetch_row($result)) {
						$kurs = $row[0];
					}
					IF ($kurs == '') {
						$kurs = 2.1;
						} ELSE {
						$kurs = str_replace(',', '.', $kurs);
					}
					$kurs1 = $kurs;
					
					$query = "select val from settings s where  s.id = 15";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) {
						$nadb_mat = $row[0];
					}
					
					$query = "select val from settings s where  s.id = 4";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) {
						$nds = $row[0];
					}
					IF ($nds == '') {
						$nds = 0;
						} ELSE {
						$nds = str_replace(',', '.', $nds);
					}
					
					$nds_ = 1 + $nds / 100;
					
					$idAcct = $_GET['id'];
					$total_KOL = 0;
					$query = "select COUNT(*) from tn_list_par where order_id = " . $idAcct . " and del = 0";
					$result = mysql_query($query) or die($query);
					if ($row = mysql_fetch_row($result)) {
						$tn_count = $row[0];
						
					}
					
					
					$result = mysql_query('select 
					tt1.sum_all,  
					tt1.sum_no, 
					tt1.sum_job, 
					tt1.sum_rdy, 
					tt1.tn, 
					tt1.opl + tt1.opl2, 
					ROUND((sum_job + sum_rdy) / count_sum_job,2) sull
					from 
					(select  
					IF (sum(tt.sum_all) IS NULL  , 0 , sum(tt.sum_all) ) sum_all, 
					IF (sum(tt.sum_no) IS NULL  , 0 , sum(tt.sum_no) ) sum_no, 
					IF (sum(tt.sum_job) IS NULL , 0 , sum(tt.sum_job) ) sum_job, 
					IF (sum(tt.sum_rdy) IS NULL , 0 , sum(tt.sum_rdy) ) sum_rdy, 
					IF (sum(tt.tn) IS NULL , 0 , sum(tt.tn) ) tn, 
					IF (sum(tt.opl) IS NULL , 0 , sum(tt.opl) ) opl , 
					
					IF (sum(tt.count_all) IS NULL , 0 , sum(tt.count_all) ) count_all, 
					IF ( sum(tt.count_sum_no) IS NULL , 0 , sum(tt.count_sum_no) ) count_sum_no, 
					IF (sum(tt.count_sum_job) IS NULL , 0 , sum(tt.count_sum_job) ) count_sum_job,
					IF (ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = 0 and opl.CLIENT_ID =  tt.CLIENT_ID GROUP BY opl.ORDER_NUM),2) IS NULL , 0 , 
					ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = 0 and opl.CLIENT_ID =  tt.CLIENT_ID GROUP BY opl.ORDER_NUM),2)) opl2
					
					from (
					
					select 
					(SELECT u.USER_FIO FROM users u WHERE u.USER_LOGIN = o.user_id) NAME, 
					o.user_id, 
					o.CLIENT_ID,
					ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER GROUP BY op.ORDER_ID ),2) sum_all,
					IF (ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2)  IS NULL AND 
					ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = o.NUMBER GROUP BY opl.ORDER_NUM),2) IS NULL  , 
					IF (ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND op.status = ""  GROUP BY op.ORDER_ID ),2) IS NULL,0,
					ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND op.status = ""  GROUP BY op.ORDER_ID ),2)) , 0) sum_no,
					ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND op.status <> ""  AND op.status <> "2" AND op.status <> "3" GROUP BY op.ORDER_ID ),2) sum_job,
					IF (ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND (op.status = "2" OR op.status = "3") GROUP BY op.ORDER_ID ),2) IS NULL,
					/*IF (ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2) IS NULL,
					ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = o.NUMBER GROUP BY opl.ORDER_NUM),2),
					ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2)),*/ 0,
					ROUND((select SUM(ROUND(op.price / 1.2,2 ) * 1.2 * op.TOTAL) from order_product op where op.ORDER_ID = o.NUMBER AND (op.status = "2" OR op.status = "3") GROUP BY op.ORDER_ID ),2)) sum_rdy,
					ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2) tn ,
					ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = o.NUMBER GROUP BY opl.ORDER_NUM),2) opl,
					
					IF ((select count(*) from order_product op where op.ORDER_ID = o.NUMBER GROUP BY op.ORDER_ID ) IS NULL, 0 , (select count(*) from order_product op where op.ORDER_ID = o.NUMBER GROUP BY op.ORDER_ID ))  count_all,
					
					IF (ROUND((select SUM(tn.summ) from tn_list_par   tn where tn.order_id = o.NUMBER AND tn.del <> 1  GROUP BY tn.order_id),2)  IS NULL AND 
					ROUND((select SUM( opl.ALL_SUM) from oplati   opl where opl.ORDER_NUM = o.NUMBER GROUP BY opl.ORDER_NUM),2) IS NULL  , 
					(select count(*) from order_product op where op.ORDER_ID = o.NUMBER AND op.status = ""  GROUP BY op.ORDER_ID ) , 0) count_sum_no,
					
					(select count(*) from order_product op where op.ORDER_ID = o.NUMBER AND op.status <> ""  ) count_sum_job
					
					
					from
					(select  o.user_id , o.NUMBER,o.CLIENT_ID
					from 
					orders o 
					where o.CLIENT_ID = (select o1.CLIENT_ID from orders o1 where o1.number ='. $idAcct . ' LIMIT 1) AND DATE_OR >= "2017-12-01" ) o ) tt  ) tt1');
					if ($row = mysql_fetch_row($result)) {
						$balaceJob = $row[2]; 
						$balaceRdy = $row[3]; 
						$balaceTN =  $row[4]; 
						$balaceOpl = $row[5]; 
						$balaceSum = ROUND($balaceOpl - $balaceTN,2);
						IF ($balaceTN == 0) {
							$balaceSum = ROUND($balaceOpl - $balaceRdy,2) ;
						}
					}


                    $firm_parent = 1;
					// if($idAcct != ''){
					//$query = "select o.NUMBER, c.CLIENT_NAME, o.status_id , c.id , c.email, c.NADBAVKA, o.CUR_ID from orders o, clients c where o.number =" . $idAcct . " and c.id = o.CLIENT_ID; ";
					$query = "SELECT t1.NUMBER, t1.CLIENT_NAME, t1.status_id , t1.id , t1.email, t1.NADBAVKA, t1.CUR_ID,t2.USER_FIO, t1.parent_company
								FROM
									(SELECT o.NUMBER, c.CLIENT_NAME, o.status_id , c.id , c.email, c.NADBAVKA, o.CUR_ID,o.USER_ID,o.parent_company FROM orders o, clients c WHERE o.number =" . $idAcct . " AND c.id = o.CLIENT_ID) t1
								LEFT JOIN
									(SELECT USER_LOGIN,USER_FIO FROM users) t2
								ON t1.USER_ID=t2.USER_LOGIN";
					
					$result = mysql_query($query) or die($query);
					if ($row = mysql_fetch_row($result)) {
						$CLIENT_NAME = $row[1];
						$email = $row[4];
						$nadb = $row[5];
						$CUR_ID = $row[6];
						$username = $row[7];
                        $firm_parent = $row[8];
						
						$client_id = $row[3];
						$sum_acct = 0;

						if($firm_parent == 2) {
                            echo '<div style="width: 381px; height: 300px; background-image: url(\'proc/logoM.png\'); position: absolute; right: 20%; top: 0; background-size: cover;" onclick="selectedFirm(' . $idAcct . ')"></div>';
                        } else {
                            echo '<div style="width: 300px; height: 300px; background-image: url(\'proc/logo.jpg\'); position: absolute; right: 20%; top: 0; background-size: cover;" onclick="selectedFirm(' . $idAcct . ')"></div>';
                        }

						echo "<h3 class='text-muted' style='margin-left: 20%;'>Счет № $row[0]</h3><br/>";
						echo "<h4 class='text-muted'>Плательщик $row[1]</h4>";
						//echo '<div class="alert alert-info">';
						echo '<div style="color: #777">';
						echo "<h4 class='text-muted'>МЕНЕДЖЕР: $username <br><br>БАЛАНС: $balaceSum <br> &nbsp; &nbsp; &nbsp; В работе $balaceJob; <br>&nbsp; &nbsp; &nbsp; Готовы $balaceRdy; <br>&nbsp; &nbsp; &nbsp; Выписаны ТН $balaceTN; <br>&nbsp; &nbsp; &nbsp; Оплаты $balaceOpl </h4>";
						echo '</div>';
						$query1 = "select TRUNCATE(sum(ost_sum),2), TRUNCATE(sum(all_sum),2) from oplati where client_id =" . $client_id;
						$result1 = mysql_query($query1) or die($query);
						while ($row3 = mysql_fetch_row($result1)) {
							$sum = $row3[1] - $row3[0];
						}
						
						if ($tn_count == '0') {
							echo ' <button type="button" class="btn btn-warning" id = "add">Добавить продукт</button>';
						}
						if ($admin == '4' OR $login == '026' OR $login == '030' OR $login == '030' or $login == '033'){
							echo ' <button type="button" class="btn btn-fancy"   id = "post">Доставка <span class="fa fa-car"> </span></button>';
						}
						echo "</div> <br/>";
						echo "<table class='table  table-bordered' ><thead>
						<tr><td></td><td></td><td>Наименование продукта</td><td>Кол-во</td><td>Ед. изм.</td><td>Стоимость без НДС</td><td>Цена</td><td>Статус</td>			<td></td>					
						</tr></thead>";
						$query = "select id,p_names, total, price, summ,product_id,ORDER_ID,DIZ,sum_press, units, status,	num_prod_ord  from order_product where order_id =" . $idAcct;
						$result = mysql_query($query) or die($query);
						echo "<tbody>";
						$json_tran = Array();
						while ($row = mysql_fetch_array($result)) {
							if ($total_KOL < (int)$row[11]) {
								$total_KOL = (int)$row[11];
							}
							$summ = 0;
							$summ_no_nds = 0;
							$sum_nds = 0;
							$price = 0;
							$total = $row[2];
							$summ = $row[4];
							$price = round($row[3], 2) / $nds_;
							$price = round($price, 2);
							$summ = $total * round($price, 2) * $nds_;
							$summ = round($summ, 2);
							
							$sum_prod = $row[4] + $row[7] + $row[8];
							$sum_acct = $sum_acct + $summ;
							$st = "<input type='checkbox' name='tran' value='".$row[0]."'>";
							$json_tran[] = array(
							'id' => $row[0],
							'chek' => $st,
							'name' => $row[1]
							);
							echo "<tr class='odd gradeX'>";
							echo "<td>";
							//	if ($tn_count == '0') {
							echo "<input type='checkbox' name='chDel' value='" . $row[0] . "'>";
							
							echo "<input type='hidden' id='status" . $row[0] . "' value='" . $row[10] . "'>";
							
							//}
							echo "</td>";
							echo "<td>$row[11]</td>";
							echo "<td>$row[1]</td>";
							echo "<td>$row[2]</td>";
							echo "<td>$row[9]</td>";
							echo "<td>" . number_format(($row[3] / $nds_), 2, ',', ' ') . "</td>";
							// echo "<td>".number_format($row[7], 2, ',', ' ')."</td>";
							// echo "<td>".number_format($row[8], 2, ',', ' ')."</td>";
							echo "<td>" . number_format($summ, 2, ',', ' ') . "</td>";
							switch ($row[10]) {
								case '3':
								echo "<td><span class='label label-success '>Сдано заказчику</span><td>";
								break;
								case '4':
								echo "<td><span class='label label-danger '>Брак/Отказ</span><td>";
								break;
								case '20':
								echo "<td><span class='label label-danger '>Возврат</span><td>";
								break;
								case '21':
								echo "<td><span class='label label-danger '>Возврат</span><td>";
								break;
								case '0':
								echo "<td><span class='label label-danger '>Возврат</span><td>";
								break;
								case '1':
								echo "<td><span class='label label-info '>в цеху</span><td>";
								break;
								case '2':
								echo "<td><span class='label label-success'>Готово</span><td>";
								break;
								case '10':
								echo "<td><span class='label label-primary'>Дизайн</span><td>";
								break;
								
								case '11':
								if ($row[4] == '1') {
									echo "<td><span class='label label-primary '>Препресс</span><td>";
									break;
									} else {
									echo "<td><span class='label label-primary '>Ожидание препресса</span><td>";
									break;
								}
								case '12':
								if ($row[4] == '1') {
									echo "<td> <span class='label label-info'>Печатается</span><td>";
									break;
									} else {
									echo "<td> <span class='label label-info'>Ожидание печати</span><td>";
									break;
								}
								default:
								echo "<td><td>";
								break;
							}
							
							
							if ($tn_count == '0') {
								if ($row[10] == "" OR $row[10] == "0"   OR $row[10] == "20" OR $row[10] == "21" OR $admin == '4') {
									echo "<a onClick='_reviewAcctProduct1($row[0])'><button type= 'button' class='btn btn-warning'><span class='glyphicon glyphicon-pencil'></span></button></a> ";
									if ($row[10] == "") {
										echo "<a onClick='_deleteAcctProduct($row[0],$idAcct)'><button type= 'button' class='btn btn-danger'><span class='glyphicon glyphicon-trash'></span></button></a>";
									}
									} else {
									echo "<a onClick='_reviewAcctProduct2($row[0])'><button type= 'button' class='btn btn-warning'><span class='glyphicon glyphicon-pencil'></span></button></a> ";
								}
								
								
							}
							if ($tn_count != '0') {
								IF ($admin == '4') {
									
									echo "<a onClick='_reviewAcctProduct1($row[0])'><button type= 'button' class='btn btn-warning'><span class='glyphicon glyphicon-pencil'></span></button></a> ";
									
									} else {
									echo "<a onClick='_reviewAcctProduct2($row[0])'><button type= 'button' class='btn btn-danger'><span class='glyphicon glyphicon-pencil'></span></button></a> ";
								}
							}
							
							if($admin == '4' || $login == '026') {
								echo "<a onClick='trasferToReportArtliner($row[0])' style='margin-left: 5px;'><button type= 'button' class='btn btn-secondary'><span class='glyphicon glyphicon-chevron-right'></span></button></a> ";
							}
									
						}
						echo "</td></tr>";
					}
					echo "<tr class='odd gradeX'>	
					<td colspan='6'>Итого:</td>
					<td colspan='3' >" . number_format($sum_acct, 2, ',', ' ') . "</td>";
					// }
					
					echo "</tr></tbody></table>";

					$query = "select limits,size_pre from clients where id =" . $client_id;
					$result = mysql_query($query) or die($query);
					if ($row = mysql_fetch_row($result)) {
						$limits = $row[0];
						$size_pre = $row[1];

					} ?>
					
					<div class='row'>
						<div class="col-md-2">
							<div class="btn-group">
								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
									Документы
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
                                    <?php if($firm_parent == 1) { ?>
                                        <li><a href='proc/temp_doc_client.php?id=<? echo $idAcct ?>'>Договор</a></li>
                                        <li><a onclick="ShowFormAddCost(<? echo $idAcct ?>)">Разовый договор</a></li>
                                        <li><a href='proc/acct_prot.php?id=<? echo $idAcct ?>' target='_blank'>Счет-протокол</a></li>
                                        <li><a href='proc/acct_prot5.php?id=<? echo $idAcct ?>' target='_blank'>Счет-протокол(БЕЗ
                                        НДС)</a></li>
                                        <li><a href='proc/acct_prot2.php?id=<? echo $idAcct ?>' target='_blank'>Счет-протокол(с
                                        печатью)</a></li>
                                        <li><a href='proc/acct_prot4.php?id=<? echo $idAcct ?>' target='_blank'>Счет-протокол(На один
                                        листик)</a></li>
                                        <li><a href='_list_tn.php?id=<? echo $idAcct ?> ' target='_blank'>ТН</a></li>
                                        <li><a href='_list_tn1.php?id=<? echo $idAcct; ?>' target='_blank'>TТН</a></li>
                                    <?php } else if($firm_parent == 2) { ?>
                                        <li><a href='proc/temp_doc_client_m.php?id=<? echo $idAcct ?>'>Договор</a></li>
                                        <li><a onclick="ShowFormAddCost(<? echo $idAcct ?>, true)">Разовый договор</a></li>
                                        <li><a href='proc/acct_prot5_m.php?id=<? echo $idAcct ?>' target='_blank'>Счет-протокол(БЕЗ
                                                НДС)</a></li>
                                        <li><a href='proc/acct_prot2_m.php?id=<? echo $idAcct ?>' target='_blank'>Счет-протокол(с
                                                печатью)</a></li>
                                        <li><a href='proc/acct_prot4_m.php?id=<? echo $idAcct ?>' target='_blank'>Счет-протокол(На один
                                                листик)</a></li>
                                        <li><a href='_list_tn_m.php?id=<? echo $idAcct ?> ' target='_blank'>ТН</a></li>
                                        <li><a href='_list_tn1_m.php?id=<? echo $idAcct; ?>' target='_blank'>TТН</a></li>
                                    <?php } ?>
									<? //if ($admin == '4' ) { echo "<li><a href='_list_tn1.php?id=<? echo $idAcct  ' target='_blank'>TТН</a></li>"; } ?>
									<? $query = "select count(*) from tn_list_par where order_id = " . $idAcct . " and type = 'act' and del = 0";
										$result = mysql_query($query) or die($query);
										$act_flag = 0;
										if ($row = mysql_fetch_row($result)) {
											$act_flag = $row[0];
										}
										
										if ($act_flag == '0') {
											echo "<li><a onclick='showacct(" . $idAcct . ")'>АКТ услуг</a></li>";
											echo "<li><a onclick='showacct1(" . $idAcct . ")'>АКТ услуг БЕЗ НДС</a></li>";
										} ?>
										<?php if(in_array(1, $ACCESS_ROLES)) { ?>
										<li><a onclick="javascript: window.open('/www/core/print.php?m=orders&u=ordersCheck&a=printOrderCheck&idAcct=<? echo $idAcct; ?>', 'Товарный чек');" style="cursor: pointer">Товарный чек</a></li>
										<?php } ?>
										<li><a onclick='deltn()'>Удаление ТН</a></li>
										<li><a onclick="func_mail(<? echo $idAcct ?>,<? echo $client_id ?>,'<? echo $email ?>', <? echo $firm_parent; ?>)">Отправить
										на Email</a></li>
										<li><a  onclick="blank_print('<? echo $idAcct ?>')" >Бланк</a></li>
										<li><a onclick="get_acct_tran('<? echo $idAcct?>')">Перемещение</a></li>
										
								</ul>
							</div>
						</div>
						<div class="col-md-8"></div>
						<div class="col-md-2"><? if ($tn_count == '0') {
							IF ($stat == 0) {
								echo '<button type="button" data-dismiss="modal" class="btn btn-success" onclick="get_job(' . $idAcct . ')">Отправить в работу</button></div>';
							}
						} ?>
						</div>
					</div>
			</div>
			
			<!------------------------------------------act --------------------------------------------------------------->
			<div id="myacct" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" data-keyboard="false"
         style="display: none;">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title">АКТ</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-2">
									<button type="button" id='confirm' class="btn btn-default">Печать</button>
								</div>
								
								<div class="col-md-10">
									<iframe id='fr' src="" width="100%" height="700" align="left">
										Ваш браузер не поддерживает плавающие фреймы!
									</iframe>
								</div>
							</div>
							
							
							<div class="modal-footer">
								<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!--------------------------------------------------------------------------------------------------------->
			
			<!------------------------------------------act1 --------------------------------------------------------------->
			<div id="myacct1" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" data-keyboard="false"
         style="display: none;">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title">АКТ</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-2">
									<button type="button" id='confirm1' onclick= 'print_act_no_nds()' class="btn btn-default">Печать</button>
								</div>
								
								<div class="col-md-10">
									<iframe id='fr1' src="" width="100%" height="700" align="left">
										Ваш браузер не поддерживает плавающие фреймы!
									</iframe>
								</div>
							</div>
							
							
							<div class="modal-footer">
								<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!--------------------------------------------------------------------------------------------------------->
			
			<!------------------------------------------acct_tranc --------------------------------------------------------------->
			<div id="acct_tranc" class="modal fade bd-example-modal-lg"  tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title">ПЕРЕМЕЩЕНИЕ</h4>
						</div>
						<div class="modal-body" >
							
							<?
								echo '<div class="row">	
								<div class="col-md-1"></div>	
								<div class="col-md-1">Флаг</div>										
								<div class="col-md-3">Наименование</div>
								<div class="col-md-3">Номер счета</div>
								</div>
								<div class="row">	
								<div class="col-md-1">&nbsp;</div>	
								<div class="col-md-1">&nbsp;</div>										
								<div class="col-md-3">&nbsp;</div>
								<div class="col-md-3">&nbsp;</div>
								</div>';
								FOR($i = 0; $i < count($json_tran); $i++ ){
									echo '<div class="row">	
									<div class="col-md-1"></div>	
									<div class="col-md-1">'.$json_tran[$i]['chek'].'</div>										
									<div class="col-md-3" id = "namsse'.$json_tran[$i]['id'].'" >'.$json_tran[$i]['name'].'</div>
									<div class="col-md-3"><input  id = "new_acct'.$json_tran[$i]['id'].'" type="text" value = ""/></div>
									</div>';
									//	echo $json_tran[$i]['id']." ".$json_tran[$i]['chek']." ".$json_tran[$i]['name']."<br>";
								}
								
								
							?>
							<div class="row">
								<div class="col-md-2"></div>
								
								<div class="col-md-10"></div>
							</div>
							
							
							
							<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
							<button type="button" class="btn btn-primary" onclick='tran_()'>Переместить</button></div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			<!------------------------------------------Удаление ТН--------------------------------------------------------------->
			<div id="mydeltn" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false"
         style="display: none;">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title">Удаление ТН</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<div class="col-md-2"></div>
								
								<div class="col-md-5"><label>Номер ТН</label></div>
							</div>
							
							<? $query = "select id, num_tm from tn_list_par where order_id = " . $idAcct . " and del = 0";
								$result = mysql_query($query) or die($query);
								while ($row = mysql_fetch_row($result)) {
									echo '<div class="row">
									<div class="col-md-2"></div>
									<div class="col-md-5"><div class="checkbox">
									<label>
									<input type="checkbox" name="chDel2" value=' . "'" . $row[0] . "'" . '>' . $row[1] . '
									</label>
									</div>
									</div>
									</div>';
									
								} ?>
								
								
								<div class="modal-footer">
									<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
									<a onclick="fun_del_tn()">
										<button type="button" class="btn btn-primary">Удалить</button>
									</a></div>
						</div>
					</div>
				</div>
			</div>
			<!------------------------------------------Доставка--------------------------------------------------------------->
			<div id="form_post" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false"
         style="display: none;">
				<div class="modal-dialog modal-lgd">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title">Доставка</h4>
						</div>
						<div class="modal-body">
							
							
							<div class="row">
								<div class="col-md-2"><label>Вид</label></div>
								<div class="col-md-10">
									<select name="view_post" id="view_post" class="form-control">
										
										<option>БЕЛПОЧТА</option>
									</select>  
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2"><label>ФИО</label></div>
								<div class="col-md-10">
									<input type="text" id='post_fio' class="form-control" onKeyUp="this.value = this.value.replace (/[^a-zA-Zа-яА-ЯёЁ .]/, '')">
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2"><label>Область</label></div>
								<div class="col-md-10">
									<select name="region_id" id="region_id" class="form-control">
										<option value=''>Выберите область</option>
										<option>Брестская обл.</option>
										<option>Витебская обл.</option>
										<option>Гомельская обл.</option>
										<option>Гродненская обл.</option>
										<option>Минская обл.</option>
										<option>Могилевская обл.</option>
									</select>  
								</div>
							</div>
							<div class="row">
								<div class="col-md-2"><label>Район</label></div>
								<div class="col-md-10">
									<input type="text" id='post_raion' class="form-control">
								</div>
							</div>
							<div class="row">
								<div class="col-md-2"><label>Населенный пункт</label></div>
								<div class="col-md-10">
									<input type="text" id='post_city' class="form-control">
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2"><label>Улица</label></div>
								<div class="col-md-10">
									<div class="input-group ">
										<span class="input-group-addon">ул.</span>
										<input type="text" id='post_street' class="form-control">
									</div>
								</div>
							</div>
							
							
							<div class="row">
								<div class="col-md-2"><label>Дом</label></div>
								<div class="col-md-10">
									<div class="input-group ">
										<span class="input-group-addon">д.</span>
										<input type="text" id='post_house_num' class="form-control" >
									</div>
								</div>
							</div>
							
							
							<div class="row">
								<div class="col-md-2"><label>Корпус</label></div>
								<div class="col-md-10">
									<div class="input-group ">
										<span class="input-group-addon">корпус.</span>
										<input type="text" id='post_house_kor' class="form-control" >
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2"><label>Квартира</label></div>
								<div class="col-md-10">
									<div class="input-group ">
										<span class="input-group-addon">кв.</span>
										<input type="text" id='post_room' class="form-control" >
									</div>
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2"><label>Индекс</label></div>
								<div class="col-md-10">
									<input type="text" id='post_index' class="form-control"  placeholder="224000" onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')">
								</div>
							</div>
							
							
							<div class="row">
								<div class="col-md-2"><label>Телефон</label></div>
								<div class="col-md-10">
									<input type="text" id='post_phone' class="form-control" >
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2"><label>Цена</label></div>
								<div class="col-md-10">
									
									<input type="text" id='post_price' class="form-control" onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')">
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2"><label>Трек-код</label></div>
								<div class="col-md-10">
									
									<input type="text" id='post_track' disabled class="form-control" >
								</div>
							</div>
							
							<div class="row">
								<div class="col-md-2"><label>Дата отправки</label></div>
								<div class="col-md-10">
									<input type="date" id='post_date' class="form-control" onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')">
								</div>
							</div>
							<div class="row">
								<div class="col-md-2"><label>Email для сообщения</label></div>
								<div class="col-md-10">
									
									<input type="text" id='post_mail' class="form-control" >
								</div>
							</div>
							
							
							<div class='row'>
								<div class='col-md-2'> <input type="button" class="btn btn-default form-control" value="Добавить" style="margin: 10px" onclick="addRow_post()"   ></div>
								<div class='col-md-2'> <input type="button" class="btn btn-default form-control" value="Удалить" style="margin: 10px" onclick="del_row_post()"  ></div>
							</div>
							
							<div>
								<table width='100%' name = 'post_inf' id = "post_inf" class="table table-striped table-bordered table-hover " id="contact_cl" >
									<thead>
										<tr>
											<th scope="col">Del <i class='glyphicon glyphicon-trash'></i> </th>
											<th scope="col">Описание</th>
											<th scope="col">Кол-во мест</th>
											<th scope="col">Вес, кг</th>
											<th scope="col">Длина, см</th>
											<th scope="col">Ширина, см</th>
											<th scope="col">Высота, см</th>
										</tr>
									</thead>
									<tbody>
										<tr>
										</tr>
									</tbody>
								</table>		
								
							</div>
							
							
							
							<div id='info_dost'>
								
							</div>
							
							
							
							
							
						</div>
						<div class="modal-footer">
							<input type="hidden" id="post_kol" value = '0'>
							<button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
							<button type="button" class="btn btn-primary" onclick="add_post()">Добавить</button>
						</div>
					</div>
				</div>
			</div>
			<!--------------------------------------------------------------------------------------------------------->
			<?
				//$query = "select id from equipment where l_use = 1";
                $query = "SELECT equipment.*
                            FROM equipment
                            INNER JOIN equipment_operation ON equipment.ID=equipment_operation.EQUIPMENT_ID AND equipment_operation.OPERATION_TYPE=1
                            WHERE equipment.l_use=1
                            ORDER BY equipment.EQ_NAME";
				$result = mysql_query($query) or die($query);
				$srt_eq = '';
				while ($row = mysql_fetch_row($result)) {
					
					$srt_eq .= $row[0] . ";";
					
				}
				$srt_eq = substr($srt_eq, 0, -1);
				
			?>
			
			
			
			
			<div style="display:none;">
				<div class="row">
					<?
						$srt_eq1 = str_replace(";", ",", $srt_eq);
						
						echo "<select id = 'p_eq_i' " . ' style="width:90%;"' . " onchange=" . '"' . "rts('p_eq_1','p_color_1','p_sizep_1','p_mat_1' )" . '"' . "> <option value='0' title='0'>Выберите</option>";
						//  $ID_EQ = explode(";", $srt_eq);
						//$name_eq = "";
						//  FOR ($g = 0; $g < count($ID_EQ); $g++) {
                        $combi_array = array();

						$query = "select id, eq_name, l_use, l_offset, ladnr, uandd, nadb_max,nadb_min,total_max,total_min, oper, IF(equipment.methodCalcPriceOperation IS NOT NULL, 1, 0) as mcpo from equipment where id in (" . $srt_eq1 . ") and  eq_name is not null ORDER BY eq_name;";
						$result = mysql_query($query) or die($query);
						WHILE ($row = mysql_fetch_row($result)) {
							
							echo "<option value='$row[0]' " . ' style="display:block;"' . " data-combi='" . $row[11] . "' data-flag-offset='".$row[3]."' data-nadb_max='" . $row[6] . "' data-nadb_min='" . $row[7] . "' data-total_max='" . $row[8] . "' data-total_min='" . $row[9] . "'>$row[1]</option>";
							
							$name_eq = $row[10];
							$query1 = "select  o.par, o.OPERATION_PRICE, o.id, o.OperationType from operations o where FIND_IN_SET(o.id ,'" . $row[10] . "')";
							$result1 = mysql_query($query1) or die($query1);
							WHILE ($row1 = mysql_fetch_row($result1)) {
							    if($row1[3] == 3) {
                                    $combi_array[$row[0]][] = array(
                                        'id' => $row1[2],
                                        'name' => $row1[0]
                                    );
							        continue;
                                }
								$rqw = $rqw . "<option value='$row1[2]'  data-attr='$row[0]' title = '$row1[1]' " . ' style="display:none;"' . ">$row1[0]</option>";
							}
						}
						// }
						echo "</select>";

						echo "<script> var COMBI_OBJECT = {}; </script>";
						foreach ($combi_array as $k => $combi) {
                            echo "<script> var arr_tmp_combi = new Array(); </script>";
                            foreach ($combi as $_k => $_v) {
                                echo "<script> 
                                        var tmp_combi = {
                                            'id': '" . $_v['id'] . "',
                                            'name': '" . $_v['name'] . "'
                                        };
                                        arr_tmp_combi.push(tmp_combi);
                                        </script>";
                            }
                            echo "<script> COMBI_OBJECT.combi" . $k . " = arr_tmp_combi; </script>";
                        }
						
					?>
				</div>
				<div class="row">
					<?
						echo "<select id = 'p_color_i' " . ' style="width:90%;"' . "> <option value='0' data-attr='0' title='0'>Выберите</option>";
						
						echo $rqw;
						
						
						echo "</select>";
					?>
				</div>
				<div class="row">
					<?
						echo "<select id = 'p_sizep_i'" . ' style="width:90%;"' . " onchange=" . '"' . "rts1('p_sizep_1','p_mat_1','p_eq_1' )" . '"' . "> <option value='0' title='0'>Выберите</option>";
						
						// FOR ($g = 0; $g < count($ID_EQ); $g++) {
						
						$query = "SELECT s.SIZE ,e1.id, s.id  FROM size_print s, (select e.id, e.format from equipment e  WHERE e.id in (" . $srt_eq1 . ")) e1 WHERE FIND_IN_SET(s.ID,e1.format)";
						$result = mysql_query($query) or die($query);
						WHILE ($row = mysql_fetch_row($result)) {
							echo "<option value='$row[0]' data-attr = '$row[1]' data-attr-id = '$row[2]'" . ' style="display:none;"' . ">$row[0]</option>";
						}
						// }
						echo "</select>";
					?>
				</div>
				<div class="row">
					<?
						
						echo "<select id = 'p_mat_i'" . ' style="width:30%;"' . " > <option value='0' data-attr = '0' data-attr-size = '0' title='0|0*0'>Выберите</option>";
						$ID_EQ = explode(";", $srt_eq);
						
						// $name_eq = "";
						$query = " SELECT 
						ttr.ID, ttr.title, ttr.m_price, ttr.m_size, GROUP_CONCAT(ttr.dt3  SEPARATOR ',') idd, ttr.flags , ttr.parent   
						FROM
						(SELECT 
						kl.ID, kl.title, '' m_price, '' m_size, e1.id dt3, kl.flags , kl.parent  
						FROM  
						kl_mat kl, (select e.id, e.mater from equipment e WHERE e.id in (" . $srt_eq1 . ")) e1 
						WHERE FIND_IN_SET(kl.ID,e1.mater) ORDER BY kl.parent) ttr
						GROUP BY ttr.ID ORDER BY ttr.parent";
						
						
						// $query = "SELECT kl.ID, kl.title, '' m_price, '' m_size, e1.id, kl.flags , kl.parent  FROM  kl_mat kl, (select e.id, e.mater from equipment e  WHERE e.id in (" . $srt_eq1 . ")) e1 WHERE FIND_IN_SET(kl.ID,e1.mater) ORDER BY kl.parent";
						$result = mysql_query($query) or die($query);
						$flags == "-1";
						WHILE ($row = mysql_fetch_row($result)) {
							IF ($flags == "-1") {
								//   $name_eq = $name_eq . $row[1] . ";";
								$flags = fun_group($row[0]);
								$namess = fun_names($row[0]);
								
								echo "<optgroup label='$flags' name = 'optgr' style='display: none;'><option value='$row[0]' data-opt_gr = '$flags' data-attr = '$row[4]' data-attr-size = '$row[3]' title='$row[2]|$row[3]' " . ' style="display:none;"' . ">$namess</option>";
								} ELSE {
								
								IF ($flags != fun_group($row[0])) {
									$flags = fun_group($row[0]);
									//    $name_eq = $name_eq . $row[1] . ";";
									
									$namess = fun_names($row[0]);
									
									echo "</optgroup> <optgroup label='$flags' name = 'optgr' style='display: none;'>";
									echo "<option value='$row[0]' data-opt_gr = '$flags' data-attr = '$row[4]' data-attr-size = '$row[3]' title='$row[2]|$row[3]' " . ' style="display:none;"' . ">$namess</option>";
									
									} ELSE {
									//    $name_eq = $name_eq . $row[1] . ";";
									
									$namess = fun_names($row[0]);
									
									echo "<option value='$row[0]'  data-opt_gr = '$flags' data-attr = '$row[4]' data-attr-size = '$row[3]' title='$row[2]|$row[3]' " . ' style="display:none;"' . ">$namess</option>";
								}
							}
						}
						echo "</select>";
						
						
					?>
				</div>
				<div class="row">
					<select id='p_lam_i' style="width:90%;">
						<option value='0' title="0|0|0|0" style="display:block;" selected></option>
						<option value='1' title="0.2|0.2|0.4|0.2" style="display:block;">гл.+гл.</option>
						<option value='2' title="0.4|0.4|0.8|0.4" style="display:block;">мат+мат</option>
						<option value='3' title="0.1|0.1|0.2|0.1" style="display:block;">гл.+0</option>
						<option value='4' title="0.1|0.2|0.4|0.15" style="display:block;">мат+0</option>
						<option value='5' title="0.5|0.5|1|0.5" style="display:block;">цифра</option>
						<option value='6' title="0.3|0.3|0.6|0.3" style="display:block;">гл.+мат</option>
						<option value='7' title="0.4|0.4|0.6|0.4" style="display:block;">СофтТач+0</option>
						<option value='8' title="0.8|0.8|1|0.8" style="display:block;"> СофтТач+СофтТач</option>
						<option value='9' title="0.5|0.6|1|0.55" style="display:block;"> СофтТач+мат</option>
					</select>
				</div>
				
				<div class="row">
					<select id='p_diam_i' style="width:90%;">
						<option value='0' style="display:block;" selected></option>
						<option value='1' style="display:block;">3 мм</option>
						<option value='2' style="display:block;">4 мм</option>
						<option value='4' style="display:block;">5 мм</option>
						<option value='3' style="display:block;">6 мм</option>
					</select>
				</div>
				<div class="row">
					<select id='p_colorluv_i' style="width:90%;">
						<option value='0' style="display:block;" selected></option>
						<option value='1' style="display:block;">4 мм серебро</option>
						<option value='2' style="display:block;">4 мм золото</option>
						<option value='3' style="display:block;">4 мм черный</option>
						<option value='4' style="display:block;">5 мм золото</option>
						<option value='5' style="display:block;">5 мм серебро</option>
					</select>
				</div>
			</div>
			
			<!-- /#Модальные окна -->
			<div id="myModalprod3" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false"
         style="display: none;">
				<div class="modal-dialog  modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title" id="headerModalAddProduct">Добавление продукта</h4>
						</div>
						<div class="modal-body">
							<!-- Сообщение об ошибке -->
							<div class="alert alert-danger" id='err1' style="display: none;">
								<!-- Кнопка для закрытия сообщения, созданная с помощью элемента a -->
								<a href="#" class="close" id="close">×</a>
								<strong>Ошибка!</strong> Выберите продукт!
							</div>
							<form class='form-signin' method='post' action='_addOrProdsftest.php' enctype='multipart/form-data'
							id='forms'>
								<div class='row'>
									<div class="col-md-3"><label>Наименование продукции:</label></div>
									<div class="col-md-4">
										<select class="js-example-basic-single js-states form-control" id="directoryCodeStat" name="directoryCodeStat" tabindex='1'>
											<?
												$query = "SELECT dcs.ID id, dcs.name name, dcsf.comm comm
															FROM
																directoryCodeStat dcs
															LEFT JOIN
																directoryCodeStatFull dcsf
															ON dcs.code_stat=dcsf.code_stat
															WHERE dcs.deleteStatus=0
															ORDER BY dcs.name";
												$result = mysql_query($query) or die($query);
												while ($row = mysql_fetch_array($result)) {
													echo "<option value='" . $row['id'] . "' title='".$row['comm']."'> " . $row['name'] . "</option>";
												}
											?>
										</select>
										<script>
											$("#directoryCodeStat").select2({
												width: '100%',
												dropdownParent: $('#myModalprod3'),
												placeholder: 'Выберите вид товара'
											});
											document.getElementById("directoryCodeStat").selectedIndex = -1;
											document.getElementById("directoryCodeStat").dispatchEvent(new Event('change'));
										</script>
									</div>
								</div>
								<div class='row'>
									<div class='col-md-3'><label>Дополнительное название: </label></div>
									<div class='col-md-4'><input type='text' id='p_names' size='100%' style='width:100%' name='p_names' value=''
									onblur="get_price()" tabindex='1'></div>
								</div>
								<br>
								<div class='row'>
									
									<div class='col-md-3'>
										<div class="form-group"><label>Кол-во. изделий:</label><br>
										<label>Размер готового изделия:</label></div>
									</div>
									<div class='col-md-3'>
										<div class="form-group"><input type='text' id='p_cir' size='20' name='p_cir' value=''
											onblur="get_price()" tabindex='1'>
											<select id='unit_prod1' name='unit_prod1' tabindex='1' onblur="get_price()">
												<option value=''></option>
												<?
													$query = "select  * from units";
													$result = mysql_query($query) or die($query);
													
													while ($row = mysql_fetch_row($result)) {
														
														echo "<option value='" . $row[1] . "'> " . $row[1] . "</option>";
														
													}
												?>
											</select><br><input type='text' id='p_size' size='20' name='p_size' value=''
										onblur="get_price()" tabindex='1'></div>
									</div>
									
									<div class='col-md-2'>
										<div class="form-group"><label>Переплет</label><br><label>Сторона переплета:</label><br><label>Материал переплета:</label>
										</div>
									</div>
									<div class='col-md-3'>
										
										
										<div class="form-group">
											<select id='p_per_i' name="p_per" style="width:90%;" tabindex='1'>
												<option value='0' title="0|0|0|0" style="display:block;"></option>
												<option value='1' title="0.3|0.4|0.7|0.4" style="display:block;">пружина 6,4
													мм
												</option>
												<option value='14' title="0.6|0.8|1.4|0.8" style="display:block;">2 пружины 6,4
													мм
												</option>
												<option value='15' title="0.9|1.2|2.1|1.2" style="display:block;">3 пружины 6,4
													мм
												</option>
												<option value='2' title="0.4|0.45|0.8|0.45" style="display:block;">пружина 8,0
													мм
												</option>
												<option value='16' title="0.8|0.9|1.6|0.9" style="display:block;">2 пружина 8,0
													мм
												</option>
												<option value='17' title="1.2|1.35|2.4|1.35" style="display:block;">3 пружина
													8,0 мм
												</option>
												<option value='3' title="0.5|0.55|0.9|0.55" style="display:block;">пружина 9,5
													мм
												</option>
												<option value='4' title="0.55|0.6|1|0.6" style="display:block;">пружина 11,0
													мм
												</option>
												<option value='5' title="0.6|0.65|1.1|0.65" style="display:block;">пружина 12,7
													мм
												</option>
												<option value='6' title="0.65|0.7|1.5|0.7" style="display:block;">пружина 14,3
													мм
												</option>
												<option value='7' title="0.05|0.1|0.2|0.1" style="display:block;">скоба</option>
												<option value='8' title="3.5|5|6|4" style="display:block;">Твердая обложка
													(PUR)
												</option>
												<option value='9' title="3.5|5|6|4" style="display:block;">Твердая обложка
													(скобы)
												</option>
												<option value='10' title="3.5|5|6|4" style="display:block;">Твердая обложка
												</option>
												<option value='11' title="3.5|5|6|4" style="display:block;">Твердая обложка
													(пружина)
												</option>
												<option value='12' title="0.5|0.7|1|0.5" style="display:block;">термоклей
												</option>
												<option value='13' title="0.5|0.7|1|0.5" style="display:block;">нитка</option>
												
											</select><br><select id='p_stor_i' name="p_stor" style="width:90%;"
											onblur="get_price()" tabindex='1'>
												<option style="display:block;"></option>
												<option style="display:block;">узкой</option>
												<option style="display:block;">широкой</option>
											</select>
											<br><select id='p_per_mat_i' name="p_per_mat" style="width:90%;"
											onblur="get_price()"tabindex='1'>
												<option style="display:block;" ></option>
												<?
													$query = "			
													SELECT 
													ttr.ID, ttr.title, ttr.m_price, ttr.m_size, '8,9,10,11' idd, ttr.flags , ttr.parent   
													FROM
													(SELECT 
													kl.ID, kl.title, '' m_price, '' m_size, kl.flags , kl.parent  
													FROM  
													kl_mat kl where kl.id = 428 OR kl.id = 429 OR kl.id = 430
													ORDER BY kl.title) ttr
													GROUP BY ttr.ID ORDER BY ttr.title";
													
													
													// $query = "SELECT kl.ID, kl.title, '' m_price, '' m_size, e1.id, kl.flags , kl.parent  FROM  kl_mat kl, (select e.id, e.mater from equipment e  WHERE e.id in (" . $srt_eq1 . ")) e1 WHERE FIND_IN_SET(kl.ID,e1.mater) ORDER BY kl.parent";
													$result = mysql_query($query) or die($query);
													$flags == "-1";
													WHILE ($row = mysql_fetch_row($result)) {
														
														
														
														IF ($flags == "-1") {
															//   $name_eq = $name_eq . $row[1] . ";";
															$flags = fun_group($row[0]);
															$namess = fun_names($row[0]);
															
															echo "<optgroup label='$flags' name = 'optgr' style='display: block;'><option value='$row[0]' data-opt_gr = '$flags' data-attr = '$row[4]' data-attr-size = '$row[3]' title='$row[2]|$row[3]' " . ' style="display:none;"' . ">$namess</option>";
															} ELSE {
															
															IF ($flags != fun_group($row[0])) {
																$flags = fun_group($row[0]);
																//    $name_eq = $name_eq . $row[1] . ";";
																
																$namess = fun_names($row[0]);
																
																echo "</optgroup> <optgroup label='$flags' name = 'optgr' style='display: block;'>";
																echo "<option value='$row[0]' data-opt_gr = '$flags' data-attr = '$row[4]' data-attr-size = '$row[3]' title='$row[2]|$row[3]' " . ' style="display:block;"' . ">$namess</option>";
																
																} ELSE {
																//    $name_eq = $name_eq . $row[1] . ";";
																
																$namess = fun_names($row[0]);
																
																echo "<option value='$row[0]'  data-opt_gr = '$flags' data-attr = '$row[4]' data-attr-size = '$row[3]' title='$row[2]|$row[3]' " . ' style="display:block;"' . ">$namess</option>";
															}
														}
													}
												?>
											</select>
											<br><div id = 'err_chk'></div>
										</div>
									</div>
								</div>
								
								<?
									
									$query = "select * from PR_OPER";
									$result = mysql_query($query) or die($query);
									$iddd = '';
									while ($row = mysql_fetch_row($result)) {
										if ($row[3] == 1) {
											$iddd = $iddd . $row[0] . ",";
										}
									}
									$iddd = substr($iddd, 0, -1);
									
									
								?>
								
								<div class='row'>
									<div class='col-md-3'>
										<div class='checkbox'>
											<label>
												<input type='checkbox' name='optionsRadiosInline' id='optionsRadiosInline1'
												value='option1' onclick='maket(1)'>Дизайнерам
											</label>
										</div>
									</div>
									<div class='col-md-3'>
										<div class='checkbox'>
											<label>
												<input type='checkbox' name='optionsRadiosInline' id='optionsRadiosInline2'
												value='option2' onclick='maket(2)'>Препресс
											</label>
										</div>
									</div>
									<div class='col-md-2'>
										<div class='checkbox'>
											<label>
												<input type='checkbox' name='optionsRadiosInline'
												id='optionsRadiosInline3' <? //if ($admin == '4' OR $login == '026' OR   $login == '030') {} else {	echo ' disabled ';} ?>
												value='option3' onclick='maket(3)'> На печать
											</label>
										</div>
									</div>
									<div class='col-md-2'>
										<div class='checkbox'>
											<label>
												<input type='checkbox' name='optionsRadiosInline' id='optionsRadiosInline4'
												value='option4' onclick='maket(4)'>Передать в цех, без файлов
											</label>
										</div>
									</div>
									<div class='col-md-2' id='bnt_file'>
										<label class='btn btn-success btn-file btn-sm'>
											Выберете файлы...<input name='file[]' type='file' multiple='true'
											style='display: none;' id='filea'/>
										</label>
										
										
									</div>
								</div>
								
								<div class='row'>
									<div class='col-md-3'>
										
										<div id='op1' style='display:none;'>
											<button type='button' class='btn btn-default btn-sm' onclick='addmyModal3()'>Выбор
												дизайн
											</button>
										</div>
									</div>
									<div class='col-md-3'>
										<div id='op2' style='display:block;'>
											<button type='button' class='btn btn-default btn-sm' onclick='addmyModal4()'>Выбор
												препресс
											</button>
										</div>
									</div>
									
									<div class='col-md-2'>
										
									</div>
									<div class='col-md-4'>
										
										<div id="listss"></div>
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
                                                <td><label>Плот. Резка:</label></td>
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
													<td><label>Стоимость дизайна:</label></td>
												</tr>
												<tr class='odd gradeX'>
													<td><label>Стоимость препресса:</label></td>
												</tr>
												<tr class='odd gradeX'>
													<td><label>Параметры:</label></td>
												</tr>
											</table>
											
											<hr>
											<div class='row'>
												
												<div class='col-md-4'>
													
													<div class='row'>
														<div class='col-md-3'><label>Стоимость:</label></div>
														<div class='col-md-9'><input type='text' id='p_sum_all'>
															<input type='hidden' id='p_sum_sys' name='p_sum_sys'>
															<input type='hidden' id='p_price_all' name='p_price_all'>
															<input type='hidden' id='str_tm' name='str_tm'>
															<input type='hidden' id='str_tm1' name='str_tm1'>
															<input type='hidden' id='id_prod' name='id_prod'>
															<input type='hidden' id='kol' value="0" name='kol'>
															<input type='hidden' id='orderAcct' name='orderAcct'
															value='<? echo $idAcct; ?>'/>
															<input type='hidden' id='maket_sh' value="2" name='maket_sh'>
															<input type='hidden' id='view_diz' name='view_diz' size='10' value='0'>
															<input type='hidden' id='num_peod' name='num_peod' size='10' value='0'>
															<input type='hidden' id='view_press' name='view_press' size='10'
															value='<? echo $iddd ?>'>
															<input type='hidden' id='cl_file' name='cl_file' size='10' value=''>
															<input type='hidden' id='print_diz' name='print_diz' size='10' value=''>
															<input type='hidden' id='press_diz' name='press_diz' size='10' value=''>
															<input type='hidden' id='file_yes' name='file_yes' size='10' value='0'>
															<input type='hidden' id='sum_press' name='sum_press' size='10'
															value='0'>
														</div>
													</div>
													
													
													<div class='row'>
														<div class='col-md-3'><label>Срочность:</label></div>
														<div class='col-md-3'>
															<select id='p_fast' name='p_fast' style="width:90%;"
															onblur="get_price()">
																<option value="1" selected>ОБЫЧНО</option>
																<option value="1.2">СРОЧНО</option>
																<option value="1.5">ОЧЕНЬ СРОЧНО</option>
																<select>
																</div>
															</div>
															<div class='row'>
																<div class='col-md-3'><label>Дата сдачи:</label></div>
																<div class='col-md-9'><input type="datetime-local" name="p_dates_time"
																	id="p_dates_time" value='2017-01-01T13:00'>
																</div>
															</div>
															
															
														</div>
														<div class='col-md-5'><label>Комментарий:</label><textarea class="form-control"
															rows="5" id='list_comm'
															name='list_comm'></textarea>
														</div>
													</div>
													
													
													<div class='row'>
														<div class='col-md-3'>
															<div class="checkbox">
																<label>
																	<input type="checkbox" value="" id="no_price">Не считать цену
																</label> <br>
																<label>
																	<input type="checkbox" value="" id="file_diz">Файлы у дизайнера
																</label>
																
															</div>
														</div>
													</div>
													<!--<a onclick="get_price()"><button type="button"   class="btn btn-primary" >Расчет</button></a>-->
												</div>
											</div>
										</div>
									</form>
									<div class="modal-footer">
										
										<button type="button" onclick="addProd()" class="btn btn-primary" id="show_add">Добавить</button>
										
									</div>
								</div>
							</div>
						</div>
						
						<script>
							function get_acct_tran(id){
								$('#acct_tranc').modal('show');
							}
						</script>
						
						<script>
							function checks(id, id2) {
								
								if (document.getElementById(id + "_").checked) {
									
									
									document.getElementById(id).hidden = false;
									if (id2 != "") {
										document.getElementById(id2).hidden = false;
									}
									
									switch (id) {
										case "check13" :
										document.getElementById("p_bug_1").focus();
										break;
										case "check1" :
										document.getElementById("p_perf_1").focus();
										break;
										case "check2" :
										document.getElementById("p_ygl+_1").focus();
										break;
										case "check4" :
										document.getElementById("p_otv_1").focus();
										break;
										case "check5" :
										document.getElementById("p_luv_1").focus();
										break;
										case "check7" :
										document.getElementById("p_vir_1").focus();
										break;
										case "check8" :
										document.getElementById("p_con_1").focus();
										break;
										case "check9" :
										document.getElementById("p_tis_1").focus();
										break;
									}
									
									
									get_price();
									} else {
									document.getElementById(id).hidden = true;
									if (id2 != "") {
										document.getElementById(id2).hidden = true;
									}
									var part = Number(document.getElementById('kol').value) + 1;
									switch (id) {
										case "check13" :
										for (var i = 1; i < part; i++) {
											$('#p_bug_' + i).val('');
										}
										break;
										case "check1" :
										for (var i = 1; i < part; i++) {
											$('#p_perf_' + i).val('');
										}
										break;
										case "check2" :
										for (var i = 1; i < part; i++) {
											$('#p_ygl_' + i).val('');
										}
										break;
										case "check4" :
										for (var i = 1; i < part; i++) {
											$('#p_otv_' + i).val('');
											$('#p_diam_' + i).val('');
										}
										break;
										case "check5" :
										for (var i = 1; i < part; i++) {
											$('#p_luv_' + i).val('');
											$('#p_colorluv_' + i).val('');
										}
										break;
										case "check7" :
										for (var i = 1; i < part; i++) {
											$('#p_vir_' + i).val('');
											$('#p_prstamp_' + i).val('');
										}
										break;
										case "check8" :
										for (var i = 1; i < part; i++) {
											$('#p_con_' + i).val('');
											$('#p_prkl_' + i).val('');
										}
										break;
										case "check9" :
										for (var i = 1; i < part; i++) {
											$('#p_tis_' + i).val('');
											$('#p_prckl_' + i).val('');
										}
										break;
									}
									
									get_price();
									
									
								}
								
							}
						</script>
						
						
						<script>
							
							function blank_print(id){
								var nodeList = document.getElementsByName('chDel');
								var array = Array.prototype.slice.call(nodeList);
								var array_id = '';
								for (var i = 0; i < array.length; i++) {
									if (array[i].checked) {
										array_id = array_id + array[i].value + ",";
									}
								}
								if (array_id != "") {
									array_id = array_id.substring(0, array_id.length - 1);
								}
								window.open(	'proc/plan_job1.php?id=' + id + '&num=' + array_id);
							}
							
							$('#add').bind('click', function () {
								document.getElementById("directoryCodeStat").selectedIndex = -1;
								document.getElementById("directoryCodeStat").dispatchEvent(new Event('change'));
								document.getElementById('show_add').style.display = 'block';
								var nodeList = document.getElementsByName('chDel');
								var array = Array.prototype.slice.call(nodeList);
								var array_id = '';
								for (var i = 0; i < array.length; i++) {
									if (array[i].checked) {
										array_id = array_id + array[i].value + ",";
									}
								}
								if (array_id != "") {
									array_id = array_id.substring(0, array_id.length - 1);
									$.ajax({
										type: "GET",
										url: '../ajax_php_sql.php',
										data: {
											flag: '19',
											array_id: array_id
											}, success: function (data) {//возвращаемый результат от сервера
											window.location.reload();
										}
									});
									
								}
								else {
									document.getElementById('id_prod').value = "";
									document.getElementById('p_cir').value = "";
									document.getElementById('p_names').value = "";
									document.getElementById('p_size').value = "";
									document.getElementById('no_price').checked = false
									document.getElementById('maket_sh').value = 2;
									$("input[name='qwerty']").prop('checked', false);
									$('#max1').attr('checked', false);
									$('#p_fast').val(1);
									$('#unit_prod1').val('');
									$('#p_per_mat_i').val(0);
									$('#p_per_i').val(0);
									$('#p_stor_i').val('');
									$('#p_dates_time').val("");
									$('#p_sum_all').val("");
									$('#p_sum_sys').val("");
									$('#list_comm').val("");
									$('#cl_file').val("");
									$('#print_diz').val("");
									$('#press_diz').val("");
									$('#p_price_all').val("");
									$('#view_diz').val("");
									$('#view_press').val("");
									document.getElementById('listss').innerHTML = '';
									num_peod = '<?echo $total_KOL;?>';
									if ($('#num_peod').val() != "0") {
										num_peod = $('#num_peod').val()
									}
									num_peod = Number(num_peod) + 1;
									$('#num_peod').val(num_peod);
									delrowTable();
									rowTable();
									//$( "#p_fast" ).toggle();
									maket(2);
									
									var id_acct = "<? echo $idAcct; ?>";
									removeAllTextNodes(document.getElementById("headerModalAddProduct"));
									document.getElementById("headerModalAddProduct").appendChild(document.createTextNode("Добавление продукта в счет №" + id_acct));
									
									$('#myModalprod3').modal('show')
								}
							});
							
							
							$('#post').bind('click', function () {
								$('#post_fio').val('');
								$('#region_id').val('');
								$('#view_post').val('');
								$('#post_city').val('');
								$('#post_raion').val('');
								$('#post_street').val('');
								$('#post_house_num').val('');
								$('#post_house_kor').val('');
								$('#post_room').val('');
								$('#post_index').val('');
								$('#post_phone').val('');
								$('#post_price').val('');
								//	$('#post_track').val('');
								document.getElementById('post_track').innerHTML = '';
								
								if ($('#post_kol').val() > '0'){
									
									$("input[name='post_chk']").prop('checked', true);
									del_row_post();
								}
								$('#post_kol').val('0')
								
								document.getElementById("info_dost").innerHTML = '';
								
								view_post();
								var nodeList = document.getElementsByName('chDel');
								var array = Array.prototype.slice.call(nodeList);
								var array_id = '';
								for (var i = 0; i < array.length; i++) {
									if (array[i].checked) {
										array_id = array_id + array[i].value + ",";
									}
								}
								if (array_id != "") {
									$('#form_post').modal('show')
								}
								else { alert("Выберите продукт!!!")
								}
								
							});
							function delrowTable() {
								document.getElementById('kol').value = '0';
								var tbl = document.getElementById('dynamic');                   // таблица, с которой работаем
								var rws = tbl.rows.length;                                            // коллекция существующих строк таблицы
								for (i = 0; i < rws; i++) {
									kol_cell = tbl.rows[i].cells.length;
									for (y = 1; y <= kol_cell; y++) {
										if (tbl.rows[i].cells.length > 1) {
											tbl.rows[i].deleteCell(-1);
										}
									}
								}
							}
							
							function fun_eq(id) {
								id_1 = id.substring(0, id.length - 1);
								val_ch = document.getElementById(id).value;
								p_eq_sel = document.getElementById(id_1).length;
								for (var y = 0; y < p_eq_sel; y++) {
									if ((document.getElementById(id_1).options[y].label == val_ch) && (document.getElementById(id_1).options[y].style.display == 'block')) {
										document.getElementById(id_1).options[y].selected = true;
									}
								}
								p_id_avt = id_1.split('_');
								if ((p_id_avt[0] + "_" + p_id_avt[1] + "_") == 'p_eq_') {
									rts('p_eq_' + p_id_avt[2], 'p_color_' + p_id_avt[2], 'p_sizep_' + p_id_avt[2], 'p_mat_' + p_id_avt[2]);
									p_color_total = document.getElementById('p_color_' + p_id_avt[2]).length;
									str_color = "";
									for (var y = 0; y < p_color_total; y++) {
										if (document.getElementById('p_color_' + p_id_avt[2]).options[y].style.display == 'block') {
											str_color = str_color + "<option>" + document.getElementById('p_color_' + p_id_avt[2]).options[y].label + "</option>";
										}
									}
									document.getElementById('p_color_' + p_id_avt[2] + "1").innerHTML = str_color
									document.getElementById('p_color_' + p_id_avt[2] + "2").value = '';
									
									p_sizep_total = document.getElementById('p_sizep_' + p_id_avt[2]).length;
									str_sizep = "";
									for (var y = 0; y < p_sizep_total; y++) {
										if (document.getElementById('p_sizep_' + p_id_avt[2]).options[y].style.display == 'block') {
											str_sizep = str_sizep + "<option>" + document.getElementById('p_sizep_' + p_id_avt[2]).options[y].label + "</option>";
										}
									}
									document.getElementById('p_sizep_' + p_id_avt[2] + "1").innerHTML = str_sizep
									document.getElementById('p_sizep_' + p_id_avt[2] + "2").value = '';
									document.getElementById('p_mat_' + p_id_avt[2] + "2").value = '';
								}
								
								if ((p_id_avt[0] + "_" + p_id_avt[1] + "_") == 'p_sizep_') {
									rts1('p_sizep_' + p_id_avt[2], 'p_mat_' + p_id_avt[2], 'p_eq_' + p_id_avt[2])
									str_mat = "";
									p_mat_total = document.getElementById('p_mat_' + p_id_avt[2]).length;
									for (var y = 0; y < p_mat_total; y++) {
										if (document.getElementById('p_mat_' + p_id_avt[2]).options[y].style.display == 'block') {
											str_mat = str_mat + "<option>" + document.getElementById('p_mat_' + p_id_avt[2]).options[y].label + "</option>";
										}
									}
									document.getElementById('p_mat_' + p_id_avt[2] + "1").innerHTML = str_mat
									document.getElementById('p_mat_' + p_id_avt[2] + "2").value = '';
								}
								get_price();
							}
							function delOneRowTable(){
								
								var kol = Number(document.getElementById('kol').value) ;
								var tbl = document.getElementById('dynamic');                   // таблица, с которой работаем
								var rws = tbl.rows.length;                                            // коллекция существующих строк таблицы
								for (i = 0; i < rws; i++) {
									kol_cell = tbl.rows[i].cells.length;
									for (y = kol - 1; y < kol; y++) {
										if (tbl.rows[i].cells.length > 1) {
											tbl.rows[i].deleteCell(-1);
										}
									}
								}
								document.getElementById('kol').value = kol - 1;
							}
							
							function rowTable() {
								var tbl = document.getElementById('dynamic');                   // таблица, с которой работаем
								var rws = tbl.rows.length;                                            // коллекция существующих строк таблицы
								i = 0;
								var kol_part = Number(document.getElementById('kol').value) + 1;
								document.getElementById('kol').value = kol_part;
								var tabkol = kol_part + 1;
								
								var ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' id='p_namepart_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + " tabindex='" + tabkol + "' >";
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text'  id = 'p_size_" + kol_part + "' style='width:100%;' onchange='selectedSize(" + kol_part + ")' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'  >";
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text'  id = 'p_kolstr_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'  >";
								
								ro = tbl.rows[i++].insertCell(-1);
								p_eq_total = document.getElementById('p_eq_i').length;
								str_eq = "";
								for (var y = 0; y < p_eq_total; y++) {
									str_eq = str_eq + "<option>" + document.getElementById('p_eq_i').options[y].label + "</option>";
								}
								
								ro.innerHTML = "<select id = 'p_eq_" + kol_part + "' " + " tabindex='" + tabkol + "'" + "onchange=" + '"' + "rts('p_eq_" + kol_part + "','p_color_" + kol_part + "','p_sizep_" + kol_part + "','p_mat_" + kol_part + "' )" + '"' + " " + "' style='width:100px' " + "> " + document.getElementById('p_eq_i').innerHTML + "</select>";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<select id = 'p_color_" + kol_part + "' onblur=" + '"' + "get_price()" + '"' + " tabindex='" + tabkol + "'" + " onchange=" + '"' + "rts_combicolor('p_eq_" + kol_part + "','p_color_" + kol_part + "')\"> " + document.getElementById('p_color_i').innerHTML + "</select>";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<select class='chosen'  style='display: block;' id = 'p_mat_" + kol_part + "' onblur=" + '"' + "get_price()" + '"' + " tabindex='" + tabkol + "'" + " onchange='chosenSelected(this)'> " + document.getElementById('p_mat_i').innerHTML + "</select>	<input type='checkbox' " + "' onblur=" + '"' + "get_price()" + '"' + "value='' id='mat_firm" + kol_part + "' >Сырье заказчика &nbsp;&nbsp;&nbsp;<div id='sir" + kol_part + "' " + "></div>";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<select id = 'p_sizep_" + kol_part + "'" + " tabindex='" + tabkol + "' onblur=" + '"' + "get_price()" + '"' + "> " + document.getElementById('p_sizep_i').innerHTML + "</select>  <input  type = 'hidden'  id = 'p_size_r_" + kol_part + "' size= '10' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'  >&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp <b>Другой размер: </b> <input  type = 'text' tabindex='" + tabkol + "' id = 'p_new_size_" + kol_part + "'  size= '10' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input type='checkbox' tabindex='" + tabkol + "' id = 'p_cut_" + kol_part + "' style='width:5%;position: left;float: left; clear: fight;'  onblur=" + '"' + "get_price()" + '"' + ">  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<b>Плот. Резка(по меткам)</b> <input type='checkbox' tabindex='" + tabkol + "' id = 'p_cut2_" + kol_part + "' style='width:5%;position: rigth;float: rigth; clear: fight;'  onblur=" + '"' + "get_price()" + '"' + "> P= <input  type = 'text'  id = 'p_size_cut_" + kol_part + "' size= '10' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'> мм. (Периметр 1-го изделия)";

                                //плоттераня резка
                                ro = tbl.rows[i++].insertCell(-1);
                                ro.innerHTML = "<select id='p_size_cut_op_" + kol_part + "' name='p_size_cut_op_" + kol_part + "'>" + str_plottCut + "</select>" +
                                    "<select id='p_size_cut_eq_" + kol_part + "' name='p_size_cut_eq_" + kol_part + "' onchange='get_price()'><option value=''>Выберите</option></select>" +
                                    "P= <input  type = 'text' name = 'p_size_cut2_" + kol_part + "' id = 'p_size_cut2_" + kol_part + "' size= '10' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'> мм. (Периметр 1-го изделия)";
                                document.getElementById("p_size_cut_op_" + kol_part).onchange = function(e) {
                                    deleteFooterDOM(document.getElementById("p_size_cut_eq_" + kol_part));
                                    let opt = document.createElement('option');
                                    opt.setAttribute('value', '');
                                    opt.appendChild(document.createTextNode('Выберите'));
                                    document.getElementById("p_size_cut_eq_" + kol_part).appendChild(opt);
                                    if(e.target.value) {
                                        var eqCut = sendAjax('m=ajaxs&a=getEqupmentsByOperations', {'operations': e.target.value}, null, false, true);
                                        if(eqCut && Array.isArray(eqCut)) {
                                            for(let i in eqCut) {
                                                let opt = document.createElement('option');
                                                opt.setAttribute('value', eqCut[i].ID);
                                                opt.appendChild(document.createTextNode(eqCut[i].EQ_NAME));
                                                document.getElementById("p_size_cut_eq_" + kol_part).appendChild(opt);
                                            }
                                            if(eqCut.length == 1) {
                                                document.getElementById("p_size_cut_eq_" + kol_part).selectedIndex = 1;
                                                document.getElementById("p_size_cut_eq_" + kol_part).dispatchEvent(new Event('change'));
                                            }
                                        }
                                    }
                                }

								p_lam_total = document.getElementById('p_lam_i').length;
								str_lam = "";
								for (var y = 0; y < p_lam_total; y++) {
									str_lam = str_lam + "<option>" + document.getElementById('p_lam_i').options[y].label + "</option>";
								}
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<select id = 'p_lam_" + kol_part + "' tabindex='" + tabkol + "'" + ">" + document.getElementById('p_lam_i').innerHTML + "</select>";
								
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_bug_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_perf_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_ygl_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_otv_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								
								p_diam_total = document.getElementById('p_diam_i').length;
								str_diam = "";
								for (var y = 0; y < p_diam_total; y++) {
									str_diam = str_diam + "<option>" + document.getElementById('p_diam_i').options[y].label + "</option>";
								}
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<select id = 'p_diam_" + kol_part + "' tabindex='" + tabkol + "'" + ">" + document.getElementById('p_diam_i').innerHTML + "</select>";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_luv_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								
								p_lam_total = document.getElementById('p_lam_i').length;
								str_lam = "";
								for (var y = 0; y < p_lam_total; y++) {
									str_lam = str_lam + "<option>" + document.getElementById('p_lam_i').options[y].label + "</option>";
								}
								
								p_colorluv_total = document.getElementById('p_colorluv_i').length;
								str_colorluv = "";
								for (var y = 0; y < p_colorluv_total; y++) {
									str_colorluv = str_colorluv + "<option>" + document.getElementById('p_colorluv_i').options[y].label + "</option>";
								}
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<select id = 'p_colorluv_" + kol_part + "' tabindex='" + tabkol + "'" + ">" + document.getElementById('p_colorluv_i').innerHTML + "</select>";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_vir_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_con_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_tis_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_off_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_prstamp_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_prkl_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_prckl_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_prdiz_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_press_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
								/*-------------------img----------------------*/
								ro = tbl.rows[i++].insertCell(-1);
								ro.innerHTML = "<div id='row'>	<div class='col-md-6'>		<div id='row' >			<div class='col-md-4'>Вынос цвета</div>			<div class='col-md-8'><input   tabindex='" + tabkol + "' type = 'text' size='15' value ='2'	id='vin" + kol_part + "' onchange='selectedSize(" + kol_part + ")' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + "> мм</div>		</div>			<div id='row' style='display:none;'>			<div class='col-md-12'>				<div class='checkbox'><label><input type='checkbox' value='' tabindex='" + tabkol + "' id= 'vin1" + kol_part + "' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">Поля выноса</label></div>			</div>		</div>		<div id='row'>			<div class='col-md-12'>				<div class='checkbox'>					<label><input type='checkbox' value=''  tabindex='" + tabkol + "' id = 'max" + kol_part + "' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">Максимальное заполнение </label>				</div>			</div>		</div>		<div id='row'>			<div class='col-md-12'>				<div class='checkbox'><label><input type='checkbox' value='' checked tabindex='" + tabkol + "' id = 'pol" + kol_part + "' onchange='selectedSize(" + kol_part + ")' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">Непечатные поля</label></div>			</div>		</div>		<div id='row'>			<div class='col-md-12'>				<div class='checkbox'>					<label><input type='checkbox' value=''  tabindex='" + tabkol + "' id = 'pers" + kol_part + "' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">Перерасчет (пружина, термоклей) </label>				</div>			</div>		</div></div>	<div class='col-md-1'></div>	<div class='col-md-4'><div id='row'><div class='col-md-12'><div id='info" + kol_part + "'></div><div id='list" + kol_part + "'></div></div></div></div></div>";
								
								
							}
							
							
							function get_price1(stra) {
								console.clear();
								console.log("                                         ")
								console.log("------------------START------------------")
								console.log("                                         ")
								p_pr_diz = 0;
								l_error = 0;
								/*Надбавка на материалы*/
								nadb_mat = "<?echo $nadb_mat;?>";
								nadb_mat = 1 + (Number(nadb_mat) / 100);
								
								//Мин. стоимость вырубка	
								var min_vir = 15
								//Мин. стоимость когрев	
								var min_con = 15
								//Мин. стоимость тиснение	
								var min_tis = 15
								
								var nds_ = "<? echo $nds_ ?>";
								
								//Кат.
								var nadb = "<? echo $nadb ?>";
								if (nadb == "") {
									nadb = 4;
								}
								var unit_prod = document.getElementById('unit_prod1').value;
								var kot_total = 1;
								if (unit_prod == "тыс.шт.") {
									kot_total = 1000;
								}
								var str_tm = "";
								var str_tm1 = "";
								//Срочность
								var fast = document.getElementById('p_fast').value;
								var part = Number(document.getElementById('kol').value) + 1;
								sum_all = 0;
								sum_all1 = 0;
								i = 1;
								
								var arr = [];
								
								
								for (var i = 1; i < part; i++) {
									seb_tir = 0;
									//new_par = datas.split('|');
									ss = stra.split("^");
									new_par = ss[i - 1].split("|");
									
									//console.log(new_par)
									/*new_price  = 1;
										new_siz = 0;
									new_zakaz = '';	*/
									new_siz = "";
									new_price = new_par[0];
									new_siz = new_par[1];
									new_zakaz = new_par[2];
									new_message = new_par[3];
									new_wir = new_par[4];
									new_wir_m = new_par[5];
									new_alg = new_par[6];
									//Работы на стороне:
									if (document.getElementById('p_off_' + i).value != "") {
										p_off = document.getElementById('p_off_' + i).value;
										p_off = p_off.replace(",", ".")
									}
									else {
										p_off = 0
									}
									
									//Цена штампа:
									if (document.getElementById('p_prstamp_' + i).value != "") {
										p_pr_stamp = document.getElementById('p_prstamp_' + i).value;
										p_pr_stamp = p_pr_stamp.replace(",", ".")
									}
									else {
										p_pr_stamp = 0
									}
									
									//Цена клише, конгрев:
									if (document.getElementById('p_prkl_' + i).value != "") {
										p_pr_kl = document.getElementById('p_prkl_' + i).value;
										p_pr_kl = p_pr_kl.replace(",", ".")
									}
									else {
										p_pr_kl = 0
									}
									
									//Цена клише, тиснение:
									if (document.getElementById('p_prckl_' + i).value != "") {
										p_prc_kl = document.getElementById('p_prckl_' + i).value;
										p_prc_kl = p_prc_kl.replace(",", ".")
									}
									else {
										p_prc_kl = 0
									}
									p_pr_diz = 0
									//Стоимость дизайна:
									if (document.getElementById('p_prdiz_' + i).value != "") {
										p_pr_diz = document.getElementById('p_prdiz_' + i).value;
										p_pr_diz = p_pr_diz.replace(",", ".")
									}
									else {
										p_pr_diz = 0
									}
									//Стоимость дизайна:
									if (document.getElementById('p_press_' + i).value != "") {
										p_press = document.getElementById('p_press_' + i).value;
										p_press = p_press.replace(",", ".")
									}
									else {
										p_press = 0
									}
									
									if (document.getElementById('p_size_' + i).value != "") {
										psize = document.getElementById('p_size_' + i).value;
										psize1 = psize.split('*');
									}
									else {
										psize = document.getElementById('p_size').value;
										psize1 = psize.split('*');
									}
									
									
									if (document.getElementById('p_cir').value != "") {
										n_cir = Number((document.getElementById('p_cir').value).replace(",", "."));
									}//ТИРАЖ
									else {
										n_cir = 0;
									}
									/*--------------------------------------------------------------------------------------------------------------*/
									
									kofff = 3;
									vin = document.getElementById("vin" + i).value;
									color_r = '255,255,255';
									if (document.getElementById('vin1' + i).checked) {
										color_r = '255,0,0';
									}
									
									pagekol = 0;
									pagekol1 = 0;
									perim = 0;
									//Длина изделия, мм
									prod_size_1 = psize1[0];
									//Ширина изделия, мм	
									prod_size_2 = psize1[1];
									perim = (Number(prod_size_1) * 2) + (Number(prod_size_2) * 2);
									prod_size_1 = Number(prod_size_1) / kofff;
									prod_size_2 = Number(prod_size_2) / kofff;
									kotaa = 2;
									if ($('#pers' + i).prop('checked') == false) {
										if (document.getElementById('p_stor_i').value == "узкой") {
											if (prod_size_1 < prod_size_2) {
												prod_size_2 = prod_size_2 * 2;
												} else {
												prod_size_1 = prod_size_1 * 2;
											}
											kotaa = 4;
										}
										if (document.getElementById('p_stor_i').value == "широкой") {
											if (prod_size_1 > prod_size_2) {
												prod_size_2 = prod_size_2 * 2;
												} else {
												prod_size_1 = prod_size_1 * 2;
											}
											kotaa = 4;
										}
									}
									
									l_rasch_mat = '0';
									// с выносами
									prod_size_11 = Number(prod_size_1) + (Number(vin) / kofff);
									prod_size_22 = Number(prod_size_2) + (Number(vin) / kofff);
									
									sel_mat_total = document.getElementById("p_mat_" + i).length;
									for (var y = 0; y < sel_mat_total; y++) {
										if (document.getElementById("p_mat_" + i).options[y].selected == true) {
											
											var val_mat = (document.getElementById("p_mat_" + i).options[y].title).split('|');
											var price_mat = val_mat[0]; 		//Стоимость бумаги	
											var val_mat_sz = val_mat[1].split("*");
											var mat_size_1 = val_mat_sz[0]; 	//Длина поля печати, мм	
											/*if (val_mat_sz[1] != ""){*/
											var mat_size_2 = val_mat_sz[1]; 	//Ширина поля печати, мм	
											l_rasch_mat = '0';
											/*}
												else {
												mat_size_2 = 0; 
												l_rasch_mat = '1';  
											} */
										}
									}
									
									psizeee = document.getElementById('p_sizep_' + i).value;
									if (document.getElementById('p_new_size_' + i).value != "") {
										psizeee = document.getElementById('p_new_size_' + i).value;
									}
									
									val_mat_sz = psizeee.split('*');
									
									mat_size_1_old = mat_size_1;
									mat_size_2_old = mat_size_2;
									var mat_size_1 = val_mat_sz[0]; 	//Длина поля печати, мм	
									var mat_size_2 = val_mat_sz[1]; 	//Ширина поля печати, мм	
									
									
									var x1 = Number(mat_size_1_old) / Number(mat_size_1);
									var x2 = Number(mat_size_2_old) / Number(mat_size_2);
									var x3 = Number(mat_size_1_old) / Number(mat_size_2);
									var x4 = Number(mat_size_2_old) / Number(mat_size_1);
									x1 = x1.toFixed(2);
									x3 = x3.toFixed(2);
									x2 = x2.toFixed(2);
									x4 = x4.toFixed(2);
									var y1 = Number(parseInt(x1)) * Number(parseInt(x2));
									var y2 = Number(parseInt(x3)) * Number(parseInt(x4));
									y1 = Number(y1.toFixed());
									y2 = Number(y2.toFixed());
									
									pagekol_new = 0;
									if (y1 > y2) {
										pagekol_new = y1; 	//"К-во изд. на листе"
									}
									else {
										pagekol_new = y2; 	//"К-во изд. на листе"
									}
									
									if (pagekol_new == 0) {
										if (document.getElementById('no_price').checked == false) {
											document.getElementById('p_sum_all').value = 0;
										document.getElementById('p_price_all').value = 0; }
										
										console.log("ОШИБКА!!! На выбраную бумагу не вмещается формат на котором хотете печать. Выбраный размер бумаги: " + mat_size_1_old + "*" + mat_size_2_old + ", Хотите печатать на формате: " + mat_size_1 + "*" + mat_size_2)
										l_error = 1;
										//return;
									}
									
									price_mat_old = Number(price_mat);
									price_mat = Number(price_mat_old) / Number(pagekol_new);
									price_mat = price_mat.toFixed(2);
									//console.log("Кол-во новых листов на старом = " + pagekol_new + "! Старая цена = " + price_mat_old + "! Новая = " + price_mat )
									
									pol = 0;
									pol1 = 0;
									if (document.getElementById('pol' + i).checked) {
										// с отстурами
										pol = 6 / kofff;
										pol1 = 6 / kofff;
									}
									if (document.getElementById('p_cut2_' + i).checked || (document.getElementById('p_size_cut_op_' + i).value && document.getElementById('p_size_cut_eq_' + i).value)) {
										pol = 25 / kofff;
										pol1 = 25 / kofff;
									}
									
									if (new_wir == '1'){
										document.getElementById('max' + i).checked = true;
										
									}
									
									// if (l_rasch_mat == "1") {
									// var x1 = Number(mat_size_1 / kofff) / Number(prod_size_11);
									// var x2 = Number(mat_size_1 / kofff) / Number(prod_size_22);
									// pagekol4 = Math.floor(x1);
									// var stron1 = Number(prod_size_22) * Number(kofff);
									// pagekol2 = Math.floor(x2);
									// var stron2 = Number(prod_size_11) * Number(kofff);
									// alg1 = 2;
									
									// stron1 = stron1.toFixed(2);
									// stron2 = stron2.toFixed(2);
									
									// kol_srt1 = Math.ceil(Number(n_cir) / Number(pagekol4));
									// kol_srt2 = Math.ceil(Number(n_cir) / Number(pagekol2));
									
									// mat_size_2_one = ((Number(stron1)) * kol_srt1);
									// mat_size_2_two = ((Number(stron2)) * kol_srt2);
									
									
									// if (Number(mat_size_2_one) >= Number(mat_size_2_two)) {
									// mat_size_2 = Number(mat_size_2_two) + (Number(pol) * Number(kofff) * 2) + Number(vin) * 2;
									// }
									// else {
									// mat_size_2 = Number(mat_size_2_one) + (Number(pol) * Number(kofff) * 2) + Number(vin) * 2;
									// }
									
									
									// document.getElementById('max' + i).checked = true;
									
									// pagekol = 0;
									// }
									
									var sqrs = ((Number(prod_size_11) * Number(kofff)) / 1000) * ((Number(prod_size_22) * Number(kofff)) / 1000);
									mat_size_1 = Number(mat_size_1) / kofff;
									mat_size_2 = Number(mat_size_2) / kofff;
									
									mat_size_11 = Number(mat_size_1) - pol1 - pol1;
									mat_size_22 = Number(mat_size_2) - pol - pol;
									
									
									if (document.getElementById('p_cut2_' + i).checked || (document.getElementById('p_size_cut_op_' + i).value && document.getElementById('p_size_cut_eq_' + i).value)) {
										if (mat_size_1 > mat_size_2) {
											pol1 = 25 / kofff;
											mat_size_11 = Number(mat_size_1) - pol1 - pol1;
											pol = 15 / kofff;
											mat_size_22 = Number(mat_size_2) - pol - pol;
											} else {
											pol1 = 15 / kofff;
											mat_size_11 = Number(mat_size_1) - pol1 - pol1;
											pol = 25 / kofff;
											mat_size_22 = Number(mat_size_2) - pol - pol;
										}
										//console.log(mat_size_11 + " " + mat_size_22 + " " + mat_size_1 + " " + mat_size_2)
									}
									
									
									var x1 = Number(mat_size_11) / Number(prod_size_11);
									var x2 = Number(mat_size_22) / Number(prod_size_22);
									var x3 = Number(mat_size_11) / Number(prod_size_22);
									var x4 = Number(mat_size_22) / Number(prod_size_11);
									x1 = x1.toFixed(2);
									x3 = x3.toFixed(2);
									x2 = x2.toFixed(2);
									x4 = x4.toFixed(2);
									var y1 = Number(parseInt(x1)) * Number(parseInt(x2));
									var y2 = Number(parseInt(x3)) * Number(parseInt(x4));
									y1 = Number(y1.toFixed());
									y2 = Number(y2.toFixed());
									
									
									if (y1 > y2) {
										pagekol = y1; 	//"К-во изд. на листе"
										alg = 1;
									}
									else {
										pagekol = y2; 	//"К-во изд. на листе"
										alg = 2;
										
									}
									if (new_wir == '1'){
										//console.log ("new_alg " + alg + " " + new_alg )
										alg = new_alg;
										
									}
									
									
									wh = "";
									dl = "";
									str = '';
									str = str + '<div title="" style="width: ' + (Number(mat_size_1) + 2) + 'px; height: ' + ( Number(mat_size_2) + 2) + 'px; border: 1px solid rgb(0, 126, 178); position: relative; background-color: rgb(255, 255, 255);" id="ui-id-1">';
									
									if (alg == 1) {
										for (y = 0; y < Number(parseInt(x2)); y++) {
											for (var ii = 0; ii < Number(parseInt(x1)); ii++) {
												if (vin != 0) {
													str = str + '<div  style="top: ' + (pol + (y * (prod_size_22) ) ) + 'px; left: ' + (pol1 + (ii * (prod_size_11) ) ) + 'px; border: 1px solid rgb(' + color_r + '); position: absolute; width: ' + prod_size_11 + 'px; height: ' + prod_size_22 + 'px; background-color: rgb(255, 255, 255);">';
													str = str + '<div style="top: ' + ((vin / 2) - 1 ) + 'px; left: ' + ((vin / 2) - 1 ) + 'px; border: 0.5px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_1 + 'px; height: ' + prod_size_2 + 'px; background-color: rgb(200, 200, 200);"></div>';
													str = str + '</div>';
													wh = (pol + ((y + 1) * prod_size_22 ));
													dl = (pol1 + ((ii + 1) * prod_size_11 ));
												}
												else {
													str = str + '<div style="top: ' + (pol + (y * (prod_size_22) ) ) + 'px; left: ' + (pol1 + (ii * (prod_size_11) ) ) + 'px; border: 1px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_11 + 'px; height: ' + prod_size_22 + 'px; background-color: rgb(200, 200, 200);"></div>';
													wh = (pol + ((y + 1) * prod_size_22 ));
													dl = (pol1 + ((ii + 1) * prod_size_11 ));
												}
											}
										}
									}
									
									if (alg == 2) {
										for (y = 0; y < Number(parseInt(x4)); y++) {
											for (var ii = 0; ii < Number(parseInt(x3)); ii++) {
												if (vin != 0) {
													str = str + '<div  style="top: ' + (pol + (y * (prod_size_11) ) ) + 'px; left: ' + (pol1 + (ii * (prod_size_22) ) ) + 'px; border: 1px solid rgb(' + color_r + '); position: absolute; width: ' + prod_size_22 + 'px; height: ' + prod_size_11 + 'px; background-color: rgb(255, 255, 255);">';
													str = str + '<div style="top: ' + ((vin / 2) - 1 ) + 'px; left: ' + ((vin / 2) - 1 ) + 'px; border: 0.5px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_2 + 'px; height: ' + prod_size_1 + 'px; background-color: rgb(200, 200, 200);"></div>';
													str = str + '</div>';
													wh = (pol + ((y + 1) * prod_size_11 ));
													dl = (pol1 + ((ii + 1) * prod_size_22 ));
												}
												else {
													str = str + '<div style="top: ' + (pol + (y * (prod_size_11) ) ) + 'px; left: ' + (pol1 + (ii * (prod_size_22) ) ) + 'px; border: 1px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_22 + 'px; height: ' + prod_size_11 + 'px; background-color: rgb(200, 200, 200);"></div>';
													wh = (pol + ((y + 1) * prod_size_11 ));
													dl = (pol1 + ((ii + 1) * prod_size_22 ));
												}
											}
										}
									}
									pagekol1 = 0;
									if (document.getElementById('max' + i).checked) {
										new_mat_size_1 = mat_size_1 - dl - pol1;
										new_mat_size_2 = mat_size_2 - wh - pol;
										if ((new_mat_size_1 > prod_size_22 && mat_size_22 > prod_size_11) || (new_mat_size_1 > prod_size_11 && mat_size_11 > prod_size_22)) {
											
											var x1 = Number(new_mat_size_1) / Number(prod_size_11);
											var x2 = Number(mat_size_22) / Number(prod_size_22);
											var x3 = Number(new_mat_size_1) / Number(prod_size_22);
											var x4 = Number(mat_size_22) / Number(prod_size_11);
											x1 = x1.toFixed(2);
											x3 = x3.toFixed(2);
											x2 = x2.toFixed(2);
											x4 = x4.toFixed(2);
											var y1 = Number(parseInt(x1)) * Number(parseInt(x2));
											var y2 = Number(parseInt(x3)) * Number(parseInt(x4));
											
											if (y1.toFixed() >= y2.toFixed()) {
												pagekol1 = y1.toFixed(); 	//"К-во изд. на листе"
												alg = 1;
											}
											else {
												pagekol1 = y2.toFixed(); 	//"К-во изд. на листе"
												alg = 2;
											}
											//console.log( x1 + " " + x2  + " " + x3 + " " + x4 + " " +  y1 + " " + y2 + " " +   alg )
											if (alg == 1) {
												for (y = 0; y < Number(parseInt(x2)); y++) {
													for (var ii = 0; ii < Number(parseInt(x1)); ii++) {
														if (vin != 0) {
															str = str + '<div  style="top: ' + (pol1 + (y * prod_size_22) ) + 'px; left: ' + (dl + (ii * (prod_size_11) ) ) + 'px; border: 1px solid rgb(' + color_r + '); position: absolute; width: ' + prod_size_11 + 'px; height: ' + prod_size_22 + 'px; background-color: rgb(255, 255, 255);">';
															str = str + '<div style="top: ' + ((vin / 2) - 1 ) + 'px; left: ' + ((vin / 2) - 1 ) + 'px; border: 0.5px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_1 + 'px; height: ' + prod_size_2 + 'px; background-color: rgb(200, 200, 200);"></div>';
															str = str + '</div>';
														}
														else {
															str = str + '<div style="top: ' + (pol1 + (y * prod_size_22)) + 'px; left: ' + (dl + (ii * (prod_size_11) ) ) + 'px; border: 1px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_11 + 'px; height: ' + prod_size_22 + 'px; background-color: rgb(200, 200, 200);"></div>';
															
														}
													}
												}
											}
											
											if (alg == 2) {
												for (y = 0; y < Number(parseInt(x4)); y++) {
													for (var ii = 0; ii < Number(parseInt(x3)); ii++) {
														if (vin != 0) {
															str = str + '<div  style="top: ' + (pol1 + (y * prod_size_11)) + 'px; left: ' + (dl + (ii * (prod_size_22) ) ) + 'px; border: 1px solid rgb(' + color_r + '); position: absolute; width: ' + prod_size_22 + 'px; height: ' + prod_size_11 + 'px; background-color: rgb(255, 255, 255);">';
															str = str + '<div style="top: ' + ((vin / 2) - 1 ) + 'px; left: ' + ((vin / 2) - 1 ) + 'px; border: 0.5px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_2 + 'px; height: ' + prod_size_1 + 'px; background-color: rgb(200, 200, 200);"></div>';
															str = str + '</div>';
															
														}
														else {
															str = str + '<div style="top: ' + (pol1 + (y * prod_size_11)) + 'px; left: ' + (dl + (ii * (prod_size_22) ) ) + 'px; border: 1px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_22 + 'px; height: ' + prod_size_11 + 'px; background-color: rgb(200, 200, 200);"></div>';
															
														}
													}
												}
											}
											
										}
										
										if ((new_mat_size_2 > prod_size_22 && mat_size_22 > prod_size_11) || (new_mat_size_2 > prod_size_11 && mat_size_11 > prod_size_22)) {
											
											var x1 = Number(mat_size_11) / Number(prod_size_11);
											var x2 = Number(new_mat_size_2) / Number(prod_size_22);
											var x3 = Number(mat_size_11) / Number(prod_size_22);
											var x4 = Number(new_mat_size_2) / Number(prod_size_11);
											x1 = x1.toFixed(2);
											x3 = x3.toFixed(2);
											x2 = x2.toFixed(2);
											x4 = x4.toFixed(2);
											var y1 = Number(parseInt(x1)) * Number(parseInt(x2));
											var y2 = Number(parseInt(x3)) * Number(parseInt(x4));
											
											y1 = Number(y1);
											y2 = Number(y2);
											
											if (y1.toFixed() >= y2.toFixed()) {
												pagekol1 = y1.toFixed(); 	//"К-во изд. на листе"
												alg = 1;
											}
											else {
												pagekol1 = y2.toFixed(); 	//"К-во изд. на листе"
												alg = 2;
											}
											
											
											// console.log( x1 + " " + x2  + " " + x3 + " " + x4 + " " +  y1 + " " + y2 + " " +   alg )
											if (alg == 1) {
												for (y = 0; y < Number(parseInt(x2)); y++) {
													for (var ii = 0; ii < Number(parseInt(x1)); ii++) {
														if (vin != 0) {
															str = str + '<div  style="top: ' + (wh + (y * prod_size_22)) + 'px; left: ' + (pol + (ii * (prod_size_11) ) ) + 'px; border: 1px solid rgb(' + color_r + '); position: absolute; width: ' + prod_size_11 + 'px; height: ' + prod_size_22 + 'px; background-color: rgb(255, 255, 255);">';
															str = str + '<div style="top: ' + ((vin / 2) - 1 ) + 'px; left: ' + ((vin / 2) - 1 ) + 'px; border: 0.5px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_1 + 'px; height: ' + prod_size_2 + 'px; background-color: rgb(200, 200, 200);"></div>';
															str = str + '</div>';
														}
														else {
															str = str + '<div style="top: ' + (wh + (y * prod_size_22) ) + 'px; left: ' + (pol + (ii * (prod_size_11) ) ) + 'px; border: 1px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_11 + 'px; height: ' + prod_size_22 + 'px; background-color: rgb(200, 200, 200);"></div>';
															
														}
													}
												}
											}
											
											if (alg == 2) {
												for (y = 0; y < Number(parseInt(x4)); y++) {
													for (var ii = 0; ii < Number(parseInt(x3)); ii++) {
														if (vin != 0) {
															str = str + '<div  style="top: ' + (wh + (y * prod_size_11)) + 'px; left: ' + (pol + (ii * (prod_size_22) ) ) + 'px; border: 1px solid rgb(' + color_r + '); position: absolute; width: ' + prod_size_22 + 'px; height: ' + prod_size_11 + 'px; background-color: rgb(255, 255, 255);">';
															str = str + '<div style="top: ' + ((vin / 2) - 1 ) + 'px; left: ' + ((vin / 2) - 1 ) + 'px; border: 0.5px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_2 + 'px; height: ' + prod_size_1 + 'px; background-color: rgb(200, 200, 200);"></div>';
															str = str + '</div>';
															
														}
														else {
															str = str + '<div style="top: ' + (wh + (y * prod_size_11)) + 'px; left: ' + (pol + (ii * (prod_size_22) ) ) + 'px; border: 1px solid rgb(0, 153, 102); position: absolute; width: ' + prod_size_22 + 'px; height: ' + prod_size_11 + 'px; background-color: rgb(200, 200, 200);"></div>';
															
														}
													}
												}
											}
											
										}
										
									}
									
									
									str = str + '</div>';
									
									pagekol = Math.ceil(pagekol);
									
									var form_kol = 0; //"К-во стр."	
									if (document.getElementById('p_kolstr_' + i).value == '') {
										form_kol = 0;
										} else {
										form_kol = Number((document.getElementById('p_kolstr_' + i).value).replace(",", "."))
									}
									
									
									pagekol = Number(pagekol) + Number(pagekol1);
									
									
									var listkol = pagekol * kotaa; //"К-во стр. на листе"	
									kol_pech_i = "";
									kol_pech_k = "";
									var kol_pech_i = Math.ceil(n_cir * kot_total / pagekol); //Кол-во печ. листов, изделие	
									//var kol_pech_k = Math.ceil((form_kol / listkol) * n_cir * kot_total); //Кол-во  печ. листов, каталог
									var kol_pech_k = Math.ceil((Math.ceil(form_kol / kotaa) * n_cir * kot_total)/pagekol);							
									
									tir_list = '';
									if (form_kol == 0) {
										tir_list = kol_pech_i;
										} else {
										tir_list = kol_pech_k
									}
									document.getElementById("list" + i).innerHTML = str;
									if (isNaN(pagekol)) {
										pagekol = 0;
									}
									if (isNaN(tir_list)) {
										tir_list = 0;
									}
									if (new_wir == '1'){
										
										document.getElementById("info" + i).innerHTML = "К-во на листе = " + n_cir + '<br> Листов на тираж = 1';
										
										} else {
										document.getElementById("info" + i).innerHTML = "К-во на листе = " + pagekol + '<br> Листов на тираж = ' + tir_list;
									}
									new_pepr = pagekol;
									/*--------------------------------------------------------------------------------------------------------------*/
									
									sel_oper_total = document.getElementById("p_color_" + i).length;
                                    var price_oper = 0;
									for (var y = 0; y < sel_oper_total; y++) {
										if (document.getElementById("p_color_" + i).options[y].selected == true) {
											price_oper = document.getElementById("p_color_" + i).options[y].title;
										}
									}
									if(document.getElementById("p_color_" + i + "_combi")) {
									    var combi_f = document.getElementsByClassName("p_color_" + i + "_combi_f");
									    var arr_combi_f = new Array();
									    for(var cf in combi_f) {
									        if(combi_f[cf].checked) {
                                                arr_combi_f.push(combi_f[cf].value);
                                            }
                                        }
                                        var combi_b = document.getElementsByClassName("p_color_" + i + "_combi_b");
                                        var arr_combi_b = new Array();
                                        for(var cb in combi_b) {
                                            if(combi_b[cb].checked) {
                                                arr_combi_b.push(combi_b[cb].value);
                                            }
                                        }

                                        $('[class*="_combi_b"]').each(function() {
                                            this.disabled = true;
                                        });
                                        $('[class*="_combi_f"]').each(function() {
                                            this.disabled = true;
                                        });
                                        $.ajax({
                                            type: 'POST',
                                            url: '/www/core/ajax.php?m=ajaxs&u=systemOld&a=getPriceEqCombi',
                                            data: {
                                                'eq': document.getElementById('p_eq_' + i).value,
                                                'clr': document.getElementById('p_color_' + i).value,
                                                'combi_f': arr_combi_f,
                                                'combi_b': arr_combi_b,
                                            },
                                            cache: false,
                                            async: false,
                                            dataType: 'json',
                                            success: function(response) {
                                                price_oper = response && response.Data ? response.Data : 0;
                                                $('[class*="_combi_b"]').each(function() {
                                                    this.disabled = false;
                                                });
                                                $('[class*="_combi_f"]').each(function() {
                                                    this.disabled = false;
                                                });
                                            },
                                            error: function(xhr) {
                                                console.log(xhr.statusText + xhr.responseText);
                                            },
                                        });
                                    }
									var nadbv = 1.4;
									/*switch (nadb) {
										case "2":
										nadb_mat = 1.1;
										nadbv = 1.1;
										break;
										case "3":
										nadb_mat = 1.7;
										nadbv = 1.3;
										break;
										case "5":
										nadb_mat = 1.4;
										nadbv = 1.2;
										break;
										default:
										nadb_mat = 2;
										nadbv = 1.4;
										break;
									}*/
									switch (nadb) {
										case "2":
											nadb_mat = 1.1;
											nadbv = 1.1;
											break;
										case "3":
											nadb_mat = 1.3;
											nadbv = 1.3;
											break;
										case "5":
											nadb_mat = 1.2;
											nadbv = 1.2;
											break;
										default:
											nadb_mat = 1.4;
											nadbv = 1.4;
											break;
									}
									
									nadb_max = 0;
									nadb_min = 0;
									total_max = 0;
									total_min = 0;
									for (var y = 0; y < document.getElementById("p_eq_" + i).length; y++) {
										if (document.getElementById("p_eq_" + i).options[y].selected == true) {
											nadb_max = $(document.getElementById('p_eq_' + i).options[y]).data('nadb_max');
											nadb_min = $(document.getElementById('p_eq_' + i).options[y]).data('nadb_min');
											nadb_max = nadb_max / 100;
											nadb_min = nadb_min / 100;
											total_max = $(document.getElementById('p_eq_' + i).options[y]).data('total_max');
											total_min = $(document.getElementById('p_eq_' + i).options[y]).data('total_min');
										}
									}
									if (tir_list > total_max) {
										total_max = tir_list;
									}
									
									nadbvv = (Number(nadb_max) - Number(nadb_min)) / Number(total_max);
									nadbvv_tir = ((-1 * Number(nadbvv)) * Number(tir_list)) + (Number(nadb_max) + Number(nadbvv))
									if (l_rasch_mat == "1") {
										console.log("Цена печати широкоформатки " + price_oper + " Площадь " + sqrs + " Кол-во " + n_cir)
										price_oper = Number(price_oper) * Number(sqrs) * Number(n_cir);
										
									}
									
									var print_sum = price_oper * nadbv * nadbvv_tir   //Стоимость печати	
									print_sum = print_sum.toFixed(2);
									if(isNaN(print_sum))
										print_sum = 0;
									
									
									/*Автоматом подбираем размер*/
									u = 0;
									//otvet = qww(i , pol * kofff,  pol1 * kofff);
									
									
									//if	( $('#p_sizep_' + i).val() != ""  ||  $('#p_sizep_' + i).val() != "0"  ){
									
									l_sizee = true;
									/*var kol = document.getElementById('p_sizep_' + i).length;
									for (y = 0; y < kol; y++) {
										if ($(document.getElementById('p_sizep_' + i).options[y]).data('attr') == document.getElementById("p_eq_" + i).value) {
											//					console.log(document.getElementById('p_sizep_' + i).options[y].text + " " + new_siz)
											if (document.getElementById('p_sizep_' + i).options[y].text == new_siz) {
												if(!document.getElementById('p_sizep_' + i).options[y].hasAttribute('disabled'))
													document.getElementById('p_sizep_' + i).options[y].selected = true;
												//очередной костыль для размера
												if($(document.getElementById('p_sizep_' + i).options[y]).data('attr') == 3 && document.getElementById('p_sizep_' + i).options[y].value == "297*420")
													document.getElementById('p_sizep_' + i).selectedIndex = -1;
												l_sizee = false;
												
												// get_price();
											}
										}
									}
									if (new_wir == '1'){
										if( l_sizee){
											if(new_siz != '0*0'){
												$('#p_new_size_' + i).val(new_siz);
												//  get_price();
											}
											} else {
											$('#p_new_size_' + i).val('');
											//get_price();
										}
									}*/
									
									
									
									//}
									if ((new_price == 0 || new_price == '0') && !document.getElementById("mat_firm" + i).checked) {
										if (document.getElementById('no_price').checked == false) {
											document.getElementById('p_sum_all').value = 0;
										document.getElementById('p_price_all').value = 0; }
										console.log("ОШИБКА В РАСЧЕТАХ!!! Не задана цена на материал")
										l_error = 1;
										//	return;
									}
									if (new_zakaz != '') {
										document.getElementById("sir" + i).innerHTML = '<span class="label label-danger">Нет в наличии материала!</span>';
										} else {
										document.getElementById("sir" + i).innerHTML = '';
									}
									console.log("------------Схема расчета бумаги ---------------")
									console.log("------------Часть " + i + " ---------------")
									xfcnm = new_message.split("@");
									
									//  console.log(new_message)
									//shema.$data[$i]['size']."%".$data[$i]['tiraj']."%". $data[$i]['sum_'] / $data[$i]['tiraj'] ."%".$data[$i]['sum_']."%".$data[$i]['list_list']."%".$data[$i]['zakaz']."@";
									console.log("Размер         | Кол-во листов | Цена,$ | Сумма,$ | Что использовали ")
									
									for (var u = 0; u < xfcnm.length - 1; u++) {
										xfcnm1 = xfcnm[u].split("%");
										while (xfcnm1[0].length < 15) {
											xfcnm1[0] = xfcnm1[0] + " ";
										}
										while (xfcnm1[1].length < 15) {
											xfcnm1[1] = xfcnm1[1] + " ";
										}
										while (xfcnm1[2].length < 8) {
											xfcnm1[2] = xfcnm1[2] + " ";
										}
										while (xfcnm1[3].length < 9) {
											xfcnm1[3] = xfcnm1[3] + " ";
										}
										new_asd = xfcnm1[4].split("`");
										console.log(xfcnm1[0] + "|" + xfcnm1[1] + "|" + xfcnm1[2] + "|" + xfcnm1[3] + "|" + new_asd[0])
										if (new_asd.length - 1 != 1) {
											for (var tt = 1; tt < new_asd.length - 1; tt++) {
												console.log("                                          |" + new_asd[tt])
											}
										}
										
									}
									
									//console.log("------------------------------------------------")
									//console.log(otvet)
									/*конце автоподбора размера*/
									//	console.log("Стоимость материала 2 = " + price_mat )
									
									price_mat = new_price;
									price_mat = (Number(price_mat) * Number(nadb_mat));
									price_mat = price_mat.toFixed(2);
									
									//		console.log("Стоимость материала 3 = " + price_mat )
									//Тираж листов в зав. от того, каталог или изделие	
									if (document.getElementById('mat_firm' + i).checked) {
										price_mat = 0;
									}
									if (l_rasch_mat == "1") {
										console.log("Метров пагонных материала " + (Number(mat_size_22) * Number(kofff) / 1000) + "  Цена материала " + Number(price_mat))
										console.log("кол-во листов " + tir_list + " Цена материала " + ((Number(mat_size_22) * Number(kofff) / 1000) * Number(price_mat)) + " Стоимость печати полная " + print_sum + " надб " + nadbv + " стоимость печати " + price_oper + " надбавка на тираж " + nadbvv_tir)
										sun_tir = ((Number(mat_size_22) * Number(kofff) / 1000) * Number(price_mat)) + Number(print_sum);	//Стоимость печати за тираж	
										sun_tir = sun_tir.toFixed(2);
										
										} else {
										console.log("----------------Материал цены-------------------")
										
										console.log("Цена | Надбавка | Цена с Надбавкой")
										
										ot_price_no = new_price;
										if (document.getElementById('mat_firm' + i).checked) {
											ot_price_no = "0";
										}
										while (ot_price_no.length < 5) {
											ot_price_no = ot_price_no + " ";
										}
										ot_nadb_mat = String(nadb_mat);
										while (ot_nadb_mat.length < 10) {
											ot_nadb_mat = ot_nadb_mat + " ";
										}
										ot_price_mat = price_mat;
										while (ot_price_mat.length < 17) {
											ot_price_mat = ot_price_mat + " ";
										}
										
										console.log(ot_price_no + "|" + ot_nadb_mat + "|" + ot_price_mat)
										
										console.log("------------------Операция Печать----------------");
										
										console.log("Кол-во листов | Цена | Надбавка на кат. | Надбавка на тираж | Стоимость");
										if (new_wir == '1'){
											
											tir_list = Number(sqrs) * Number(n_cir) * Number(kot_total);
											tir_list = tir_list.toFixed(2);
										}
										
										//расчет для офсета
										/*if($('#p_eq_' + i + ' option:selected').attr('data-flag-offset') == 1)
										{
											var id_eq = parseInt($('#p_eq_' + i + ' option:selected').val());
											var id_color = parseInt($('#p_color_' + i + ' option:selected').val());
											var id_format = parseInt($('#p_sizep_' + i + ' option:selected').attr('data-attr-id'));
											if(id_eq > 0 && id_color > 0 && tir_list > 0 && id_format > 0)
											{
												//Функция отправки запроса
												getOffsetPrice(id_eq, id_color, tir_list, '#p_off_' + i, id_format);
											}
											else
												$('#p_off_' + i).val("");
										}
										else
											$('#p_off_' + i).val("");*/
										
										ob2_tir_list = String(tir_list);
										while (ob2_tir_list.length < 14) {
											ob2_tir_list = ob2_tir_list + " ";
										}
										
										ob2_price_oper = String(price_oper);
										while (ob2_price_oper.length < 6) {
											ob2_price_oper = ob2_price_oper + " ";
										}
										
										ob2_nadbv = String(nadbv);
										while (ob2_nadbv.length < 18) {
											ob2_nadbv = ob2_nadbv + " ";
										}
										
										ob2_nadbvv_tir = String(nadbvv_tir);
										while (ob2_nadbvv_tir.length < 19) {
											ob2_nadbvv_tir = ob2_nadbvv_tir + " ";
										}
										
										ob2_print_sum = String(print_sum);
										while (ob2_print_sum.length < 10) {
											ob2_print_sum = ob2_print_sum + " ";
										}
										console.log(ob2_tir_list + "|" + ob2_price_oper + "|" + ob2_nadbv + "|" + ob2_nadbvv_tir + "|" + ob2_print_sum)
										 
										if (new_wir == '1'){
											
											
											//console.log("asdasd " + print_sum + " " + tir_list + " " + price_mat)
											sun_tir = (Number(print_sum) * Number(tir_list))  + Number(price_mat);	//Стоимость печати за тираж	
											sun_tir = sun_tir.toFixed(2);
										}
										else  {
											sun_tir = (Number(print_sum) + Number(price_mat)) * Number(tir_list);	//Стоимость печати за тираж	
											//приладочные страницы для офсетной печати
											if($('#p_eq_' + i + ' option:selected').attr('data-flag-offset') == 1)
											{
												if(parseInt($('#p_off_' + i).attr('data-plus-list')) > 0)
												{
													sun_tir += Number(price_mat) * parseInt($('#p_off_' + i).attr('data-plus-list'));
												}
											}
											sun_tir = sun_tir.toFixed(2);
										}
										//	console.log("кол-во листов " + tir_list + " Цена материала "  + price_mat + " Стоимость печати полная " + print_sum + " надб " + nadbv + " стоимость печати " + price_oper + " надбавка на тираж " + nadbvv_tir)
										//sun_tir = (Number(print_sum) + Number(price_mat)) * Number(tir_list);	//Стоимость печати за тираж	
										//sun_tir = sun_tir.toFixed(2);
										
										console.log("-------------СТОИМОСТЬ ПЕЧАТИ + МАТЕРИАЛ--------------")
										console.log("СТОИМОСТЬ | СЕБЕСТОИМОСТЬ")
										
										ob_sun_tir = String(sun_tir);
										while (ob_sun_tir.length < 10) {
											ob_sun_tir = ob_sun_tir + " ";
										}
										seb_tir = (Number(price_oper) + Number(new_price)) * Number(tir_list);
										seb_tir = seb_tir.toFixed(2);
										ob_seb_tir = seb_tir
										while (ob_seb_tir.length < 14) {
											ob_seb_tir = ob_seb_tir + " ";
										}
										console.log(ob_sun_tir + "|" + ob_seb_tir)
										
										
									}
									
									
									//Р
									var P = 1
									rez = 0;
									if (document.getElementById('p_cut_' + i).checked) {
										P = 1.1;
										
										/*if(new_wir != 1){
											kof_rez = 2;
											all_perim = Number(perim) * Number(n_cir) * Number(kot_total);
											rez =  all_perim * kof_rez;
										} else {*/
										rez = Number(seb_tir) * P - Number(seb_tir);
										rez = rez.toFixed(2);
										//}
										
										if (rez != 0) {
											console.log("Стоимость резки: " + String(rez) + " $")
										}
									}
									
									
									price_perim = 0;
									
									
									
									if (document.getElementById('p_bug_' + i).value != "") {
										//Цена биговка	
										//	var kol_bug = Number((document.getElementById('p_bug_' + i).value).replace(",", ".")) * n_cir * kot_total;
										var kol_bug = Number((document.getElementById('p_bug_' + i).value).replace(",", ".")) * tir_list * new_pepr;
										var price_bug;
										if (kol_bug > 0 && kol_bug < 50) {
											price_bug = kol_bug * 0.06
										}
										else if (kol_bug >= 50 && kol_bug < 100) {
											price_bug = kol_bug * 0.05
										}
										else if (kol_bug >= 100 && kol_bug < 200) {
											price_bug = kol_bug * 0.04
										}
										else if (kol_bug >= 200 && kol_bug < 300) {
											price_bug = kol_bug * 0.035
										}
										else if (kol_bug >= 300 && kol_bug < 500) {
											price_bug = kol_bug * 0.03
										}
										else if (kol_bug >= 500 && kol_bug < 1000) {
											price_bug = kol_bug * 0.025
										}
										else if (kol_bug >= 1000 && kol_bug < 2000) {
											price_bug = kol_bug * 0.02
										}
										else if (kol_bug >= 2000 && kol_bug < 3000) {
											price_bug = kol_bug * 0.01
										}
										else if (kol_bug >= 3000 && kol_bug < 5000) {
											price_bug = kol_bug * 0.009
										}
										else if (kol_bug >= 5000) {
											price_bug = kol_bug * 0.008
										}
										price_bug = price_bug.toFixed(2);
										console.log("Стоимость биговки " + price_bug + " $")
										} else {
										price_bug = 0
									}
									
									if (document.getElementById('p_perf_' + i).value != "") {
										//Цена перфорация	
										//var kol_perf = Number((document.getElementById('p_perf_' + i).value).replace(",", ".")) * n_cir * kot_total;
										var kol_perf = Number((document.getElementById('p_perf_' + i).value).replace(",", ".")) * tir_list * new_pepr;
										var price_perf;
										if (kol_perf > 0 && kol_perf < 50) {
											price_perf = kol_perf * 0.065
										}
										else if (kol_perf >= 50 && kol_perf < 100) {
											price_perf = kol_perf * 0.055
										}
										else if (kol_perf >= 100 && kol_perf < 200) {
											price_perf = kol_perf * 0.045
										}
										else if (kol_perf >= 200 && kol_perf < 300) {
											price_perf = kol_perf * 0.04
										}
										else if (kol_perf >= 300 && kol_perf < 500) {
											price_perf = kol_perf * 0.035
										}
										else if (kol_perf >= 500 && kol_perf < 1000) {
											price_perf = kol_perf * 0.03
										}
										else if (kol_perf >= 1000 && kol_perf < 2000) {
											price_perf = kol_perf * 0.025
										}
										else if (kol_perf >= 2000 && kol_perf < 3000) {
											price_perf = kol_perf * 0.015
										}
										else if (kol_perf >= 3000 && kol_perf < 5000) {
											price_perf = kol_perf * 0.014
										}
										else if (kol_perf >= 5000) {
											price_perf = kol_perf * 0.013
										}
										price_perf = price_perf.toFixed(2);
										console.log("Стоимость перфорации " + price_perf + " $")
										} else {
										price_perf = 0
									}
									price_lam = 0;
									//Цена ламинирование	
									sel_lam_total = document.getElementById("p_lam_" + i).length;
									for (var y = 0; y < sel_lam_total; y++) {
										if (document.getElementById("p_lam_" + i).options[y].selected == true) {
											var val_mat = (document.getElementById("p_lam_" + i).options[y].title).split('|');
											switch (nadb) {
												case "2":
												price_lam = Number(val_mat[0]) * tir_list;
												break;
												case "3":
												price_lam = Number(val_mat[1]) * tir_list;
												break;
												case "5":
												price_lam = Number(val_mat[3]) * tir_list;
												break;
												default:
												price_lam = Number(val_mat[2]) * tir_list;
												break;
											}
										}
									}
									price_lam = price_lam.toFixed(2); 		//Цена ламинирование	
									
									if (price_lam != 0) {
										console.log("Стоимость ламинирование " + price_lam + " $")
									}
									
									if (document.getElementById('p_ygl_' + i).value != "") {
										//Цена углы	
										//var kol_ygl = Number((document.getElementById('p_ygl_' + i).value).replace(",", ".")) * n_cir * kot_total;
										var kol_ygl = Number((document.getElementById('p_ygl_' + i).value).replace(",", ".")) * tir_list * new_pepr;
										var price_ygl;
										
										if (kol_ygl > 0 && kol_ygl < 50) {
											price_ygl = kol_ygl * 0.005
										}
										else if (kol_ygl >= 50 && kol_ygl < 100) {
											price_ygl = kol_ygl * 0.004
										}
										else if (kol_ygl >= 100 && kol_ygl < 200) {
											price_ygl = kol_ygl * 0.0035
										}
										else if (kol_ygl >= 200 && kol_ygl < 300) {
											price_ygl = kol_ygl * 0.003
										}
										else if (kol_ygl >= 300 && kol_ygl < 500) {
											price_ygl = kol_ygl * 0.0025
										}
										else if (kol_ygl >= 500 && kol_ygl < 1000) {
											price_ygl = kol_ygl * 0.0023
										}
										else if (kol_ygl >= 1000 && kol_ygl < 2000) {
											price_ygl = kol_ygl * 0.0022
										}
										else if (kol_ygl >= 2000 && kol_ygl < 3000) {
											price_ygl = kol_ygl * 0.0021
										}
										else if (kol_ygl >= 3000 && kol_ygl < 5000) {
											price_ygl = kol_ygl * 0.002
										}
										else if (kol_ygl >= 5000 && kol_ygl < 10000) {
											price_ygl = kol_ygl * 0.0019
										}
										else if (kol_ygl >= 10000 && kol_ygl < 20000) {
											price_ygl = kol_ygl * 0.0018
										}
										else if (kol_ygl >= 20000 && kol_ygl < 30000) {
											price_ygl = kol_ygl * 0.0017
										}
										else if (kol_ygl >= 30000 && kol_ygl < 50000) {
											price_ygl = kol_ygl * 0.0016
										}
										else if (kol_ygl >= 50000) {
											price_ygl = kol_ygl * 0.0015
										}
										
										price_ygl = price_ygl.toFixed(2); 				//Цена углы	
										console.log("Стоимость углы " + price_ygl + " $")
										} else {
										price_ygl = 0
									}
									
									//Цена отверстия	 p_otv_1
									if (document.getElementById('p_otv_' + i).value != "") {
										//	var kol_otv = Number((document.getElementById('p_otv_' + i).value).replace(",", ".")) * n_cir * kot_total;
										var kol_otv = Number((document.getElementById('p_otv_' + i).value).replace(",", ".")) * tir_list * new_pepr;
										var price_otv;
										
										if (kol_otv > 0 && kol_otv < 50) {
											price_otv = kol_otv * 0.02
										}
										else if (kol_otv >= 50 && kol_otv < 100) {
											price_otv = kol_otv * 0.01
										}
										else if (kol_otv >= 100 && kol_otv < 200) {
											price_otv = kol_otv * 0.009
										}
										else if (kol_otv >= 200 && kol_otv < 300) {
											price_otv = kol_otv * 0.008
										}
										else if (kol_otv >= 300 && kol_otv < 500) {
											price_otv = kol_otv * 0.007
										}
										else if (kol_otv >= 500 && kol_otv < 1000) {
											price_otv = kol_otv * 0.006
										}
										else if (kol_otv >= 1000 && kol_otv < 2000) {
											price_otv = kol_otv * 0.005
										}
										else if (kol_otv >= 2000 && kol_otv < 3000) {
											price_otv = kol_otv * 0.004
										}
										else if (kol_otv >= 3000) {
											price_otv = kol_otv * 0.003
										}
										
										price_otv = price_otv.toFixed(2); 				//Цена отверстия	
										console.log("Стоимость отверстия " + price_otv + " $")
										} else {
										price_otv = 0
									}
									
									if (document.getElementById('p_luv_' + i).value != "") {
										//Цена люверс	
										//var kol_luv = Number((document.getElementById('p_luv_' + i).value).replace(",", ".")) * n_cir * kot_total;
										var kol_luv = Number((document.getElementById('p_luv_' + i).value).replace(",", ".")) * tir_list * new_pepr;
										var price_luv;
										
										if (kol_luv > 0 && kol_luv < 50) {
											price_luv = kol_luv * 0.15
										}
										else if (kol_luv >= 50 && kol_luv < 100) {
											price_luv = kol_luv * 0.13
										}
										else if (kol_luv >= 100 && kol_luv < 200) {
											price_luv = kol_luv * 0.12
										}
										else if (kol_luv >= 200 && kol_luv < 300) {
											price_luv = kol_luv * 0.11
										}
										else if (kol_luv >= 300 && kol_luv < 500) {
											price_luv = kol_luv * 0.1
										}
										else if (kol_luv >= 500 && kol_luv < 1000) {
											price_luv = kol_luv * 0.09
										}
										else if (kol_luv >= 1000 && kol_luv < 2000) {
											price_luv = kol_luv * 0.085
										}
										else if (kol_luv >= 2000 && kol_luv < 3000) {
											price_luv = kol_luv * 0.08
										}
										else if (kol_luv >= 3000 && kol_luv < 5000) {
											price_luv = kol_luv * 0.075
										}
										else if (kol_luv >= 5000 && kol_luv < 10000) {
											price_luv = kol_luv * 0.07
										}
										else if (kol_luv >= 10000 && kol_luv < 20000) {
											price_luv = kol_luv * 0.065
										}
										else if (kol_luv >= 20000 && kol_luv < 30000) {
											price_luv = kol_luv * 0.06
										}
										else if (kol_luv >= 30000 && kol_luv < 50000) {
											price_luv = kol_luv * 0.055
										}
										else if (kol_luv >= 50000) {
											price_luv = kol_luv * 0.05
										}
										
										price_luv = price_luv.toFixed(2);  //Цена люверс	
										console.log("Стоимость люверс " + price_luv + " $")
										} else {
										price_luv = 0
									}
									
									if (document.getElementById('p_vir_' + i).value != "") {
										//Вырубка. Кол-во ударов 
										//kot_rub = n_cir * kot_total / Number((document.getElementById('p_vir_' + i).value).replace(",", "."));
										kot_rub =  tir_list * new_pepr  / Number((document.getElementById('p_vir_' + i).value).replace(",", "."));
										kot_rub = Math.ceil(kot_rub);
										
										
										var price_rub = 0; //Цена вырубка	
										//Цена вырубка	
										if (kot_rub > 0 && kot_rub < 50) {
											price_rub = kot_rub * 0.6
										}
										else if (kot_rub >= 50 && kot_rub < 100) {
											price_rub = kot_rub * 0.3
										}
										else if (kot_rub >= 100 && kot_rub < 200) {
											price_rub = kot_rub * 0.25
										}
										else if (kot_rub >= 200 && kot_rub < 300) {
											price_rub = kot_rub * 0.2
										}
										else if (kot_rub >= 300 && kot_rub < 500) {
											price_rub = kot_rub * 0.15
										}
										else if (kot_rub >= 500 && kot_rub < 1000) {
											price_rub = kot_rub * 0.1
										}
										else if (kot_rub >= 1000 && kot_rub < 2000) {
											price_rub = kot_rub * 0.08
										}
										else if (kot_rub >= 2000 && kot_rub < 3000) {
											price_rub = kot_rub * 0.06
										}
										else if (kot_rub >= 3000 && kot_rub < 5000) {
											price_rub = kot_rub * 0.05
										}
										else if (kot_rub >= 5000) {
											price_rub = kot_rub * 0.04
										}
										price_rub = price_rub.toFixed(2);
										if (price_rub < min_vir && price_rub > 0) {
											price_rub = min_vir
										}
										
										console.log("Стоимость вырубка " + price_rub + " $")
										} else {
										price_rub = 0
									}
									
									//Конгрев. Кол-во ударов	
									//"Конгрев. Цена за 1 удар"	
									//Цена конгрев	
									
									if (document.getElementById('p_con_' + i).value != "") {
										//kot_con = n_cir * kot_total / Number((document.getElementById('p_con_' + i).value).replace(",", "."));
										kot_con = tir_list * new_pepr / Number((document.getElementById('p_con_' + i).value).replace(",", "."));
										kot_con = Math.ceil(kot_con);
										var price_con; //Цена конгрев	
										//Цена конгрев	
										if (kot_con > 0 && kot_con < 50) {
											price_con = kot_con * 0.8
										}
										else if (kot_con >= 50 && kot_con < 100) {
											price_con = kot_con * 0.45
										}
										else if (kot_con >= 100 && kot_con < 200) {
											price_con = kot_con * 0.27
										}
										else if (kot_con >= 200 && kot_con < 300) {
											price_con = kot_con * 0.23
										}
										else if (kot_con >= 300 && kot_con < 500) {
											price_con = kot_con * 0.16
										}
										else if (kot_con >= 500 && kot_con < 1000) {
											price_con = kot_con * 0.09
										}
										else if (kot_con >= 1000 && kot_con < 2000) {
											price_con = kot_con * 0.08
										}
										else if (kot_con >= 2000 && kot_con < 3000) {
											price_con = kot_con * 0.07
										}
										else if (kot_con >= 3000 && kot_con < 5000) {
											price_con = kot_con * 0.06
										}
										else if (kot_con >= 5000 && kot_con < 10000) {
											price_con = kot_con * 0.05
										}
										else if (kot_con >= 10000) {
											price_luv = kot_con * 0.04
										}
										price_con = price_con.toFixed(2);
										if (price_con < min_con && price_con > 0) {
											price_con = min_con
										}
										console.log("Стоимость конгрев " + price_rub + " $")
										} else {
										price_con = 0
									}
									var price_p; //Цена Плотерная	
									price_p = 0;
									//Плотерная резка
                                    if(document.getElementById('p_size_cut_op_' + i).value && document.getElementById('p_size_cut_eq_' + i).value) {
                                        kot_p = Number(n_cir) * Number(kot_total) * (Number((document.getElementById('p_size_cut2_' + i).value).replace(",", ".")) / 1000);
                                        kot_p = Math.ceil(Math.floor(kot_p * 1000) / 1000);
                                        let obj = {
                                            'p_size_cut_op': document.getElementById('p_size_cut_op_' + i).value,
                                            'p_size_cut_eq': document.getElementById('p_size_cut_eq_' + i).value,
                                        }
                                        let costOper = sendAjax('m=ajaxs&u=systemOld&a=getCostOperations', obj, null, false, true);
                                        price_p = kot_p * parseFloat(costOper);
                                        price_p = price_p.toFixed(2);
                                        console.log("Стоимость плотерной резки " + price_p + " $");

                                    } else if ((document.getElementById('p_cut2_' + i).checked) && (document.getElementById('p_size_cut_' + i).value != "")) {
										var matter = "";
										if(document.getElementById('p_mat_' + i).selectedIndex >= 0 && document.getElementById('p_mat_' + i).options[document.getElementById('p_mat_' + i).selectedIndex].hasAttribute("data-opt_gr")) {
											matter = document.getElementById('p_mat_' + i).options[document.getElementById('p_mat_' + i).selectedIndex].getAttribute("data-opt_gr");
										}
										kot_p = Number(n_cir) * Number(kot_total) * (Number((document.getElementById('p_size_cut_' + i).value).replace(",", ".")) / 1000);
										//kot_p = Number(tir_list) * (Number((document.getElementById('p_size_cut_' + i).value).replace(",", ".")) / 1000);
										kot_p = Math.ceil(Math.floor(kot_p * 1000) / 1000);
										
										kot_p = Number(kot_p);
										if(matter != "Бумага самоклеящаяся" && matter != "Пленка самоклеящаяся") {
											var send = {
												operation: 93,
												width: document.getElementById('p_size_cut_' + i).value
											}
											$.ajax({
												type: "GET",
												url: "modeler.php",
												data: {'CostOperations': JSON.stringify(send)},
												cache: false,
												async: false,
												success: function(respond) {
													if(respond) {
														var answer = JSON.parse(respond);
														if(answer) {
															price_p = kot_p * parseFloat(answer);
														}
													}
												}
											});
										}
										else {										
											//Цена Плотерная	
											
											if (kot_p > 0 && kot_p < 10) {
												price_p = kot_p * 0.3
											}
											else if (kot_p >= 10 && kot_p < 20) {
												price_p = kot_p * 0.25
											}
											else if (kot_p >= 20 && kot_p < 50) {
												price_p = kot_p * 0.2
											}
											else if (kot_p >= 50 && kot_p < 100) {
												price_p = kot_p * 0.1
											}
											else if (kot_p >= 100 && kot_p < 500) {
												price_p = kot_p * 0.08
											}
											else if (kot_p >= 500 && kot_p < 1000) {
												price_p = kot_p * 0.07
											}
											else if (kot_p >= 1000) {
												price_p = kot_p * 0.06
											}
										}
										price_p = Number(price_p);
										price_p = price_p.toFixed(2);
										console.log("Стоимость плотерной резки " + price_p + " $")
									} else {
										price_p = 0
									}
									
									
									//Тиснение. Кол-во ударов	
									//"Тиснение. Цена за 1 удар"	
									//Цена тиснение	
									if (document.getElementById('p_tis_' + i).value != "") {
										//kot_tis = n_cir * kot_total / Number((document.getElementById('p_tis_' + i).value).replace(",", "."));
										kot_tis = tir_list * new_pepr / Number((document.getElementById('p_tis_' + i).value).replace(",", "."));
										kot_tis = Math.ceil(kot_tis);
										var price_tis; //Цена тиснение	
										//Цена тиснение	
										if (kot_tis > 0 && kot_tis < 50) {
											price_tis = kot_tis * 0.9
										}
										else if (kot_tis >= 50 && kot_tis < 100) {
											price_tis = kot_tis * 0.5
										}
										else if (kot_tis >= 100 && kot_tis < 200) {
											price_tis = kot_tis * 0.3
										}
										else if (kot_tis >= 200 && kot_tis < 300) {
											price_tis = kot_tis * 0.25
										}
										else if (kot_tis >= 300 && kot_tis < 500) {
											price_tis = kot_tis * 0.17
										}
										else if (kot_tis >= 500 && kot_tis < 1000) {
											price_tis = kot_tis * 0.1
										}
										else if (kot_tis >= 1000 && kot_tis < 2000) {
											price_tis = kot_tis * 0.09
										}
										else if (kot_tis >= 2000 && kot_tis < 3000) {
											price_tis = kot_tis * 0.08
										}
										else if (kot_tis >= 3000 && kot_tis < 5000) {
											price_tis = kot_tis * 0.07
										}
										else if (kot_tis >= 5000 && kot_tis < 10000) {
											price_tis = kot_tis * 0.06
										}
										else if (kot_tis >= 10000 && kot_tis < 20000) {
											price_tis = kot_tis * 0.05
										}
										else if (kot_tis >= 20000) {
											price_tis = kot_tis * 0.04
										}
										price_tis = price_tis.toFixed(2);
										console.log("Стоимость тиснение " + price_tis + " $")
										if (price_tis < min_tis && price_tis > 0) {
											price_tis = min_tis
										}
										} else {
										price_tis = 0
									}
									
									
									price_per = 0;
									sel_per_total = document.getElementById("p_per_i").length;
									for (var y = 0; y < sel_per_total; y++) {
										if (document.getElementById("p_per_i").options[y].selected == true) {
											var val_mat = (document.getElementById("p_per_i").options[y].title).split('|');
											switch (nadb) {
												case "2":
												price_per = Number(val_mat[0]) * n_cir * kot_total;
												break;
												case "3":
												price_per = Number(val_mat[1]) * n_cir * kot_total;
												break;
												case "5":
												price_per = Number(val_mat[3]) * n_cir * kot_total;
												break;
												default:
												price_per = Number(val_mat[2]) * n_cir * kot_total;
												break;
											}
										}
									}
									price_per = price_per.toFixed(2); 		//Цена переплет	
									if (price_per != 0) {
										console.log("Стоимость переплета " + price_per + " $")
									}
									if (isNaN(sun_tir)) {
										sun_tir = 0;
									}
									
									if (isNaN(price_bug)) {
										price_bug = 0;
									}
									if (isNaN(price_perf)) {
										price_perf = 0;
									}
									if (isNaN(price_lam)) {
										price_lam = 0;
									}
									if (isNaN(price_ygl)) {
										price_ygl = 0;
									}
									if (isNaN(price_otv)) {
										price_otv = 0;
									}
									if (isNaN(price_luv)) {
										price_luv = 0;
									}
									if (isNaN(price_rub)) {
										price_rub = 0;
									}
									
									if (isNaN(price_con)) {
										price_con = 0;
									}
									if (isNaN(price_tis)) {
										price_tis = 0;
									}
									if (isNaN(price_p)) {
										price_p = 0;
									}
									if (isNaN(price_perim)) {
										price_perim = 0;
									}
									if (isNaN(fast)) {
										fast = 1;
									}
									
									
									if (fast == 0) {
										fast = 1;
									}
									
									
									//Работы на стороне
									if (p_off != 0) {
										console.log("Работы на стороне: " + p_off + "BYN")
									}
									
									//Приладочные страницы
									if($('#p_eq_' + i + ' option:selected').attr('data-flag-offset') == 1)
									{
										if(parseInt($('#p_off_' + i).attr('data-plus-list')) > 0)
										{
											console.log("Приладочные страницы: " + parseInt($('#p_off_' + i).attr('data-plus-list')));
										}
									}
									
									//Цена штампа:
									if (p_pr_stamp != 0) {
										console.log("Цена штампа: " + p_pr_stamp + "BYN")
									}
									
									//Цена клише, конгрев:
									if (p_pr_kl != 0) {
										console.log("Цена клише, конгрев: " + p_pr_kl + "BYN")
									}
									
									//Цена клише, тиснение:
									if (p_prc_kl != 0) {
										console.log("Цена клише, тиснение: " + p_prc_kl + "BYN")
									}
									
									//Стоимость дизайна:
									if (p_pr_diz != 0) {
										console.log("Стоимость дизайна: " + p_pr_diz + "BYN")
									}
									
									//Стоимость препресса:
									if (p_press != 0) {
										console.log("Стоимость препресса: " + p_press + "BYN")
									}
									//Срочность кофф.
									
									console.log("Срочность кофф.: " + fast)
									
									kuByn = '<? echo $kurs1; ?>' ;
									console.log("КУРС " + kuByn);
									
									sumss = ((Number(sun_tir) + Number(rez) + Number(price_bug) + Number(price_perf) + Number(price_lam) + Number(price_ygl) + Number(price_otv) + Number(price_luv) + Number(price_rub) + Number(price_con) + Number(price_tis) + Number(price_p) + Number(price_perim)) * Number(fast));
									
									sumss1 = Number(p_off) + Number(p_pr_stamp) + Number(p_pr_kl) + Number(p_prc_kl) + Number(p_pr_diz) + Number(p_press);
									
									arr.push(sumss * kuByn + sumss1);
									
									
									/*console.log(sun_tir + " " + P + "  " + price_bug + " " + price_perf + " " + price_lam + " " + price_ygl + " " + price_otv + " " + price_luv + " " + price_rub + " " + price_con + " " + price_tis + " " + price_p + " " + price_perim  + " " + fast + " " + p_off + " " + p_pr_stamp + " " + p_pr_kl + " " + p_prc_kl + " " + p_pr_diz + " " + p_press)
										console.log("Стоимость переплета " + price_per + "$")		
										console.log("Стоимость плоттерной резки " + price_p + "$")		
										console.log("Стоимость широкофотной резки " + price_perim + "$")		
									*/
									
									if (isNaN(sumss)) {
										sumss = 0;
									}
									if (isNaN(sumss1)) {
										sumss1 = 0;
									}
									if (document.getElementById('p_cut2_' + i).checked && (!document.getElementById('p_size_cut_op_' + i).value || !document.getElementById('p_size_cut_eq_' + i).value)) {
										
										if (document.getElementById('p_size_cut_' + i).value == '') {
											sumss = 0;
										}
									}
									//console.log("sumss " + sumss)
									
									sum_all = Number(sum_all) + Number(sumss);
									sum_all1 = Number(sum_all1) + Number(sumss1);
									if (document.getElementById('p_cut_' + i).checked) {
										p_cut_1 = '1';
										} else {
										p_cut_1 = '0';
									}
									if (document.getElementById('p_cut2_' + i).checked) {
										p_cut_2 = '1';
										} else {
										p_cut_2 = '0';
									}
									if (document.getElementById('vin1' + i).checked) {
										vin_1 = '1';
										} else {
										vin_1 = '0';
									}
									if (document.getElementById('max' + i).checked) {
										max_1 = '1';
										} else {
										max_1 = '0';
									}
									if (document.getElementById('pol' + i).checked) {
										pol_1 = '1';
										} else {
										pol_1 = '0';
									}
									
									if (document.getElementById('pers' + i).checked) {
										pers_1 = '1';
										} else {
										pers_1 = '0';
									}
									if (document.getElementById('mat_firm' + i).checked) {
										mat_firm1 = '1';
										} else {
										mat_firm1 = '0';
									}

									var clr_str = "";
									var clr_str1 = "";
									var clr_str2 = "";
									var clr_arr = $("#p_color_" + i + " option:selected").text().split('+');
                                    clr_str1 = clr_arr[0] && clr_arr[0] != 0 ? clr_arr[0] : "";
                                    clr_str1 = clr_str1 == 1 ? 'K' : clr_str1;
                                    clr_str1 = clr_str1 == 4 ? 'CMYK' : clr_str1;
                                    clr_str2 = clr_arr[1] && clr_arr[1] != 0 ? clr_arr[1] : "";
                                    clr_str2 = clr_str2 == 1 ? 'K' : clr_str2;
                                    clr_str2 = clr_str2 == 4 ? 'CMYK' : clr_str2;
									var combi_tm_f = "";
									if(arr_combi_f) {
									    for(var cf in arr_combi_f) {
                                            combi_tm_f += (combi_tm_f ? "~" : "") + arr_combi_f[cf];
                                            var spl = arr_combi_f[cf].split('_');
                                            eval("var _co = COMBI_OBJECT.combi" + document.getElementById('p_eq_' + i).value);
                                            var nm_combi = "";
                                            for(var _c in _co) {
                                                if(spl[0] == _co[_c].id) {
                                                    nm_combi = _co[_c].name;
                                                    break;
                                                }
                                            }
                                            clr_str1 += nm_combi.substr(0, 1).toUpperCase();
                                        }
                                    }
                                    var combi_tm_b = "";
                                    if(arr_combi_b) {
                                        for(var cf in arr_combi_b) {
                                            combi_tm_b += (combi_tm_b ? "~" : "") + arr_combi_b[cf];
                                            var spl = arr_combi_b[cf].split('_');
                                            eval("var _co = COMBI_OBJECT.combi" + document.getElementById('p_eq_' + i).value);
                                            var nm_combi = "";
                                            for(var _c in _co) {
                                                if(spl[0] == _co[_c].id) {
                                                    nm_combi = _co[_c].name;
                                                    break;
                                                }
                                            }
                                            clr_str2 += nm_combi.substr(0, 1).toUpperCase();
                                        }
                                    }
                                    clr_str = clr_str1 + (clr_str2 ? "+" + clr_str2 : "");
                                    clr_str = (!combi_tm_f && !combi_tm_b) ? $("#p_color_" + i + " option:selected").text() : clr_str;

									str_tm = str_tm + document.getElementById('p_namepart_' + i).value + "|" + document.getElementById('p_size_' + i).value + "|" + document.getElementById('p_kolstr_' + i).value + "|" + document.getElementById('p_eq_' + i).value + "|" + document.getElementById('p_color_' + i).value + "|" + document.getElementById('p_sizep_' + i).value + "|" + document.getElementById('p_mat_' + i).value + "|" + p_cut_1 + "|" + document.getElementById('p_lam_' + i).value + "|" + document.getElementById('p_bug_' + i).value + "|" + document.getElementById('p_perf_' + i).value + "|" + document.getElementById('p_ygl_' + i).value + "|" + document.getElementById('p_otv_' + i).value + "|" + document.getElementById('p_diam_' + i).value + "|" + document.getElementById('p_luv_' + i).value + "|" + document.getElementById('p_colorluv_' + i).value + "|" + document.getElementById('p_vir_' + i).value + "|" + document.getElementById('p_con_' + i).value + "|" + document.getElementById('p_tis_' + i).value + "|" + document.getElementById('p_off_' + i).value + "|" + document.getElementById('p_prstamp_' + i).value + "|" + document.getElementById('p_prkl_' + i).value + "|" + document.getElementById('p_prckl_' + i).value + "|" + document.getElementById('p_prdiz_' + i).value + "|" + document.getElementById('p_press_' + i).value + "|" + document.getElementById('vin' + i).value + "|" + vin_1 + "|" + max_1 + "|" + pol_1 + "|" + pers_1 + "|" + mat_firm1 + "|" + document.getElementById('p_size_r_' + i).value + "|" + p_cut_2 + "|" + document.getElementById('p_size_cut_' + i).value + "|" + document.getElementById('p_new_size_' + i).value + (combi_tm_f || combi_tm_b ? "|" + combi_tm_f + "|" + combi_tm_b : "||") + "|" + document.getElementById('p_size_cut_op_' + i).value + "|" + document.getElementById('p_size_cut_eq_' + i).value + "|" + document.getElementById('p_size_cut2_' + i).value + "^";
									
									str_tm1 = str_tm1 + document.getElementById('p_namepart_' + i).value + "|" + document.getElementById('p_size_' + i).value + "|" + document.getElementById('p_kolstr_' + i).value + "|" + $("#p_eq_" + i + " option:selected").text() + "|" + clr_str + "|" + $("#p_sizep_" + i + " option:selected").text() + "|" + $("#p_mat_" + i + " option:selected").text() + " : " + tir_list + "|" + p_cut_1 + "|" + $("#p_lam_" + i + " option:selected").text() + "|" + document.getElementById('p_bug_' + i).value + "|" + document.getElementById('p_perf_' + i).value + "|" + document.getElementById('p_ygl_' + i).value + "|" + document.getElementById('p_otv_' + i).value + "|" + $("#p_diam_" + i + " option:selected").text() + "|" + document.getElementById('p_luv_' + i).value + "|" + $("#p_colorluv_" + i + " option:selected").text() + "|" + document.getElementById('p_vir_' + i).value + "|" + document.getElementById('p_con_' + i).value + "|" + document.getElementById('p_tis_' + i).value + "|" + document.getElementById('vin1' + i).value + "|" + vin_1 + "|" + max_1 + "|" + pol_1 + "|" + pers_1 + "|" + mat_firm1 + "|" + document.getElementById('p_size_r_' + i).value + "|" + p_cut_2 + "|" + document.getElementById('p_size_cut_' + i).value + "|" + document.getElementById('p_new_size_' + i).value + "|" + document.getElementById('p_size_cut_op_' + i).value + "|" + document.getElementById('p_size_cut_eq_' + i).value + "|" + document.getElementById('p_size_cut2_' + i).value + "^";
									console.log("_______________________________________________________")
									
									
									/* if(i < part){ i++; }
									else  {*/
									
									
									/*if (i < part){
										i++;
										continue outer;
										} 
									else {*/
									
								}

								if (sum_all > 0) {
									sum_all = Number(sum_all) + Number(price_per)
									sum_all = (sum_all * Number(kuByn) * nds_);
									sum_all = sum_all.toFixed(2);
								}
								else sum_all = 0;
								sum_all = Number(sum_all) + Number(sum_all1);
								nadb_f = "<?echo $nadb_f;?>";
								nadb_f = 1 + (Number(nadb_f) / 100);
								console.log("Первая сумма " + sum_all)
								sum_all = Number(sum_all) * Number(nadb_f);
								sum_all = sum_all.toFixed(2);
								console.log("Cумма  c надбавкой фирмы " + sum_all)
								price_ed = ((sum_all / n_cir) / 1.2);
								price_ed = price_ed.toFixed(2);
								if (Number(price_ed) < 0.01 && Number(price_ed) > 0) {
									if (document.getElementById('no_price').checked == false) {
										if ($("#unit_prod1").val() != "тыс.шт.") {
											$("#unit_prod1").val("тыс.шт.");
											
											$("#p_cir").val(Number(n_cir) / 1000);
											get_price();
										}
										
										else {
											if (document.getElementById('no_price').checked == false) {
												document.getElementById('p_sum_all').value = 0;
											document.getElementById('p_price_all').value = 0; }
											console.log("ОШИБКА В РАСЧЕТАХ!!! ЦЕНА ЗА ЕДИНИЦУ МЕНЬШЕ 0.01")
											l_error = 1;
											//return;
											
										}
									}
									
									
								}
								price_ed = price_ed * 1.2;
								
								price_ed = price_ed.toFixed(3);
								
								sum_all = price_ed * n_cir;
								sum_all = sum_all.toFixed(2);
								
								if (isNaN(sum_all)) {
									sum_all = 0;
								}
								console.log("Пересчитанная сумма " + sum_all)
								
								if (document.getElementById('no_price').checked == false) {
									document.getElementById('p_sum_all').value = sum_all;
									document.getElementById('p_price_all').value = price_ed;
								}
								
								
								document.getElementById('p_sum_sys').value = sum_all;
								
								
								str_tm = str_tm.substring(0, str_tm.length - 1);
								document.getElementById('str_tm').value = str_tm;
								str_tm1 = str_tm1.substring(0, str_tm1.length - 1);
								document.getElementById('str_tm1').value = str_tm1;
								document.getElementById('sum_press').value = p_pr_diz;
								
								//Общая стоимость работ 
								console.log("Стоимость частей");
								kolasd = 0;
								sum_all_arr = 0;
								for (y = 0; y < arr.length; y++) {
									kolasd++;
									sum_all_arr = Number(arr[y]);
									sum_all_arr = sum_all_arr.toFixed(2);
									
									console.log("Часть_" + kolasd + " = " + sum_all_arr + " BYN");
								}
								
								
								console.log("                                         ")
								console.log("------------------END--------------------")
								console.log("                                         ")
								//}
								document.getElementById('sum_press').value = p_pr_diz;
								if (document.getElementById('no_price').checked == false) {
									if (l_error == 1) {
										
										document.getElementById('p_sum_all').value = 0;
										document.getElementById('p_price_all').value = 0;
									}
								}
								
							}
							
							//Функция получения информации по офсетам
							/*function getOffsetPrice(id_eq, id_color, tir_list, id_html, id_format)
							{
								var obj = {
									'id_eq': id_eq,
									'id_color': id_color,
									'tir_list': tir_list,
									'id_format': id_format
								}
								
								
								data = {'query':JSON.stringify(obj)};
								var loading = $('.loading');
								$.ajax({
									cache: false,
									url : "_getOffsetPrice.php",
									type: "GET",
									data : data,
									success : function(data, textStatus, jqXHR){
										if(data)
										{
											var mess = JSON.parse(data);
											if(mess)
											{
												var result = parseFloat(mess.value);
												kuByn = '<? echo $kurs1; ?>' ;
												// *100/100 помогает исправить косяк float
												$(id_html).val(Math.round(result * kuByn * 100)/100);
												
												$(id_html).attr('data-plus-list', mess.dop_list);
											}
										}
									},
									error : function(xhr, ajaxOptions, thrownError){
										alert(thrownError);
										return;
									}
								});
							}*/
							
							//Костыль на выбор самоклейки
							function chosenSelected(e) {
								if(e.selectedIndex < 0)
									return;
								
								var dt = e.options[e.selectedIndex].value;
								var id_count = e.id.substring(6);
								
								selectedSize(id_count);
								
								var p_eq = document.getElementById("p_eq_" + id_count);
								if(p_eq.options[p_eq.selectedIndex].value == 1) {
									var text = e.options[e.selectedIndex].getAttribute('data-opt_gr');
									if(text == "Бумага самоклеящаяся") {
										var p_sizep = document.getElementById("p_sizep_" + id_count);
										var opts = p_sizep.getElementsByTagName('option');
										if(opts.length > 0) {
											for(var i = 0; i < opts.length; i++) {
												if(opts[i].style.display == "block") {
													if(opts[i].value != "360*660" && opts[i].value != "350*500") {
														if(opts[i].selected) {
															p_sizep.selectedIndex = -1;
														}
														opts[i].setAttribute('disabled', true);
													}
												}
											}
										}
									}
									else {
										var p_sizep = document.getElementById("p_sizep_" + id_count);
										var opts = p_sizep.getElementsByTagName('option');
										if(opts.length > 0) {
											for(var i = 0; i < opts.length; i++) {
												if(opts[i].style.display == "block") {
													if(opts[i].hasAttribute('disabled')) {
														opts[i].removeAttribute('disabled');
													}
												}
											}
										}
									}
								}
							}
							
							function selectedSize(id_count) {
								clearSelect(document.getElementById("p_sizep_" + id_count), 1);
								var send = {};
								//размер изделия
								if(document.getElementById("p_size_" + id_count).value != "")
									send.size = document.getElementById("p_size_" + id_count).value;
								else if(document.getElementById("p_size").value != "")
									send.size = document.getElementById("p_size").value;
								//оборудование
								if(document.getElementById("p_eq_" + id_count).selectedIndex >= 0)
									send.idprint = document.getElementById("p_eq_" + id_count).value;
								//материал
								if(document.getElementById("p_mat_" + id_count).selectedIndex >= 0)
									send.material = document.getElementById("p_mat_" + id_count).value;
								//вынос цвета
								send.vynos = 0;
								if(document.getElementById("vin" + id_count).value != "")
									send.vynos = document.getElementById("vin" + id_count).value;
								//есть печатное поле
								if(document.getElementById("pol" + id_count).checked)
									send.pol = true;
								else
									send.pol = false;
								//запрос на сервер
								$.ajax({
									type: "GET",
									url: "modeler.php",
									data: {DefineSize: JSON.stringify(send)},
									cache: false,
									async: false,
									success: function(respond) {
										if(respond) {
											var answer = JSON.parse(respond);
											if(answer && Array.isArray(answer) && answer.length > 0) {
												var delta = -1;
												var selValue = 0;
												for(var i = 0; i < answer.length; i++) {
													var opt = document.createElement('option');
													opt.setAttribute('value', answer[i].size);
													opt.setAttribute('data-attr', answer[i].equipment);
													opt.setAttribute('data-attr-id', answer[i].id);
													opt.setAttribute('data-size-material', answer[i].size_in_material);
													opt.setAttribute('data-size-delta', answer[i].delta);
													opt.setAttribute('style', "display: block");
													opt.appendChild(document.createTextNode(answer[i].size));
													document.getElementById("p_sizep_" + id_count).appendChild(opt);
													if(answer[i].delta >= 0 && (delta == -1 || delta > parseFloat(answer[i].delta))) {
														delta = parseFloat(answer[i].delta);
														selValue = answer[i].size;
													}
												}
												if(selValue == 0 && answer.length > 0) {
													document.getElementById("p_sizep_" + id_count).selectedIndex = 1;
												}
												else {
													$("#p_sizep_" + id_count).val(selValue);
												}
												document.getElementById("p_sizep_" + id_count).dispatchEvent(new Event('change'));
											}
										}
									}
								});
							}
							
							//чистим селекты
							function clearSelect(select, num) {
								var num_ = 0;
								if(num)
									num_ = num;
								while(select.options.length > num_) {
									remove(select.options[num_]);
								}
							}
							
						</script>
						
						
						
						<?php
							
							
							
							IF ($CUR_ID == '840') {
								$kurs = $CUR_ID;
							}
							$query = "select val from settings s where s.id = 3";
							$result = mysql_query($query) or die("Query failed1");
							WHILE ($row = mysql_fetch_row($result)) {
								$price_diz = $row[0];
							}
							IF ($price_diz == '') {
								$price_diz = 0;
								} ELSE {
								$price_diz = str_replace(',', '.', $price_diz);
							}
							
							
							echo "<input type = 'hidden' id='price_diz' value = '" . $price_diz . "'>";
							echo "<input type = 'hidden' id='kurs' value = '" . $kurs . "'>";
							
						?>
						<div id="myModal3" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="false">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<button class="close" type="button" onclick="hidemyModal3()">×</button>
										<h4 class="modal-title">Изменение операции дизайн</h4>
									</div>
									<div class="modal-body ">
										<div class="col-lg-12">
											<div class="panel panel-default">
												<div class="panel-body">
													<table width="100%" class="table table-striped table-bordered table-hover"
													id="dataTables1">
														<thead>
															<tr>
																<th>Операция</th>
																<th>Время</th>
																<th>Стоимость</th>
															</tr>
														</thead>
														<tbody>
															<?
																$total_time = 0;
																$total_sum = 0;
																$query = "select  * from DIZ_OPER where DEFAULT_ = 0";
																$result = mysql_query($query) or die($query);
																while ($row = mysql_fetch_row($result)) {
																	$sums = 0;
																	
																	echo "<tr class='odd gradeX'>";
																	echo '<td><label><input type="checkbox" id = d' . $row[0] . '  value=" ' . $row[2] . '"  name="diz"  onclick="fun_view_diz(' . $row[0] . ')">' . $row[1] . '</label></td>';
																	echo '<div id=elemms' . $row[0] . '  style="display:none;"> <td><div class="col-md-3" > 
																	<label id=elemm' . $row[0] . '1 value = "'.$row[2].'">' . $row[2] . '</label>
																	</div></td>';
																	echo '<td><div class="col-md-3" >';
																	$sums = ($row[2] / 60) * $price_diz * $kurs1;
																	
																	echo '<label id=elemm' . $row[0] . '2 value = "'.round($sums, 2).'">' . round($sums, 2) . '</label>
																	</div></td></div>';
																	echo "</tr>";
																	
																}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="panel panel-default">
												<div class="panel-body">
													<table width="100%" class="table table-striped table-bordered table-hover"
													id="dataTables2">
														<thead>
															<tr>
																<th>Операция</th>
																<th>Время</th>
																<th>Стоимость</th>
															</tr>
														</thead>
														<tbody>
															<?
																$total_time = 0;
																$total_sum = 0;
																$query = "select  * from DIZ_OPER where DEFAULT_ = 1";
																$result = mysql_query($query) or die($query);
																while ($row = mysql_fetch_row($result)) {
																	$sums = 0;
																	
																	echo "<tr class='odd gradeX'>";
																	echo '<td><label><input type="checkbox" id = d' . $row[0] . '  value=" ' . $row[2] . '"  name="diz"  onclick="fun_view_diz(' . $row[0] . ')">' . $row[1] . '</label></td>';
																	echo '<div id=elemms' . $row[0] . '  style="display:none;"> <td><div class="col-md-3" > 
																	<label id=elemm' . $row[0] . '1 value = "'.$row[2].'">' . $row[2] . '</label>
																	</div></td>';
																	echo '<td><div class="col-md-3" >';
																	$sums = ($row[2] / 60) * $price_diz * $kurs1;
																	
																	echo '<label id=elemm' . $row[0] . '2 value = "'.round($sums, 2).'">' . round($sums, 2) . '</label>
																	</div></td></div>';
																	echo "</tr>";
																	
																}
															?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<?
											echo '<div class="row">
											<div class="col-md-6"> 
											<label>Итого:</label>
											</div>
											<div class="col-md-3"> 
											<label id = total_time_diz>' . $total_time . '</label>
											</div>
											<div class="col-md-3">
											<label id = total_sum_diz>' . round($total_sum, 2) . '</label>
											</div>
											</div>	';
											
											
											echo '	</div>
											<div class="modal-footer"><button type="button" onclick="hidemyModal3()" class="btn btn-default">Отмена</button>
											<button type="button" class="btn btn-primary"    onclick="diz_fun(' . $price_diz . ',' . $kurs1 . ') ">Добавить</button>
											</div>
											</div>
											</div>
											</div>';
											
											echo '		 
											<div id="myModal4" class="modal fade " tabindex="1" data-backdrop="static" data-keyboard="false" >
											<div class="modal-dialog">
											<div class="modal-content">
											<div class="modal-header"><button class="close" type="button"  onclick="hidemyModal4()">×</button>
											<h4 class="modal-title">Изменение операции препресс</h4>
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
											$query = "select  * from PR_OPER";
											$result = mysql_query($query) or die("Query failed");
											while ($row = mysql_fetch_row($result)) {
												$sums = 0;
												if ($row[3] == "1") {
													$total_time = $total_time + $row[2];
													echo '<div class="row">
													<div class="col-md-6"> 
													<div class="checkbox">
													<label><input type="checkbox" id = p' . $row[0] . '  value=" ' . $row[2] . '" checked name="pre"  onclick="fun_view_pre(' . $row[0] . ')">' . $row[1] . '</label>
													</div>
													</div>
													<div id=elemss' . $row[0] . '  style="display:block;">
													<div class="col-md-3"> 
													<label id=elem' . $row[0] . '1>' . $row[2] . '</label>
													</div>
													<div class="col-md-3">';
													$sums = ($row[2] / 60) * $price_diz * $kurs1;
													$total_sum = $total_sum + $sums;
													echo '<label id=elem' . $row[0] . '2>' . round($sums, 2) . '</label>
													</div>
													</div>
													</div>';
													} else {
													echo '<div class="row">
													<div class="col-md-6"> 
													<div class="checkbox">
													<label><input type="checkbox" id = p' . $row[0] . '  value=" ' . $row[2] . '"  name="pre" onclick="fun_view_pre(' . $row[0] . ')">' . $row[1] . '</label>
													</div>
													</div>
													<div id=elemss' . $row[0] . '  style="display:none;">
													<div class="col-md-3"> 
													<label id=elem' . $row[0] . '1>' . $row[2] . '</label>
													</div>
													<div class="col-md-3">';
													$sums = ($row[2] / 60) * $price_diz * $kurs1;
													echo '<label id=elem' . $row[0] . '2>' . round($sums, 2) . '</label>
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
											<label id = total_time_pre>' . $total_time . '</label>
											</div>
											<div class="col-md-3">
											<label id = total_sum_pre>' . round($total_sum, 2) . '</label>
											</div>
											</div>	';
											mysql_close($connection);
											echo '	</div>
											<div class="modal-footer"><button type="button"  onclick="hidemyModal4()"  class="btn btn-default">Отмена</button>
											<button type="button" class="btn btn-primary"    onclick="pre_fun(' . $price_diz . ',' . $kurs1 . ') ">Добавить</button>	
											</div>
											</div>
											</div>
											</div>';
										?>
										
										
										<div id="filesa" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true"
										style="display: none;">
											<div class="modal-dialog modal-lg">
												<div class="modal-content ">
													<div class="modal-header">
														<button class="close" type="button" data-dismiss="modal">×</button>
														<h4 class="modal-title">Редактирование файлов</h4>
													</div>
													<div class="modal-body">
														
														<div id='info_prod'></div>
														
														<div class="modal-footer">
															<button type="button" data-dismiss="modal" class="btn btn-default">Отмена
															</button>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										
										<script>
											
											$(function () {
												// Confirm
												$('#confirm').on('click', function () {
													document.getElementById('confirm').disabled = true;
													// $.alertable.confirm('Внимание! При печати бланк сохраниться в базу! Если вы действительно уверены, что ввели все верно того подтвердите печать! Иначе отмените!').then(function() {
													
													var idAcct = "<? echo $idAcct ?>";
													
													var table = document.getElementsByTagName('table');
													if(!table[0])
														return;
													var inputs = table[0].getElementsByTagName('input');
													
													var arr = new Array();
													for(var i = 0; i < inputs.length; i++) {
														if(inputs[i].hasAttribute('type')) {
															if(inputs[i].getAttribute('type') == 'checkbox') {
																if(inputs[i].checked) {
																	arr.push(inputs[i].value);
																}
															}
														}
													}
													
													var smeta = "";
													if(arr.length > 0)
														smeta = JSON.stringify(arr);
													
													document.getElementById('fr').src = 'proc/act_.php?id=' + idAcct + "&no_nds=0&smeta=" + smeta;
													window.setTimeout(ffa, 2000);
													// }, function() {
													// });
												});
												// Confirm
												
											});
											
											function print_act_no_nds() {
												document.getElementById('confirm1').disabled = true;
												// $.alertable.confirm('Внимание! При печати бланк сохраниться в базу! Если вы действительно уверены, что ввели все верно того подтвердите печать! Иначе отмените!').then(function() {
												
												var idAcct = "<? echo $idAcct ?>";
												
												document.getElementById('fr1').src = 'proc/act_.php?id=' + idAcct + "&no_nds=1";
												window.setTimeout(ffa1, 2000);
												// }, function() {
												// });
											}
											
											function ffa() {
												var getMyFrame = document.getElementById('fr');
												getMyFrame.focus();
												getMyFrame.contentWindow.print();
											}
											
											function ffa1() {
												var getMyFrame = document.getElementById('fr1');
												getMyFrame.focus();
												getMyFrame.contentWindow.print();
											}
											function showacct(id) {
												var table = document.getElementsByTagName('table');
												if(!table[0])
													return;
												var inputs = table[0].getElementsByTagName('input');
												
												var arr = new Array();
												for(var i = 0; i < inputs.length; i++) {
													if(inputs[i].hasAttribute('type')) {
														if(inputs[i].getAttribute('type') == 'checkbox') {
															if(inputs[i].checked) {
																arr.push(inputs[i].value);
															}
														}
													}
												}
												
												var smeta = "";
												if(arr.length > 0)
													smeta = JSON.stringify(arr);
																								
												
												document.getElementById('fr').src = 'proc/act.php?id=' + id + "&no_nds=0&smeta=" + smeta;
												$('#myacct').modal('show');
											}
											function showacct1(id) {
												document.getElementById('fr1').src = 'proc/act.php?id=' + id + "&no_nds=1";
												$('#myacct1').modal('show');
											}
											/*function addshamp() {
												$("#multiform").submit();
											}*/
											
											$(document).ready(function () {
												$("#date").val('<? echo date("Y-m-d")?>');
											});
											
											function deltn() {
												$('#mydeltn').modal('show');
											}
											
											function fun_del_tn() {
												id33 = "<? echo $idAcct ?>";
												var tr1 = '';
												var nodeList = document.getElementsByName('chDel2');
												var array = Array.prototype.slice.call(nodeList);
												for (var i = 0; i < array.length; i++) {
													if (array[i].checked) {
														var tr1 = tr1 + array[i].value + ',';
													}
												}
												tr1 = tr1.substring(0, tr1.length - 1);
												
												$.post("_updatetn_del.php", {id: id33, id_nak: tr1}, function () {
													window.location.reload();
												});
											}
											
										</script>
										<script src="../../vendor/jquery/jquery.min.js"></script>
										<script src="../../vendor/bootstrap/js/bootstrap-select.js" type="text/javascript"></script>
										<!-- Bootstrap Core JavaScript -->
										<script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
										<!-- DataTables JavaScript -->
										<script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
										<script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
										<script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
										<link rel="stylesheet" href="../../vendor/chosen.min.css">
										
										<script src="../../vendor/chosen.jquery.min.js"></script>
										
										<script>
											$(document).ready(function () {
												$('#dataTables1').DataTable({
													responsive: true,
													"iDisplayLength": 5
												});
											});
											$(document).ready(function () {
												$('#dataTables2').DataTable({
													responsive: true,
													"iDisplayLength": 5
												});
											});
											
											/*$(document).ready(function() {
												$('#dataTables-example1').DataTable({
												responsive: true
												});
											});*/
											
										</script>
										<script>
											 /*function getDoc(frame) {
												var doc = null;
												
												// IE8 cascading access check
												try {
													if (frame.contentWindow) {
														doc = frame.contentWindow.document;
													}
													} catch (err) {
												}
												
												if (doc) { // successful getting content
													return doc;
												}
												
												try { // simply checking may throw in ie8 under ssl or mismatched protocol
													doc = frame.contentDocument ? frame.contentDocument : frame.document;
													} catch (err) {
													// last attempt
													doc = frame.document;
												}
												return doc;
											}*/
											
										/*	$("#multiform").submit(function (e) {
												var formObj = $(this);
												var formURL = formObj.attr("action");
												
												if (window.FormData !== undefined)  // for HTML5 browsers
												{
													
													var formData = new FormData(this);
													$.ajax({
														url: formURL,
														type: "POST",
														data: formData,
														mimeType: "multipart/form-data",
														contentType: false,
														cache: false,
														processData: false,
														success: function (data, textStatus, jqXHR) {
															
															$('#myModald').modal('hide');
															ref1(document.getElementById('kolss').value, document.getElementById('namess').value);
														},
														error: function (jqXHR, textStatus, errorThrown) {
															$('#myModald').modal('hide');
														}
													});
													e.preventDefault();
												}
												else  //for olden browsers
												{
													//generate a random id
													var iframeId = "unique" + (new Date().getTime());
													
													//create an empty iframe
													var iframe = $('<iframe src="javascript:false;" name="' + iframeId + '" />');
													
													//hide it
													iframe.hide();
													
													//set form target to iframe
													formObj.attr("target", iframeId);
													
													//Add iframe to body
													iframe.appendTo("body");
													iframe.load(function (e) {
														var doc = getDoc(iframe[0]);
														var docRoot = doc.body ? doc.body : doc.documentElement;
														var data = docRoot.innerHTML;
														//data return from server.
														
													});
													
												}
												
											});*/
											
										</script>
										<script>
											function fun1() {
												var chbox;
												chbox = document.getElementById('one');
												opl = document.getElementById('opl');
												if (chbox.checked) {
													opl.disabled = 0;
													document.getElementById('opl').value = 0;
												}
												else {
													opl.disabled = 1;
													document.getElementById('opl').value = 0;
												}
											}
										</script>
										
										<script>
											function fun() {
												
												var total_KOL = "<? echo $total_KOL ?>";
												var idAcct = "<? echo $idAcct ?>";
												var sum_acct = "<? echo $sum_acct ?>";
												var client_id = "<? echo $client_id ?>";
												var pred = "<? echo $pred ?>";
												var sum_opl;
												
												if (total_KOL != 0) {
													chbox = document.getElementById('one');
													if (chbox.checked) {
														sum_opl = document.getElementById('opl').value;
														sum_opl = sum_opl.replace(',', '.');
													}
													else {
														sum_opl = 0;
													}
													
													two = document.getElementById('two');
													if (two.checked) {
														flag = 1;
													}
													else {
														flag = 0;
													}
													
													var tableElem = document.getElementById('radio_chk');
													var elements = tableElem.getElementsByTagName('input');
													
													for (var i = 0; i < elements.length; i++) {
														if (elements[i].checked == true) {
															var view_opl1 = elements[i].value
														}
													}
													
													location.href = '_oplata.php?id=' + idAcct + '&sum=' + sum_acct + '&client=' + client_id + '&sumOpl=' + sum_opl + '&flag=' + flag + '&pred=' + pred + '&view_opl=' + view_opl1;
													} else {
													document.getElementById('err3').style.display = 'block';
												}
												
											}
										</script>
										<script>
											
											function fun4() {
												var total_KOL = "<? echo $total_KOL ?>";
												var idAcct = "<? echo $idAcct ?>";
												var sum_acct = "<? echo $sum_acct ?>";
												var client_id = "<? echo $client_id ?>";
												var pred = "<? echo $pred ?>";
												var sum_opl;
												
												if (total_KOL != 0) {
													sum_opl = document.getElementById('opl').value;
													if (sum_opl != 0) {
														sum_opl = sum_opl.replace(',', '.');
														two = document.getElementById('two');
														if (two.checked) {
															flag = 1;
														}
														else {
															flag = 0;
														}
														var tableElem = document.getElementById('radio_chk');
														var elements = tableElem.getElementsByTagName('input');
														
														for (var i = 0; i < elements.length; i++) {
															if (elements[i].checked == true) {
																var view_opl1 = elements[i].value
															}
														}
														location.href = '_oplata.php?id=' + idAcct + '&sum=' + sum_acct + '&client=' + client_id + '&sumOpl=' + sum_opl + '&flag=' + flag + '&pred=' + pred + '&view_opl=' + view_opl1;
													}
													else {
														document.getElementById('err2').style.display = 'block';
													}
													
													
													} else {
													document.getElementById('err').style.display = 'block';
												}
											}
										</script>
										
										
										<script>
											
											function chk_avail(){
												
												
												
												p_cir = Number( $("#p_cir").val()) * 2;
												if ($("#unit_prod1").val() == "тыс.шт."){
													p_cir = Number(p_cir) * 1000;
												}
												
												
												if($("#p_per_mat_i").val() > 0 && $("#p_size").val() != "" && $("#p_cir").val() > "" ){
													$.ajax({
														type: "GET",
														url: '../ajax_php_sql.php',
														data: {
															size : $("#p_size").val() ,
															uz : $("#p_per_mat_i").val(),
															tiraj : p_cir   ,
															flag : '35'
															},  success:function (data) {//возвращаемый результат от сервера
															
															document.getElementById('err_chk').innerHTML = data;
														}
													});
													} else {
													document.getElementById('err_chk').innerHTML = "";
												}
												
												
											}
											
											function tran_(){
												var nodeList = document.getElementsByName('tran');
												var array = Array.prototype.slice.call(nodeList);
												str_id = "";
												for (var i = 0; i < array.length; i++) {
													if (array[i].checked) {
														if ($("#new_acct" + array[i].value).val() != ""){
															str_id = str_id + array[i].value  + "," + $("#new_acct" + array[i].value).val() + "|";
															} else {
															alert("Не задан номер счета для продукта " + document.getElementById('namsse' + array[i].value).innerHTML)
														}
													}
												}
												str_id = str_id.slice(0, -1);
												
												if (str_id != ''){
													//alert(str_id)
													$.ajax({
														type: "GET",
														url: '../ajax_php_sql.php',
														data: {
															str_id : str_id,
															flag : '33'
															},  success:function (data) {//возвращаемый результат от сервера
															//alert(data)
															window.location.reload();
														}
													});
													}	else {
													alert("Не выбраны позиции для перемещения!")
												}
												
											}
										</script>
										
										<script>
											function fun2() {
												var idAcct = "<? echo $idAcct ?>";
												var sum_acct = "<? echo $sum_acct ?>";
												var client_id = "<? echo $client_id ?>";
												var total_KOL = "<? echo $total_KOL ?>";
												var sum_opl = 0;
												
												
												if (total_KOL != 0) {
													location.href = '_oplata.php?id=' + idAcct + '&sum=' + sum_acct + '&client=' + client_id + '&sumOpl=' + sum_opl + '&flag=2';
													} else {
													document.getElementById('err').style.display = 'block';
												}
											}
										</script>
										<script>
											
											function addProd() {
												
												if(document.getElementById("directoryCodeStat").selectedIndex < 0) {
													alert("Выберите тип продукции");
													return;
												}
									
												get_price();
												
												window.setTimeout(	addProd4, 1000);
											}
											function addProd4()	{
												
												var part = Number(document.getElementById('kol').value) + 1;
												sum_all = 0;
												sum_all1 = 0;
												/*for(var i = 1; i < part; i++){
													if (document.getElementById("p_eq_" + i).valur != "0"){
													if (document.getElementById("p_mat_" + i).valur != "0"){
													document.getElementById("p_mat_" + i).focus;
													}
													}
													
												}*/
												p_pr_diz = 0
												//Стоимость дизайна:
												if (document.getElementById('p_prdiz_1').value != "") {
													p_pr_diz = document.getElementById('p_prdiz_1').value;
													p_pr_diz = p_pr_diz.replace(",", ".")
												}
												else {
													p_pr_diz = 0
												}
												document.getElementById('sum_press').value = p_pr_diz;
												if (document.getElementById('no_price').checked == true) {
													
													p_syn = document.getElementById('p_sum_all').value.replace(",", ".");
													document.getElementById('p_sum_all').value = p_syn;
													sum_all = Number(document.getElementById('p_sum_all').value);
													n_cir = Number((document.getElementById('p_cir').value).replace(",", "."));
													
													sum_all = sum_all.toFixed(2);
													price_ed = ((sum_all / n_cir) / 1.2);
													price_ed = price_ed.toFixed(2);
													sum_all = price_ed * n_cir * 1.2;
													sum_all = sum_all.toFixed(2);
													
													document.getElementById('p_sum_all').value = sum_all;
													price_ed = Number(sum_all) / Number(n_cir);
													price_ed = price_ed.toFixed(3);
													document.getElementById('p_price_all').value = price_ed;
												}
												else {
													get_price();
												}
												
												
												if (($('#p_per_i').val() == 8 || $('#p_per_i').val() == 9 || $('#p_per_i').val() == 10 || $('#p_per_i').val() == 11 ) && $('#p_per_mat_i').val() == 0) {
													
													alert("Выберите материал переплета!");
													return;
												}
											
												
							
												if (document.getElementById('id_prod').value != "") {
													get_price();
													
													addProd1();
												}
												else {
													
													
													if (document.getElementById('file_diz').checked == false) {
														if ($("#filea").val() == '' && document.getElementById('maket_sh').value != '4') {
															alert("Не выбраны файлы!");
															return;
															
														}
													}
													
													if (document.getElementById('p_dates_time').value == "0" || document.getElementById('p_dates_time').value == "") {
														alert("Не задана дата готовности!")
														return
													}
													
													today = '<?echo date("Y-m-d")?>';
													d1 = new Date(document.getElementById('p_dates_time').value);
													d2 = new Date(today);
													if (d1 < d2) {
														alert("Дата сдачи не может быть меньше текущей!")
														return
													}
													
													if (document.getElementById('p_size').value == "") {
														alert("Не введен размер продукта!")
														return
													}
													
													if (document.getElementById('no_price').checked == false) {
														if (document.getElementById('p_sum_all').value == "0" || document.getElementById('p_sum_all').value == "NaN" || document.getElementById('p_sum_all').value == "0.00") {
															alert("Не расчитан заказ!")
															return
														}
														
														
														
													}
													else {
														get_price();
													}
													if (document.getElementById('unit_prod1').value == "") {
														alert("Укажите единицу измерения!")
														return
													}
													
													
													if (document.getElementById('p_cir').value == "0" || document.getElementById('p_cir').value == "") {
														alert("Не введен тираж!")
														return
													}
													if ((document.getElementById("view_diz").value == '0' || document.getElementById("view_diz").value == '') && document.getElementById("maket_sh").value == "1") {
														alert("Укажите позиции дизайна!")
														return
													}
													/*	
														if ((document.getElementById("view_press").value == '0' || document.getElementById("view_diz").value == '') && document.getElementById("maket_sh").value == "2" ){
														alert("Укажите позиции препресса!") 
														return
														}
													*/
													var id = <?echo $idAcct ?>;
													var p_size = document.getElementById('p_size').value;
													var name_prod = getConcatenedTextContent(document.getElementById('directoryCodeStat').options[document.getElementById('directoryCodeStat').selectedIndex]) + document.getElementById('p_names').value;
													var directoryCodeStat = document.getElementById('directoryCodeStat').value;
													var unit_prod = 'шт.';
													var price_prod = Number(document.getElementById('p_price_all').value);
													var total_prod = document.getElementById('p_cir').value;
													var ps_fast = document.getElementById('p_fast').value;
													var ps_dates_time = document.getElementById('p_dates_time').value;
													var str_tm = document.getElementById('str_tm').value;
													var str_tm1 = document.getElementById('str_tm1').value;
													var p_per = document.getElementById('p_per_i').value;
													var p_stor = document.getElementById('p_stor_i').value;
													var p_per_mat = document.getElementById('p_per_mat_i').value;
													str_per = p_per + "|" + p_stor + "|" + p_per_mat;
													var form = $('#forms')[0];
													var datass = new FormData(form);
													//создаем форму и процент загрузки
													var mod_div = document.createElement('div');
													mod_div.setAttribute('id', "modal_upload");
													mod_div.setAttribute('style', "position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 2000;background: rgba(29,37,49,.9);");
													document.getElementsByTagName('body')[0].appendChild(mod_div);
													var head = document.createElement('h3');
													head.setAttribute('style', "color: #f7e946;top: 40%;position: relative;left: 25%;");
													head.setAttribute('id', "progress_label");
													head.appendChild(document.createTextNode("Загрузка 0%"));
													mod_div.appendChild(head);
													var pr_div = document.createElement('div');
													pr_div.setAttribute('class', "progress");
													pr_div.setAttribute('style', "top: 40%;position: relative;width: 50%;left: 25%;background-color: black;");
													mod_div.appendChild(pr_div);
													var progressbar_div = document.createElement('div');
													progressbar_div.setAttribute('id', "_progressbar");
													progressbar_div.setAttribute('class', "progress-bar");
													progressbar_div.setAttribute('role', "progressbar");
													progressbar_div.setAttribute('style', "width: 0;background-color:#f7e946;");
													progressbar_div.setAttribute('aria-valuenow', "0");
													progressbar_div.setAttribute('aria-valuemin', "0");
													progressbar_div.setAttribute('aria-valuemax', "100");
													pr_div.appendChild(progressbar_div);
													
													var xhr = new XMLHttpRequest();
													// обработчик для закачки
													xhr.upload.onprogress = function(event) {
														var pr = Math.floor(parseFloat(event.loaded) * 100 / parseFloat(event.total));
														removeAllTextNodes(document.getElementById("progress_label"));
														document.getElementById("progress_label").appendChild(document.createTextNode("Загрузка " + pr + "%"));
														document.getElementById("_progressbar").setAttribute('style', "width:" + pr + "%;background-color:#f7e946;");
														document.getElementById("_progressbar").setAttribute('aria-valuenow', pr);
													}

													// обработчики успеха и ошибки
													// если status == 200, то это успех, иначе ошибка
													xhr.onload = xhr.onerror = function() {
														//remove(document.getElementById("modal_upload"));
														if (this.status == 200) {
															location.href = '_addAcct.php?id=' + document.getElementById("orderAcct").value;
														} else {
															alert("error " + this.status);
														}
													};

													xhr.open("POST", "_addOrProdsftest.php", true);
													xhr.send(datass);
													/*$.ajax({
														type: "POST",
														enctype: 'multipart/form-data',
														url: "_addOrProdsftest.php",
														data: datass,
														processData: false,
														contentType: false,
														cache: false,
														success: function (data) {
															location.href = '_addAcct.php?id=' + document.getElementById("orderAcct").value;
														}
													});*/
													// $.post( "_addOrProdsftest.php", { id : id,
													// p_size 		: p_size,
													// name_prod 	: name_prod,
													// unit_prod 	: unit_prod,
													// price_prod 	: price_prod,
													// total_prod 	: total_prod ,
													// ps_fast   	: ps_fast,
													// str_tm   	: str_tm,
													// str_tm1   	: str_tm1,
													// str_per 		: str_per,
													// ps_dates_time : ps_dates_time,
													// view_diz: document.getElementById("view_diz").value,
													// view_press: document.getElementById("view_press").value	}, function(){window.location.reload();});
													
												}
											}
										</script>
										<script>
											
											function prov() {
												var total_KOL = "<? echo $total_KOL ?>";
												
												if (total_KOL != "0") {
													$("#myModal12321312").modal('show');
													} else {
													document.getElementById('err').style.display = 'block';
												}
											}
											
											
										</script>
										<script>
											
										 
											function funee() {
												client = document.getElementById('client').value;
												num = document.getElementById('num').value;
												sum = document.getElementById('sum').value;
												date = document.getElementById('date').value;
												var tableElem = document.getElementById('radio_chk');
												var elements = tableElem.getElementsByTagName('input');
												
												for (var i = 0; i < elements.length; i++) {
													if (elements[i].checked == true) {
														var view_opl1 = elements[i].value
													}
												}
												$.ajax({
													type: "GET",
													url: '_addOplata.php',
													data: {
														client: client,
														num: num,
														sum: sum,
														date: date,
														view_opl: view_opl1
														}, success: function (data) {//возвращаемый результат от сервера
														window.location.reload();
														window.location.href = '_addAcct.php?id=' + id;
													}
													
												});
												
											}
											
										</script>
										<script>
											function get_job(id) {
												var total_KOL = "<? echo $total_KOL ?>";
												var nodeList = document.getElementsByName('chDel');
												var array = Array.prototype.slice.call(nodeList);
												str_id = "";
												for (var i = 0; i < array.length; i++) {
													if (array[i].checked) {
														
														if ($("#status" + array[i].value).val() == "" || $("#status" + array[i].value).val() == "20" || $("#status" + array[i].value).val() == "0") {
															str_id = str_id + array[i].value + ",";
														}
														
													}
												}
												str_id = str_id.slice(0, -1);
												if (str_id != "") {
													
													window.open('proc/plan_job.php?id=' + str_id, '_blank');
													$.ajax({
														type: "GET",
														url: '_addjob.php',
														data: {
															id: id,
															id1: str_id
															}, success: function (data) {//возвращаемый результат от сервера
															window.location.reload();
															window.location.href = '_addAcct.php?id=' + id;
														}
														
													});
													} else {
													document.getElementById('err').style.display = 'block';
												}
												
											}
										</script>
										
										<script type="text/javascript">
											$("#close").click(function () {
												document.getElementById('err').style.display = 'none';
												document.getElementById('err1').style.display = 'none';
												document.getElementById('err3').style.display = 'none';
												document.getElementById('err3').style.display = 'none';
											});
										</script>
										<script type="text/javascript">
											$("#close1").click(function () {
												document.getElementById('tn_err1').style.display = 'none';
												
											});
										</script>
										<script type="text/javascript">
											$("#close2").click(function () {
												document.getElementById('tn_err2').style.display = 'none';
												
											});
										</script>
										<script type="text/javascript">
											$("#close3").click(function () {
												document.getElementById('tn_err3').style.display = 'none';
												
											});
										</script>
										<script type="text/javascript">
											$("#close4").click(function () {
												document.getElementById('tn_err4').style.display = 'none';
												
											});
										</script>
										<script type="text/javascript">
											$("#close5").click(function () {
												document.getElementById('tn_err5').style.display = 'none';
												
											});
										</script>
										<script type="text/javascript">
											$("#close6").click(function () {
												document.getElementById('tn_err6').style.display = 'none';
											});
										</script>
										
										<script>
											function del_row() {
												var nodeList = document.getElementsByName('chDel');
												var array = Array.prototype.slice.call(nodeList);
												for (var i = 0; i < array.length; i++) {
													if (array[i].checked) {
														var tr = array[i].parentNode.parentNode;
														var parent = tr.parentNode
														parent.removeChild(tr);
													}
												}
											}
										</script>
										<script>
											function addRow() {  // функция для добавления строки к части шаблона
												var tbl = document.getElementById('dynamic');                   // таблица, с которой работаем
												var rws = tbl.rows;                                            // коллекция существующих строк таблицы
												var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
												var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
												var ro = tbl.insertRow(-1);                                   // добавляем снизу ОБРАЗ ещё одной строки
												var kol = document.getElementById('kol').value;
												kol = Number(kol) + 1;
												document.getElementById('kol').value = kol;
												
												
												ce = ro.insertCell(-1);
												ce.innerHTML = "<input type='checkbox' name='chDel' value='1'>";
												
												ce = ro.insertCell(-1);
												ce.innerHTML = "<select name='selOper" + kol + "' id='selOper" + kol + "' >" + document.getElementById('selOper').innerHTML + "</select>";
												
												ce = ro.insertCell(-1);
												ce.innerHTML = "	<textarea class='form-control' rows='2' id='par" + kol + "'></textarea>";
												
												ce = ro.insertCell(-1);
												ce.innerHTML = "<select  id='optgroup" + kol + "'  name='optgroup" + kol + "' >" + document.getElementById('optgroup').innerHTML + "</select>";
											}
										</script>
									 
										<script>
											function maket(id) {
												document.getElementById('maket_sh').value = id;
												if (id == '1') {
													document.getElementById('op1').style.display = 'block';
													document.getElementById('op2').style.display = 'none';
													
													document.getElementById('optionsRadiosInline2').checked = false;
													document.getElementById('optionsRadiosInline1').checked = true;
													document.getElementById('optionsRadiosInline3').checked = false;
													document.getElementById('optionsRadiosInline4').checked = false;
													price_diz = document.getElementById('price_diz').value;
													kurs = document.getElementById('kurs').value;
													var str = '', str1 = '', sum = 0;
													var nodeList = document.getElementsByName('diz');
													var array = Array.prototype.slice.call(nodeList);
													for (var i = 0; i < array.length; i++) {
														if (array[i].checked) {
															str = str + array[i].id.substring(1, array[i].id.length) + ',';
															sum = Number(sum) + Number(array[i].value);
														}
													}
													document.getElementById('view_diz').value = str.substring(0, str.length - 1);
													summ = (sum / 60) * price_diz * kurs
													document.getElementById('p_prdiz_1').value = summ.toFixed(2);
													
													str = '', str1 = '', sum = 0;
													nodeList = document.getElementsByName('pre');
													array = Array.prototype.slice.call(nodeList);
													for (i = 0; i < array.length; i++) {
														if (array[i].checked) {
															str = str + array[i].id.substring(1, array[i].id.length) + ',';
															sum = Number(sum) + Number(array[i].value);
															
														}
													}
													document.getElementById('view_press').value = str.substring(0, str.length - 1);
													summ = (sum / 60) * price_diz * kurs
													document.getElementById('p_press_1').value = summ.toFixed(2);
													document.getElementById('bnt_file').style.display = 'block';
												}
												if (id == '2') {
													document.getElementById('op2').style.display = 'block';
													document.getElementById('op1').style.display = 'none';
													
													document.getElementById('optionsRadiosInline2').checked = true;
													document.getElementById('optionsRadiosInline1').checked = false;
													document.getElementById('optionsRadiosInline3').checked = false;
													document.getElementById('optionsRadiosInline4').checked = false;
													//document.getElementById('p_prdiz_1').value = 0;
													//document.getElementById("view_diz").value = '';
													price_diz = document.getElementById('price_diz').value;
													kurs = document.getElementById('kurs').value;
													str = '', str1 = '', sum = 0;
													nodeList = document.getElementsByName('pre');
													array = Array.prototype.slice.call(nodeList);
													for (i = 0; i < array.length; i++) {
														if (array[i].checked) {
															str = str + array[i].id.substring(1, array[i].id.length) + ',';
															sum = Number(sum) + Number(array[i].value);
															
														}
													}
													document.getElementById('view_press').value = str.substring(0, str.length - 1);
													summ = (sum / 60) * price_diz * kurs
													document.getElementById('p_press_1').value = summ.toFixed(2);
													document.getElementById('bnt_file').style.display = 'block';
												}
												if (id == '3') {
													document.getElementById('op2').style.display = 'none';
													document.getElementById('op1').style.display = 'none';
													document.getElementById('optionsRadiosInline4').checked = false;
													document.getElementById('optionsRadiosInline2').checked = false;
													document.getElementById('optionsRadiosInline1').checked = false;
													document.getElementById('optionsRadiosInline3').checked = true;
													// document.getElementById('p_prdiz_1').value = 0;
													// document.getElementById('p_press_1').value = 0;
													document.getElementById("view_diz").value = '';
													document.getElementById("view_press").value = '';
													document.getElementById('bnt_file').style.display = 'block';
													
												}
												if (id == '4') {
													document.getElementById('op2').style.display = 'none';
													document.getElementById('op1').style.display = 'none';
													// document.getElementById('p_prdiz_1').value = 0;
													// document.getElementById('p_press_1').value = 0;
													document.getElementById("view_diz").value = '';
													document.getElementById("view_press").value = '';
													document.getElementById('optionsRadiosInline3').checked = false;
													document.getElementById('optionsRadiosInline2').checked = false;
													document.getElementById('optionsRadiosInline1').checked = false;
													document.getElementById('optionsRadiosInline4').checked = true;
													document.getElementById('listss').innerHTML = '';
													document.getElementById('bnt_file').style.display = 'none';
												}
											}
										</script>
										<script>
											
											function rts(id1, id2, id3, id4) {
												
												var id_eq = document.getElementById(id1).value;
												var kol = document.getElementById(id2).length;
												rts_combicolor(id1, id2);
												var i, y;
												i = 0;
												
												type = $("#" + id2 + " :selected").text();
												num = document.getElementById(id2).value;
												
												for (y = 0; y < kol; y++) {
													
													if ($(document.getElementById(id2).options[y]).data('attr') == id_eq) {
														document.getElementById(id2).options[y].style.display = 'block';
														document.getElementById(id2).options[y].disabled = false;
														i++;
														} else {
														document.getElementById(id2).options[y].style.display = 'none';
														document.getElementById(id2).options[y].disabled = true;
													}
												}
												
												
												$('#' + id2).val('')
												
												for (y = 0; y < kol; y++) {
													if ($(document.getElementById(id2).options[y]).data('attr') == id_eq) {
														if (document.getElementById(id2).options[y].text == type) {
															//console.log(document.getElementById(id2).options[y].text)
															document.getElementById(id2).options[y].selected = true;
														}
													}
												}
												
												
												type = $("#" + id4 + " :selected").text();
												num = document.getElementById(id4).value;
												
												var kol = document.getElementById(id4).length;
												for (y = 0; y < kol; y++) {
													
													
													attr = $(document.getElementById(id4).options[y]).data('attr')  + '';
													// console.log(attr);
													att = attr.split(',');
													l_flag_att = 0; 
													for (tt = 0 ; tt < att.length; tt++){
														//console.log(att[tt] + " == " + id_eq )
														if (att[tt] == id_eq){
															l_flag_att = 1; 
														}
													}
													//console.log(att);
													if (l_flag_att == 1) {
														document.getElementById(id4).options[y].style.display = 'block';
														document.getElementById(id4).options[y].disabled = false;
														i++;
														} else {
														document.getElementById(id4).options[y].style.display = 'none';
														document.getElementById(id4).options[y].disabled = true;
													}
												}
												$('#' + id4).val('')
												
												for (y = 0; y < kol; y++) {
													attr = $(document.getElementById(id4).options[y]).data('attr') + '';
													//console.log(attr);
													att = attr.split(',');
													l_flag_att = 0; 
													for (tt = 0 ; tt < att.length; att ++){
														if (att[tt] == id_eq){
															l_flag_att = 1; 
														}
													}
													if (l_flag_att == 1) {
														if (document.getElementById(id4).options[y].text == type) {
															document.getElementById(id4).options[y].selected = true;
														}
													}
												}
												
												type = $("#" + id3 + " :selected").text();
												num = document.getElementById(id3).value;
												
												var kol = document.getElementById(id3).length;
												for (y = 0; y < kol; y++) {
													if ($(document.getElementById(id3).options[y]).data('attr') == id_eq) {
														document.getElementById(id3).options[y].style.display = 'block';
														document.getElementById(id3).options[y].disabled = false;
														//костыль для бумаги 297*420 для Xerox 1000
														/*if(id_eq == 3 && document.getElementById(id3).options[y].value == "297*420")
															document.getElementById(id3).options[y].disabled = true;
														i++;
														} else {
														document.getElementById(id3).options[y].style.display = 'none';
														document.getElementById(id3).options[y].disabled = true;*/
													}
												}
												$('#' + id3).val('')
												
												for (y = 0; y < kol; y++) {
													if ($(document.getElementById(id3).options[y]).data('attr') == id_eq) {
														if (document.getElementById(id3).options[y].text == type) {
															document.getElementById(id3).options[y].selected = true;
														}
													}
												}
												
												
												
												if ($("div").is("#" + id4 + "_chosen")) {
													$("#" + id4).trigger('chosen:updated');
												}
												else {
													$("#" + id4).chosen();
												}
												
												
												//  console.clear();
												//console.log('Подчистить пустые optgroup')
												var opr = document.getElementById(id4); 
												var nodeList = opr.getElementsByTagName('optgroup');
												
												for (var i = 0; i < nodeList.length; i++) {
													
													list = nodeList[i].getElementsByTagName('option');
													flagss = 0;
													for (var y = 0; y < list.length; y++) {
														if (list[y].style.display == 'block'){
															flagss = 1;
														}
													}
													
													if (flagss == 1){
														//console.log(nodeList[i].label)
														nodeList[i].style.display = 'block';
														} else {
														nodeList[i].style.display = 'none';
													}
													
												}
												$("#" + id4).trigger('chosen:updated');
												
												var id_count = id1.substring(5);
												selectedSize(id_count);
												
											}

											function rts_combicolor(id1, id2) {
                                                var id_eq = document.getElementById(id1).value;
                                                if(document.getElementById(id2 + "_combi"))
                                                    remove(document.getElementById(id2 + "_combi"));
                                                eval("var equpment_combi_color = COMBI_OBJECT.combi" + id_eq);
                                                if(equpment_combi_color && Array.isArray(equpment_combi_color) && equpment_combi_color.length > 0) {
                                                    var tbl = '<div class="row" id="' + id2 + '_combi">';
                                                    tbl += '<div class="col-md-6"><label>Лицевая сторона</label><table>';
                                                    tbl += '<thead><tr>' +
                                                        '<th></th>' +
                                                        '<th>10%</th>' +
                                                        '<th>40%</th>' +
                                                        '<th>70%</th>' +
                                                        '<th>90%</th>' +
                                                        '</tr></thead><tbody>';
                                                    for(var i in equpment_combi_color) {
                                                        tbl += '<tr>' +
                                                            '<td><b>' + equpment_combi_color[i].name + '<b></td>' +
                                                            '<td><input class="' + id2 + '_combi_f" type="radio" name="' + id2 + "_" + equpment_combi_color[i].id + '_f" value="' + equpment_combi_color[i].id + '_10" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                                                            '<td><input class="' + id2 + '_combi_f" type="radio" name="' + id2 + "_" + equpment_combi_color[i].id + '_f" value="' + equpment_combi_color[i].id + '_40" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                                                            '<td><input class="' + id2 + '_combi_f" type="radio" name="' + id2 + "_" + equpment_combi_color[i].id + '_f" value="' + equpment_combi_color[i].id + '_70" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                                                            '<td><input class="' + id2 + '_combi_f" type="radio" name="' + id2 + "_" + equpment_combi_color[i].id + '_f" value="' + equpment_combi_color[i].id + '_90" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                                                            '</tr>';
                                                    }
                                                    tbl += '</tbody></table></div>';

                                                    tbl += '<div class="col-md-6"><label>Оборотная сторона</label><table>';
                                                    tbl += '<thead><tr>' +
                                                        '<th></th>' +
                                                        '<th>10%</th>' +
                                                        '<th>40%</th>' +
                                                        '<th>70%</th>' +
                                                        '<th>90%</th>' +
                                                        '</tr></thead><tbody>';
                                                    for(var i in equpment_combi_color) {
                                                        tbl += '<tr>' +
                                                            '<td><b>' + equpment_combi_color[i].name + '<b></td>' +
                                                            '<td><input class="' + id2 + '_combi_b" type="radio" name="' + id2 + "_" + equpment_combi_color[i].id + '_b" value="' + equpment_combi_color[i].id + '_10" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                                                            '<td><input class="' + id2 + '_combi_b" type="radio" name="' + id2 + "_" + equpment_combi_color[i].id + '_b" value="' + equpment_combi_color[i].id + '_40" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                                                            '<td><input class="' + id2 + '_combi_b" type="radio" name="' + id2 + "_" + equpment_combi_color[i].id + '_b" value="' + equpment_combi_color[i].id + '_70" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                                                            '<td><input class="' + id2 + '_combi_b" type="radio" name="' + id2 + "_" + equpment_combi_color[i].id + '_b" value="' + equpment_combi_color[i].id + '_90" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                                                            '</tr>';
                                                    }
                                                    tbl += '</tbody></table></div>';
                                                    tbl += '</div>';
                                                    $('#' + id2).after(tbl);
                                                }
                                            }
                                            
                                            function down_combi(e) {
                                                if(e.checked) {
                                                    e.checked = false;
                                                    return false;
                                                }
                                            }

                                            function change_combi(e) {
                                                if(e.classList[0]) {
                                                    var cl_this = document.getElementsByClassName(e.classList[0]);
                                                    var flag = false;
                                                    var i_flag = 0;
                                                    for(var i in cl_this) {
                                                        if(cl_this[i].checked) {
                                                            i_flag++;
                                                        }
                                                        if(i_flag > 2) {
                                                            flag = true;
                                                            break;
                                                        }
                                                    }
                                                    if(flag) {
                                                        e.checked = false;
                                                        return false;
                                                    }
                                                    get_price();
                                                }
                                            }

											function get_price() {
												str = "";
												var part = Number(document.getElementById('kol').value) + 1;
												chk_avail();
												for (var num_ = 1; num_ < part; num_++) {
													
													
													siz1222 = $('#p_size').val();
													tir1222 = $('#p_cir').val();
													tir1222 = tir1222.replace(",", ".");
													p_eq_i222 = $('#p_eq_' + num_).val();
													
													p_mat_i222 = $('#p_mat_' + num_).val();
													//console.log("123123   "  + $('#p_mat_' + num_).val() + " " + $('#p_sizep_' + num_).val());
													size_222 = $('#p_sizep_' + num_).val();
													
													size_222_new = document.getElementById('p_new_size_' + num_).value;
													if (size_222 == null) {
														size_222 = 0;
													}
													
													if (p_mat_i222 == null) {
														p_mat_i222 = '';
													}
													
													unit_prod1222 = $('#unit_prod1').val();
													if (unit_prod1222 == "тыс.шт.") {
														tir1222 = tir1222 * 1000;
													}
													p_size222 = $('#p_size_' + num_).val();
													if (p_size222 != "") {
														siz1222 = p_size222;
													}
													
													vin222 = $('#vin' + num_).val();
													
													p_kolstr222 = $('#p_kolstr_' + num_).val();
													max222 = 0;
													if (document.getElementById('max' + num_).checked) {
														max222 = 1;
													}
													
													pl222 = 0;
													if (document.getElementById('pol' + num_).checked) {
														pl222 = 1;
													}
													p_cut2222 = 0
													if (document.getElementById('p_cut2_' + num_).checked || (document.getElementById('p_size_cut_op_' + num_).value && document.getElementById('p_size_cut_eq_' + num_).value)) {
														p_cut2222 = 1;
													}
													//	pers = $('#pers' + num_).val();
													pers222 = 0
													if (document.getElementById('pers' + num_).checked) {
														pers222 = 1;
													}
													polsss = 0
													if (document.getElementById('pol' + num_).checked) {
														polsss = 1;
													}
													
													p_stor_i222 = $('#p_stor_i').val();
													
													str = str + tir1222 + "|" + p_eq_i222 + "|" + p_mat_i222 + "|" + size_222 + "|" + vin222 + "|" + max222 + "|" + siz1222 + "|" + pl222 + "|" + p_cut2222 + "|" + pers222 + "|" + p_stor_i222 + "|" + p_kolstr222 + "|" + polsss + "|" + size_222_new + "^";
												}
												
												str = str.slice(0, -1);
												//	console.log(str)
												$.ajax({
													type: "GET",
													url: '../Func_tir.php',
													data: {
														str: str,
														
														}, success: function (data) {//возвращаемый результат от сервера
														get_price1(data);
														
														
													}
												});
												
												
											}
											
											function rts1(id1, id2, id3) {
												var id_eq = document.getElementById(id1).value;
												var id_eq1 = document.getElementById(id3).value;
												var kol = document.getElementById(id2).length;
												var i, y;
												i = 0;
												
												for (y = 0; y < kol; y++) {
													if ($(document.getElementById(id2).options[y]).data('attr-size') == id_eq && $(document.getElementById(id2).options[y]).data('attr') == id_eq1) {
														document.getElementById(id2).options[y].style.display = 'block';
														document.getElementById(id2).options[y].disabled = false;
														i++;
														} else {
														document.getElementById(id2).options[y].style.display = 'none';
														document.getElementById(id2).options[y].disabled = true;
														
													}
												}
												
												$('#' + id2).val('')
												
												
											}
										</script>
										
										<script type="text/javascript">
											function validate_form(idD) {
												if (document.getElementById('kol1').value == "0") {
													alert("Выберите тираж!");
													return;
												}
												if (Number(document.getElementById('par2').value) <= 0 && Number(document.getElementById('par3').value) <= 0) {
													alert("Не указала стоимость!");
													return;
												}
												
												
												if ($("#file").val() == '' && $("#file1").val() == '' && $("#file2").val() == '') {
													
													alert("Не выбраны файлы!");
													return;
													
												}
												
												var form = $('#forms')[0];
												
												var data = new FormData(form);
												
												$.ajax({
													type: "POST",
													enctype: 'multipart/form-data',
													url: "_addOrProdsf.php",
													data: data,
													processData: false,
													contentType: false,
													cache: false,
													success: function (data) {
														location.href = '_addAcct.php?id=' + document.getElementById("orderAcct").value;
													}
												});
												
											}
										</script>
										<script>
											
											function hidemyModal3() {
												$('#myModal3').modal('hide');
												$('#myModalprod1').focus();
											}
											function hidemyModal4() {
												$('#myModal4').modal('hide');
												$('#myModalprod1').focus();
											}
											function addmyModal3() {
												
												$('#myModal3').modal({
													keyboard: false,
													show: true
												})
											}
											function addmyModal4() {
												$('#myModal4').modal({
													keyboard: false,
													show: true
												})
											}
											function diz_fun(price_diz, kurs) {
												var str = '', str1 = '', sum = 0;
												var nodeList = document.getElementsByName('diz');
												var array = Array.prototype.slice.call(nodeList);
												for (var i = 0; i < array.length; i++) {
													if (array[i].checked) {
														str = str + array[i].id.substring(1, array[i].id.length) + ',';
														
														sum = Number(sum) + Number(array[i].value);
														
													}
												}
												document.getElementById('view_diz').value = str.substring(0, str.length - 1);
												summ = (sum / 60) * price_diz * kurs
												document.getElementById('p_prdiz_1').value = summ.toFixed(2);
												
												$('#myModal3').modal('hide');
												$('#myModalprod1').focus();
											}
											function pre_fun(price_diz, kurs) {
												var str = '', str1 = '', sum = 0;
												var nodeList = document.getElementsByName('pre');
												var array = Array.prototype.slice.call(nodeList);
												for (var i = 0; i < array.length; i++) {
													if (array[i].checked) {
														str = str + array[i].id.substring(1, array[i].id.length) + ',';
														sum = Number(sum) + Number(array[i].value);
														
													}
												}
												document.getElementById('view_press').value = str.substring(0, str.length - 1);
												summ = (sum / 60) * price_diz * kurs
												document.getElementById('p_press_1').value = summ.toFixed(2);
												
												$('#myModal4').modal('hide');
												// $('#myModalprod1').focus();
											}
											
											function fun_view_pre(id) {
												var str = 'elemss' + id;
												var str1 = 'p' + id;
												var str3 = 'elem' + id + '1';
												var str4 = 'elem' + id + '2';
												var time, sum, time1, sum1, total_time, total_sum;
												time1 = document.getElementById(str3).innerHTML;
												sum1 = document.getElementById(str4).innerHTML;
												time = document.getElementById('total_time_pre').innerHTML;
												sum = document.getElementById('total_sum_pre').innerHTML;
												
												time1 = Number(time1);
												sum1 = Number(sum1);
												time = Number(time);
												sum = Number(sum);
												
												if (document.getElementById(str1).checked) {
													document.getElementById(str).style.display = 'block';
													total_time = time + time1
													total_sum = sum + sum1
													document.getElementById('total_time_pre').innerHTML = total_time;
													document.getElementById('total_sum_pre').innerHTML = total_sum.toFixed(2);
													
													} else {
													document.getElementById(str).style.display = 'none';
													total_time = time - time1
													total_sum = sum - sum1
													document.getElementById('total_time_pre').innerHTML = total_time;
													document.getElementById('total_sum_pre').innerHTML = total_sum.toFixed(2);
													;
												}
											}
											function fun_view_diz(id) {
												if (id != ""){
													var str = 'elemms' + id;
													var str1 = 'd' + id;
													var str3 = 'elemm' + id + '1';
													var str4 = 'elemm' + id + '2';
													
													if ($("#" + str ).length && $("#" + str1 ).length && $("#" + str3 ).length&& $("#" + str4 ).length ){
														
														var time, sum, time1, sum1, total_time, total_sum;
														time1 = document.getElementById(str3).innerHTML;
														sum1 = document.getElementById(str4).innerHTML;
														time = document.getElementById('total_time_diz').innerHTML;
														sum = document.getElementById('total_sum_diz').innerHTML;
														
														time1 = Number(time1);
														sum1 = Number(sum1);
														time = Number(time);
														sum = Number(sum);
														
														if (document.getElementById(str1).checked == true) {
															document.getElementById(str).style.display = 'block';
															total_time = time + time1
															total_sum = sum + sum1
															document.getElementById('total_time_diz').innerHTML = total_time;
															document.getElementById('total_sum_diz').innerHTML = total_sum.toFixed(2);
															} else {
															document.getElementById(str).style.display = 'none';
															total_time = time - time1
															total_sum = sum - sum1
															document.getElementById('total_time_diz').innerHTML = total_time;
															document.getElementById('total_sum_diz').innerHTML = total_sum.toFixed(2);
														}
													}
												}
												
											}
										</script>
										<!-- jQuery-->
										<script>
											function _reviewAcctProduct2(id) {
												console.clear();
												document.getElementById('show_add').style.display = 'none';
												_reviewAcctProduct3(id);
											}
											
											function _reviewAcctProduct1(id) {
												document.getElementById("directoryCodeStat").selectedIndex = -1;
												document.getElementById("directoryCodeStat").dispatchEvent(new Event('change'));
												console.clear();
												
												document.getElementById('show_add').style.display = 'block';
												_reviewAcctProduct3(id);
											}
											function _reviewAcctProduct3(id) {
												
												$.ajax({
													type: "GET",
													url: '_addOrProdsftest3.php',
													data: {
														id: id
														
														}, success: function (data) {//возвращаемый результат от сервера
														document.getElementById('listss').innerHTML = '';
														document.getElementById('id_prod').value = "";
														document.getElementById('p_cir').value = "";
														document.getElementById('p_names').value = "";
														document.getElementById('p_size').value = "";
														document.getElementById('no_price').checked = false
														
														$('#cl_file').val('');
														$('#print_diz').val('');
														$('#press_diz').val('');
														
														
														var nodeList = document.getElementsByName('diz');
														var array = Array.prototype.slice.call(nodeList);
														var array_id = '';
														for (var i = 0; i < array.length; i++) {
															if (array[i].checked) {
																$("#" + array[i].id).prop('checked', false);
																fun_view_diz(array[i].id.substr(1));
															}
														}
														
														$("#optionsRadiosInline1").prop('checked', false); 
														$("#optionsRadiosInline2").prop('checked', false); 
														$("#optionsRadiosInline3").prop('checked', false); 
														$("#optionsRadiosInline4").prop('checked', false); 
														$("input[name='qwerty']").prop('checked', false);
														document.getElementById("check1").hidden = true;
														document.getElementById("check2").hidden = true;
														document.getElementById("check3").hidden = true;
														document.getElementById("check4").hidden = true;
														document.getElementById("check5").hidden = true;
														document.getElementById("check6").hidden = true;
														document.getElementById("check7").hidden = true;
														document.getElementById("check8").hidden = true;
														document.getElementById("check9").hidden = true;
														document.getElementById("check10").hidden = true;
														document.getElementById("check11").hidden = true;
														document.getElementById("check12").hidden = true;
														document.getElementById("check13").hidden = true;
														var shab = data.split('$');
														document.getElementById('id_prod').value = shab[0];
														document.getElementById('p_cir').value = shab[1];
														
														$('#directoryCodeStat').val(shab[23]);
														document.getElementById("directoryCodeStat").dispatchEvent(new Event('change'));
														
														var tp = getConcatenedTextContent(document.getElementById("directoryCodeStat").options[document.getElementById("directoryCodeStat").selectedIndex]);
														document.getElementById('p_names').value = shab[2].substr(shab[2].indexOf(tp) + 1 + tp.length);
														$('#p_fast').val(shab[3]);
														$('#p_dates_time').val(shab[4]);
														$('#list_comm').val(shab[22]);
														
														sum_rev = 0;
														sum_rev = Number(shab[5]);
														sum_rev = sum_rev.toFixed(2);
														$('#p_sum_all').val(sum_rev);
														$('#p_price_all').val(shab[10]);
														$('#p_size').val(shab[6]);
														shiv = shab[7].split('|');
														$('#p_per_i').val(shiv[0]);
														$('#p_stor_i').val(shiv[1]);
														$('#p_per_mat_i').val(shiv[2]);
														$('#str_tm').val(shab[8]);
														$('#str_tm1').val(shab[9]);
														$('#unit_prod1').val(shab[14]);
														$('#cl_file').val(shab[15]);
														$('#print_diz').val(shab[16]);
														$('#press_diz').val(shab[17]);
														$('#sum_press').val(shab[18]);
														document.getElementById('listss').innerHTML = '';		//shab[12]  						
														document.getElementById('listss').innerHTML = 'Прикреплено ' + shab[18] + ' файлов клиента <a onclick ="list_file(1)"><span class="glyphicon glyphicon-pencil"></span></a><br>Прикреплено ' + shab[19] + ' файлов на Препресс <a onclick ="list_file(2)"><span class="glyphicon glyphicon-pencil"></span></a><br> Прикреплено ' + shab[20] + ' файлов на печать <a onclick ="list_file(3)"><span class="glyphicon glyphicon-pencil"></span></a><br>';
														switch (shab[13]) {
															case "1":
															$('#optionsRadiosInline1').prop('checked', true); 
															$('#maket_sh').val("1");
															document.getElementById('op1').style.display = 'block';
															document.getElementById('op2').style.display = 'none';
															$('#view_diz').val(shab[11] + "");
															str_view_diz = shab[11];
															diz_oper_rev = str_view_diz.split(',');
															//console.log(str_view_diz)
															for (u = 0; u < diz_oper_rev.length ; u++){
																$('#d' + diz_oper_rev[u]).attr('checked', true);
																//console.log(diz_oper_rev[u])
																fun_view_diz(diz_oper_rev[u]);
															}
															break;
															case "2":
															$('#optionsRadiosInline2').prop('checked', true); 
															document.getElementById('op2').style.display = 'block';
															document.getElementById('op1').style.display = 'none';
															$('#maket_sh').val("2");
															
															break;
															case "3":
															$('#optionsRadiosInline3').prop('checked', true); 
															$('#maket_sh').val("3");
															document.getElementById('op1').style.display = 'none';
															document.getElementById('op2').style.display = 'none';
															
															break;
															
															case "4":
															$('#optionsRadiosInline4').prop('checked', true); 
															document.getElementById('op1').style.display = 'none';
															document.getElementById('op2').style.display = 'none';
															$('#maket_sh').val("4");
															document.getElementById('bnt_file').style.display = 'none';
															break;
															
														}
														
														
														shab1 = shab[8].split('^');
														delrowTable();
														if (shab[8] != ''){
															for (t = 0; t < shab1.length; t++) {
																rowTable();
																
																i = t + 1;
																shab2 = shab1[t].split('|');
																$('#p_namepart_' + i).val(shab2[0]);
																$('#p_size_' + i).val(shab2[1]);
																$('#p_kolstr_' + i).val(shab2[2]);
																$('#p_eq_' + i).val(shab2[3]);
																rts('p_eq_' + i, 'p_color_' + i, 'p_sizep_' + i, 'p_mat_' + i);
																

																var kol = document.getElementById('p_sizep_' + i).length;
																for (y = 0; y < kol; y++) {
																	if ($(document.getElementById('p_sizep_' + i).options[y]).data('attr') == shab2[3]) {
																		if (document.getElementById('p_sizep_' + i).options[y].value == shab2[5]) {
																			document.getElementById('p_sizep_' + i).options[y].selected = true;
																		}
																	}
																}
																document.getElementById('p_mat_' + i + "_chosen").style.width = '500px';
																
																var kol = document.getElementById('p_mat_' + i).length;
																for (y = 0; y < kol; y++) {
																	
																	attr = $(document.getElementById('p_mat_' + i).options[y]).data('attr')   + '';
																	
																	att = attr.split(',');
																	l_flag_att = 0; 
																	for (tt = 0 ; tt < att.length; tt++){
																		
																		if (att[tt] == shab2[3]){
																			l_flag_att = 1; 
																		}
																	}
																	
																	if (l_flag_att == 1) {
																		if (document.getElementById('p_mat_' + i).options[y].value == shab2[6]) {
																			
																			document.getElementById('p_mat_' + i).options[y].selected = true;
																		}
																	}
																}
																$('#p_mat_' + i).trigger('chosen:updated');
																
																$('#p_color_' + i).val(shab2[4]);
																if (shab2[7] == "1") {
																	$('#p_cut_' + i).attr('checked', true);
																}
																if (shab2[32] == "1") {
																	$('#p_cut2_' + i).attr('checked', true);
																}
																
																$('#p_size_cut_' + i).val(shab2[33]);
																$('#p_new_size_' + i).val(shab2[34]);
																
																$('#p_lam_' + i).val(shab2[8]);
																$('#p_bug_' + i).val(shab2[9]);
																if ((shab2[9] != '0') && (shab2[9] != '')) {
																	document.getElementById("check13").hidden = false;
																	document.getElementById("check13_").checked = true;
																}
																$('#p_perf_' + i).val(shab2[10]);
																if ((shab2[10] != '0') && (shab2[10] != '')) {
																	document.getElementById("check1").hidden = false;
																	document.getElementById("check1_").checked = true;
																}
																$('#p_ygl_' + i).val(shab2[11]);
																if ((shab2[11] != '0') && (shab2[11] != '')) {
																	document.getElementById("check2").hidden = false;
																	document.getElementById("check2_").checked = true;
																}
																$('#p_otv_' + i).val(shab2[12]);
																if ((shab2[12] != '0') && (shab2[12] != '')) {
																	document.getElementById("check4").hidden = false;
																	document.getElementById("check4_").checked = true;
																	document.getElementById("check3").hidden = false;
																	
																}
																
																if (shab2[13] == "") {
																	$('#p_diam_' + i).val();
																}
																else {
																	$('#p_diam_' + i).val(shab2[13]);
																}
																if ((shab2[13] != '0') && (shab2[13] != '')) {
																	document.getElementById("check4").hidden = false;
																	document.getElementById("check4_").checked = true;
																	document.getElementById("check3").hidden = false;
																	
																}
																
																$('#p_luv_' + i).val('');
																if ((shab2[14] != '0') && (shab2[14] != '')) {
																	$('#p_luv_' + i).val(shab2[14]);
																	document.getElementById("check5").hidden = false;
																	document.getElementById("check5_").checked = true;
																	document.getElementById("check6").hidden = false;
																}
																if (shab2[15] == "") {
																	$('#p_colorluv_' + i).val();
																}
																else {
																	$('#p_colorluv_' + i).val(shab2[15]);
																}
																if ((shab2[15] != '0') && (shab2[15] != '')) {
																	
																	document.getElementById("check5").hidden = false;
																	document.getElementById("check5_").checked = true;
																	document.getElementById("check6").hidden = false;
																}
																$('#p_vir_' + i).val('');
																if ((shab2[16] != '0') && (shab2[16] != '')) {
																	$('#p_vir_' + i).val(shab2[16]);
																	document.getElementById("check7").hidden = false;
																	document.getElementById("check7_").checked = true;
																	document.getElementById("check10").hidden = false;
																}
																$('#p_con_' + i).val(shab2[17]);
																if ((shab2[17] != '0') && (shab2[17] != '')) {
																	document.getElementById("check8").hidden = false;
																	document.getElementById("check8_").checked = true;
																	document.getElementById("check11").hidden = false;
																}
																$('#p_tis_' + i).val(shab2[18]);
																if ((shab2[18] != '0') && (shab2[18] != '')) {
																	document.getElementById("check12").hidden = false;
																	document.getElementById("check9").hidden = false;
																	document.getElementById("check9_").checked = true;
																}
																$('#p_off_' + i).val(shab2[19]);
																$('#p_prstamp_' + i).val(shab2[20]);
																if ((shab2[20] != '0') && (shab2[20] != '')) {
																	document.getElementById("check7").hidden = false;
																	document.getElementById("check7_").checked = true;
																	document.getElementById("check10").hidden = false;
																}
																$('#p_prkl_' + i).val(shab2[21]);
																if ((shab2[21] != '0') && (shab2[21] != '')) {
																	document.getElementById("check8").hidden = false;
																	document.getElementById("check8_").checked = true;
																	document.getElementById("check11").hidden = false;
																}
																$('#p_prckl_' + i).val(shab2[22]);
																if ((shab2[22] != '0') && (shab2[22] != '')) {
																	document.getElementById("check12").hidden = false;
																	document.getElementById("check9").hidden = false;
																	document.getElementById("check9_").checked = true;
																}
																$('#p_prdiz_' + i).val(shab2[23]);
																$('#p_press_' + i).val(shab2[24]);
																$('#vin' + i).val(shab2[25]);
																
																$('#vin1' + i).attr('checked', false);
																if (shab2[26] == "1") {
																	$('#vin1' + i).attr('checked', true);
																}
																$('#max' + i).attr('checked', false);
																if (shab2[27] == "1") {
																	$('#max' + i).attr('checked', true);
																}
																$('#pol' + i).attr('checked', false);
																if (shab2[28] == "1") {
																	$('#pol' + i).attr('checked', true);
																}
																$('#pers' + i).attr('checked', false);
																if (shab2[29] == "1") {
																	$('#pers' + i).attr('checked', true);
																}
																$('#mat_firm' + i).attr('checked', false);
																if (shab2[30] == "1") {
																	$('#mat_firm' + i).attr('checked', true);
																}
																
																$('#p_size_r_' + i).val('');
																$('#p_size_r_' + i).val(shab2[31]);

																if(document.getElementById('p_color_' + i + "_combi")) {
																    if(shab2[35]) {
																        var fr = shab2[35].split('~');
																        var arr_fr = document.getElementsByClassName('p_color_' + i + '_combi_f');
																        for(var i_fr in arr_fr) {
																            for(var j_fr in fr) {
																                if(fr[j_fr] == arr_fr[i_fr].value) {
                                                                                    arr_fr[i_fr].checked = true;
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                    if(shab2[36]) {
                                                                        var fr = shab2[36].split('~');
                                                                        var arr_fr = document.getElementsByClassName('p_color_' + i + '_combi_b');
                                                                        for(var i_fr in arr_fr) {
                                                                            for(var j_fr in fr) {
                                                                                if(fr[j_fr] == arr_fr[i_fr].value) {
                                                                                    arr_fr[i_fr].checked = true;
                                                                                    break;
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }

																if(shab2[37]) {
																    $('#p_size_cut_op_' + i).val(shab2[37]);
																    document.getElementById('p_size_cut_op_' + i).dispatchEvent(new Event('change'));
																    if(shab2[38]) {
                                                                        $('#p_size_cut_eq_' + i).val(shab2[38]);
                                                                        if(shab2[39]) {
                                                                            $('#p_size_cut2_' + i).val(shab2[39]);
                                                                        }
                                                                    }
                                                                }
																
																k = i + "2";
																
															}
															} else {
															rowTable();
														}
														
														var id_acct = "<? echo $idAcct; ?>";
														removeAllTextNodes(document.getElementById("headerModalAddProduct"));
														document.getElementById("headerModalAddProduct").appendChild(document.createTextNode("Добавление продукта в счет №" + id_acct));
														
														$('#myModalprod3').modal('show');
													}
													
												});
												
											}
										</script>
										<script type="text/javascript">
											$(document).on('click', '._kol', function (data) {
												var kol = $('#flag').attr("value");
												if (kol != '0') {
													
													var str = $(this).attr("id");
													
													var val1 = $(this).val()
													
													var sum1 = $('#price' + str[str.length - 1]).attr("value");
													var sum2 = $('#price_' + str[str.length - 1]).attr("value");
													
													$('#kol1').val(val1);
													$('#price1').val(sum1);
													$('#price_1').val(sum2);
													
													$('#par1').val(val1);
													$('#par2').val(sum1);
													$('#par3').val(sum2);
												}
											})
											/*	$('._kol').on('click', function() {
												
											});*/
											
											
											function addProd1() {
											
														
												$("#file_yes").val("1")
												if ($("#filea").val() == '') {
													$("#file_yes").val("0")
													
												}
												
												if (document.getElementById('no_price').checked == false) {
													if (document.getElementById('p_sum_all').value == "0" || document.getElementById('p_sum_all').value == "NaN") {
														alert("Не расчитан заказ!")
														return
													}
													if (document.getElementById('p_dates_time').value == "0" || document.getElementById('p_dates_time').value == "") {
														alert("Не задана дата готовности!")
														return
													}
													
													today = '<?echo date("Y-m-d")?>';
													d1 = new Date(document.getElementById('p_dates_time').value);
													d2 = new Date(today);
													if (d1 < d2) {
														alert("Дата сдачи не может быть меньше текущей!")
														return
													}
													if (document.getElementById('p_size').value == "") {
														alert("Не введен размер продукта!")
														return
													}
												}
												if (document.getElementById('p_cir').value == "0" || document.getElementById('p_cir').value == "") {
													alert("Не введен тираж!")
													return
												}
												if (document.getElementById('unit_prod1').value == "") {
													alert("Укажите единицу измерения!")
													return
												}
												if ((document.getElementById("view_diz").value == '0' || document.getElementById("view_diz").value == '') && document.getElementById("maket_sh").value == "1" ){
													alert("Укажите позиции дизайна!") 
													return
												} 
												
												var form = $('#forms')[0];
												var datass = new FormData(form);
												
												//создаем форму и процент загрузки
												var mod_div = document.createElement('div');
												mod_div.setAttribute('id', "modal_upload");
												mod_div.setAttribute('style', "position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 2000;background: rgba(29,37,49,.9);");
												document.getElementsByTagName('body')[0].appendChild(mod_div);
												var head = document.createElement('h3');
												head.setAttribute('style', "color: #f7e946;top: 40%;position: relative;left: 25%;");
												head.setAttribute('id', "progress_label");
												head.appendChild(document.createTextNode("Загрузка 0%"));
												mod_div.appendChild(head);
												var pr_div = document.createElement('div');
												pr_div.setAttribute('class', "progress");
												pr_div.setAttribute('style', "top: 40%;position: relative;width: 50%;left: 25%;background-color: black;");
												mod_div.appendChild(pr_div);
												var progressbar_div = document.createElement('div');
												progressbar_div.setAttribute('id', "_progressbar");
												progressbar_div.setAttribute('class', "progress-bar");
												progressbar_div.setAttribute('role', "progressbar");
												progressbar_div.setAttribute('style', "width: 0;background-color:#f7e946;");
												progressbar_div.setAttribute('aria-valuenow', "0");
												progressbar_div.setAttribute('aria-valuemin', "0");
												progressbar_div.setAttribute('aria-valuemax', "100");
												pr_div.appendChild(progressbar_div);
												
												var xhr = new XMLHttpRequest();
												// обработчик для закачки
												xhr.upload.onprogress = function(event) {
													var pr = Math.floor(parseFloat(event.loaded) * 100 / parseFloat(event.total));
													removeAllTextNodes(document.getElementById("progress_label"));
													document.getElementById("progress_label").appendChild(document.createTextNode("Загрузка " + pr + "%"));
													document.getElementById("_progressbar").setAttribute('style', "width:" + pr + "%;background-color:#f7e946;");
													document.getElementById("_progressbar").setAttribute('aria-valuenow', pr);
												}

												// обработчики успеха и ошибки
												// если status == 200, то это успех, иначе ошибка
												xhr.onload = xhr.onerror = function() {
													//remove(document.getElementById("modal_upload"));
													if (this.status == 200) {
														location.href = '_addAcct.php?id=' + document.getElementById("orderAcct").value;
													} else {
														alert("error " + this.status);
													}
												};

												xhr.open("POST", "_addOrProdsftest1.php", true);
												xhr.send(datass);
												/*$.ajax({
													type: "POST",
													enctype: 'multipart/form-data',
													url: "_addOrProdsftest1.php",
													data: datass,
													processData: false,
													contentType: false,
													cache: false,
													success: function (data) {
														//alert(data) 
														console.log(data)
														 
														location.href = '_addAcct.php?id=' + document.getElementById("orderAcct").value;
													}
												});*/
												
											}

											function removeAllTextNodes(node) {
												if(!node)
													return;
												if (node.nodeType === 3) {
													node.parentNode.removeChild(node);
												} else if (node.childNodes) {
													for (var i = node.childNodes.length; i--;) {
														removeAllTextNodes(node.childNodes[i]);
													}
												}
											}

                                            //удаление элементов ниже текущего в родительском блоке
                                            //если элемент равен null, то все элементы в блоке
                                            //если родитель равен null, то все элементы в документе
                                            function deleteFooterDOM(parent, element) {
                                                if(parent == null)
                                                    parent = document;
                                                var all = parent.getElementsByTagName("*");

                                                var index = -1;

                                                if(element == null)
                                                    index = 0;

                                                var i = all.length - 1;
                                                while(all[i] != element && i >= 0) {
                                                    remove(all[i]);
                                                    i--;
                                                }
                                            }
											
											function func_mail(id_order, id_client, mail, firm_parent) {
												
												if (mail != '' && mail != '-' && mail != null) {
													
													if (!IsValidateEmail(mail)) {
														do {
															var name = prompt("Введите email?", "");
														} while (!IsValidateEmail(name) && name != null);
														
														save_mail(id_order, id_client, name);
													}
													else {
														post_mail(id_order, id_client, mail, firm_parent)
														
													}
													} else {
													do {
														var name = prompt("Введите email?", "");
													} while (!IsValidateEmail(name) && name != null);
													
													save_mail(id_order, id_client, name);
													
												}
											}
											function IsValidateEmail(email) {
												var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,6})$/;
												return reg.test(email);
											}
											
											function save_mail(id_order, id_client, mail) {
												$.ajax({
													type: "GET",
													url: '_save_mail.php',
													data: {
														id_client: id_client,
														mail: mail
														}, success: function (data) {//возвращаемый результат от сервера
														post_mail(id_order, id_client, mail)
													}
												});
												
											}
											
											function post_mail(id_order, id_client, mail, firm_parent) {
                                                firm_parent = firm_parent ? firm_parent : 1;
												$.ajax({
													type: "GET",
													url: (firm_parent == 2) ? 'proc/acct_prot1_m.php' : 'proc/acct_prot1.php',
													data: {id: id_order}, success: function (data) {//возвращаемый результат от сервера
														$.ajax({
															type: "GET",
															url: '_post_mail.php',
															data: {id_order: id_order, id_client: id_client, mail: mail},
															success: function (data) {//возвращаемый результат от сервера
																alert("Сообщение отправлено!")
															}
														});
													}
												});
												
												
											}
										</script>
										<script>
											function list_file(id) {
												/*$('#cl_file').val(shab[15]);*/
												if (id == '1') {
													
													$.ajax({
														type: "GET",
														url: '../ajax_php_sql.php',
														data: {
															link: $('#cl_file').val(),
															flag: '18'
															}, success: function (data) {//возвращаемый результат от сервера
															
															document.getElementById('info_prod').innerHTML = "";
															document.getElementById('info_prod').innerHTML = data;
														}
													});
												}
												if (id == '2') {
													
													$.ajax({
														type: "GET",
														url: '../ajax_php_sql.php',
														data: {
															link: $('#print_diz').val(),
															flag: '18'
															}, success: function (data) {//возвращаемый результат от сервера
															
															document.getElementById('info_prod').innerHTML = "";
															document.getElementById('info_prod').innerHTML = data;
														}
													});
												}
												if (id == '3') {
													
													$.ajax({
														type: "GET",
														url: '../ajax_php_sql.php',
														data: {
															link: $('#press_diz').val(),
															flag: '18'
															}, success: function (data) {//возвращаемый результат от сервера
															
															document.getElementById('info_prod').innerHTML = "";
															document.getElementById('info_prod').innerHTML = data;
														}
													});
												}
												$('#filesa').modal('show');
											}
											
											function del_file(put, pol) {
												$.ajax({
													type: "GET",
													url: '../ajax_php_sql.php',
													data: {
														put: 'pg/' + put,
														flag: '5'
														}, success: function (data) {//возвращаемый результат от сервера
														
														document.getElementById(pol).style.display = 'none';
													}
												});
											}
											
											function handleFileSelect(evt) {
												var files = evt.target.files; // FileList object
												
												// files is a FileList of File objects. List some properties.
												var output = [];
												for (var i = 0, f; f = files[i]; i++) {
													
													
													output.push('<li><strong>', f.name, '</strong> (', f.type || 'n/a', ') - ',
													f.size, ' bytes, last modified: ',
													f.lastModifiedDate.toLocaleDateString(), '</li>');
												}
												document.getElementById('listss').innerHTML = '';
												document.getElementById('listss').innerHTML = '<ul>' + output.join('') + '</ul>';
											}
											
											document.getElementById('filea').addEventListener('change', handleFileSelect, false);
											
											function add_post(){
											    var parent_company = <?php echo $firm_parent; ?>;
                                                parent_company = parent_company ? parent_company : 1;

												var kol = 1;
												var current;
												var str = "";
												var tbl = document.getElementById ('post_inf');                   // таблица, с которой работаем
												var rws = tbl.rows;                                            // коллекция существующих строк таблицы
												var lst = rws [rws.length - 1]; 
												var cls = lst.cells.length; 
												
												/*for (var i=2; i<rws.length; i++) 							//цикл по всем строкам
												{*/	
												
												for (var t = kol ; t <= document.getElementById('post_kol').value; t ++ ){
													
													
													current = document.getElementById('post_opis' + kol).value;
													str = str + current;
													str = str.concat("$");	
													
													current = document.getElementById('post_mest' + kol).value;
													str = str + current;
													str = str.concat("$");	
													
													current = document.getElementById('post_ves' + kol).value;
													str = str + current;
													str = str.concat("$");	
													
													current = document.getElementById('post_dl' + kol).value;
													str = str + current;
													str = str.concat("$");	
													
													current = document.getElementById('post_wir' + kol).value;
													str = str + current;
													str = str.concat("$");	
													
													current = document.getElementById('post_vis' + kol).value;
													str = str + current;
													str = str.concat("!");
													
													kol++;
													
												}
												var nodeList = document.getElementsByName('chDel');
												var array = Array.prototype.slice.call(nodeList);
												var array_id = '';
												for (var i = 0; i < array.length; i++) {
													if (array[i].checked) {
														array_id = array_id + array[i].value + ",";
													}
												}
												if (array_id != "") {
													array_id = array_id.substring(0, array_id.length - 1);
												}
												
												if ($('#post_date').val() == ''){
													alert('Нет трек-кода');
													return;
													
												}
												$.ajax({
													type: "GET",
													url: '../ajax_php_sql.php',
													data: {
														flag: '47',
														id_ord :  '<?echo $idAcct?>',
														post_fio : $('#post_fio').val(),
														region_id : $('#region_id').val(),
														view_post : $('#view_post').val(),
														post_city : $('#post_city').val(),
														post_raion : $('#post_raion').val(),
														post_street : $('#post_street').val(),
														post_house_num : $('#post_house_num').val(),
														post_house_kor : $('#post_house_kor').val(),
														post_room : $('#post_room').val(),
														post_index : $('#post_index').val(),
														post_phone : $('#post_phone').val(),
														post_price : $('#post_price').val(),
														post_track : $('#post_track').val(),
														post_date : $('#post_date').val(),
														post_mail  : $('#post_mail').val(),
                                                        parent_company: parent_company,
														str : str,
														goods : array_id
														
														}, success: function (data) {//возвращаемый результат от сервера
														alert('Доставка добавлена');

														if(parent_company == 2) {
                                                            window.open('proc/post_m.php?id=' + data, '_blank');
                                                        } else {
                                                            window.open('proc/post.php?id=' + data, '_blank');
                                                        }
														
														if ( $('#post_mail').val() != "") {
															$.ajax({
																type: "GET",
																url: '_post_mail_track.php',
																data: {
																	cod : $('#post_track').val(),
																	mail : $('#post_mail').val()
																}, 
																success: function (data) {
																	
																	$('#form_post').modal('hide')
																}
															}); 
															
														}
														
														
													}
												});
												
												
												
												
											}
											
											function view_post(){
												
												$.ajax({
													type: "GET",
													url: '../ajax_php_sql.php',
													data: {
														flag: '212',
														id_ord :  '<?echo $idAcct?>',
                                                        parent_company : '<? echo $firm_parent; ?>'
														}, success: function (data) {//возвращаемый результат от сервера
														par = data.split('|');
														
														//CLIENT_NAME, PHONE_CITY,post_post_index, post_post_kv, post_post_kor, post_post_street, post_post_city, post_region_id
														console.log(par)
														if(par[11] == ''){
															alert('Закончились ТРЕК-КОДЫ');
															return;
															
														}
														$('#post_fio').val(par[0]);
														$('#post_raion').val(par[13]);
														$('#region_id').val(par[7]);
														$('#view_post').val();
														$('#post_city').val(par[6]);
														$('#post_street').val(par[5]);
														$('#post_house_num').val(par[8]);
														$('#post_house_kor').val(par[4]);
														$('#post_room').val(par[3]);
														$('#post_index').val(par[2]);
														$('#post_phone').val(par[1]);
														$('#post_price').val('')
														$("#post_date").val('<? echo date("Y-m-d")?>');
														$("#post_mail").val(par[14]);
														
														if(par[9] != ''){
															document.getElementById('info_dost').innerHTML = "<br>Адрес: " + par[9] + " <br> телефон: " + par[10];
														}
														
														$("#post_track").val(par[11]);
													}
												});
												
												
												
												
											}
											
											
											
											
											function addRow_post(){
												
												var tbl = document.getElementById ('post_inf');                   // таблица, с которой работаем
												var rws = tbl.rows;                                            // коллекция существующих строк таблицы
												var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
												var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
												//console.log(cls);
												var ro = tbl.insertRow (-1);                                   // добавляем снизу ОБРАЗ ещё одной строки
												
												var kol = document.getElementById ('post_kol').value; 
												kol = Number(kol) + 1;
												document.getElementById ('post_kol').value = kol;
												
												var ce = ro.insertCell (-1);
												ce.innerHTML = "<input type='checkbox' name='post_chk' value='1'>";
												
												ce = ro.insertCell (-1);
												ce.innerHTML = "<input name='post_opis" + kol + "' id = 'post_opis" + kol + "' type='text' value=''  class='form-control'/>";
												
												ce = ro.insertCell (-1);
												ce.innerHTML = "<input name='post_mest" + kol + "' id = 'post_mest" + kol + "' type='text'  class='form-control' />";
												
												ce = ro.insertCell (-1);
												ce.innerHTML = "<input name='post_ves" + kol + "' id = 'post_ves" + kol + "' type='text'  class='form-control' />";
												
												ce = ro.insertCell (-1);
												ce.innerHTML = "<input name='post_dl" + kol + "' id = 'post_dl" + kol + "' type='text'  class='form-control'/>";
												
												ce = ro.insertCell (-1);
												ce.innerHTML = "<input name='post_wir" + kol + "' id = 'post_wir" + kol + "' type='text'  class='form-control' />";
												
												ce = ro.insertCell (-1);
												ce.innerHTML = "<input name='post_vis" + kol + "' id = 'post_vis" + kol + "' type='text'  class='form-control'/>";
												
												
											}
											
											function del_row_post(){
												
												var nodeList = document.getElementsByName('post_chk');
												var array = Array.prototype.slice.call(nodeList);
												for (var i = 0; i < array.length; i++) {
													if (array[i].checked) {
														var tr = array[i].parentNode.parentNode;
														var parent = tr.parentNode
														parent.removeChild(tr);
													}
												}
											}
											
										</script>
										
										<script>
											function ShowFormAddCost(id, mechta = false) {
												if(!id || parseInt(id) <= 0)
													return;
												//создаем форму
												//модалка
												var modal = document.createElement('div');
												modal.setAttribute('class', "modal fade");
												modal.setAttribute('id', "modalSumma");
												modal.setAttribute('tabindex', "-1");
												modal.setAttribute('role', "dialog");
												modal.setAttribute('aria-labelledby', "modalSummaLabel");
												modal.setAttribute('aria-hidden', "true");
												document.getElementsByTagName('body')[0].appendChild(modal);
												//диалог
												var dialog = document.createElement('div');
												dialog.setAttribute('class', "modal-dialog");
												dialog.setAttribute('role', "document");
												modal.appendChild(dialog);
												//контент
												var content = document.createElement('div');
												content.setAttribute('class', "modal-content");
												dialog.appendChild(content);
												//заголовок
												var header = document.createElement('div');
												header.setAttribute('class', "modal-header");
												content.appendChild(header);
												//... кнопка закрыть...
												//...спан...
												var span = document.createElement('span');
												span.setAttribute('class', "close");
												span.setAttribute('data-dismiss', "modal");
												span.setAttribute('aria-label', "Close");
												header.appendChild(span);											
												//значок закрытия
												var i_ = document.createElement('i');
												i_.setAttribute('class', "fa fa-close");
												span.appendChild(i_);
												//...заголовок окна...
												var h4 = document.createElement('h4');
												h4.setAttribute('class', "modal-title");
												h4.setAttribute('id', "modalSummaLabel");
												h4.appendChild(document.createTextNode("Добавить данные в договор"));
												header.appendChild(h4);
												//тело модалки
												var body = document.createElement('div');
												body.setAttribute('class', "modal-body");
												content.appendChild(body);
												//стоимость
												var row = document.createElement('div');
												row.setAttribute('class', "row");
												body.appendChild(row);
												var col = document.createElement('div');
												col.setAttribute('class', "col-md-3");
												col.appendChild(document.createTextNode("Стоимость"));
												row.appendChild(col);
												var col = document.createElement('div');
												col.setAttribute('class', "col-md-4");
												row.appendChild(col);
												//инпут
												var input = document.createElement('input');
												input.setAttribute('type', "number");
												input.setAttribute('id', "modalSummaCost");
												col.appendChild(input);
												var col = document.createElement('div');
												col.setAttribute('class', "col-md-5");
												col.appendChild(document.createTextNode("белорусских рублей"));
												row.appendChild(col);
												//НДС
												var row = document.createElement('div');
												row.setAttribute('class', "row");
												if(mechta) {
                                                    row.setAttribute('hidden', "true");
                                                }
												body.appendChild(row);
												var col = document.createElement('div');
												col.setAttribute('class', "col-md-3");
												col.appendChild(document.createTextNode("НДС"));
												row.appendChild(col);
												var col = document.createElement('div');
												col.setAttribute('class', "col-md-4");
												row.appendChild(col);
												//инпут
												var input = document.createElement('input');
												input.setAttribute('type', "number");
												input.setAttribute('id', "modalSummaNDS");
												col.appendChild(input);
												var col = document.createElement('div');
												col.setAttribute('class', "col-md-5");
												col.appendChild(document.createTextNode("белорусских рублей"));
												row.appendChild(col);
												
												//подвал
												var footer = document.createElement('div');
												footer.setAttribute('class', "modal-footer");
												content.appendChild(footer);
												//кнопка отмена
												var btn_close = document.createElement('button');
												btn_close.setAttribute('type', "button");
												btn_close.setAttribute('class', "btn btn-secondary");
												btn_close.setAttribute('data-dismiss', "modal");
												btn_close.appendChild(document.createTextNode("Отмена"));
												footer.appendChild(btn_close);
												//кнопка Создать
												var btn_print = document.createElement('button');
												btn_print.setAttribute('type', "button");
												btn_print.setAttribute('class', "btn btn-primary");
												btn_print.appendChild(document.createTextNode("Создать"));
												footer.appendChild(btn_print);
												//клик по кнопке
												btn_print.onclick = function() {
													var cost = 0;
													var nds = 0;
													if(document.getElementById("modalSummaCost").value != "")
														cost = document.getElementById("modalSummaCost").value;
													if(document.getElementById("modalSummaNDS").value != "")
														nds = document.getElementById("modalSummaNDS").value;
													if(!mechta) {
                                                        location.href = "proc/temp_doc_client_one.php?id=" + id + "&cost=" + cost + "&nds=" + nds;
                                                    } else {
                                                        location.href = "proc/temp_doc_client_one_m.php?id=" + id + "&cost=" + cost + "&nds=" + nds;
                                                    }
													//закрываем модалку
													$('#modalSumma').modal('hide');
												}
												
												//при скрывании модалки - удаляем её
												$('#modalSumma').on('hidden.bs.modal', function (e) {
													remove(document.getElementById("modalSumma"));
												});
												//открываем модалку
												$('#modalSumma').modal('show');
											}
											
											//перенос позиции в отчет артлайнеров
											function trasferToReportArtliner(id) {
												if(!id || parseInt(id) <= 0)
													return;
												//создаем форму
												//модалка
												var modal = document.createElement('div');
												modal.setAttribute('class', "modal fade");
												modal.setAttribute('id', "modalArt");
												modal.setAttribute('tabindex', "-1");
												modal.setAttribute('role', "dialog");
												modal.setAttribute('aria-labelledby', "modalArtLabel");
												modal.setAttribute('aria-hidden', "true");
												document.getElementsByTagName('body')[0].appendChild(modal);
												//диалог
												var dialog = document.createElement('div');
												dialog.setAttribute('class', "modal-dialog");
												dialog.setAttribute('role', "document");
												modal.appendChild(dialog);
												//контент
												var content = document.createElement('div');
												content.setAttribute('class', "modal-content");
												dialog.appendChild(content);
												//заголовок
												var header = document.createElement('div');
												header.setAttribute('class', "modal-header");
												content.appendChild(header);
												//... кнопка закрыть...
												//...спан...
												var span = document.createElement('span');
												span.setAttribute('class', "close");
												span.setAttribute('data-dismiss', "modal");
												span.setAttribute('aria-label', "Close");
												header.appendChild(span);											
												//значок закрытия
												var i_ = document.createElement('i');
												i_.setAttribute('class', "fa fa-close");
												span.appendChild(i_);
												//...заголовок окна...
												var h4 = document.createElement('h4');
												h4.setAttribute('class', "modal-title");
												h4.setAttribute('id', "modalArtLabel");
												h4.appendChild(document.createTextNode("Добавить данные в договор"));
												header.appendChild(h4);
												//тело модалки
												var body = document.createElement('div');
												body.setAttribute('class', "modal-body");
												body.setAttribute('id', "modalArtBody");
												body.setAttribute('data-id', id);
												content.appendChild(body);
												
												/** ПОЛЯ **/
												//номер заявки в фотостори
												var row = document.createElement('div');
												row.setAttribute('class', "row");
												row.setAttribute('style', "margin: 0");
												body.appendChild(row);
												var label = document.createElement('label');
												label.appendChild(document.createTextNode("Номер заявки в фотостори"));
												row.appendChild(label);
												var input = document.createElement('input');
												input.setAttribute('type', "number");
												input.setAttribute('class', "form-control");
												input.setAttribute('id', "fa_id_order_artliner");
												row.appendChild(input);
												//название
												var row = document.createElement('div');
												row.setAttribute('class', "row");
												row.setAttribute('style', "margin: 0");
												body.appendChild(row);
												var label = document.createElement('label');
												label.appendChild(document.createTextNode("Название"));
												row.appendChild(label);
												var input = document.createElement('input');
												input.setAttribute('type', "text");
												input.setAttribute('class', "form-control");
												input.setAttribute('id', "fa_product_name");
												input.value = "Фотостори";
												row.appendChild(input);
												//тип оплаты
												var row = document.createElement('div');
												row.setAttribute('class', "row");
												row.setAttribute('style', "margin: 0");
												body.appendChild(row);
												var label = document.createElement('label');
												label.appendChild(document.createTextNode("Тип оплаты"));
												row.appendChild(label);
												var select = document.createElement('select');
												select.setAttribute('id', "fa_payment_type");
												select.setAttribute('class', "form-control");
												row.appendChild(select);
												var option = document.createElement('option');
												option.setAttribute('value', "ЕРИП");
												option.appendChild(document.createTextNode("ЕРИП"));
												select.appendChild(option);
												var option = document.createElement('option');
												option.setAttribute('value', "Оплата при доставке");
												option.appendChild(document.createTextNode("Оплата при доставке"));
												select.appendChild(option);
												select.selectedIndex = -1;
												//тип доставки
												var row = document.createElement('div');
												row.setAttribute('class', "row");
												row.setAttribute('style', "margin: 0");
												body.appendChild(row);
												var label = document.createElement('label');
												label.appendChild(document.createTextNode("Тип доставки"));
												row.appendChild(label);
												var select = document.createElement('select');
												select.setAttribute('id', "fa_carriers_type");
												select.setAttribute('class', "form-control");
												row.appendChild(select);
												var option = document.createElement('option');
												option.setAttribute('value', "Белпочта");
												option.appendChild(document.createTextNode("Белпочта"));
												select.appendChild(option);
												var option = document.createElement('option');
												option.setAttribute('value', "ЕМС-экспресс");
												option.appendChild(document.createTextNode("ЕМС-экспресс"));
												select.appendChild(option);
												var option = document.createElement('option');
												option.setAttribute('value', "Офис");
												option.appendChild(document.createTextNode("Офис"));
												select.appendChild(option);
												select.selectedIndex = -1;
												
												//подвал
												var footer = document.createElement('div');
												footer.setAttribute('class', "modal-footer");
												content.appendChild(footer);
												//кнопка отмена
												var btn_close = document.createElement('button');
												btn_close.setAttribute('type', "button");
												btn_close.setAttribute('class', "btn btn-secondary");
												btn_close.setAttribute('data-dismiss', "modal");
												btn_close.appendChild(document.createTextNode("Отмена"));
												footer.appendChild(btn_close);
												//кнопка Создать
												var btn_print = document.createElement('button');
												btn_print.setAttribute('type', "button");
												btn_print.setAttribute('class', "btn btn-primary");
												btn_print.appendChild(document.createTextNode("Добавить в отчет"));
												footer.appendChild(btn_print);
												//клик по кнопке
												btn_print.onclick = function() {
													if(!document.getElementById("modalArtBody").hasAttribute("data-id")) {
														alert("Произошла внутренняя ошибка. Обновите страницу и попробуйте заново");
														return;
													}
													if(document.getElementById("fa_id_order_artliner").value == "") {
														alert("Введите номер заявки в фотостори");
														return;
													}
													if(document.getElementById("fa_product_name").value == "") {
														alert("Введите название");
														return;
													}
													if(document.getElementById("fa_payment_type").selectedIndex < 0) {
														alert("Выберите тип оплаты");
														return;
													}
													if(document.getElementById("fa_carriers_type").selectedIndex < 0) {
														alert("Выберите тип доставки");
														return;
													}
													var send = {
														id_order_artliner: document.getElementById("fa_id_order_artliner").value,
														product_name: document.getElementById("fa_product_name").value,
														payment_type: document.getElementById("fa_payment_type").value,
														carriers_type: document.getElementById("fa_carriers_type").value,
														order: document.getElementById("modalArtBody").getAttribute("data-id"),
													}
													$.ajax({
														type: "GET",
														url: "modeler.php",
														data: {'trasferToReportArtliner': JSON.stringify(send)},
														cache: false,
														success: function(respond) {
															if(respond) {
																//закрываем модалку
																$('#modalArt').modal('hide');
															}
															else {
																alert("Ошибка на сервере: не удалось сохранить данные");
															}
														},
														error: function() {
															alert("Произошла ошибка при отправке данных на сервер. Попробуйте позже");
														}
													});
												}
												
												//при скрывании модалки - удаляем её
												$('#modalArt').on('hidden.bs.modal', function (e) {
													remove(document.getElementById("modalArt"));
												});
												//открываем модалку
												$('#modalArt').modal('show');
											}
											
										</script>
										
										
									</body>
								</html>
								
														