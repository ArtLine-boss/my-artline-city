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
    <title>Добавление продукта</title>
 <script src="../../vendor/jquery/jquery.min.js"></script>
	<script type='text/javascript' src='../../vendor/jquery/chosen.jquery.min.js'></script>
	<link rel="stylesheet" type="text/css" href="../../less/chosen.min.css" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/justified-nav.css" rel="stylesheet">
    <script src="js/bootstrap.min.js" type = "text/javascript"></script>
   
    <script src="../../js/funJs.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
        window.onblur = function () {window.close()}
    </script>
</head>
<body>
    <div class='container'>
        <div class='masthead'>
            <h3 class='text-muted'>Добавление продукта</h3><hr/>
                <?php
					$id_acct = $_GET["id_acct"];
                    print  "<form  class='form-signin' method='post' action='_addOrProds.php'>";
                    print "<select name = 'orderProd' id=sl_fruits >";
                    print "<option value=''>Выберите продукт</option>"; 
				    include_once '../db.php';
                    $query="select id, product_name from product where product_name is not null and  product_name != ''  ORDER BY product_name;";
                    $result = mysql_query($query) or die("Query failed");
                    while ($row = mysql_fetch_row($result)) { 
                    print "<option value='$row[0]'>$row[1]</option>"; }
                    print "</select><br/><br/>";
					print "<input type='hidden' value='".$id_acct."' name='orderAcct'><br/>";
                    print "<input type='submit' value='Добавить' />";
                    print "</form>";
                ?>
        </div>
    </div>
	    
<script>
    $('#sl_fruits').chosen();
</script>
    
	
</body>
</html>