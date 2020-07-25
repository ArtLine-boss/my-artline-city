<?
$val = $_GET['val'];
$id =  $_GET['id'];
$res = "";
    include_once '../db.php';
switch ($val ) {
    case "0":
	 $query = "select DISTINCT e.eq_name, e.id from operations o, (select * from equipment e) e where  FIND_IN_SET(o.id ,e.oper) AND o.OPERATION_NAME = '".$id."'";
	 $result = mysql_query($query) or die($query);
	 WHILE ($row = mysql_fetch_row($result)) { 
	 $res = $res."<option value='$row[1]'> $row[0]</option>";
	 }
	 echo $res;
        break;
    case "1":
        break;
    }


?>