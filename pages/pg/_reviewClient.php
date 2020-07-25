
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
        <!-- Bootstrap core CSS -->
		      <script src="../../vendor/jquery/jquery.js"></script>
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
	<script src="../../js/jquery.maskedinput-1.2.2.js"></script>
    <script src="../../js/funJs.js"></script>
   <script>
        // window.onblur = function () {window.close()}
    </script>
  </head>
  <body>

<?php
$sum = 0;
 if (!empty($_GET["id_client"])) 
 { 
    include_once '../db.php';
    $query="select * from clients where id =".$_GET["id_client"];
    $result = mysql_query($query) or die("Query failed");
    while ($line = mysql_fetch_row($result)) { 
        if ($line[3] == 'u'){
            print "<div class='container'>";
            print "<div class='masthead'>";
            print "<h3 class='text-muted'>Юридическое лицо</h3><hr/>";
			 $query1 = "select TRUNCATE(sum(ost_sum),2), TRUNCATE(sum(all_sum),2) from oplati where client_id =" .$_GET["id_client"];
			 $result1 = mysql_query($query1) or die("Query failed");
			 while ($row = mysql_fetch_row($result1)) { 
			 $sum = $row[1] - $row[0];
			 }
			
			print "<h4 class='text-muted'>Баланс: ".$sum." руб.</h3><hr/>";
            print "<form  class='form-signin' method='post' action='_updateClient.php'>";
            print "<div class='row'><div class='col-md-2'><div class='block1'><label >ФИО:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientName1' value='$line[1]'></div></div></div>";  
            print "<div class='row'><div class='col-md-2'><div class='block1'><label >Email: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientEmail1' value='$line[2]'></div></div></div>";
            print "<div class='row'><div class='col-md-2'><div class='block1'><label >Город. телефон:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientPhoneCity1' value='$line[4]'></div></div></div>"; 
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Моб. телефон:</label></div></div><div class='col-md-2'><div class='block1'><input type='text'  size = 60 id='clientPhoneMob1' value='$line[5]'></div></div></div>";
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Почтовый адрес:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientAddressPost1' value='$line[8]'></div></div></div>";
				print "<div class='row'><div class='col-md-2'><div class='block1'><label>ФИО директора(р.п.):</label></div></div><div class='col-md-4'><div class='block1'><input type='text' id='fi3o' name='fi3o' size = 60 placeholder='Иванова Ивана Ивановича' value='$line[20]'></div></div></div>";
				print "<div class='row'><div class='col-md-2'><div class='block1'><label>ФИО директора:</label></div></div><div class='col-md-4'><div class='block1'><input type='text' id='fi3o1' size = 60 name='fi3o1' value='$line[22]'></div></div></div>";
				print "<div class='row'><div class='col-md-2'><div class='block1'><label>Основании:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='osnov' name='osnov' value='$line[21]'></div></div></div>";
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Адрес доставки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientAddressDev1' value='$line[9]'></div></div></div>";
		      $line19 = (substr($line[19] ,0 , strrpos($line[19] , 'от') - 1));
				$line19_1 = (substr($line[19] ,strrpos($line[19] , ' ') + 1));
				IF ($line19_1 != ''){
					$date_beg = new DateTime($line19_1) ;
					$date_rr = $date_beg->format('Y-m-d');
				}
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Договора №:</label></div></div><div class='col-md-2'><div class='block1'><input type='text'  size = 12 id='num_doc1' 
				value='".$line19."'></div></div>
				<div class='col-md-1'><div class='block1'><label>от:</label></div></div><div class='col-md-2'><div class='block1'><input type='date'  id='num_doc_date' ></div></div></div>";
				 $line24 = (substr($line[24] ,0 , strrpos($line[24] , 'от') - 1));
				$line24_1 = (substr($line[24] ,strrpos($line[24] , ' ') + 1));
				IF ($line24_1 != ''){
					$date_begf = new DateTime($line24_1) ;
					$date_rrr = $date_begf->format('Y-m-d');
				}
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Довереность №:</label></div></div><div class='col-md-2'><div class='block1'><input type='text'  size = 12 id='num_dov1' 
				value='".$line24."'></div></div>
				<div class='col-md-1'><div class='block1'><label>от:</label></div></div><div class='col-md-2'><div class='block1'><input type='date'  id='num_dov_date' ></div></div></div>";
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>УНП: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientUnp1' value='$line[10]'></div></div></div>";
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>р/с: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientAcct1' value='$line[11]'></div></div></div>";
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Банк: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientBank1' value='$line[12]'></div></div></div>";
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Код банка: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientCodeBank1' value='$line[13]'></div></div></div>"; 
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>Категория:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientNadb1' value='$line[14]'></div></div></div>  "	;
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>Лимит:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientLim1' value='$line[15]'></div></div></div>  ";
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>Время рассрочки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientTime1' value='$line[16]'></div></div></div> ";
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>Размер предоплаты:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientSize1' value='$line[17]'></div></div></div>  ";
						print "	<div class='row-fluid'>
					<div class='col-md-2'><div class='block1'><input type='button' class='btn btn-default' value='Добавить' style='margin: 10px' onclick='addRow()'></div></div>
					<div class='col-md-1'><div class='block1'><input type='button' class='btn btn-default' value='Удалить' style='margin: 10px' onclick='del_row()'></div></div>
				</div>";

				print '	<table name = "clientType" id = "dynamic" class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th scope="col">Del <i class="glyphicon glyphicon-trash"></i> </th>
								<th scope="col">ФИО</th>
								<th scope="col">Email</th>
								<th scope="col">Город. телефон</th>
								<th scope="col">Моб. телефон</th>
								<th scope="col">Skype</th>
								<th scope="col">Viber</th>
							</tr>
						</thead>
						<tbody>';
							$line_1 = explode("!", $line[18]);
							$kol = 0;
						FOR ($x = 0; $x < count($line_1); $x++){
							$kol = $kol + 1;
							$line_2 = explode("$", 	$line_1[$x]);
							echo "<tr>";
							echo "<td><input type='checkbox' name='chDel' value='1'></td>";
							echo "<td><input name='fio" .$kol . "' id = 'fio" . $kol . "' type='text' value='".$line_2[0]."' size = '25'/></td>";
							echo "<td><input name='email" . $kol . "' id = 'email" . $kol . "' type='text' size = '15' value='".$line_2[1]."'/></td>";
							echo "<td><input name='city" . $kol . "' id = 'city" . $kol. "' type='text' size = '7' value='".$line_2[2]."'/></td>";
							echo "<td><input name='mob" . $kol . "' id = 'mob" . $kol . "' type='text' size = '9' value='".$line_2[3]."'/></td>";
							echo "<td><input name='Skype" . $kol . "' id = 'Skype" . $kol . "' type='text' size = '10' value='".$line_2[4]."'/></td>";
							echo "<td><input name='Viber" . $kol . "' id = 'Viber" . $kol . "' type='text' size = '10' value='".$line_2[5]."'/></td>";
							echo "</tr>";
						}
					print '</tbody>
					</table>		
				<div class="row"><div class="col-md-2"><div class="block1">';
            print "<input type='hidden' value='u' id='clientType1'> <br/>";
            print "<input type='hidden' value='$line[0]' id='clientId1'> <br/>";
           print "<input type='button' onclick='edit1()' value='Изменить' />
			  			<input name='kol' id = 'kol' type='hidden' value = '".$kol."'/> ";
            print "</form>";
            print "</div>";
            print "</div>";
        }
        if ($line[3] == 'f'){
            print "<div class='container'>";
            print "<div class='masthead'>";
            print "<h3 class='text-muted'>Физическое лицо</h3><hr/>";
			 $query1 = "select TRUNCATE(sum(ost_sum),2), TRUNCATE(sum(all_sum),2) from oplati where client_id =" .$_GET["id_client"];
			 $result1 = mysql_query($query1) or die("Query failed");
			 while ($row = mysql_fetch_row($result)) { 
			 $sum = $row[1] - $row[0];
			 }
			
			print "<h4 class='text-muted'>Баланс: ".$sum." руб.</h3><hr/>";
            print "<form  class='form-signin' method='post' action='_updateClient.php'>"; 
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Наименование:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientName' value='$line[1]'></div></div></div>";       
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Email: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientEmail' value='$line[2]'></div></div></div>";     
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Город. телефон:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientPhoneCity' value='$line[4]'></div></div></div>";    
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Моб. телефон:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientPhoneMob' value='$line[5]'></div></div></div>";    
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Skype: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientSkype' value='$line[6]'></div></div></div>";       
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Viber: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientViber' value='$line[7]'></div></div></div>";  
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Почтовый адрес:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientAddressPost' value='$line[8]'></div></div></div>";   
            print "<div class='row'><div class='col-md-2'><div class='block1'><label>Адрес доставки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientAddressDev' value='$line[9]'></div></div></div>";    
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>Категория:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientNadb' value='$line[14]'></div></div></div>  "	;
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>Лимит:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientLim' value='$line[15]'></div></div></div>  ";
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>Время рассрочки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientTime' value='$line[16]'></div></div></div> ";
			print "<div class='row'><div class='col-md-2'><div class='block1'><label>Размер предоплаты:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' size = 60 id='clientSize' value='$line[17]'></div></div></div>  ";

            print "<input type='hidden' value='f' id='clientType'><br/>";
            print "<input type='hidden' value='$line[0]' id='clientId'> <br/>";
            print "<input type='button' onclick='edit()' value='Изменить' />";
            print "</form>";
            print "</div>";
            print "</div>";
				
        }
    }   
mysql_close($connection);

 } 

