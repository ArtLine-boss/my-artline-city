<?
include '../firewall1.php';
session_start();
$login = $_SESSION['login'];

include "../db.php";

	$query="SELECT t2.*, t2.sum_ttn - sum_opl raz
				FROM
				(select 
					f.ID, 
					f.FIRM_NAME, 
					IF(ROUND((select SUM(t1.all_sum) from ttn t1 where t1.cod_firm  = f.id GROUP BY f.id ),2) IS NULL , 0 , 
					ROUND((select SUM(t1.all_sum) from ttn t1 where t1.cod_firm  = f.id GROUP BY f.id ),2)) sum_ttn, 
					IF(ROUND((select SUM(o.OST_SUM) from oplati o where o.CLIENT_ID = f.id AND o.view_opl = 5 GROUP BY o.CLIENT_ID  ),2) IS NULL , 0 ,
					ROUND((select SUM(o.OST_SUM) from oplati o where o.CLIENT_ID = f.id AND o.view_opl = 5 GROUP BY o.CLIENT_ID  ),2)) sum_opl  
				from firms f where f.id <> 4 AND f.id <> 13) t2 ";

	$json = Array();
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
	

	  
	 $qr2=" select tt.* from 
	((select t1.num nums, t1.seria, DATE_FORMAT(t1.dt, '%d/%m/%Y') dates_, t1.all_sum sums , '1' types , t1.dt dt from ttn t1 where t1.cod_firm  = ".$row[0].") 
	 UNION
	(select  '' nums, '' seria, DATE_FORMAT(o.DATE_, '%d/%m/%Y') dates_, o.ost_sum sums, '2' types, o.DATE_ dt from oplati o where o.view_opl = 5 AND o.CLIENT_ID = ".$row[0].")) tt ORDER BY tt.dt"	;

		$list_prod = "";
		$iy = 0;
		$rs = mysql_query($qr2) or die($qr2);

		while ($rw = mysql_fetch_row($rs)) {
		$iy++;
		$list_prod = $list_prod."<div class = 'row'>
							
											<div class='col-md-1'>".$iy.". </div> 
											<div class='col-md-1'>".$rw[2]."</div> 
											<div class='col-md-3'>".$rw[1]." ". $rw[0]."</div> ";
											IF($rw[4] == "1") {
												$list_prod = $list_prod."<div class='col-md-2'>".$rw[3]."</div> <div class='col-md-2'></div> ";
											}
											else {
												$list_prod = $list_prod."<div class='col-md-2'></div> <div class='col-md-2'>".$rw[3]."</div> ";
											}
											
											$list_prod = $list_prod."	</div>";
		}	
		$json[] = array(
		   'q' => "<i class='fa fa-plus-square' aria-hidden='true'></i>",
			'id' =>$row[0],
			'names' =>$row[1],
			'sum1' =>$row[2],
			'sum2' =>$row[3],
			'sum3' =>$row[4],
			'TM' =>$list_prod,
		);
	}								
		echo json_encode($json);
		
		
/*
	/*
		
		select t1.num, t1.seria, DATE_FORMAT(t1.dt, '%d/%m/%Y') dates_, t1.all_sum from ttn t1 where t1.cod_firm  = 1

		select  DATE_FORMAT(o.DATE_, '%d/%m/%Y') date_, o.ost_sum  from oplati o where o.CLIENT_ID = 1 AND o.view_opl = 5
		
		*/
		/*$qr2="select t1.num, t1.seria, DATE_FORMAT(t1.dt, '%d/%m/%Y') dates_, t1.all_sum from ttn t1 where t1.cod_firm  = ".$row[0];

		$list_prod = "";
		$iy = 0;
		$rs = mysql_query($qr2) or die($qr2);
		$list_prod = "		 <div class = 'row'>
							<div class='col-md-12'><b>ТТН:</b></div> 
							
						</div><div class = 'row'>
							<div class='col-md-1'></div> 
							<div class='col-md-1'>Дата</div> 
							<div class='col-md-3'>Номер накладной</div> 
							<div class='col-md-1'>Сумма</div> 
						</div>";
		while ($rw = mysql_fetch_row($rs)) {
		$iy++;
		$list_prod = $list_prod."<div class = 'row'>
							
											<div class='col-md-1'>".$iy.". </div> 
											<div class='col-md-1'>".$rw[2]."</div> 
											<div class='col-md-3'>".$rw[1]." ". $rw[0]."</div> 
											<div class='col-md-1'>".$rw[3]."</div> 
										</div>";
		}	
											
		$qr2="select DATE_FORMAT(o.DATE_, '%d/%m/%Y') date_, o.ost_sum  from oplati o where o.view_opl = 5 AND  o.CLIENT_ID = ".$row[0];

		
		$iy = 0;
		$rs = mysql_query($qr2) or die($qr2);
		$list_prod .= "
		 
		 <div class = 'row'>
							<div class='col-md-12'><hr></div> 
							
						</div>
				<div class = 'row'>
							<div class='col-md-12'><b>ОПЛАТЫ:</b></div> 
							
						</div>		
		 <div class = 'row'>
							<div class='col-md-1'></div> 
							<div class='col-md-1'>Дата</div> 
							<div class='col-md-1'>Сумма</div> 
						</div>";
		while ($rw = mysql_fetch_row($rs)) {
		$iy++;
		$list_prod = $list_prod."<div class = 'row'>
										
											<div class='col-md-1'>".$iy.". </div> 
											<div class='col-md-1'>".$rw[0]."</div> 
											<div class='col-md-1'>".$rw[1]."</div> 
										</div>";
		}	
	
	  */

?>	
		
		