<?
	if(empty(isset($_GET['reset']))) {
		include 'firewall1.php';
	}
	else {
		include_once 'db.php';
	}
	session_start();
	$login = $_SESSION['login'];
	$query = "select user_per from users where user_login = '".$login ."' LIMIT 1";
	$result = mysql_query($query) or die($query);
	if ($row = mysql_fetch_row($result)) { 
		$admin = $row[0];
	}
	if( isset( $_POST['ResetPass'] ) ) {
		$username = "";
		$flag = 0;
		if(!empty($_POST['username'])) {
			$username = $_POST['username'];
			$flag = 1;
		}
		else if(!empty($_POST['current_password'])) {
			//проверяем текущий пароль
			$select = "SELECT USER_PASSWORD FROM users WHERE USER_LOGIN='$login'";
			$query = mysql_query($select) or die(null);
			if($row = mysql_fetch_array($query)) {
				if($row['USER_PASSWORD'] == md5(md5($_POST['current_password']))) {
					$username = $login;
					$flag = 2;
				}
			}
		}
		$mess_error = "";
		if(!empty($username)) {
			//текущий и новый не должны совпадать
			if(!empty($_POST['current_password']) && $_POST['password'] == $_POST['current_password']) {
				$mess_error = "<label style='color:red'>Текущий и новый пароль не могут совпадать</label>";
			}
			else {
				//новый и повторный пароль должны совпадать
				if($_POST['password'] == $_POST['repeat_password']) {
					//проверяем новый пароль на правило
					$password = $_POST['password'];
					//длина
					if(strlen($password) < 7) {
						$mess_error = "<label style='color:red'>Длина пароля должна быть не меньше 7 символов</label>";
					}
					else {
						//символы латинские и цифры
						if(preg_match("/(([a-zA-Z]\d)|(\d[a-zA-Z]))+/", $password)) {
							$password = md5(md5($password));
							$update = "UPDATE users SET reset_password=0,USER_PASSWORD='$password' WHERE USER_LOGIN='$username'";
							$query = mysql_query($update) or die(null);
							if($query) {
								$mess_error = "<label style='color:green'>Пароль успешно обновлен. При следующем входе используйте новый пароль</label>";
								if($flag == 2)
									session_destroy();
							}
							else {
								$mess_error = "<label style='color:red'>Произошла ошибка при обновлении пароля</label>";
							}
						}
						else {
							$mess_error = "<label style='color:red'>Символы в пароле должны быть только буквами латинского алфавита и цифрами</label>";
						}
					}
				}
				else {
					$mess_error = "<label style='color:red'>Не совпадает повторный ввод пароля</label>";
				}
			}
		}
		else {
			$mess_error = "<label style='color:red'>Не удалось распознать имя пользователя или неверный пароль</label>";
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Система управления заказами</title>
		<link rel="icon" href="../favicon.png" type="image/png">
		<!-- Bootstrap Core CSS -->
		<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<!-- MetisMenu CSS -->
		<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		<!-- Custom CSS -->
		<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
		<!-- Morris Charts CSS -->
		<link href="../vendor/morrisjs/morris.css" rel="stylesheet">
		<!-- Custom Fonts -->
		<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		<!-- jQuery -->
		<script src="../vendor/jquery/jquery.min.js"></script>
		<script src="../js/funJs.js"></script>
		<!-- Bootstrap Core JavaScript -->
		<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<!-- Metis Menu Plugin JavaScript -->
		<script src="../vendor/metisMenu/metisMenu.min.js"></script>
		<!-- Morris Charts JavaScript -->
		<script src="../vendor/raphael/raphael.min.js"></script>
		<script src="../vendor/morrisjs/morris.min.js"></script>
		<script src="../data/morris-data.js"></script>
		<!-- Custom Theme JavaScript -->
		<script src="../dist/js/sb-admin-2.js"></script>
		<!-- DataTables JavaScript -->
		<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
		<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
		<!-- Page-Level Demo Scripts - Tables - Use for reference -->
		<!-- js класс -->
		<script src="js/directoryCodeStat.js?version=2"></script>
	</head>

	<body>
		<div id="wrapper">
			<?php
				include_once("menu.php");
			?>
			<div id="page-wrapper">
				<div class="row">
					<div class="col-lg-12">
						<h2 class="page-header">Смена пароля</h2>
					</div>
				</div>
				<div class='row'>
					<div class='col-md-2'></div>
					<div class='col-md-8'>
						<? echo $mess_error; ?>
						<form method="POST">
							<? if($admin == 4 && !isset($_GET['current'])): ?>
								<div class='row'>
									<label>Пользователь</label>
									<select id="username" name="username" class="form-control">
										<?
											$select = "SELECT USER_LOGIN,USER_FIO FROM users WHERE USER_PER<>4 ORDER BY USER_FIO";
											$query = mysql_query($select) or die(null);
											while($row = mysql_fetch_array($query)) {
												$name = $row['USER_FIO'];
												if(empty($name))
													$name = $row['USER_LOGIN'];
												echo "<option value='".$row['USER_LOGIN']."'>".$name."</option>";
											}
										?>
									</select>
									<script>
										document.getElementById("username").selectedIndex = -1;
									</script>
								</div>
							<? endif; ?>
							<? if(isset($_GET['current'])): ?>
								<div class="row">
									<label>Текущий пароль</label>
									<input name="current_password" type="password" style="width: 100%">
								</div>
							<? endif; ?>
							<div class="row">
								<label>Новый пароль</label>
								<input name="password" type="password" style="width: 100%">
							</div>
							<div class="row">
								<label>Повторить пароль</label>
								<input name="repeat_password" type="password" style="width: 100%">
							</div>
							<div class="row" style="margin-top:10px">
								<button class="btn btn-success" type="submit" id="ResetPass" name="ResetPass">Сбросить пароль</button>
							</div>
							<div class='row'>
								Новый пароль должен содержать не менее 7 символов, и состоять из латиских букв и цифр
							</div>
						</form>
					</div>
					<div class='col-md-2'></div>
				</div>
			</div>
		</div>	
		<!-- Site footer -->
		<footer class="footer">
			<p>&copy; ArtLineCity 2019</p>
		</footer>
	</body>
</html>