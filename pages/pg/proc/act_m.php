<?php
include '../../firewall.php';
require_once 'functions.php';


define('FPDF_FONTPATH', '../../../PHPpdf/font/');
/*require_once '../../../PHPpdf/fpdf.php';*/

require_once '../../../PHPpdf/lib/pdftable.inc.php';

include "../../db.php";

session_start();
$login = $_SESSION['login'];


if ($nds == '') {
    $nds = 0;
} else {
    $nds = (double)str_replace(',', '.', $nds);
}
$nds = 20;
$nds_ = 1 + $nds / 100;

$ID = $_GET['id'];
$no_nds = $_GET['no_nds'];
$smeta = $_GET['smeta'];


$newDate = date("d.m.Y", strtotime($date_dover));


$face_to_face = $face_to . ', ' . $to_face;
$dover = '№ ' . $num_dover . ', ' . $newDate . 'г.';

$query = "select user_id, client_id , DATE_FORMAT(DATE_OR, '%d %M %Y'),DATE_FORMAT(DATE_OR, '%d.%m.%Y'),CUR_ID  from orders where number = " . $ID;
$result = mysql_query($query) or die("Query failed");
if ($row = mysql_fetch_row($result)) {
    $users = $row[0];
    $clients = $row[1];
    $DATE_OR = $row[2];
    $DATE_ = $row[3];
    $cur_val = $row[4];
}


$DATE_OR = date("d F Y");
// $DATE_OR  = date("d F Y");
$DATE_OR = str_replace('January', 'Января', $DATE_OR);
$DATE_OR = str_replace('February', 'Февраля', $DATE_OR);
$DATE_OR = str_replace('March', 'Марта', $DATE_OR);
$DATE_OR = str_replace('April', 'Апреля', $DATE_OR);
$DATE_OR = str_replace('May', 'Мая', $DATE_OR);
$DATE_OR = str_replace('June', 'Июня', $DATE_OR);
$DATE_OR = str_replace('July', 'Июля', $DATE_OR);
$DATE_OR = str_replace('August', 'Августа', $DATE_OR);
$DATE_OR = str_replace('September', 'Сентября', $DATE_OR);
$DATE_OR = str_replace('October', 'Октября', $DATE_OR);
$DATE_OR = str_replace('November', 'Ноября', $DATE_OR);
$DATE_OR = str_replace('December', 'Декабря', $DATE_OR);


$query = "select user_fio, user_mail, user_post from users where user_login = '" . $users . "';";
$result = mysql_query($query) or die("Query failed");
if ($row = mysql_fetch_row($result)) {
    $user_fio = $row[0];
    $user_mail = $row[1];
    $user_post = $row[2];
}


$query = "select val from settings where id = 5";
$result = mysql_query($query) or die($query);
if ($row = mysql_fetch_row($result)) {
    $fio_dir = $row[0];
}

$query = "select val from settings where id = 6";
$result = mysql_query($query) or die($query);
if ($row = mysql_fetch_row($result)) {
    $r_s = $row[0];
}

$query = "select c.CLIENT_NAME, c.ADDRESS_POST,c.PHONE_CITY,c.PHONE_MOB,c.EMAIL,c.ACCT,c.BANK,c.CODE_BANK,c.fio_dir1, c.num_doc, c.UNP from clients c  where c.ID = " . $clients;
$result = mysql_query($query) or die("Query failed");
if ($row = mysql_fetch_row($result)) {
    $CLIENT_NAME = $row[0];
    $ADDRESS_POST = $row[1];
    $PHONE = $row[2] . " " . $row[3];
    $EMAIL = $row[4];
    $ACCT = $row[5];
    $BANK = $row[6];
    $CODE_BANK = $row[7];
    $fio_dir1 = $row[8];
    $num_doc = $row[9];
    $unp = $row[10];
}

$pdf = new PDFTable();


$pdf->AddFont('ArialMT', '', 'arial.php');
$pdf->AddPage();
// Logo

// Line break
$pdf->SetFont('ArialMT', '', 8);
$pdf->Image('11.png', 6, 6, 50, 50);

