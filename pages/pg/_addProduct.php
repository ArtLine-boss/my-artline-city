<?php
include '../firewall.php';
?>

<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <title>Добавление нового клиента</title>
<link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../vendor/bootstrap/css/bootstrap.css" rel="stylesheet">
	
    <script src="../../vendor/bootstrap/js/bootstrap.min.js" type = "text/javascript"></script>
    <script src="../../vendor/jquery/jquery.min.js"></script>
	<script src="../../vendor/jquery/chosen.jquery.min.js"></script>
    <script src="../../js/funJs.js"></script>
	<script src="../../vendor/jquery/jquery.min.js"></script>
	 <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="jquery.multi-select.js"></script>

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.7.5/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="../../vendor/bootstrap/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="../../vendor/bootstrap/css/bootstrap-multiselect.css" type="text/css"/>

</head>

 <script type="text/javascript">
    function ee(id){
		$(document).ready(function() {
        $('#'+id).multiselect({
            enableClickableOptGroups: true,
				 buttonClass: 'btn-default btn-xs',
				 numberDisplayed: 0,
				 buttonWidth: '100px'
        });
    });
	 }
	</script>
<body>




        <div class='masthead'>
            <h3 class='text-muted'>Добавление продукта</h3><br/>
		
              <div class='row'><div class='col-md-1'><div class='block1'><label>Наименование:</label></div></div><div class='col-md-2'><div class='block2'> <input type='text' size = '100' name='productName' id = 'productName' ></div></div></div>    
               <div class='row'><div class='col-md-1'><div class='block1'><label>Размер: </label></div></div><div class='col-md-2'><div class='block2'> <input type='text' name='' id ='productSize' ></div></div></div> 
					<div class='row'><div class='col-md-1'><div class='block1'>
			  <label>Сшивка: </label></div></div><div class='col-md-2'><div class='block2'>
			<input type="checkbox" name="selSh1" id = "selSh1" value="0" >
		</div></div></div> 
			   <div class='row'><div class='col-md-1'><div class='block1'><label>Сшивка: </label></div></div><div class='col-md-2'><div class='block2'>
				 
			  <select name='selSh' id='selSh' >
				<option value='0'></option>
				<option value='1'>по короткой стороне</option>	
				<option value='2'>по длинной стороне</option>
			</select></div></div></div> 
              <div class='row'><div class='col-md-1'><div class='block1'>
			  <label>Сшивка на скобу: </label></div></div><div class='col-md-2'><div class='block2'>
			<input type="checkbox" name="skoba" id = "skoba" value="0" >
		</div></div></div> 
		
		<hr>





<div id='Label1' style='display: none;'>



		<select name='selOper' id='selOper' >
			<option value=''></option>
				<?php
					include "../db.php";
					$query="select distinct operation_name from operations where operation_name is not null ORDER BY operation_name";
					$result = mysql_query($query) or die("Query failed");
					while ($row = mysql_fetch_row($result))
					{ 
						print "<option value='$row[0]'>$row[0]</option>"; 
					}
	
						/*$query="select operation_name, PAR, ID from operations where operation_name is not null ORDER BY operation_name";
					$result = mysql_query($query) or die("Query failed");
					while ($row = mysql_fetch_row($result))
					{ 
						print "<option value='i$row[2]'>$row[0] - $row[1]</option>"; 
					}
					*/
				?>
			
		 </select>	</br>	
					  <select  name='kol_str[]' multiple='multiple' id='kol_str'>
		<?php
				
					$query="select ID, STAMP_TYPE , STAMP_NAME, STAMP_KOL from stamps WHERE STAMP_NAME  <> '' ORDER BY STAMP_TYPE";
					$result = mysql_query($query) or die("Query failed");
					$flag = '';
								
					$ko = 0;
					while ($row = mysql_fetch_row($result))
					{ 
						
					if ($flag != $row[1]){
						
						if ($ko != 0) {
							print " </optgroup>";
						
						}
						$flag = $row[1];
						print "
							<optgroup label='$row[1]' >
						<option value='$row[0]'>&nbsp;&nbsp;&nbsp;$row[2]</option>"; 
						$ko = 1;
					} else {
						print "<option value='$row[0]'>&nbsp;&nbsp;&nbsp;$row[2]</option>"; 
						$flag = $row[1];
						$ko = 1;
						}
					}
				?>
			</select>	
		  
	</br>
		<select id='selEq' multiple='multiple' name='selEq[]' style='height: 100px;'>
				<?php
					//$query="select id, eq_name from equipment where eq_name is not null;";
					$query = "select DISTINCT o.OPERATION_NAME, e.eq_name, e.id from operations o, (select * from equipment e) e where FIND_IN_SET(o.id ,e.oper)";
					$result = mysql_query($query) or die($query);
					
					while ($row = mysql_fetch_row($result))
					{ 					
						echo "<option id = '$row[0]' value='$row[2]' ".' style="display:block;">'."$row[1]</option>"; 
					}

		   ?>
		   
		  </select> </br>
	<select id='optgroup' multiple='multiple' name='optgroup[]' style='height: 200px;'>
		<?php
					$query="select MT_TYPE, ma.M_NAME, ma.ID,ma.M_PRICE from material m, material_attr ma where ma.ID_M = m.ID and ma.M_NAME is not null and ma.M_SIZE <> '720*1040' and ma.M_SIZE <> '650*940' and
