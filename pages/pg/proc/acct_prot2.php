<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.

require_once 'functions.php';

define('FPDF_FONTPATH','../../../PHPpdf/font/');
/*require_once '../../../PHPpdf/fpdf.php';*/

require_once '../../../PHPpdf/lib/pdftable.inc.php';

include "../../db.php";

$query = "select val from settings s where s.id  = 4";

$result = mysql_query($query) or die("Query failed1");

IF ($row = mysql_fetch_row($result)) { 
	$nds = $row[0];

	}
	
IF ($nds ==''){
	$nds = 0;
	} ELSE {
$nds = (double)str_replace(',', '.', $nds);
}

$nds_ = 1 + $nds/100;

$ID = $_GET['id'];

$query = "select user_id, client_id , DATE_FORMAT(DATE_OR, '%d %M %Y'),CUR_ID from orders where number = ".$ID;
$result = mysql_query($query) or die($query);
if($row = mysql_fetch_row($result)){
	$users = $row[0];
	$clients = $row[1];
	$DATE_OR = $row[2];
	$cur_val = $row[3]; 
}


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



$query = "select user_fio, user_mail, user_post from users where user_login = '".$users."';";
$result = mysql_query($query) or die("Query failed");
if($row = mysql_fetch_row($result)){
$user_fio = $row[0];
$user_mail = $row[1];
$user_post = $row[2];
}

$query = "select val from settings where id = 5";
$result = mysql_query($query) or die($query);
if($row = mysql_fetch_row($result)){
	$fio_dir = $row[0];
}

$query = "select val from settings where id = 6";
$result = mysql_query($query) or die($query);
if($row = mysql_fetch_row($result)){
	$r_s = $row[0];
}

$query = "select val from settings where id = 13";
$result = mysql_query($query) or die($query);
if($row = mysql_fetch_row($result)){
	$bank_cod = $row[0];
}

$query = "select c.CLIENT_NAME, c.ADDRESS_POST,c.PHONE_CITY,c.PHONE_MOB,c.EMAIL,c.ACCT,c.BANK,c.CODE_BANK,c.fio_dir1, c.num_doc from clients c  where c.ID = ".$clients;
$result = mysql_query($query) or die($query);
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
}



$pdf = new PDFTable();


	$pdf->AddFont('ArialMT','','arial.php');
	$pdf->AddPage();
    // Logo
    $pdf->Image('logo.jpg',140,5,45);

    // Move to the right.
	 $pdf->SetFont('ArialMT','',9);
    $pdf->Cell(0,0,'Изготовитель: ОДО "АртЛайнСити"',0,0);
	   $pdf->Ln(); 
	$pdf->Cell(0,7,'224030, г. Брест, ул. Карбышева, 74-9 ',0,0);
	   $pdf->Ln(); 
	$pdf->Cell(0,0,'8 (0162) 536828, 8 (029) 6094477, 8 (033) 6094477',0,0);
	   $pdf->Ln(); 
	$pdf->Cell(0,7,'e-mail:'.iconv("UTF-8", "cp1251", $user_mail));
	   $pdf->Ln(); 
	$pdf->Cell(0,0,'УНП: 290479470');
	   $pdf->Ln(); 
	$pdf->Cell(0,7,'Р/сч:'.iconv("UTF-8", "cp1251", $r_s));
	   $pdf->Ln(); 
	$pdf->Cell(0,0,'Банк: ОАО "Белгазпромбанк", Брестская областная дирекция');
	   $pdf->Ln(); 
	$pdf->Cell(0,7,'Код банка:'.iconv("UTF-8", "cp1251", $bank_cod));
	   $pdf->Ln(); 
	$pdf->Cell(0,0,'Адрес банка: г. Брест, ул. Сов. Конституции, 15');
	
   
    // Line break
	    $pdf->SetFont('ArialMT','',14);
    $pdf->Ln(10);
	$pdf->Cell(0,0,'СЧЕТ-ПРОТОКОЛ СОГЛАСОВАНИЯ ЦЕН № '.$ID,0,0,'C');
	 $pdf->Ln(); 
	 
	$pdf->Cell(0,10,'от '.$DATE_OR.' г. ',0,0,'C');
	   $pdf->Ln(10);
	   
	$pdf->SetFont('ArialMT','',8);
    $pdf->Cell(0,0,'Заказчик: '.iconv("UTF-8", "cp1251", $CLIENT_NAME) );
	   $pdf->Ln(); 
	$pdf->Cell(0,7,'Юр. адрес: '.iconv("UTF-8", "cp1251", $ADDRESS_POST));
	   $pdf->Ln(); 
	$pdf->Cell(0,0,'Тел.: '.iconv("UTF-8", "cp1251", $PHONE));
	   $pdf->Ln(); 
	$pdf->Cell(0,7,'e-mail: '.iconv("UTF-8", "cp1251", $EMAIL));
	   $pdf->Ln(); 
	$pdf->Cell(0,0,'Р/сч: '.iconv("UTF-8", "cp1251", $ACCT));
	   $pdf->Ln(); 
	$pdf->Cell(0,7,'Банк: '.iconv("UTF-8", "cp1251", $BANK) );
	   $pdf->Ln(); 
	$pdf->Cell(0,0,'Код банка: '.iconv("UTF-8", "cp1251", $CODE_BANK) );
	 $pdf->Ln(3); 
	$pdf->Cell(0,0,'Номер договора: '.iconv("UTF-8", "cp1251", $num_doc));
	$pdf->Ln(5);



