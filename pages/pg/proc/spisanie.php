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
	//входные даты
	$dt1 = $_GET['dt1'];
	$dt2 = $_GET['dt2'];

	//запрос на количество списания
	$query = "SELECT t7.id_mat, t8.M_NAME, t7.supply, t7.supplyP, t7.outgo, t7.total
				FROM
					(SELECT t5.id_mat, t5.supply, t5.supplyP, t5.total, IF(t6.total IS NULL, 0, t6.total) outgo
					FROM
						(SELECT t3.id_mat, IF(t4.total IS NULL, 0, t4.total) supply, t3.supplyP, t3.total
						FROM
							(SELECT t1.id_mat, IF(t1.total IS NULL, 0, t1.total) total, IF(t2.total IS NULL, 0, t2.total) supplyP
							FROM
								(SELECT cm.id_mat, SUM(cm.total) total FROM con_mater cm WHERE cm.date >= '".$dt1."' AND cm.date < '".$dt2."' AND cm.flags = 'r' GROUP BY cm.id_mat) t1
							LEFT JOIN
								(SELECT cm.id_mat, SUM(cm.total) total FROM con_mater cm WHERE cm.date < '".$dt2."' AND cm.flags = 'p' GROUP BY cm.id_mat) t2
							ON t1.id_mat = t2.id_mat) t3
						LEFT JOIN
							(SELECT ttn_m.id_mat, SUM(ttn_m.total) total FROM TTN_mater ttn_m GROUP BY ttn_m.id_mat) t4
						ON t3.id_mat = t4.id_mat
						WHERE t3.total > 0) t5
					LEFT JOIN
						(SELECT cm.id_mat, SUM(cm.total) total FROM con_mater cm WHERE cm.date < '".$dt1."' AND cm.flags = 'r' GROUP BY cm.id_mat) t6
					ON t5.id_mat=t6.id_mat) t7
				LEFT JOIN
					(SELECT id, M_NAME FROM material_attr) t8
				ON t7.id_mat=t8.id";
	//создаем таблицу
	ECHO "<table >
		<thead>
			<tr>
				<th>id</th>
				<th>m_name</th>
				<th>остаток на ".$dt2."</th>
				<th>Списать за тек. месяц</th>
				<th>Остаток ОДО до спис.</th>
				<th>Остаток Уч. до спис.</th>
				<th>Остаток ОДО после спис.</th>
				<th>Остаток Уч. после спис.</th>
				<th>КОД</th>
			
			</tr>
		</thead><tbody>";
	
	//для списка что осталось на учредителе
	/*ECHO "<table >
		<thead>
			<tr>
				<th>id</th>
				<th>m_name</th>
				<th>Остаток Уч. после спис.</th>
			
			</tr>
		</thead><tbody>";*/
	//выполняем запрос
	$result = mysql_query($query) or die($query);
	
	while ($row = mysql_fetch_array($result)) { 
		//ид материала
		$id_mat = $row['id_mat'];
		//наименование материала
		$M_NAME = $row['M_NAME'];
		//остаток на складе после списания
		$rest = floatval($row['supply']) + floatval($row['supplyP']) - floatval($row['outgo']) - floatval($row['total']);
		//количество списываемого
		$total = $row['total'];
		//запрос по поступлениям... в списке и ТН и приходы, отсортированные по дате
		$select_mat = "SELECT *
						FROM
						(SELECT t1.id_TTN, t1.total, IF(t1.cod_mat IS NULL, '', t1.cod_mat) cod_mat, t2.cod_firm, t2.dt
						FROM
							(SELECT ttn_m.id_TTN, ttn_m.cod_mat, ttn_m.total FROM TTN_mater ttn_m WHERE ttn_m.id_mat=".$id_mat.") t1
						LEFT JOIN
							(SELECT ID, cod_firm, dt FROM TTN) t2
						ON t1.id_TTN=t2.ID
						UNION
						SELECT '' id_TTN, total, '' cod_mat, 0 cod_firm, date dt FROM con_mater cm WHERE cm.flags='p' AND cm.id_mat=".$id_mat."
						ORDER BY dt) t3
						WHERE t3.dt<'".$dt2."'";
		//временная сумма поступления
		$sum_supply = 0;
		//сумма остатка по ОДО до списания
		$summ_odo = 0;
		//сумма остатка по ОДО после списания
		$summ_odo_ost = 0;
		//сумма остатка по учредителю до списания
		$sum_uch = 0;
		//сумма остатка по учредителю после списания
		$summ_uch_ost = 0;
		//список кодов
		$list_code = "";
		//флаг или определили начало списывания
		$flag = false;
		//выполняем запрос по поступлениям
		$result_mat = mysql_query($select_mat);
		//временное количество списываемого
		$tmp_total = $total;
		while($r = mysql_fetch_array($result_mat)) {
			//если временная сумма меньше ранее списанного, то увеличиваем её на сумму поступления
			if($sum_supply < floatval($row['outgo']))
				$sum_supply += $r['total'];
			//если сумма больше или ровна сумме ранее списанного
			if($sum_supply >= floatval($row['outgo'])) {
				//если это начало списывания
				if(!$flag && $sum_supply > 0) {
					//определяем остаток по данной позиции
					$ost = $sum_supply - floatval($row['outgo']);
					//если учредитель
					if(intval($r['cod_firm']) == 4) {
						//остаток после списания
						$ost_ = $ost - $tmp_total;
						//если он меньше нуля, то меняем временное количество списываемого
						if($ost_ < 0)
							$tmp_total = $ost_ * (-1);
						//иначе обнуляем и в остаток после списывания пишем разницу
						else {
							$tmp_total = 0;
							$summ_uch_ost += $ost_;
						}
						//в сумму до списывания пишем остаток по позиции
						$sum_uch += $ost;
					}
					//если ОДО
					else {
						//также как и в предыдущем блоке
						$ost_ = $ost - $tmp_total;
						if($ost_ < 0)
							$tmp_total = $ost_ * (-1);
						else {
							$tmp_total = 0;
							$summ_odo_ost += $ost_;
						}
						$summ_odo += $ost;
					}
					//если есть код, то пишем в список
					if($r["cod_mat"] != "") {
						if($list_code != "")
							$list_code .= ", ";
						$list_code .= $r["cod_mat"];
					}
					//выставляем, что нашли начало
					$flag = true;
				}
				else {
					//если не всё списали, то пишем код списывания
					if($tmp_total > 0) {
						if($r["cod_mat"] != "") {
							if($list_code != "")
								$list_code .= ", ";
							$list_code .= $r["cod_mat"];
						}
					}
					//для учредителя
					if(intval($r['cod_firm']) == 4) {
						//тут списываем остатки
						$ost_ = floatval($r['total']) - $tmp_total;
						if($ost_ < 0)
							$tmp_total = $ost_ * (-1);
						else {
							$tmp_total = 0;
							$summ_uch_ost += $ost_;
						}
						//добавляем в сумму до списания
						$sum_uch += floatval($r['total']);
					}
					//для ОДО
					else {
						//всё как и в блоке выше
						$ost_ = floatval($r['total']) - $tmp_total;
						if($ost_ < 0)
							$tmp_total = $ost_ * (-1);
						else {
							$tmp_total = 0;
							$summ_odo_ost += $ost_;
						}
						$summ_odo += floatval($r['total']);
					}
				}
			}
		}
		
		//для списка что осталось на учредителе
		/*if($summ_uch_ost <= 0)
			continue;*/
		
		//пишем строку таблицы
		ECHO "<tr>
				<td >".$id_mat."</td >
				<td >".$M_NAME."</td >
				<td >".round($rest*100)/100 ."</td >
				<td >".round($total*100)/100 ."</td >
				<td >".round($summ_odo*100)/100 ."</td >
				<td >".round($sum_uch*100)/100 ."</td >
				<td >".round($summ_odo_ost*100)/100 ."</td >
				<td >".round($summ_uch_ost*100)/100 ."</td >
				<td >".$list_code."</td >
			
			</tr>";
		//для списка что осталось на учредителе
		/*ECHO "<tr>
				<td >".$id_mat."</td >
				<td >".$M_NAME."</td >
				<td >".round($summ_uch_ost*100)/100 ."</td >
			
			</tr>";*/
	}
?>