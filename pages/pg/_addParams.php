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
    <title>Добавление нового заказа</title>
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <script src="../../vendor/bootstrap/js/bootstrap.min.js" type = "text/javascript"></script>
	<script src="../../js/funJs.js"></script>
    <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

</head>
<body>
    <div class='container'>
        <div class='masthead'>
        	<form  class='form-signin' method='post' action='_addParam.php'>
				<p>Пожалуйста, заполните поля:</p>
				<div class='row'><div class='col-md-2'><div class='block1'><label >Наименование:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' id='name' name='name'></div></div></div>
				<div class='row'><div class='col-md-2'><div class='block1'><label >Значение: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' id='val' name='val'></div></div></div>
				<div class='row'><div class='col-md-2'><div class='block1'><input type="submit" class="btn btn-default" value="Создать" style="margin: 10px"></div></div></div>
			</form> 
        </div>
    </div>
</body>
</html>
