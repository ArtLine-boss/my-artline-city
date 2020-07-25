<?
$val = $_GET['val'];
$id =  $_GET['id'];

$val1  = explode(",", $val );


$res = "";
    include_once '../db.php';

	 $query = "select DISTINCT e.eq_name, e.id from operations o, (select * from equipment e) e where  FIND_IN_SET(o.id ,e.oper) AND o.OPERATION_NAME = '".$id."'";
	 $result = mysql_query($query) or die($query);
	 WHILE ($row = mysql_fetch_row($result)) { 
	 $flsg = false;
			FOR ($i = 0; $i < count($val1); $i++){	
		IF ($val1[$i] == $row[1] ) {
			 $flsg = true;
		}
		
	 
		}
		
		
		IF ($flsg) {	
			$res = $res."<option value='$row[1]' selected> $row[0]</option>";
		}ELSE {
			$res = $res."<option value='$row[1]'> $row[0]</option>";
		}
		
	 }
	 
	 
	 
	 echo $res;
    

?>