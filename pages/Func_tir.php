<?php
	
	
	/*
		Дописать что не помещается на лист!!
		
	*/
	include 'db.php';
	
	
	$query = "select val from settings s where  s.id = 17";
	$result = mysql_query($query) or die("Query failed1");
	WHILE ($row = mysql_fetch_row($result)) {
		$alf_prih = $row[0];
	}
	
	function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
    $sort_col = array();
    foreach ($arr as $key=> $row) {
        $sort_col[$key] = $row[$col];
    }

    array_multisort($sort_col, $dir, $arr);
}
	function alg_price($flg, $ids, $price_m){
	   $max_pr = 0; 
	   $max_pr1 = 0; 
	   $max_pr2 = 0; 
		$total_pr2 = 0; 
	   $max_pr3 = 0; 
		
		$qw = "SELECT IF(ROUND((ROUND(tm.sum_all/tm.total,2) / replace((select st.val from settings_attr st where st.SET_ID = 2 and st.DATE_VAL <=  t.dt ORDER BY st.ID DESC LIMIT 1), ',', '.') ),2) > 
		ROUND((ROUND(tm.sum_all/tm.total,2) / 	 replace((select st.val from settings_attr st where st.SET_ID = 2   ORDER BY st.ID DESC LIMIT 1), ',', '.') ),2) , 
		ROUND((ROUND(tm.sum_all/tm.total,2) / replace((select st.val from settings_attr st where st.SET_ID = 2 and st.DATE_VAL <=  t.dt ORDER BY st.ID DESC LIMIT 1), ',', '.') ),2) , 
		ROUND((ROUND(tm.sum_all/tm.total,2) / 	 replace((select st.val from settings_attr st where st.SET_ID = 2   ORDER BY st.ID DESC LIMIT 1), ',', '.') ),2)) uu
		from TTN_mater tm, TTN t where tm.id_mat = ".$ids." and t.ID = tm.id_TTN and tm.sum_all > 0 ORDER BY t.dt";
		
		$rst=mysql_query( $qw);
		
		while($rsw =  mysql_fetch_row($rst)){
			//максимальный
			
			$total_pr2++;
			
			if ( (DOUBLE)$max_pr1 < (DOUBLE)$rsw[0] ) {
				$max_pr1 = (DOUBLE)$rsw[0] ;
			} 
			//cреднее
			$max_pr2 += (DOUBLE)$rsw[0];
			
			//Из последнего прихода
			$max_pr3 = (DOUBLE)$rsw[0] ; 
		}
		
		IF ($flg == 1){ //максимальный
			$max_pr = $max_pr1;
		}
		IF ($flg == 2){ //Среднее
			IF ((DOUBLE)$max_pr2 != 0 AND (DOUBLE)$total_pr2 != 0) {
				$max_pr = ROUND((DOUBLE)$max_pr2 / (DOUBLE)$total_pr2 , 2);	
			}
		}
		IF ($flg == 3){ //Из последнего прихода
			$max_pr = $max_pr3;
		}
		IF ($max_pr == 0){
			$max_pr =  $price_m;
		}
		return $max_pr;
		
	}
		function size_page($arg_1, $arg_2, $arg_3 ,$arg_4 ,$arg_5, $arg_6, $arg_7, $arg_8, $arg_9, $arg_10,  $arg_11){
		
		if ($arg_1 == 0 OR $arg_2 == 0){
		return 0;
		}
		$pol = $arg_4;
		$pol1 =  $arg_5;
		$pol_flag = $arg_6;
		$bqs =  explode("*",$arg_1);
		IF ($pol_flag == 0 OR $pol_flag == '0' ){
		$mat_size_1 = (INT)$bqs[0] - (INT)$pol1 - (INT)$pol1;
		$mat_size_2 = (INT)$bqs[1] - (INT)$pol - (INT)$pol;
		} ELSE {
		if((INT)$mat_size_1 > (INT)$mat_size_2) {
		$mat_size_1 = (INT)$bqs[0] - (INT)$pol - (INT)$pol;
		$mat_size_2 = (INT)$bqs[1] - (INT)$pol1  - (INT)$pol1;
		}
		$mat_size_1 = (INT)$bqs[0] - (INT)$pol1 - (INT)$pol1;
		$mat_size_2 = (INT)$bqs[1] - (INT)$pol - (INT)$pol;
		}
		
		$bqs1 =  explode("*",$arg_2);
		$prod_size_1 = $bqs1[0];
		$prod_size_2 = $bqs1[1];
		IF($arg_9 == "1" OR $arg_9 == 1){
		
		$kotaa = 2;
		if($arg_7 == 0 OR $arg_7 == '0'){
		if ($arg_8 == "узкой"){
		if ((INT)$prod_size_1 < (INT)$prod_size_2 ) {
		$prod_size_2 = (INT)$prod_size_2 * 2;
		} else {
		$prod_size_1 = (INT)$prod_size_1 * 2;
		}
		$kotaa = 4;
		}
		if ($arg_8 == "широкой"){
		if ((INT)$prod_size_1 > (INT)$prod_size_2 ) {
		$prod_size_2 = (INT)$prod_size_2 * 2;
		} else {
		$prod_size_1 = (INT)$prod_size_1 * 2;
		}
		$kotaa = 4;
		}
		}
		}
		$pagekol1 = 0;
		$x1 = (double)$mat_size_1 / (double)$prod_size_1;
		$x2 = (double)$mat_size_2 / (double)$prod_size_2;
		$x3 = (double)$mat_size_1 / (double)$prod_size_2;
		$x4 = (double)$mat_size_2 / (double)$prod_size_1;
		$x1 = floor($x1);
		$x3 = floor($x3);
		$x2 = floor($x2);
		$x4 = floor($x4);
		
		$y1 = $x1 * $x2;
		$y2 = $x3 * $x4;
		if(floor($y1) >= floor($y2)){
		$pagekol = floor($y1); 	//"К-во изд. на листе"
		$alg = 1;
		}
		else {
		$pagekol = floor($y2); 	//"К-во изд. на листе"
		$alg = 2;
		}
		
		
		if ($alg == 1){
		for ($y = 0; $y < $x2 ; $y++ ) {
		for ( $ii = 0; $ii < $x1 ; $ii++ ) {
		$wh = ($pol + (($y + 1) * $prod_size_2 ));
		$dl = ($pol1 + (($ii + 1) * $prod_size_1 ));
		}
		}				
		}
		
		if ($alg == 2){
		for ($y = 0; $y < $x4 ; $y++ ) {
		for ($ii = 0;$ii < $x3 ; $ii++ ) {
		$wh = ($pol + (($y + 1) * $prod_size_1 ));
		$dl = ($pol1 + (($ii + 1) * $prod_size_2 ));
		}
		}				
		}
		
		if ($arg_3 == "1" OR $arg_3 == 1){
		
		$new_mat_size_1 = $mat_size_1 - $dl ;
		$new_mat_size_2 = $mat_size_2 - $wh  ;
		
		if (($new_mat_size_1 > $prod_size_2 AND $mat_size_2 > $prod_size_1) OR ($new_mat_size_1 > $prod_size_1 AND $mat_size_2 > $prod_size_2)){
		
		$x1 = (INT)$new_mat_size_1/(INT)$prod_size_1;
		$x2 = (INT)$mat_size_2/(INT)$prod_size_2;
		$x3 = (INT)$new_mat_size_1/(INT)$prod_size_2;
		$x4 = (INT)$mat_size_2/(INT)$prod_size_1;
		
		$x1 = floor($x1);
		$x3 = floor($x3);
		$x2 = floor($x2);
		$x4 = floor($x4);
		
		$y1 = (INT)$x1 * (INT)$x2;
		$y2 = (INT)$x3 * (INT)$x4;
		
		$y1 = $x1 * $x2;
		$y2 = $x3 * $x4;
		if(floor($y1) >= floor($y2)){
		$pagekol1 = floor($y1); 	//"К-во изд. на листе"
		}
		else {
		$pagekol1 = floor($y2); 	//"К-во изд. на листе"
		
		}
		
		
		}	
		
		
		if (($new_mat_size_2 > $prod_size_2 AND $mat_size_1 > $prod_size_1) OR ($new_mat_size_2 > $prod_size_1 AND $mat_size_1 > $prod_size_2)){
		
		$x1 = (INT)$mat_size_1/(INT)$prod_size_1;
		$x2 = (INT)$new_mat_size_2/(INT)$prod_size_2;
		$x3 = (INT)$mat_size_1/(INT)$prod_size_2;
		$x4 = (INT)$new_mat_size_2/(INT)$prod_size_1;
		$x1 = floor($x1);
		$x3 = floor($x3);
		$x2 = floor($x2);
		$x4 = floor($x4);
		
		$y1 = (INT)$x1 * (INT)$x2;
		$y2 = (INT)$x3 * (INT)$x4;
		
		$y1 = $x1 * $x2;
		$y2 = $x3 * $x4;
		
		
		if(floor($y1) >= floor($y2)){
		$pagekol1 = floor($y1); 	//"К-во изд. на листе"
		}
		else {
		$pagekol1 = floor($y2); 	//"К-во изд. на листе"
		
		}
		}
		}
		
		
		$pagekol = $pagekol + $pagekol1;
		
		
		if($arg_10 != '') {
		
		
		//$listkol = $pagekol * $kotaa; //"К-во стр. на листе"	
		$form_kol = (INT)str_replace(",", ".", $arg_10);
		$listkol = (double)$pagekol * (int)$kotaa;   
		//echo  $pagekol."|". $kotaa.":". $listkol."<br>";
		if((double) $form_kol > 0 AND (double) $listkol > 0){
		$kol_pech_k = ( (double) $form_kol / (double) $listkol );
		}
		ELSE {
		$kol_pech_k = 0; 
		}
		// var kol_pech_k = Math.ceil((form_kol / listkol) * n_cir * kot_total); //Кол-во  печ. листов, каталог	
		
		
		
		//$kol_pech_k = ceil($kol_pech_k);
		//		echo $arg_10.'/'. $pagekol. '|'.$kotaa."$".$kol_pech_k. '<br>';
		$pagekol = $kol_pech_k;
		//echo $kol_pech_k."<br>";
		}
		
		
		return $pagekol ;
		}
		
		function size_page1($arg_1, $arg_2, $arg_3 ,$arg_4 ,$arg_5, $arg_6, $arg_7, $arg_8, $arg_9, $arg_10,  $arg_11){
		
		if ($arg_1 == 0 OR $arg_2 == 0){
		return 0;
		}
		$pol = $arg_4;
		$pol1 =  $arg_5;
		$pol_flag = $arg_6;
		$bqs =  explode("*",$arg_1);
		IF ($pol_flag == 0 OR $pol_flag == '0' ){
		$mat_size_1 = (INT)$bqs[0] - (INT)$pol1 - (INT)$pol1;
		$mat_size_2 = (INT)$bqs[1] - (INT)$pol - (INT)$pol;
		} ELSE {
		if((INT)$mat_size_1 > (INT)$mat_size_2) {
		$mat_size_1 = (INT)$bqs[0] - (INT)$pol - (INT)$pol1;
		$mat_size_2 = (INT)$bqs[1] - (INT)$pol  - (INT)$pol1;
		} else {
		$mat_size_1 = (INT)$bqs[1] - (INT)$pol - (INT)$pol1;
		$mat_size_2 = (INT)$bqs[0] - (INT)$pol - (INT)$pol1;
		}
		
		}
		
		$bqs1 =  explode("*",$arg_2);
		$prod_size_1 = $bqs1[0];
		$prod_size_2 = $bqs1[1];
		IF($arg_9 == "1" OR $arg_9 == 1){
		
		$kotaa = 2;
		if($arg_7 == 0 OR $arg_7 == '0'){
		if ($arg_8 == "узкой"){
		if ((INT)$prod_size_1 < (INT)$prod_size_2 ) {
		$prod_size_2 = (INT)$prod_size_2 * 2;
		} else {
		$prod_size_1 = (INT)$prod_size_1 * 2;
		}
		$kotaa = 4;
		}
		if ($arg_8 == "широкой"){
		if ((INT)$prod_size_1 > (INT)$prod_size_2 ) {
		$prod_size_2 = (INT)$prod_size_2 * 2;
		} else {
		$prod_size_1 = (INT)$prod_size_1 * 2;
		}
		$kotaa = 4;
		}
		}
		}
		$pagekol1 = 0;
		$x1 = (double)$mat_size_1 / (double)$prod_size_1;
		$x2 = (double)$mat_size_2 / (double)$prod_size_2;
		$x3 = (double)$mat_size_1 / (double)$prod_size_2;
		$x4 = (double)$mat_size_2 / (double)$prod_size_1;
		$x1 = floor($x1);
		$x3 = floor($x3);
		$x2 = floor($x2);
		$x4 = floor($x4);
		
		$y1 = $x1 * $x2;
		$y2 = $x3 * $x4;
		
		$pagekol22	 = floor($y1); 	//"К-во изд. на листе"
		$alg2 = 2;
		
		for ($y = 0; $y < $x4 ; $y++ ) {
		for ($ii = 0;$ii < $x3 ; $ii++ ) {
		$wh2 = ($pol + (($y + 1) * $prod_size_1 ));
		$dl2 = ($pol1 + (($ii + 1) * $prod_size_2 ));
		}
		}				
		
		
		$pagekol11 = floor($y2); 	//"К-во изд. на листе"
		$alg1 = 1;
		for ($y = 0; $y < $x2 ; $y++ ) {
		for ( $ii = 0; $ii < $x1 ; $ii++ ) {
		$wh1 = ($pol + (($y + 1) * $prod_size_2 ));
		$dl1 = ($pol1 + (($ii + 1) * $prod_size_1 ));
		}
		}			
		
		$pagekol = (INT)$pagekol22;
		IF ((INT)$pagekol11 > (INT)$pagekol22){
		$pagekol = (INT)$pagekol11;
		}
		
		
		$new_mat_size_11 = 0;
		$new_mat_size_21 = 0;	
		if ($arg_3 == "1" OR $arg_3 == 1){
		
		IF ($dl1 == ""){
		$dl1 = 0;
		}	
		IF ($dl2 == ""){
		$dl2 = 0;
		}	
		IF ($wh1 == ""){
		$wh1 = 0;
		}	
		IF ($wh2 == ""){
		$wh2 = 0;
		}	
		
		
		
		$new_mat_size_11 = (int)$mat_size_1 - (int)$dl1 ;
		$new_mat_size_21 = (int)$mat_size_2 - (int)$wh1  ;
		
		$new_mat_size_12 = (int)$mat_size_1 - (int)$dl2;
		$new_mat_size_22 = (int)$mat_size_2 - (int)$wh2 ;
		
		
		
		$pagekol44 = 0;
		$pagekol33 = 0;
		
		
		
		if ((($new_mat_size_11 > $prod_size_2 AND $mat_size_2 > $prod_size_1) OR ($new_mat_size_11 > $prod_size_1 AND $mat_size_2 > $prod_size_2)) AND $dl1 > 0) {
		$x1 = (INT)$new_mat_size_11/(INT)$prod_size_1;
		$x2 = (INT)$mat_size_2/(INT)$prod_size_2;
		$x3 = (INT)$new_mat_size_11/(INT)$prod_size_2;
		$x4 = (INT)$mat_size_2/(INT)$prod_size_1;
		
		$x1 = floor($x1);
		$x3 = floor($x3);
		$x2 = floor($x2);
		$x4 = floor($x4);
		
		$y1 = (INT)$x1 * (INT)$x2;
		$y2 = (INT)$x3 * (INT)$x4;
		
		$y1 = $x1 * $x2;
		$y2 = $x3 * $x4;
		if(floor($y1) >= floor($y2)){
		$pagekol33 = floor($y1); 	//"К-во изд. на листе"
		}
		else {
		$pagekol33 = floor($y2); 	//"К-во изд. на листе"
		}
		}
		if ((($new_mat_size_21 > $prod_size_2 AND $mat_size_1 > $prod_size_1) OR ($new_mat_size_21 > $prod_size_1 AND $mat_size_1 > $prod_size_2)) AND $wh1 > 0){
		
		$x1 = (INT)$new_mat_size_21/(INT)$prod_size_1;
		$x2 = (INT)$mat_size_1/(INT)$prod_size_2;
		$x3 = (INT)$new_mat_size_21/(INT)$prod_size_2;
		$x4 = (INT)$mat_size_1/(INT)$prod_size_1;
		
		$x1 = floor($x1);
		$x3 = floor($x3);
		$x2 = floor($x2);
		$x4 = floor($x4);
		
		$y1 = (INT)$x1 * (INT)$x2;
		$y2 = (INT)$x3 * (INT)$x4;
		
		$y1 = $x1 * $x2;
		$y2 = $x3 * $x4;
		if(floor($y1) >= floor($y2)){
		$pagekol33 = floor($y1); 	//"К-во изд. на листе"
		}
		else {
		$pagekol33 = floor($y2); 	//"К-во изд. на листе"
		
		}
		
		}	
		
		if ((($new_mat_size_12 > $prod_size_2 AND $mat_size_2 > $prod_size_1) OR ($new_mat_size_12 > $prod_size_1 AND $mat_size_2 > $prod_size_2)) AND $dl2 > 0){
		
		$x1 = (INT)$mat_size_2/(INT)$prod_size_1;
		$x2 = (INT)$new_mat_size_12/(INT)$prod_size_2;
		$x3 = (INT)$mat_size_2/(INT)$prod_size_2;
		$x4 = (INT)$new_mat_size_12/(INT)$prod_size_1;
		$x1 = floor($x1);
		$x3 = floor($x3);
		$x2 = floor($x2);
		$x4 = floor($x4);
		
		$y1 = (INT)$x1 * (INT)$x2;
		$y2 = (INT)$x3 * (INT)$x4;
		
		$y1 = $x1 * $x2;
		$y2 = $x3 * $x4;
		
		
		if(floor($y1) >= floor($y2)){
		$pagekol44 = floor($y1); 	//"К-во изд. на листе"
		}
		else {
		$pagekol44 = floor($y2); 	//"К-во изд. на листе"
		
		}
		}
		
		
		
		if ((($new_mat_size_22 > $prod_size_2 AND $mat_size_1 > $prod_size_1) OR ($new_mat_size_22 > $prod_size_1 AND $mat_size_1 > $prod_size_2)) AND $wh2 > 0){
		
		$x1 = (INT)$mat_size_1/(INT)$prod_size_1;
		$x2 = (INT)$new_mat_size_22/(INT)$prod_size_2;
		$x3 = (INT)$mat_size_1/(INT)$prod_size_2;
		$x4 = (INT)$new_mat_size_22/(INT)$prod_size_1;
		$x1 = floor($x1);
		$x3 = floor($x3);
		$x2 = floor($x2);
		$x4 = floor($x4);
		
		$y1 = (INT)$x1 * (INT)$x2;
		$y2 = (INT)$x3 * (INT)$x4;
		
		$y1 = $x1 * $x2;
		$y2 = $x3 * $x4;
		
		
		if(floor($y1) >= floor($y2)){
		$pagekol44 = floor($y1); 	//"К-во изд. на листе"
		}
		else {
		$pagekol44 = floor($y2); 	//"К-во изд. на листе"
		
		}
		}
		
		}
		$alg  = 2;
		IF ((INT)$pagekol11 + (INT)$pagekol44 >= (INT)$pagekol22 + (INT)$pagekol33){
		$pagekol = (INT)$pagekol11 + (INT)$pagekol44;
		$alg = 2;
		}
		else {
		$pagekol =(INT)$pagekol22 + (INT)$pagekol33;
		$alg = 1;
		}
		
		return $pagekol."|".$alg;
		
		
		}
		
		/*Входные*/
		/*$id_eq = 1;
		$size_prod = '200*300';
		$kl_mat = '7';
		//$pol = 6;
		$tiraj = 200;
		
		
		*/
		
		
		
		
		
		$strs= $_GET['str'];	
		$str_output = '';	
		//echo $strs;
		$str_part = explode("^",$strs);
		// echo  $strs."<br>";
		// echo count($str_part);
		
		FOR($e = 0 ; $e < count($str_part); $e++){
		
		$str_part1 = explode("|",$str_part[$e]);
		
		$tiraj = $str_part1[0];	
		$id_eq = $str_part1[1];	
		$kl_mat = $str_part1[2];	
		$size_	 = $str_part1[3];			
		$vin = $str_part1[4];	
		$max = $str_part1[5];	
		$size_prod = $str_part1[6];	
		$pl = $str_part1[7];	
		$p_cut2 = $str_part1[8];	
		$pers  = $str_part1[9];	
		$p_stor_i = $str_part1[10];	
		$p_kolstr = $str_part1[11];	
		$polsss = $str_part1[12];	
		$size_new = trim($str_part1[13]);
		
		if($size_new == $size_)
			$size_new = '';
		
		
		//echo $tiraj." ".$id_eq." ".$kl_mat." ".$size_." ".$vin." ".$max." ".$size_prod." ".$pl." ".$p_cut2." ".$pers." ".$p_stor_i." ".$p_kolstr." ".$polsss ." ".$size_new."<br>";	
		
		$result=mysql_query("select format from equipment where  id = ".$id_eq);
		while($cat =  mysql_fetch_row($result)){
		$size_eq = $cat[0];
		}
		
		// if($size_eq == ""){
		// $size_eq = 
		// }
		
		//echo $kl_mat." | ".$size_eq." | ".$tiraj." | ".$size_prod."<br>";
		IF ($kl_mat != "" AND $kl_mat!= "0" AND $tiraj!= "" AND $size_prod!= "" ){
		
		
		$size_eq = '';
		
		$data_mat = array (); 
		$l_samok = 0;
		$l_mel = 0;
		$l_diz = 0;
		$result = mysql_query("select id, m_name, m_price, m_size, m_kol_all,ID_M from material_attr where id_tree = ".$kl_mat." and arh = 0");
		while($row =  mysql_fetch_row($result)){
		$bqs =  explode("*",$row[3]);
		//echo $bqs[0]. " " . $bqs[1];
		$per = (int)$bqs[0] + (int)$bqs[0] + (int)$bqs[1] + (int)$bqs[1];
		
		$SIZRE = $row[3];
		$SIZRE = str_replace("x", "*", $SIZRE); //en
		$SIZRE = str_replace("X", "*", $SIZRE); //en
		$SIZRE = str_replace("х", "*", $SIZRE); //ru
		$SIZRE = str_replace("Х", "*", $SIZRE); //ru
		
		$price_s = alg_price($alf_prih,$row[0],$row[2]);
		
		
		
		$data_mat[] = array(
		'id' =>$row[0],
		'm_name' =>$row[1],
		'm_price' =>$price_s ,
		'm_size' =>$row[3],
		'm_kol_all' =>$row[4],
		'per' =>$per,
		);
		//	echo  strpos(strtolower($row[1]),'самок').'<br>';
		IF ( strpos(strtolower($row[1]),'самок') == 0){
		$l_samok = 1;
		}	
		IF ( $row[5] == '1'){
		$l_mel = 1;
		}	
		IF ( $row[5] == '6'){
		$l_diz = 1;
		}	
		
		}
		
		//echo "<br><br><br><br>".$l_samok ."<br>";
		//PRINT_R($data_mat);
		
		
		$result=mysql_query("select format from equipment where  id = ".$id_eq);
		while($cat =  mysql_fetch_row($result)){
			$size_eq = $cat[0];
		}
		
		if($size_eq == ""){
			$size_new = '-1';
		}
		
		$bqs =  explode("*",$size_prod);
		
		$size_prod = ((INT)$bqs[0] + (INT)$vin * 2) ."*".((INT)$bqs[1] + (INT)$vin  * 2);
		
		//echo $size_prod." ".$size_eq;
		
		$bsize_eq =  explode(",",$size_eq );
		
		//print_r($bsize_eq);
		
		$p_l1 = 6;
		$p_l2 = 6;
		IF ($polsss == "0" OR $polsss == 0){
		$p_l1 = 0;
		$p_l2 = 0;	
		} 
		
		
		IF($p_cut2 == '1' OR $p_cut2 == 1){
		$p_l1 = 25;
		$p_l2 = 15;
		} 
		
		
		$data = array (); 
		$l_flag_wir = 0;
		IF($size_new  == "" OR $size_new  == "0" OR $size_new  == 0){
		
			FOR($i = 0 ; $i < count($bsize_eq); $i++){
				$result=mysql_query("select SIZE from size_print where  id = ".$bsize_eq[$i]);
				while($row =  mysql_fetch_row($result)){
					
					$bqs =  explode("*",$row[0]);
					$per = (int)$bqs[0] + (int)$bqs[0] + (int)$bqs[1] + (int)$bqs[1];
					if($l_mel == 1 and $row[0] == '350*500') {
					} 
					
					elseif ($l_samok == 0 ){
						$data[] = array(
						'id' =>$bsize_eq[$i],
						'size' =>$row[0],
						'per' =>$per,
						'dlina' => '',
						'tiraj1' => '',
						);
					} 
					ELSEIF($row[0] != '297*420') {
					
						if($l_diz == 1 and $row[0] != '360*660'){
							$data[] = array(
							'id' =>$bsize_eq[$i],
							'size' =>$row[0],
							'per' =>$per,
							'dlina' => '',
							'tiraj1' => '',				
							);
						}
						if($l_diz == 0 AND $row[0] != '330*500'){
							$data[] = array(
							'id' =>$bsize_eq[$i],
							'size' =>$row[0],
							'per' =>$per,
							'dlina' => '',
							'tiraj1' => '',				
							);
						}
					
					} 
					
					ELSE {
						$data[] = array(
						'id' =>$bsize_eq[$i],
						'size' =>$row[0],
						'per' =>$per,
						'dlina' => '',
						'tiraj1' => '',				
						);
					}
				}
			}
		} else {
				
			IF ($size_new == '-1'){
				IF (count($data_mat) > 0){
					
					FOR ($u = 0; $u < count($data_mat); $u++){						
						
						$m_new_size =  $data_mat[$u]['m_size'];
						
						$bqs =  explode("*",$size_prod);
						
						$par = explode("*",$m_new_size );	
						
						
						IF (count($par) == 1){
							
							$mat_d = (INT)$bqs[0];
							if ((INT)$bqs[0] > (INT)$bqs[1]){
								$mat_d = (INT)$bqs[1] ;
							}
							
							
							
							$m_new_size = $par[0] ."*".$mat_d;
							$pagekole2 = 0;
							
							if ((int)$par[0] >= (INT)$bqs[0] OR (int)$par[0] >= (INT)$bqs[1]){
								
								while ((INT)$pagekole2 != (INT)$tiraj){
									//	echo $m_new_size."<br>";
									$pagekol23  = size_page1($m_new_size, $size_prod,1 ,$p_l1 ,$p_l2, $p_cut2,$pers,$p_stor_i,"1", $p_kolstr, 1);
									$pagekol233 = explode("|", $pagekol23);
									$pagekole2   = $pagekol233[0];
									
									IF ((INT)$pagekole2 > (INT)$tiraj ){
										$pagekole2 = (INT)$tiraj;
									}
									
									$alg_wir = $pagekol233[1];
									//$pagekol2 = $pagekol2 - 1;
									$mat_d = $mat_d  + 1;
									$par1 = explode("*",$m_new_size );	
									$m_new_size = $par1[0] . "*" . $mat_d;
								}
								//$m_new_size = $par1[0] . "*" . (int)$par1[1] + 256;
								$par1 = explode("*",$m_new_size );	
								//	$m_new_size = $par1[0] . "*" . ((INT)$par1[1] + (INT)$dob );
								
								
								$m_sum_kol = (INT)$par1[1] / 1000 ;
								$m_sum_newa = (INT)$par1[1] / 1000 * (double)$data_mat[$u]['m_price'];
								$m_sum_newa = ROUND($m_sum_newa,2);
								//echo $alg_wir."<br>";
								$list_list = $data_mat[$u]['m_name'] ."; цена = ". $data_mat[$u]['m_price'].";`";
								$data[] = array(
								'id' => $u,
								'size' =>$m_new_size,
								'per' => '',
								'dlina' => $alg_wir,
								'tiraj' => '1',
								'tiraj1' => $m_sum_kol,
								'sum_' => $m_sum_newa,
								'list_list' => $list_list,
								);	
								$l_flag_wir = 1;
							}
						} else {
						
							//$per1 = $par[0] * $par[1]; //m_new_size
							//$per2 = $bqs[0] * $bqs[1]; //size_prod
							$m_sum_newa = ROUND(((double)$data_mat[$u]['m_price'] * ((double)$bqs[0] / 1000) * ((double)$bqs[1] / 1000) ) / (((double)$par[0] / 1000) * ((double)$par[1] / 1000)),2);
							$m_sum_newa = ROUND((double)$m_sum_newa  * (double)$tiraj,2);
							$l_flag_wir = 1;
							
							$list_list = $data_mat[$u]['m_name'] ."; цена = ". $data_mat[$u]['m_price'].";`";
							$data[] = array(
								'id' => $u,
								'size' =>$size_prod,
								'per' => '',
								'dlina' => '',
								'tiraj' => $tiraj ,
								'sum_' => $m_sum_newa,
								'list_list' => $list_list,
							);	
						}
					}
				}
			} else {
				$data[] = array(
				'id' => '0',
				'size' =>$size_new,
				'per' => '',
				'dlina' => '',
				'tiraj1' => '',
				);	
			}
		
		}
		
		//print_r($data);
		//echo '<br><br><br><br>';
		$laga = 0;
		$laga = count($data_mat);
		
		
		if ($l_flag_wir == 0){
			
			
			/*Раскладываем на печатные листы, получаем кол-во печатных листов*/
			FOR ($i = 0 ; $i < count($data) ; $i++ ){	
				$pagekol = size_page($data[$i]['size'], $size_prod, $max ,$p_l1 ,$p_l2, $p_cut2,$pers,$p_stor_i,"1", $p_kolstr , 0);
				//echo $pagekol."<br>";
				$data[$i]['kol_page'] = $pagekol;	
				if($pagekol == 0 OR $tiraj == 0){
					$data[$i]['tiraj'] = 0;
				} else {
					IF ($p_kolstr != '') {
					//ECHO $tiraj ." | ". $pagekol."<br>";
						$data[$i]['tiraj'] = ceil( (INT)$tiraj * ROUND($pagekol,2));	
					} else {
						$data[$i]['tiraj'] = ceil($tiraj / $pagekol);	
					}
				
				}
			}
			//PRINT_R($data);
			// Вычисляем стоимость за единицу листа
			// Сначала надо взять листы которые совпадают по размеру
			
			FOR ($i = 0 ; $i < count($data) ; $i++ ){
				
				$bqs =  explode("*",$data[$i]['size']);
				$mat_size_1 = floatval($bqs[0]);
				$mat_size_2 = floatval($bqs[1]);
				$t_st = '';
				FOR ($y = 0 ; $y < count($data_mat) ; $y++ ){
					$bqs1 =  explode("*",$data_mat[$y]['m_size']);
					$mat_size_11 = floatval($bqs1[0]);
					$mat_size_22 = floatval($bqs1[1]);
					// размер совпадает
					IF(($mat_size_1 == $mat_size_11 and $mat_size_2 == $mat_size_22) OR ($mat_size_1 == $mat_size_22 AND $mat_size_2 == $mat_size_11)){
						//$data_mat[$y]['flags'] = '1'; // основная бумага
						//$data_mat[$y]['m_price']
						//$data_mat[$y]['m_kol_all']
						$t_st =  $t_st  . '1|1|'.$data_mat[$y]['m_price']."|".$data_mat[$y]['m_kol_all']."|".$data_mat[$y]['m_name']." ".$data_mat[$y]['m_size']."^" ; // основная бумага
					} ELSE {
						// разложить бумагу на лист получить стоимость
						//$data_mat[$y]['flags'] = '0';
						
						$kol_pages = size_page($data_mat[$y]['m_size'], $data[$i]['size'], 1,0,0,0,0,"", "0" , "", 0);
						IF ($kol_pages > 0) {
							$pross = ROUND( (DOUBLE)$data_mat[$y]['m_price'] / (DOUBLE)$kol_pages,2);
							$t_st = $t_st  . '0|'.$kol_pages.'|'.$pross."|".$data_mat[$y]['m_kol_all']."|".$data_mat[$y]['m_name']." ".$data_mat[$y]['m_size']."^" ; 
						}
					
					}
				
				}		
				$t_st = substr($t_st, 0, -1);
				$data[$i]['flags'] = $t_st;
			}
			
			// Подсчет цены за единицу бумаги
			// Формирование списка бумаги
			
			
			FOR ($i = 0 ; $i < count($data) ; $i++ ){
				$no_nal = 0;
				$kol_ = 0;
				$sum_ = 0;
				if($data[$i]['flags'] != "") {
				
					$bqs =  explode("^",$data[$i]['flags']);
					$list_list = "";
					//echo $data[$i]['flags'] ."<br><br><br>";
					
					
					// сначало те корорые у нас есть оригинальный размер
					FOR ($y = 0 ; $y < count($bqs) ; $y++ ){
						$qqs =  explode("|",$bqs[$y] );
						IF (($qqs[0] == '1' OR $qqs[0] == 1) AND  ($qqs[3] > 0 OR  $qqs[3] != '0')){
							
							IF ((INT)$data[$i]['tiraj'] > (INT)$qqs[3] ){
								$kol_ = $kol_ + (INT)$qqs[3] ;
								$sum_ = $sum_ + round((INT)$qqs[3] * (double)$qqs[2],2); // цена за все листы
								$list_list = $list_list . $qqs[4] .": кол-во =". $qqs[3]."; цена = ". $qqs[2]."; На листе = ".$qqs[1].";`";
							} ELSE {
								$kol_ = $kol_ + (INT)$data[$i]['tiraj'] ;
								$sum_ = $sum_ + round((INT)$data[$i]['tiraj']* (double)$qqs[2],2); // цена за все листы
								$list_list = $list_list . $qqs[4] .": кол-во =". $data[$i]['tiraj']."; цена = ". $qqs[2]."; На листе = ".$qqs[1].";`";
							}
						} 	
					}
					
					
					$arra = array (); 
					
					
					FOR ($y = 0 ; $y < count($bqs) ; $y++ ){
						$qqs =  explode("|",$bqs[$y] );
						   
						$arra[] = array(
							'id' =>$y,
							'price' => $qqs[2]
						);
					
					}
				  
					array_sort_by_column($arra, 'price');  
				  
					
					FOR ($y = 0 ; $y < count($arra) ; $y++ ){
						
						$qqs =  explode("|",$bqs[$arra[$y]['id']] );
						IF (($qqs[0] == '0' OR $qqs[0] == 0) AND ($qqs[3] > 0 OR  $qqs[3] != '0')){
							$ost = 0;
							//сколько недостоет 
							$ost = (INT)$data[$i]['tiraj'] - (INT)$kol_;
							
							IF ($ost > 0){
								if((INT)$ost == 0 OR $qqs[1] == 0 ){
									$kol_big_page = 0; // кол-во больших листов
								} else {
									$kol_big_page = ceil((INT)$ost / (INT)$qqs[1]); // кол-во больших листов
								}
								
								
								IF ($kol_big_page > (INT)$qqs[3] ){
									$kol_ = $kol_ + ((INT)$qqs[3] * (INT)$qqs[1] );
									$sum_ = $sum_ + round(((INT)$qqs[3]  * ((double)$qqs[2] * (double)$qqs[1] )),2);
									$list_list = $list_list . $qqs[4] .": кол-во =". $qqs[3]."; цена = ". ((double)$qqs[2] * (double)$qqs[1] )."; На листе = ".$qqs[1].";`";
								} ELSE {
									$kol_ = $kol_ + (INT)$ost;
									//	IF($size_new  == "" OR $size_new  == "0" OR $size_new  == 0){
									$sum_ = $sum_ + round(((INT)$ost  * ((double)$qqs[2] * (double)$qqs[1] ) / $qqs[1] ),2);
									//	} else {
									//		$sum_ = $sum_ + round(((INT)$kol_big_page  * (double)$qqs[2]),2);
									//}
									
									$list_list = $list_list . $qqs[4] .": кол-во =". $kol_big_page."; цена = ". ((double)$qqs[2] * (double)$qqs[1] )."; На листе = ".$qqs[1].";`";
								}
							
							}
						} 	
					}
					
					// ДЛЯ ЗАКАЗА БУМАГИ
					$zakaz = "";
					// Подсчет бумаги и цены берем основную	
					$ost = (INT)$data[$i]['tiraj'] - (INT)$kol_;
					
					$no_nal = $ost;
					IF ($ost > 0){
						
						FOR ($y = 0 ; $y < count($bqs) ; $y++ ){
							$qqs =  explode("|",$bqs[$y] );
							
							IF (($qqs[0] == '1' OR $qqs[0] == 1) AND $ost > 0) {
							
								$zakaz = $zakaz. $qqs[4]." Кол-во ". ((INT)$data[$i]['tiraj'] - (INT)$ost)." шт ;";
								$kol_ = (INT)$data[$i]['tiraj'] - (INT)$kol_ ;
								$sum_ = $sum_ + round(((INT)$ost) * (double)$qqs[2],2); // цена за все листы
								$list_list = $list_list ."Нет материала!!!" .$qqs[4] .": кол-во =". $ost ."; цена = ". $qqs[2]."; На листе = ".$qqs[1].";`";
								$ost = $ost - $kol_;
							} 	
						}	
					}
					
					IF ((INT)$data[$i]['tiraj'] != (INT)$kol_){
						FOR ($y = 0 ; $y < count($bqs) ; $y++ ){
							$qqs =  explode("|",$bqs[$y] );
							$ost1 = (INT)$data[$i]['tiraj'] - (INT)$kol_;
							
							IF ($ost1 > 0){
								
								IF (($qqs[0] == '1' OR $qqs[0] == 0) AND $ost > 0) {
									
									$zakaz = $zakaz. $qqs[4]." Кол-во ". (ceil(((INT)$data[$i]['tiraj'] - (INT)$kol_) / (INT)$qqs[1]))." шт;";
									$kol_big_page = ceil(((INT)$data[$i]['tiraj'] - (INT)$kol_) / (INT)$qqs[1]); // кол-во больших листов
									$KOL_1 = (INT)$data[$i]['tiraj'] - (INT)$kol_;
									//	ECHO $size_new ."|".round(((INT)$data[$i]['tiraj'] - (INT)$kol_) * ((double)$qqs[2] / (INT)$qqs[1]),2)."<BR>";
									$kol_ = (INT)$data[$i]['tiraj'] - (INT)$kol_;
									IF($size_new  == "" OR $size_new  == "0" OR $size_new  == 0){
										$sum_ = (double)$sum_ + round( $KOL_1 * (((double)$qqs[2] * (double)$qqs[1] ) / (INT)$qqs[1]), 2); // цена за все листы
										//		echo $sum_ . "<br>"; 
									} else {
										$sum_ = $sum_ + round(((INT)$kol_big_page  * ((double)$qqs[2] * (double)$qqs[1] )),2);
									}
									
									
									$list_list = $list_list ."Нет материала!!!" .$qqs[4] .": кол-во =". $kol_big_page  ."; цена = ". ((double)$qqs[2] * (double)$qqs[1] )."; На листе = ".$qqs[1].";`";
									$ost = $ost - $kol_;
								} 	
							}	
						}
					}
					$data[$i]['kol_'] = $kol_;
					$data[$i]['sum_'] = $sum_;
					$data[$i]['zakaz'] = $zakaz;
					$data[$i]['list_list'] = $list_list;
				}
			}
		}
		$price_ = 0;
		$id_price_ = 0;
		$l_falga = false;
		$per = 0;
		$kol_list = 0;
		//print_R($data);
		
		
		$zakaz_1 = "";
		IF (count($data) >0){
		
			$result = mysql_query("select size_rez from material_attr where id_tree = ".$kl_mat." and arh = 0 and (size_rez <> null OR size_rez <> '') LIMIT 1");
			while($row =  mysql_fetch_row($result)){ $size_maa = $row[0]; }
			
			
			IF ($size_maa != '') {
				$data_mat_Sq = array (); 
				$result = mysql_query("select sp.size from size_print  sp where sp.id in (".$size_maa.")");
				while($row =  mysql_fetch_row($result)){
					$data_mat_Sq[] = $row[0];
				}
			
			}
			
			IF (count($data_mat_Sq) >0){
				FOR ($i = 0 ; $i < count($data) ; $i++ ){
					FOR ($y = 0 ; $y < count($data_mat_Sq) ; $y++ ){
						IF($data[$i]['size'] ==  $data_mat_Sq[$y]){
							$data[$i]['size_l'] = '1';
						}
					}		
				}
			}
			
			FOR ($i = 0 ; $i < count($data) ; $i++ ){
				
				IF($data[$i]['sum_'] != ""){
					
					IF (count($data_mat_Sq) == 0 OR (count($data_mat_Sq) >0 AND $data[$i]['size_l'] == '1')) {
						
						
						IF($price_ == 0 ){
							$price_ = $data[$i]['sum_'] +  ((INT)$data[$i]['tiraj'] * 0.1) ;
							$id_price_ = $i;
							$per = $data[$i]['per'];
							$kol_list = $data[$i]['tiraj'];
							$zakaz_1 = $data[$i]['zakaz'];
						
						
						} ELSEIF($price_ > ($data[$i]['sum_'] + ((INT)$data[$i]['tiraj'] * 0.1)) AND $data[$i]['sum_'] > 0 AND ($data[$i]['zakaz'] == "" OR $zakaz_1 != "")){
							$price_ = ($data[$i]['sum_'] + ((INT)$data[$i]['tiraj'] * 0.1));
							$id_price_ = $i;
							$per = $data[$i]['per'];
							$kol_list = $data[$i]['tiraj'];
							$zakaz_1 = $data[$i]['zakaz'];
						} ELSEIF($price_ == ($data[$i]['sum_'] + ((INT)$data[$i]['tiraj'] * 0.1)) AND $kol_list > $data[$i]['tiraj'] AND ($data[$i]['zakaz'] == "" OR $zakaz_1 != "")){
							$price_ = ($data[$i]['sum_'] + ((INT)$data[$i]['tiraj'] * 0.1));
							$id_price_ = $i;
							$per = $data[$i]['per'];
							$kol_list = $data[$i]['tiraj'];
							$zakaz_1 = $data[$i]['zakaz'];
						} ELSEIF($price_ == ($data[$i]['sum_'] + ((INT)$data[$i]['tiraj'] * 0.1)) AND $kol_list == $data[$i]['tiraj'] AND  $per < $data[$i]['per'] AND $data[$i]['sum_'] > 0 AND ($data[$i]['zakaz'] == "" OR $zakaz_1 != "")){
							$price_ = ($data[$i]['sum_'] + ((INT)$data[$i]['tiraj'] * 0.1));
							$id_price_ = $i;
							$per = $data[$i]['per'];
							$kol_list = $data[$i]['tiraj'];
							$zakaz_1 = $data[$i]['zakaz'];
						
						}
					}
				}
				
				// Echo "Размер ".$data[$i]['size']."<br>";
				// Echo "Кол-во на листе изделий ".$data[$i]['kol_page']."<br>";
				// Echo "Кол-во листов ".$data[$i]['tiraj']."<br>";
				// Echo "Цена за лист ". ROUND(((double)$data[$i]['sum_'] / (INT)$data[$i]['tiraj'] ),2)."<br>";
				// Echo "Стоимость листов ".$data[$i]['sum_']."<br>";
				// if($laga == 0 OR $laga == '0'){
				// Echo "Не задана на узле бумага!!<br><br><br>";
				// } else 		Echo "Заказ ".$data[$i]['zakaz']."<br><br><br>";
				
			}
		} ELSE {
			$l_falga = true;
		}
		
		$shema = "";
		FOR ($i = 0; $i < count($data); $i++){
			IF ((INT)$data[$i]['tiraj'] > 0 AND (double)$data[$i]['sum_'] > 0) {
				$shema = $shema.$data[$i]['size']."%".$data[$i]['tiraj']."%". ROUND($data[$i]['sum_'] / $data[$i]['tiraj'] ,2)."%".$data[$i]['sum_']."%".$data[$i]['list_list']."@";
			} ELSE {
				$shema = $shema.$data[$i]['size']."%".$data[$i]['tiraj']."%0%".$data[$i]['sum_']."%".$data[$i]['list_list']."@";
			}
		
		}
		
		IF($l_falga){
			$str_output .=  '0|0*0|Ошибка не задан на узле бумаги!|'.$shema.'|0|0|1^';
		} ELSE {
			if ((double)$data[$id_price_]['sum_'] == 0 OR (INT)$data[$id_price_]['tiraj'] == 0){
				$str_output .=  '0|'.$data[$id_price_]['size'].'|Нет расчета!|'.$shema.'|'.$l_flag_wir.'|0|'.$data[$id_price_]['dlina'].'^';
				//	$str_output .=  ROUND(((double)$data[$id_price_]['sum_'] / (INT)$data[$id_price_]['tiraj'] ),2).'|'.$data[$id_price_]['size'].'|'.$data[$id_price_]['zakaz'].'^';
			} else {
				$str_output .=  ROUND(((double)$data[$id_price_]['sum_'] / (double)$data[$id_price_]['tiraj'] ),2).'|'.$data[$id_price_]['size'].'|'.$data[$id_price_]['zakaz'].'|'.$shema.'|'.$l_flag_wir.'|'.$data[$id_price_]['tiraj1'].'|'.$data[$id_price_]['dlina'].'^';
			}
		
		}
		
		} else {
			$str_output .=  '0|0*0|Нет расчета!|'.$shema.'|0|0|1^';
		}
		}
		echo 	$str_output;
		
		
		
		
		?>		
		
				