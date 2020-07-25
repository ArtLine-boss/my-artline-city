<?php

		$id_client=$_GET["id_client"];
		$mail=$_GET["mail"];
		
		include_once'../db.php';		
		$query = "	UPDATE clients SET 
				EMAIL = '".$mail."'
				WHERE ID ='".$id_client."';";
		mysql_query($query) or die($query);
		
	
?>



