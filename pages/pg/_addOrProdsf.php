<?php
include '../firewall.php';
if (isset($_FILES))
{
	$name = substr(md5(microtime() . rand(0, 9999)), 0, 32);
	$link1 = $name;
	$name_dir = "files/prod/$name";
	$name_r = "\\files\prod\\".$name;
	mkdir($name_dir,0777 );

  //пролистываем весь массив изображений по одному $_FILES['file']['name'] as $k=>$v
  foreach ($_FILES['file']['name'] as $k=>$v)
  {
	 
	if((!empty($_FILES["file"])) && ($_FILES['file']['error'][$k] == 0)) {
  // проверяем, что файл это изображение JPEG и его размер не больше 350кб
    $filename = basename($_FILES['file']['name'][$k]);
	$ext = substr($filename, strrpos($filename, '.') + 1);
	$name_s = substr(md5(microtime() . rand(0, 9999)), 0, 30).'.'.$ext;
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

if (isset($_FILES))
{
	$name = substr(md5(microtime() . rand(0, 9999)), 0, 32);
	$link2 = $name;
	$name_dir = "files/prod/$name";
	$name_r = "\\files\prod\\".$name;
	mkdir($name_dir,0777 );

  //пролистываем весь массив изображений по одному $_FILES['file']['name'] as $k=>$v
  foreach ($_FILES['file1']['name'] as $k=>$v)
  {
	 
	if((!empty($_FILES["file1"])) && ($_FILES['file1']['error'][$k] == 0)) {
  // проверяем, что файл это изображение JPEG и его размер не больше 350кб
    $filename = basename($_FILES['file1']['name'][$k]);
	$ext = substr($filename, strrpos($filename, '.') + 1);
	$name_s = substr(md5(microtime() . rand(0, 9999)), 0, 30).'.'.$ext;
    // путь для сохранения файла
      $newname = dirname(__FILE__).$name_r.'\\'.$name_s;
	 
      // проверяем, файл с таким названием уже есть на сервере
      if (!file_exists($newname)) {
        // переместить загруженный файл в новое место
        if ((move_uploaded_file($_FILES['file1']['tmp_name'][$k],$newname))) {
			
           echo "Прелестно, файл был загружен: ".$newname."<br>";;
        } else {
           echo "Произошла ошибка при загрузке файла!"."<br>";;
		   
        }
      } else {
         echo "Ошибка: файл ".$_FILES["file1"]["name"][$k]." уже существует"."<br>";;
      }
 
	} else {
	$link2 = '';	
	 echo "Ошибка: файл не загружен!"."<br>";;
	}
  }
}


if (isset($_FILES))
{
	$name = substr(md5(microtime() . rand(0, 9999)), 0, 32);
	$link3 = $name;
	$name_dir = "files/prod/$name";
	$name_r = "\\files\prod\\".$name;
	mkdir($name_dir,0777 );

  //пролистываем весь массив изображений по одному $_FILES['file']['name'] as $k=>$v
  foreach ($_FILES['file2']['name'] as $k=>$v)
  {
	 
	if((!empty($_FILES["file2"])) && ($_FILES['file2']['error'][$k] == 0)) {
  // проверяем, что файл это изображение JPEG и его размер не больше 350кб
    $filename = basename($_FILES['file2']['name'][$k]);
	$ext = substr($filename, strrpos($filename, '.') + 1);
	$name_s = substr(md5(microtime() . rand(0, 9999)), 0, 30).'.'.$ext;
    // путь для сохранения файла
      $newname = dirname(__FILE__).$name_r.'\\'.$name_s;
	 
      // проверяем, файл с таким названием уже есть на сервере
      if (!file_exists($newname)) {
        // переместить загруженный файл в новое место
        if ((move_uploaded_file($_FILES['file2']['tmp_name'][$k],$newname))) {
			
           echo "Прелестно, файл был загружен: ".$newname."<br>";;
        } else {
           echo "Произошла ошибка при загрузке файла!"."<br>";;
		   
        }
      } else {
         echo "Ошибка: файл ".$_FILES["file2"]["name"][$k]." уже существует"."<br>";;
      }
 
	} else {
	$link3 = '';	
	 echo "Ошибка: файл не загружен!"."<br>";;
	}
  }
}

$dizz_sum = $_POST['dizz_sum'];
$press_sum = $_POST['press_sum'];
$size = $_POST['psize'];
$price = $_POST['price1'];
$sum = $_POST['price_1'];
$orderProd = $_POST['orderProd'];
$orderAcct = $_POST['orderAcct'];
$kol = $_POST['kol1'];
$Template = $_POST['Template'];
$TempPR = $_POST['Template1'];
$p_names = $_POST['p_names'];
$view_diz = $_POST['view_diz'];
$view_press = $_POST['view_press'];
$selSh = $_POST['selSh'];
$query = "INSERT INTO order_product (ORDER_ID, PRODUCT_ID, TOTAL, PRICE, SUMM,TEMPLATE,TEMP_PR,SIZE,DIZ,SHABLON_CL,p_names,PR,sum_press,press_diz,view_press,view_diz	,cshivka, cl_file) 
				VALUES(	{$orderAcct},{$orderProd},'{$kol}','{$price}','{$sum}','{$Template}','{$TempPR}','{$size}','{$dizz_sum}','{$link1}','{$p_names}','1', '{$press_sum}','{$link2}','{$view_press}','{$view_diz}', '{$selSh}','{$link3}');";
include_once "../db.php";
print $query;
mysql_query($query) or die(query);
mysql_close($connection);

?>
<script >
/*	var id  = '<?php echo $orderAcct;?>';
	window.close();
    window.opener.location.href = '_addAcct.php?id='+id;*/
</script>
 

	
