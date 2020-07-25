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
    <title></title>

   <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../vendor/bootstrap/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
   <script src="../../vendor/bootstrap/js/bootstrap.min.js" type = "text/javascript"></script>
	  <script src="../../vendor/bootstrap/js/bootstrap-select.min.js" type = "text/javascript"></script>
	<script src="../../vendor/jquery/jquery.min.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
<script type="text/javascript" src="../../vendor/bootstrap/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap-multiselect.css" type="text/css"/>
   <script>
//        window.onblur = function () {window.close()}
    </script>
	 <!-- Initialize the plugin: -->
<script type="text/javascript">
    $(document).ready(function() {
        $('#optgroup').multiselect({
            enableClickableOptGroups: true
        });
    });
	 
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#selOper').multiselect({
            enableClickableOptGroups: true
        });
    });
	 
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#eqFormat').multiselect({
            enableClickableOptGroups: true
        });
    });
	 
</script>
</head>
<body>

<!-- Build your select: -->

    <div class='container'>
        <div class='masthead'>
            <h3 class='text-muted'>Добавление обордование</h3><hr/>
            <form  class='form-signin' method='post' action='_addEquipment.php'>
				<div class='checkbox'><label><input type='checkbox' name='offTest' id='offTest' > Подрядчик</label></div> 
				<label>Наименование: </label> <input type='text' id ='eqName' name='eqName' ><br/>    
				<label>Макс. размер: </label> <input type='text' id='eqMaxSize' name='eqMaxSize' ><br/>  
				<label>Мин. размер:</label> <input type='text' id='eqMinSize' name='eqMinSize' ><br/>  	
				<label>Используемые форматы: </label> <select id='eqFormat' data-style="btn-default" multiple name='eqFormat[]' >
					<?php
						include "../db.php";
						$query="select o.id, o.size from size_print o ";
						$result = mysql_query($query) or die($query);
						while ($row = mysql_fetch_row($result))
						{ 
						
								print "<option value='$row[0]'>".$row[1]."</option>"; 
							
							 
						}
						?>
				
				</select>	
				<br/>  
				<label>Ед. изм.:</label> <input type='text' id='eqUnit' name='eqUnit' ><br/>  
				<label>Кол-во операций, мин. (час): </label> <input type='text' id='eqKolOper' name='eqKolOper' ><br/>  
				<label>Время приладки, мин. (час):</label> <input type='text' id='eqMakeReadyTime' name='eqMakeReadyTime' ><br/>  
				<label>Аммортизация оборудования: </label> <input type='text' id='eqAmmor' name='eqAmmor' ><br/>  
				<label>Срок аммортизации, мес.:</label> <input type='text' id='eqSrokAmmor' name='eqSrokAmmor' ><br/> 
				<label>Стоимость оборудования: </label> <input type='text' id='eqPriceOper' name='eqPriceOper' ><br/>  
				<label>Занимаемая площадь, м.кв.:</label> <input type='text' id='eqSq' name='eqSq' ><br/> 	
				<label>Стоимость аренды, за кв.м.: </label> <input type='text' id='eqPriceArenda' name='eqPriceArenda' ><br/>  
				<label>Отступ справо и слево:</label> <input type='text' size = '5' name='ladnr' id = 'ladnr' value = '0' ><br/>
				<label>Отступ сверху и снизу:</label> <input type='text' size = '5' name='uandd' id = 'uandd' value = '0' ><br/>
				<label>Надбавка max %:</label> <input type='text' size = '5' name='nadb_max' id = 'nadb_max' value = '0' ><br/>
				<label>Кол-во min:</label> <input type='text' size = '5' name='total_min' id = 'total_min' value = '0' ><br/>
				<label>Надбавка min %:</label> <input type='text' size = '5' name='nadb_min' id = 'nadb_min' value = '0' ><br/>
				<label>Кол-во max:</label> <input type='text' size = '5' name='total_max' id = 'total_max' value = '0' ><br/>
				<label>
				Операция
				<select  id = 'selOper'  data-style="btn-default" multiple  name='selOper[]'>
				
					<?php
						include "../db.php";
						$query="select o.id, o.operation_name, o.par, o.comments  from operations o where operation_name  is not null ORDER BY operation_name ";
						$result = mysql_query($query) or die($query);
						while ($row = mysql_fetch_row($result))
						{ 
							if($row[3] != ""){
								print "<option value='$row[0]'>".$row[1]." ".$row[2]."(".$row[3].")"."</option>"; 
							}else {
								print "<option value='$row[0]'>".$row[1]." ".$row[2]."</option>"; 
							}
							 
						}
						?>
				</select>	
				</label>
				
					 
					 			<div class='row'><div class='col-md-1'><div class='block1'><label>Материалы</label></div></div><div class='col-md-6'><div class='block2'> 
		<select  id = 'optgroup'  data-style="btn-default" multiple  name='optgroup[]' style='height: 300px;width 300px'>
		<?php
					//$query="select MT_TYPE, ma.M_NAME, ma.ID,ma.M_PRICE from material m, material_attr ma where ma.ID_M = m.ID and ma.M_NAME is not null and ma.M_SIZE <> '720*1040' and ma.M_SIZE <> '650*940' and
