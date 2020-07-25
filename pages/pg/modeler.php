<?php
	include "../db.php";
	include "utility.php";
	include "../cron/PHPExcel/Classes/PHPExcel.php";
	include "../cron/PHPExcel/Classes/PHPExcel/Writer/Excel5.php";
	
	session_start();
	$login = $_SESSION['login'];

	if( isset( $_GET['id_tree'] ) ) {
		$data = json_decode($_GET['id_tree']);
		$return = null;
		if($data && $data != '' && $data > 0) {
			$sql = "SELECT ma.M_SIZE sz, ma.M_KOL_ALL kol FROM material_attr ma WHERE ma.id_tree=".$data." and ma.M_KOL_ALL>0 ORDER BY ma.ID DESC LIMIT 1";
			$result = mysql_query($sql);
			if($row = mysql_fetch_array($result)) {
				$return = $row['sz'];
			}
		}
		
		die(json_encode($return));
	}
	
	//отчет по менеджерам
	if( isset( $_GET['loadReportOfManager'] ) ){
		$sql = "select t8.id, t8.order_id, t8.num_prod_ord num_prod_ord, t8.name,t8.add_date,t8.date_rdy,t8.st,t8.date_,t8.user_fio,t9.client_name
					from
					(select t6.id, t6.order_id, t6.num_prod_ord num_prod_ord, t6.name,t6.add_date,t6.date_rdy,t6.st,t6.date_,t7.user_fio,t6.client_id
					from
					(select *
					from
					(select t3.id, t3.order_id, t3.num_prod_ord num_prod_ord, t3.name,t3.add_date,t3.date_rdy,t3.st,t3.user_id,t3.client_id,t4.datetime date_
					from
					(select t1.ID id, t1.ORDER_ID order_id, t1.num_prod_ord num_prod_ord, t1.p_names name,t1.add_date add_date,t1.dates_rdy date_rdy,t1.status st, t2.user_id, t2.client_id
					from
					(select op.ID, op.ORDER_ID, op.p_names,op.add_date,op.dates_rdy,op.status,op.num_prod_ord from order_product op where op.`status` not in (2,3)) t1
					left join
					(select number, user_id, client_id from orders) t2
					on t1.ORDER_ID=t2.number) t3
					inner join
					(select id_prod, user_log,datetime from log_task) t4
					on t3.id=t4.id_prod and t3.user_id=t4.user_log
					order by t4.datetime desc) t5
					group by t5.id) t6
					left join
					(select user_login,user_fio from users) t7
					on t6.user_id=t7.user_login
					order by t7.user_fio,t6.order_id,t6.num_prod_ord) t8
					left join
					(select id,client_name from clients) t9
					on t8.client_id=t9.id";
		$result = mysql_query($sql);
		while($row = mysql_fetch_array($result)) {
			$data = array(
				'manager' => $row['user_fio'],
				'smeta' => $row['order_id']."_".$row['num_prod_ord'],
				'name' => $row['name'],
				'add_date' => $row['add_date'],
				'date_' => $row['date_'],
				'date_rdy' => $row['date_rdy'],
				'status' => $row['st'],
				'client_name' => $row['client_name']
			);
			$return[] = $data;
		}
		
		die(json_encode($return));
	}
	
	//стоимость операции
	if( isset( $_GET['CostOperations'] ) ) {
		$data = json_decode($_GET['CostOperations']);
		if(!empty($data->operation) && !empty($data->width)) {
			$sql = "SELECT MAKEREADY_PRICE, OPERATION_PRICE FROM operations WHERE ID=".$data->operation;
			$result = mysql_query($sql);
			if($row = mysql_fetch_array($result)) {
				$return = floatval($row['OPERATION_PRICE']);
			}
		}
		die(json_encode($return));
	}
	
	//определение доступных размеров
	if( isset( $_GET['DefineSize'] ) ) {
		$data = json_decode($_GET['DefineSize']);
		if(!empty($data->idprint)) {
			$vynos = 0;
			if(!empty($data->vynos))
				$vynos = floatval($data->vynos);
			//определяем параметры принтера
			$select_eq = "SELECT FORMAT, ladnr, uandd FROM equipment WHERE id=".$data->idprint." AND l_use=1";
			$query_eq = mysql_query($select_eq) or die(null);
			if($row_eq = mysql_fetch_array($query_eq)) {
				$format = $row_eq['FORMAT'];
				$left = floatval($row_eq['ladnr']);
				if(!$data->pol)
					$left = 0;
				$top = floatval($row_eq['uandd']);
				if(!$data->pol)
					$top = 0;
				if(!$format || empty($format))
					die(null);
				//определяем размеры для принтера
				$select_sizeprint = "SELECT id, size FROM size_print WHERE id IN (".$format.")";
				$query_sizeprint = mysql_query($select_sizeprint) or die(null);
				while($row_sizeprint = mysql_fetch_array($query_sizeprint)) {
					$elem = array(
						'id' => $row_sizeprint['id'],
						'size' => $row_sizeprint['size'],
						'equipment' => $data->idprint
					);
					$sizeprint[] = $elem;
				}
				//проверяем или есть размеры
				if(!$sizeprint || !is_array($sizeprint) || count($sizeprint) == 0)
					die(null);
				$size_product = null;
				$size_material = array();
				//если пришел размер изделия
				if(!empty($data->size)) {
					$insize = $data->size;
					$size_product = $data->size;
					$w = floatval(split('[*]', $insize)[0]) + 2 * $vynos;
					$h = floatval(split('[*]', $insize)[1]) + 2 * $vynos;
					for($i = 0; $i < count($sizeprint); $i++) {
						$tmpsize = $sizeprint[$i]['size'];
						$s1 = floatval(split('[*]', $tmpsize)[0]);
						$s2 = floatval(split('[*]', $tmpsize)[1]);
						//проверяем 2 варианта поворота листа и 2 варианта поворота изделия
						$w_ = $s1 - 2 * $left;
						$h_ = $s2 - 2 * $top;
						if($w <= $w_ && $h <= $h_)
							$prsize[] = $sizeprint[$i];
						else if($w <= $h_ && $h <= $w_)
							$prsize[] = $sizeprint[$i];
						else {
							$w_ = $s2 - 2 * $left;
							$h_ = $s1 - 2 * $top;
							if($w <= $w_ && $h <= $h_)
								$prsize[] = $sizeprint[$i];
							else if($w <= $h_ && $h <= $w_)
								$prsize[] = $sizeprint[$i];
						}
					}
					//проверяем или есть размеры
					if(!$prsize || !is_array($prsize) || count($prsize) == 0)
						die(null);
					$sizeprint = $prsize;
				}
				//ели есть ид материала
				$in_size = true;
				if(!empty($data->material)) {
					$id_mat = $data->material;
					//ищем материалы, которые в наличии
					$select_mat = "SELECT ma.M_SIZE sz FROM material_attr ma WHERE ma.id_tree=".$id_mat." AND ma.M_KOL_ALL>0 ORDER BY ma.ID";
					$query_mat = mysql_query($select_mat) or die(null);
					while($row_mat = mysql_fetch_array($query_mat)) {
						$size_material[] = $row_mat['sz'];
						if(empty($row_mat['sz']) || empty(split('[*]', $row_mat['sz'])[0]) || empty(split('[*]', $row_mat['sz'])[1]))
							$in_size = false;
					}
					if(count($size_material) == 0 ) {
						//если нет в наличии, то ищем последний материал
						$select_mat = "SELECT ma.M_SIZE sz FROM material_attr ma WHERE ma.id_tree=".$id_mat." ORDER BY ma.ID DESC LIMIT 1";
						$query_mat = mysql_query($select_mat) or die(null);
						if($row_mat = mysql_fetch_array($query_mat)) {
							$size_material[] = $row_mat['sz'];
							if(empty($row_mat['sz']) || empty(split('[*]', $row_mat['sz'])[0]) || empty(split('[*]', $row_mat['sz'])[1]))
								$in_size = false;
						}
					}
					//смотрим или есть возможность впихнуть печатный лист в материал
					if(count($size_material) > 0 && $in_size) {
						for($i = 0; $i < count($sizeprint); $i++) {
							$w = floatval(split('[*]', $sizeprint[$i]['size'])[0]);
							$h = floatval(split('[*]', $sizeprint[$i]['size'])[1]);
							for($j = 0; $j < count($size_material); $j++) {
								$w_ = floatval(split('[*]', $size_material[$j])[0]);
								$h_ = floatval(split('[*]', $size_material[$j])[1]);
								if($w <= $w_ && $h <= $h_) {
									$tmpmatsize[] = $sizeprint[$i];
									break;
								}
								else if($w <= $h_ && $h <= $w_) {
									$tmpmatsize[] = $sizeprint[$i];
									break;
								}
							}
						}
						//проверяем или есть размеры
						if(!$tmpmatsize || !is_array($tmpmatsize) || count($tmpmatsize) == 0)
							die(null);
						$sizeprint = $tmpmatsize;
					}
				}
				
				//определяем остатки в полученных размерах
				//проходим по раскладке
				if($size_product) {
					$w = floatval(split('[*]', $size_product)[0]) + 2 * $vynos;
					$h = floatval(split('[*]', $size_product)[1]) + 2 * $vynos;
					for($i = 0; $i < count($sizeprint); $i++) {
						$w1_ = floatval(split('[*]', $sizeprint[$i]['size'])[0]) - 2 * $left;
						$h1_ = floatval(split('[*]', $sizeprint[$i]['size'])[1]) - 2 * $top;
						$w2_ = floatval(split('[*]', $sizeprint[$i]['size'])[0]) - 2 * $top;
						$h2_ = floatval(split('[*]', $sizeprint[$i]['size'])[1]) - 2 * $left;
						$delta1 = $w1_ * $h1_ - floor($w1_/$w) * floor($h1_/$h) * $w * $h;
						$delta2 = $w1_ * $h1_ - floor($w1_/$h) * floor($h1_/$w) * $w * $h;
						$delta3 = $w2_ * $h2_ - floor($w2_/$w) * floor($h2_/$h) * $w * $h;
						$delta4 = $w2_ * $h2_ - floor($w2_/$h) * floor($h2_/$w) * $w * $h;
						$delta = $delta1;
						if($delta > $delta2)
							$delta = $delta2;
						if($delta > $delta3)
							$delta = $delta3;
						if($delta > $delta4)
							$delta = $delta4;
						//$sizeprint[$i]['delta'] = $delta;
					}
				}
				//проходим по размерам материала
				if($in_size && $size_material && is_array($size_material) && count($size_material) > 0) {
					for($i = 0; $i < count($sizeprint); $i++) {
						$w = floatval(split('[*]', $sizeprint[$i]['size'])[0]);
						$h = floatval(split('[*]', $sizeprint[$i]['size'])[1]);
						$delta = 0;
						$size_in_material = "";
						if($sizeprint[$i]['delta'])
							$delta = $sizeprint[$i]['delta'];
						$delta_ = -1;
						for($j = 0; $j < count($size_material); $j++) {
							$w_ = floatval(split('[*]', $size_material[$j])[0]);
							$h_ = floatval(split('[*]', $size_material[$j])[1]);
							$delta1 = $w_ * $h_ - floor($w_/$w) * floor($h_/$h) * $w * $h;
							$delta2 = $w_ * $h_ - floor($w_/$h) * floor($h_/$w) * $w * $h;
							if($delta1 > $delta2)
								$delta1 = $delta2;
							if($delta_ == -1 || $delta_ > $delta1) {
								$size_in_material = $size_material[$j];
								$delta_ = $delta1;
							}
						}
						
						$delta += $delta_;
						$sizeprint[$i]['delta'] = $delta;
						$sizeprint[$i]['size_in_material'] = $size_in_material;
					}
				}
				
				$return = $sizeprint;
			}
		}
	
		die(json_encode($return));
	}
	
	//возвращает список продуктов и кодов
	if( isset( $_GET['loadDataCodeStatFull'] ) ) {
		$select = "SELECT dcs.ID id, dcs.code_stat code_stat, dcs.name name, dcsf.comm comm
					FROM
						directoryCodeStat dcs
					LEFT JOIN
						directoryCodeStatFull dcsf
					ON dcs.code_stat=dcsf.code_stat
					WHERE dcs.deleteStatus=0
					ORDER BY dcs.name";
		$query = mysql_query($select) or die(null);
		while($row = mysql_fetch_array($query)) {
			$elem = array(
				'id' => $row['id'],
				'code_stat' => $row['code_stat'],
				'name' => $row['name'],
				'comm' => $row['comm']
			);
			$return[] = $elem;
		}
		die(json_encode($return));
	}
	
	//возвращает список кодов
	if( isset( $_GET['loadDataCodeStat'] ) ) {
		$select = "SELECT dcsf.code_stat code_stat, dcsf.comm comm FROM directoryCodeStatFull dcsf ORDER BY dcsf.code_stat";
		$query = mysql_query($select) or die(null);
		while($row = mysql_fetch_array($query)) {
			$elem = array(
				'code_stat' => $row['code_stat'],
				'comm' => $row['comm']
			);
			$return[] = $elem;
		}
		die(json_encode($return));
	}
	
	//пишем коды
	if( isset( $_GET['insertDataCodeStat'] ) ) {
		$data = json_decode($_GET['insertDataCodeStat']);
		if(!empty($data->name) && !empty($data->code)) {
			$ins = "INSERT INTO directoryCodeStat(code_stat, name) VALUES('".$data->code."','".$data->name."')";
			if(!empty($data->id)) {
				$ins = "UPDATE directoryCodeStat SET code_stat='".$data->code."', name='".$data->name."' WHERE id=".$data->id;
			}
			$query = mysql_query($ins) or die(false);
			if($query)
				die(true);
			else
				die(false);
		}
		else
			die(false);
	}
	
	//текущий код
	if( isset( $_GET['loadDataCodeStatCurrent'] ) ) {
		$data = $_GET['loadDataCodeStatCurrent'];
		if($data) {
			$select = "SELECT code_stat, name FROM directoryCodeStat WHERE id=".$data;
			$query = mysql_query($select) or die(null);
			if($row = mysql_fetch_array($query)) {
				$return = array(
					'code' => $row['code_stat'],
					'name' => $row['name']
				);
			}
		}
		die(json_encode($return));
	}
	
	//удаление конкретного продукта
	if( isset( $_GET['deleteDataCodeStatCurrent'] ) ) {
		$data = $_GET['deleteDataCodeStatCurrent'];
		if($data) {
			$del = "UPDATE directoryCodeStat SET deleteStatus=1 WHERE id=".$data;
			$query = mysql_query($del) or die(false);
			if($query)
				die(true);
			else
				die(false);
		}
		else
			die(false);
	}

	//отчет "Оплачено, но не в работе"
	if( isset($_GET['loadDataNotTask'] )) {
		session_start();
		$login = $_SESSION['login'];
		$__select = "";
		if(!empty($login)) {
			//определяем группу для юзера
			$select = "SELECT * FROM users WHERE user_login='".$login."' AND user_per=3";
			$query = mysql_query($select) or die(null);
			if($row = mysql_fetch_array($query))
				$__select = " AND user_login='".$login."'";
			//получаем данные
			$select = "SELECT t11.id,t11.order_id,t11.p_names,t11.num_prod_ord,t11.user_fio,t11.client_name,t11.SUMM,t11.oplata,IF(t12.summ IS NOT NULL,t11.oplata-t12.summ,t11.oplata) delta
						FROM
							(SELECT t9.id,t9.order_id,t9.p_names,t9.num_prod_ord,t9.user_fio,t10.client_name,t9.SUMM,t9.oplata
							FROM
								(SELECT id,order_id,p_names,num_prod_ord,SUMM,client_id,oplata,user_fio
								FROM
									(SELECT *
									FROM
										(SELECT id,order_id,p_names,num_prod_ord,SUMM,user_id,client_id,oplata
										FROM
											(SELECT *
											FROM
												(SELECT *
												FROM
													(SELECT op.ID, op.ORDER_ID, op.p_names,op.num_prod_ord,op.SUMM FROM order_product op WHERE op.`status`='' AND op.num_prod_ord IS NOT NULL AND op.add_date>='2019-01-01') t1
												LEFT JOIN
													(SELECT number, user_id, client_id FROM orders) t2
												ON t1.ORDER_ID=t2.number) t3
											LEFT JOIN
												(SELECT opl.ORDER_NUM,SUM(opl.ALL_SUM) oplata FROM oplati opl WHERE opl.ORDER_NUM>0 GROUP BY opl.ORDER_NUM) t4
											ON t3.ORDER_ID=t4.ORDER_NUM) t5
										WHERE t5.oplata IS NOT NULL) t6
									LEFT JOIN
										(SELECT user_login,user_fio FROM users WHERE user_per IN (3,4)".$__select.") t7
									ON t6.user_id=t7.user_login) t8
								WHERE t8.user_fio IS NOT NULL) t9
							LEFT JOIN
								(SELECT id,client_name FROM clients) t10
							ON t9.client_id=t10.id) t11
						LEFT JOIN
							(SELECT o_p.ORDER_ID,SUM(o_p.SUMM) summ FROM order_product o_p WHERE o_p.`status`<>'' GROUP BY o_p.ORDER_ID) t12
						ON t11.order_id=t12.ORDER_ID";
			$query = mysql_query($select) or die(null);
			while($row = mysql_fetch_array($query)) {
				$delta = ceil(floatval($row['delta'])*100)/100;
				if($delta <= 0)
					continue;
				$summ = ceil(floatval($row['SUMM'])*100)/100;
				$oplata = ceil(floatval($row['oplata'])*100)/100;
				$send = array(
					'id' => $row['id'],
					'order_id' => $row['order_id'],
					'num' => $row['num_prod_ord'],
					'p_names' => $row['p_names'],
					'client_name' => $row['client_name'],
				);
				$obj = array(
					'id' => $row['id'],
					'order_id' => "<a href = '/pages/pg/_addAcct.php?id=".$row['order_id']."'>".$row['order_id'].'_'.$row['num_prod_ord'].'</a>',
					'p_names' => $row['p_names'],
					'user_fio' => $row['user_fio'],
					'client_name' => $row['client_name'],
					'summ' => $summ,
					'oplata' => $oplata,
					'func' => "<button class='btn btn-warning btn-circle' data-toggle='tooltip' data-original-title='Отправить в работу' onclick='sendInWork(".json_encode($send).")'><i class='fa fa-play'></i></button>",
				);
				$return[] = $obj;
			}
		}
		
		die(json_encode($return));
	}

	//отметка о переносе контакта
	if( isset( $_GET['CheckTransferContact'] ) ) {
		$data = json_decode($_GET['CheckTransferContact']);
		$message = array(
			'error' => true,
			'message' => 'Пустой запрос'
		);
		if(!empty($data)) {
			$update = "UPDATE contact_fresh SET FLAG=1 WHERE ID=".$data;
			$message['message'] = "Ошибка запроса: ".$update;
			$query = mysql_query($update) or die(json_encode($message));
			if($query) {
				$message['error'] = false;
				$message['message'] = "Обновлено";
				die(json_encode($message));
			}
			else {
				$message['message'] = "Не удалось обновить запись";
				die(json_encode($message));
			}
		}
		
		die(json_encode($message));
	}
	
	//инициализация совмещения пользователей
	if( isset( $_GET['InitComboUser'] ) ) {
		$select = "SELECT id, active, login_children FROM combo_users WHERE login_parent='$login' ORDER BY id DESC LIMIT 1";
		$query = mysql_query($select) or die(null);
		$init = true;
		$user = "";
		if($row = mysql_fetch_array($query)) {
			if($row['active'] == 0) {
				$select_ = "SELECT USER_FIO FROM users WHERE USER_LOGIN='".$row['login_children']."'";
				$query_ = mysql_query($select_) or die(null);
				if($row_ = mysql_fetch_array($query_)) {
					$user = $row_['USER_FIO'];
				}
				$init = false;
			}
		}
		if($init) {
			$select = "SELECT u.USER_LOGIN,u.USER_FIO FROM users u WHERE u.USER_PER in (3,4) AND u.USER_LOGIN<>'admins' AND u.USER_LOGIN<>'$login' ORDER BY u.USER_FIO";
			$query = mysql_query($select) or die(null);
			while($row = mysql_fetch_array($query)) {
				$arr[] = array(
					'login' => $row['USER_LOGIN'],
					'user' => $row['USER_FIO'],
				);
			}
		}
		$return = array(
			'init' => $init,
			'data' => $arr,
			'user_children' => $user
		);
		
		die(json_encode($return));
	}
	
	//сохранение совмещения пользователей
	if( isset( $_GET['saveComboUser'] ) ) {
		$message = array(
			'not_error' => false,
			'message' => ""
		);
		$data = json_decode($_GET['saveComboUser']);
		if(!empty($data)) {
			//текущая дата
			$dates = date('Y-m-d H:i:s');
			$insert = "INSERT INTO combo_users(date_combo,login_parent,login_children) VALUES('$dates','$login','$data')";
			$query = mysql_query($insert) or die(null);
			if($query) {
				$message['not_error'] = true;
				$message['message'] = "Сохранено";
			}
			else {
				$message['message'] = "Не удалось сохранить запись";
			}
		}
		else {
			$message['message'] = "Пустой запрос";
		}
		
		die(json_encode($message));
	}
	
	//деактивация совмещения пользователей
	if( isset( $_GET['deactiveComboUser'] ) ) {
		$message = array(
			'not_error' => false,
			'message' => ""
		);
		//ищем запись
		$select = "SELECT id,login_parent,login_children,active FROM combo_users WHERE login_parent='$login' ORDER BY id DESC LIMIT 1";
		$query = mysql_query($select) or die(null);
		if($row = mysql_fetch_array($query)) {
			if($row['active'] == 0) {
				//текущая дата
				$dates = date('Y-m-d H:i:s');
				$insert = "INSERT INTO combo_users(date_combo,login_parent,login_children,active) VALUES('$dates','$login','".$row['login_children']."',1)";
				$query_ = mysql_query($insert) or die(null);
				if($query) {
					$message['not_error'] = true;
					$message['message'] = "Сохранено";
				}
				else {
					$message['message'] = "Не удалось сохранить запись";
				}
			}
			else {
				$message['message'] = "Не удалось найти активное совмещение";
			}
		}
		else {
			$message['message'] = "Не удалось найти совмещение";
		}
		
		die(json_encode($message));
	}
	
	//отметка о смене раскраски
	if( isset( $_GET['SelectedFont'] ) ) {
		$message = array(
			'not_error' => false,
			'message' => ""
		);
		$select = "SELECT color_default FROM users WHERE USER_LOGIN='$login'";
		$query = mysql_query($select) or die(null);
		if($row = mysql_fetch_array($query)) {
			if($row['color_default'] == 0) {
				$color = 1;
			}
			else {
				$color = 0;
			}
			$update = "UPDATE users SET color_default=$color WHERE USER_LOGIN='$login'";
			$query = mysql_query($update) or die(null);
			if($query) {
				$message['not_error'] = true;
				$message['message'] = "Сохранено";
			}
			else {
				$message['message'] = "Не удалось обновить данные";
			}
		}
		else {
			$message['message'] = "Не найден пользователь";
		}
		
		die(json_encode($message));
	}
	
	//получение отчета по артлайнерам
	if( isset( $_GET['loadArtlinerReport'] ) ) {
		$data = json_decode($_GET['loadArtlinerReport']);
		if(!empty($data) && !empty($data->current_page) && !empty($data->quentity) && !empty($data->sortField)) {
			//фильтры
			$where = "";
			if(!empty($data->startDate)) {
				$where .= "date_artliner>='".$data->startDate."'";
			}
			if(!empty($data->endDate)) {
				if(!empty($where))
					$where .= " AND ";
				$where .= "date_artliner<='".$data->endDate."'";
			}
			if(!empty($data->isERIP)) {
				if(!empty($where))
					$where .= " AND ";
				$where .= "payment_type='ЕРИП'";
			}
			if(!empty($data->searchData)) {
				if(!empty($where))
					$where .= " AND ";
				$where .= "carriers_code LIKE '%".$data->searchData."%'";
			}
			if(!empty($where))
				$where = " WHERE ".$where;
			//получаем количество страниц
			$select = "SELECT COUNT(*) q_page FROM artliner_report".$where;
			$query = mysql_query($select) or die(null);
			$q_page = 0;
			if($row = mysql_fetch_array($query)) {
				$q_page = $row['q_page'];
			}
			if($q_page <= 0)
				die(null);
			$nmb_page = ceil(floatval($q_page) / floatval($data->quentity));
			$current_page = 1;
			if(intval($data->current_page) > 0) {
				if($nmb_page < intval($data->current_page)) {
					$current_page = $nmb_page;
				}
				else {
					$current_page = intval($data->current_page);
				}
			}
			
			$order = "ORDER BY ".$data->sortField." ";
			if(!empty($data->sort)) {
				$order .= $data->sort." ";
			}
			$start = (intval($current_page) - 1) * intval($data->quentity);
			$select = "SELECT *
					FROM
						(SELECT * FROM artliner_report ar".$where.") t1
					INNER JOIN
						(SELECT cl.ID ids,
									cl.CLIENT_NAME clientname,
									cl.EMAIL clientemail,
									IF(cl.PHONE_MOB IS NULL,cl.PHONE_CITY,cl.PHONE_MOB) clientphone,
									cl.post_post_index clientpostindex,
									IF(cl.post_post_index IS NULL AND cl.post_post_index<>'',cl.ADDRESS_POST,CONCAT_WS(' ',cl.post_region_id,cl.post_post_city,cl.post_post_street,cl.post_house_num,cl.post_post_kor,cl.post_post_kv)) clientaddress 
						FROM clients cl) t2
					ON t1.id_client=t2.ids
					".$order."LIMIT $start,".$data->quentity;
			
			$query = mysql_query($select) or die(null);
			while($row = mysql_fetch_array($query)) {
				$return['arr'][] = $row;
			}
			if(!empty($return)) {
				$return['current_page'] = $current_page;
				$return['nmb_page'] = $nmb_page;
				$return = json_encode($return);
			}
		}
		die($return);
	}
	
	//обновление данных об артлайнере
	if( isset( $_GET['updateArtliner'] ) ) {
		$data = json_decode($_GET['updateArtliner']);
		if(empty($data->id))
			die(true);
		$id = $data->id;
		$value = "";
		if(!empty($data->id_order))
			$value .= "id_order=".$data->id_order.",";
		if(!empty($data->id_client))
			$value .= "id_client=".$data->id_client.",";
		if(!empty($data->payment))
			$value .= "payment=".$data->payment.",";
		if(!empty($data->payment_date))
			$value .= "payment_date='".$data->payment_date."',";
		if(!empty($data->carriers_code))
			$value .= "carriers_code='".$data->carriers_code."',";
		if(!empty($data->carriers_date))
			$value .= "carriers_date='".$data->carriers_date."',";
		$value = trim($value, ',');
		if(empty($value))
			die(true);
		$update = "UPDATE artliner_report SET $value WHERE id=$id";
		$query = mysql_query($update) or die(true);
		//меняем в расчете
		if(!empty($data->id_client)) {
			$select = "SELECT id_calc FROM artliner_report WHERE id=$id";
			$query = mysql_query($select) or die(true);
			if($row = mysql_fetch_array($query)) {
				$id_calc = $row['id_calc'];
			}
			if(!empty($id_calc)) {
				$update = "UPDATE bitrix24_template_calculation SET client_id_db=".$data->id_client." WHERE id=$id_calc";
				$query = mysql_query($update) or die(true);
			}
		}
		die(true);
	}
	
	//возвращаем список клиентов
	if( isset( $_GET['getClients'] ) ) {
		$select = "SELECT ID,CLIENT_NAME FROM clients";
		$query = mysql_query($select) or die(null);
		while($row = mysql_fetch_array($query)) {
			$return[] = $row;
		}
		die(json_encode($return));
	}
	
	//отчет в excel
	if( isset( $_GET['reportOrder'] ) ) {
		$data = json_decode($_GET['reportOrder']);
		if(empty($data) || empty($data->client))
			die(null);
		
		$id_client = intval($data->client);
		$where_date = "";
		if(!empty($data->startDate)) {
			$where_date .= " AND DATE_OR>='".$data->startDate."'";
		}
		if(!empty($data->endDate)) {
			$where_date .= " AND DATE_OR<='".$data->endDate."'";
		}
		
		$sql = "select cl.CLIENT_NAME, t2.date_or, t2.ORDER_ID, t2.p_names,t2.TOTAL,t2.SIZE,t2.TEMPLATE,t2.cshivka
				from
				(select t1.date_or, t1.client_id, op.ORDER_ID, op.p_names,op.TOTAL,op.SIZE,op.TEMPLATE,op.cshivka
				from
				(select number,client_id, date_or from orders where client_id=".$id_client.$where_date.") t1
				inner join
				order_product op
				on t1.number=op.ORDER_ID) t2
				left join
				clients cl
				on t2.client_id=cl.ID";
		
		$result = mysql_query($sql) or die($sql);
		
		while($row = mysql_fetch_array($result)) {
			$template = $row['TEMPLATE'];
			
			$array = explode("^", $template);
			
			for($i = 0; $i < count($array); $i++) {
				$arr_template = explode("|", $array[$i]);
				
				$size = $row['SIZE'];
				if($arr_template[1] && $arr_template[1] != "")
					$size = $arr_template[1];
				
				$mat_count = $arr_template[6];
				
				$arr_mat_count = explode(":", $mat_count);
				$mat = trim($arr_mat_count[0]);
				$list = trim($arr_mat_count[1]);
				
				$cshivka_s = explode("|", $row['cshivka']);
				$cshivka = trim($cshivka_s[0]);
				
				$arr = array(
					'dates' => substr($row['date_or'], 0, strpos($row['date_or'], " ")),
					'client' => $row['CLIENT_NAME'],
					'name' => $row['p_names'],
					'total' => $row['TOTAL'],
					'size' => $size,
					'pages' => $arr_template[2],
					'material' => $mat,
					'color' => $arr_template[4],
					'list' => $list,
					'cshivka' => returnPer($cshivka),
					'lam' => $arr_template[8]
				);
				
				$json[] = $arr;
			}
		}
		
		$return = array(
			'arr' => $json
		);
		
		if(!empty($data->download)) {
			// Создадим папку если её нет	 
			if( ! is_dir( "files_report" ) ) mkdir( "files_report", 0777 );
			// Создаем объект класса PHPExcel
			$xls = new PHPExcel();
			// Устанавливаем индекс активного листа
			$xls->setActiveSheetIndex(0);
			// Получаем активный лист
			$sheet = $xls->getActiveSheet();
			//Добавляем заголовки
			$sheet->setCellValue('A1', "Клиент");
			$sheet->setCellValue('B1', "Дата");
			$sheet->setCellValue('C1', "Наименование");
			$sheet->setCellValue('D1', "Количество");
			$sheet->setCellValue('E1', "Размер");
			$sheet->setCellValue('F1', "Количество страниц");
			$sheet->setCellValue('G1', "Материал");
			$sheet->setCellValue('H1', "Красочность");
			$sheet->setCellValue('I1', "Количество печатных листов");
			$sheet->setCellValue('J1', "Сшивка");
			$sheet->setCellValue('K1', "Ламинирование");
			//пишем данные
			$num_rows = 1;
			foreach($json as $key) {
				$num_rows++;
				//Добавляем данные
				$sheet->setCellValue('A'.$num_rows, $key['client']);
				$sheet->setCellValue('B'.$num_rows, $key['dates']);
				$sheet->setCellValue('C'.$num_rows, $key['name']);
				$sheet->setCellValue('D'.$num_rows, $key['total']);
				$sheet->setCellValue('E'.$num_rows, $key['size']);
				$sheet->setCellValue('F'.$num_rows, $key['pages']);
				$sheet->setCellValue('G'.$num_rows, $key['material']);
				$sheet->setCellValue('H'.$num_rows, $key['color']);
				$sheet->setCellValue('I'.$num_rows, $key['list']);
				$sheet->setCellValue('J'.$num_rows, $key['cshivka']);
				$sheet->setCellValue('K'.$num_rows, $key['lam']);
			}
			
			// Выводим HTTP-заголовки
			/*header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
			header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
			header ( "Cache-Control: no-cache, must-revalidate" );
			header ( "Pragma: no-cache" );
			header ( "Content-type: application/vnd.ms-excel" );
			header ( "Content-Disposition: attachment; filename=report.xls" );*/

			// Выводим содержимое файла
			$objWriter = new PHPExcel_Writer_Excel5($xls);
			$objWriter->save('files_report/report.xls');
			
			$return['path'] = "files_report/report.xls";
		}
		
		
		die(json_encode($return));
	}
	
	//скачать файл
	if( isset( $_GET['dFile'] ) ){
		$data = json_decode($_GET['dFile']);
		file_force_download($data);
	}
	
	//перенос заявки фотостори в отчет артлайнеров
	if( isset( $_GET['trasferToReportArtliner'] ) ) {
		$data = json_decode($_GET['trasferToReportArtliner']);
		if(empty($data->order) || empty($data->id_order_artliner) || empty($data->product_name) || empty($data->payment_type) || empty($data->carriers_type)) {
			die(null);
		}
		//берем данные по заявке
		$select = "SELECT order_id,total,summ,template,size,add_date FROM order_product WHERE id=".$data->order;
		$query = mysql_query($select) or die(null);
		if($row = mysql_fetch_array($query)) {
			$order_id = $row['order_id'];
			$total = $row['total'];
			$summ = $row['summ'];
			$template = $row['template'];
			$size = $row['size'];
			$add_date = $row['add_date'];
			//определяем количество страниц
			$temp = explode("^", $template);
			if(empty($temp[1]))
				die(null);
			$temp1 = explode("|", $temp[1]);
			if(empty($temp1[2]))
				die(null);
			$pages = intval($temp1[2]);
		}
		else {
			die(null);
		}
		//определяем клиента
		$select = "SELECT o.CLIENT_ID FROM orders o WHERE o.NUMBER=".$order_id;
		$query = mysql_query($select) or die(null);
		if($row = mysql_fetch_array($query)) {
			$client = $row['CLIENT_ID'];
		}
		else {
			die(null);
		}
		//пишем в отчет
		$ins = "INSERT INTO artliner_report(id_order,id_order_artliner,date_artliner,id_client,product_name,product_count,product_size,product_pages,product_laminat,product_summa,payment_type,carriers_type) VALUES($order_id,".$data->id_order_artliner.",'$add_date',$client,'".$data->product_name."',$total,'$size',$pages,'Глянец',$summ,'".$data->payment_type."','".$data->carriers_type."')";
		$query = mysql_query($ins) or die(null);
		if($query)
			die(true);
		else
			die(null);
	}
	
	//сохранение колонок для текущего пользователя
	if( isset( $_GET['saveFieldsForArtlinerReport'] ) ) {
		$data = json_decode($_GET['saveFieldsForArtlinerReport']);
		if(isset($_SESSION['login']) && !empty($data)) {
			//проверяем или есть уже запись для пользователя
			$select = "SELECT id FROM artliner_report_userfields WHERE user_login='".$_SESSION['login']."'";
			$query = mysql_query($select) or die(null);
			if($row = mysql_fetch_array($query)) {
				$id = $row['id'];
			}
			//пишем или обновляем запись
			if(empty($id)) {
				$ins = "INSERT INTO artliner_report_userfields(user_login,array_fields) VALUES('".$_SESSION['login']."','".json_encode($data)."')";
			}
			else {
				$ins = "UPDATE artliner_report_userfields SET array_fields='".json_encode($data)."' WHERE id=$id";
			}
			$query = mysql_query($ins) or die(null);
		}
	}
	
	//функция возврата данных о переплете
	function returnPer($val) {
		$data = array(
			'title' => "1",
			'name' => "пружина 6,4 мм"
		);	
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "14",
			'name' => "2 пружины 6,4 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "15",
			'name' => "3 пружины 6,4 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "2",
			'name' => "пружина 8,0 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "16",
			'name' => "2 пружина 8,0 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "17",
			'name' => "3 пружина 8,0 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "3",
			'name' => "пружина 9,5 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "4",
			'name' => "пружина 11,0 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "5",
			'name' => "пружина 12,7 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "6",
			'name' => "пружина 14,3 мм"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "7",
			'name' => "скоба"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "8",
			'name' => "Твердая обложка (PUR)"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "9",
			'name' => "Твердая обложка (скобы)"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "10",
			'name' => "Твердая обложка"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "11",
			'name' => "Твердая обложка (пружина)"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "12",
			'name' => "термоклей"
		);
		if($val == $data['title'])
			return $data['name'];
		
		$data = array(
			'title' => "13",
			'name' => "нитка"
		);
		if($val == $data['title'])
			return $data['name'];
		
		return "";
	}
	
?>