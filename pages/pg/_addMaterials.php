<?php
include '../firewall.php';
?>
<?php



$type = $_POST[type];


	if((!empty($_FILES["file"])) && ($_FILES['file']['error'] == 0)) {
  // проверяем, что файл это изображение JPEG и его размер не больше 350кб
  $filename = basename($_FILES['file']['name']);
  $ext = substr($filename, strrpos($filename, '.') + 1);
  $name = substr(md5(microtime() . rand(0, 9999)), 0, 20).'.'.$ext;
  if (($ext == "jpg") && ($_FILES["file"]["type"] == "image/jpeg") && 
    ($_FILES["file"]["size"] < 350000000)) {
    // путь для сохранения файла
      $newname = dirname(__FILE__).'\image\mat\\'.$name;
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


include_once '../db.php';

if ($type == 'n')
{
	$typeMaterial = $_POST[typeMaterial];
	$nameMaterial = $_POST[nameMaterial];
	$izmMaterial = $_POST[izmMaterial];
	$sizeMaterial = $_POST[sizeMaterial];
	$priceMaterial = $_POST[priceMaterial];
	$tolMaterial = $_POST[tolMaterial];
		if ($tolMaterial == ""){
		$tolMaterial = "-";
	}
	$priceMaterial = str_replace(',', '.', $priceMaterial);
	$query = "INSERT INTO material (MT_TYPE) VALUES('{$typeMaterial}' );";
	mysql_query($query) or die("Query failed");
	$idMaterial = mysql_insert_id();
	$query = "INSERT INTO material_attr (ID_M, M_NAME, M_PRICE, M_SIZE, M_UNIT,M_TOL,M_AVA)
									VALUES({$idMaterial},'{$nameMaterial}', '{$priceMaterial}','{$sizeMaterial}','{$izmMaterial}','{$tolMaterial}','{$link1}');";
	mysql_query($query) or die("Query failed");
}
if ($type == 's')
{ 
	$idMaterial = $_POST[addMaterial];
	$nameMaterial = $_POST[nameMaterial];
	$izmMaterial = $_POST[izmMaterial];
	$sizeMaterial = $_POST[sizeMaterial];
	$priceMaterial = $_POST[priceMaterial];
	$tolMaterial = $_POST[tolMaterial];
	if ($tolMaterial == ""){
		$tolMaterial = "-";
	}
	$query = "INSERT INTO material_attr (ID_M, M_NAME, M_PRICE, M_SIZE, M_UNIT,M_TOL,M_AVA)
									VALUES({$idMaterial},'{$nameMaterial}', '{$priceMaterial}','{$sizeMaterial}','{$izmMaterial}','{$tolMaterial}','{$link1}');";
	mysql_query($query) or die("Query failed");
}

?>
<script >
	window.close();
	window.opener.location.reload();
</script>