ma.M_SIZE <> '470*650' and ma.M_SIZE <> '700*1000' and ma.M_SIZE <> '720*1020';";
				/*$query="select  ma.MT_TYPE, ma.M_NAME, ma.ID,ma.M_PRICE , eq.id from equipment eq, 
				(select m.MT_TYPE, ma.M_NAME, ma.ID,ma.M_PRICE from material m, material_attr ma where ma.ID_M = m.ID and ma.M_NAME is not null and ma.M_SIZE <> '720*1040' 
				and ma.M_SIZE <> '650*940' and ma.M_SIZE <> '470*650' and ma.M_SIZE <> '700*1000') ma where FIND_IN_SET(ma.id ,eq.mater);";*/
					$result = mysql_query($query) or die($query);
					$flag = '';
						$ko = 0;
					while ($row = mysql_fetch_row($result))
					{ 
						
					if ($flag != $row[0]){
						if ($ko != 0) {
							echo " </optgroup>";
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
	
		  
<input name='kol' id = 'kol' type='text' size='10' value = '0'/> </br>
</div>



		<input name="sub" type="submit" class="btn btn-default" value="Добавить часть" style="margin: 10px" onclick="addRows()">
		<input name="sub" type="submit" class="btn btn-default" value="Добавить операцию" style="margin: 10px" onclick="addRow()">
		<input name="sub" type="submit" class="btn btn-default" value="Удалить" style="margin: 10px" onclick="del_row()">

	<table  id = "dynamic" class="table table-bordered ">
        <thead>
            <tr>
				<th scope="col"></th>
				<th scope="col"><h6>Del <i class='glyphicon glyphicon-trash'></i> </th>
            <th scope="col"><h6>Менеджер&nbsp; <i class='glyphicon glyphicon-sunglasses'></i></th>
            <th scope="col"><h6>Операция</th>
            <th scope="col"><h6><i class='glyphicon glyphicon-eye-open'></i></th>
            <th scope="col"><h6>Кол-во операций</th>
				<th scope="col"><h6><i class='glyphicon glyphicon-eye-open'></i> </th>
				<th scope="col"><h6>Оборудование</th>
				<th scope="col"><h6><i class='glyphicon glyphicon-eye-open'></i></th>
				<th scope="col"><h6>Тип материала</th>
            <th scope="col"><h6>Материал</th>
				<th scope="col"><h6><i class='glyphicon glyphicon-eye-open'></i></th>
				<th scope="col"><h6>Кол-во мат.</th>
				<th scope="col"><h6><i class='glyphicon glyphicon-eye-open'></i></th>
				<th scope="col"><h6>Кол-во кол-во стр.</th>
				<th scope="col"><h6><i class='glyphicon glyphicon-eye-open'></i></th>
				<th scope="col"><h6>Размер изделия</th>
				<th scope="col"><h6><i class='glyphicon glyphicon-eye-open'></i></th>
				<th scope="col"><h6>Штамп</th>
				
			</tr>
		</thead>
		<tbody>
			<tr>
	
			</tr>
        </tbody>
        
    </table>

	</br>
	<button type="button" class="btn btn-default" onclick="tabWalker()">Добавить шаблон </button></a><br/><br/>



        </div>

<script>
	function addRows() // функция для добавления части шаблона
	{
		var tbl = document.getElementById ('dynamic');                   // таблица, с которой работаем
		var rws = tbl.rows;                                            // коллекция существующих строк таблицы
		var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
		var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
				
		var kol = document.getElementById ('kol').value; 
		kol = Number(kol) + 1;
		document.getElementById ('kol').value = kol;
		
		var ro = tbl.insertRow (-1); 
		
		
		var ce = ro.insertCell (-1);
			ce.innerHTML = kol;
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input type='checkbox' name='chDel' value='1'>";
			
			
		if (cls == 0)
		{
			
				ce = ro.insertCell (-1);
				tbl.rows[rws.length-1].cells[2].colSpan=17;
				ce.innerHTML ="<h6><input name='textShab' id = 'textShab" + kol + "' type='text' size='160'/>";
			
		/*
			for (var i = 3; i<18; i++)
			{
				var del = tbl.rows[rws.length-1]
				del.deleteCell(-1);
			}*/
			
			
		}
		else
		{
			
				ce = ro.insertCell (-1);
				tbl.rows[rws.length-1].cells[2].colSpan=17;
				ce.innerHTML ="<h6><input name='textShab' id = 'textShab" + kol + "' type='text' size='160'/>";
			
			
			/*for (var i = 3; i<18; i++)
			{
				var del = tbl.rows[rws.length-1]
				del.deleteCell(-1);
			}
			tbl.rows[rws.length-1].cells[0].colSpan=0;*/
		}


	}
	
</script>
<script>	
	function addRow()  // функция для добавления строки к части шаблона
		{
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
			ce.innerHTML = kol;
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input type='checkbox' name='chDel' value='1'>";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input type='checkbox' name='ch1" + kol + "' id = 'ch1" + kol + "' >";

			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6 data-style=" + '"btn-default btn-xs"' + " id = 'selOper" + kol + "0' ><select style='width: 100px;'  name='selOper" + kol + "' id='selOper" + kol + "'" + ' onChange="' + "rts11('selOper" + kol + "','selEq" + kol + "', " + kol + ")" + '" ' + ">" + document.getElementById('selOper').innerHTML + "</select> <div class='btn-group'></div>" ;

			ce = ro.insertCell (-1);
			ce.innerHTML = " <input type='checkbox' name='ch2" + kol + "' id = 'ch2" + kol + "' value='1' >";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6><input name='kolOper" + kol + "' id = 'kolOper" + kol + "' type='text' value='0' size='3'/>";
			
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input type='checkbox' name='ch3" + kol + "' id = 'ch3" + kol + "' value='1' >";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6 id = 'selEq" + kol + "0'><select style='width: 100px;'   id='selEq" + kol + "' name='selEq" + kol + "[]' ></select> <div class='btn-group'></div>" ;
			

			ce = ro.insertCell (-1);
			ce.innerHTML = " <input type='checkbox' name='ch4" + kol + "' id = 'ch4" + kol + "' value='1' >";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6>0";
			
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6 id = 'optgroup" + kol + "0'><select style='width: 100px;'  id='optgroup" + kol + "' name='optgroup" + kol + "[]' " + ' onClick="' + "rts('selEq" + kol + "','optgroup" + kol + "')" + '" '+ " ></select><div class='btn-group'></div>" ;
			
			ce = ro.insertCell (-1);
			ce.innerHTML = " <input type='checkbox' name='ch5" + kol + "' id = 'ch5" + kol + "' value='1' >";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6><input name='kolMat" + kol + "' id = 'kolMat" + kol + "' type='text' value='0' size='5'/>";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = " <input type='checkbox' name='ch6" + kol + "' id = 'ch6" + kol + "' value='1' >";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6><input name='kolstr" + kol + "' id = 'kolstr" + kol + "' type='text' value='0' size='5'/>";

			ce = ro.insertCell (-1);
			ce.innerHTML = " <input type='checkbox' name='ch7" + kol + "' id = 'ch7" + kol + "' value='1' >";
			
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6><input name='chSTRSize" + kol + "' id = 'chSTRSize" + kol + "' type='text' value='0*0' size='10'/>";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<input type='checkbox' name='chRas" + kol + "' id = 'chRas" + kol + "' value='1'>";
			
			ce = ro.insertCell (-1);
			ce.innerHTML = "<h6><select data-style=" + '"btn-default btn-xs"' + " style='width: 100px;'  multiple name='kol_str[]" + kol + "'  id='kol_str" + kol + "' >" + document.getElementById('kol_str').innerHTML + "</select>" ;
			$("#selEq"+kol).attr("disabled","disabled");
			ee("kol_str" + kol );

			// по завершению цикла, когда ВСЕ ячейки получат содержимое, ОБРАЗ добавленной строки превратится в РЕАЛЬНУЮ строку
		}
</script>	
<script>	
		function del_row() 
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
<script>
		function tabWalker(){
			
			var productName=document.getElementById('productName').value;
			var productSize=document.getElementById('productSize').value;
			var selSh=document.getElementById('selSh').value;
			var skoba=document.getElementById('skoba');
			var selSh1=document.getElementById('selSh1');
			
			var kat1 = document.getElementById('kat1');
		    var kat2 = document.getElementById('kat2');
			var skoba_;
			if (skoba.checked)
				{
					skoba_ = 1;
				}
				else {
					skoba_ = 0;
				}
			if (selSh1.checked)
				{
					selSh1_ = 1;
				}
				else {
					selSh1_ = 0;
				}
			
			var current;
			var str = "";
			var tbl = document.getElementById ('dynamic');                   // таблица, с которой работаем
			var rws = tbl.rows;                                            // коллекция существующих строк таблицы
			var t=0;	
			var lst = rws [rws.length - 1]; 
			var cls = lst.cells.length; 
				
				
			for (var i=2; i<rws.length; i++) 							//цикл по всем строкам
				{	
				
				kol = rws[i].cells[0].innerText;
				
					if (rws[i].cells.length==3)
					{
						
						if (t==0)
						{
							
							current = document.getElementById ('textShab'+kol).value;
							str = str + current;
							str = str.concat("^");
								
							t++;							
						}
						else
						{	
							str = str.substring(0, str.length-1);
							str=str.concat("!")
							current = document.getElementById ('textShab'+kol).value;
							str = str + current;
							str = str.concat("^");
							
						}
						
						kol++;
						
					
						
					}
					else{
					
					
				 		if(document.getElementById('ch1' + kol).checked) {
							current = '1';
						} else {
							current = '0';
						}
						str = str + current;
						str = str.concat("$");
						
				
						current = document.getElementById('selOper' + kol).value;
						str = str + current;
						str = str.concat("$");
						if(document.getElementById('ch2' + kol).checked){
							current = '1';
						} else {
							current = '0';
						}
						
						str = str + current;
						str = str.concat("$");
		
						current = document.getElementById('kolOper' + kol).value;
						str = str + current;
						str = str.concat("$");
						
						if(document.getElementById('ch3' + kol).checked){
							current = '1';
						} else {
							current = '0';
						}
						str = str + current;
						str = str.concat("$");
							
						var optgroup   = document.getElementById('selEq' + kol);
						var selMat 		= optgroup.options.length;
							
							var SelectElements = new Array;
							var y = 0;

							for (var n = 0; n < selMat; n++)
							{
							  if (optgroup.options[n].selected==true)
							  {

							   SelectElements[y]=optgroup.options[n].value;

									 y++;
							  }

							}
						
						current = SelectElements;
						str = str + current;	
						str = str.concat("$");	
							
							
							
							/*
						current = document.getElementById('selEq' + kol).value;
						str = str + current;
						str = str.concat("$");*/
						
						if(document.getElementById('ch4' + kol).checked){
							current = '1';
						} else {
							current = '0';
						}
						str = str + current;
						str = str.concat("$");
							
						str = str + "0";
						str = str.concat("$");
						
							var optgroup   = document.getElementById('optgroup' + kol);
							var selMat 		= optgroup.options.length;
							
							var SelectElements = new Array;
							var y = 0;

							for (var n = 0; n < selMat; n++)
							{
							  if (optgroup.options[n].selected==true)
							  {

							   SelectElements[y]=optgroup.options[n].value;

									 y++;
							  }

							}
						
						current = SelectElements;
						str = str + current;
						
						str = str.concat("$");	
						
						if(document.getElementById('ch5' + kol).checked){
							current = '1';
						} else {
							current = '0';
						}
						str = str + current;
						str = str.concat("$");
							
						current = document.getElementById('kolMat' + kol).value;
						str = str + current;
						str = str.concat("$");
					
						
						if(document.getElementById('ch6' + kol).checked){
							current = '1';
						} else {
							current = '0';
						}
						str = str + current;
						str = str.concat("$");
						
						current = document.getElementById('kolstr' + kol).value;
						str = str + current;
						str = str.concat("$");
						
						if(document.getElementById('ch7' + kol).checked){
							current = '1';
						} else {
							current = '0';
						}
						str = str + current;
						str = str.concat("$");
						
						current = document.getElementById('chSTRSize' + kol).value;
						str = str + current;
						str = str.concat("$");
						
						if(document.getElementById('chRas' + kol).checked){
							current = '1';
						} else {
							current = '0';
						}
						str = str + current;
						str = str.concat("$");
						
						
						
								var optgroup   = document.getElementById('kol_str' + kol);
							var selMat 		= optgroup.options.length;
							
							var SelectElements = new Array;
							var y = 0;

							for (var n = 0; n < selMat; n++)
							{
							  if (optgroup.options[n].selected==true)
							  {

							   SelectElements[y]=optgroup.options[n].value;

									 y++;
							  }

							}
						
						current = SelectElements;
						str = str + current;
					

						str=str.concat(";")
			
							kol++;
						
					}
						
				}
		str = str.substring(0, str.length-1);
	
		var post= '_addProducts.php?productName='+productName+'&productSize='+productSize+'&productStr='+str+'&selSh='+selSh+'&skoba='+skoba_+'&kat1='+kat1+'&kat2='+kat2+'&selSh1='+selSh1_;
			window.location.href = post; 
			} 	
</script>  
<script>
function rts(id1, id2){

var node_id2 = document.getElementById(id2 + "0");
document.getElementById(id2).remove();
node_id2.getElementsByClassName('btn-group')[0].remove();


node_id2.innerHTML = node_id2.innerHTML +  "<select  data-style=" + '"btn-default btn-xs"' + " style='width: 100px;'  id='" + id2 + "' name='" + id2 + "[]' " + ' onClick="' + "rts('" + id1 + "','" + id2 + "')" + '" '+ " ></select>" 
$("#"+id2).empty();
	var eq = document.getElementById(id1);
	var kott = eq.options.length;
	
	var str = '';
	for (var n = 0; n < kott; n++){
		if (eq.options[n].selected==true){
			str= str + eq.options[n].value + ',';
			
  		}
	}
	
	
str = str.substr(0, str.length - 1)

var theElement = document.getElementById(id2);
		theElement.innerHTML = "";
	$.get( '_list_mater.php', { str: str}, function (data) {
		
		theElement.innerHTML = data;
	document.getElementById(id2).multiple = 'multiple';
	$('#' + id2 + ' option:selected').each(function(){
this.selected=false;
});
		ee(id2);
	});

}
</script>


<script>
function rts11(id1, id2, kol){

var node_id2 = document.getElementById(id2 + "0");

node_id2.getElementsByClassName('btn-group')[0].remove();
document.getElementById(id2).remove();
node_id2.innerHTML = node_id2.innerHTML +  "<select style='width: 100px;'  id='" + id2 + "' name='" + id2 + "[]' data-style=" + '"btn-default btn-xs"' + " ></select>" 
 
node_id3 = document.getElementById("optgroup" + kol + "0");
document.getElementById("optgroup" + kol).remove();
node_id3.getElementsByClassName('btn-group')[0].remove(); 
node_id3.innerHTML = node_id3.innerHTML +  "<select  data-style=" + '"btn-default btn-xs"' + " style='width: 100px;'  id='optgroup" + kol + "' name='optgroup" + kol + "[]' " + ' onClick="' + "rts('" + id2 + "','optgroup" + kol + "')" + '" '+ " ></select><div class='btn-group'></div>" 
$("#optgroup" + kol).empty(); 
 //div class="btn-group"

   /*$("#"+id2).empty();*/
	var eq = document.getElementById(id1);
	var kott = eq.options.length;
	
	var str = '';
	for (var n = 0; n < kott; n++){
		if (eq.options[n].selected==true){
			str= str + eq.options[n].value + ',';
			
  		}
	}
	
	
str = str.substr(0, str.length - 1)
var theElement = document.getElementById(id2);
		theElement.innerHTML = "";
		
		$.ajax({
			type: "GET",
			url: '_add_prod_filt_mat.php',
			data: {
				val : "0",
				id  : $("#"+id1+" option:selected").val()
			},  success:function (data) {//возвращаемый результат от сервера
					document.getElementById(id2).multiple = 'multiple';
					document.getElementById(id2).innerHTML = document.getElementById(id2).innerHTML +  data;
					$('#' + id2 + ' option:selected').each(function(){
						this.selected=false;
						});

				ee(id2);
		}
		});	
	
	
	  
		
}
</script>


	   <script type="text/javascript">


$('table tbody').sortable({
    helper: fixWidthHelper
}).disableSelection();
    
function fixWidthHelper(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
}


</script>


</body>
</html>

