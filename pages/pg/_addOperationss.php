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
    <div class='container'>
        <div class='masthead'>
            <h3 class='text-muted'>Добавление операции</h3><hr/>
            <form  class='form-signin' method='post' action='_addOperation.php'>
                <label>Наименование:</label> <input type='text' name='operName' ><br/>   
				<label>Параметр: </label><input type='text' name='operPar' ><br/> 				
                <label>Ед. изм.: </label><input type='text' name='operUnits' ><br/>  
                <label>Кол-во операций(мин):</label><input type='text' name='operUnitMin' ><br/>
                <label>Время приладки(мин):</label><input type='text' name='operTime' ><br/> 
				<label>Стоимость приладки($):</label><input type='text' name='operTimeP' ><br/> 				
                <label>Стоимость операции($):</label><input type='text' name='operPrice' ><br/>   
					  <label>Комментарии:</label>
					  
					  <select name='opercom'>
						<option value = ""> </option>
						<?
						$query="select eq_name from equipment";
						$result = mysql_query($query) or die($query);
						while ($row = mysql_fetch_row($result)) { 
							echo '<option value = "'.$row[0].'">'.$row[0].' </option>'	;		
						}
						?>
						</select><br/>   
                <input type='hidden' value='' name=''><br/>
                <input type='submit' value='Добавить' />
            </form>
        </div>
    </div>
</body>
</html>