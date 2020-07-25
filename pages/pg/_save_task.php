<?



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


include '../firewall.php';
$id = $_POST['id_savetask'];
$stats = $_POST['stats'];
$stats1 = $_POST['stats1'];
$login = $_SESSION['login'];
$list_comm = $_POST['list_comm'];

include_once '../db.php';

$query="select status,print_diz,cl_file, ORDER_ID,p_names from order_product where id =".$id ;
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) 
{ 
	$status = $row[0];
	$print_diz = $row[1];
	$cl_file = $row[2]; 
   $id_ore = $row[3];
	$name_prod = $row[4];
}

if ($cl_file != ""){
	$list = explode("/", $cl_file);
	
} else {
	$list = explode("/", $print_diz);
}


if ($status == '10') {
	$d_li = 'diz';
} else  {
	$d_li = 'press';
}

IF ($cl_file == "" AND $print_diz == ""){
	  $name_prod2 = str_replace(' ', '_', $name_prod);
  $name_prod2  =  str2url($name_prod2) . "_".rand(1, 99); 
  
	$name = $id_ore ;
	$list3 = "files/prod/$name";
	// echo $list3 ;
	if (is_dir($list3) == FALSE){
		mkdir($list3,0777 );
	}
	$name = $name.'/'.$name_prod2;
	$list3 = "files/prod/$name/";
		if (is_dir($list3) == FALSE){
		mkdir($list3,0777 );
	}
	$list = explode("/", $list3);
}
$new_link =  $list[0]."/".$list[1]."/".$list[2]."/".$list[3]."/".$d_li;
$new_link1 =  "\\".$list[0]."\\".$list[1]."\\".$list[2]."\\".$list[3]."\\".$d_li;

if (is_dir($new_link) == FALSE){
	mkdir($new_link,0777 );
}
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
      $newname = dirname(__FILE__).$new_link1.'\\'.$name_s;
	 
      // проверяем, файл с таким названием уже есть на сервере
      if (!file_exists($newname)) {
        // переместить загруженный файл в новое место
        if ((move_uploaded_file($_FILES['file']['tmp_name'][$k],$newname))) {
			
           // echo "Прелестно, файл был загружен: ".$newname."<br>";;
        } else {
           // echo "Произошла ошибка при загрузке файла!"."<br>";;
		   
        }
      } else {
         // echo "Ошибка: файл ".$_FILES["file"]["name"][$k]." уже существует"."<br>";;
      }
 
	} else {
	$link1 = '';	
	 // echo "Ошибка: файл не загружен!"."<br>";;
	}
  }
}

if ($status == '10') {
   $query = "UPDATE order_product SET print_diz='{$new_link}', status = '".$stats."' WHERE  id=".$id  ;	
	 } else if ($status == '11') {
  $query = "UPDATE order_product SET press_diz='{$new_link}', status = '".$stats."' WHERE  id=".$id  ;	
 } else if ($status == '12') {
	$query = "UPDATE order_product SET press_diz='{$new_link}', status = '".$stats."' WHERE  id=".$id  ;	
 } else $query = "UPDATE order_product SET  status = '".$stats."' WHERE  id=".$id  ;	


mysql_query($query) or die($query);

$orderDate   = date("Y-m-d H:i:s");

$query = "INSERT INTO log_task (id_prod, user_log, datetime, comm,prob,status_old,status_new) VALUES('{$id}','{$login}','{$orderDate}','{$list_comm}','{$stats1}','{$status}','{$stats}');";
					mysql_query($query) or die($query);




$query = "UPDATE lock_task SET flags='0' WHERE  id_prod=".$id."  AND oper = ". $status  ;	
mysql_query($query) or die($query);
mysql_close($connection);

?>