$pdf->Cell(0, 7, 'Исполнитель: Общество с ограниченной ответственностью "Мечта клиента"', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(0, 0, 'Р/сч: BY14OLMP30123000990800000933 в ОАО "Белгазпромбанк" г. Минск, ул. Притыцкого, 60/2 ', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(0, 7, 'код OLMPBY2X, УНП: 291546971', 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(0, 0, 'Адрес: 224030, г. Брест, ул. Карбышева, 74, тел.: ,бухгалтерия (0162) 53-45-42', 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('ArialMT', '', 10);
$pdf->Cell(0, 7, 'АКТ № 0000000 от ' . $DATE_OR, 0, 0, 'L');
$pdf->Ln(10);
$pdf->SetFont('ArialMT', '', 8);
$pdf->Cell(0, 7, 'Заказчик: ' . iconv("UTF-8", "cp1251", $CLIENT_NAME), 0, 0, 'L');
$pdf->Ln();
$pdf->Cell(0, 0, 'Счет-протокол № ' . $ID . ' от ' . $DATE_ . 'г.', 0, 0, 'L');
$pdf->Ln();
if (!empty($num_doc)) {
    $pdf->Cell(0, 7, 'Номер договора: ' . iconv("UTF-8", "cp1251", $num_doc), 0, 0, 'L');
    $pdf->Ln();
    $pdf->Cell(0, 0, 'Р/сч:' . iconv("UTF-8", "cp1251", $ACCT) . ', ' . iconv("UTF-8", "cp1251", $BANK), 0, 0, 'L');
    $pdf->Ln();
    $pdf->Cell(0, 7, 'код ' . iconv("UTF-8", "cp1251", $CODE_BANK) . ', УНП: ' . iconv("UTF-8", "cp1251", $unp), 0, 0, 'L');
    $pdf->Ln();
    $pdf->Cell(0, 0, 'Адрес: ' . iconv("UTF-8", "cp1251", $ADDRESS_POST), 0, 0, 'L');
    $pdf->Ln();
    $pdf->Cell(0, 7, '', 0, 0, 'L');
    $pdf->Ln();
} else {
    $pdf->Cell(0, 7, 'Р/сч:' . iconv("UTF-8", "cp1251", $ACCT) . ', ' . iconv("UTF-8", "cp1251", $BANK), 0, 0, 'L');
    $pdf->Ln();
    $pdf->Cell(0, 0, 'код ' . iconv("UTF-8", "cp1251", $CODE_BANK) . ', УНП: ' . iconv("UTF-8", "cp1251", $unp), 0, 0, 'L');
    $pdf->Ln();
    $pdf->Cell(0, 7, 'Адрес: ' . iconv("UTF-8", "cp1251", $ADDRESS_POST), 0, 0, 'L');
    $pdf->Ln();
}


$price_all = 0;
$sum_nds_all = 0;
$summ_all = 0;


$html = '
<table  border=1 align=center>
  <tr>
    <td align=center width=5>№</td>
    <td align=center width=65>Наименование работы(услуги)</td>
    <td align=center width=5>Ед.изм.</td>
    <td align=center width=14>Кол-во</td>
    <td align=center width=14>Цена руб.</td>
    <td align=center width=18>Сумма руб. коп.</td>
    <td align=center width=5>Ставка НДС, %</td>
    <td align=center width=9>Сумма НДС руб. коп.</td>
    <td align=center width=18> Стоимость с НДС руб. коп. </td>
  </tr>

';


$flags = 0;

if ($smeta == "") {
    //$query = "select p_names, total, summ,DIZ,sum_press,units,price from order_product where order_id = ".$ID;
    $query = "SELECT *
				FROM
					(SELECT p_names, total, summ,DIZ,sum_press,units,price,code_stat FROM order_product WHERE order_id=" . $ID . ") t1
				INNER JOIN
					(SELECT ID,code_stat FROM directoryCodeStat) t2
				ON t1.code_stat=t2.ID
				WHERE t2.code_stat='18.14.10' OR t2.code_stat='18.13.30'";
} else {
    $arr_smeta = json_decode($smeta);
    if (count($arr_smeta) <= 0) {
        //$query = "select p_names, total, summ,DIZ,sum_press,units,price from order_product where order_id = ".$ID;
        $query = "SELECT *
				FROM
					(SELECT p_names, total, summ,DIZ,sum_press,units,price,code_stat FROM order_product WHERE order_id=" . $ID . ") t1
				INNER JOIN
					(SELECT ID,code_stat FROM directoryCodeStat) t2
				ON t1.code_stat=t2.ID
				WHERE t2.code_stat='18.14.10' OR t2.code_stat='18.13.30'";
    } else {
        $str_smeta = $arr_smeta[0];
        for ($i = 1; $i < count($arr_smeta); $i++) {
            $str_smeta .= "," . $arr_smeta[$i];
        }
        //$query = "select p_names, total, summ,DIZ,sum_press,units,price from order_product where id in (".$str_smeta.")";
        $query = "SELECT *
				FROM
					(SELECT p_names, total, summ,DIZ,sum_press,units,price,code_stat FROM order_product WHERE order_id IN (" . $str_smeta . ")) t1
				INNER JOIN
					(SELECT ID,code_stat FROM directoryCodeStat) t2
				ON t1.code_stat=t2.ID
				WHERE t2.code_stat='18.14.10' OR t2.code_stat='18.13.30'";
    }
}
$result = mysql_query($query) or die($query);
$var = 187;
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
    $summ = $row[2] + $row[3] + $row[4];
    if ($total != 0) {

        $price = round($row[6], 2) / $nds_;
        $price = round($price, 2);

        if ($no_nds == 1 or $no_nds == '1') {
            $price = round($row[6], 2) / $nds_;
            $price = round($price * $nds_, 2);

        }

        $summ = $total * round($price, 2) * $nds_;

        if ($no_nds == 1 or $no_nds == '1') {
            $summ = $total * round($price, 2);
            $nds = 0;
        }


        $summ = round($summ, 2);
        $summ_no_nds = round($price * $total, 2);
        $sum_nds = $summ - $summ_no_nds;
        $summ_no_nds = round($summ_no_nds, 2);
        $sum_nds = round($sum_nds, 2);
        $summ = round($summ, 2);
        $summ_no_nds = round($summ_no_nds, 2);
        $sum_nds = round($sum_nds, 2);
        $summ_no_nds_all = $summ_no_nds_all + $summ_no_nds;
        $sum_nds_all = $sum_nds_all + $sum_nds;
        $summ_all = $summ_all + $summ;

    }
    $dlina = strlen(iconv("UTF-8", "cp1251", $p_names));
    if ($dlina >= 36) {
        $nachalo = 0;
        $p_names1 = '';
        do {
            $nachalo2 = $nachalo + 36;
            $p_names1 = $p_names1 . substr(iconv("UTF-8", "cp1251", $p_names), $nachalo, $nachalo2) . "<br>";
            $nachalo = $nachalo + 37;
            $dlina = $dlina - 36;
        } while ($dlina > 0);
    } else {
        $p_names1 = $p_names;
    }

    $html .= '<tr>' .
        '<td align=right border=1111   >' . $flags . '</td>'
        . '<td align=left border=1111  >' . iconv("UTF-8", "cp1251", $p_names) . '</td>'
        . '<td align=left border=1111  >' . iconv("UTF-8", "cp1251", $p_names_unit) . '</td>'
        . '<td align=right border=1111  >' . number_format($total, 2, ',', '&nbsp;') . '</td>'
        . '<td align=right border=1111  >' . number_format($price, 2, ',', '&nbsp;') . '</td>'
        . '<td align=right border=1111  >' . number_format($summ_no_nds, 2, ',', '&nbsp;') . '</td>'
        . '<td align=right border=1111  >' . ($no_nds ? 'Без НДС' : $nds) . '</td>'
        . '<td align=right border=1111  >' . ($no_nds ? '-' : number_format($sum_nds, 2, ',', '&nbsp;')) . '</td>'
        . '<td align=right border=1111 >' . number_format($summ, 2, ',', '&nbsp;') . '</td>'
        . '</tr>';
    $var = $var + 6.5;
}
$var1 = round($var, 0);
// $html .= '</table>';
// $pdf->htmltable($html);

$html .= '<tr>' .
    '<td align=left border=1111  colspan="2">ИТОГО</td>'
    . '<td align=center border=1111  >x</td>'
    . '<td align=center border=1111  >x</td>'
    . '<td align=center border=1111  >x</td>'
    . '<td align=right border=1111  >' . number_format($summ_no_nds_all, 2, ',', '&nbsp;') . '</td>'
    . '<td align=center border=1111  >x</td>'
    . '<td align=right border=1111  >' . ($no_nds ? 'x' : number_format($sum_nds_all, 2, ',', '&nbsp;')) . '</td>'
    . '<td align=right border=1111 >' . number_format($summ_all, 2, ',', ' ') . '</td>'
    . '</tr>';
$html .= '<tr>' .
    '<td align=left border=0000  ></td>'
    . '<td align=center border=0000  ></td>'
    . '<td align=center border=0000  ></td>'
    . '<td align=center border=0000  ></td>'
    . '<td align=center border=0000  ></td>'

    . '<td align=right border=0000  colspan="3">Всего(с учетом НДС):</td>'
    . '<td align=right border=1111 >' . number_format($summ_all, 2, ',', '&nbsp;') . '</td>'
    . '</tr>';
$html .= '</table>';
$pdf->Ln();
$pdf->htmltable($html);

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
$pdf->Ln(10);
$pdf->SetFont('ArialMT', '', 8);

$pdf->MultiCell(0, 5, 'Всего оказано услуг на сумму: ' . $strsum1 . ', в т.ч.: НДС - ' . $strsum . '.');

$pdf->Ln();
$pdf->MultiCell(0, 5, 'Вышеперечисленные услуги выполненны полностью и в срок. Заказчик претензий по объему, качеству и срокам оказанния услуг не имеет.');

$pdf->Ln(25);


$pdf->Cell(100, 5, 'ИСПОЛНИТЕЛЬ:');
$pdf->Cell(100, 5, 'ЗАКАЗЧИК:');
$pdf->Ln();


$pdf->Cell(100, 0, iconv("UTF-8", "cp1251", $user_post));
$pdf->Ln(3);
$pdf->Cell(100, 5, '                _________________/' . iconv("UTF-8", "cp1251", $user_fio));
$pdf->Cell(100, 5, '__________________________/' . iconv("UTF-8", "cp1251", $fio_dir1));
$pdf->Ln();
$pdf->Cell(100, 10, '                                     м.п');
$pdf->Cell(100, 10, '                                     м.п');


$pdf->Output();


?>
