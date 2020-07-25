<?php
include '../firewall.php';
?>
<?php 


if((!empty($_FILES["file"])) && ($_FILES['file']['error'] == 0)) {
  // проверяем, что файл это изображение JPEG и его размер не больше 350кб
  $filename = basename($_FILES['file']['name']);
  $ext = substr($filename, strrpos($filename, '.') + 1);
  $name = substr(md5(microtime() . rand(0, 9999)), 0, 20).'.'.$ext;
  if (($ext == "jpg") && ($_FILES["file"]["type"] == "image/jpeg") && 
    ($_FILES["file"]["size"] < 350000000)) {
    // путь для сохранения файла
      $newname = dirname(__FILE__).'\image\stamp\\'.$name;
	  $link1 = $name;
      // проверяем, файл с таким названием уже есть на сервере
      if (!file_exists($newname)) {
        // переместить загруженный файл в новое место
        if ((move_uploaded_file($_FILES['file']['tmp_name'],$newname))) {
           echo "Прелестно, файл был загружен: ".$newname;
        } else {
           echo "Произошла ошибка при загрузке файла!";
        }
      } else {
         echo "Ошибка: файл ".$_FILES["file"]["name"]." уже существует";
      }
  } else {
     echo "Ошибка при загрузке файла, изображение не .jpg или больше чем 350кб.";
  }
} else {
 echo "Ошибка: файл не загружен!";
}

if((!empty($_FILES["file1"])) && ($_FILES['file1']['error'] == 0)) {
  // проверяем, что файл это изображение JPEG и его размер не больше 350кб
    $filename = basename($_FILES['file1']['name']);
	$ext = substr($filename, strrpos($filename, '.') + 1);
	$name = substr(md5(microtime() . rand(0, 9999)), 0, 20).'.'.$ext;
  if  ($_FILES["file1"]["size"] < 350000000) {
    // путь для сохранения файла
      $newname = dirname(__FILE__).'\image\stamp\\'.$name;
	  $link2 = $name;
      // проверяем, файл с таким названием уже есть на сервере
      if (!file_exists($newname)) {
        // переместить загруженный файл в новое место
        if ((move_uploaded_file($_FILES['file1']['tmp_name'],$newname))) {
			
           echo "Прелестно, файл был загружен: ".$newname;
        } else {
           echo "Произошла ошибка при загрузке файла!";
		   
        }
      } else {
         echo "Ошибка: файл ".$_FILES["file1"]["name"]." уже существует";
      }
  } else {
     echo "Ошибка при загрузке файла, изображение не .jpg или больше чем 350кб.";
  }
} else {
 echo "Ошибка: файл не загружен!";
}

$typeStamp = $_POST[typeStamp];
$nameStamp = $_POST[nameStamp];
$sizeStamp = $_POST[sizeStamp];
$kolStamp = $_POST[kolStamp];
$numStamp = $_POST[numStamp];
$idStamp = $_POST[idStamp];
$priceStamp = $_POST[priceStamp];
	if ($_POST['priceTest']=="on"){ 
		$priceTest = 1;
	} 
	else{
		$priceTest = 0;
	}
		include_once'../db.php';		
		if ($link1=='' AND $link2 ==''){
		$query = "	UPDATE stamps SET 
				STAMP_TYPE = '{$typeStamp}',
				STAMP_NAME = '{$nameStamp}',
				STAMP_NUMDER = '{$numStamp}',
				STAMP_SIZE = '{$sizeStamp}',
				STAMP_KOL = '{$kolStamp}',
		      STAMP_PRICE = '{$priceStamp}',
				STAMP_TEST =  '{$priceTest}'
				WHERE ID ={$idStamp};";
		}
		if ($link1!='' AND $link2==''){
		$query = "	UPDATE stamps SET 
				STAMP_TYPE = '{$typeStamp}',
				STAMP_NAME = '{$nameStamp}',
				STAMP_NUMDER = '{$numStamp}',
				STAMP_SIZE = '{$sizeStamp}',
				STAMP_KOL = '{$kolStamp}',
				STAMP_AVA = '{$link1}',
		      STAMP_PRICE = '{$priceStamp}',
				STAMP_TEST =  '{$priceTest}'
				WHERE ID ={$idStamp};";
		}
		if ($link1=='' AND $link2!=''){
		$query = "	UPDATE stamps SET 
				STAMP_TYPE = '{$typeStamp}',
				STAMP_NAME = '{$nameStamp}',
				STAMP_NUMDER = '{$numStamp}',
				STAMP_SIZE = '{$sizeStamp}',
				STAMP_KOL = '{$kolStamp}',
				STAMP_SHOB = '{$link2}',
		      STAMP_PRICE = '{$priceStamp}',
				STAMP_TEST =  '{$priceTest}'
				WHERE ID ={$idStamp};";
		}
		if ($link1!='' AND $link2!=''){
		$query = "	UPDATE stamps SET 
				STAMP_TYPE = '{$typeStamp}',
				STAMP_NAME = '{$nameStamp}',
				STAMP_NUMDER = '{$numStamp}',
				STAMP_SIZE = '{$sizeStamp}',
				STAMP_KOL = '{$kolStamp}',
				STAMP_AVA = '{$link1}',
				STAMP_SHOB = '{$link2}',
		      STAMP_PRICE = '{$priceStamp}',
				STAMP_TEST =  '{$priceTest}'
				WHERE ID ={$idStamp};";
		}
		
		mysql_query($query) or die($query);
?>

<script >
	window.close();
	window.opener.location.href = '../stamps.php';
</script>
