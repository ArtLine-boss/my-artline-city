<?php
include '../../firewall.php';
require_once 'functions.php';


define('FPDF_FONTPATH','../../../PHPpdf/font/');
/*require_once '../../../PHPpdf/fpdf.php';*/

require_once '../../../PHPpdf/lib/pdftable.inc.php';

include "../../db.php";

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

$ID = $_GET['id'];
$number = $_GET['number'];
$face_to = $_GET['face_to'];
$to_face = $_GET['to_face'];
$num_dover = $_GET['num_dover'];
$date_dover = $_GET['date_dover'];
$flafff = $_GET['flafff'];
$str = $_GET['srt'];
$date_create = DATE("Y-m-d");
$seria = $_GET['seriass'];
$no_nds = $_GET['no_nds'];

$newDate = date("d.m.Y", strtotime($date_dover));



$face_to_face = $face_to.', '.$to_face;
$dover  = '№ '.$num_dover.', '.$newDate.'г.';

$parent_company = 1;
$query = "select user_id, client_id , DATE_FORMAT(DATE_OR, '%d %M %Y'),DATE_FORMAT(DATE_OR, '%d.%m.%Y'),CUR_ID,parent_company from orders where number = ".$ID;
$result = mysql_query($query) or die("Query failed");
if($row = mysql_fetch_row($result)){
	$users = $row[0];
	$clients = $row[1];
	$DATE_OR = $row[2];
	$DATE_ = $row[3];
	$cur_val = $row[4];
	$parent_company = $row[5];
}


$date_beg = new DateTime($_GET['dates']) ;
$DATE_OR = $date_beg->format('d F Y');


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

session_start();
$users = $_SESSION['login'];

$query = "select user_fio, user_mail, user_post  from users where user_login = '".$users."';";
$result = mysql_query($query) or die("Query failed");
if($row = mysql_fetch_row($result)){
	$user_fio = $row[0];
	$user_mail = $row[1];
	$user_post = $row[2];
}

/*if($users != '026' && $users != '033') {
	$user_fio = "Мякишев Е.В.";
	$user_post ="Директор";
	$user_fio = iconv("cp1251", "UTF-8", $user_fio);
	$user_post = iconv("cp1251", "UTF-8", $user_post);
}*/


$query = "select val from settings where id = 19";
$result = mysql_query($query) or die($query);
if($row = mysql_fetch_row($result)){
	$fio_dir = $row[0];
}

$query = "select val from settings where id = 6";
$result = mysql_query($query) or die($query);
if($row = mysql_fetch_row($result)){
	$r_s = $row[0];
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
$unp =  $row[10];
$num_doc_m = $row[11];
}



$pdf = new PDFTable();


	$pdf->AddFont('ArialMT','','arial.php');
	$pdf->AddPage();
    // Logo
   
    // Line break
	    $pdf->SetFont('ArialMT','',7);
	$pdf->Image('11.png',6,6,50,50);

	//$pdf->Cell(0,10,'Грузоотправитель ОДО "АртЛайнСити", 224030, г. Брест, ул. Карбышева, 74-9',0,0,'L');
$html = '	<TABLE BORDER CELLSPACING=0 >

<TR>
<TD  VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=9 >&nbsp;
<TD  ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=4  border=1111>Грузоотправитель
<TD  ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=4 border=1111>Грузополучатель
<TD VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=8>&nbsp;
</TR>

<TR>
<TD  VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=8 >&nbsp;
<TD  VALIGN=TOP BORDERCOLOR=#ffffff>УНП
<TD  ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=4  border=1111>291546971
<TD  ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=4 border=1111>&nbsp;'.$unp.'
<TD VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=7>&nbsp;
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
</TR>


<TR>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=25><B>'.$DATE_OR.' г.</B>
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
</TR>
<TR>
<TD ALIGN=LEFT VALIGN=TOP><I><B>Грузоотправитель</B></I>
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD ALIGN=LEFT VALIGN=TOP COLSPAN=21 border=0010><B>Частное предприятие "Мечта клиента", 224030, г. Брест, ул. Карбышева, 74</B>
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=21><FONT SIZE=5>(наименование, адрес)</FONT>
</TR>
<TR>
<TD ALIGN=LEFT VALIGN=TOP><I><B>Грузополучатель</B></I>
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP COLSPAN=21 border=0010><B>'.iconv("UTF-8", "cp1251", $CLIENT_NAME).', '.iconv("UTF-8", "cp1251", $ADDRESS_POST).'</B>
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=21><FONT SIZE=5>(наименование, адрес)</FONT>
</TR>
<TR>
<TD ALIGN=LEFT VALIGN=TOP COLSPAN=4><I><B>Основание отпуска</B></I>


