<?
	header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
	header('Pragma: no-cache'); // HTTP 1.0.
	header('Expires: 0'); // Proxies.
	require_once '../../../pages/pg/proc/functions.php';
	include "../../../pages/db.php";
	include "../../../pages/pg/utility.php";
	define('FPDF_FONTPATH','../../../PHPpdf/font/');
	require_once '../../../PHPpdf/lib/pdftable.inc.php';
	
	if( isset( $_GET['list_id'] ) ) {
		$data = json_decode($_GET['list_id']);
		if(!empty($data) && !empty($data->list_id)) {
			//��� ������������
			$username = "";
			if(!empty($data->username))
				$username = $data->username;
			$email_user = "";
			if(!empty($data->email))
				$email_user = $data->email;
			//����
			if(empty($data->date_)) {
				$date_ = date("d F Y");
			}
			else {
				$date_ = date("d F Y", strtotime($data->date_));
			}
			$date_ = str_replace('January', '������', $date_);
			$date_ = str_replace('February', '�������', $date_);
			$date_ = str_replace('March', '�����', $date_);
			$date_ = str_replace('April', '������', $date_);
			$date_ = str_replace('May', '���', $date_);
			$date_ = str_replace('June', '����', $date_);
			$date_ = str_replace('July', '����', $date_);
			$date_ = str_replace('August', '�������', $date_);
			$date_ = str_replace('September', '��������', $date_);
			$date_ = str_replace('October', '�������', $date_);
			$date_ = str_replace('November', '������', $date_);
			$date_ = str_replace('December', '�������', $date_);
			//�������� ���
			$nds = 0;
			$sel_nds = "SELECT val FROM settings WHERE id=4";
			$q_nds = mysql_query($sel_nds) or die(null);
			if($r_nds = mysql_fetch_array($q_nds)) {
				$nds = floatval(str_replace(',', '.', $r_nds['val']));
			}
			$nds_ = round((1 + $nds / 100) * 100) / 100;
			//�������� ��� ���������
			$fio = "";
			$sel_fio = "SELECT val FROM settings WHERE id=5";
			$q_fio = mysql_query($sel_fio) or die(null);
			if($r_fio = mysql_fetch_array($q_fio)) {
				$fio = $r_fio['val'];
			}
			//�������� ��������� ����
			$rs = "";
			$sel_rs = "SELECT val FROM settings WHERE id=6";
			$q_rs = mysql_query($sel_rs) or die(null);
			if($r_rs = mysql_fetch_array($q_rs)) {
				$rs = $r_rs['val'];
			}
			//�������� ��� �����
			$bank = "";
			$sel_bank = "SELECT val FROM settings WHERE id=13";
			$q_bank = mysql_query($sel_bank) or die(null);
			if($r_bank = mysql_fetch_array($q_bank)) {
				$bank = $r_bank['val'];
			}
			//�������� ������ � �������
			$select = "SELECT t1.id,t1.name,t1.client_id_db,t1.total,t1.summ,t1.summ_one,t1.`data`,t1.order_id,cl.CLIENT_NAME,cl.ADDRESS_POST,cl.PHONE_CITY,cl.PHONE_MOB,cl.EMAIL,cl.ACCT,cl.BANK,cl.CODE_BANK,cl.fio_dir,cl.num_doc
						FROM
							(SELECT bitrix.id,bitrix.name,bitrix.client_id_db,bitrix.total,bitrix.summ,bitrix.summ_one,bitrix.`data`,bitrix.order_id FROM bitrix24_template_calculation bitrix WHERE bitrix.id IN ({$data->list_id})) t1
						LEFT JOIN
							clients cl
						ON t1.client_id_db=cl.ID";
			$query = mysql_query($select) or die(null);
			while($row = mysql_fetch_array($query)) {
				$orders[] = $row;
			}
			//������ �������
			if(!$orders || empty($orders)) {
				die(null);
			}
			if($orders[0]['client_id_db'] == 0) {
				if(!empty($data->client)) {
					$client = $data->client;
					if(!empty($client->name))
						$CLIENT_NAME = $client->name;
					if(!empty($client->address))
						$ADDRESS_POST = $client->address;
					if(!empty($client->phone))
						$PHONE = $client->phone;
					if(!empty($client->email))
						$EMAIL = $client->email;
					if(!empty($client->acc_num))
						$ACCT = $client->acc_num;
					if(!empty($client->bank))
						$BANK = $client->bank;
					if(!empty($client->bik))
						$CODE_BANK = $client->bik;
					if(!empty($client->manager))
						$fio_dir1 = $client->manager;
					if(!empty($client->num_doc))
						$num_doc = $client->num_doc;
				}
			}
			else {
				$CLIENT_NAME = $orders[0]['CLIENT_NAME'];
				$ADDRESS_POST = $orders[0]['ADDRESS_POST'];
				$PHONE = $orders[0]['PHONE_CITY']." ".$orders[0]['PHONE_MOB'];
				$EMAIL = $orders[0]['EMAIL'];
				$ACCT = $orders[0]['ACCT'];
				$BANK = $orders[0]['BANK'];
				$CODE_BANK = $orders[0]['CODE_BANK'];
				$fio_dir1 = $orders[0]['fio_dir'];
				$num_doc = $orders[0]['num_doc'];
			}
			
			//����� �����
			if(!empty($orders[0]['order_id'])) {
				$num_act = $orders[0]['order_id'];
			}
			else {
				$insert = "INSERT INTO orders (CALC) VALUES(1)";
				$query_insert = mysql_query($insert) or die(null);
				if($query_insert) {
					$num_act = mysql_insert_id();
					//����� � ��
					foreach($orders as $order) {
						$update = "UPDATE bitrix24_template_calculation SET order_id=".$num_act." WHERE id=".$order['id'];
						$query_update = mysql_query($update) or die(null);
					}
				}
			}
			
			/************������� PDF*************/
			$pdf = new PDFTable();
			$pdf->AddFont('ArialMT','','arial.php');
			$pdf->AddPage();
			//�������
			$pdf->Image('../../../pages/pg/proc/logo.jpg',140,5,45);
			//���������� � ��������
			$pdf->SetFont('ArialMT','',9);
			$pdf->Cell(0,0,'������������: ��� "�����������"',0,0);
			$pdf->Ln(); 
			$pdf->Cell(0,7,'224030, �. �����, ��. ���������, 74-9 ',0,0);
			$pdf->Ln(); 
			$pdf->Cell(0,0,'8 (0162) 536828, 8 (029) 6094477, 8 (033) 6094477',0,0);
			$pdf->Ln(); 
			$pdf->Cell(0,7,'e-mail: '.iconv("UTF-8", "cp1251", $email_user));
			$pdf->Ln(); 
			$pdf->Cell(0,0,'���: 290479470');
			$pdf->Ln(); 
			$pdf->Cell(0,7,'�/��: '.iconv("UTF-8", "cp1251", $rs));
			$pdf->Ln(); 
			$pdf->Cell(0,0,'����: ��� "��������������", ��������� ��������� ��������');
			$pdf->Ln(); 
			$pdf->Cell(0,7,'��� �����: '.iconv("UTF-8", "cp1251", $bank));
			$pdf->Ln(); 
			$pdf->Cell(0,0,'����� �����: �. �����, ��. ���. �����������, 15');
			//���������
			$pdf->SetFont('ArialMT','',14);
			$pdf->Ln(10);
			$pdf->Cell(0,0,'����-�������� ������������ ��� � '.$num_act,0,0,'C');
			$pdf->Ln(); 
			$pdf->Cell(0,10,'�� '.$date_.' �. ',0,0,'C');
			$pdf->Ln(10);
			//������ � ���������
			$pdf->SetFont('ArialMT','',8);
			$pdf->Cell(0,0,'��������: '.iconv("UTF-8", "cp1251", $CLIENT_NAME) );
			$pdf->Ln(); 
			$pdf->Cell(0,7,'��. �����: '.iconv("UTF-8", "cp1251", $ADDRESS_POST));
			$pdf->Ln(); 
			$pdf->Cell(0,0,'���.: '.iconv("UTF-8", "cp1251", $PHONE));
			$pdf->Ln(); 
			$pdf->Cell(0,7,'e-mail: '.iconv("UTF-8", "cp1251", $EMAIL));
			$pdf->Ln(); 
			$pdf->Cell(0,0,'�/��: '.iconv("UTF-8", "cp1251", $ACCT));
			$pdf->Ln(); 
			$pdf->Cell(0,7,'����: '.iconv("UTF-8", "cp1251", $BANK) );
			$pdf->Ln(); 
			$pdf->Cell(0,0,'��� �����: '.iconv("UTF-8", "cp1251", $CODE_BANK) );
			$pdf->Ln(3); 
			$pdf->Cell(0,0,'����� ��������: '.iconv("UTF-8", "cp1251", $num_doc));
			$pdf->Ln(5);
			$pdf->SetFont('ArialMT','',8);
			$pdf->Ln();
			//�������
			$html = '<table  border=1 align=center>
					  <tr>
						<td align=center width=5>�</td>
						<td align=center width=45>������������ ������</td>
						<td align=center width=14>��. ���.</td>
						<td align=center width=12>���-��</td>
						<td align=center width=15>���� ���. ���.</td>
						<td align=center width=5>���. ����.</td>
						<td align=center width=18>����� ���. ���.</td>
						<td align=center width=5>������ ���, %</td>
						<td align=center width=18>����� ��� ���. ���.</td>
						<td align=center width=18> ����� � ��� ���. ���. </td>
					  </tr>

					';
			$nm_row = 0;
			$summ_no_nds_all = 0;
			$sum_nds_all = 0;
			$summ_all = 0;
			foreach($orders as $order) {
				$nm_row++;
				$json = json_decode($order['data']);
				//������� ���������
				$unit = $json->unit_prod1;
				//���������� �� ������� � ������ ������ ���������
				$p_cir = $json->p_cir;
				//���� � �� ������� � ������ ���... ������� ���
				$cost = floatval(str_replace(',', '.', $order['summ_one']));
				$price = round(floatval(str_replace(',', '.', $order['total'])) * $cost * 100) / 100;
				$summ_no_nds_all += $price;
				//���� � ���
				$cost_nds = round(($price * $nds / 100) * 100) / 100;
				$sum_nds_all += $cost_nds;
				$price_nds = floatval(str_replace(',', '.', $order['summ']));
				$summ_all += $price_nds;
				//����� ������
				$html .= '<tr>'.
							'<td align=right border=1111   >'.$nm_row.'</td>'
							.'<td align=left border=1111  >'.iconv("UTF-8", "cp1251",$order['name']).'</td>'
							.'<td align=left border=1111  >'.iconv("UTF-8", "cp1251",$unit).'</td>'
							.'<td align=right border=1111  >'.str_replace('.', ',', $p_cir).'</td>'
							.'<td align=right border=1111  >'.number_format($cost , 2, ',', ' ').'</td>'
							.'<td align=right border=1111  >'.'0%'.'</td>'
							.'<td align=right border=1111  >'.number_format($price , 2, ',', ' ').'</td>'
							.'<td align=right border=1111  >'.$nds.'</td>'
							.'<td align=right border=1111  >'.number_format($cost_nds , 2, ',', ' ').'</td>'
							.'<td align=right border=1111 >'.number_format($price_nds , 2, ',', ' ').'</td>'
							.'</tr>'
							;
			}
			//�����
			$html .= '<tr>'.
					'<td align=right border=0000  ></td>'
					.'<td align=left border=0000  ></td>'
					.'<td align=left border=0000  ></td>'
					.'<td align=right border=0000  ></td>'
					.'<td align=right border=1111  >�����:</td>'
					.'<td align=right border=0000  ></td>'
					.'<td align=right border=1111  >'.number_format($summ_no_nds_all , 2, ',', ' ').'</td>'
					.'<td align=right border=0000  > </td>'
					.'<td align=right border=1111  >'.number_format($sum_nds_all , 2, ',', ' ').'</td>'
					.'<td align=right border=1111 >'.number_format($summ_all , 2, ',', ' ').'</td>'
					.'</tr>'
					;
			$html .= '</table>';
			$pdf->Ln();
			$pdf->htmltable($html);
			$pdf->Ln();
			//����� ��������
			$cur_val = "933";
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
			$pdf->SetFont('ArialMT','',9);
			$pdf->Cell(0,0,'����� ���: '.$strsum );
			$pdf->Ln();
			$pdf->Cell(0,10,'����� � ������ �� ����� � ���: '.$strsum1);
			//��� ���������
			$pdf->SetFont('ArialMT','',8);
			$pdf->Ln(10);
			$pdf->Cell(0,0,'��������! ');
			$pdf->Ln();
			$pdf->Cell(0,10,'��������� ����������� ����� ����������� ������ �� ��������� ����.  ');
			$pdf->Ln();
			$pdf->Cell(0,0,'��� ��������� ��������� ��� ���� ����� ����������� � ���������� ���������: ����� ���������� ���������, �������, ����, ');
			$pdf->Ln();
			$pdf->Cell(0,10,'������������; ������������ - ������ � ������ � ����������; �� � ������������� � ���. �����������, ������ (��� �������) ');
			$pdf->Ln(10);
			//����� ��� �������
			$pdf->Cell(100,5,'�����������:');  
			$pdf->Cell(100,5,'��������:');
			$pdf->Ln();
			$pdf->Cell(100,0,iconv("UTF-8", "cp1251", $user_post) );
			$pdf->Ln(3);
			$pdf->Cell(100,5,'                _________________/'. iconv("UTF-8", "cp1251", $username) );
			$pdf->Cell(100,5,'__________________________/'. iconv("UTF-8", "cp1251", $fio_dir1) );
			$pdf->Ln();
			$pdf->Cell(100,10,'                                     �.�');  
			$pdf->Cell(100,10,'                                     �.�');
			$pdf->Ln(10);
			$pdf->Cell(0,0,'��� ��������: '. iconv("UTF-8", "cp1251", $username));
			
			//����� PDF
			$pdf->Output();
		}
	}
?>