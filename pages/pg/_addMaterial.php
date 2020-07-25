<?php
include '../firewall.php';
?>
<?php
	$line ="<select name = 'addMaterial'> <option value='' selected >Выберите материал</option>"; 
	include_once '../db.php';
	$query="select ID,MT_TYPE from material";
	$result = mysql_query($query) or die("Query failed");
	while ($row = mysql_fetch_row($result)) 
	{ 
		$line =$line."<option value='$row[0]'>$row[1]</option>"; 
	}
	$line = $line."</select><br/>";
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
	/*	window.onblur = function () {window.close()}*/
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
			theElement.innerHTML = "<form  class='form-signin' method='post' action='_addMaterials.php' enctype='multipart/form-data'>	<p>Пожалуйста, заполните поля:</p><label >Тип материала:</label><input type='text' name='typeMaterial'><br/>    	<label >Имя материала: </label><input type='text' name='nameMaterial'><br/>     	<label >Ед. измерения:</label><input type='text' name='izmMaterial'><br/>    	<label>Размер, мм</label><input type='text' name='sizeMaterial'><br/>    	<label>Стоимость, $</label><input type='text' name='priceMaterial'><br/>  <label>Толщина, мм</label><input type='text' name='tolMaterial'><br/> <label>Аватарка: </label><input name='file' type='file' /> </label><br/>  <input type='hidden' value='n' name='type'><br/><input type='submit' value='Создать' /></form>";
		} 
		else if (input.value == 's'){
			theElement.innerHTML = "<form  class='form-signin' method='post' action='_addMaterials.php' enctype='multipart/form-data'> <p>Пожалуйста, заполните поля:</p> <label >Тип материала:</label> "+ line+ "<label >Имя материала: </label><input type='text' name='nameMaterial'><br/>   <label >Ед. измерения:</label><input type='text' name='izmMaterial'><br/>   <label>Размер, мм</label><input type='text' name='sizeMaterial'><br/>  <label>Стоимость, $</label><input type='text' name='priceMaterial'><br/><label>Толщина, мм</label><input type='text' name='tolMaterial'><br/> <label>Аватарка:</label> <input name='file' type='file' /> <br/>    <input type='hidden' value='s' name='type'> <br/>  <input type='submit' value='Создать' /></form>";
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

