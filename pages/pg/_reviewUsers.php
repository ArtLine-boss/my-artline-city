<?php
include '../firewall.php';
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Добавление нового клиента</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/justified-nav.css" rel="stylesheet">
    <script src="js/bootstrap.min.js" type = "text/javascript"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/funJs.js"></script>
    <script src="js/bootstrap.js"></script>
   <script>
        window.onblur = function () {window.close()}
    </script>
  </head>
  <body>

<?php

 if (!empty($_GET["id_users"])) 
 { 
    include_once '../db.php';
    $query="select ID, USER_FIO, USER_LOGIN, USER_PASSWORD , USER_POST , USER_PER from users where id =".$_GET["id_users"];
    $result = mysql_query($query) or die("Query failed");
    while ($row = mysql_fetch_row($result)) { 
		print " <div class='container'>";
		print " 	<form  class='form-signin' method='post' action='_updateUsers.php'>	";
		print "		<h3>Пожалуйста, заполните поля:</h3><hr/>		<label>ФИО: </label>";
		print "		<input type='text' name='usersFIO' value = '$row[1]'><br/>    	<label>Должность: </label>";
		print "		<input type='text' name='usersPost' value = '$row[4]'><br/>     	<label>Логин: </label>";
		print "		<input type='text' name='usersLogin' value = '$row[2]'><br/>    	<label>Права доступа: </label>";
		print "		<select name='usersPer' > <!--class='form-control'-->";
		switch ($row[5]){
			case 1: print " <option value = '0' >Выбрать</option>
							<option value = '4'>Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1' selected>Производственник</option>
							<option value = '5'>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7'>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;	
		
			case 2: print "	<option value = '0' >Выбрать</option>
							<option value = '4'>Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2' selected>Бухгалтер</option> 
							<option value = '1'>Производственник</option>
							<option value = '5'>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7'>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;	
		
			case 3:	print "	<option value = '0' >Выбрать</option>
							<option value = '4'>Администратор</option> 
							<option value = '3' selected>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1'>Производственник</option><option value = '5'>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7'>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;	
		
			case 4: print "	<option value = '0' >Выбрать</option>
							<option value = '4' selected>Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1'>Производственник</option>
							<option value = '5'>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7'>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;	
							
					case 5: print "	<option value = '0' >Выбрать</option>
							<option value = '4' >Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1'>Производственник</option>
							<option value = '5' selected>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7'>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;	
							
							
					case 6: print "	<option value = '0' >Выбрать</option>
							<option value = '4' >Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1'>Производственник</option>
							<option value = '5'>Дизайнер</option>
							<option value = '6' selected>Печатник</option>
							<option value = '7'>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;	
case 7: print "	<option value = '0' >Выбрать</option>
							<option value = '4' >Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1'>Производственник</option>
							<option value = '5'>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7' selected>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;			
				case 8: print "	<option value = '0' >Выбрать</option>
							<option value = '4' >Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1'>Производственник</option>
							<option value = '5'>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7' selected>Препресс</option>
							<option value = '8' selected>Начальник производства</option>"; break;		
							
			case 0: print "	<option value = '0' selected>Выбрать</option>
							<option value = '4' >Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1'>Производственник</option>
							<option value = '5'>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7'>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;		
							
			default: 	  "	<option value = '0' selected>Выбрать</option>
							<option value = '4'>Администратор</option> 
							<option value = '3'>Менеджер</option> 
							<option value = '2'>Бухгалтер</option> 
							<option value = '1'>Производственник</option>
							<option value = '5'>Дизайнер</option>
							<option value = '6'>Печатник</option>
							<option value = '7'>Препресс</option>
							<option value = '8' >Начальник производства</option>"; break;	
		}
		
		print "     </select><br/>";
		print "<input type='hidden' value='$row[0]' name='usersId'> <br/>";
		print "	<br/>";
		print "	<input type='submit' value='Изменить' /></form>";
		print "</div>";
    }   
mysql_close($connection);

 } 

?>

  </body>
</html>
