<?php
	include "../db.php";
	//создаем ттн
	$date_current = '2019-03-29';
	$insert = "INSERT INTO TTN (num,cod_firm,dt) VALUES('220190415',4,'".$date_current."')";
	$result_insert = mysql_query($insert);
	if($result_insert) {
		//ид новой записи
		$id_ttn = mysql_insert_id();
		//получаем список списаний за дату
		$select = "SELECT id,cm.id_mat,cm.total FROM con_mater cm WHERE cm.date>'2019-04-15' and cm.flags='r'";
		$result_sel = mysql_query($select);
		//общая сумма
		$summ_all = 0;
		$ind = 0;
		while($row = mysql_fetch_array($result_sel)) {
			//получаем данные по текущему материалу
			$sel_mat = "SELECT M_PRICE,M_KOL_ALL,M_UNIT FROM material_attr WHERE id=".$row['id_mat'];
			$res_mat = mysql_query($sel_mat);
			if($r_mat = mysql_fetch_array($res_mat)) {
				//получаем общее количество
				$kol = floatval($r_mat['M_KOL_ALL']) + floatval($row['total']);
				//определяем сумму за текущий материал
				$summ = ceil(floatval($r_mat['M_PRICE']) * floatval($row['total']) * 2.2 * 100)/100;
				$summ_all += $summ;
				//обновляем количество в материале
				$up_mat = "UPDATE material_attr SET M_KOL_ALL=".$kol." WHERE id=".$row['id_mat'];
				$res_up_mat = mysql_query($up_mat);
				if(!$res_up_mat) {
					echo "ERROR UPDATE METRIAL: ".$row['id_mat']."<br>";
					continue;
				}
				//пишем новый пункт в ттн
				$ins_ttn_mat = "INSERT INTO TTN_mater(id_TTN,id_mat,total,unit,sum_all,total_ttn) VALUES(".$id_ttn.",".$row['id_mat'].",".floatval($row['total']).",'".$r_mat['M_UNIT']."',".$summ.",".floatval($row['total']).")";
				$res_ins_ttn_mat = mysql_query($ins_ttn_mat);
				if($res_ins_ttn_mat) {
					//обновляем дату в расходе
					//$up_con_mat = "UPDATE con_mater SET date='".$date_current."' WHERE id=".$row['id'];
					$up_con_mat = "UPDATE con_mater SET date='2019-03-01' WHERE id=".$row['id'];
					$res_up_con_mat = mysql_query($up_con_mat);
					if(!$res_up_con_mat) {
						echo "ERROR UPDATE con_mater: ".$row['id']."<br>";
						continue;
					}
				}
				else {
					echo "ERROR INSERT IN TABLE TTN_mater: ".$row['id_mat']."<br>";
				}
			}
			$ind++;
		}
		//обновляем общую сумму накладной
		$summ_all = ceil($summ_all * 100)/100;
		$update = "UPDATE TTN SET all_sum=".$summ_all." WHERE id=".$id_ttn;
		$result_update = mysql_query($update);
		if($result_update) {
			echo "Quantity: ".$ind;
		}
		else {
			echo "ERROR UPDATE TTN: ".$id_ttn."<br>";
		}
	
	}
	else {
		echo "ERROR INSERT<br>";
	}
?>