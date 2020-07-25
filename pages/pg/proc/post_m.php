<?php
	
	function num2strbyn1($num) {
		$nul='ноль';
		$ten=array(
		array('','один','два','три','четыре','п€ть','шесть','семь', 'восемь','дев€ть'),
		array('','одна','две','три','четыре','п€ть','шесть','семь', 'восемь','дев€ть'),
		);
		$a20=array('дес€ть','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'п€тнадцать','шестнадцать','семнадцать','восемнадцать','дев€тнадцать');
		$tens=array(2=>'двадцать','тридцать','сорок','п€тьдес€т','шестьдес€т','семьдес€т' ,'восемьдес€т','дев€носто');
		$hundred=array('','сто','двести','триста','четыреста','п€тьсот','шестьсот', 'семьсот','восемьсот','дев€тьсот');
		$unit=array( // Units
		array('коп.' ,'коп.' ,'коп.',	 1),
		array('руб.'   ,'руб.'    ,'руб.'     ,0),
		array('тыс€ча'  ,'тыс€чи'  ,'тыс€ч'     ,1),
		array('миллион' ,'миллиона','миллионов' ,0),
		array('миллиард','милиарда','миллиардов',0),
		);
		//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out = array();
		if (intval($rub)>0) {
			foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
				if (!intval($v)) continue;
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
			} //foreach
		}
		else $out[] = $nul;
		$out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
		$out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}		
	
	function num2strbyn($num) {
		$nul='ноль';
		$ten=array(
		array('','один','два','три','четыре','п€ть','шесть','семь', 'восемь','дев€ть'),
		array('','одна','две','три','четыре','п€ть','шесть','семь', 'восемь','дев€ть'),
		);
		$a20=array('дес€ть','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'п€тнадцать','шестнадцать','семнадцать','восемнадцать','дев€тнадцать');
		$tens=array(2=>'двадцать','тридцать','сорок','п€тьдес€т','шестьдес€т','семьдес€т' ,'восемьдес€т','дев€носто');
		$hundred=array('','сто','двести','триста','четыреста','п€тьсот','шестьсот', 'семьсот','восемьсот','дев€тьсот');
		$unit=array( // Units
		array('коп.' ,'коп.' ,'коп.',	 1),
		array('руб.'   ,'руб.'    ,'руб.'     ,0),
		array('тыс€ча'  ,'тыс€чи'  ,'тыс€ч'     ,1),
		array('миллион' ,'миллиона','миллионов' ,0),
		array('миллиард','милиарда','миллиардов',0),
		);
		//
		list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
		$out = array();
		if (intval($rub)>0) {
			foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
				if (!intval($v)) continue;
				$uk = sizeof($unit)-$uk-1; // unit key
				$gender = $unit[$uk][3];
				list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
				// mega-logic
				$out[] = $hundred[$i1]; # 1xx-9xx
				if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
				else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
				// units without rub & kop
				if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
			} //foreach
		}
		else $out[] = $nul;
		
		
		return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
	}
	
	
	
	function mb_ucasefirst($stri){ 
		if($stri{0}>="\xc3") 
		return (($stri{1}>="\xa0")?($stri{0}.chr(ord($stri{0})-32)):($stri{0}.$stri{0})).substr($stri,1); 
		else return ucfirst($stri); 
	} 
	
	
	
	function morph($n, $f1, $f2, $f5) {
		$n = abs(intval($n)) % 100;
		if ($n>10 && $n<20) return $f5;
		$n = $n % 10;
		if ($n>1 && $n<5) return $f2;
		if ($n==1) return $f1;
		return $f5;
	}
	
	
	
	include '../../firewall.php';
	
	define('FPDF_FONTPATH','../../../PHPpdf/font/');
	require_once '../../../PHPpdf/fpdf.php';
	
	//require_once '../../../PHPpdf/lib/pdftable.inc.php';
	require('code39.php');
	include "../../db.php";
	
	session_start();
	$login = $_SESSION['login'];
	
	
	
	$id = $_GET['id'];
	
	
	$qw = "select * from mail where id = ".$id ;
	$result = mysql_query($qw) or die($qw);
	while ($row = mysql_fetch_row($result)) { 
		$id_ord = $row[1]; //'номер заказа',
		$fio = iconv("UTF-8", "cp1251", $row[2]); //'‘»ќ',
		$obl = iconv("UTF-8", "cp1251", $row[3]); //'ќбласть',
		$city = iconv("UTF-8", "cp1251", $row[4]); //'√ород',
		$house_num = iconv("UTF-8", "cp1251", $row[5]); //'номер дома',
		$room = iconv("UTF-8", "cp1251", $row[6]); //'квартира',
		$korp = iconv("UTF-8", "cp1251", $row[7]); //'корпус',
		$phone =  iconv("UTF-8", "cp1251", $row[8]); //'телефон',
		$index_ = iconv("UTF-8", "cp1251",  $row[9]); //'индекс',
		$price =  iconv("UTF-8", "cp1251", $row[10]); //'цена',
		$street = iconv("UTF-8", "cp1251",  $row[11]);  //'улица',
		$view_ =  iconv("UTF-8", "cp1251", $row[12]); //'¬ид оплаты',
		$id_cl =  iconv("UTF-8", "cp1251", $row[13]); //'ид клиента',
		$user_login = iconv("UTF-8", "cp1251",  $row[14]); //,
		$date_otpr =  iconv("UTF-8", "cp1251", $row[15]);  //'дата отправки',
		$goods = iconv("UTF-8", "cp1251",  $row[16]); //'товары которые отправили',
		$track_cod =  iconv("UTF-8", "cp1251", $row[17]); //'трек код\\\\\\\\',
		$raion =  iconv("UTF-8", "cp1251", $row[21]); //'район\\\\\\\\',
	}
	
	if ($raion != "") {
		$raion .= " р-он";
		}
	$addr  = '';
	IF ($street != "") {
		$addr = $addr . "ул. " . $street;
	}
	
	
	IF ($house_num != "") {
		$addr = $addr . ", д. ".$house_num;
	}
	
	
	IF ($korp != "") {
		$addr .= ", корпус ".$korp;
	}
	
	IF ($room != "") {
		$addr .= ", кв ".$room;
	}
	
	
	
	
	
	if (	$price != ""){
		
		$price = str_replace(".",",",$price);
		$price_arr = explode(",",$price) ;
		$pri = $price_arr[0];
		$kop = $price_arr[1];
		$pri_str = num2strbyn($price_arr[0]);
		
	}
	
	else {
		
		$pri_str = '------';
		$pri=  '------';
		$kop  = '---';
	}
	
	
	
	
	$pdf = new PDF_Code39( 'L', 'mm', 'A5');
	
	
	$pdf->AddFont('ArialMT','','arial.php');
	$pdf->AddPage();
	
   
	$pdf->SetFont('ArialMT','',10);
	
	
	$pdf->Image('post1_m.jpg',0,0,210);
	$pdf->Ln(22);
	$pdf->Cell(154,5,'',0,0);
	$pdf->Cell(10,5,$pri,0,0);
	$pdf->Cell(14,5,'',0,0); 
	$pdf->Cell(0,5, $kop,0,0); 
	
	$pdf->Ln(4);
	$pdf->Cell(114,5,'',0,0);
	$pdf->Cell(10,5,$pri_str,0,0);
	$pdf->Cell(54,5,'',0,0); 
	$pdf->Cell(0,5,$kop ,0,0);


	if(!empty($track_cod)) {
		$pdf->Code39(100, 60, $track_cod, 1.2, 20);
	}
	
	$pdf->SetFont('ArialMT','',10);
	$pdf->Ln(36);
	$pdf->Cell(115,0,'',0,0); 
	$pdf->Cell(0,50,substr($fio, 0 , 41),0,0); 
	$pdf->Ln(5);
	$pdf->Cell(100,0,'',0,0); 
	$pdf->Cell(0,50,substr($fio, 42),0,0); 
	
	$pdf->Ln(22);
	$pdf->Cell(115,0,'',0,0); 
	$pdf->Cell(0,15,$addr,0,0); 
	
	IF($obl != ""){
		$pdf->Ln(4);
		$pdf->Cell(105,0,'',0,0); 
		$pdf->Cell(0,15,$index_. ", ".  $city .", " .$raion ,0,0); 
		$pdf->Ln(4);
		$pdf->Cell(105,0,'',0,0); 
		$pdf->Cell(0,15,$obl,0,0); 
		
	} else {
		$pdf->Ln(4);
		$pdf->Cell(105,0,'',0,0); 
		$pdf->Cell(0,15,$index_. ", ". $city.", " .$raion ,0,0); 
	}
	
	$pdf->Ln(17);
	$pdf->Cell(135,0,'',0,0); 
	$pdf->Cell(0,0,$phone,0,0); 
	
	$pdf->AddPage();
	
   
	$pdf->SetFont('ArialMT','',10);
	
	//вторая страница
	
	$pdf->Image('post2_m.jpg',0,0,210);
	$pdf->Ln(35);
	$pdf->Cell(10,0,'',0,0); 
	$pdf->Cell(10,0,$price,0,0);
	$pdf->Cell(20,0,'',0,0); 
	$pdf->SetFont('ArialMT','',9);
	$pdf->Cell(111,0,num2strbyn1(str_replace(",",".",$price)),0,0); 
	$pdf->Ln(13);
	$pdf->Cell(20,0,'',0,0);
	if(!empty($phone)) {
		$pdf->Cell(0,50,$fio.", ".$phone,0,0);
	}
	else {
		$pdf->Cell(0,50,$fio,0,0);
	}
	$pdf->Ln(6);
	$pdf->Cell(15,0,'',0,0); 
	$pdf->Cell(0,50,$addr,0,0); 

	IF($obl != ""){
		$pdf->Ln(7);
		$pdf->Cell(10,0,'',0,0); 
		$pdf->Cell(0,50,$index_. ", ". $city. ", " .$raion. ", ". $obl,0,0); 
	} else {
		$pdf->Ln(7);
		$pdf->Cell(10,0,'',0,0); 
		$pdf->Cell(0,50,$index_. ", ". $city.", " .$raion,0,0); 
	}

	$pdf->Ln(57);
	$pdf->Cell(50,0,'',0,0); 
	$pdf->Cell(0,0,$track_cod,0,0);

$pdf->Output();



?>
