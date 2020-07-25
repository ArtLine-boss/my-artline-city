
<?
	
	if ($_GET['id'] == '1'){

		$firm_parent = (isset($_GET['firm_parent']) && !empty($_GET['firm_parent'])) ? $_GET['firm_parent'] : 1;
		$folder = ($firm_parent == 2) ? 'UpdateTnMechta' : 'UpdateTn';
		
		include "db.php";
		set_time_limit(0);
		ini_set('memory_limit', '20000M');
		unlink($folder . "/Rashod.dbf") ;
		unlink($folder . "/Dogovora.dbf") ;
		unlink($folder . "/Kontr.dbf") ;
		unlink($folder . "/Tovar.dbf") ;
		unlink($folder . "/RashodTv.dbf") ;
		
		if (file_exists($folder . "/Rashod.dbf") OR file_exists($folder . "/Dogovora.dbf") OR file_exists($folder . "/Kontr.dbf") OR file_exists($folder . "/Tovar.dbf") OR file_exists($folder . "/RashodTv.dbf")) {
			echo "Файлы заблокированы для записи!";
		}
		else {
			
			$Rashod = "Rashod.dbf";
			$Dogovora = "Dogovora.dbf";
			$Kontr = "Kontr.dbf";
			$Tovar = "Tovar.dbf";
			$RashodTv = "RashodTv.dbf";
			
			$Rashod1 = "Rashod.dbf";
			$Dogovora1 = "Dogovora.dbf";
			$Kontr1 = "Kontr.dbf";
			$Tovar1 = "Tovar.dbf";
			$RashodTv1 = "RashodTv.dbf";
			
			//Столбцы
			
			$def_Rashod = array(
			array("DATARAS","C",10,0), //Имя, строка
			array("NUMNAKL","C",8,0), //Имя, строка
			array("SHETN","C",10,0), //Имя, строка
			array("SHETD","C",10,0), //Имя, строка 
			array("SERNAKL","C",2,0), //Имя, строка
			array("KONTRCODE","C",20,0), //Имя, строка
			array("DOGOVCODE","C",8,0), //Имя, строка
			array("VALUTACODE","N",3,0), //Имя, строка
			array("SKLADCODE","C",5,0), //Имя, строка
			array("SUMMADOC","N",15,2), //Имя, строка
			array("MENEGER","C",50,0), //Имя, строка
			array("TYPE","C",5,0), //Имя, строка
			array("DEFECT","C",1,0) //Имя, строка 
			
			);
			$def_Dogovora = array(
			array("DOGOVCODE","C",8,0), //Имя, строка
			array("KONTRCODE","C",20,0), //Имя, строка
			array("NAME","C",85,0), //Имя, строка
			array("DOGOVN","C",8,0), //Имя, строка
			array("DOGOVD","C",10,0), //Имя, строка
			array("SHETN","C",8,0), //Имя, строка
			array("SHETD","C",10,0) //Имя, строка 
			
		);
		$def_Kontr = array(
		array("KONTRCODE","C",20,0), //Имя, строка
		array("NAME","C",100,0), //Имя, строка
		array("FULLNAME","C",150,0), //Имя, строка
		array("U_ADDRESS","C",100,0), //Имя, строка
		array("SHET","C",28,0), //Имя, строка
		array("BIC","C",11,0)//Имя, строка
		
		);
		$def_Tovar = array(
		array("TOVARCODE","C",9,0), //Имя, строка
		array("NAME","C",100,0), //Имя, строка
		array("FULLNAME","C",100,0), //Имя, строка
		array("GROUPCODE","C",9,0), //Имя, строка
		array("GROUPNAME","C",100,0), //Имя, строка
		array("EDIZM","C",10,0) //Имя, строка
		
		);
		$def_RashodTv = array(
		array("NUMNAKL","C",8,0), //Имя, строка
		array("TOVARCODE","C",9,0), //Имя, строка
		array("KOL","N",19,2), //Имя, строка
		array("CENA","N",15,2), //Имя, строка
		array("SUMMA","N",15,2), //Имя, строка
		array("STNDS","N",2,0), //Имя, строка
		array("NDS","N",15,2), //Имя, строка
		array("VSEGO","N",15,2), //Имя, строка
		array("CODESTAT","C",100,0) //Имя, строка
		);
		//Создем файл
		$DBF_Rashod   = dbase_create($folder . '/'.$Rashod, $def_Rashod);
		$DBF_Dogovora = dbase_create($folder . '/'.$Dogovora, $def_Dogovora);
		$DBF_Kontr    = dbase_create($folder . '/'.$Kontr, $def_Kontr);
		$DBF_Tovar    = dbase_create($folder . '/'.$Tovar, $def_Tovar);
		$DBF_RashodTv = dbase_create($folder . '/'.$RashodTv, $def_RashodTv);
		
		$path = $folder . "/".date("d_m_Y")."/";
		if (!file_exists($path)) {
		mkdir($path, 0700);
		}
		
		$DBF_Rashod1   = dbase_create($folder . '/'.date("d_m_Y")."/".$Rashod1, $def_Rashod);
		$DBF_Dogovora1 = dbase_create($folder . '/'.date("d_m_Y")."/".$Dogovora1, $def_Dogovora);
		$DBF_Kontr1    = dbase_create($folder . '/'.date("d_m_Y")."/".$Kontr1, $def_Kontr);
		$DBF_Tovar1    = dbase_create($folder . '/'.date("d_m_Y")."/".$Tovar1, $def_Tovar);
		$DBF_RashodTv1 = dbase_create($folder . '/'.date("d_m_Y")."/".$RashodTv1, $def_RashodTv);
		
		
		$query = "select val from settings s where  s.id = 4";
		$result = mysql_query($query) or die($query);
		WHILE ($row = mysql_fetch_row($result)) { 
		$nds = $row[0];
		}
		IF ($nds ==''){
		$nds = 0;
		} ELSE {
		$nds = str_replace(',', '.', $nds);
		}
		$nds_old = $nds;
		$tn = 0;
		$act = 0;
		$del = 0;
		//Получаем данные из MySQL
		$query = "select t.dates_, 
       				t.num_tm, 
       				t.seria, 
       				t.val_, 
       				c.UNP, 
       				c.CLIENT_NAME, 
       				c.ADDRESS_DEV, 
       				c.CODE_BANK, 
       				c.ACCT,  
       				c.num_doc, 
       				(select u.USER_FIO from users u where u.USER_LOGIN = t.user_log) user_name,
       				o.NUMBER, 
       				DATE_FORMAT(o.DATE_OR, '%d.%m.%Y') date, 
       				t.type, 
       				t.del, 
       				t.id, 
       				o.cur_id, 
       				t.no_nds,
       				c.num_doc_m
				FROM tn_list_par t, orders o, clients c 
				WHERE ((t.del = 0 and t.exp_1c = 0) OR (t.del = 1 and t.exp_1c = 2)) AND NUMBER = t.order_id AND c.ID = o.CLIENT_ID AND o.parent_company=" . $firm_parent ;
		$result = mysql_query($query) or die($query);
		WHILE ($row = mysql_fetch_row($result)) { 
		$summ_all = 0;
		$num_doc = "";
		$date_doc  = "";
		if (iconv("UTF-8", "cp1251", $row[9]) != ""){
		// echo iconv("UTF-8", "cp1251", $row[9]);
			$num_doc = (substr(	 $row[9] ,0 , strrpos(	 $row[9] , 'от') - 1));
			$date_doc = (substr( $row[9] ,strrpos(	$row[9] , ' ') + 1));
		} 
		ELSE {
			$date_doc = '';
			$num_doc = '';
		}

		if($firm_parent == 2) {
			if (iconv("UTF-8", "cp1251", $row[18]) != "") {
				$num_doc = (substr(	 $row[18] ,0 , strrpos(	 $row[18] , 'от') - 1));
				$date_doc = (substr( $row[18] ,strrpos(	$row[18] , ' ') + 1));
			} else {
				$date_doc = '';
				$num_doc = '';
			}
		}
		
		
		dbase_add_record($DBF_Kontr, array(iconv("UTF-8", "cp1251", $row[4]), 
		iconv("UTF-8", "cp1251", $row[5]), 
		iconv("UTF-8", "cp1251", $row[5]), 
		iconv("UTF-8", "cp1251", $row[6]), 
		iconv("UTF-8", "cp1251", $row[8]), 
		iconv("UTF-8", "cp1251", $row[7])));
		dbase_add_record($DBF_Kontr1, array(iconv("UTF-8", "cp1251", $row[4]), 
		iconv("UTF-8", "cp1251", $row[5]), 
		iconv("UTF-8", "cp1251", $row[5]), 
		iconv("UTF-8", "cp1251", $row[6]), 
		iconv("UTF-8", "cp1251", $row[8]), 
		iconv("UTF-8", "cp1251", $row[7])));
		IF ($date_doc != ""){
		$date_doc1 = new DateTime($date_doc) ;
		$date_rr = $date_doc1->format('d.m.Y');
		} ELSE {
		$date_rr = "";
		}
		
		/*dbase_add_record($DBF_Dogovora, array(iconv("UTF-8", "cp1251",$num_doc) ,
		iconv("UTF-8", "cp1251", $row[4]), 
		iconv("UTF-8", "cp1251", $row[9]), $num_doc, $date_rr,iconv("UTF-8", "cp1251", $row[11]),iconv("UTF-8", "cp1251", $row[12])));*/
		
		dbase_add_record($DBF_Dogovora, array($num_doc,
		iconv("UTF-8", "cp1251", $row[4]), 
		iconv("UTF-8", "cp1251", $firm_parent == 2 ? $row[18] : $row[9]), iconv("UTF-8", "cp1251",$num_doc), $date_rr,iconv("UTF-8", "cp1251", $row[11]),iconv("UTF-8", "cp1251", $row[12])));
		
		
		dbase_add_record($DBF_Dogovora1, array($num_doc ,
		iconv("UTF-8", "cp1251", $row[4]), 
		iconv("UTF-8", "cp1251", $firm_parent == 2 ? $row[18] : $row[9]), iconv("UTF-8", "cp1251",$num_doc), $date_rr,iconv("UTF-8", "cp1251", $row[11]),iconv("UTF-8", "cp1251", $row[12])));
		
		
		$tovar_tn  = explode('|' , iconv("UTF-8", "cp1251", $row[3]));
		FOR ($i = 0 ;  $i < count( $tovar_tn);  $i++ ){
		$tovar_tn1  = explode( '^', $tovar_tn[$i]);
		
		$tovar_id = $tovar_tn1[0];
		$tovar_total =  $tovar_tn1[1];
		
		if(!$tovar_id || !$tovar_total)
			continue;
		
		$query1 = "select t1.id,t1.p_names,t1.price,t1.units,t2.code_stat
					from
						(select id, p_names, price, units, code_stat from order_product where id = ".$tovar_id.") t1
					left join
						(select id,code_stat from directoryCodeStat) t2
					on t1.code_stat=t2.id";
		$result1 = mysql_query($query1) or die($query1);
		if($row1 = mysql_fetch_row($result1)){
		$p_names = '';
		$total = 0;
		$summ = 0;
		$summ_no_nds = 0;  
		$sum_nds = 0; 
		$price = 0;
		$p_names = iconv("UTF-8", "cp1251", $row1[1]);
		// $total = str_replace(',', '.', $tovar_total);	
		// $summ = $total * iconv("UTF-8", "cp1251", $row1[2]);
		// $nds_ = 1 + $nds/100;
		// $summ_no_nds =  $summ / $nds_;
		// $sum_nds = $summ  - $summ_no_nds;
		// $price = $summ_no_nds/$total;
		// $summ = round($summ ,2);
		// $summ_no_nds = round($summ_no_nds ,2); 
		// $sum_nds = round($sum_nds ,2);
		// $price = round($price ,2);
		// $summ = round($summ ,2);
		// $summ_no_nds = round($summ_no_nds ,2); 
		// $sum_nds = $summ - $summ_no_nds ;
		// $summ_all = $summ_all + $summ;
		
		$nds = $nds_old;
		
		$nds_ = 1 + $nds/100;
		$price = iconv("UTF-8", "cp1251", $row1[2])/ $nds_; 
		$price = round($price ,2);  
		IF(iconv("UTF-8", "cp1251", $row[17]) == '1' OR iconv("UTF-8", "cp1251", $row[17]) == 1){
		$price = iconv("UTF-8", "cp1251", $row1[2])/ $nds_; 
		$price = round($price * $nds_ ,2);
		}
		
		$total = str_replace(',', '.', $tovar_total);	
		$summ = $total * $price * $nds_;
		
		IF(iconv("UTF-8", "cp1251", $row[17]) == '1' OR iconv("UTF-8", "cp1251", $row[17]) == 1){
		$summ = $total * round($price,2) ;
		$nds = 0;
		}
		
		$summ = round($summ ,2);
		
		$summ_no_nds = round($price *$total,2); 
		$sum_nds = $summ  - $summ_no_nds;
		$summ_no_nds = round($summ_no_nds ,2); 
		$sum_nds = round($sum_nds ,2);
		
		$summ = round($summ ,2);
		$summ_no_nds = round($summ_no_nds ,2); 
		$sum_nds = round($sum_nds ,2);
		
		$summ_no_nds_all = $summ_no_nds_all  + $summ_no_nds ;
		$sum_nds_all = $sum_nds_all + $sum_nds;
		$summ_all = $summ_all + $summ;

		$units = iconv("UTF-8", "cp1251", $row1[3]);
		$sql_units = "SELECT units.codeC FROM units WHERE units.NAME='" . $row1[3] . "'";
		$result_units = mysql_query($sql_units) or die($sql_units);
		if($row_units = mysql_fetch_row($result_units)) {
			$units = $row_units[0];
		}

		
		dbase_add_record($DBF_Tovar,  array(iconv("UTF-8", "cp1251", $row1[0]) , $p_names,$p_names,"","",$units));
		dbase_add_record($DBF_RashodTv, array( iconv("UTF-8", "cp1251", $row[1]) , $tovar_id , $total ,$price,$summ_no_nds,$nds,$sum_nds,$summ, $row1[4])); 
		
		dbase_add_record($DBF_Tovar1,  array(iconv("UTF-8", "cp1251", $row1[0]) , $p_names,$p_names,"","",$units));
		dbase_add_record($DBF_RashodTv1, array( iconv("UTF-8", "cp1251", $row[1]) , $tovar_id , $total ,$price,$summ_no_nds,$nds,$sum_nds,$summ, $row1[4])); 
		
		}
		} 
		
		$date_doc1 = new DateTime(iconv("UTF-8", "cp1251", $row[0])) ;
		$date_rr = $date_doc1->format('d.m.Y');
		dbase_add_record($DBF_Rashod, array($date_rr,
		iconv("UTF-8", "cp1251", $row[1]), iconv("UTF-8", "cp1251", $row[11]),iconv("UTF-8", "cp1251", $row[12]),
		iconv("UTF-8", "cp1251", $row[2]), 
		iconv("UTF-8", "cp1251", $row[4]), iconv("UTF-8", "cp1251", $num_doc), iconv("UTF-8", "cp1251", $row[16]), "00001",$summ_all, iconv("UTF-8", "cp1251", $row[10]),iconv("UTF-8", "cp1251", $row[13]), $row[14]));
		
		dbase_add_record($DBF_Rashod1, array($date_rr,
		iconv("UTF-8", "cp1251", $row[1]), iconv("UTF-8", "cp1251", $row[11]),iconv("UTF-8", "cp1251", $row[12]),
		iconv("UTF-8", "cp1251", $row[2]), 
		iconv("UTF-8", "cp1251", $row[4]), iconv("UTF-8", "cp1251",$num_doc), iconv("UTF-8", "cp1251", $row[16]), "00001",$summ_all, iconv("UTF-8", "cp1251", $row[10]),iconv("UTF-8", "cp1251", $row[13]), $row[14]));
		
		$query = "UPDATE tn_list_par SET exp_1c = 1  WHERE id = ".$row[15];
		mysql_query($query) or die($query); 
		
		IF (iconv("UTF-8", "cp1251", $row[14]) == '1' ){
		$del++;
		$del_list .= $date_rr." ".$row[1]."; ";
		}
		
		IF (iconv("UTF-8", "cp1251", $row[13]) == 'tn') {
		$tn++;
		}
		else {
		$act++;
		}
		
		
		
		}
		//Закрываем указатель на файл
		dbase_close($DBF_Rashod);
		dbase_close($DBF_Dogovora);
		dbase_close($DBF_Kontr);
		dbase_close($DBF_Tovar);
		dbase_close($DBF_RashodTv);
		
		dbase_close($DBF_Rashod1);
		dbase_close($DBF_Dogovora1);
		dbase_close($DBF_Kontr1);
		dbase_close($DBF_Tovar1);
		dbase_close($DBF_RashodTv1);
		
		$rt = 'Кол-во накладных '.$tn.' ' ;
		$rt .=   'Кол-во АКТОВ '.$act.' ' ;
		$rt .=   'Кол-во испорченных накладных '.$del.' ' ;
		$rt .=   $del_list.' ' ;
		
		
		echo $rt;
		
		}
		
		
		}
		
		?>
				