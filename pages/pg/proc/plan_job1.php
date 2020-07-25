<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
define('FPDF_FONTPATH','../../../PHPpdf/font/');
require_once '../../../PHPpdf/lib/pdftable.inc.php';
include "../../db.php";

$ID = $_GET['id'];
$num = $_GET['num'];
$pdf = new PDFTable( 'P', 'mm', 'A5' );
;
$pdf->AddFont('ArialMT','','arial.php');
$pdf->AddFont('ArialMTB','B','arial_bold.php');
$pdf->SetTopMargin(5);
$pdf->SetRightMargin(5);
$pdf->SetLeftMargin(5);

$query="	select DATE_FORMAT(op.dates_rdy, '%d.%m.%Y %H:%i') dt, 
				op.ORDER_ID ,
				(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
				(select (select u.USER_FIO from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) men, 
				op.p_names, 
				op.total, 
				op.size,
				op.cshivka, 
				op.fast,
				op.template  ,
				op.units,
				op.temp_pr, 
				op.id,
				op.num_prod_ord
			from order_product op where  op.ORDER_ID = ".$ID." ORDER BY op.num_prod_ord";
			
IF ($num != ''){
	
	$query="	select DATE_FORMAT(op.dates_rdy, '%d.%m.%Y %H:%i') dt, 
				op.ORDER_ID ,
				(select (select c.CLIENT_NAME from clients c where c.ID = o.CLIENT_ID ) from orders o where o.number = op.ORDER_ID) name,
				(select (select u.USER_FIO from users u where u.USER_LOGIN = o.USER_ID ) from orders o where o.number = op.ORDER_ID) men, 
				op.p_names, 
				op.total, 
				op.size,
				op.cshivka, 
				op.fast,
				op.template  ,
				op.units,
				op.temp_pr, 
				op.id,
				op.num_prod_ord
			from order_product op where  op.ID in (".$num.") ORDER BY op.num_prod_ord";
	
}
			
$result = mysql_query($query) or die($query);
$kol = 0;
while ($row = mysql_fetch_row($result)) { 


$fast = '';
$cshivk = '';
$html = '';
$cshivka = explode("|", $row[7]);
	IF ($cshivka[0] != "" AND $cshivka[0] != "0") {
		$cshivk .= 'Переплет по '.iconv("UTF-8", "cp1251", $cshivka[1]).' стороне; '; 

		switch ($cshivka[0]) {
			case '1': $cshivk .= 'пружина 6,4 мм'; break;
			case '2': $cshivk .= 'пружина 8,0 мм'; break;
			case '3': $cshivk .= 'пружина 9,5 мм'; break;
			case '4': $cshivk .= 'пружина 11,0 мм'; break;
			case '5': $cshivk .= 'пружина 12,7 мм'; break;
			case '6': $cshivk .= 'пружина 14,3 мм'; break;
			case '7': $cshivk .= 'скоба'; break;
			case '8': $cshivk .= 'Твердая обложка (PUR)'; break;
			case '9': $cshivk .= 'Твердая обложка (скобы)'; break;
			case '10': $cshivk .= 'Твердая обложка'; break;
			case '11': $cshivk .= 'Твердая обложка (пружина)'; break;
			case '12': $cshivk .= 'термоклей'; break;
			case '13': $cshivk .= 'нитка'; break;
			case '14': $cshivk .= 'пружины 6,4 мм'; break;
			case '15': $cshivk .= 'пружины 6,4 мм'; break;
			case '16': $cshivk .= 'пружина 8,0 мм'; break;
			case '17': $cshivk .= 'пружина 8,0 мм'; break;
		}
	}


$fast .= "Cрочность:" ;
	switch ($row[8]) {
			case '1': $fast .= 'ОБЫЧНО'; break;
			case '1.2': $fast .= 'СРОЧНО'; break;
			case '1.5': $fast .= 'ОЧЕНЬ СРОЧНО'; break;
	}
$pdf->SetCompression(true);
$pdf->AddPage();

$pdf->Ln(2); 
$date = new DateTime();

$pdf->SetFont('ArialMT','',10);
$pdf->Cell(25,0,"Дата печати бланка: ".$date->format('d-m-Y H:i'),0,0); 
$pdf->Ln(5);
$pdf->Cell(25,10,"Дата сдачи: ",0,0); 
$pdf->Cell(80,10,iconv("UTF-8", "cp1251", $row[0]),0,0); 
$pdf->SetFont('ArialMT','',16);
$pdf->Cell(0,10,iconv("UTF-8", "cp1251", $row[1])."_".$row[13],0,0);
$pdf->SetFont('ArialMT','',10);
$pdf->Ln(); 
$pdf->Cell(25,0,"Заказчик: ",0,0);
$pdf->Cell(45,0,iconv("UTF-8", "cp1251", $row[2]),0,0);
$pdf->Ln(); 
$pdf->Cell(25,10,"Продукция: ",0,0);
$pdf->Cell(0,10,iconv("UTF-8", "cp1251", $row[4]),0,0);
$pdf->Ln(); 
$pdf->Cell(25,0, "Формат, мм: ",0,0);
$pdf->Cell(45,0, $row[6],0,0);
$pdf->Cell(25,0,"Тираж : ",0,0);
	$koff = 1;
if(iconv("UTF-8", "cp1251",$row[10]) == 'тыс.шт.'){
	$koff = 1000;
}
$pdf->Cell(0,0,$row[5] * $koff ,0,0);
$pdf->Ln();
$pdf->Cell(25,10,"Менеджер: ",0,0);
$pdf->Cell(0,10,iconv("UTF-8", "cp1251", $row[3]),0,0);
$pdf->Ln(); 




 
$pdf->SetFont('ArialMTB','B',10);
$pdf->Cell(0,7, $cshivk ,0,0);
$pdf->SetFont('ArialMT','',10);
$pdf->Ln(); 
$html = '
<table  border= 0 align=left>
  <tr>
    <td align=left ></td>
    <td align=left ></td>
    <td align=left ></td>
    <td align=left ></td>
	 <td align=left >Отметка</td>
	
  </tr>

';

$html1 = '';														 
				 
	

$temp = explode("^", iconv("UTF-8", "cp1251", $row[9]));
	FOR ($i = 0; $i < count($temp); $i++){
		$z = $i + 1;
		$part = explode("|", $temp[$i]);
		IF ($part[0] != "" AND  $part[0] != '0') {
			$html .= '<tr>
							 <td align=left bgcolor="#D0D0FF">Наим. части:</td>
							 <td align=left bgcolor="#D0D0FF">'.$part[0].'</td>
							 <td align=left ></td>
							 <td align=left ></td>
							 <td align=left ></td>

				
						  </tr>'		;														 			
		} else {
				$html .= '<tr>
							 <td align=left bgcolor="#D0D0FF">Наим. части:</td>
							 <td align=left bgcolor="#D0D0FF"></td>
							 <td align=left ></td>
							 <td align=left ></td>
							 <td align=left ></td>
						  </tr>'		;		
			
		}
		
		$temp1 = explode("^", iconv("UTF-8", "cp1251", $row[11]));

		$part11 = explode("|", $temp1[0]);
		$pol_vin = $part11[25];
		

			IF ($part[1] != ""){
			$sizree = explode("*", $part[1]);
		}
		else {
				$sizree = explode("*", $row[6]);
		}
		$siz1 =  $sizree[0] + $pol_vin  * 2;
		$siz2 =  $sizree[1] + $pol_vin  * 2;
		
		$peb = $siz1.'*'.$siz2;
		
		$html .= '<tr>
							 <td align=left bgcolor="#D0D0FF">Печатный размер</td>
							 <td align=left bgcolor="#D0D0FF">'.$peb.'</td>
							 <td align=left ></td>
							 <td align=left ></td>
							 <td align=left ></td>

				
						  </tr>'		;		
		
		
		IF ($part[1] != "" AND  $part[1] != '0') { 
		$html .= '<tr>
							 <td align=left>Размер изделия:</td>
							 <td align=left>'.$part[1].'</td>
							 <td align=left></td>
							 <td align=left></td>
							 <td align=left></td>

				
						  </tr>'		;		
		}
		IF ($part[2] != "" AND  $part[2] != '0') { 
		$html .= '<tr>
							 <td align=left>Кол-во стр:</td>
							 <td align=left>'.$part[2].'</td>
							 <td align=left></td>
							 <td align=left></td>
							 <td align=left></td>

				
						  </tr>'		;		
		}
		IF ($part[3] != "" AND  $part[3] != '0' AND  $part[3] != 'Выберите') { 
		$html .= '<tr>
							 <td align=left>Оборудование:</td>
							 <td align=left>'.$part[3].'</td>
							 <td align=left>Цвет</td>
							 <td align=left> '.$part[4].'</td>
							 <td align=left>_______</td>

				
						  </tr>'		;		
		}
		IF ($part[6] != "" AND  $part[6] != '0') { 
		$mat = explode(":",$part[6]);
		$size_mat = $part[5];
		IF ($part[28] != ""){
			$size_mat = $part[28];
		}
		
		$html .= '<tr>
							 <td align=left>Бумага:</td>
							 <td align=left>'.$mat[0].'</td>
							 <td align=left>'.$size_mat.'</td>
							 <td align=left>Кол-во листов :'.$mat[1].' </td>
							 
							 <td align=left>_______</td>
				
						  </tr>'		;	
		}
		IF ($part[8] != "" AND  $part[8] != '0') { 
		$html .= '<tr>
							 <td align=left >Ламинирование:</td>
							 <td align=left>'.$part[8].'</td>
							 <td align=left></td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;	}
			
		IF ($part[7] != "" AND  $part[7] != '0') {
				$html .= '<tr>
							 <td align=left>Резка</td>
							 <td align=left></td>
							 <td align=left></td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;				}
					  	$html1 = '';
						IF (($part[26] != "" AND  $part[26] != '0') || (!empty($part[29]) && !empty($part[30]))) {
				$html .= '<tr>
							 <td align=left>Плоттерная резка</td>
							 <td align=left></td>
							 <td align=left></td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;				}
					  	$html1 = '';
								
		IF ($part[9] != "" AND  $part[9] != '0') {
				$html1 .= '<tr>
							 <td align=left>Биговка:</td>
							 <td align=left>'.$part[9].'</td>
							 <td align=left>'.(double)str_replace(',', '.',$part[9]) * $row[5].'</td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;
			}
		IF ($part[10] != "" AND  $part[10] != '0') {
				$html1 .= '<tr>
							 <td align=left>Перфорация:</td>
							 <td align=left>'.$part[10].'</td>
							 <td align=left>'.(double)str_replace(',', '.',$part[10]) * $row[5].'</td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;				}
		IF ($part[11] != "" AND  $part[11] != '0') {
				$html1 .= '<tr>
							 <td align=left>Скругление углов:</td>
							 <td align=left>'.$part[11].'</td>
							 <td align=left>'.(double)str_replace(',', '.',$part[11]) * $row[5].'</td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;	
				}
		IF ($part[12] != "" AND  $part[12] != '0') { 
		$html1 .= '<tr>
							 <td align=left>Отверстия: '.$part[13].'</td>
							 <td align=left>'.$part[12].'</td>
							 <td align=left>'.(double)str_replace(',', '.',$part[12]) * $row[5].'</td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;	
				}
		IF ($part[14] != "" AND  $part[14] != '0') { 
		$html1 .= '<tr>
							 <td align=left>Люверс: '.$part[15].'</td>
							 <td align=left>'.$part[14].'</td>
							 <td align=left>'.(double)str_replace(',', '.',$part[14]) * $row[5].'</td>
							 <td align=left></td>
							<td align=left>_______</td>
						  </tr>'		;	
				}
		IF ($part[16] != "" AND  $part[16] != '0') { 

		$html1 .= '<tr>
							 <td align=left>Вырубка:</td>
							 <td align=left>'.$part[16].'</td>
							 <td align=left>'.ROUND($row[5] * $koff / (double)str_replace(",", ".", $part[16]),0).'</td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;	
				}
		IF ($part[17] != "" AND  $part[17] != '0') { 
		$html1 .= '<tr>
							 <td align=left>Конгрев:</td>
							 <td align=left>'.$part[17].'</td>
							 <td align=left>'.$row[5] * $koff / (double)str_replace(',', '.',$part[17]).'</td>
							 <td align=left></td>
							 <td align=left>_______</td>
						  </tr>'		;	
				}
			IF ($part[18] != "" AND  $part[18] != '0') { 
			$html1 .= '<tr>
								 <td align=left>Тиснение:</td>
								 <td align=left>'.$part[18].'</td>
								 <td align=left>'.$row[5] * $koff / (double)str_replace(',', '.',$part[18]).'</td>
								 <td align=left></td>
								 <td align=left>_______</td>
							  </tr>'		;	
				}
	
		
		
		if ($html1 != ''){
			$html .= '<tr>
				 <td align=left ></td>
				 <td align=left >На 1 изд.</td>
				 <td align=left >На тираж</td>
				 <td align=left ></td>
				 <td align=left ></td>
				</tr>

			'; 
			$html .= $html1;
		}
			$html .= '<tr>
    <td align=left ></td>
    <td align=left ></td>
    <td align=left ></td>
    <td align=left ></td>
	 <td align=left ></td>
	
  </tr>'		;	
		
	}

$pdf->htmltable($html);


}
$pdf->SetCompression(true);
$pdf->Output();

									
?>
