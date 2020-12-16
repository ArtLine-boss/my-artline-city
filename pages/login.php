<?php
	
	// Страница авторизации

	# Соединямся с БД
	
	include 'db.php';
	//include 'firewall1.php';
	session_start();
	$mess_error = "";
	
	if(isset($_POST['inputSubmit'])){
		# Вытаскиваем из БД запись, у которой логин равняеться введенному
		
		
		if($_POST['login'] != '' AND $_POST['password'] != '' ){
			
			
			$login = $_POST['login'];
			
			$query = mysql_query("SELECT id, user_password FROM users WHERE user_login='".mysql_real_escape_string($_POST['login'])."' AND USER_STATUS=1 LIMIT 1");
			
			$data = mysql_fetch_assoc($query);
			
			
			# Сравниваем пароли
			if($data['user_password'] == md5(md5($_POST['password'])))
			{
				$_SESSION["login"] = $login;
				$_SESSION["password"] = $data['user_password'];
				$_SESSION["last_time"] = time();
				
				header("Location: orders.php"); exit();
				
				# Переадресовываем браузер на страницу проверки нашего скрипта
				// header("Location: check.php"); exit();
			}
			else {
				$mess_error = "<label style='color: red'>Вы ввели неправильный логин/пароль</label>";
			}
			
	 	} else {
			$mess_error = "<label style='color: red'>Вы ввели неправильный логин/пароль</label>";
		}
	}	
			
?>
<html lang="ru">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Вход в систему управления заказами</title>
	<link rel="icon" href="../favicon.png" type="image/png">

    <!-- Bootstrap Core CSS -->
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>

<body style="background-color: white">

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Вход</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="login.php">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Login" name="login" type="login" autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Запомнить меня
                                    </label>
                                </div>
                                <!-- Change this to a button or input when using this as a form -->
                                <button type = "submit" name = "inputSubmit" id="inputSubmit" class="btn btn-lg btn-success btn-block">Вход</button>
                            </fieldset>
                        </form>
						<?
							if(!empty($mess_error)) {
								echo $mess_error;
							}
						?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="../vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../dist/js/sb-admin-2.js"></script>

    <script>
        $(document).ready(function() {
            var d_today = new Date();
            var year_start = d_today.getFullYear();
            var year_end = d_today.getFullYear();
            if(d_today.getMonth() == 0)
                year_start--;
            else
                year_end++;
            var d_start = new Date(year_start, 11, 17);
            var d_end = new Date(year_end, 0, 20);

            if(d_today.getTime() < d_start.getTime() || d_today.getTime() > d_end.getTime())
                return;
            document.getElementsByTagName('body')[0].setAttribute('style', 'background: url(/js/snowfall/img/pexels.jpeg) no-repeat center center fixed; background-size: cover;');
        });
    </script>

</body>

</html>
			
						