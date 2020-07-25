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
 <a href="../stock.php">
          <button type="button" class="btn btn-default"><i class="fa fa-reply fa-fw"></i> СКЛАД</button></a>
    <div class='container'>
        <div class='masthead'>
        	<?php
			$price = 0;
			$total = 0;
        		$idAcct = $_GET['id'];
				if($idAcct != ''){
				$query = "select * from stock where  id = ".$idAcct;
				include "../db.php";
				$result = mysql_query($query) or die("Query failed");
				while($row = mysql_fetch_row($result)){
					print "<h3 class='text-muted'>Номер № $row[2]</h3><br/>";
					print "<div class='row'><div class='col-md-2'><div class='block1'><h4 class='text-muted'>Дата: $row[1]</h4></div></div><div class='col-md-6'><div class='block2'><h4 class='text-muted'>Поставщик: $row[3]</h4></div></div></div>";
			
					print "<hr/><a onClick='_openstock($row[0])'><button type='button' class='btn btn-default'><span class='glyphicon glyphicon-plus-sign'></span> 
					Добавить</button></a></div><br/>";
					print "<table class='table  table-bordered' ><thead>
					<tr><td>Наименование</td><td>Размер</td><td>Кол-во</td><td>Стоимость</td><td></td>					
					</tr></thead>";
				$query="select s.id , (select m.M_NAME from material_attr m where m.id = s.name) name, s.TOTAL, s.PRICE,s.SIZE from stock_attr  s where s.STOCK_ID = ".$idAcct;
				$result = mysql_query($query) or die("Query failed");
				print "<tbody>";
				while ($row = mysql_fetch_row($result)) { 
				$sum = str_replace(',', '.', $row[3]);
						print "<tr class='odd gradeX'>";
						print "<td>$row[1]</td>";
						print "<td>$row[4]</td>";
						print "<td>$row[2]</td>";
						print "<td>".$sum."</td>";
						print "<td><a onClick=''><button type= 'button' class='btn btn-default'><span class='glyphicon glyphicon-pencil'></span></button></a> 
						<a onClick=''><button type= 'button' class='btn btn-default'><span class='glyphicon glyphicon-trash'></span></button></a></td></tr>";
						$price = $price + (double)$sum ;
						$total =  $total  + $row[2];
					}
					
						
					$query = "select sum(summ) from order_product where order_id =".$idAcct;
					$result = mysql_query($query) or die("Query failed");
					if($row = mysql_fetch_row($result)){
					print "<tr class='odd gradeX'>	
						<td colspan='1'>Итого:</td>
						<td colspan='1' >".$total." </td>
						<td colspan='1' >". $price." </td>";
					}
					
					print "</tr></tbody></table>"; 
					
					
				}
				} else echo "<h3 class='text-danger'><i class='fa fa-warning fa-fw'></i> Не выбрана накладная </h3>";
				
				
				
				
					mysql_close($connection);
        	?>
        	
				
					
			
        </div>
    </div>

</body>
</html>