$pdf->SetFont('ArialMT','',8);
$pdf->Cell(40,5,'Счет-протокол согласования цен действителен в течение 3 (трех) банковских дней');
$pdf->Ln();
$price_all = 0 ;
$sum_nds_all = 0 ;
$summ_all = 0 ;

/*$html = '
<table  border=1 align=center>
  <tr>
    <td align=center width=5>№</td>
    <td align=center width=50>Наименование товара</td>
    <td align=center width=14>Ед. изм.</td>
    <td align=center width=15>Кол-во</td>
    <td align=center width=5>Цена руб. коп.</td>
    <td align=center width=5>Опт. надб.</td>
    <td align=center width=18>Сумма руб. коп.</td>
    <td align=center width=5>Ставка НДС, %</td>
    <td align=center width=18>Сумма НДС руб. коп.</td>
    <td align=center width=18> Всего с НДС руб. коп. </td>
  </tr>

';
*/

$html = '
<table  border=1 align=center>
  <tr>
    <td align=center width=5>№</td>
    <td align=center width=45>Наименование товара</td>
    <td align=center width=14>Ед. изм.</td>
    <td align=center width=12>Кол-во</td>
    <td align=center width=15>Цена руб. коп.</td>
    <td align=center width=5>Опт. надб.</td>
    <td align=center width=18>Сумма руб. коп.</td>
    <td align=center width=5>Ставка НДС, %</td>
    <td align=center width=18>Сумма НДС руб. коп.</td>
    <td align=center width=18> Всего с НДС руб. коп. </td>
  </tr>

';



$flags = 0;
$query = "select p_names, total, summ,DIZ,sum_press,units,price from order_product where order_id = ".$ID;
$result = mysql_query($query) or die($query);
$var = 175;
while ($row = mysql_fetch_row($result)) { 
	++$flags;
$p_names = '';
$total = 0;
$p_names_unit = '';
$summ = 0;
$summ_no_nds = 0;  
$sum_nds = 0;
$price = 0;
$p_names_unit = $row[5]; 
$p_names = $row[0];
$total = $row[1];
$summ = $row[2]+$row[3]+$row[4];
if ($total != 0){
$price = $row[6] / $nds_;
$price = round($price ,2);
$summ = $total * round($price,2) * $nds_;
$summ = round($summ ,2);
$summ_no_nds = round($price * $total,2); 
$sum_nds = $summ  - $summ_no_nds;
$summ_no_nds = round($summ_no_nds ,2); 
$sum_nds = round($sum_nds ,2);
$summ = round($summ ,2);
$summ_no_nds = round($summ_no_nds ,2); 
$sum_nds = round($sum_nds ,2);
$summ_no_nds_all = $summ_no_nds_all  + $summ_no_nds ;
$sum_nds_all = $sum_nds_all + $sum_nds;
$summ_all = $summ_all + $summ;
}


$html .= '<tr>'.
	'<td align=right border=1111 nowrap  >'.$flags.'</td>'
	.'<td align=left border=1111  >'.iconv("UTF-8", "cp1251",$p_names).'</td>'
	.'<td align=left border=1111  >'.iconv("UTF-8", "cp1251",$p_names_unit).'</td>'
	.'<td align=right border=1111  >'.str_replace('.', ',', $total).'</td>'
	.'<td align=right border=1111  >'.number_format($price , 2, ',', ' ').'</td>'
	.'<td align=right border=1111  >'.'0%'.'</td>'
	.'<td align=right border=1111  >'.number_format($summ_no_nds , 2, ',', ' ').'</td>'
	.'<td align=right border=1111  >'.$nds.'</td>'
	.'<td align=right border=1111  >'.number_format($sum_nds , 2, ',', ' ').'</td>'
	.'<td align=right border=1111 >'.number_format($summ , 2, ',', ' ').'</td>'
	.'</tr>'
	;
$var = $var + 5;
}
$var1 = round($var,0); 




