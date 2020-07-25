
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

include_once '../db.php';


	$typeStamp = $_POST[typeStamp];
	$nameStamp = $_POST[nameStamp];
	$kolStamp = $_POST[kolStamp];
	$sizeStamp = $_POST[sizeStamp];
	$numStamp = $_POST[numStamp];
	$priceStamp = $_POST[priceStamp];
	
	if ($_POST['priceTest']=="on"){ 
		$priceTest = 1;
	} 
	else{
		$priceTest = 0;
	}
	$query = "INSERT INTO stamps (STAMP_TYPE, STAMP_NAME, STAMP_NUMDER, STAMP_SIZE, STAMP_KOL, STAMP_AVA, STAMP_SHOB,STAMP_PRICE,STAMP_TEST)
									VALUES('{$typeStamp}','{$nameStamp}','{$numStamp}','{$sizeStamp}','{$kolStamp}','{$link1}','{$link2}', '{$priceStamp}','{$priceTest}');";
									
							
	
	mysql_query($query) or die($query);


?>
<script >
	
	window.close();
	window.opener.location.reload();
	
</script>