// ma.M_SIZE <> '470*650' and ma.M_SIZE <> '700*1000' and ma.M_SIZE <> '720*1020';";
					$query="select MT_TYPE, ma.M_NAME, ma.ID,ma.M_PRICE from material m, material_attr ma where ma.ID_M = m.ID and ma.M_NAME is not null;";
					$result = mysql_query($query) or die($query);
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
		   
		  </select> </br>
			
			</div></div></div>
	
			<br/><br/>
					 
					 
                <button type='button' class="btn btn-default" onclick = "tabWalker('dynamic','eqName','eqMaxSize','eqMinSize','eqFormat','eqUnit','eqKolOper','eqMakeReadyTime','eqAmmor','eqSrokAmmor','eqPriceOper','eqSq','eqPriceArenda')" />Добавить </button></a>
            </form> 




<script>
function addOper (tableID,selOper) 								 // функция для добавления строки к части шаблона
{
	var tbl = document.getElementById (tableID);                   // таблица, с которой работаем
	var rws = tbl.rows;                                            // коллекция существующих строк таблицы
	var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
	var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
	//console.log(cls);
	var ro = tbl.insertRow (-1);                                   // добавляем снизу ОБРАЗ ещё одной строки

	
	var selOper = document.getElementById(selOper).value;
	

	
	var ce = ro.insertCell (-1);
	ce.innerHTML = "<input type='checkbox' name='chDel' value='1'>";
	

	ce = ro.insertCell (-1);
	ce.innerHTML = selOper;

	

	
	document.getElementById('selOper').selectedIndex='';
	
	// по завершению цикла, когда ВСЕ ячейки получат содержимое, ОБРАЗ добавленной строки превратится в РЕАЛЬНУЮ строку
}
</script>			

<script>
	function del_row() //удаление строк
	{
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
</script>

<script >
      
		function tabWalker(tableID,eqName,eqMaxSize,eqMinSize,eqFormat,eqUnit,eqKolOper,eqMakeReadyTime,eqAmmor,eqSrokAmmor,eqPriceOper,eqSq,eqPriceArenda) 				//функция построчного чтения таблицы, с последующим добавлением в базу
		{
			var str = "";
			
			var t=0;
			var eqName=document.getElementById(eqName).value;
			var eqMaxSize=document.getElementById(eqMaxSize).value;
			var eqMinSize=document.getElementById(eqMinSize).value;
			//var eqFormat=document.getElementById(eqFormat).value;
			var eqUnit=document.getElementById(eqUnit).value;
			var eqKolOper=document.getElementById(eqKolOper).value;
			var eqMakeReadyTime=document.getElementById(eqMakeReadyTime).value;
			var eqAmmor=document.getElementById(eqAmmor).value;
			var eqSrokAmmor=document.getElementById(eqSrokAmmor).value;
			var eqPriceOper=document.getElementById(eqPriceOper).value;
			var eqSq=document.getElementById(eqSq).value;
			var eqPriceArenda=document.getElementById(eqPriceArenda).value;
			var uandd=document.getElementById('uandd').value;
			var ladnr=document.getElementById('ladnr').value;
			var nadb_max=document.getElementById('nadb_max').value;
			var total_min=document.getElementById('total_min').value;
			var nadb_min=document.getElementById('nadb_min').value;
			var total_max=document.getElementById('total_max').value;
			
			var optgroup   = document.getElementById('optgroup');
			var selMat 		= optgroup.options.length;
							
			var SelectElements = new Array;
			var y = 0;

			for (var n = 0; n < selMat; n++){
				if (optgroup.options[n].selected==true){
					SelectElements[y]=optgroup.options[n].value;
					y++;
				}
			}
			current = SelectElements;			
				
	
	
				var optgroup1   = document.getElementById('selOper');
			var selMat1 		= optgroup1.options.length;
							
			var SelectElements2 = new Array;
			var y = 0;

			for (var n = 0; n < selMat1; n++){
				if (optgroup1.options[n].selected==true){
					SelectElements2[y]=optgroup1.options[n].value;
					y++;
				}
			}
			current2 = SelectElements2;


var optgroup1   = document.getElementById('eqFormat');
			var selMat1 		= optgroup1.options.length;
							
			var SelectElements2 = new Array;
			var y = 0;

			for (var n = 0; n < selMat1; n++){
				if (optgroup1.options[n].selected==true){
					SelectElements2[y]=optgroup1.options[n].value;
					y++;
				}
			}
			eqFormat = SelectElements2;						

		 offTest = 0;
		 if(document.getElementById('offTest').checked){
					 offTest = 1;
		 }

			var post= '_addEquipment.php?eqName='+eqName+'&eqMaxSize='+eqMaxSize+'&eqMinSize='+eqMinSize+'&eqFormat='+eqFormat+'&eqUnit='+eqUnit+'&eqKolOper='+eqKolOper+'&eqMakeReadyTime='+eqMakeReadyTime+'&eqAmmor='+eqAmmor+'&eqSrokAmmor='+eqSrokAmmor+'&eqPriceOper='+eqPriceOper+'&offTest='+offTest+'&eqSq='+eqSq+'&eqPriceArenda='+eqPriceArenda+'&ladnr='+ladnr+'&uandd='+uandd+'&eqStr='+current2+'&current='+current+'&nadb_max='+nadb_max+'&total_min='+total_min+'&nadb_min='+nadb_min+'&total_max='+total_max;
			window.location.href = post;
		}
		//window.onload=tabWalker;
     
  </script>

</body>
</html>
