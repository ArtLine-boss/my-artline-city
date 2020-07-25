<?php


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

function copy_files($source, $res){ 
    $hendle = opendir($source); // открываем директорию 
    while ($file = readdir($hendle)) { 
        if (($file!=".")&&($file!="..")) { 
            if (is_dir($source."/".$file) == true) { 
                if(is_dir($res."/".$file)!=true) // существует ли папка 
                    mkdir($res."/".$file, 0777); // создаю папку 
                    copy_files ($source."/".$file, $res."/".$file); 
            } 
            else{ 
                if(!copy($source."/".$file, $res."/".$file)) {  
                    print ("при копировании файла $file произошла ошибка...<br>\n");  
                }// end if copy 
            }  
        } // else $file == .. 
    } // end while 
    closedir($hendle); 
}


	include "../db.php";
	$array_id = $_GET['array_id'];
	$orderUsers = $_GET['login'];
	$query="select DISTINCT client_id from orders o, (select ORDER_ID from order_product where FIND_IN_SET(id, '".$array_id."')) o2 where o.NUMBER = o2.order_id LIMIT 1";
	$result = mysql_query($query) or die($query);
	if ($row = mysql_fetch_row($result)){ 
		$orderClient = $row[0];
	}
	$orderDate   = date("Y-m-d H:i:s");
	$orderStatus = 0;
	$val_ = 933;
	$query = "INSERT INTO orders (DATE_OR, USER_ID, STATUS_ID, CLIENT_ID,CUR_ID) 
					VALUES(	'{$orderDate}','{$orderUsers}',{$orderStatus},{$orderClient},'{$val_}');";
	mysql_query($query) or die($query);
	$insert_ID_ord = mysql_insert_id();
	$kol = 0;
	$query="select ORDER_ID, PRODUCT_ID, TOTAL, PRICE, SUMM, TEMPLATE, TEMP_PR, SIZE, DIZ, SHABLON_CL, p_names, PR, press_diz, print_diz, sum_press, view_diz, view_press, flags, cshivka, cl_file, units, dates_rdy, fast from order_product where FIND_IN_SET(id, '".$array_id."')";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)){ 
	$kol++;
	
	 $name_prod1 = str_replace(' ', '_', $row[10]);
      $name_prod1 = str_replace('\\', '_', $name_prod1);
  $name_prod1 = substr($name_prod1, 0, 25);
  $name_prod1  =  str2url($name_prod1) . "_".rand(1, 99); 


	$name = $insert_ID_ord ;

	$name_dir = "files/prod/$name";
	if (is_dir($name_dir) == FALSE){
		mkdir($name_dir,0777 );
	}
	$name = $name.'/'.$name_prod1;
	$name_dir = "files/prod/$name/";
		if (is_dir($name_dir) == FALSE){
		mkdir($name_dir,0777 );
	}
	$name_dir1 = "files/prod/$name/client";
	$name_r1 = "\\files\prod\\".$name."\\client";
	if (is_dir($name_dir1) == FALSE){
		mkdir($name_dir1,0777 );
	}	
	$name_dir2 = "files/prod/$name/diz";
	$name_r2 = "\\files\prod\\".$name."\\diz";
		if (is_dir($name_dir2) == FALSE){
		mkdir($name_dir2,0777 );
	}
	$name_dir3 = "files/prod/$name/press";
	$name_r3 = "\\files\prod\\".$name."\\press";
		if (is_dir($name_dir3) == FALSE){
		mkdir($name_dir3,0777 );
	}
	
  copy_files($row[12],$name_dir2);
	 copy_files($row[13],$name_dir3);
	$query1 = "INSERT INTO order_product (ORDER_ID, PRODUCT_ID, TOTAL, PRICE, SUMM, TEMPLATE, TEMP_PR, SIZE, DIZ, SHABLON_CL, p_names, PR, press_diz, print_diz, sum_press, view_diz, view_press, flags, cshivka, cl_file, units, num_prod_ord) VALUES(	'{$insert_ID_ord}','{$row[1]}','{$row[2]}','{$row[3]}','{$row[4]}','{$row[5]}','{$row[6]}','{$row[7]}','{$row[8]}','{$row[9]}','{$row[10]}','{$row[11]}','{$name_dir3}','{$name_dir2}','','{$row[15]}','{$row[16]}','{$row[17]}','{$row[18]}','{$name_dir1}','{$row[20]}','{$kol}');";
	mysql_query($query1) or die($query1);
	}
	mysql_close($connection);
	echo $insert_ID_ord;

	
	
	
	
?>