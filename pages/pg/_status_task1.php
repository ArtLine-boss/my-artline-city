<?php
include '../firewall.php';


$id = $_POST['ID_ORDER'] ;
$kol = $_POST['kol'];
$brak =$_POST['brak'];
$comment = $_POST['comment'];
$date_pos   =  date("Y-m-d H:i:s");


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
			
           /*echo "Прелестно, файл был загружен: ".$newname."<br>";;*/
        } else {
          /* echo "Произошла ошибка при загрузке файла!"."<br>";;*/
		   
        }
      } else {
         /*echo "Ошибка: файл ".$_FILES["file"]["name"][$k]." уже существует"."<br>";;*/
      }
 
	} else {
	$link1 = '';	
	/* echo "Ошибка: файл не загружен!"."<br>";;*/
	}
  }
}



include "../db.php";

$query= " UPDATE order_per SET status ='3', date_end='".$date_pos."', kol_F = ".$kol." , brak = ".$brak." , comment = '".$comment."' WHERE ID=".$id;
mysql_query($query) or die("Query failed1"); 

$query= " select o.num_oper,Numder_order from order_per o where o.id = ".$id;
$result = mysql_query($query) or die("Query failed2");
	if($row = mysql_fetch_row($result)){
		$fh =  $row[0];
		$id_orede =  $row[1];
	}
	
IF ($fh == '998'){

	$query= " select o1.id from order_per o1,(select o.id, o.Numder_order, o.num_prod, o.acct_or, o.num_oper from order_per o where o.id = ".$id.") o
where o1.Numder_order = o.Numder_order and o1.num_prod = o.num_prod and o1.acct_or = o.acct_or and  o1.num_oper = 999 ";

$result = mysql_query($query) or die("Query failed3");
	if($row = mysql_fetch_row($result)){
			$id_next =  $row[0];
		}
	if ($id_next != '' OR $id_next != 0){
		
	IF ($kol != 0){
		$query= "UPDATE order_per SET date_pos ='".$date_pos."', status=1, kol_F = ".$kol.", file = '".$link1."' WHERE  ID=".$id_next;
	mysql_query($query) or die("Query failed5");
	$query= "UPDATE order_product SET press_diz = '".$link1."' WHERE  ORDER_ID=".$id_orede;
	mysql_query($query) or die("Query failed6");
	}ELSE {
		$query= "UPDATE order_per SET date_pos ='".$date_pos."', status=1, file = '".$link1."'  WHERE  ID=".$id_next;
	mysql_query($query) or die("Query failed7");
	$query= "UPDATE order_product SET press_diz = '".$link1."' WHERE  ORDER_ID=".$id_orede;
	mysql_query($query) or die("Query failed8");
	}
} 
}