?>

<script type="text/javascript">

jQuery(function($) {

$.mask.definitions['~']='[+-]';

$('#clientPhoneCity').mask('(99999) 999999');
$('#clientPhoneMob').mask('(9999) 9999999');
$('#clientPhoneCity1').mask('(99999) 999999');
$('#clientPhoneMob1').mask('(9999) 9999999');
$('#clientUnp1').mask('999999999');
// $('#clientAcct1').mask('9999999999999');

// $('#clientCodeBank1').mask('999');
});</script> 
<script>
function addRow(){
	var tbl = document.getElementById ('dynamic');                   // таблица, с которой работаем
			var rws = tbl.rows;                                            // коллекция существующих строк таблицы
			var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
			var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
			//console.log(cls);
			var ro = tbl.insertRow (-1);                                   // добавляем снизу ОБРАЗ ещё одной строки
			
			var kol = document.getElementById ('kol').value; 
			kol = Number(kol) + 1;
			document.getElementById ('kol').value = kol;
			
			var ce = ro.insertCell (-1);
			ce.innerHTML = "<input type='checkbox' name='chDel' value='1'>";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input name='fio" + kol + "' id = 'fio" + kol + "' type='text' value='' size = '25'/>";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input name='email" + kol + "' id = 'email" + kol + "' type='text' size = '15' />";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input name='city" + kol + "' id = 'city" + kol + "' type='text' size = '7' />";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input name='mob" + kol + "' id = 'mob" + kol + "' type='text' size = '9' />";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input name='Skype" + kol + "' id = 'Skype" + kol + "' type='text' size = '10' />";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input name='Viber" + kol + "' id = 'Viber" + kol + "' type='text' size = '10' />";
			
			
			}

