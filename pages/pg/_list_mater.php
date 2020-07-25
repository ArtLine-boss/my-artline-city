<?php
	include "../db.php";
	$str = $_GET['str'];
	$query="select DISTINCT ma.MT_TYPE, ma.M_NAME, ma.ID,ma.M_PRICE from (select eq.id, eq.mater from equipment eq where FIND_IN_SET(eq.id,'".$str."')) eq , 
				(select m.MT_TYPE, ma.M_NAME, ma.ID,ma.M_PRICE from material m, material_attr ma where ma.ID_M = m.ID and ma.M_NAME is not null and ma.M_SIZE <> '720*1040' 
				and ma.M_SIZE <> '650*940' and ma.M_SIZE <> '470*650' and ma.M_SIZE <> '700*1000') ma where FIND_IN_SET(ma.id,eq.mater);";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)){ 
		if ($flag != $row[0]){
			if ($ko != 0) {
				print " </optgroup>";
			}
			$flag = $row[0];
			print "<optgroup label='$row[0]' ><option value='$row[2]'>&nbsp;&nbsp;&nbsp;$row[1]</option>"; 
			$ko = 1;
			} else { print "<option value='$row[2]'>&nbsp;&nbsp;&nbsp;$row[1]</option>";
			$flag = $row[0];
				$ko = 1;
			}
		}
	
	mysql_close($connection);

	
	
	
?>