<?php
	function file_force_download1($file) {
		if (file_exists($file)) {
			// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
			// если этого не сделать файл будет читаться в память полностью!
			if (ob_get_level()) {
				ob_end_clean();
			}
			// заставляем браузер показать окно сохранения файла
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename=' . basename($file));
			header('Content-Transfer-Encoding: binary');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			// читаем файл и отправляем его пользователю
			readfile($file);
			exit;
		}
	}
	
	require_once '../../../PHPWord/PHPWord.php';
	include "../../db.php";
	include "../utility.php";
	//входные данные
	$ID = $_GET['id'];
	$cost = 0;
	if(!empty($_GET['cost']))
		$cost = floatval($_GET['cost']);
	$nds = 0;
	if(!empty($_GET['nds']))
		$nds = floatval($_GET['nds']);
	
	$query = "select user_id, client_id , DATE_FORMAT(DATE_OR, '%d %M %Y') date_ from orders where number = ".$ID;
	$result = mysql_query($query) or die("Query failed1");
	if($row = mysql_fetch_array($result)){
		$users = $row['user_id'];
		$clients = $row['client_id'];
		$DATE_OR = $row['date_'];
	}

	$query = "select user_fio, user_mail from users where user_login = '".$users."';";
	$result = mysql_query($query) or die("Query failed2");
	if($row = mysql_fetch_array($result)){
		$user_fio = $row['user_fio'];
		$user_mail = $row['user_mail'];
	}

	$DATE_OR  = str_replace('January','Января',$DATE_OR);
	$DATE_OR  = str_replace('February','Февраля',$DATE_OR);
	$DATE_OR  = str_replace('March','Марта',$DATE_OR);
	$DATE_OR  = str_replace('April','Апреля',$DATE_OR);
	$DATE_OR  = str_replace('May','Мая',$DATE_OR);
	$DATE_OR  = str_replace('June','Июня',$DATE_OR);
	$DATE_OR  = str_replace('July','Июля',$DATE_OR);
	$DATE_OR  = str_replace('August','Августа',$DATE_OR);
	$DATE_OR  = str_replace('September','Сентября',$DATE_OR);
	$DATE_OR  = str_replace('October','Октября',$DATE_OR);
	$DATE_OR  = str_replace('November','Ноября',$DATE_OR);
	$DATE_OR  = str_replace('December','Декабря',$DATE_OR);

	$query = "SELECT * FROM clients c  WHERE c.ID = ".$clients;
	$result = mysql_query($query) or die("Query failed3");
	if($row = mysql_fetch_array($result)){
		$CLIENT_NAME = $row['CLIENT_NAME'];
		$ADDRESS_POST = $row['ADDRESS_POST'];
		if($row['post_post_index'] != "" && $row['post_post_city'] != "") {
			$addr = $row['post_post_index'];
			$city = $row['post_post_city'];
			$start = false;
			if($row['post_region_id'] != "") {
				$region = $row['post_region_id'];
				if(strpos($region, substr($city, 0, strlen($city) - 1)) != 0) {
					$addr .= " ".$region;
					$start = true;
				}
			}
			if($row['post_raion'] != "") {
				if($start)	
					$addr .= ", ".$row['post_raion']." р-н";
				else {
					$addr .= " ".$row['post_raion']." р-н";
					$start = true;
				}
			}
			if($start) {
				if(strpos($city, "г.") == 0)
					$addr .= ", ".$city;
				else
					$addr .= ", г.".$city;
			}
			else
				if(strpos($city, "г.") == 0)
					$addr .= " ".$city;
				else
					$addr .= " г.".$city;
			if($row['post_post_street'] != "") {
				if(strpos($row['post_post_street'], "пер.") == 0 || strpos($row['post_post_street'], "м-н") == 0 || strpos($row['post_post_street'], "пр-т") == 0)
					$addr .= ", ".$row['post_post_street'];
				else
					$addr .= ", ул.".$row['post_post_street'];
			}
			if($row['post_house_num'] != "") {
				$addr .= ", д.".$row['post_house_num'];
			}
			if($row['post_post_kor'] != "") {
				$addr .= "/".$row['post_post_kor'];
			}
			if($row['post_post_kv'] != "") {
				$addr .= ", кв.".$row['post_post_kv'];
			}
			$ADDRESS_POST = $addr;
		}
		$PHONE = "";
		if($row['PHONE_CITY'] != "")
			$PHONE = "тел.".$row['PHONE_CITY'];
		if($row['PHONE_MOB'] != "") {
			if($PHONE != "")
				$PHONE .= "; ";
			$PHONE = "моб.".$row['PHONE_MOB'];
		}
		$EMAIL = $row['EMAIL'];
		$ACCT = $row['ACCT'];
		$BANK = $row['BANK'];
		$CODE_BANK = $row['CODE_BANK'];
		$fio_dir = $row['fio_dir'];
		$osn = $row['osn'];
		$UNP = $row['UNP'];
		$fio_dir1 = $row[fio_dir1];
	}

	$str_rn = '</w:t></w:r></w:p><w:p w:rsidR="00D11242"><w:pPr><w:spacing w:after="0" w:line="240" w:lineRule="auto"/><w:jc w:val="both"/><w:rPr><w:rFonts w:ascii="Tahoma" w:eastAsia="Times New Roman" w:hAnsi="Tahoma" w:cs="Tahoma"/><w:color w:val="000000"/><w:sz w:val="16"/><w:szCs w:val="16"/><w:lang w:eastAsia="ru-RU"/></w:rPr></w:pPr><w:r><w:rPr><w:rStyle w:val="a7"/><w:rFonts w:ascii="Tahoma" w:hAnsi="Tahoma" w:cs="Tahoma"/><w:b w:val="0"/><w:color w:val="333333"/><w:sz w:val="16"/><w:szCs w:val="16"/><w:bdr w:val="none" w:sz="0" w:space="0" w:color="auto" w:frame="1"/><w:shd w:val="clear" w:color="auto" w:fill="FFFFFF"/></w:rPr><w:t>';

	if($PHONE != "")
		$ADDRESS_POST .= $str_rn.$PHONE;
	if($EMAIL != "")
		$ADDRESS_POST .= $str_rn."e-mail: ".$EMAIL;

	$cost_str = number2string($cost);
	$valuta1 = "белорусских рублей";
	if($cost > 1 && $cost < 5)
		$valuta1 = "белорусских рубля";
	if($cost == 1)
		$valuta1 = "белорусский рубль";
	$nds_str = number2string($nds);
	$valuta2 = "белорусских рублей";
	if($nds > 1 && $nds < 5)
		$valuta2 = "белорусских рубля";
	if($nds == 1)
		$valuta2 = "белорусский рубль";
	$nds_ = $nds." ( $nds_str )";
	
	$PHPWord = new PHPWord();
	$document = $PHPWord->loadTemplate('tmp_doc_client_one.docx'); //шаблон
	$document->setValue('num','_______'); //номер
	$document->setValue('date', $DATE_OR); //номер
	$document->setValue('cost',$cost); //стоимость
	$document->setValue('coststr',$cost_str); //стоимость строкой
	$document->setValue('valuta1',$valuta1); //валюта для стоимости
	$document->setValue('nds',$nds_); //ндс
	$document->setValue('valuta2',$valuta2); //валюта для ндс
	$document->setValue('firm',  $CLIENT_NAME); //номер
	$document->setValue('dir_', $fio_dir); //номер
	$document->setValue('dir', $fio_dir1); //номер
	$document->setValue('ust', $osn); //номер
	$document->setValue('mai', $user_mail); //номер
	$document->setValue('bank', $BANK); //номер
	$document->setValue('unp', $UNP); //номер
	$document->setValue('acct', $ACCT); //номер
	$document->setValue('code', $CODE_BANK); //номер
	$document->setValue('addr', $ADDRESS_POST); //номер
	

	$document->save('tmp_doc_client_one_full.docx'); //имя заполненного шаблона для сохранения

	file_force_download1('tmp_doc_client_one_full.docx');

	unlink('tmp_doc_client_full.docx');
	
?>


