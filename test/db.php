<?php
	/*$host = 'localhost';*/
	
	//$host = 'localhost';
	$host = '192.168.1.42';
	$user = 'root';
	$pwsd = '';
	$db = 'Art'; 
	$connection = mysql_connect($host, $user, $pwsd);
	
	mysql_query("SET NAMES utf-8"); 
	
	mysql_select_db($db, $connection); 
	
	if (!$connection || !mysql_select_db($db, $connection))
	{
		exit(mysql_error());
	}
?>