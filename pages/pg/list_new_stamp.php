<?php
	include "../db.php";
	$name = $_GET['name'];
	$str = "";
	$query = "select ID, STAMP_TYPE , STAMP_NAME, STAMP_KOL,STAMP_SIZE,STAMP_PRICE,STAMP_NEW from stamps WHERE  UPPER(STAMP_TYPE) = '".strtoupper($name)."' AND STAMP_NAME  <> '' AND STAMP_TEST = 1 ORDER BY STAMP_TYPE ";
	$result = mysql_query($query) or die($query);
	WHILE ($row = mysql_fetch_row($result)) { 
	$str = $str."<option value='$row[0]?$row[3]^$row[4]/$row[6]`$row[5]'>$row[2]</option>"; 
	}
	mysql_close($connection);
	echo $str;
?>