<?php
include '../firewall.php';
?>
<?php 
$link1 = '';

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
	$link1 = '';
 echo "Ошибка: файл не загружен!";
}


$typeMaterial = $_POST[typeMaterial];
$nameMaterial = $_POST[nameMaterial];
$izmMaterial = $_POST[izmMaterial];
$sizeMaterial = $_POST[sizeMaterial];
$priceMaterial = $_POST[priceMaterial];
$tolMaterial = $_POST[tolMaterial];
$idMaterial = $_POST[idMaterial];
	if ($tolMaterial == ""){
		$tolMaterial = "-";
	}
	$priceMaterial = str_replace(',', '.', $priceMaterial);
		include_once'../db.php';		
		
		
		if ($link1!=''){
			$query = "	UPDATE material_attr SET 
				ID_M = '{$typeMaterial}',
				M_NAME = '{$nameMaterial}',
				M_PRICE = '{$priceMaterial}',
				M_SIZE = '{$sizeMaterial}',
				M_UNIT= '{$izmMaterial}',
				M_TOL= '{$tolMaterial}',
				M_AVA = '{$link1}'
				WHERE ID ='{$idMaterial}';";
		} else {
			$query = "	UPDATE material_attr SET 
				ID_M = '{$typeMaterial}',
				M_NAME = '{$nameMaterial}',
				M_PRICE = '{$priceMaterial}',
				M_SIZE = '{$sizeMaterial}',
				M_UNIT= '{$izmMaterial}',
				M_TOL= '{$tolMaterial}'
				WHERE ID ='{$idMaterial}';";
		}
		
		
		mysql_query($query) or die("Query failed");
?>

<script >
	window.close();
	window.opener.location.href = '../material.php';
</script>