function del_row(){
	
	var nodeList = document.getElementsByName('chDel');
			var array = Array.prototype.slice.call(nodeList);
			for (var i = 0; i < array.length; i++) {
				if (array[i].checked) {
					var tr = array[i].parentNode.parentNode;
					var parent = tr.parentNode
					parent.removeChild(tr);
				}
			}
}

function edit(){

	var id_AcctProd = '_updateClient.php?clientType='+document.getElementById('clientType').value+'&clientName='+document.getElementById('clientName').value+'&clientEmail='+document.getElementById('clientEmail').value+'&clientPhoneMob='+document.getElementById('clientPhoneMob').value+'&clientPhoneCity='+document.getElementById('clientPhoneCity').value+'&clientAddressPost='+document.getElementById('clientAddressPost').value+'&clientAddressDev='+document.getElementById('clientAddressDev').value+'&clientNadb='+document.getElementById('clientNadb').value+'&clientLim='+document.getElementById('clientLim').value+'&clientTime='+document.getElementById('clientTime').value+'&clientSize='+document.getElementById('clientSize').value+'&clientSkype='+document.getElementById('clientSkype').value+'&clientViber='+document.getElementById('clientViber').value+'&clientId='+document.getElementById('clientId').value;

window.location.href = id_AcctProd;		
	}
	function edit1(){
	var kol = 1;
	var current;
	var str = "";
	var tbl = document.getElementById ('dynamic');                   // таблица, с которой работаем
	var rws = tbl.rows;                                            // коллекция существующих строк таблицы
	var lst = rws [rws.length - 1]; 
	var cls = lst.cells.length; 
	
	/*for (var i=2; i<rws.length; i++) 							//цикл по всем строкам
				{*/	
				
	for (var t = kol ; t <= document.getElementById('kol').value; t ++ ){
		
		current = document.getElementById('fio' + kol).value;
		str = str + current;
		str = str.concat("$");	
			
		current = document.getElementById('email' + kol).value;
		str = str + current;
		str = str.concat("$");	
			
		current = document.getElementById('city' + kol).value;
		str = str + current;
		str = str.concat("$");	
			
		current = document.getElementById('mob' + kol).value;
		str = str + current;
		str = str.concat("$");	
		
		current = document.getElementById('Skype' + kol).value;
		str = str + current;
		str = str.concat("$");	
		
		current = document.getElementById('Viber' + kol).value;
		str = str + current;
		str = str.concat("!");
			
		kol++;
		
	}
	/*		
}*/
	str = str.substring(0, str.length-1);

	 dateObj = new Date(document.getElementById('num_doc_date').value)
	 dateObj1 = 	String(('0' + dateObj.getDate()).slice(-2)) + "." + String( ('0' + (dateObj.getMonth() + 1)).slice(-2)) + "." + String(dateObj.getFullYear()) 
	if (document.getElementById('num_doc1').value != ''){
		num_doc = document.getElementById('num_doc1').value + " от " +  dateObj1 ;
	}
	else num_doc =  '';
	
	dateObj2 = new Date(document.getElementById('num_dov_date').value)
	dateObj12 = 	String(('0' + dateObj2.getDate()).slice(-2)) + "." + String( ('0' + (dateObj2.getMonth() + 1)).slice(-2)) + "." + String(dateObj2.getFullYear()) 
	str = str.substring(0, str.length-1);
	if (document.getElementById('num_dov1').value != ''){
		num_dov = document.getElementById('num_dov1').value + " от " +  dateObj12 ;
	}
	else num_dov =  '';	

	var id_AcctProd = '_updateClient.php?clientType='+document.getElementById('clientType1').value+'&clientName='+document.getElementById('clientName1').value+'&clientEmail='+document.getElementById('clientEmail1').value+'&clientPhoneMob='+document.getElementById('clientPhoneMob1').value+'&clientPhoneCity='+document.getElementById('clientPhoneCity1').value+'&clientAddressPost='+document.getElementById('clientAddressPost1').value+'&clientAddressDev='+document.getElementById('clientAddressDev1').value+'&clientNadb='+document.getElementById('clientNadb1').value+'&clientLim='+document.getElementById('clientLim1').value+'&clientTime='+document.getElementById('clientTime1').value+'&clientSize='+document.getElementById('clientSize1').value+'&clientUnp='+document.getElementById('clientUnp1').value+'&clientAcct='+document.getElementById('clientAcct1').value+'&clientBank='+document.getElementById('clientBank1').value+'&clientCodeBank='+document.getElementById('clientCodeBank1').value+'&str='+str+'&num_doc='+num_doc+'&osnov='+document.getElementById('osnov').value+'&fio='+document.getElementById('fi3o').value+'&fio1='+document.getElementById('fi3o1').value+'&clientId='+document.getElementById('clientId1').value+'&num_dov='+num_dov;
;

window.location.href = id_AcctProd;	
	
	}
	

			
</script>
<script>
$(document).ready(function(){
	if(document.getElementById('num_doc1').value != ""){
		$("#num_doc_date").val('<? echo $date_rr?>');
	}
	if(document.getElementById('num_dov1').value != ""){
		$("#num_dov_date").val('<? echo $date_rrr?>');
	}
});
</script>

  </body>
</html>
