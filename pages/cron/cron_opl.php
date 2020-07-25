<?
	include '../db.php';
	$dir = '../import/';
	
	foreach (glob($dir."*.in") as $filename) {
		
		$nfile = explode("/", $filename);
		$namefile = explode(".", $nfile[2]);
 
		$namef = $dir.$namefile[0].".csv";
		$newf = $dir.$namefile[0].'_'.date("d_m_y").".out";
		 $namef1 = $dir.$namefile[0].'_'.date("d_m_y").".csv";

		$f = fopen($namef, "rt") or die("Ошибка открытия файла!");
		for ($i=0; $data=fgetcsv($f,1000,";"); $i++) {
			/*  echo iconv("WINDOWS-1251", "UTF-8",$data[0])."<br>"; // УНП
				echo iconv("WINDOWS-1251", "UTF-8",$data[1])."<br>"; // Наименование фирмы 
				echo iconv("WINDOWS-1251", "UTF-8",$data[2])."<br>"; // Номер счета
				echo iconv("WINDOWS-1251", "UTF-8",$data[3])."<br>"; // Сумма
				echo iconv("WINDOWS-1251", "UTF-8",$data[4])."<br>"; // Дата
				echo iconv("WINDOWS-1251", "UTF-8",$data[5])."<br>"; // Тип
			*/
			/*Нам оплата*/
			IF (iconv("WINDOWS-1251", "UTF-8",$data[5]) == "3") {
				$client = 0;
				IF(iconv("WINDOWS-1251", "UTF-8",$data[2]) != ""){
					$query = 'select client_id from orders where number ='.iconv("WINDOWS-1251", "UTF-8",$data[2])." LIMIT 1"; 
					$result = mysql_query($query) or die($query);
					while ($row = mysql_fetch_row($result)) { 
						$client = $row[0];
					}
				}
				
				
				IF (iconv("WINDOWS-1251", "UTF-8",$data[0]) != ""){
					
					$query = "select id from clients where UNP = '".iconv("WINDOWS-1251", "UTF-8",$data[0])."'"; 
					$result = mysql_query($query) or die($query);
					while ($row = mysql_fetch_row($result)) { 
						$client1 = $row[0];
					}
				}
				ELSE {
					$query = "select id from clients where CLIENT_NAME = '".iconv("WINDOWS-1251", "UTF-8",$data[1])."'"; 
					$result = mysql_query($query) or die($query);
					while ($row = mysql_fetch_row($result)) { 
						$client1 = $row[0];
					} 
				}
				
				$num = iconv("WINDOWS-1251", "UTF-8",$data[2]);
				/*Проверка на номер счета и id cl*/
				IF($client1 != $client AND iconv("WINDOWS-1251", "UTF-8",$data[0]) != '290479470'  AND iconv("WINDOWS-1251", "UTF-8",$data[0]) != '291546971') {
					$client = $client1;	
					$num =0;
				}
				
				IF ( $client == 0 OR  $client == "0"){
					$name = iconv("WINDOWS-1251", "UTF-8",$data[1]);
					$unp_firm = iconv("WINDOWS-1251", "UTF-8",$data[0]);
				$query = "INSERT INTO clients (CLIENT_NAME, UNP) VALUES('{$name}','{$unp_firm}');";
				mysql_query($query) or die($query);
				$client = mysql_insert_id();
				}
				
				
				$sum = iconv("WINDOWS-1251", "UTF-8",$data[3]);
				$date_ = iconv("WINDOWS-1251", "UTF-8",$data[4]);
				$list_comm = $namef.", ".iconv("WINDOWS-1251", "UTF-8",$data[1]);
				$query = "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, ALL_SUM, DATE_,view_opl,Comments) VALUES ('{$client}', '{$num}', '{$sum}', '{$date_}','3','{$list_comm}');";
				
				
				
				mysql_query($query) or die($query);
				}
				/*Мы платим*/
				IF (iconv("WINDOWS-1251", "UTF-8",$data[5]) == "5") {
				$client = 0;
				IF (iconv("WINDOWS-1251", "UTF-8",$data[0]) != ""){
				$query = "select id from firms where unp = '".iconv("WINDOWS-1251", "UTF-8",$data[0])."'"; 
				$result = mysql_query($query) or die($query);
				while ($row = mysql_fetch_row($result)) { 
				$client = $row[0];
				}
				} 
				ELSE {
				$query = "select id from firms where FIRM_NAME = '".iconv("WINDOWS-1251", "UTF-8",$data[1])."'"; 
				$result = mysql_query($query) or die($query);
				while ($row = mysql_fetch_row($result)) { 
				$client = $row[0];
				} 
				}
				
				
				IF ( $client == 0 OR  $client == "0"){
				$name = iconv("WINDOWS-1251", "UTF-8",$data[1]);
				$unp_firm = iconv("WINDOWS-1251", "UTF-8",$data[0]);
				$query = "INSERT INTO firms (FIRM_NAME, unp) VALUES('{$name}','{$unp_firm}');";
				mysql_query($query) or die($query);
				$client = mysql_insert_id();
				}
				$num = iconv("WINDOWS-1251", "UTF-8",$data[2]);
				$sum = iconv("WINDOWS-1251", "UTF-8",$data[3]);
				$date_ = iconv("WINDOWS-1251", "UTF-8",$data[4]);
				$list_comm = $namef.", ".iconv("WINDOWS-1251", "UTF-8",$data[1]);
				$query = "INSERT INTO oplati (CLIENT_ID, ORDER_NUM, OST_SUM, DATE_,view_opl,Comments) VALUES ('{$client}', '{$num}', '{$sum}', '{$date_}','5','{$list_comm}');";
				mysql_query($query) or die($query);
				echo $query."<br>";
				
				}
				
				
				}
				fclose($f);
				rename ($namef, $namef1);   
				rename ($filename, $newf);  // переименовать файл in в out
				// echo "Выгрузка прошла ".date("Y-m-d");  
				
				}
				
				//echo "1";
				
				?>								