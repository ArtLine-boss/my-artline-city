<?php
	include "../../pages/db.php";
	include "../../pages/pg/utility.php";
	
	//запрос на глобальные параметры
	if( isset( $_GET['globalParameters'] ) ) {
		$return = array();
		$data = json_decode($_GET['globalParameters']);
		if(!empty($data->company_unp)) {
			$unp = json_decode($data->company_unp);
			if(!empty($unp)) {
				foreach($unp as $unp_) {
					$s = "SELECT cl.ID,cl.CLIENT_NAME,cl.ADDRESS_DEV,cl.NADBAVKA FROM clients cl WHERE cl.UNP='".$unp_."'";
					$q = mysql_query($s) or die(null);
					$f = false;
					while($r = mysql_fetch_array($q)) {
						$elem = array(
							'id_client_db' => $r['ID'],
							'surcharge' => $r['NADBAVKA'],
							'client_name_db' => $r['CLIENT_NAME'],
							'addr_client_db' => $r['ADDRESS_DEV'],
						);
						$return['data_client_db'][] = $elem;
						$f = true;
					}
					if($f)
						break;
				}
			}
		}
		else if(!empty($data->client_phone)) {
			$phones = json_decode($data->client_phone);
			if(is_array($phones)) {
				foreach($phones as $phone) {
					if(!empty($phone->VALUE)) {
						$s = "SELECT cl.ID,cl.CLIENT_NAME,cl.ADDRESS_DEV,cl.NADBAVKA FROM clients cl WHERE cl.PHONE_MOB LIKE '%".trim($phone->VALUE)."%' OR cl.PHONE_CITY LIKE '%".trim($phone->VALUE)."%'";
						$q = mysql_query($s) or die(null);
						$f = false;
						while($r = mysql_fetch_array($q)) {
							$elem = array(
								'id_client_db' => $r['ID'],
								'surcharge' => $r['NADBAVKA'],
								'client_name_db' => $r['CLIENT_NAME'],
								'addr_client_db' => $r['ADDRESS_DEV'],
							);
							$return[] = $elem;
							$f = true;
						}
						if($f)
							break;
					}
				}
			}
		}
		//надвака на материалы
		$select = "select val from settings s where s.id = 15";
		$query = mysql_query($select) or die(null);
		if($row = mysql_fetch_array($query))
			$return['nds_marerial'] = $row['val'];
		
		//курс доллара
		$select_dollar = "select val from settings s where s.id = 2";
		$query_dollar = mysql_query($select_dollar) or die(null);
		if($row_dollar = mysql_fetch_array($query_dollar))
			$return['dollar'] = $row_dollar['val'];
		
		//надбавка фирмы
		$select_nds_firma = "select val from settings s where s.id = 11";
		$query_nds_firma = mysql_query($select_nds_firma) or die(null);
		if($row_nds_firma = mysql_fetch_array($query_nds_firma))
			$return['nds_firma'] = $row_nds_firma['val'];
		
		//НДС
		$select_nds = "select val from settings s where s.id = 4";
		$query_nds = mysql_query($select_nds) or die(null);
		if($row_nds = mysql_fetch_array($query_nds))
			$return['nds'] = $row_nds['val'];
		
		//если был передан ид заказа
		if(!empty($data->id_task)) {
			$select_task = "SELECT data,data_calc FROM bitrix24_template_calculation WHERE id=".$data->id_task." AND in_work=0";
			$query_task = mysql_query($select_task) or die(null);
			if($row_task = mysql_fetch_array($query_task)) {
				$return['data'] = $row_task['data'];
				$return['data_calc'] = $row_task['data_calc'];
			}
		}
		
		die(json_encode($return));
	}
	
	//запрос на оборудование
	if( isset( $_GET['loadEquipment'] ) ) {
		$select_ = "SELECT GROUP_CONCAT(id) ids FROM equipment WHERE l_use = 1";
		$query_ = mysql_query($select_) or die(null);
		if($row_ = mysql_fetch_array($query_)) {
			$select = "SELECT id, eq_name, l_use, l_offset, ladnr, uandd, nadb_max,nadb_min,total_max,total_min, oper FROM equipment WHERE id IN (" . $row_['ids'] . ") AND  eq_name IS NOT NULL ORDER BY eq_name";
			$query = mysql_query($select) or die(null);
			while($row = mysql_fetch_array($query)) {
				$elem = array(
					'id' => $row['id'],
					'eq_name' => $row['eq_name'],
					'l_use' => $row['l_use'],
					'l_offset' => $row['l_offset'],
					'ladnr' => $row['ladnr'],
					'uandd' => $row['uandd'],
					'nadb_max' => $row['nadb_max'],
					'nadb_min' => $row['nadb_min'],
					'total_max' => $row['total_max'],
					'total_min' => $row['total_min'],
					'oper' => $row['oper'],
				);
				$return[] = $elem;
			}
		}
		
		die(json_encode($return));
	}
	
	//получение цветов для выбранного оборудования
	if( isset( $_GET['loadCurrentEquipmentColor'] ) ) {
		$data = json_decode($_GET['loadCurrentEquipmentColor']);
		if(!empty($data)) {
			$select_ = "SELECT oper FROM equipment WHERE id=".$data;
			$query_ = mysql_query($select_) or die(null);
			if($row_ = mysql_fetch_array($query_)) {
				$select = "SELECT  o.par, o.OPERATION_PRICE, o.id FROM operations o WHERE FIND_IN_SET(o.id ,'" . $row_['oper'] . "')";
				$query = mysql_query($select) or die(null);
				while($row = mysql_fetch_array($query)) {
					$elem = array(
						'par' => $row['par'],
						'OPERATION_PRICE' => $row['OPERATION_PRICE'],
						'id' => $row['id'],
					);
					$return[] = $elem;
				}
			}
		}
		
		die(json_encode($return));
	}
	
	//получение бумаги для выбранного принтера
	if( isset( $_GET['loadCurrentEquipmentMaterial'] ) ) {
		$data = json_decode($_GET['loadCurrentEquipmentMaterial']);
		if(!empty($data)) {
			$select = "SELECT ttr.ID, ttr.title, ttr.m_price, ttr.m_size, GROUP_CONCAT(ttr.dt3  SEPARATOR ',') idd, ttr.flags , ttr.parent   
						FROM
							(SELECT 
							kl.ID, kl.title, '' m_price, '' m_size, e1.id dt3, kl.flags , kl.parent  
							FROM  
							kl_mat kl, (select e.id, e.mater from equipment e WHERE e.id=".$data.") e1 
							WHERE FIND_IN_SET(kl.ID,e1.mater) ORDER BY kl.parent) ttr
						GROUP BY ttr.ID 
						ORDER BY ttr.parent";
			$query = mysql_query($select) or die(null);
			$flags == "-1";
			$return = "";
			while($row = mysql_fetch_array($query)) {
				$title = array(
					'm_price' => $row['m_price'],
					'm_size' => $row['m_size'],
				);
				if($flags == "-1") {
					$flags = fun_group($row['ID']);
					$namess = fun_names($row['ID']);
					$return .= "<optgroup label='$flags' name = 'optgr'><option value='".$row['ID']."' data-opt_gr = '$flags' data-attr = '".$row['idd']."' data-attr-size = '".$row['m_size']."' title='".json_encode($title)."' " . ">$namess</option>";
				}
				else {
					if($flags != fun_group($row['ID'])) {
						$flags = fun_group($row['ID']);
						$namess = fun_names($row['ID']);
						$return .= "</optgroup> <optgroup label='$flags' name = 'optgr'>";
						$return .= "<option value='".$row['ID']."' data-opt_gr = '$flags' data-attr = '".$row['idd']."' data-attr-size = '".$row['m_size']."' title='".json_encode($title)."' >$namess</option>";
					}
					else {
						$namess = fun_names($row[0]);
						$return .= "<option value='".$row['ID']."'  data-opt_gr = '$flags' data-attr = '".$row['idd']."' data-attr-size = '".$row['m_size']."' title='".json_encode($title)."'>$namess</option>";
					}
				}
			}
		}
		
		die($return);
	}
	
	//получение размеров для выбранного принтера
	if( isset( $_GET['loadCurrentEquipmentSize'] ) ) {
		$data = json_decode($_GET['loadCurrentEquipmentSize']);
		if(!empty($data) && !empty($data->val)) {
			//вынос цвета
			$vynos = 0;
			if(!empty($data->vynos))
				$vynos = floatval($data->vynos);
			//определяем параметры принтера
			$select_eq = "SELECT FORMAT, ladnr, uandd FROM equipment WHERE id=".$data->val." AND l_use=1";
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
				//полный список доступных размеров для принтера
				$select = "SELECT id, size FROM size_print WHERE id IN (".$format.")";
				$query = mysql_query($select) or die(null);
				while($row = mysql_fetch_array($query)) {
					$elem = array(
						'size' => $row['size'],
						'eq_id' => $data->val,
						's_id' => $row['id'],
					);
					$return[] = $elem;
				}
				//проверяем по изделию
				if(!empty($return) && !empty($data->size) && is_array($return) && count($return) > 0) {
					$s1 = floatval(split('[*]', $data->size)[0]);
					$s2 = floatval(split('[*]', $data->size)[1]);
					if(!empty($s1) && !empty($s2)) {
						foreach($return as $elem) {
							$e1 = floatval(split('[*]', $elem['size'])[0]);
							$e2 = floatval(split('[*]', $elem['size'])[1]);
							if(!empty($e1) && !empty($e2)) {
								if(($s1 <= $e1 && $s2 <= $e2) || ($s1 <= $e2 && $s2 <= $e1)) {
									$elem['count_sheet'] = LayoutOnSheet($e1, $e2, $s1, $s2, $top, $left, $vynos);
									if($elem['count_sheet']['count'] == 0)
										continue;
									$tmp_return[] = $elem;
								}
							}
						}
						$return = $tmp_return;
					}
				}
				//проверяем по материалу
				if(!empty($data->material)) {
					$id_mat = $data->material;
					//ищем материалы, которые в наличии
					$select_mat = "SELECT ma.ID id,ma.M_SIZE sz,ma.M_NAME name_mat,ma.M_PRICE cost FROM material_attr ma WHERE ma.id_tree=".$id_mat." AND ma.M_KOL_ALL>0 ORDER BY ma.ID";
					$query_mat = mysql_query($select_mat) or die(null);
					while($row_mat = mysql_fetch_array($query_mat)) {
						if(!empty($row_mat['sz']) && !empty(split('[*]', $row_mat['sz'])[0]) && !empty(split('[*]', $row_mat['sz'])[1]))
							$size_material[] = $row_mat;
					}
					if(count($size_material) == 0 ) {
						//если нет в наличии, то ищем последний материал
						$select_mat = "SELECT ma.ID id,ma.M_SIZE sz,ma.M_NAME name_mat,ma.M_PRICE cost FROM material_attr ma WHERE ma.id_tree=".$id_mat." ORDER BY ma.ID DESC LIMIT 1";
						$query_mat = mysql_query($select_mat) or die(null);
						if($row_mat = mysql_fetch_array($query_mat)) {
							if(!empty($row_mat['sz']) && !empty(split('[*]', $row_mat['sz'])[0]) && !empty(split('[*]', $row_mat['sz'])[1]))
								$size_material[] = $row_mat;
						}
					}
					//смотрим или есть возможность впихнуть печатный лист в материал
					if(count($size_material) > 0) {
						$tmp_return = array();
						foreach($return as $elem) {
							if(!empty($elem['count_sheet']['width_parent']) && !empty($elem['count_sheet']['height_parent'])) {
								$_w = $elem['count_sheet']['width_parent'];
								$_h = $elem['count_sheet']['height_parent'];
							}
							else {
								$_w = floatval(split('[*]', $elem['size'])[0]);
								$_h = floatval(split('[*]', $elem['size'])[1]);
							}
							$tmp_size_material = array();
							foreach($size_material as $sm) {
								$w_ = floatval(split('[*]', $sm['sz'])[0]);
								$h_ = floatval(split('[*]', $sm['sz'])[1]);
								if(!empty($w_) && !empty($h_)) {
									if(($w_ >= $_w && $h_ >= $_h) || ($h_ >= $_w && $w_ >= $_h)) {
										$tmp = array(
											'id_mat' => $sm['id'],
											'name_mat' => $sm['name_mat'],
											'cost' => $sm['cost'],
											'data' => LayoutOnSheet($w_, $h_, $_w, $_h, 0, 0, 0)
										);
										$tmp_size_material[] = $tmp;
									}
								}
							}
							$elem['material'] = $tmp_size_material;
							$tmp_return[] = $elem;
						}
						$return = $tmp_return;
						//проверяем остатки материала при резке
						$tmp_return = array();
						foreach($return as $elem) {
							if(!empty($elem['count_sheet']['width_parent']) && !empty($elem['count_sheet']['height_parent'])) {
								$w = $elem['count_sheet']['width_parent'];
								$h = $elem['count_sheet']['height_parent'];
							}
							else {
								$w = floatval(split('[*]', $elem['size'])[0]);
								$h = floatval(split('[*]', $elem['size'])[1]);
							}
							$d = -1;
							$k = -1;
							$tmp_elem = null;
							foreach($elem['material'] as $mat) {
								$w_ = $mat['data']['width_parent'];
								$h_ = $mat['data']['height_parent'];
								$k_ = floor($w_/$w) * floor($h_/$h);
								$d_ = $w_*$h_ - $k_*$w*$h;
								if($d == -1) {
									$d = $d_;
									$k = $k_;
									$tmp_elem = $mat;
								}
								else if($d_ < $d) {
									$d = $d_;
									$k = $k_;
									$tmp_elem = $mat;
								}
								else if($d_ == $d) {
									if($k_ < $k) {
										$d = $d_;
										$k = $k_;
										$tmp_elem = $mat;
									}
								}
							}
							$elem['material'] = $tmp_elem;
							$tmp_return[] = $elem;
						}
						$return = $tmp_return;
					}
				}
			}
		}
		
		die(json_encode($return));
	}
	
	//подбираем бумагу для позиции "Другой размер"
	if( isset( $_GET['loadMaterialInnerSize'] ) ) {
		$data = json_decode($_GET['loadMaterialInnerSize']);
		if(!empty($data->size) && !empty($data->material)) {
			//вынос
			$rice = 0;
			if(!empty($data->vynos))
				$rice = floatval($data->vynos);
			//поля принтера
			$left = 0;
			if(!empty($data->left))
				$left = floatval($data->left);
			$top = 0;
			if(!empty($data->top))
				$top = floatval($data->top);
			//количество изделий
			$count_product = 0;
			if(!empty($data->count_product))
				$count_product = intval($data->count_product);
			//размеры
			$w = floatval(split('[*]', $data->size)[0]);
			$h = floatval(split('[*]', $data->size)[1]);
			if(empty($w) || empty($h))
				die(null);
			
			$id_mat = $data->material;
			//ищем материалы, которые в наличии
			$select_mat = "SELECT ma.ID id,ma.M_SIZE sz,ma.M_NAME name_mat,ma.M_PRICE cost, ma.M_UNIT FROM material_attr ma WHERE ma.id_tree=".$id_mat." AND ma.M_KOL_ALL>0 ORDER BY ma.ID";
			$query_mat = mysql_query($select_mat) or die(null);
			while($row_mat = mysql_fetch_array($query_mat)) {
				if(!empty($row_mat['sz']) && !empty(split('[*]', $row_mat['sz'])[0]) && !empty(split('[*]', $row_mat['sz'])[1]))
					$size_material[] = $row_mat;
				//для широкоформатки
				else if(!empty($row_mat['sz'])) {
					$sz_d = floatval($row_mat['sz']);
					$row_mat['widescreen_lenght'] = returnWidescreen($sz_d, $w + 2*$rice, $h + 2*$rice, $left, $top, $count_product);
					$row_mat['sz'] = $row_mat['sz']."*".$row_mat['widescreen_lenght'];
					$row_mat['widescreen'] = true;
					$size_material[] = $row_mat;
				}
			}
			if(count($size_material) == 0 ) {
				//если нет в наличии, то ищем последний материал
				$select_mat = "SELECT ma.ID id,ma.M_SIZE sz,ma.M_NAME name_mat,ma.M_PRICE cost FROM material_attr ma WHERE ma.id_tree=".$id_mat." ORDER BY ma.ID DESC LIMIT 1";
				$query_mat = mysql_query($select_mat) or die(null);
				if($row_mat = mysql_fetch_array($query_mat)) {
					if(!empty($row_mat['sz']) && !empty(split('[*]', $row_mat['sz'])[0]) && !empty(split('[*]', $row_mat['sz'])[1]))
						$size_material[] = $row_mat;
					//для широкоформатки
					else if(!empty($row_mat['sz'])) {
						$sz_d = floatval($row_mat['sz']);
						$row_mat['widescreen_lenght'] = returnWidescreen($sz_d, $w + 2*$rice, $h + 2*$rice, $left, $top, $count_product);
						$row_mat['sz'] = $row_mat['sz']."*".$row_mat['widescreen_lenght'];
						$row_mat['widescreen'] = true;
						$size_material[] = $row_mat;
					}
				}
			}
			
			//определяем материал, где влазит печатный лист
			$d = -1;
			$k = -1;
			$r = null;
			if(is_array($size_material)) {
				foreach($size_material as $sm) {
					$w_ = floatval(split('[*]', $sm['sz'])[0]);
					$h_ = floatval(split('[*]', $sm['sz'])[1]);
					$k = LayoutOnSheet($w_, $h_, $w, $h, 0, 0, 0);
					if($k['count'] > 0) {
						$d_ = $w_*$h_ - $w*$h*floatval($k['count']);
						$sm['count_sheet'] = $k;
						if($d == -1) {
							$d = $d_;
							$k = intval($k['count']);
							$r = $sm;
						}
						else if($d_ < $d) {
							$d = $d_;
							$k = intval($k['count']);
							$r = $sm;
						}
						else if($d_ == $d) {
							if($k > intval($k['count'])) {
								$d = $d_;
								$k = intval($k['count']);
								$r = $sm;
							}
						}
					}
				}
			}
			
			$return = $r;
		}
		
		die(json_encode($return));
	}
	
	//список дизайна по умолчанию
	if( isset( $_GET['listDefaultDesign'] ) ) {
		$data = json_decode($_GET['listDefaultDesign']);
		//определяем курс доллара и стоимость часа
		$select_sett = "SELECT val FROM settings WHERE id=3";
		$query_sett = mysql_query($select_sett) or die(null);
		if($r = mysql_fetch_array($query_sett)) {
			$cost = floatval(str_replace(",", ".", $r['val']));
			$select = "SELECT ID,NAME,TIME_ FROM DIZ_OPER WHERE DEFAULT_ = ".$data." ORDER BY NAME";
			$query = mysql_query($select) or die(null);
			while($row = mysql_fetch_array($query)) {
				$price = ceil($cost * floatval($row['TIME_']) * 100 / 60) / 100;
				$elem = array(
					'id' => $row['ID'],
					'name' => $row['NAME'],
					'time_work' => $row['TIME_'],
					'cost' => $price
				);
				$return[] = $elem;
			}
		}
		
		die(json_encode($return));
	}
	
	//сохраняем расчет
	if( isset( $_POST['saveCalc'] ) ) {
		$data = json_decode($_POST['saveCalc']);
		if(!empty($data) && !empty($data->name) && !empty($data->user_id)) {
			//текущая дата
			$dates = date('Y-m-d H:i:s');
			//формируем поля на запись ...
			$str_field = "user_id,name,date_add";
			//... и значения этих полей
			$str_value = $data->user_id.",'".$data->name."','".$dates."'";
			//для обновления
			$str_update = "user_id=".$data->user_id.",name='".$data->name."',date_add='".$dates."'";
						
			if(!empty($data->total)) {
				//количество изделий
				$str_field .= ",total";
				$str_value .= ",".$data->total;
				$str_update .= ",total=".$data->total;
			}
			if(!empty($data->factor)) {
				//количество изделий
				$str_field .= ",factor";
				$str_value .= ",".$data->factor;
				$str_update .= ",factor=".$data->factor;
			}
			if(!empty($data->summ)) {
				//сумма заказа
				$str_field .= ",summ";
				$str_value .= ",".$data->summ;
				$str_update .= ",summ=".$data->summ;
			}
			if(!empty($data->summ_one)) {
				//сумма за один продукт
				$str_field .= ",summ_one";
				$str_value .= ",".$data->summ_one;
				$str_update .= ",summ_one=".$data->summ_one;
			}
			if(!empty($data->client_id)) {
				//ид клиента по Битрикс24
				$str_field .= ",client_id";
				$str_value .= ",".$data->client_id;
				$str_update .= ",client_id=".$data->client_id;
			}
			if(!empty($data->company_id)) {
				//ид компании по Битрекс24
				$str_field .= ",company_id";
				$str_value .= ",".$data->company_id;
				$str_update .= ",company_id=".$data->company_id;
			}
			if(!empty($data->client_id_db)) {
				//ид клиента по внутренней БД
				$str_field .= ",client_id_db";
				$str_value .= ",".$data->client_id_db;
				$str_update .= ",client_id_db=".$data->client_id_db;
			}
			if(!empty($data->data)) {
				//данные формы
				$str_field .= ",data";
				$str_value .= ",'".$data->data."'";
				$str_update .= ",data='".$data->data."'";
			}
			if(!empty($data->data_calc)) {
				//данные расчета
				$str_field .= ",data_calc";
				$str_value .= ",'".$data->data_calc."'";
				$str_update .= ",data_calc='".$data->data_calc."'";
			}
			
			if(empty($data->id))
				$select = "INSERT INTO bitrix24_template_calculation (".$str_field.") VALUES (".$str_value.")";
			else
				$select = "UPDATE bitrix24_template_calculation SET ".$str_update." WHERE id=".$data->id;
			
			$message = array(
				'error' => true,
				'message' => $select
			);
			
			$query = mysql_query($select) or die(json_encode($message));
			if($query) {
				$message = array(
					'error' => false,
					'message' => "Сохранено"
				);
			}
			else {
				$message = array(
					'error' => true,
					'message' => "Не удалось сохранить. Не выполнен запрос"
				);
			}
		}
		else {
			$message = array(
				'error' => true,
				'message' => "Пустой запрос"
			);
		}
		
		die(json_encode($message));
	}
	
	//формируем список расчетов для выбранного клиента
	if( isset( $_GET['listCalcForUsers'] ) ) {
		$data = json_decode($_GET['listCalcForUsers']);
		if(!empty($data)) {
			$client = 0;
			if(!empty($data->client_id))
				$client = intval($data->client_id);
			$company = 0;
			if(!empty($data->company_id))
				$company = intval($data->company_id);
			$str_client = "";
			if(!empty($client)) {
				$str_client = "client_id=".$client." AND ";
			}
			$select = "SELECT id, date_add, name, total, factor, summ, summ_one, order_id, file_path
						FROM bitrix24_template_calculation 
						WHERE ".$str_client."company_id=".$company." AND in_work=0";
			if(!empty($data->user_id))
				$select .= " AND user_id=".$data->user_id;
			$query = mysql_query($select) or die(null);
			while($row = mysql_fetch_array($query)) {
				//определяем список файлов если есть
				if(!empty($row['file_path'])) {
					//префикс пути
					$pref = "../../pages/cron/artliner";
					$pref_download = "/pages/cron/artliner";
					if(is_dir($pref."/".$row['file_path'])) {
						$files = scandir($pref."/".$row['file_path']);
						foreach($files as $file) {
							if($file != "." && $file != "..") {
								if(is_file($pref."/".$row['file_path']."/".$file)) {
									$file_path[] = $pref_download."/".$row['file_path']."/".$file;
								}
							}
						}
						$file_path = json_encode($file_path);
					}
				}
				$elem = array(
					'id' => $row['id'],
					'date_add' => $row['date_add'],
					'name' => $row['name'],
					'total' => $row['total'],
					'factor' => $row['factor'],
					'summ' => $row['summ'],
					'summ_one' => $row['summ_one'],
					'file_path' => $file_path,
				);
				if(!empty($row['order_id']))
					$elem['order_id'] = $row['order_id'];
				$return[] = $elem;
			}
		}
		
		die(json_encode($return));
	}
	
	//удаление выбранного расчета
	if( isset( $_GET['delCurrentCalc'] ) ) {
		$data = json_decode($_GET['delCurrentCalc']);
		if(!empty($data)) {
			$delete = "DELETE FROM bitrix24_template_calculation WHERE id={$data}";
			$return = array(
				'error' => true,
				'message' => "Ошибка запроса: {$delete}"
			);
			$query = mysql_query($delete) or die(json_encode($return));
			if($query) {
				$return['error'] = false;
				$return['message'] = "Удалено";
			}
			else {
				$return['error'] = true;
				$return['message'] = "Ошибка при удалении записи";
			}
		}
		else {
			$return = array(
				'error' => true,
				'message' => "Пустой запрос"
			);
		}
		die(json_encode($return));
	}
	
	//функция определения максимального количества листов на большом листе
	function LayoutOnSheet($w, $h, $w_, $h_, $f_v, $f_g, $v) {
		/* 
			$w - длина родительского листа
			$h - высота родительского листа
			$w_ - длина листа
			$h_ - высота листа
			$f_v - поля принтера по вертикали (сверху, снизу)
			$f_v - поля принтера по горизонтали (слева, справа)
			$v - вынос цвета
		*/
		$echo = 0;
		$w = floatval($w);
		$h = floatval($h);
		$w_ = floatval($w_);
		$h_ = floatval($h_);
		$f_v = floatval($f_v);
		$f_g = floatval($f_g);
		$v = floatval($v);
		//4 варианта раскладки, выбираем оптимальный
		$t1 = ReturnCountLayoutOnSheet($w - $f_g, $h - $f_v, $w_ + 2*$v, $h_ + 2*$v);
		$t2 = ReturnCountLayoutOnSheet($w - $f_g, $h - $f_v, $h_ + 2*$v, $w_ + 2*$v);
		$t3 = ReturnCountLayoutOnSheet($h - $f_g, $w - $f_v, $w_ + 2*$v, $h_ + 2*$v);
		$t4 = ReturnCountLayoutOnSheet($h - $f_g, $w - $f_v, $h_ + 2*$v, $w_ + 2*$v);
		$m = array(
			'count' => $t1,
			'width_parent' => $w,
			'height_parent' => $h,
			'width' => $w_,
			'height' => $h_,
		);
		//определяем максимальный
		if($t2 > $m['count']) {
			$m = array(
				'count' => $t2,
				'width_parent' => $w,
				'height_parent' => $h,
				'width' => $h_,
				'height' => $w_,
			);
		}
		if($t3 > $m['count']) {
			$m = array(
				'count' => $t3,
				'width_parent' => $h,
				'height_parent' => $w,
				'width' => $w_,
				'height' => $h_,
			);
		}
		if($t4 > $m['count']) {
			$m = array(
				'count' => $t4,
				'width_parent' => $h,
				'height_parent' => $w,
				'width' => $h_,
				'height' => $w_,
			);
		}
		return $m;
	}
	
	//раскладка по 4м параметрам... возвращает количество
	function ReturnCountLayoutOnSheet($w, $h, $w_, $h_) {
		return intval(floor($w/$w_) * floor($h/$h_));
	}
	
	//определние длины для широкоформатки
	function returnWidescreen($length, $w, $h, $f_l, $f_t, $count) {
		//погонный метр считаем с двумя полями
		$result = 2 * $f_t;
		//доступная ширина без полей
		$length = $length - 2 * $f_l;
		//определяем минимальный размер и максимальный
		$max = $w;
		$min = $h;
		if($max < $min)
			$max+=+$min-$min=$max;
		//по минимально стороне впихиваем
		$count_g = floor($length / $min);
		$full_col = floor($count / $count_g);
		//остаток элементов
		$mod = $count - $full_col * $count_g;
		//добавляем к результату
		$result += $full_col * $max;
		//допихиваем
		while(true) {
			if($mod <= 0)
				break;
			//если влезло в одну строку
			if(($max * $mod) <= $length) {
				$result += $min;
				break;
			}
			//иначе
			else {
				//если не выгодно впихивать строку с наименьшим, то делаем типа изначальную расскладку
				if(2 * $min > $max) {
					$result += $max;
					break;
				}
				//иначе пихаем с наименьшим и изменяем остаток
				else {
					$result += $min;
					$mod = $mod - floor($length / $max);
				}
			}
		}
		return $result;
	}
?>