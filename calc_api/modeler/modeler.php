<?
	include "../../pages/db.php";
	include "../../pages/pg/utility.php";
	
	//добавление объекта
	if( isset( $_GET['forms'] ) ) {
		//ответственный
		$USERID = 14;
		//получаем имя формы
		$id_forms = intval($_GET['forms']);
		$select = "SELECT name FROM api_forms WHERE id=1";
		$query = mysql_query($select) or die(null);
		if($row = mysql_fetch_array($query)) {
			$title_task = $row['name'];
		}
		else {
			die(null);
		}
		
		if(!isset($_POST['last_name_client']) || !isset($_POST['name_client']) || !isset($_POST['phone_client']) || !isset($_POST['email_client']))
			die(null);
		
		//параметры запроса для битрикс24
		$NAME = $_POST['name_client'];
		$LAST_NAME = $_POST['last_name_client'];
		$PHONE_WORK = $_POST['phone_client'];
		$EMAIL_WORK = $_POST['email_client'];
		$COMMENT = "";
		if(!empty($_POST['comment_client'])) {
			$COMMENT = $_POST['comment_client'];
		}
		
		$post_data = $_POST;
		//ставим устройство по умолчанию
		if(!empty($post_data['kol'])) {
			$kol = intval($post_data['kol']);
			for($i = 1; $i <=$kol; $i++) {
				$post_data['p_eq_'.$i] = 1;
			}
		}
		//если есть поле количества
		$total = 0;
		if(!empty($post_data['p_cir'])) {
			$total = $post_data['p_cir'];
		}
		//если есть переплет
		if(!empty($post_data['p_stor_i'])) {
			$stor = 0;
			if(!empty($post_data['p_size'])) {
				$size = $post_data['p_size'];
				$ar_size = explode("*", $size);
				if(count($ar_size) == 2 && !empty($ar_size[0]) && !empty($ar_size[1])) {
					if($ar_size[0] >= $ar_size[1]) {
						$stor = 2;
					}
					else {
						$stor = 1;
					}
				}
			}
			$post_data['p_stor_i'] = $stor;
		}
		$post_data['p_names'] = $title_task;
		$post_data = json_encode($post_data, JSON_UNESCAPED_UNICODE);
		
		/********************* ОТПРАВКА В БИТРИКС24 *********************************/
		//ищем клиента
		$queryUrl = 'https://artlinecity.bitrix24.ru/rest/1/c27x1afygwvhpvij/crm.contact.list.json';
		$queryData = http_build_query(array(
			'select' => array('ID'),
			'filter' => array(
				"NAME" => $NAME,
				"LAST_NAME" => $LAST_NAME,
				"PHONE_WORK" => $PHONE_WORK,
				"EMAIL_WORK" => $EMAIL_WORK,
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
					'NAME' => $NAME,
					'LAST_NAME' => $LAST_NAME,
					'PHONE' => array(array("VALUE" => $PHONE_WORK, "VALUE_TYPE" => "WORK" )),
					'EMAIL' => array(array("VALUE" => $EMAIL_WORK, "VALUE_TYPE" => "WORK" )),
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
		
		//создаем лид
		$queryUrl = 'https://artlinecity.bitrix24.ru/rest/1/c27x1afygwvhpvij/crm.lead.add.json';
		$send = array(
			'fields' => array(
				"TITLE" => $title_task,
				'CONTACT_ID' => $CONTACT_ID,
				"STATUS_ID" => "NEW",
				"OPENED" => "Y",
				"ASSIGNED_BY_ID" => $USERID,
				"COMMENTS" => $COMMENT,
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
		$json = json_decode($result);
		if(!empty($json->result) && is_int($json->result)) {
			
			/****************************ПИШЕМ В РАСЧЕТ************************************/
			//текущая дата
			$dates = date('Y-m-d H:i:s');
			$ins = "INSERT INTO bitrix24_template_calculation(user_id,date_add,name,client_id,data,total) VALUES($USERID,'$dates','$title_task',$CONTACT_ID,'$post_data',$total)";
			$query = mysql_query($ins) or die(null);
			if($query) {
				die(true);
			}
			else {
				die(null);
			}
		}
		else {
			die(null);
		}
	}
?>