<TD VALIGN=TOP COLSPAN=21 border=0010>Договор № '.iconv("UTF-8", "cp1251", (($parent_company == 2) ? $num_doc_m : $num_doc)).', счет-протокол № '.$ID.' от '.$DATE_.'г.
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=21><FONT SIZE=5>(дата и номер договора или другого документа)</FONT>
</TR>

<TR>
<TD ALIGN=CENTER border=1111 VALIGN=CENTER COLSPAN=25>I. ТОВАРНЫЙ РАЗДЕЛ
</TR>
<TR>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=7 border=1111><FONT SIZE=7>Наименование товара</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=7>Единица
измерения</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=7>Кол-во</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=7>Цена, руб. коп.</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=7>Стоимость,
руб. коп.</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=7>Ставка НДС,
%</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=7>Сумма НДС,
руб. коп.</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=7>Стоимость
с НДС, руб. коп.</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=4 border=1111><FONT SIZE=7>Примечание</FONT>
</TR>
<TR>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=7 border=1111><FONT SIZE=5>1</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=5>2</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=5>3</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=5>4</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=5>5</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=5>6</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=5>7</FONT>
<TD ALIGN=CENTER VALIGN=CENTER COLSPAN=2 border=1111><FONT SIZE=5>8</FONT>
<TD ALIGN=CENTER VALIGN=TOP COLSPAN=4 border=1111><FONT SIZE=5>9</FONT>
</TR>';
$strr =  explode("|", $str);
$flags = 0;
$query = "select p_names, price, id, units from order_product where order_id = ".$ID;
$result = mysql_query($query) or die("Query failed");
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


$html .= '<TR>
<TD ALIGN=LEFT VALIGN=TOP COLSPAN=7 border=1111>'.iconv("UTF-8", "cp1251",$p_names).'</TD>
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111>'.iconv("UTF-8", "cp1251",$units).'</TD>
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111>'.str_replace('.', ',', $total).'</TD>
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111>'.number_format($price , 2, ',', ' ').'</TD>
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111>'.number_format($summ_no_nds , 2, ',', ' ').'</TD>
<TD ALIGN=CENTER VALIGN=TOP COLSPAN=2 border=1111>Без НДС</TD>
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111>-</TD>
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111>'.number_format($summ , 2, ',', ' ').'</TD>
<TD VALIGN=TOP COLSPAN=4 border=1111><FONT SIZE=5>&nbsp;</FONT>
</TR>
';
}
}

//<TD VALIGN=TOP COLSPAN=4 border=1111><FONT SIZE=5>Цена изготовителя: '.$price.'; опт.надбавка: 0%; предприятие-изготовитель-ОДО "АртЛайнСити";</FONT>
}


$html .= '<TR>
<TD ALIGN=LEFT VALIGN=TOP COLSPAN=7 border=1111>ИТОГО
<TD ALIGN=CENTER VALIGN=TOP COLSPAN=2 border=1111>х
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111><B>'.number_format($qnt_all , 2, ',', ' ').'</B>
<TD ALIGN=CENTER VALIGN=TOP COLSPAN=2 border=1111>х
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111><B>'.number_format($summ_no_nds_all , 2, ',', ' ').'</B>
<TD ALIGN=CENTER VALIGN=TOP COLSPAN=2 border=1111>х
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111><B>-</B>
<TD ALIGN=RIGHT VALIGN=TOP COLSPAN=2 border=1111><B>'.number_format($summ_all , 2, ',', ' ').'</B>
<TD VALIGN=TOP COLSPAN=4 border=1111><FONT SIZE=2>&nbsp;</FONT>
</TR>';

$html .= '<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
</TR>';
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
	case "974":            /*BYR*/
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


$html .= '<TR>
<TD VALIGN=TOP>Всего сумма НДС 
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP COLSPAN=23 border=0010>Без НДС
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=21><FONT SIZE=5>(прописью)</FONT>
</TR>

