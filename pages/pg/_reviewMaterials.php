
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title></title>
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
   <script>
        /*window.onblur = function () {window.close()}*/
    </script>
  </head>
  <body>

<?php

 if (!empty($_GET["id_material"])) 
 { 
    include_once '../db.php';
    $query="select * from material_attr where id =".$_GET["id_material"];
    $result = mysql_query($query) or die("Query failed");
    while ($line = mysql_fetch_row($result)) { 
        print "<div class='container'>";
        print "<div class='masthead'>";
        print "    <h3 class='text-muted'>Просмотр информации об материале</h3><hr/>";
		print "<form  class='form-signin' method='post' action='_updateMaterials.php' enctype='multipart/form-data'> "; 	
		print "<div class='row'><div class='col-md-2'><div class='block1'><label >Тип материала: </label></div></div><div class='col-md-6'><div class='block2'>"; 
		
			print  "<select name = 'typeMaterial'> <option value='' selected >Выберите материал</option>"; 
	
				$query1="select ID,MT_TYPE from material";
				$result2 = mysql_query($query1) or die("Query failed");
				while ($row = mysql_fetch_row($result2)) 
				{ if ($line[1] == $row[0]){
					print  "<option value='$row[0]' selected>$row[1]</option>"; 
				}else{
					print  "<option value='$row[0]'>$row[1]</option>"; 
				}
					
	}
	print  "</select><br/>";
		print  "</div></div></div> ";   	
		
		
		print "<div class='row'><div class='col-md-2'><div class='block1'><label >Имя материала: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='nameMaterial' value='$line[2]'></div></div></div> ";     
		print "<div class='row'><div class='col-md-2'><div class='block1'><label >Ед. измерения: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='izmMaterial' value='$line[5]'></div></div></div> ";   
		print "<div class='row'><div class='col-md-2'><div class='block1'><label>Размер, мм </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='sizeMaterial' value='$line[4]'></div></div></div>    "; 
		print "<div class='row'><div class='col-md-2'><div class='block1'><label>Стоимость, $ </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='priceMaterial' value='$line[3]'></div></div></div>   "; 
		print "<div class='row'><div class='col-md-2'><div class='block1'><label>Толщина, мм </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='tolMaterial' value='$line[6]'></div></div></div>  "; 
		print "<div class='row'><div class='col-md-2'><div class='block1'><label>Аватарка:</label></div></div><div class='col-md-6'><div class='block2'> <input name='file' type='file' /> </div></div></div>  "; 
		print "<div class='row'><div class='col-md-2'><div class='block1'><input type='hidden' value='$line[0]' name='idMaterial'></div></div></div>
		<div class='row'><div class='col-md-2'><div class='block1'><input type='submit' value='Изменить' /></div></div></div>"; 
		print "</form> "; 
        print "</div>";
        print "</div>";
		

    }  
mysql_close($connection);

 } 

?>

		<div class='row'>
          <div class='col-md-2'>
            <div class='block1'>
			
			</div></div><div class='col-md-6'><div class='block2'>
			
			</div></div></div>


  </body>
</html>
	