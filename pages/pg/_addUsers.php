<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Добавление нового клиента</title>

    <!-- Bootstrap core CSS 
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <script src="../../vendor/bootstrap/js/bootstrap.min.js" type = "text/javascript"></script>-->

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>


	
	
  </head>
  <body>
    <script>
/*window.onblur = function () {window.close()}*/
</script>
    <div class="container">
            <form  class='form-signin' method='post' action='_addUser.php'>	
			<h3>Пожалуйста, заполните поля:</h3><hr/>		
			<div class='row'><div class='col-md-1'><div class='block1'><label>ФИО:</label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='usersFIO'></div></div></div>
			<div class='row'><div class='col-md-1'><div class='block1'><label>Должность:</label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='usersPost'>  </div></div></div>   
			<div class='row'><div class='col-md-1'><div class='block1'><label>Логин:</label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='usersLogin'> </div></div></div>   
			<div class='row'><div class='col-md-1'><div class='block1'><label>Пароль:</label></div></div><div class='col-md-6'><div class='block2'><input type='password' name='usersPassword'> </div></div></div>
			
			<div class='row'><div class='col-md-1'><div class='block1'><label>Права доступа:</label></div></div><div class='col-md-6'><div class='block2'>
			<select class="selectpicker"  name='usersPer'> <!--class="form-control"-->
				<option value = '0' selected>Выбрать</option>
				<option value = '4'>Администратор</option>
               <option value = '3'>Менеджер</option>
               <option value = '2'>Бухгалтер</option>
               <option value = '1'>Производственник</option>
					<option value = '5'>Дизайнер</option>
					<option value = '6'>Печатник</option>
					<option value = '7'>Препресс</option>
					
							<option value = '8' >Начальник производства</option>	
            </select>
			</div></div></div>
			
	

			<div class='row'><div class='col-md-1'><div class='block1'><input type='submit' value='Создать' /></div></div></div></form>
	</div>
 </body>
</html>

