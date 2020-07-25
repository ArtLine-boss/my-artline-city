<?php
include_once "../db.php";

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

$id = $_POST['id_prod'];
$p_size = $_POST['p_size'];
$name_prod = $_POST['p_names'];
$code_stat = $_POST['directoryCodeStat'];
//запрашиваем имя продукта
$sel = "SELECT name FROM directoryCodeStat WHERE id=".$code_stat;
$res = mysql_query($sel);
if($r = mysql_fetch_array($res)) {
	if($name_prod != "")
		$name_prod = $r['name']." ".$name_prod;
	else
		$name_prod = $r['name'];
}
else {
	echo null;
}
$unit_prod = $_POST['unit_prod1'];
$price_prod = $_POST['p_price_all'];
$total_prod = $_POST['p_cir'];
$ps_fast = $_POST['p_fast'];
$ps_dates_time = $_POST['p_dates_time'];
$str_tm = $_POST['str_tm'];
$str_tm1 = $_POST['str_tm1'];
$price_prod = str_replace(',', '.', $price_prod);
$total_prod = str_replace(',', '.', $total_prod);
$p_per = $_POST['p_per'];
$p_stor = $_POST['p_stor'];
$p_per_mat = $_POST['p_per_mat'];
$view_diz = $_POST['view_diz'];
$view_press = $_POST['view_press'];
$str_per  = $p_per . "|" . $p_stor. "|" . $p_per_mat;
$maket_sh  = $_POST['maket_sh'];
$sum_press  = $_POST['sum_press'];
$cl_file1  = $_POST['cl_file'];
$print_diz1  = $_POST['print_diz'];
$press_diz1  = $_POST['press_diz'];
$list_comm  = $_POST['list_comm'];
$p_sum_sys  = $_POST['p_sum_sys'];
$cl_file = explode("/", $cl_file1);
$print_diz = explode("/", $print_diz1);
$press_diz = explode("/", $press_diz1);
$file_yes  = $_POST['file_yes'];
$q = 'select order_id from order_product where id = '.$id;
$result = mysql_query($q) or die($q);
while ($row = mysql_fetch_row($result)) {
	$id_ore = $row[0];
}
switch ($maket_sh){
	case "1": $name_prod1 = $cl_file[3]; break;
   case "2": $name_prod1 = $print_diz[3]; break;
   case "3": $name_prod1 = $press_diz[3]; break;
}

  $name_prod2 = str_replace(' ', '_', $name_prod);
    $name_prod2 = str_replace('\\', '_', $name_prod2);
  $name_prod2 = substr($name_prod2, 0, 25);
  $name_prod2 =  str2url($name_prod2) . "_".rand(1, 99); 

  if ($name_prod1 == ""){
	  $name_prod1 = $name_prod2;
  }
	$name = $id_ore ;

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
switch ($maket_sh){
	case "1": $name_r = $name_r1;break;
   case "2": $name_r = $name_r2;break;
   case "3": $name_r = $name_r3; break;
}
	$name_di = $name_dir2; 
		if($file_yes == "0"){
		$name_di = $print_diz1 ;
		}	
//	echo $file_yes  . " " .$name_di . " " . $print_diz1. " " .$name_dir2; 
	if($maket_sh != "4"){
		

if (isset($_FILES))
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
	}
$query = "select val from settings s where  s.id = 4";
					$result = mysql_query($query) or die("Query failed1");
					WHILE ($row = mysql_fetch_row($result)) { 
						$nds = $row[0];
					}
					IF ($nds ==''){
						$nds = 0;
					} ELSE {
						$nds = str_replace(',', '.', $nds);
					}
$nds_ = 1 + $nds/100;					
//$price_prod = ROUND($price_prod,2) ;
$summ = ROUND(($price_prod * $total_prod),2) ;
//$price_prod  = ROUND(($summ / $total_prod ),3);
$summ = ($price_prod * $total_prod);
// if($newname != "" ){
        $query = "UPDATE order_product SET TOTAL = '{$total_prod}', PRICE = '{$price_prod}', p_names = '{$name_prod}', summ = '{$summ}', units = '{$unit_prod}',
				fast = '{$ps_fast}',	dates_rdy = '{$ps_dates_time}', TEMP_PR = '{$str_tm}', SIZE = '{$p_size}', cshivka = '{$str_per}',	template = '{$str_tm1}',
				view_press = '{$view_press}', view_diz = '{$view_diz}', cl_file = '{$name_dir1}', print_diz = '{$name_di}',press_diz = '{$name_dir3}', flags = '{$maket_sh}',sum_press = '{$sum_press}', comment = '{$list_comm}', price_sys ='{$p_sum_sys}', code_stat='{$code_stat}' WHERE ID ='{$id}';";

// } else {
	 // $query = "UPDATE order_product SET TOTAL = '{$total_prod}', PRICE = '{$price_prod}', p_names = '{$name_prod}', summ = '{$summ}', units = '{$unit_prod}',
		 			// fast = '{$ps_fast}', dates_rdy = '{$ps_dates_time}', TEMP_PR = '{$str_tm}', SIZE = '{$p_size}', cshivka = '{$str_per}', template = '{$str_tm1}',
				// view_press = '{$view_press}', view_diz = '{$view_diz}', flags = '{$maket_sh}' WHERE ID ='{$id}';";
// }

include_once "../db.php";


mysql_query($query) or die(query);
mysql_close($connection);

?>
 

	