<TR>
<TD VALIGN=TOP COLSPAN=5>Всего стоимость с НДС
<TD VALIGN=TOP COLSPAN=20 border=0010>'.$strsum1.'
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=21><FONT SIZE=5>(прописью)</FONT>
</TR>

<TR>
<TD VALIGN=TOP>Отпуск разрешил
<TD VALIGN=TOP>&nbsp;

<TD VALIGN=TOP COLSPAN=23 border=0010>'.iconv("UTF-8", "cp1251", classes_accordUsers::getPOST($users, classes_accordUsers::ACCORD_TYPE_AM)).' '.iconv("UTF-8", "cp1251", classes_accordUsers::getFIO($users, classes_accordUsers::ACCORD_TYPE_AM)).'
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=22><FONT SIZE=5>(должность, фамилия, инициалы, подпись)</FONT>
</TR>
<TR>
<TD VALIGN=TOP COLSPAN=5>Сдал грузоотправитель

<TD VALIGN=TOP COLSPAN=20 border=0010>'.iconv("UTF-8", "cp1251", classes_accordUsers::getPOST($users, classes_accordUsers::ACCORD_TYPE_AM)).' '.iconv("UTF-8", "cp1251", classes_accordUsers::getFIO($users, classes_accordUsers::ACCORD_TYPE_AM)).'
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=21><FONT SIZE=5>(должность, фамилия и инициалы, подпись; штамп (печать) грузоотправителя)</FONT>
</TR>
<TR>
<TD VALIGN=TOP COLSPAN=7>Товар к доставке принял
<TD VALIGN=TOP COLSPAN=18 border=0010>&nbsp;'.iconv("UTF-8", "cp1251", $face_to_face).'


</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=20><FONT SIZE=5>(должность, фамилия, инициалы, подпись)</FONT>
</TR>
<TR>
<TD VALIGN=TOP>по доверенности
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;';
IF ($flafff == 0){
	$html .= '<TD VALIGN=TOP COLSPAN=9 border=0010>&nbsp;
				<TD VALIGN=TOP>&nbsp;
				<TD ALIGN=RIGHT VALIGN=TOP>выданной
				<TD VALIGN=TOP COLSPAN=11 border=0010>&nbsp;</TR>';
}
else $html .= '<TD VALIGN=TOP COLSPAN=9 border=0010>&nbsp;№ '.iconv("UTF-8", "cp1251", $num_dover).' от '.$newDate.' г. 
					<TD VALIGN=TOP>&nbsp;
					<TD ALIGN=RIGHT VALIGN=TOP>выданной
					<TD VALIGN=TOP COLSPAN=11 border=0010>'.iconv("UTF-8", "cp1251", $CLIENT_NAME).'
					</TR>';




$html .= '<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=9><FONT SIZE=5>(номер, дата)</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD VALIGN=TOP BORDERCOLOR=#ffffff><FONT SIZE=5>&nbsp;</FONT>
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=11><FONT SIZE=5>(наименование организации)</FONT>
</TR>
<TR>
<TD VALIGN=TOP  COLSPAN=5>Принял грузополучатель';
IF ($flafff != 0){
$html .= '<TD VALIGN=TOP  COLSPAN=19 border=0010> &nbsp;'.iconv("UTF-8", "cp1251", $face_to_face); }
else {
	$html .= '<TD VALIGN=TOP  COLSPAN=19 border=0010> &nbsp;';
}
$html .= '<TD VALIGN=TOP COLSPAN=19 border=0010>&nbsp;
</TR>
<TR>
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD VALIGN=TOP BORDERCOLOR=#ffffff>&nbsp;
<TD ALIGN=CENTER VALIGN=TOP BORDERCOLOR=#ffffff COLSPAN=20><FONT SIZE=5>(должность, фамилия и инициалы, подпись; штамп (печать) грузополучателя)</FONT>
</TR>

<TR>
<TD ALIGN=LEFT VALIGN=TOP COLSPAN=8>С товаром переданы документы:

<TD ALIGN=LEFT VALIGN=TOP COLSPAN=17 border=0010>товарная накладная № '.iconv("UTF-8", "cp1251", $number).'  
</TR>
<TR>
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
<TD VALIGN=TOP>&nbsp;
</TR>
</TABLE>';

$pdf->htmltable($html);
$pdf->Ln();



$pdf->Output();



?>