IF ($fh  == '999'){

	$query= " select o1.id from order_per o1,
(select o.id, o.Numder_order, o.num_prod, o.acct_or, o.num_oper from order_per o where o.id = ".$id." ) o
where o1.Numder_order = o.Numder_order and o1.num_prod = o.num_prod and o1.acct_or = o.acct_or and  o1.num_oper = 1 and o1.part <> 'Сборка в готовое изделие'";
$result = mysql_query($query) or die("Query failed9");
while ($row = mysql_fetch_row($result)) { 
		$id_next =  $row[0];
	print $id_next."<BR/>";
	
	if ($id_next != '' OR $id_next != 0){
	IF ($kol != 0){
			$query= "UPDATE order_per SET date_pos ='".$date_pos."', status='1', kol_F = ".$kol.", file = '".$link1."'WHERE  ID=".$id_next;
			mysql_query($query) or die("Query failed10");
			$query= "UPDATE order_product SET print_diz = '".$link1."' WHERE  ORDER_ID=".$id_orede;
	mysql_query($query) or die("Query failed");
		}ELSE {
			$query= "UPDATE order_per SET date_pos ='".$date_pos."', status='1', file = '".$link1."' WHERE  ID=".$id_next;
			PRINT $query;
			mysql_query($query) or die("Query failed11");
			
			$query= "UPDATE order_product SET print_diz = '".$link1."'WHERE  ORDER_ID=".$id_orede;
	mysql_query($query) or die("Query failed");
		}
	} 
}

}

	
IF ($fh != '998' AND $fh != '999' ){
$query= " select o1.ID from order_per o1,(select o.id, o.Numder_order, o.num_prod, o.acct_or, o.num_oper, o.part from order_per o where o.id = ".$id." ) o
where	o1.Numder_order = o.Numder_order and o1.num_prod = o.num_prod  and  o1.acct_or = o.acct_or and o1.part = o.part and o1.num_oper = o.num_oper + 1 ";
$result = mysql_query($query) or die("Query failed12");
	if($row = mysql_fetch_row($result)){
		$id_next =  $row[0];
	}
if ($id_next != '' OR $id_next != 0){
	
IF ($kol != 0){
	$query= "UPDATE order_per SET date_pos ='".$date_pos."', status='1', kol_F = ".$kol.", file = '".$link1."' WHERE  ID=".$id_next;
mysql_query($query) or die("Query failed33");

}ELSE {
	$query= "UPDATE order_per SET date_pos ='".$date_pos."', status='1', file = '".$link1."' WHERE  ID=".$id_next;
mysql_query($query) or die("Query failed22");

}

}
}
$ih1 = 0;
$query= "SELECT COUNT(o1.id) FROM order_per o1, (select o.id, o.Numder_order, o.num_prod, o.acct_or, o.num_oper from order_per o where o.id = ".$id." ) o
WHERE o1.Numder_order = o.Numder_order and o1.num_prod = o.num_prod and o1.acct_or = o.acct_or and o1.part = 'Сборка в готовое изделие' and status <> 0 ";
$result = mysql_query($query) or die("Query failed11");																			 
if($row = mysql_fetch_row($result)){
	$ih1 = $row[0];
}

IF ($ih1 == 0){
	$ih = 1;
	$query= "SELECT COUNT(o1.id) FROM order_per o1, (select o.id, o.Numder_order, o.num_prod, o.acct_or, o.num_oper from order_per o where o.id = ".$id." ) o
	WHERE o1.Numder_order = o.Numder_order and o1.num_prod = o.num_prod and o1.acct_or = o.acct_or and o1.part <> 'Сборка в готовое изделие' and status <> 3";
	$result = mysql_query($query) or die("Query failed1");
	if($row = mysql_fetch_row($result)){
		$ih = $row[0];
	}
	if ($ih == 0){
		$query= "SELECT o1.iD FROM order_per o1, (select o.id, o.Numder_order, o.num_prod, o.acct_or, o.num_oper from order_per o where o.id = ".$id.") o
		WHERE o1.Numder_order = o.Numder_order and o1.num_prod = o.num_prod and o1.acct_or = o.acct_or and o1.part = 'Сборка в готовое изделие' and status <> 3 and o1.num_oper = 1";
		$result = mysql_query($query) or die("Query failed2");
		if($row = mysql_fetch_row($result)){
			$next_id = $row[0];
		}
		if ($next_id != '' OR $next_id != 0){
		$query= "UPDATE order_per SET date_pos ='".$date_pos."', status='1' WHERE  ID=".$next_id;
		mysql_query($query) or die("Query failed3");
		}
		
	}
}
$query = "select COUNT(*),t.id from order_per o ,(select o.Numder_order id from order_per o where o.id = ".$id.") t where o.Numder_order = t.id and status <> 3";
$result = mysql_query($query) or die("Query failed1");
	if($row = mysql_fetch_row($result)){
			$is =  $row[0];
			$idd = $row[1];
	}
	
	if ($is == 0){
		$query= "UPDATE orders SET  STATUS_ID='3' WHERE  NUMBER=".$idd;
	
		mysql_query($query) or die($query);
	}
	
mysql_close($connection);

?>
<script >	location.href = '../index.php';

</script>
