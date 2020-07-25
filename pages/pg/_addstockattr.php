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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/css/bootstrap-select.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>


</head>
<body>
    <div class='container'>
        <div class='masthead'>
        	<form  class='form-signin' method='post' action='_addStockattrs.php'>
				<p>Пожалуйста, заполните поля:</p>
				<div class='row'><div class='col-md-2'><div class='block1'><label >Наименование:</label></div></div><div class='col-md-2'><div class='block1'>
				
				<select class="selectpicker"  name='name' data-live-search="true"> <!--class="form-control"-->
					<?php
						include_once '../db.php';
					$query="select MT_TYPE, ma.M_NAME, ma.ID,ma.M_PRICE from material m, material_attr ma where ma.ID_M = m.ID and ma.M_NAME is not null;";
					$result = mysql_query($query) or die("Query failed");
					$flag = '';
						$ko = 0;
					while ($row = mysql_fetch_row($result))
					{ 
						
					if ($flag != $row[0]){
						if ($ko != 0) {
							print " </optgroup>";
						}
						$flag = $row[0];
						print "<optgroup label='$row[0]'><option value='$row[2]'>$row[1]</option>"; 
						
						$ko = 1;
					} else { print "<option value='$row[2]'>$row[1]</option>";
						$flag = $row[0];
						$ko = 1;
					}

					}
			mysql_close($connection);
			?>
            </select>
				
				</div></div></div>
				<div class='row'><div class='col-md-2'><div class='block1'><label >Кол-во: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' id='kol' name='kol'></div></div></div>
				<div class='row'><div class='col-md-2'><div class='block1'><label >Размер:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' id='size' name='size'></div></div></div>
				<div class='row'><div class='col-md-2'><div class='block1'><label >Цена с НДС:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' id='price' name='price'></div></div></div>
				<?php
					print "<div class='row'><div class='col-md-2'><div class='block1'><input type='hidden' value='".$_GET['id_acct']."' id = 'id_stock' name='id_stock'></div></div></div>";
				?>
				<div class='row'><div class='col-md-2'><div class='block1'><input type="submit" class="btn btn-default" value="Создать" style="margin: 10px"></div></div></div>
			</form> 
        </div>
    </div>

</body>
</html>
