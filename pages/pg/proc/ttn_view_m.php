<?php

require_once 'functions.php';

define('FPDF_FONTPATH','../../../PHPpdf/font/');
/*require_once '../../../PHPpdf/fpdf.php';*/

require_once '../../../PHPpdf/lib/pdftable.inc.php';

include "../../db.php";

function num2strbyn1($num) {
			$nul='ноль';
		$ten=array(
		array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
		array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
		);
		$a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
		$tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
		$hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
		$unit=array( // Units
		array('коп.' ,'коп.' ,'коп.',	 1),
		array('руб.'   ,'руб.'    ,'руб.'     ,0),
		array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
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


session_start();
$login = $_SESSION['login'];

$query = "select val from settings s where s.id  = 4";

$result = mysql_query($query) or die($query);

IF ($row = mysql_fetch_row($result)) { 
	$nds = $row[0];

	}
IF ($nds ==''){
	$nds = 0;
	} ELSE {
$nds = (double)str_replace(',', '.', $nds);
}
$nds_ = 1 + $nds/100;
$eid = $_GET['eid'];
$face_to = $_GET['face_to'];
$to_face = $_GET['to_face'];
$pricep = $_GET['pricep'];
$list_put = $_GET['list_put'];
$number = $_GET['number'];
$str = $_GET['srt'];
$flafff = $_GET['flafff'];
$seriass = $_GET['seriass'];
$dates = $_GET['dates'];
$no_nds = $_GET['no_nds'];
$date_create = DATE("Y-m-d");
$num_dover = $_GET['num_dover'];
$date_dover = $_GET['date_dover'];
$pogr = $_GET['pogr'];
$razg = $_GET['razg'];




$date_beg = new DateTime($_GET['dates']) ;
$DATE_OR = $date_beg->format('d F Y');
$DATE_ORa = $date_beg->format('d.m.Y');

$DATE_OR  = str_replace('January','Января',$DATE_OR);
$DATE_OR  = str_replace('February','Февраля',$DATE_OR);
$DATE_OR  = str_replace('March','Марта',$DATE_OR);
$DATE_OR  = str_replace('April','Апреля',$DATE_OR);
$DATE_OR  = str_replace('May','Мая',$DATE_OR);
$DATE_OR  = str_replace('June','Июня',$DATE_OR);
$DATE_OR  = str_replace('July','Июля',$DATE_OR);
$DATE_OR  = str_replace('August','Августа',$DATE_OR);
$DATE_OR  = str_replace('September','Сентября',$DATE_OR);
$DATE_OR  = str_replace('October','Октября',$DATE_OR);
$DATE_OR  = str_replace('November','Ноября',$DATE_OR);
$DATE_OR  = str_replace('December','Декабря',$DATE_OR);

$parent_company = 1;
$query = "select user_id, client_id , DATE_FORMAT(DATE_OR, '%d %M %Y'),DATE_FORMAT(DATE_OR, '%d.%m.%Y'),CUR_ID, parent_company from orders where number = ".$eid;
$result = mysql_query($query) or die($query);
if($row = mysql_fetch_row($result)){
	$users = $row[0];
	$clients = $row[1];

	$DATE_ = $row[3];
	$cur_val = $row[4];
	$parent_company = $row[5];

}
$users = $_SESSION['login'];
$query = "select user_fio, user_mail, user_post  from users where user_login = '".$users."';";
$result = mysql_query($query) or die("Query failed");
if($row = mysql_fetch_row($result)) {
    $user_fio = $row[0];
    $user_mail = $row[1];
    $user_post = $row[2];
}

if($users != '026' && $users != '033') {
    $user_fio = "Мякишев Е.В.";
    $user_post ="Директор";
	$user_fio = iconv("cp1251", "UTF-8", $user_fio);
	$user_post = iconv("cp1251", "UTF-8", $user_post);
}


$query = "select c.CLIENT_NAME, c.ADDRESS_POST,c.PHONE_CITY,c.PHONE_MOB,c.EMAIL,c.ACCT,c.BANK,c.CODE_BANK,c.fio_dir1, c.num_doc, c.UNP, c.num_doc_m from clients c  where c.ID = ".$clients;
$result = mysql_query($query) or die("Query failed");
if($row = mysql_fetch_row($result)){
$CLIENT_NAME = $row[0];
$ADDRESS_POST = $row[1];
$PHONE = $row[2]." ".$row[3];
$EMAIL = $row[4];
$ACCT = $row[5];
$BANK = $row[6];
$CODE_BANK = $row[7];
$fio_dir1 = $row[8];
$num_doc = $row[9];
$ttn_company_unp =  $row[10];
$num_doc_m = $row[11];
}
$ttn_artline_unp = 291546971;

$flag_post = 1;
if($flag_post == 1){
	$ttn_company_unp1 = $ttn_company_unp ;
} else {
   $ttn_company_unp1 = $ttn_artline_unp;
}
/*формирование ТТН*/

$pdf = new PDFTable('P', 'mm','A4');
$pdf->AddFont('ArialMT', '', 'arial.php');
$pdf->AddFont('ArialMT', 'B', 'arial_bold.php');
$pdf->SetFont('ArialMT','', 7);
$pdf->SetTopMargin(7);
$pdf->SetLeftMargin(10);
$pdf->SetRightMargin(7);
$pdf->AddPage();



/*УНП верхняя часть*/


$table_html = <<<TABLE
<table align=center CELLSPACING=0 cellpadding=0>
	
	<tr>
		<td border=0 bordercolor=#fff valign="middle" align="right"></td>
		<td border=0 bordercolor=#000 valign="middle" align="center"></td>
		<td border=0 bordercolor=#000 valign="middle" align="center"></td>
		<td border=0 bordercolor=#000 valign="middle" align="center"></td>
	</tr>
		<tr>
		<td border=0 bordercolor=#fff valign="middle" align="right"></td>
		<td border=0 bordercolor=#000 valign="middle" align="center"></td>
		<td border=0 bordercolor=#000 valign="middle" align="center"></td>
		<td border=0 bordercolor=#000 valign="middle" align="center"></td>
	</tr>
	<tr>
		<td border=0 bordercolor=#fff bordercolor="#000" valign="middle" align="center">&nbsp; </td>
		<td border=1 bordercolor=#000 bordercolor="#000" valign="middle" align="center">Грузоотправитель </td>
		<td border=1 bordercolor=#000 bordercolor="#000" valign="middle" align="center">Грузополучатель</td>
		<td border=1 bordercolor=#000 bordercolor="#000" valign="middle" align="center">Заказчик <br>автомобильной <br> перевозки</td>
	</tr>
	<tr>
		<td border=0 bordercolor=#fff valign="middle" align="right">УНП</td>
		<td border=1 bordercolor=#000 valign="middle" align="center">$ttn_artline_unp</td>
		<td border=1 bordercolor=#000 valign="middle" align="center">$ttn_company_unp</td>
		<td border=1 bordercolor=#000 valign="middle" align="center">$ttn_company_unp1</td>
	</tr>
</table>
TABLE;
$pdf->htmltable($table_html);

/*Пропускаем место*/
$pdf->Ln();
$table_html = '<table >
	<tr> 	<td VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;</td> </tr>
	<tr> 	<td VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;</td> </tr>
	<tr> 	<td VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;</td> </tr>
	<tr> 	<td VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;</td> </tr>
	<tr> 	<td VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;</td> </tr>

</table>';
$pdf->htmltable($table_html);
$pdf->Ln();

/*сведения о машине водителе*/

	$pdf->Image('11.png',6,6,50,50);
 $pdf->Cell(0,0,$DATE_OR ,0,0);
 $pdf->Ln();
 $pdf->Cell(25,6,'Автомобиль',0,0);
 $pdf->Cell(50,6,iconv("UTF-8", "cp1251",$to_face),0,0);
 $pdf->Cell(12,6,'Прицеп',0,0);
 $pdf->Cell(50,6,iconv("UTF-8", "cp1251", $pricep),0,0);
 $pdf->Cell(25,6,'К путевому листу №',0,0);
 $pdf->Cell(30,6,iconv("UTF-8", "cp1251",$list_put),0,0);
 $pdf->Ln();
 $pdf->Line(35,62,80,62);   $pdf->Line(97,62,120,62); $pdf->Line(170,62,203,62); 
 $pdf->SetFont('ArialMT','', 4);
  $pdf->Cell(35,0,' ',0,0);
 $pdf->Cell(52,0,'(марка, государственный номер)',0,0);
  $pdf->Cell(50,0,'(марка, государственный номер)',0,0);
  $pdf->SetFont('ArialMT','', 7);
 $pdf->Ln();
 $pdf->Cell(25,6,'Водитель',0,0);
 $pdf->Cell(50,6,iconv("UTF-8", "cp1251",$face_to),0,0);
  $pdf->Ln();
 $pdf->Line(35,68,203,68);  
 $pdf->SetFont('ArialMT','', 4);
  $pdf->Cell(90,0,' ',0,0);
 $pdf->Cell(52,0,'(фамилия, инициалы)',0,0);
 
  $pdf->SetFont('ArialMT','', 7);

 $pdf->Ln();
 $pdf->Cell(60,6,'Заказчик автомобильной перевозки (плательщик)',0,0);
 if($flag_post == 1){
	 $pdf->Cell(50,6,iconv("UTF-8", "cp1251", $CLIENT_NAME),0,0); 
 } else {
  $pdf->Cell(50,6,'Частное предприятие "Мечта клиента"',0,0);
 }

  $pdf->Ln();
 $pdf->Line(70,74,203,74); 
 $pdf->SetFont('ArialMT','', 4);
  $pdf->Cell(90,0,' ',0,0);
 $pdf->Cell(52,0,'(наименование, адрес)',0,0);
 
  $pdf->SetFont('ArialMT','', 7);

 $pdf->Ln();
 $pdf->Cell(25,6,'Грузоотправитель',0,0);
 $pdf->Cell(50,6,'Частное предприятие "Мечта клиента", 224030, г. Брест, ул. Карбышева, 74',0,0);
  $pdf->Ln();
 $pdf->Line(35,80,203,80); 
 $pdf->SetFont('ArialMT','', 4);
  $pdf->Cell(90,0,' ',0,0);
 $pdf->Cell(52,0,'(наименование, адрес)',0,0);
 $pdf->Ln();
// $pdf->Line(33,65,203,65); 
  $pdf->SetFont('ArialMT','', 7);
 $pdf->Ln();

 $pdf->Ln();
 // $pdf->Cell(25,6,'Грузополучатель',0,0);
 $table_html = '<TABLE
  <table cellpadding=0 cellspacing=0 width=100%>
		<tr>
		<td border=0 colspan=2>Грузополучатель</td>
			<td border=0 colspan=22> '.iconv("UTF-8", "cp1251", $CLIENT_NAME).', '.iconv("UTF-8", "cp1251", $ADDRESS_POST).'</td>
		</tr>
		<tr>
		
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		</tr>
 </table>';
 $pdf->htmltable($table_html);

 $pdf->Ln();
  
  $y_coord =  $pdf->GetY() -1;
 $pdf->Line(35,$y_coord,203,$y_coord); 
 $pdf->SetFont('ArialMT','', 4);
 $pdf->Cell(90,0,' ',0,0);
 $pdf->Cell(52,0,'(наименование, адрес)',0,0);
 $pdf->Ln();
 //$pdf->Line(33,65,203,65); 
  $pdf->SetFont('ArialMT','', 7);
 $pdf->Ln();
 
/*основание отпуска и проч пункт погрузки разгрузки*/

 $table_html = '<TABLE

<table width=100% cellpadding=0 celspacing=0>
	<tr>
		<td colspan=3 valign=middle>Основание отпуска</td>
		<td colspan=6 valign=middle>Договор № '.iconv("UTF-8", "cp1251", (($parent_company == 2) ? $num_doc_m : $num_doc)).', счет-протокол № '.$eid.' от '.$DATE_.'г.</td>
		<td colspan=3 valign=middle align=right>Пункт погрузки</td>
		<td colspan=5 valign=middle>'.iconv("UTF-8", "cp1251", $pogr).'</td>
		<td colspan=3 valign=middle align=right>Пункт разгрузки</td>
		<td colspan=4 valign=middle align=left>'.iconv("UTF-8", "cp1251",$razg).' </td>
	<tr>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="1">&nbsp;</font></td>
	</tr>
</table>';

 $pdf->htmltable($table_html);
 $y_coord =  $pdf->GetY() -1;
 $pdf->Line(40,$y_coord,70,$y_coord); 
$pdf->Line(107,$y_coord,138,$y_coord); 
$pdf->Line(172,$y_coord,203,$y_coord); 
$pdf->SetFontSize(4);
$pdf->Cell(30,0,' ',0,0);
$pdf->Cell(30,0,'(дата и номер договора или другого документа)',0,0);
$pdf->Ln();

 $pdf->SetFontSize(7);
 $pdf->Cell(25,6,'Переадресовка',0,0);
 $pdf->Cell(50,6," ",0,0);
  $pdf->Ln();
 //$pdf->Line(35,53,203,53); 
 $pdf->SetFontSize(4);
  $pdf->Cell(90,0,' ',0,0);
  $y_coord =  $pdf->GetY() -1;
 $pdf->Line(40,$y_coord,203,$y_coord); 
 $pdf->Cell(52,0,'(наименование, адрес нового грузополучателя, фамилия, инициалы уполномоченного лица)',0,0);
 $pdf->Ln();

 
$pdf->Ln(2);


$table_html ='
<table  align=left CELLSPACING=0 cellpadding=0 width=100%>
	<tr>
		<td colspan="11" border=1 bordercolor="#fff" align=center>I. ТОВАРНЫЙ РАЗДЕЛ</td>
	</tr>
	<tr>
		<td border="1" bordercolor="#000" align=center width=40>Наименование товара </td>
		<td border="1" bordercolor="#000" align=center width=10>Еди-<br>ница изме-<br>рения </td>
		<td border="1" bordercolor="#000" align=center width=10>Коли-<br>чество</td> 
		<td border="1" bordercolor="#000" align=center width=17>Цена,<br> руб. коп.</td>
		<td border="1" bordercolor="#000" align=center width=20>Стоимость, руб. коп.</td>
		<td border="1" bordercolor="#000" align=center>Ставка НДС, %</td>
		<td border="1" bordercolor="#000" align=center width=17>Сумма НДС, <br>руб. коп.</td>
		<td border="1" bordercolor="#000" align=center>Стоимость с НДС, руб. коп.</td>
		<td border="1" bordercolor="#000" align=center>Коли-<br>чество <br>грузовых <br> мест</td>
		<td border="1" bordercolor="#000" align=center>Масса груза, кг</td>
		<td border="1" bordercolor="#000" align=center width=5>Примечание</td>
	</tr>
	<tr>
		<td border="1" bordercolor="#000" align="center">1</td>
		<td border="1" bordercolor="#000" align="center">2</td>
		<td border="1" bordercolor="#000" align="center">3</td>
		<td border="1" bordercolor="#000" align="center">4</td>
		<td border="1" bordercolor="#000" align="center">5</td>
		<td border="1" bordercolor="#000" align="center">6</td>
		<td border="1" bordercolor="#000" align="center">7</td>
		<td border="1" bordercolor="#000" align="center">8</td>
		<td border="1" bordercolor="#000" align="center">9</td>
		<td border="1" bordercolor="#000" align="center">10</td>
		<td border="1" bordercolor="#000" align="center">11</td>
	</tr>';
	
	$strr =  explode("|", $str);
$flags = 0;
$query = "select p_names, price, id, units from order_product where order_id = ".$eid;
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) { 

FOR ($z = 0; $z < count($strr); $z++){
	$str2 = explode("^", $strr[$z]);

	IF ($str2[0] == $row[2] ){	
	
	
	
$p_names = '';
$total = 0;

$summ = 0;
$summ_no_nds = 0;  
$sum_nds = 0; 
$price = 0;

$p_names = $row[0];
$price = $row[1]/ $nds_; 
$price = round($price ,2);

IF($no_nds == '1' OR $no_nds == 1){
	$price = $row[1]/ $nds_; 
	$price = round($price * $nds_ ,2);
}


$total = str_replace(',', '.', $str2[1]);
$summ = $total * round($price,2) * $nds_ ;
IF($no_nds == '1' OR $no_nds == 1){
	$summ = $total * round($price,2) ;
	$nds = 0;
}

$summ = round($summ ,2);
$units =  $row[3];


$summ_no_nds = round($price *$total,2); 
$sum_nds = $summ  - $summ_no_nds;


// $summ_no_nds =  $summ / $nds_;
//$price = $summ_no_nds/$total;

$summ_no_nds = round($summ_no_nds ,2); 
$sum_nds = round($sum_nds ,2);

$summ = round($summ ,2);
$summ_no_nds = round($summ_no_nds ,2); 
$sum_nds = round($sum_nds ,2);

$summ_no_nds_all = $summ_no_nds_all  + $summ_no_nds ;
$sum_nds_all = $sum_nds_all + $sum_nds;
$summ_all = $summ_all + $summ;
 $qnt_all = $qnt_all + $total;
$qnt1_all = $qnt1_all + (double)str_replace(',', '.', $str2[2]);
$qnt2_all = $qnt2_all + (double)str_replace(',', '.', $str2[3]);

$table_html .= '	<tr>
		<td border="1" bordercolor="#000" align="left">'.iconv("UTF-8", "cp1251",$p_names).'</td>
		<td border="1" bordercolor="#000" align="center">'.iconv("UTF-8", "cp1251",$units).'</td>
		<td border="1" bordercolor="#000" align="center">'.number_format($total , 2, ',', ' ').'</td>
		<td border="1" bordercolor="#000" align="center"'.number_format($price , 2, ',', ' ').'</td>
		<td border="1" bordercolor="#000" align="center">'.number_format($summ_no_nds , 2, ',', ' ').'</td>
		<td border="1" bordercolor="#000" align="center">Без НДС</td>
		<td border="1" bordercolor="#000" align="center">-</td>
		<td border="1" bordercolor="#000" align="center">'.number_format($summ , 2, ',', ' ').'</td>
		<td border="1" bordercolor="#000" align="center">'.$str2[2].'</td>
		<td border="1" bordercolor="#000" align="center">'.$str2[3].'</td>
		<td border="1" bordercolor="#000" align="center"></td>
	</tr>';

}
}

//<TD VALIGN=TOP COLSPAN=4 border=1111><FONT SIZE=5>Цена изготовителя: '.$price.'; опт.надбавка: 0%; предприятие-изготовитель-ОДО "АртЛайнСити";</FONT>
}

	
	$table_html .= '<tr>
			<td border=1  align=left valign=middle bordercolor=#000>ИТОГО</td>
			<td border=1  align=center valign=middle bordercolor=#000>x</td>
			<td border=1  align=center valign=middle bordercolor=#000>'.number_format($qnt_all , 2, ',', ' ').'</td>
			<td border=1  align=center valign=middle bordercolor=#000>x</td>
			<td border=1 align=center  valign=middle bordercolor=#000>'.number_format($summ_no_nds_all , 2, ',', ' ').'</td>
			<td border=1 align=center  valign=middle bordercolor=#000>x</td>
			<td border=1  align=center valign=middle bordercolor=#000>-</td>
			<td border=1  align=center valign=middle bordercolor=#000>'.number_format($summ_all , 2, ',', ' ').'</td>
			<td border=1  align=center valign=middle bordercolor=#000>'.$qnt1_all.'</td>
			<td border=1  align=center valign=middle bordercolor=#000>'.$qnt2_all.'</td>
			<td border=1  align=center valign=middle bordercolor=#000>&nbsp;</td>
    </tr>
</table>';

$pdf->htmltable($table_html);
switch ($cur_val) {
    case "978":            /*EUR*/
			$strsum = num2streur($sum_nds_all);
			$strsum1 = num2streur($summ_all);
			break;
    case "810":            /*RUB*/
			$strsum = num2strrub($sum_nds_all);
			$strsum1 = num2strrub($summ_all);
			break;
    case "840":            /*USD*/
			$strsum = num2strusd($sum_nds_all);
			$strsum1 = num2strusd($summ_all);
			break;
	 case "974":            /*BYN*/
			$strsum = num2strbyn($sum_nds_all);
			$strsum1 = num2strbyn($summ_all);
			break;
}

$strsum = mb_ucasefirst($strsum);
$strsum1 = mb_ucasefirst($strsum1);
$strsum = substr($strsum, 1);
$strsum1 = substr($strsum1, 1);

/*всего прописью*/
$pdf->SetFontSize(7);
 $pdf->Cell(25,6,'Всего сумма НДС',0,0);
 $pdf->Cell(50,6,$strsum,0,0);
  $pdf->Ln();
 $pdf->SetFontSize(4);
  $pdf->Cell(90,0,' ',0,0);
  $y_coord =  $pdf->GetY() -1;
 $pdf->Line(35,$y_coord,203,$y_coord); 
 $pdf->Cell(52,0,'(сумма прописью)',0,0);
 $pdf->Ln();
$pdf->SetFontSize(7);
 $pdf->Cell(30,6,'Всего стоимость с НДС',0,0);
 $pdf->Cell(50,6,'Без НДС',0,0);
  $pdf->Ln();
 $pdf->SetFontSize(4);
  $pdf->Cell(90,0,' ',0,0);
  $y_coord =  $pdf->GetY() -1;
 $pdf->Line(40,$y_coord,203,$y_coord); 
 $pdf->Cell(52,0,'(сумма прописью)',0,0);
 $pdf->Ln();

/*информация о грузоотправителе грузополучателе*/
 $pdf->SetFontSize(7);
 $pdf->Cell(30,6,'Всего масса груза',0,0);
 IF ($qnt2_all > 0){
  if ($qnt2_all < 1) {
    $qnt2_all = (DOUBLE)$qnt2_all * 1000;
	 $pdf->Cell(60,6,num2strbyn1($qnt2_all)." гр.",0,0);
	} else {
	 $pdf->Cell(60,6,num2strbyn1($qnt2_all)." кг.",0,0);
	}
	 } else {
	  $pdf->Cell(60,6,"",0,0);
	 }


 $pdf->Cell(7,6," ",0,0);
 $pdf->Cell(42,6,'Всего количество грузовых мест',0,0);
 IF ($qnt1_all > 0){
	  $pdf->Cell(48,6,num2strbyn1($qnt1_all),0,0);
	 } ELSE {
	  $pdf->Cell(48,6,"",0,0);
	 }

  $pdf->Ln();

  $y_coord =  $pdf->GetY() -1;
  $begin_vline_y = $y_coord; //вертикальная линия коорд y
  $vline_x=107;
 $pdf->Line(37,$y_coord,$vline_x,$y_coord); 
  $pdf->Line(150,$y_coord,203,$y_coord); 
   $pdf->SetFontSize(4);
 $pdf->Cell(50,0,' ',0,0);
 $pdf->Cell(50,0,'(прописью)',0,0);
 $pdf->Cell(60,0,' ',0,0);
 $pdf->Cell(20,0,'(прописью)',0,0);
 $pdf->Ln();


$table_html ='<TABLE
	<table cellspacing=0 cellpadding=0 width=100%>
		<tr>
			<td colspan=3 valign=middle >Отпуск разрешил</td>
			<td colspan=9 valign=middle >'.iconv("UTF-8", "cp1251", $user_post).' '.iconv("UTF-8", "cp1251", $user_fio).'</td>
			<td colspan=4 valign=middle >Товар к перевозке принял</td>
			<td colspan=8 valign=middle  >'.iconv("UTF-8", "cp1251",$face_to).'</td>
		</tr>
		<tr>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
	
    </tr>
	</table>';
$pdf->htmltable($table_html);
 $y_coord =  $pdf->GetY() -1;
 $pdf->Line(40,$y_coord,$vline_x,$y_coord); 
 $pdf->Line(140,$y_coord,203,$y_coord); 
 $pdf->SetFontSize(4);
 $pdf->Cell(50,0,' ',0,0);
 $pdf->Cell(50,0,'(должность, фамилия, инициалы, подпись)',0,0);
 $pdf->Cell(50,0,' ',0,0);
 $pdf->Cell(50,0,'(должность, фамилия, инициалы, подпись)',0,0);
 $pdf->Ln();

$str_dov = "";
$str_dane = "";
 IF($flafff == "1" OR $flafff == 1){
	  $str_dov = iconv("UTF-8", "cp1251", $num_dover).' от '. date("d.m.Y", strtotime($date_dover));
	  	 $str_dane = iconv("UTF-8", "cp1251", $CLIENT_NAME);
}


$table_html ='<TABLE
	<table cellspacing=0 cellpadding=0 width=100%>
		<tr>
			<td colspan=3 valign=middle >Сдал грузоотправитель</td>
			<td colspan=9 valign=middle >'.iconv("UTF-8", "cp1251", $user_post).' '.iconv("UTF-8", "cp1251", $user_fio).'</td>
			<td colspan=3 valign=middle >По доверенности №</td>
			<td colspan=3 valign=middle >'.$str_dov.'</td>
			<td colspan=6 valign=middle >'.$str_dane.'</td>
		</tr>
		<tr>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
		<td border="0" height="1" width="4"><font size="2">&nbsp;</font></td>
	
    </tr>
	</table>';
$pdf->htmltable($table_html);

$y_coord =  $pdf->GetY() -1;
 $pdf->Line(40,$y_coord,$vline_x,$y_coord); 
 $pdf->Line(135,$y_coord,155,$y_coord); 
 $pdf->Line(160,$y_coord,203,$y_coord); 
 $pdf->SetFontSize(4);
 $pdf->Cell(50,0,' ',0,0);
 $pdf->Cell(50,0,'(должность, фамилия, инициалы, подпись)',0,0);
 $pdf->Cell(30,0,' ',0,0);
 $pdf->Cell(10,0,'(№, дата)',0,0);
  $pdf->Cell(20,0,' ',0,0);
 $pdf->Cell(30,0,'(наименование организации)',0,0);
 $pdf->Ln();

$pdf->SetFontSize(7);
 $pdf->Cell(15,6,'№ пломбы',0,0);

  $pdf->Cell(43,6," ",0,0);

  $pdf->Ln();
  $y_coord =  $pdf->GetY() -1;
 $pdf->Line(25,$y_coord,40,$y_coord); 
 $pdf->Line(125,$y_coord,140,$y_coord); 

  $pdf->SetFontSize(7);
 $pdf->Cell(30,5,'Штамп (печать) грузоотправителя ',0,0);
 $pdf->Cell(68,5," ",0,0);
 $pdf->Cell(40,5,'Штамп (печать) грузополучателя ',0,0);
 $pdf->Cell(60,5," ",0,0);
  $pdf->Ln();
  $y_coord =  $pdf->GetY() -1;
 $pdf->Line(10,$y_coord,203,$y_coord); 
// вертикальная линия
$pdf->Line($vline_x, $begin_vline_y,$vline_x,$y_coord); 

$pdf->Cell(180,4,'II. ПОГРУЗОЧНО-РАЗГРУЗОЧНЫЕ ОПЕРАЦИИ',0,0, 'C');
$pdf->Ln();

// Настройка таблицы разгрузки
$tbl_row_h = 3;
$tbl_row_w = 5;
// заголовок строка 1
$pdf->Cell($tbl_row_w*2.5,$tbl_row_h,'Операции','LT',0, 'C');
$pdf->Cell($tbl_row_w*4.5,$tbl_row_h,'Исполнитель', 'LT' ,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h,'Способ (ручной,','LT',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h,'Код','LT',0, 'C');
$pdf->Cell($tbl_row_w*9,$tbl_row_h,'Дата, время (ч., мин)','LTB',0, 'C');
$pdf->Cell($tbl_row_w*7,$tbl_row_h,'Дополнительные операции','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'Подпись','LTR',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h,' ',0,0);
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'Транспортные услуги ',0,0);
$pdf->Ln();

// заголовок строка 2
$pdf->Cell($tbl_row_w*2.5,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*4.5,$tbl_row_h,' ', 'LB' ,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h, 'механизированный)','LB',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, 'Прибытия','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, 'Убытия','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, 'Простоя','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h, 'Время','LB',0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h, 'Наименование','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LBR',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h,' ',0,0);
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ',"B",0);

$pdf->Ln();
//строка номеров
$pdf->Cell($tbl_row_w*2.5,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*4.5,$tbl_row_h,'12', 'LB' ,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h, '13','LB',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h, '14','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, '15','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, '16','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, '17','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h, '18','LB',0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h, '19','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LBR',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h,' ',0,0);
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ',"B",0);
$pdf->Ln();
// строки данных
$pdf->Cell($tbl_row_w*2.5,$tbl_row_h,'Погрузка','L',0, 'C');
$pdf->SetFont('ArialMT','', 6);
$pdf->Cell($tbl_row_w*4.5,$tbl_row_h,'Частное предприятие', 'L' ,0, 'L');
$pdf->SetFont('ArialMT','', 7);
$pdf->Cell($tbl_row_w*5,$tbl_row_h, ' ','L',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h, ' ','L',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, $DATE_ORa,'L',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, $DATE_ORa,'L',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','L',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h, ' ','L',0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h, ' ','L',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LR',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h,' ',0,0);
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ',"",0);
$pdf->Ln();

// строки данных2
$pdf->Cell($tbl_row_w*2.5,$tbl_row_h,' ','LB',0, 'C');
$pdf->SetFont('ArialMT','', 6);
$pdf->Cell($tbl_row_w*4.5,$tbl_row_h,'"Мечта клиента"', 'LB' ,0, 'L');
$pdf->SetFont('ArialMT','', 7);
$pdf->Cell($tbl_row_w*5,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LBR',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h,' ',0,0);
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ',"B",0);
$pdf->Ln();

$pdf->Cell($tbl_row_w*2.5,$tbl_row_h,'Разгрузка','LB',0, 'C');
 $pdf->SetFont('ArialMT','', 6);
$pdf->Cell($tbl_row_w*4.5,$tbl_row_h,'Грузополучатель', 'LB' ,0, 'L');
 $pdf->SetFont('ArialMT','', 7);
$pdf->Cell($tbl_row_w*5,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h, ' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h, ' ','LBR',0, 'C');
$pdf->Cell($tbl_row_w,$tbl_row_h,' ',0,0);
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ',"B",0);

$pdf->Ln();

$pdf->Cell(180,4,'III. ПРОЧИЕ СВЕДЕНИЯ (заполняются перевозчиком)',0,0, 'C');
$pdf->Ln();

/*Таблица прочих сведений*/
// заголовок строка 1

$pdf->Cell($tbl_row_w*10,$tbl_row_h,'Расстояние перевоз. по группам дорог, км ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*4,$tbl_row_h,'Код экспедир.','LT',0, 'C');
$pdf->Cell($tbl_row_w*4,$tbl_row_h,'За транспортные','LT',0, 'C');
$pdf->Cell($tbl_row_w*6,$tbl_row_h,'Попр. коэф','LT',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'Штраф','LT',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LTR',0, 'C');

$pdf->Cell($tbl_row_w ,$tbl_row_h,' ',0,0, 'C');
$pdf->Cell($tbl_row_w ,$tbl_row_h,'Отметки о составленных актах',0,0, 'L');

$pdf->Ln();

// заголовок строка 2
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'всего','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'в городе','LB',0, 'C');
$pdf->Cell($tbl_row_w*1,$tbl_row_h,'I','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'II','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'III','LB',0, 'C');
$pdf->Cell($tbl_row_w*4,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*4,$tbl_row_h,'услуги','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'расц. водит','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'осн. тариф','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LBR',0, 'C');

$pdf->Cell($tbl_row_w ,$tbl_row_h,' ',0,0, 'C');
$pdf->Cell($tbl_row_w*8 ,$tbl_row_h,' ','B',0, 'L');


$pdf->Ln();

$pdf->Cell($tbl_row_w*2,$tbl_row_h,'21','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'22','LB',0, 'C');
$pdf->Cell($tbl_row_w*1,$tbl_row_h,'23','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'24','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'25','LB',0, 'C');
$pdf->Cell($tbl_row_w*4,$tbl_row_h,'26','LB',0, 'C');
$pdf->Cell($tbl_row_w*4,$tbl_row_h,'27','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'28','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'29','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'30','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'31','LBR',0, 'C');

$pdf->Cell($tbl_row_w ,$tbl_row_h,' ',0,0, 'C');
$pdf->Cell($tbl_row_w*8 ,$tbl_row_h,' ','B',0, 'L');

$pdf->Ln();

$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*1,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*4,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*4,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LBR',0, 'C');

$pdf->Cell($tbl_row_w ,$tbl_row_h,' ',0,0, 'C');
$pdf->Cell($tbl_row_w*8 ,$tbl_row_h,' ','B',0, 'L');

$pdf->Ln(5);
// третья таблица
// заголовок
// Строка 1

$pdf->Cell($tbl_row_w*3,$tbl_row_h,'Расчет ','LT',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'За тонны','LT',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'За расст.','LT',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'За спец.','LT',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'За трансп.','LT',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'Погр.-разгр.','LT',0, 'C');
$pdf->Cell($tbl_row_w*6,$tbl_row_h,'Сверх-норм. простой','LT',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'Проч.','LT',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'Доп.','LT',0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h,'К оплате','LTR',0, 'C');
$pdf->Ln();
// Строка 2
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' стоимости','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'перевозки','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'трансп','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'услуги','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'работы т.','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'погрузка','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'разгрузка','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'допл.','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'усл.','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'итого','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'в т.ч. ТЭП','LTRB',0, 'C');

$pdf->Cell($tbl_row_w,$tbl_row_h,' ', 0,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h,'Таксировка ', 'B',0, 'C');
$pdf->Ln();

// строка номера
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'32','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'33','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'34','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'35','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'36 ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'37','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'38','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'39','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'40','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,'41','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'42','LTRB',0, 'C');

$pdf->Cell($tbl_row_w,$tbl_row_h,' ', 0,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ', 'B',0, 'C');
$pdf->Ln();
// строка данных
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'По заказу','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTRB',0, 'C');

$pdf->Cell($tbl_row_w,$tbl_row_h,' ', 0,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ', 'B',0, 'C');
$pdf->Ln();
// строка данных
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'Выполнено','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTRB',0, 'C');

$pdf->Cell($tbl_row_w,$tbl_row_h,' ', 0,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ', 'B',0, 'C');
$pdf->Ln();

// строка данных
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'Расценка','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTRB',0, 'C');

$pdf->Cell($tbl_row_w,$tbl_row_h,' ', 0,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ', 'B',0, 'C');
$pdf->Ln();

// строка данных
$pdf->Cell($tbl_row_w*3,$tbl_row_h,'К оплате','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LB',0, 'C');
$pdf->Cell($tbl_row_w*2,$tbl_row_h,' ','LTB',0, 'C');
$pdf->Cell($tbl_row_w*3,$tbl_row_h,' ','LTRB',0, 'C');

$pdf->Cell($tbl_row_w,$tbl_row_h,' ', 0,0, 'C');
$pdf->Cell($tbl_row_w*5,$tbl_row_h,' ', 'B',0, 'C');
$pdf->Ln(4);



$pdf->Cell(30, 4, 'Подпись таксировщика', 0,0);
$pdf->Cell(30, 4, ' ', 'B',0);
$pdf->Ln();
$pdf->Cell(43, 4, 'C товаром переданы документы', 0,0);
$pdf->Cell(80, 4, 'Товарно-транспортная накладная '.iconv("UTF-8", "cp1251",$seriass)." ". iconv("UTF-8", "cp1251", $number), 'B',0);

$pdf->Ln();


/*вывод документа*/
$pdf->Output();
?>

<?php

function AddRowWidthMultiCell($height, $width, $text, $pdf){
	$row_length = 200;


// Разобъем текст на массив
} 
 ?>

