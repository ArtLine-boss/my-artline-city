<?
	require_once "../../db.php";
	include_once "../../pg/utility.php";	
	require_once( '../../../libs/PrestaShop/PSWebServiceLibrary.php' );
	
	//ответственный
	$USERID = 24;
	//каталог заказов
	$folderName = "\\\\DISKSTATION1515\\Artliner\\storage\\finished";
	//искомый файл
	$fileName = "info.json";
	//получаем имя файла
	$filenames = search_file($folderName, $fileName);
	if(empty($filenames) || !is_array($filenames))
		die("OK");
	
	//объект для расчета с начальными дынными
	$calc = array(
		'kol' => 2, //количество частей
		'p_eq_1' => 1,  //оборудование для печати обложки
		'p_eq_2' => 3,  //оборудование для печати блока
		'p_mat_1' => 387,  //материал обложки
		'p_mat_2' => 13,  //материал блока
		'p_color_1' => 1,  //красочность обложки
		'p_color_2' => 3,  //красочность блока
		'p_per_i' => 8,  //переплет в твердой обложке (pur)
		'p_stor_i' => 1,  //сторона переплета (по умолчанию узкая)
		'p_cut_1' => true,  //резка обложки
		'p_cut_2' => true,  //резка для блока
		'p_per_mat_i' => 430,  //материал переплета
		'pers1' => true,  //перерасчет на термик обложки
		'pers2' => true,  //перерасчет на термик блока
		'p_namepart_1' => "Обложка",  //наименование части обложка
		'p_namepart_2' => "Блок",  //наименование части блока
	);
	
	foreach($filenames as $filename) {
		//открываем файл
		$file = fopen($filename['file'], "r");
		if(!$file) {
			continue;
		}
		
		$contents = fread($file, filesize($filename['file']));
		fclose ($file);
		$object = json_decode($contents);

		//если нет облоги и блока, то пропускаем
		if(empty($object->params[0]->block_workspace) || empty($object->params[0]->cover_workspace))
			continue;
		//имя альбома
		$name_calc = $object->order->number;
		if(strrpos($name_calc, "artlinecity_") >= 0) {
			$name_calc = substr($name_calc, strrpos($name_calc, "artlinecity_") + 12);
		}
		$calc['id_order'] = $name_calc;
		$name_calc = "Альбом артлайнер ".$name_calc;
		$calc['p_names'] = $name_calc;
		//количество изделий
		$calc['p_cir'] = $object->order->quantity;
		//сумма за заказ
		$calc['p_sum_all_hand'] = $object->order->extra_data->total;
		//количество страниц
		$page_count = $object->params[0]->page_count;
		$calc['p_kolstr_2'] = $page_count;
		//размер изделия
		$w = $object->params[0]->block_workspace->pages[0]->width;
		$h = $object->params[0]->block_workspace->pages[0]->height;
		if($w >= $h) {
			$calc['p_stor_i'] = 1;
		}
		else {
			$calc['p_stor_i'] = 2;
		}
		$p_size = $w."*".$h;
		$calc['p_size'] = $p_size;
		//размер блока
		$top = $object->params[0]->block_workspace->pages[0]->edge_width->top;
		$bottom = $object->params[0]->block_workspace->pages[0]->edge_width->bottom;
		$left = $object->params[0]->block_workspace->pages[0]->edge_width->left;
		$right = $object->params[0]->block_workspace->pages[0]->edge_width->right;
		$w = $w + $left + $right;
		$h = $h + $top + $bottom;
		$calc['p_size_2'] = $w."*".$h;
		//размер обложки
		$w = $object->params[0]->cover_workspace->pages[0]->width * 2;
		$h = $object->params[0]->cover_workspace->pages[0]->height;
		$spine_width = $object->params[0]->spine_width;
		$top = $object->params[0]->cover_workspace->pages[0]->edge_width->top;
		$bottom = $object->params[0]->cover_workspace->pages[0]->edge_width->bottom;
		$left = $object->params[0]->cover_workspace->pages[0]->edge_width->left;
		$right = $object->params[0]->cover_workspace->pages[0]->edge_width->right;
		$w = round($w + $left + $right + $spine_width);
		$h = $h + $top + $bottom;
		$calc['p_size_1'] = $w."*".$h;
		
		//собираем клиента
		$client = array(
			'firstname' => $object->order->extra_data->customer_data->firstname,
			'lastname' => $object->order->extra_data->customer_data->lastname,
			'email' => $object->order->extra_data->customer_data->email,
			'address1' => $object->order->shipping_address->address1,
			'address2' => $object->order->shipping_address->address2,
			'city' => $object->order->shipping_address->city,
			'postcode' => $object->order->shipping_address->postcode,
			'phone' => $object->order->shipping_address->phone,
			'country' => $object->order->shipping_address->country->name,
			'firstname_post' => $object->order->shipping_address->first_name,
			'lastname_post' => $object->order->shipping_address->last_name,
			'email_post' => $object->order->shipping_address->email,
			'phone_post' => $object->order->shipping_address->phone,
		);
		
		//адрес клиента строкой
		$addr = "";
		if(!empty($client['country']) && $client['country'] != "Belarus") {
			$addr .= $client['country'] . ", ";
		}
		if(!empty($client['postcode'])) {
			$addr .= $client['postcode'] . " ";
		}
		if(!empty($client['city'])) {
			$addr .= $client['city'] . ", ";
		}
		if(!empty($client['address1'])) {
			$addr .= $client['address1'] . ", ";
		}
		if(!empty($client['address2'])) {
			$addr .= $client['address2'];
		}
		$addr = trim($addr);
		$addr = trim($addr, ",");
		
		//ищем клиента в БД
		$select = "SELECT id FROM clients WHERE email='".$client['email']."'";
		$query = mysql_query($select) or die($select);
		if($row = mysql_fetch_array($query)) {
			$client['client_id_db'] = $row['id'];
		}
		//иначе создаем клиента
		else {			
			$client_name = trim($client['firstname']." ".$client['lastname']);
			
			$ins = "INSERT INTO clients (CLIENT_NAME,EMAIL,CLIENT_STATUS,PHONE_MOB,ADDRESS_POST,ADDRESS_DEV) VALUES('$client_name','".$client['email']."','f','".$client['phone']."','$addr','$addr')";
			$query = mysql_query($ins) or die($ins);
			if($query) {
				$client['client_id_db'] = mysql_insert_id();
			}
			else {
				continue;
			}
		}
		//получаем сообщения пользователя
		$COMMENT = customer_message($object->order->extra_data->reference);
		//пишем имя и адрес в комменты
		$COMMENT .= "\r\nЗаказчик: ".$client['lastname_post']." ".$client['firstname_post']." \r\nАдрес: " . $addr;
		
		//проверяем или уже есть такой заказ
		$select = "SELECT id,total FROM bitrix24_template_calculation WHERE artliner_id='".$object->order->number."'";
		$query = mysql_query($select) or die($select);
		if($row = mysql_fetch_array($query)) {
			$calc['id_calc'] = $row['id'];
			$total = intval($row['total']) + $calc['p_cir'];
			$update = "UPDATE bitrix24_template_calculation SET total=$total WHERE id=".$row['id'];
			$query_update = mysql_query($update) or die($update);
			if(!$query_update)
				die("error_update");
		}
		else {
			//обрабатываем файлы
			$folder_n = $object->order->number;
			
			//ищем клиента
			$queryUrl = 'https://artlinecity.bitrix24.ru/rest/1/c27x1afygwvhpvij/crm.contact.list.json';
			$queryData = http_build_query(array(
				'select' => array('ID'),
				'filter' => array(
					'NAME' => $client['firstname'],
					'LAST_NAME' => $client['lastname'],
					"EMAIL" => $client['email'],
					//"EMAIL_WORK" => $client['email'],
				)
			));

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_POST => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $queryUrl,
				CURLOPT_POSTFIELDS => $queryData,
			));

			$result = curl_exec($curl);
			curl_close($curl);
			$json = json_decode($result);
			$CONTACT_ID = 0;
			//если нашли, то пишем его ид
			if(!empty($json->result) && is_array($json->result)) {
				$res = $json->result;
				if(count($res) == 1 && !empty($res[0]->ID)) {
					$CONTACT_ID = $res[0]->ID;
				}
			}
			
			//иначе создаем нового клиента
			if(empty($CONTACT_ID)) {
				$queryUrl = 'https://artlinecity.bitrix24.ru/rest/1/c27x1afygwvhpvij/crm.contact.add.json';
				$send = array(
					'fields' => array(
						'NAME' => $client['firstname'],
						'LAST_NAME' => $client['lastname'],
						'PHONE' => array(array("VALUE" => $client['phone'], "VALUE_TYPE" => "WORK" )),
						'EMAIL' => array(array("VALUE" => $client['email'], "VALUE_TYPE" => "WORK" )),
						"OPENED" => "Y",
						"ASSIGNED_BY_ID" => $USERID,
					)
				);
				$queryData = http_build_query($send);
				$curl = curl_init();
				curl_setopt_array($curl, array(
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_POST => 1,
					CURLOPT_HEADER => 0,
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => $queryUrl,
					CURLOPT_POSTFIELDS => $queryData,
				));

				$result = curl_exec($curl);
				curl_close($curl);
				$json = json_decode($result);
				if(!empty($json->result) && is_int($json->result)) {
					$CONTACT_ID = $json->result;
				}
				else {
					die(null);
				}
			}
			
			$arr_files = $object->render->files;
			foreach($arr_files as $af) {
				$filename_n = $calc['id_order']."_".$object->number;
				if(strpos($af->filename, "_cover") > 0) {
					$filename_n .= "_cover";
				}
				else if(strpos($af->filename, "_pages") > 0) {
					$filename_n .= "_pages";
				}
				
				$index = 1;
				while(file_exists("files/".$folder_n."/".$filename_n.".pdf")) {
					$filename_n .= "_".$index;
					$index++;
				}
				
				if( ! is_dir( "files" ) ) mkdir( "files", 0777 );
				if( ! is_dir( "files/".$folder_n ) ) mkdir( "files/".$folder_n, 0777 );
				if(!copy($filename['folder']."/".$af->filename, "files/".$folder_n."/".$filename_n.".pdf")) {
					die("error copy");
				}
			}		
			
			$queryUrl = 'https://artlinecity.bitrix24.ru/rest/1/c27x1afygwvhpvij/crm.lead.add.json';
			$send = array(
				'fields' => array(
					"TITLE" => $name_calc,
					'CONTACT_ID' => $CONTACT_ID,
					"STATUS_ID" => "NEW",
					"OPENED" => "Y",
					"ASSIGNED_BY_ID" => $USERID,
					"COMMENTS" => $COMMENT,
					"OPPORTUNITY" => $calc['p_sum_all_hand'],
				),
				'params' => array("REGISTER_SONET_EVENT" => "Y")
			);
			
			$queryData = http_build_query($send);
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_POST => 1,
				CURLOPT_HEADER => 0,
				CURLOPT_RETURNTRANSFER => 1,
				CURLOPT_URL => $queryUrl,
				CURLOPT_POSTFIELDS => $queryData,
			));

			$result = curl_exec($curl);
			curl_close($curl);
			
			//пишем расчет
			$json = json_decode($result);
			if(!empty($json->result) && is_int($json->result)) {
				//текущая дата
				$dates = date('Y-m-d H:i:s');
				$ins = "INSERT INTO bitrix24_template_calculation (user_id,date_add,name,client_id,client_id_db,data,total,artliner_id,file_path) 
						VALUES($USERID,'$dates','$name_calc',$CONTACT_ID,".$client['client_id_db'].",'".json_encode($calc, JSON_UNESCAPED_UNICODE)."',".$calc['p_cir'].",'".$object->order->number."','files/".$folder_n."')";
				$query_ins = mysql_query($ins) or die($ins);
				if($query_ins) {
					$calc['id_calc'] = mysql_insert_id();
				}
				else
					die(null);
			}
		}
		
		//пишем в статистику
		//способ доставки
		$calc['carriers'] = carriers_order($calc['id_order']);
		if(!empty($calc['carriers'])) {
			if($calc['carriers'] == "Artliner.by") {
				$calc['carriers'] = "Офис";
			}
		}
		//способ оплаты
		$calc['payment_method'] = payment_method_order($calc['id_order']);
		if(!empty($calc['payment_method'])) {
			if($calc['payment_method'] == "BeGateway") {
				$calc['payment_method'] = "ЕРИП";
			}
		}
		
		if(empty($client['client_id_db']))
			continue;
		
		$field = "id_calc,id_order_artliner,date_artliner,id_client,product_name,product_count,product_size,product_pages,product_laminat,product_summa,payment_type,
					carriers_type,post_index,post_address,post_username,post_email,post_phone";
					
		$email_post = $client['email_post'];
		if(empty($email_post))
			$email_post = $client['email'];
		$phone_post = $client['phone_post'];
		if(empty($phone_post))
			$phone_post = $client['phone'];
		
		$values = $calc['id_calc'].",".$calc['id_order'].",'".date("Y-m-d", strtotime($object->order->create_time))."',".$client['client_id_db'].",'Альбом',".$calc['p_cir'].",'".$calc['p_size']."',".$calc['p_kolstr_2'].",'Мат',".$calc['p_sum_all_hand'].",'".$calc['payment_method']."','".$calc['carriers']."','".$client['postcode']."','".$addr."','".$client['lastname_post']." ".$client['firstname_post']."','$email_post','$phone_post'";
		
		$ins = "INSERT INTO artliner_report($field) VALUES($values)";
		$query = mysql_query($ins) or die($ins);
		
		//переименовываем файл
		rename($filename['file'], $filename['folder']."/out_info.json");
	}
	
	/**
	* Поиск файла по имени во всех папках и подпапках
	* 
	* @param string $folderName - пусть до папки
	* @param string $fileName - искомый файл
	*/
	function search_file($folderName, $fileName){
		// открываем текущую папку 
		$dir = opendir($folderName); 
		// перебираем папку 
		$paths = array();
		while (($file = readdir($dir)) !== false){ // перебираем пока есть файлы
			if($file != "." && $file != ".."){ // если это не папка
				if(is_file($folderName."/".$file)){ // если файл проверяем имя
					// если имя файла нужное, то вернем путь до него
					if($file == $fileName) {
						$paths[] = array(
							'file' => $folderName."/".$file,
							'folder' => $folderName
						);
					}
				} 
				// если папка, то рекурсивно вызываем search_file
				if(is_dir($folderName."/".$file)) $paths = array_merge($paths, search_file($folderName."/".$file, $fileName));
			} 
		}
		// закрываем папку
		closedir($dir);
		return $paths;
	}
	
	/**
	* Поиск сообщений для заказа
	* $reference - код заказа
	**/
	function customer_message($reference = null) {
		try {
			if(empty($reference))
				throw new PrestaShopWebserviceException("");
			
			$webService = new PrestaShopWebservice('https://artliner.by/api/prestashop-webservice/', '8XWV2Q4VTVS5ZAVH43DNRNJG8528WPGQ', false);
			
			//получаем ид заказа
			$query = array(
				'resource' => 'orders',
				'filter[reference]' => $reference,
			);
			$id_order = $webService->get($query);
			$id_order = $id_order->orders[0]->id;
			if(empty($id_order))
				return "";
			
			//получаем связь заказ - сообщение
			$query = array(
				'resource' => 'customer_threads',
				'display' => 'full',
				'filter[id_order]' => "[$id_order]",
			);
			$id_customer_threads = $webService->get($query);
			$id_customer_threads = $id_customer_threads->customer_threads[0]->id;
			if(empty($id_customer_threads))
				return "";
			
			//получаем сообщение
			$query = array(
				'resource' => 'customer_messages',
				'display' => '[message]',
				'filter[id_customer_thread]' => "[$id_customer_threads]",
			);
			$customer_messages = $webService->get($query);
			$customer_messages = $customer_messages->customer_messages;
			$return = "";
			if(!empty($customer_messages) && is_array($customer_messages) && count($customer_messages) > 0) {
				for($i = 1; $i <= count($customer_messages); $i++) {
					$return .= $i.". ".$customer_messages[$i - 1]->message."\r\n";
				}
			}
			return $return;
		}
		catch (PrestaShopWebserviceException $ex) {
			//сообщение об ошибке
			return "";
		}
	}
	
	/**
		Поиск способа доставки
	**/
	function carriers_order($id_order) {
		$webService = new PrestaShopWebservice('https://artliner.by/api/prestashop-webservice/', '8XWV2Q4VTVS5ZAVH43DNRNJG8528WPGQ', false);
		
		try {
			if(empty($id_order))
				throw new PrestaShopWebserviceException("");
			$query = array(
				'resource' => 'order_carriers',
				'filter[id_order]' => "[$id_order]",
				'display' => 'full',
			);
			//запрос на связь
			$order_carriers = $webService->get($query);
			$id_carriers = $order_carriers->order_carriers[0]->id_carrier;
			//запрос на способ доставки
			$query = array(
				'resource' => 'carriers',
				'filter[id]' => "[$id_carriers]",
				'display' => 'full',
			);
			$carriers = $webService->get($query);
			
			return $carriers->carriers[0]->name;
		}
		catch (PrestaShopWebserviceException $ex) {
			//сообщение об ошибке
			return "";
		}
	}
	
	/**
		Поиск способа оплаты
	**/
	function payment_method_order ($id_order) {
		$webService = new PrestaShopWebservice('https://artliner.by/api/prestashop-webservice/', '8XWV2Q4VTVS5ZAVH43DNRNJG8528WPGQ', false);
		
		try {
			if(empty($id_order))
				throw new PrestaShopWebserviceException("");
			$query = array(
				'resource' => 'order_payments',
				'filter[id_order]' => "[$id_order]",
				'display' => 'full',
			);
			$order_payments = $webService->get($query);
			return $order_payments->order_payments[0]->payment_method;
		}
		catch (PrestaShopWebserviceException $ex) {
			//сообщение об ошибке
			return "";
		}
	}
	
?>