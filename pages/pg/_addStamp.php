<?php
	$line ="<select name = 'typeStamp'> <option value='' selected >Выберите материал</option>"; 
	include_once '../db.php';
	$query="select DISTINCT STAMP_TYPE from stamps";
	$result = mysql_query($query) or die("Query failed");
	while ($row = mysql_fetch_row($result)) 
	{ 
		$line =$line."<option value='$row[0]'>$row[0]</option>"; 
	}
	$line = $line."</select>";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">

    <title>Добавление нового материаа</title>
    <!-- Bootstrap core CSS -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->

    <script src="../../js/funJs.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
  </head>
  <body>
    <script>
	/*
		window.onblur = function () {window.close()}*/
	</script>
	<script >
	function fun2() {
		var line  = "<?php echo $line;?>";
		var rad=document.getElementsByName('r2');
		for (var i=0;i<rad.length; i++) {
			 var input = rad[i];
			 if(rad[i].checked){

		var theElement = document.getElementById("elem2");
		if(input.value == 'n'){
			theElement.innerHTML = "<form  class='form-signin' method='post' action='_addStamps.php' enctype='multipart/form-data'><p>Пожалуйста, заполните поля: </p><div class='row'><div class='col-md-2'><div class='block1'><label>Тип штампа:</label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='typeStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Наименование штампа: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='nameStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Номер штампа: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='numStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>кол-во за удар:  </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='kolStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Размер: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='sizeStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Цена (рублей): </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='priceStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Аватарка: </label></div></div><div class='col-md-6'><div class='block2'><input name='file' type='file' /></div></div></div><div class='row'><div class='col-md-6'><div class='checkbox'><label><input type='checkbox' name='priceTest'> Расчетный</label></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Шаблон: </label></div></div><div class='col-md-6'><div class='block2'><input name='file1' type='file' /></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><input type='hidden' value='n' name='type'><br/><input type='submit' value='Создать' /></div></div></div></form>";
		} 
		else if (input.value == 's'){
			theElement.innerHTML = "<form  class='form-signin' method='post' action='_addStamps.php' enctype='multipart/form-data'> <p>Пожалуйста, заполните поля:</p><div class='row'><div class='col-md-2'><div class='block1'><label>Тип материала:</label></div></div><div class='col-md-6'><div class='block2'> "+ line+ "</div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Наименование штампа: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='nameStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Номер штампа: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='numStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>кол-во за удар: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='kolStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Размер: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='sizeStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Цена (рублей): </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='priceStamp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Аватарка: </label></div></div><div class='col-md-6'><div class='block2'><input name='file' type='file' /></div></div></div><div class='row'><div class='col-md-6'><div class='checkbox'><label><input type='checkbox' name='priceTest' > Расчетный</label></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Шаблон: </label> </div></div><div class='col-md-6'><div class='block2'><input name='file1' type='file' /></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><input type='hidden' value='s' name='type'> <br/>  <input type='submit' value='Создать' /></div></div></div>	</form>";
			}
			 }
		}
	}
	</script>
    <div class="container">
		<div class='masthead'>
				<input type="radio" name="r2" value="n" onclick = "fun2()">Новый<br>
				<input type="radio" name="r2" value="s" onclick = "fun2()">Существующий<br>
							
		</div>
	</div>
<div class="container" id="elem2"></div>

  </body>
</html>

