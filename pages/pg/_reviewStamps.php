
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

    <!-- Custom styles for this template -->

    <script src="../../js/funJs.js"></script>
   <script>
       /* window.onblur = function () {window.close()}*/
    </script>
  </head>
  <body>

<?php

 if (!empty($_GET["id_stamp"])) 
 { 
    include_once '../db.php';
    $query="select * from stamps where id =".$_GET["id_stamp"];
    $result = mysql_query($query) or die("Query failed");
    while ($line = mysql_fetch_row($result)) { 
      print "<div class='container'>";
      print "<div class='masthead'>";
      print "    <h3 class='text-muted'>Просмотр информации</h3><hr/>";
		print "<form  class='form-signin' method='post' action='_updateStamps.php' enctype='multipart/form-data'> "; 	
		print "<div class='row'><div class='col-md-2'><div class='block1'><label >Тип штампа: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='typeStamp' value='$line[1]'></div></div></div>   ";   
		print "<div class='row'><div class='col-md-2'><div class='block1'><label >Наименование штампа: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='nameStamp' value='$line[2]'></div></div></div>  ";print "<div class='row'><div class='col-md-2'><div class='block1'><label >Номер штампа: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='numStamp' value='$line[3]'></div></div></div>  ";     
		print "<div class='row'><div class='col-md-2'><div class='block1'><label >Кол-во за удар: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='kolStamp' value='$line[5]'></div></div></div> ";   
		print "<div class='row'><div class='col-md-2'><div class='block1'><label>Размер: </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='sizeStamp' value='$line[4]'></div></div></div>    "; 
		print "<div class='row'><div class='col-md-2'><div class='block1'><label>Цена (рублей): </label></div></div><div class='col-md-6'><div class='block2'><input type='text' name='priceStamp' value='$line[8]'></div></div></div> ";
		IF ($line[10] == '1'){
			print "<div class='row'><div class='col-md-6'><div class='checkbox'><label><input type='checkbox' name='priceTest' checked> Расчетный</label></div></div></div> ";
		}
		else{
			print "<div class='row'><div class='col-md-6'><div class='checkbox'><label><input type='checkbox' name='priceTest'> Расчетный</label></div></div></div> ";
		}
		
		print "<div class='row'><div class='col-md-2'><div class='block1'><label>Аватарка: </label></div></div><div class='col-md-6'><div class='block2'><input name='file' type='file' /></div></div></div> "; 
		print "<div class='row'><div class='col-md-2'><div class='block1'><label>Шаблон: </label></div></div><div class='col-md-6'><div class='block2'><input name='file1' type='file' /></div></div></div> "; 
		print "<div class='row'><div class='col-md-2'><div class='block1'><input type='hidden' value='$line[0]' name='idStamp'><br/><input type='submit' value='Изменить' /></div></div></div> "; 
		print "</form> "; 
      print "</div>";
      print "</div>";
		

    }   
mysql_close($connection);

 } 

?>

  </body>
</html>


	