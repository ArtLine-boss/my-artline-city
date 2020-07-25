<?
	
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
	include '../../firewall1.php';
	session_start();
	?>
   <html>
  <head>
    <title>НеизвестнаяТаблица</title>
    <meta name="GENERATOR" content="HeidiSQL 9.3.0.4984">
    <meta http-equiv="Content-Type" content="text/html; charset=Windows-1251" />
    <style type="text/css">
      thead tr {background-color: ActiveCaption; color: CaptionText;}
      th, td {vertical-align: top; font-family: "Tahoma", Arial, Helvetica, sans-serif; font-size: 8pt; padding: 3px; }
      table, td {border: 1px solid silver;}
      table {border-collapse: collapse;}
      thead .col0 {width: 300px;}
      thead .col1 {width: 111px;}
      thead .col2 {width: 98px;}
      thead .col3 {width: 96px;}
      thead .col4 {width: 81px;}
      thead .col5 {width: 81px;}
    </style>
  </head>

  <body>



<?php
	
	
	$dt1 = $_GET['dt1'];
	$dt2 = $_GET['dt2'];
	
	$query = "SELECT 
	tt_f.id_mat id,
	(select ma.M_NAME from material_attr ma where ma.ID = tt_f.id_mat) m_name,
	(tt_f.sums + tt_f.total_pr) -  tt_f.total_r_all 'остаток на тек. число', 
		tt_f.total_r_tek   'Списать за тек. месяц',
	((tt_f.sums + tt_f.total_pr) -  tt_f.total_r_pro) - (IF(tt_f.sums1 -  tt_f.total_r_pro < 0, 0 ,tt_f.sums1 -  tt_f.total_r_pro))  'Остаток ОДО до спис.',
	IF(tt_f.sums1 -  tt_f.total_r_pro < 0, 0 ,tt_f.sums1 -  tt_f.total_r_pro)  'Остаток Уч. до спис.',
	((tt_f.sums + tt_f.total_pr) -  tt_f.total_r_all) - (IF(tt_f.sums1 -  tt_f.total_r_all < 0, 0 ,tt_f.sums1 -  tt_f.total_r_all))  'Остаток ОДО после спис.',
	IF(tt_f.sums1 -  tt_f.total_r_all < 0, 0 ,tt_f.sums1 -  tt_f.total_r_all)  'Остаток Уч. после спис.'
	
	FROM (SELECT 
	tt9.id_mat , tt9.sums  , tt9.sums1  , tt9.total_pr  , tt9.total_r_pro , tt9.total_r_all , IF(tt10.total IS NULL, 0, ROUND(tt10.total,0)) total_r_tek
	FROM 
	(SELECT 
	tt7.id_mat , tt7.sums  , tt7.sums1  , tt7.total_pr  , tt7.total_r_pro , IF(tt8.total IS NULL, 0, ROUND(tt8.total,0)) total_r_all
	FROM 
	(SELECT 
	tt5.id_mat , tt5.sums  , tt5.sums1  , tt5.total_pr  , IF(tt6.total IS NULL, 0, ROUND(tt6.total,0)) total_r_pro
	FROM 
	(SELECT tt3.id_mat , tt3.sums  , tt3.sums1  , IF(tt4.total IS NULL, 0, ROUND(tt4.total,0)) total_pr
	FROM
	(SELECT 
	tt1.id_mat, tt1.sums, IF( tt2.sums1 IS NULL, 0, tt2.sums1) sums1
	FROM (select  tm1.id_mat,   ROUND(sum(tm1.total),0) sums  from TTN t1,  TTN_mater tm1  where t1.dt < '".$dt2."' and tm1.id_TTN = t1.ID GROUP BY  tm1.id_mat) tt1 
	LEFT JOIN 
	(select tm2.id_mat,   ROUND(sum(tm2.total),0) sums1 from TTN t2 ,  TTN_mater tm2  where t2.cod_firm = 4  and   t2.dt < '".$dt2."' and tm2.id_TTN = t2.ID GROUP BY  tm2.id_mat) tt2 
	ON tt1.id_mat = tt2.id_mat) tt3
	LEFT JOIN 
	(select c4.id_mat, ROUND(sum(c4.total),2) total from con_mater  c4 where c4.date < '".$dt2."' and c4.flags = 'p' group by c4.id_mat) tt4 
	ON tt3.id_mat = tt4.id_mat) tt5
	LEFT JOIN 
	(select c6.id_mat, ROUND(sum(c6.total),2) total from con_mater  c6 where c6.date < '".$dt1."' and c6.flags = 'r' group by c6.id_mat) tt6
	ON tt5.id_mat = tt6.id_mat) tt7
	LEFT JOIN 
	(select c8.id_mat, ROUND(sum(c8.total),2) total from con_mater  c8 where c8.date < '".$dt2."' and c8.flags = 'r' group by c8.id_mat) tt8
	ON tt7.id_mat = tt8.id_mat) tt9
	LEFT JOIN 
	(select c10.id_mat, ROUND(sum(c10.total),2) total from con_mater  c10 where c10.date >= '".$dt1."' and c10.date < '".$dt2."' and c10.flags = 'r' group by c10.id_mat) tt10
	ON tt10.id_mat = tt9.id_mat) tt_f where 	tt_f.total_r_tek > 0
	
	
	
	
	";

	ECHO "<table >
	<thead>
		<tr>
			<th>id</th>
			<th>m_name</th>
			<th>остаток на ".$dt2." число</th>
			<th>Списать за тек. месяц</th>
			<th>Остаток ОДО до спис.</th>
			<th>Остаток Уч. до спис.</th>
			<th>Остаток ОДО после спис.</th>
			<th>Остаток Уч. после спис.</th>
			<th>КОД</th>
		
		</tr>
	</thead><tbody>";
	
	$result = mysql_query($query) or die($query);
	
	while ($row = mysql_fetch_row($result)) { 
	$sum = 0;
		 $list_cod = '';
	 $ff= 0;
		 $qry = "select  total, cod_mat from TTN_mater tm where id_mat = ".$row[0]." ORDER BY id DESC";
		 $rs = mysql_query($qry) or die($qry);
		 
			while ($rw = mysql_fetch_row($rs)) { 
			$sum = $sum  + (INT)$rw[0];
			IF($ff == 0){
				IF($sum < (INT)$row[4]){
			 $list_cod = $list_cod .$rw[1].",";
			}
			IF($sum >= (INT)$row[4]){
			 $list_cod = $list_cod .$rw[1];
			 $ff = 1;
			}
			
			}
		
			
				
		}
		 
	ECHO "<tr>
			<td >".$row[0]."</td >
			<td >".$row[1]."</td >
			<td >".$row[2]."</td >
			<td >".$row[3]."</td >
			<td >".$row[4]."</td >
			<td >".$row[5]."</td >
			<td >".$row[6]."</td >
			<td >".$row[7]."</td >
			<td >". $list_cod."</td >
		
		</tr>";
		
	}
?>