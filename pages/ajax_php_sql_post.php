<?
	// flag параметр
	
	function rus2translit($string) {
		$converter = array(
		'а' => 'a',   'б' => 'b',   'в' => 'v',
		'г' => 'g',   'д' => 'd',   'е' => 'e',
		'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
		'и' => 'i',   'й' => 'y',   'к' => 'k',
		'л' => 'l',   'м' => 'm',   'н' => 'n',
		'о' => 'o',   'п' => 'p',   'р' => 'r',
		'с' => 's',   'т' => 't',   'у' => 'u',
		'ф' => 'f',   'х' => 'h',   'ц' => 'c',
		'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
		'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
		'э' => 'e',   'ю' => 'yu',  'я' => 'ya',
		
		'А' => 'A',   'Б' => 'B',   'В' => 'V',
		'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
		'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
		'И' => 'I',   'Й' => 'Y',   'К' => 'K',
		'Л' => 'L',   'М' => 'M',   'Н' => 'N',
		'О' => 'O',   'П' => 'P',   'Р' => 'R',
		'С' => 'S',   'Т' => 'T',   'У' => 'U',
		'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
		'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
		'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
		'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
		);
		return strtr($string, $converter);
	}
	function str2url($str) {
		// переводим в транслит
		$str = rus2translit($str);
		// в нижний регистр
		$str = strtolower($str);
		// заменям все ненужное нам на "-"
		$str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);
		// удаляем начальные и конечные '-'
		$str = trim($str, "-");
		return $str;
	}
	
	include 'firewall1.php';
	session_start();
	$login = $_SESSION['login'];
	$query = "select user_per from users where user_login = '".$login ."' LIMIT 1";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) { 
		$admin = $row[0];
	}
	
	$flag = $_POST['flag'];
	include 'db.php';
	switch ($flag) {
		case '4':	
		
		
		$id = $_POST['id_prod_rev'];
	$p_names = $_POST['p_names'];	
	$p_dates_time  = $_POST['p_dates_time'];
	$status = $_POST['stats'];
	
	
	$q = 'select order_id,print_diz,status,press_diz,cl_file from order_product where id = '.$id;
	$result = mysql_query($q) or die($q);
	while ($row = mysql_fetch_row($result)) {
	$name_r1 = explode("/", $row[1]);
	$name_r2 =  $row[1];
	$name_r3 =  $row[3];
	$name_r4 =  $row[4];
	$status_id =  $row[2];
	}
	if ($status != ""){
		$orderDate   = date("Y-m-d H:i:s");
		$query = "INSERT INTO log_task (id_prod, user_log, datetime, comm,prob,status_old,status_new) VALUES('{$id}','{$login}','{$orderDate}','','','{$status_id}','{$status}');";
		mysql_query($query) or die($query);
		/** Снимаем занятость задачи */
		$query = "UPDATE lock_task SET flags=0 WHERE id_prod=" . $id;
		mysql_query($query) or die($query);
	}
	if ($status == ""){
	$status = $status_id ;
	}
	//$name_r =   '\pg\' . $name_r1[0] . '\' . $name_r1[1] . '\' . $name_r1[2] . '\' . $name_r1[3] . '\' . $name_r1[4];
	if ($status == "10"){
	$name_r =   '\pg\\'.str_replace("/", "\\", $name_r4);
	}
	
	if ($status == "12"){
	$name_r =   '\pg\\'.str_replace("/", "\\", $name_r3);
	}
	if ($status == "11"){
	$name_r =   '\pg\\'.str_replace("/", "\\", $name_r2);
	}	
	
	
	
	
	
	echo dirname(__FILE__).$name_r;
	if (isset($_FILES) AND $name_r <> "")
	{
	//пролистываем весь массив изображений по одному $_FILES['file']['name'] as $k=>$v
	foreach ($_FILES['file']['name'] as $k=>$v)
	{
	
	if((!empty($_FILES["file"])) && ($_FILES['file']['error'][$k] == 0)) {
	// проверяем, что файл это изображение JPEG и его размер не больше 350кб
	$filename = basename($_FILES['file']['name'][$k]);
	
	$ext = substr($filename, strrpos($filename, '.') + 1);
	$name_s = substr(md5(microtime() . rand(0, 9999)), 0, 30).'.'.$ext;
	$name_s = str_replace(' ', '_', $filename);
	$name_s  =  str2url($filename) .'.'.$ext;;
	
	// путь для сохранения файла
	$newname = dirname(__FILE__).$name_r.'\\'.$name_s;
	
	// проверяем, файл с таким названием уже есть на сервере
	if (!file_exists($newname)) {
	// переместить загруженный файл в новое место
	if ((move_uploaded_file($_FILES['file']['tmp_name'][$k],$newname))) {
	
	echo "Прелестно, файл был загружен: ".$newname."<br>";;
	} else {
	echo "Произошла ошибка при загрузке файла!"."<br>";;
	
	}
	} else {
	echo "Ошибка: файл ".$_FILES["file"]["name"][$k]." уже существует"."<br>";;
	}
	
	} else {
	$link1 = '';	
	echo "Ошибка: файл не загружен!"."<br>";;
	}
	}
	}
	
	
	$query = "UPDATE order_product SET dates_rdy = '{$p_dates_time}',p_names = '{$p_names}', status = '{$status}'  WHERE  id = {$id};";
	mysql_query($query) or die($query);
	
	
	
	
	break;
	
	}
	
	
	
	?>	