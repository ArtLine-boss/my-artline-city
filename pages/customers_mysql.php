<?php
	$host = 'localhost';
	/*$host = '25.32.68.33';*/
	$user = 'root';
	$pwsd = '';
	$db = 'Art'; 
	
	header("Access-Control-Allow-Origin: *");
	header("Content-Type: application/json; charset=UTF-8");
	
	$conn = new mysqli($host, $user, $pwsd, $db);
	
	// О нет!! переменная connect_errno существует, а это значит, что соединение не было успешным!
	if ($conn->connect_errno) {
		echo "Извините, возникла проблема на сайте";
		echo "Ошибка: Не удалсь создать соединение с базой MySQL и вот почему: \n";
		echo "Номер_ошибки: " . $conn->connect_errno . "\n";
		echo "Ошибка: " . $conn->connect_error . "\n";
		exit;
	}
	
	$result = $conn->query("SELECT ID CompanyName, USER_FIO City, USER_FIO Country FROM Users");
	
	$outp = "";
	while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
		if ($outp != "") {$outp .= ",";}
		$outp .= '{"Name":"'  . $rs["CompanyName"] . '",';
		$outp .= '"City":"'   . $rs["City"]        . '",';
		$outp .= '"Country":"'. $rs["Country"]     . '"}';
	}
	$outp ='{"records":['.$outp.']}';
	$conn->close();
	
	echo($outp);
?>