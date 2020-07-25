<?

$dt1  = $_GET["dt1"];
$dt2  = $_GET["dt2"];

$date2 = date('Y-m-d', strtotime('+1 day', strtotime($dt2)));
$json = Array();
include "../db.php";


$query="	
	select 
		op.temp_pr, 
		tb1.id_prod,   
		tb1.user_log ,  
		op.sum_press, 
		(select u.USER_FIO from users u where u.USER_LOGIN = tb1.user_log LIMIT 1) name
	from 
		order_product op, 
		(select DISTINCT lt.id_prod, user_log from log_task lt where lt.status_old = 10 and lt.prob not like '%брак%' and lt.datetime >='".$dt1."' and  lt.datetime <= '".$date2."' and 
		(select op.status from order_product op where op.id = lt.id_prod) <> 10 ) tb1 
	where 
		op.id = tb1.id_prod ORDER BY tb1.user_log ";
		
		$USER_id = "";
	$USER_FIO = "";
	$SUM_RDY = 0;
	
	$json = Array();
	$result = mysql_query($query) or die($query);				
	while ($row = mysql_fetch_row($result)) { 
	
	
		$strr =  explode("^", $row[0]);	
		$strr1 =  explode("|", $strr[0]);	

		 $sum = $strr1[23];
			IF ($sum  == ""){
				$sum  = 0;
			}
			//	ECHO $USER_id." ".$row[2]."<br>";
				
				
		IF ($USER_FIO == ""){
			$USER_id = "";
			$USER_FIO = "";
			$SUM_RDY = 0;
		
			$USER_FIO = $row[4];
			$SUM_RDY = $sum;
			$USER_id = $row[2];
		}ELSEIF ($USER_id  == $row[2]) {
			$SUM_RDY = $SUM_RDY + $sum;
		}ELSE {
			//ECHO $USER_FIO."1111<br>";
			$json[] = array(
				'name' =>$USER_FIO,
				'sum_' =>$SUM_RDY,
				);
			$USER_id = "";
			$USER_FIO = "";
			$SUM_RDY = 0;
		
			$USER_FIO = $row[4];
			$SUM_RDY = $sum;
			$USER_id = $row[2];	
				
				
		}
		
	}
	$json[] = array(
				'name' =>$USER_FIO,
				'sum_' =>$SUM_RDY,
				);
	
	
	
	
//	PRINT_R($json);
		echo '<div class="row"><div class="col-md-4"><b>ФИО</div>   <div class="col-md-2">Сумма, BYN	</b></div> </div>	';		
echo '<div class="row"><div class="col-md-4">&nbsp;</div>   <div class="col-md-2">&nbsp;</div> </div>	';			
	FOR($i = 0; $i < count($json); $i++){
		echo '<div class="row"><div class="col-md-4">'.$json[$i]['name'].'</div><div class="col-md-2">'.$json[$i]['sum_']." </div></div>";
	}
	
	



?> 