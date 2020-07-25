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

$id = $_POST['orderAcct'];
$p_size = $_POST['p_size'];
$name_prod = $_POST['p_names'];
$code_stat = $_POST['directoryCodeStat'];
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
$str_per  = $p_per . "|" . $p_stor . "|" . $p_per_mat;
$maket_sh  = $_POST['maket_sh'];
$num_peod  = $_POST['num_peod'];
$sum_press  = $_POST['sum_press'];
$list_comm  = $_POST['list_comm'];
$p_sum_sys  = $_POST['p_sum_sys'];

$p_sum_sys  = $_POST['p_sum_sys'];

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

  $name_prod1 = str_replace(' ', '_', $name_prod);
      $name_prod1 = str_replace('\\', '_', $name_prod1);
  $name_prod1 = substr($name_prod1, 0, 25);
  $name_prod1  =  str2url($name_prod1) . "_".rand(1, 99); 


	$name = $id ;

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
	IF($maket_sh != "4"){
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
					$result = mysql_query($query) or die("12");
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
	$orderDate   = date("Y-m-d H:i:s");
switch ($maket_sh){
	case "1":
        $query = "INSERT INTO order_product (ORDER_ID, TOTAL, PRICE,p_names,summ,units,fast,dates_rdy,TEMP_PR,SIZE,cshivka,template,view_press,view_diz,cl_file,print_diz,press_diz, flags,num_prod_ord,sum_press,comment,price_sys,add_date,code_stat) 
				VALUES(	{$id},{$total_prod},'{$price_prod}','{$name_prod}','{$summ}','{$unit_prod}','{$ps_fast}','{$ps_dates_time}','{$str_tm}','{$p_size}','{$str_per}','{$str_tm1}','{$view_press}','{$view_diz}','{$name_dir1}','{$name_dir2}','{$name_dir3}','{$maket_sh}','{$num_peod}','{$sum_press}','{$list_comm}','{$p_sum_sys}','{$orderDate}','{$code_stat}');";
        break;
    case "2":
        $query = "INSERT INTO order_product (ORDER_ID, TOTAL, PRICE,p_names,summ,units,fast,dates_rdy,TEMP_PR,SIZE,cshivka,template,view_press,view_diz,cl_file,print_diz,press_diz, flags,num_prod_ord,sum_press,comment,price_sys,add_date,code_stat) 
				VALUES(	{$id},{$total_prod},'{$price_prod}','{$name_prod}','{$summ}','{$unit_prod}','{$ps_fast}','{$ps_dates_time}','{$str_tm}','{$p_size}','{$str_per}','{$str_tm1}','{$view_press}','{$view_diz}','{$name_dir1}','{$name_dir2}','{$name_dir3}','{$maket_sh}','{$num_peod}','{$sum_press}','{$list_comm}','{$p_sum_sys}','{$orderDate}','{$code_stat}');";
        break;
    case "3":
      $query = "INSERT INTO order_product (ORDER_ID, TOTAL, PRICE,p_names,summ,units,fast,dates_rdy,TEMP_PR,SIZE,cshivka,template,view_press,view_diz,cl_file,print_diz,press_diz, flags,num_prod_ord,sum_press,comment,price_sys,add_date,code_stat) 
				VALUES(	{$id},{$total_prod},'{$price_prod}','{$name_prod}','{$summ}','{$unit_prod}','{$ps_fast}','{$ps_dates_time}','{$str_tm}','{$p_size}','{$str_per}','{$str_tm1}','{$view_press}','{$view_diz}','{$name_dir1}','{$name_dir2}','{$name_dir3}','{$maket_sh}','{$num_peod}','{$sum_press}','{$list_comm}','{$p_sum_sys}','{$orderDate}','{$code_stat}');";
        break;
		 default:  $query = "INSERT INTO order_product (ORDER_ID, TOTAL, PRICE,p_names,summ,units,fast,dates_rdy,TEMP_PR,SIZE,cshivka,template,view_press,view_diz, flags,num_prod_ord,cl_file,print_diz,press_diz,sum_press,comment,price_sys,add_date,code_stat) 
				VALUES(	{$id},{$total_prod},'{$price_prod}','{$name_prod}','{$summ}','{$unit_prod}','{$ps_fast}','{$ps_dates_time}','{$str_tm}','{$p_size}','{$str_per}','{$str_tm1}','{$view_press}','{$view_diz}','{$maket_sh}','{$num_peod}','{$name_dir1}','{$name_dir2}','{$name_dir3}','{$sum_press}','{$list_comm}','{$p_sum_sys}','{$orderDate}','{$code_stat}');";
}





mysql_query($query) or die($query);
mysql_close($connection);

?>
 

	
