<?
	
	function cleanDir($link) {
		
		if (is_dir('../pg/'.$link) AND $link!= "" ){
			
			$files1 = scandir('../pg/'.$link);
			
			for ($i = 2; $i < count($files1); $i++ ){
				echo '../pg/'.$link.'/'.$files1[$i]."<br>";
				unlink('../pg/'.$link.'/'.$files1[$i]);
			}
			
		}
	}
	include "../db.php";
	$qw = "select op.print_diz , op.cl_file  from orders o, order_product op 
	where  o.DATE_OR < DATE_SUB(NOW(), INTERVAL 90 DAY) AND op.ORDER_ID = o.NUMBER and op.print_diz <> '0' and op.print_diz <> '' and  op.cl_file <> '';";
	
	$res = mysql_query($qw) or die($qw);
	WHILE ($row = mysql_fetch_row($res)) { 
		
		IF ($row[0] != ''){
			cleanDir($row[0]);
		}
		
		
		IF ($row[1] != ''){
			cleanDir($row[1]);
		}
		
	} 
	
	
	
?>
