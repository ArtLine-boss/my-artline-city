<?php
	header('Content-Type: text/html; charset=utf-8');
    include_once $_SERVER['DOCUMENT_ROOT'] . "/www/core/core.php";
	include_once 'db.php';
	session_start();
	$hash = "";
	$reset = 0;
	$ACCESS_ROLES = array();
	$query = mysql_query("SELECT user_hash,reset_password FROM users WHERE user_login='".$_SESSION['login']."' AND user_password='".$_SESSION['password']."' AND  USER_STATUS=1 LIMIT 1");
	if($data = mysql_fetch_assoc($query)) {
		$reset = $data['reset_password'];
		if(isset($_SESSION['last_time']) && (time() - $_SESSION['last_time']) <= 3600) {
			$_SESSION["last_time"] = time();
			if($reset == 1) {
				header('Location: rpassword.php?current&reset');
			}
		}
		else {
			session_destroy();
			header('Location: login.php');
		}
	}	
	else {
		session_destroy();
		header('Location: login.php');				
	}
	
	//доступы для пользователя
	$sql = "SELECT accessrole.level_id FROM accessrole WHERE accessrole.user_id='".$_SESSION['login']."' AND date_start<='".date("Y-m-d H:i:s")."' AND (date_end>='".date("Y-m-d H:i:s")."' OR date_end='0000-00-00 00:00:00')";
	$query = mysql_query($sql) or die();
	while($data = mysql_fetch_array($query)) {
		$ACCESS_ROLES[] = $data['level_id'];
	}
	
?>