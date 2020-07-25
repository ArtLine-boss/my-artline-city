<?
	include "../../pages/db.php";
	include "../../pages/pg/utility.php";
	
	//получение кодов статистики
	if( isset( $_GET['codeStat'] ) ) {
		$select = "SELECT dcs.ID id, dcs.name name, dcsf.comm comm
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
				'name' => $row['name'],
				'comm' => $row['comm'],
			);
			$return[] = $elem;
		}
		
		die(json_encode($return));
	}
	
	//перенос данных из расчета в заявку
	if( isset( $_GET['transfer'] ) ) {
		$data = json_decode($_GET['transfer']);
		if(!$data || empty($data) || empty($data->id) || empty($data->code_stat) || empty($data->name_code_stat) || empty($data->date_rdy))
			die(returnError(true, "Пустые входные данные"));
		
		//текущая дата
		$current_date = date('Y-m-d H:i:s');
		
		//получаем данные из расчета
		$select = "SELECT * FROM bitrix24_template_calculation WHERE id=".$data->id;
		$query = mysql_query($select) or die(returnError(true, "Ошибка запроса: ".$select));
		if($row = mysql_fetch_array($query)) {
			$calc = $row;
		}
		else
			die(returnError(true, "Не найдена запись для расчета"));
		
		//проверяем пользователя по системе
		if(empty($calc['user_id']))
			die(returnError(true, "Пустой пользователь в расчете"));
		$select = "SELECT USER_LOGIN FROM users WHERE id_bitrix24=".$calc['user_id'];
		$query = mysql_query($select) or die(returnError(true, "Ошибка запроса: ".$select));
		if($row = mysql_fetch_array($query)) {
			$user_login = $row['USER_LOGIN'];
		}
		else
			die(returnError(true, "Не найден пользователь"));
		
		//проверяем или есть такая заявка
		$order_id = 0;
		if(!empty($data->order_id)) {
			$select = "SELECT * FROM orders WHERE NUMBER=".$data->order_id;
			$query = mysql_query($select) or die(returnError(true, "Ошибка запроса".$select));
			if($row = mysql_fetch_array($query)) {
				$order_id = intval($data->order_id);
			}
		}
		if(empty($order_id)) {
			//если нет такой заявки или она новая, то создаем
			$ins = "INSERT INTO orders(CALC) VALUES(1)";
			$query = mysql_query($ins) or die(returnError(true, "Ошибка запроса: ".$ins));
			if($query) {
				$order_id = mysql_insert_id();
			}
			else {
				die(returnError(true, "Не удалось создать заявку"));
			}
		}
		
		/************ НЕОБХОДИМО ОБНОВИТЬ ORDERS **************/
		if(!empty($calc['client_id_db'])) {
			$client_id = $calc['client_id_db'];
		}
		else {
			//создаем клиента
			die(returnError(true, "Заглушка для пустого клиента"));
		}
		//формируем запрос на обновление
		$update = "UPDATE orders SET DATE_OR='".$current_date."',USER_ID='".$user_login."',STATUS_ID=1,CLIENT_ID=".$client_id.",CUR_ID=933,CALC=0 WHERE NUMBER=".$order_id;
		$query = mysql_query($update) or die(returnError(true, "Ошибка запроса: ".$update));
		if(!$query)
			die(returnError(true, "Не удалось обновить данные заявки"));
		
		/************ ПИШЕМ ДАННЫЕ В order_product **************/
		//проверяем или есть сметы в заявке и определяем номер текущей сметы
		$smeta = 1;
		$select = "SELECT MAX(num_prod_ord) smeta FROM order_product WHERE order_id=".$order_id;
		$query = mysql_query($select) or die(returnError(true, "Ошибка запроса: ".$select));
		if($row = mysql_fetch_array($query)) {
			$smeta = intval($row['smeta']) + 1;
		}
		
		//данные формы
		$form = json_decode($calc['data'], true);
		$form_calc = json_decode($calc['data_calc'], true);
		$arrayCalc = $form_calc['arrayCalc'];
		
		//определяем количество частей
		$kol = intval($form['kol']);
		//проходим по частям и формируем строку
		$temp_pr = "";
		$template = "";
		for($i = 1; $i <= $kol; $i++) {
			//данные формы
			$temp_pr .= $form['p_namepart_'.$i]."|".$form['p_size_'.$i]."|".$form['p_kolstr_'.$i]."|".$form['p_eq_'.$i]."|".$form['p_color_'.$i]."|".$form['p_sizep_'.$i]."|".$form['p_mat_'.$i]."|".intval($form['p_cut_'.$i])."|".$form['p_lam_'.$i]."|".$form['p_bug_'.$i]."|".$form['p_perf_'.$i]."|".$form['p_ygl_'.$i]."|".$form['p_otv_'.$i]."|".$form['p_diam_'.$i]."|".$form['p_luv_'.$i]."|".$form['p_colorluv_'.$i]."|".$form['p_vir_'.$i]."|".$form['p_con_'.$i]."|".$form['p_tis_'.$i]."|".$form['p_off_'.$i]."|".$form['p_prstamp_'.$i]."|".$form['p_prkl_'.$i]."|".$form['p_prckl_'.$i]."|".$form['p_prdiz_'.$i]."|".$form['p_press_'.$i]."|".$form['vin'.$i]."|".intval($form['vin1'.$i])."|".intval($form['max'.$i])."|".intval($form['pol'.$i])."|".intval($form['pers'.$i])."|".intval($form['mat_firm'.$i])."|".$form['p_size_r_'.$i]."|".intval($form['p_cut2_'.$i])."|".$form['p_size_cut_'.$i]."|".$form['p_new_size_'.$i]."^";
			
			//данные для формы заявки на производство
			$template .= $form['p_namepart_'.$i]."|".$form['p_size_'.$i]."|".$form['p_kolstr_'.$i]."|".returnValSQL("equipment","EQ_NAME",$form['p_eq_'.$i])."|".returnValSQL("operations","PAR",$form['p_color_'.$i])."|".$form['p_sizep_'.$i]."|".fun_names($form['p_mat_'.$i])." : ".$arrayCalc[$i-1]['count_list_pages']."|".intval($form['p_cut_'.$i])."|".returnLaminat($form['p_lam_'.$i])."|".$form['p_bug_'.$i]."|".$form['p_perf_'.$i]."|".$form['p_ygl_'.$i]."|".$form['p_otv_'.$i]."|".returnDiam($form['p_diam_'.$i])."|".$form['p_luv_'.$i]."|".returnColorLuv($form['p_colorluv_'.$i])."|".$form['p_vir_'.$i]."|".$form['p_con_'.$i]."|".$form['p_tis_'.$i]."|".$form['vin'.$i]."|".intval($form['vin1'.$i])."|".intval($form['max'.$i])."|".intval($form['pol'.$i])."|".intval($form['pers'.$i])."|".intval($form['mat_firm'.$i])."|".$form['p_size_r_'.$i]."|".intval($form['p_cut2_'.$i])."|".$form['p_size_cut_'.$i]."|".$form['p_new_size_'.$i]."^";
		}
		
		if(empty($kol))
			die(returnError(true, "Не верно сохранен расчет: пустое количество частей"));
		
		$template = trim($template,"^");
		$temp_pr = trim($temp_pr,"^");
		
		//собираем поля и данные в таблицу продуктов заказа
		$fields = "ORDER_ID,PRODUCT_ID";
		$values = "".$order_id.",0";
		//количество изделий
		if(!empty($calc['total'])) {
			$fields .= ",TOTAL";
			$values .= ",".$calc['total'];
		}
		//цена за изделие
		if(!empty($calc['total']) && !empty($calc['summ'])) {
			$fields .= ",PRICE";
			$values .= ",".floatval($calc['summ']) / floatval($calc['total']);
		}
		//общая стоимость
		if(!empty($calc['summ'])) {
			$fields .= ",SUMM";
			$values .= ",".$calc['summ'];
		}
		//шаблон для производства
		if(!empty($template)) {
			$fields .= ",TEMPLATE";
			$values .= ",'".$template."'";
		}
		//шаблон формы
		if(!empty($temp_pr)) {
			$fields .= ",TEMP_PR";
			$values .= ",'".$temp_pr."'";
		}
		//размер изделия
		if(!empty($form['p_size'])) {
			$fields .= ",SIZE";
			$values .= ",'".$form['p_size']."'";
		}
		//имя
		if(!empty($data->name_code_stat)) {
			$fields .= ",p_names";
			$nm = $data->name_code_stat;
			if(!empty($data->inner_name))
				$nm .= " ".$data->inner_name;
			$values .= ",'".$nm."'";
		}
		//путь к папке печати
		$path_press_diz = "files/prod/".$order_id."/".$order_id."_".$smeta."/press";
		if( ! is_dir( "../../pages/pg/".$path_press_diz ) ) 
			mkdir( "../../pages/pg/".$path_press_diz, 0777, true );
		$fields .= ",press_diz";
		$values .= ",'".$path_press_diz."'";
		//путь к папке препресса
		$path_print_diz = "files/prod/".$order_id."/".$order_id."_".$smeta."/diz";
		if( ! is_dir( "../../pages/pg/".$path_print_diz ) ) 
			mkdir( "../../pages/pg/".$path_print_diz, 0777, true );
		$fields .= ",print_diz";
		$values .= ",'".$path_print_diz."'";
		//сумма препресса
		if(!empty($form['p_press_'])) {
			$fields .= ",sum_press";
			$values .= ",".$form['p_press_'];
		}
		//дизайн
		if(!empty($form['json_design'])) {
			$fields .= ",view_diz";
			$arr_design = $form['json_design'];
			$str_design = "";
			foreach($arr_design as $des) {
				$str_design .= $des['id'].",";
			}
			$values .= ",'".trim($str_design, ",")."'";
		}
		//препресс
		if(!empty($form['optionsRadiosInline2']) && $form['optionsRadiosInline2'] == true) {
			$fields .= ",view_press";
			$values .= ",1";
		}
		//определяем куда отправляем заявку
		$flag = 0;
		$status = 0;
		//на дизайн
		if(!empty($form['optionsRadiosInline1']) && $form['optionsRadiosInline1'] == true) {
			$flag = 1;
			$status = 10;
		}
		//на препресс
		else if(!empty($form['optionsRadiosInline2']) && $form['optionsRadiosInline2'] == true) {
			$flag = 2;
			$status = 11;
		}
		//на печать
		else {
			//проверяем или есть файлы
			if(!empty($_FILES)) {
				$flag = 3;
				$status = 12;
			}
			else {
				$flag = 4;
				$status = 1;
			}
		}
		if(!empty($flag)) {
			$fields .= ",flags";
			$values .= ",".$flag;
		}
		//определяем сшивку
		$str_per = "";
		if(!empty($form['p_per_i']))
			$str_per .= $form['p_per_i']."|";
		if(!empty($form['p_stor_i']))
			$str_per .= $form['p_stor_i']."|";
		if(!empty($form['p_per_mat_i']))
			$str_per .= $form['p_per_mat_i']."|";
		if(!empty($str_per)) {
			$fields .= ",cshivka";
			$values .= ",'".$str_per."'";
		}
		//путь к папке клиентских файлов
		$path_cl_file = "files/prod/".$order_id."/".$order_id."_".$smeta."/client";
		if( ! is_dir( "../../pages/pg/".$path_cl_file ) ) 
			mkdir( "../../pages/pg/".$path_cl_file, 0777, true );
		$fields .= ",cl_file";
		$values .= ",'".$path_cl_file."'";
		//единицы измерения
		if(!empty($form['unit_prod1'])) {
			$fields .= ",units";
			$values .= ",'".$form['unit_prod1']."'";
		}
		//дата готовности
		$fields .= ",dates_rdy";
		$values .= ",'".$data->date_rdy."'";
		//срочность
		if(!empty($form['p_fast'])) {
			$fields .= ",fast";
			$values .= ",".$form['p_fast'];
		}
		//статус заявки
		if(!empty($status)) {
			$fields .= ",status";
			$values .= ",".$status;
		}
		//номер сметы
		if(!empty($smeta)) {
			$fields .= ",num_prod_ord";
			$values .= ",".$smeta;
		}
		//комментарий
		if(!empty($form['list_comm'])) {
			$fields .= ",comment";
			$values .= ",'".$form['list_comm']."'";
		}
		//рассчитанная сумма
		if(!empty($form_calc['all_summ_order_byn_calc'])) {
			$fields .= ",price_sys";
			$values .= ",".$form_calc['all_summ_order_byn_calc'];
		}
		//дата добавления (текущая)
		$fields .= ",add_date";
		$values .= ",'".$current_date."'";
		//код статистики
		$fields .= ",code_stat";
		$values .= ",".$data->code_stat;
		
		//запрос на запись
		$INSERT = "INSERT INTO order_product (".$fields.") VALUES (".$values.")";
		$QUERY = mysql_query($INSERT) or die(returnError(true, "Ошибка запроса: ".$INSERT));
		if(!$QUERY)
			die(returnError(true, "Не удалось выполнить запись продукта заявки"));
		$id_product_ins = mysql_insert_id();
		
		/***************** ПИШЕМ ЗАПИСЬ В log_task *************************/
		$ins = "INSERT INTO log_task(id_prod,user_log,datetime,status_new) VALUES($id_product_ins,'$user_login','$current_date','$status')";
		$query = mysql_query($ins) or die(returnError(true, "Ошибка запроса: ".$ins));
		if(!$query)
			die(returnError(true, "Не удалось поместить заявку в таблицу log_task"));
		
		/**************** ПИШЕМ ФАЙЛЫ ****************************************************/
		$path = "";
		switch($flag) {
			case 1:
				$path = $path_cl_file;
				break;
			case 2:
				$path = $path_print_diz;
				break;
			case 3:
				$path = $path_press_diz;
				break;
		}
		
		if(!empty($path)) {
			$path = "../../pages/pg/".$path;
			// Создадим папку если её нет	 
			if( ! is_dir( $path ) ) 
				mkdir( $path, 0777, true );
			
			foreach( $_FILES as $file ){
				$filename = $path."/".$order_id."_".$smeta."_".$file['name'];
				$index = 1;
				//проверяем имя файла на уникальность
				while(true) {
					if(!file_exists($filename))
						break;
					$filename = $path."/".$order_id."_".$smeta."_".$index."_".$file['name'];
					$index++;
				}
				//перемещаем файл из временного хранилища
				if( !move_uploaded_file( $file['tmp_name'], $filename ) ) {
					die(returnError(true, "Не удалось скопировать файл ".$file['name']." в каталог ".$path));
				}
			}
		}
		
		/************************  ОБНОВЛЯЕМ ДАННЫЕ В ТАБЛИЦЕ РАСЧЕТОВ  ****************************/
		$update = "UPDATE bitrix24_template_calculation SET in_work=1,order_id=".$order_id." WHERE id=".$data->id;
		$query = mysql_query($update) or die(returnError(true, "Ошибка запроса: ".$update));
		if(!$query)
			die(returnError(true, "Ошибка выполнения запроса обновления таблицы расчетов"));
		
		//возвращаем успешное окончание
		die(returnError(false, "Заявка перенесена"));
	}
	
	//функция возвращает значения выбранного поля из таблицы по идентификатору
	function returnValSQL($table, $field, $id) {
		if(empty($table) || empty($field) || empty($id))
			return "";
		
		$SELECT = "SELECT ".$field." FROM ".$table." WHERE id=".$id;
		$QUERY = mysql_query($SELECT);
		if($ROW = mysql_fetch_array($QUERY)) {
			return strval($ROW[$field]);
		}
		else
			return "";
	}
	
	//функция, возвращающая json ошибки
	function returnError($error, $message) {
		$elem = array(
			'error' => $error,
			'message' => $message
		);
		
		return json_encode($elem);
	}
?>