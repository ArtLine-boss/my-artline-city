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
      //  window.onblur = function () {window.close()}
    </script>
  </head>
  <body>

<?php

 if (!empty($_GET["id_oper"])) 
 { 
    include_once '../db.php';
    $query="select * from operations where id =".$_GET["id_oper"];
    $result = mysql_query($query) or die("Query failed");
    while ($line = mysql_fetch_row($result)) { 
        print "<div class='container'>";
        print "<div class='masthead'>";
        print "    <h3 class='text-muted'>Просмотр операции</h3><hr/>";
        print "    <form  class='form-signin' method='post' action='_updateOperation.php'>";
        print "        <label>Наименование:</label> <input type='text' name='operName' value='$line[1]'><br/>  "; 
		print "        <label>Параметр:</label> <input type='text' name='operPar' value='$line[2]'><br/>  "; 
        print "        <label>Ед. изм.: </label><input type='text' name='operUnits' value='$line[3]'><br/>  ";
        print "        <label>Кол-во операций(мин):</label><input type='text' name='operUnitMin' value='$line[4]'><br/>";
        print "        <label>Время приладки(мин):</label><input type='text' name='operTime' value='$line[5]'><br/> ";   
		print "        <label>Стоимость приладки($):</label><input type='text' name='operTimeP' value='$line[6]'><br/> ";  
        print "        <label>Стоимость операции($):</label><input type='text' name='operPrice' value='$line[7]'><br/> ";
          print " <label>Комментарии:</label>";
			  print "<select name='opercom'>
						<option value = ''> </option>";
					
						$query="select eq_name from equipment";
						$result = mysql_query($query) or die($query);
						while ($row = mysql_fetch_row($result)) { 
						if (strcasecmp($line[8], $row[0]) == 0 ){
							echo '<option value = "'.$row[0].'" selected>'.$row[0].' </option>'	;	
						}else {
							echo '<option value = "'.$row[0].'">'.$row[0].' </option>'	;	
						}							
						}
					
				print		"</select><br/>   "   	;	  
        print "        <input type='hidden' value='$line[0]' name='operId'> <br/>";
        print "        <input type='submit' value='Изменить' />";
        print "    </form>";
        print "</div>";
        print "</div>";
    }   
mysql_close($connection);

 } 

?>

  </body>
</html>
