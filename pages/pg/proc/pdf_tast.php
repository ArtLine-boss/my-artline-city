<?php
define('FPDF_FONTPATH','../../../PHPpdf/font/');
require_once '../../../PHPpdf/lib/pdftable.inc.php';
include "../../db.php";

	 $ID = $_GET['id'];

$query = "select o.number, (select c.CLIENT_NAME from clients c  where c.id = o.CLIENT_ID) CLIENT_NAME , (select u.USER_FIO from users u  where u.USER_LOGIN = o.USER_ID) USER_name from orders o where o.number =  ".$ID.";";
$result = mysql_query($query) or die($query);
if($row = mysql_fetch_row($result)){
	$id_num = $row[0];
	$CLIENT_NAME = $row[1];
	$USER_name = $row[2];
}


$pdf=new FPDF('P','mm','A5');
$pdf->AddFont('ArialMT','','arial.php');


$query = "select id, order_id, product_id, total,size, p_names from order_product where order_id = ".$id_num.";";
$result = mysql_query($query) or die($query );
while ($row = mysql_fetch_row($result)) { 



	$pdf->AddPage();

	$pdf->SetFont('ArialMT','',7);
	$pdf->Cell(80,0,'Номер заказа: '.iconv("UTF-8", "cp1251", $id_num),0,0);
		$pdf->Cell(0,0,"Менеджер: ".iconv("UTF-8", "cp1251", $USER_name),0,0);
	$pdf->Ln();
	$pdf->Cell(0,7,'Клиент: '.iconv("UTF-8", "cp1251", $CLIENT_NAME),0,0);
	$pdf->Ln(); 
	$pdf->Cell(0,0,'Наименование продукта: '.iconv("UTF-8", "cp1251", $row[5]),0,0);
	$pdf->Ln();
	$pdf->Cell(80,7,'Тираж : '.iconv("UTF-8", "cp1251", $row[3]),0,0);
	$pdf->Cell(0,7,'Размер: '.iconv("UTF-8", "cp1251", $row[4]),0,0);
	$pdf->Ln(10); 


	
	$query1 = "select 
	t.Numder_order,	
	(select p.p_names from order_product p where p.id = t.acct_or) product_name ,
	t.part,
	o.operation_name, 
	o.par,
	t.kol,	
	(select m.M_NAME from material_attr m where m.id = t.mater) mat,
	t.date_pos,
	t.status, 
	t.id, 
	t.num_prod, 
	t.acct_or, 
	t.comment ,
	(select user_id from time_eq where id = t.id) user_id,
	t.num_oper,
	(select eq_name from equipment e WHERE e.id = t.oborud) eq
from 	
	operations o, 	
	(select * from order_per o  WHERE o.acct_or = ".$row[0]." ) t 
where 
	o.ID = t.operation and (t.status <> '3' OR t.status <> '2')  ORDER BY t.part, t.num_oper;";
	$result1 = mysql_query($query1) or die($query1 );
	while ($row1 = mysql_fetch_row($result1)) { 
	if (iconv('utf-8', 'windows-1251', $row1[2]) != ''){
		$pdf->Cell(0,0, 'Часть: '.iconv('utf-8', 'windows-1251', $row1[2]),0,0);
					$pdf->Ln();
	}

$pdf->Cell(60,7,  'Оборудование: '.iconv('utf-8', 'windows-1251', $row1[15]),0,0);

		$pdf->Cell(0,7, 'Операция: '.iconv('utf-8', 'windows-1251', $row1[3]),0,0);
			$pdf->Ln();
 IF (strcasecmp(iconv('utf-8', 'windows-1251', $row1[2]),'Препресс')  == 0 OR strcasecmp(iconv('utf-8', 'windows-1251', $row1[2]),'Дизайн')  == 0){

			$srtrrr = '';
			IF (strcasecmp(iconv('utf-8', 'windows-1251', $row1[2]),'Препресс')  == 0){
			$qr = "select o1.NAME from (select oper_all from order_per  where id = (select num_order_per from time_eq where id = ".$row[0]." limit 1)) o, PR_OPER o1 where LOCATE(o1.id,o.oper_all)";
			}
			IF (strcasecmp(iconv('utf-8', 'windows-1251', $row1[2]),'Дизайн')  == 0){
			$qr = "select o1.NAME from (select oper_all from order_per  where id = (select num_order_per from time_eq where id = ".$row[0]." limit 1)) o, DIZ_OPER o1 where LOCATE(o1.id,o.oper_all)";
			}
			$result2 = mysql_query($qr) or die($qr);
			while ($row2 = mysql_fetch_row($result1)) {
				$srtrrr = $srtrrr.$row2[0].",";
			}
				IF ($srtrrr != ""){
				$pdf->Cell(60,0,  'Параметр: '.iconv('utf-8', 'windows-1251',substr($srtrrr, 0, -1)  ),0,0);
 }
 }

IF ( strcasecmp(iconv('utf-8', 'windows-1251', $row1[4]),'Да')  == 0  OR strcasecmp(iconv('utf-8', 'windows-1251', $row1[4]),'номер клише')  == 0 OR strcasecmp(iconv('utf-8', 'windows-1251', $row1[4]),'номер клише')  == 0)	{ 
	$pdf->Cell(60,0,  'Параметр: '.iconv('utf-8', 'windows-1251', $row1[12]),0,0);
}else {

	$pdf->Cell(60,0,  'Параметр: '.iconv('utf-8', 'windows-1251', $row1[4]),0,0);
	}
 
 



$pdf->Cell(0,0,  'Материал: '.iconv('utf-8', 'windows-1251', $row1[6]),0,0);
	$pdf->Ln();
$pdf->Cell(60,7,  'Кол-во: '.iconv('utf-8', 'windows-1251', $row1[5]),0,0);
	$pdf->Ln();
$pdf->Cell(0,0,  'Комментарий: '.iconv('utf-8', 'windows-1251', $row1[12]),0,0);
	$pdf->Ln();
$pdf->Cell(0,7,  '____________________________________________________________________________________________',0,0);
	$pdf->Ln();
	}	
	

}
$pdf->Output();
?>
