

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

 if (!empty($_GET["id"])) 
 { 
    include_once '../db.php';
    $query="select * from settings where id =".$_GET["id"];
    $result = mysql_query($query) or die("Query failed");
    while ($line = mysql_fetch_row($result)) { 
        print "<div class='container'>";
        print "<div class='masthead'>";
        print "    <h3 class='text-muted'>Просмотр</h3><hr/>";
		print " 	<form  class='form-signin' method='post' action='_updateParams.php'>";
		print " 		<p>Пожалуйста, заполните поля:</p>";
		print " 		<div class='row'><div class='col-md-2'><div class='block1'><label >Наименование:</label></div></div><div class='col-md-2'><div class='block1'><label>$line[1]</label></div></div></div>";
		print " 		<div class='row'><div class='col-md-2'><div class='block1'><label >Значение: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' id='val' name='val' value = '$line[2]'>
		<input type='hidden' value='$line[0]' name='id'></div></div></div>";
		print " 		<div class='row'><div class='col-md-2'><div class='block1'><input type='submit' class='btn btn-default' value='Изменить' style='margin: 10px'></div></div></div>";
		print " 	</form> ";
        print "</div>";
        print "</div>";
		

    }  
mysql_close($connection);

 } 

?>


  </body>
</html>
	