$html .= '<tr>'.
	'<td align=right border=0000  ></td>'
	.'<td align=left border=0000  ></td>'
	.'<td align=left border=0000  ></td>'
	.'<td align=right border=0000  ></td>'
	.'<td align=right border=1111  >Итого:</td>'
	.'<td align=right border=0000  ></td>'
	.'<td align=right border=1111  >'.number_format($summ_no_nds_all , 2, ',', ' ').'</td>'
	.'<td align=right border=0000  > </td>'
	.'<td align=right border=1111  >'.number_format($sum_nds_all , 2, ',', ' ').'</td>'
	.'<td align=right border=1111 >'.number_format($summ_all , 2, ',', ' ').'</td>'
	.'</tr>'
	;
$html .= '</table>';

$pdf->htmltable($html);
$pdf->Ln();
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
	case "933":            /*BYN*/
		$strsum = num2strbyn($sum_nds_all);
		$strsum1 = num2strbyn($summ_all);
		break;
}

$strsum = mb_ucasefirst($strsum);
$strsum1 = mb_ucasefirst($strsum1);
$strsum = substr($strsum, 1);
$strsum1 = substr($strsum1, 1);

    //$pdf->Image('stamp.jpg',20,$var1,180);
	//$pdf->Image('stamp.jpg',20,$var1 + 10,180);
	
$pdf->Ln(15);
$pdf->SetFont('ArialMT','',9);
$pdf->Cell(0,0,'Сумма НДС: '.$strsum );
$pdf->Ln();
$pdf->Cell(0,10,'Всего к оплате на сумму с НДС: '.$strsum1);

$pdf->Ln();
$image1 = "stamp.jpg";
$pdf->Cell( 20, 0, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY() + 20, 180), 0, 0, 'L', false );


$pdf->SetFont('ArialMT','',8);
$pdf->Ln(10);
$pdf->Cell(0,0,'ВНИМАНИЕ! ');
$pdf->Ln();
$pdf->Cell(0,10,'Продукция отпускается после поступления оплаты на расчетный счет.  ');
$pdf->Ln();
$pdf->Cell(0,0,'Для получения продукции при себе иметь подписанные и заверенные документы: копию платежного поручения, договор, счет, ');
$pdf->Ln();
$pdf->Cell(0,10,'доверенность; руководителю - печать и приказ о назначении; ИП – свидетельство о гос. регистрации, печать (при наличии) ');
$pdf->Ln(10);

     //$pdf->Image('stamp.jpg',25,181,50);
	 //$pdf->Image('stamp.jpg',20,null,180);
	 //$pdf->Image('stamp.jpg',20,$var1,180);

// $pdf->Cell(100,0,'ИСПОЛНИТЕЛЬ:');  
// $pdf->Cell(100,0,'ЗАКАЗЧИК:');
// $pdf->Ln();


// $pdf->Cell(100,10,'Директор__________________/'. iconv("UTF-8", "cp1251", $fio_dir) );
// $pdf->Cell(100,10,'__________________________/'. iconv("UTF-8", "cp1251", $fio_dir1) );
// $pdf->Ln();
// $pdf->Cell(100,0,'                                     м.п');  
// $pdf->Cell(100,0,'                                     м.п');


$pdf->Ln();
//$pdf->Cell(0,10,'Ваш менеджер: '. iconv("UTF-8", "cp1251", $user_fio));  

$pdf->Output();



?>
