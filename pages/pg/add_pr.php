<?
INCLUDE '../db.php';
$kol = 1;		
	$EQQQ_ID = '';	
	$MATER_ID = '';		
$query = "select  DISTINCT e.id,  e.mater from equipment e, (select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where OPERATION_NAME = '".$_GET['selOper']."' and par is not null) o where FIND_IN_SET(o.id ,e.oper);	";	
$result = mysql_query($query) or die($query);		
	WHILE ($row = mysql_fetch_row($result)) {
		$EQQQ_ID = $EQQQ_ID.$row[0].",";
		$MATER_ID = $MATER_ID.$row[1].",";
		
	}
$EQQQ_ID = substr($EQQQ_ID, 0, -1);
$MATER_ID = substr($MATER_ID, 0, -1);

 echo  "<div class='row'>
													<div class='col-md-2'>
														<div class='block1'> 
															<label id = 'cch".$kol."8'>Оборудование: </label>
														</div>
													</div>";
											echo  "<div class='col-md-10'>
													<div class='block2'>
														<select  id = 'ch".$kol."8' name = ''  onclick = " . '"'."rts('ch".$kol."8', 'ch".$kol."1')".'" '."> <option value='0'>Выберите</option>"; 
										$ID_EQ = explode(",", $EQQQ_ID);
										FOR ($g = 0; $g < count($ID_EQ); $g++){
											$query = "select id, eq_name from equipment where id = ".$ID_EQ[$g]." and  eq_name is not null;";
											$result = mysql_query($query) or die($query);
											WHILE ($row = mysql_fetch_row($result)) { 
												echo  "<option value='$row[0]'>$row[1]</option>"; 
											}
										}
										echo  "</select>
													</div>
													</div>
												</div>";


$query = "select o.id, o.par, o.OPERATION_PRICE,o.MAKEREADY_PRICE,o.OPERATION_NAME, e.id from  equipment e, (select id , par, OPERATION_PRICE,MAKEREADY_PRICE,OPERATION_NAME from operations where OPERATION_NAME = '".$_GET['selOper']."' and par is not null) o where FIND_IN_SET(o.id ,e.oper);";
$result = mysql_query($query) or die($query);
echo  "<div class='row'>
			<div class='col-md-2'>
				<div class='block1'> 
					<label id = 'cch".$kol."1'>".$_GET['selOper'].": </label>
				</div>
			</div>  
			<div class='col-md-10'>
				<div class='block2'>
					<select id = 'ch".$kol."1'  >";
echo  "<option label = '0'  value=''>Выберите</option>"; 
WHILE ($row = mysql_fetch_row($result)) { 
		$name_oper = $row[4];
		if ($row[3] == ''){
			$prol = 0;
		}else{
		$prol = $row[3];
		}
		echo  "<option label = '$row[5]' value='$row[0]#$row[2]^$prol'>$row[1]</option>"; 
	}
	echo  "</select></div>
	</div>
</div>";

echo   "<div class='row'>
	<div class='col-md-2'>
	<div class='block1'> ";
	
	echo "<label id = 'cch".$kol."3'>Материал: </label>";
	echo "</div></div>";
	echo  "<div class='col-md-10'>
	<div class='block2'>
	<select  id = 'ch".$kol."3' name = '' onclick = " . '"'."rts('ch".$kol."8', 'ch".$kol."3')".'" '."><option label = '0' value='0@0!' >Выберите материал</option>"; 
	$TEMPLATE_TYPE = explode(",", $MATER_ID);
	FOR ($z = 0; $z < count($TEMPLATE_TYPE); $z++){
		$query = "select  ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE , ma.m_tol, eq.id from equipment eq, (select ma.ID, ma.M_NAME, ma.M_PRICE,ma.M_SIZE , ma.m_tol 
		from  material_attr ma where ma.ID = '".$TEMPLATE_TYPE[$z]."' and ma.M_NAME is not null) ma where FIND_IN_SET(ma.id ,eq.mater);";
		$result = mysql_query($query) or die("Query failed3");
		WHILE ($row = mysql_fetch_row($result)) { 
		echo  "<option label = '$row[5]' value='$row[0]@$row[2]!$row[3]^$row[4]' ".' style="display:block;">' ."$row[1]</option>"; 
		}
		}
	echo  "</select>
	</div>
</div>
	</div>";

?>