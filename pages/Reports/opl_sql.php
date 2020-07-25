<?

$dt1  = $_GET["dt1"];
$dt2  = $_GET["dt2"];



include "../db.php";

$qt = "select view_opl, ROUND(sum(all_sum),2) from oplati WHERE DATE_ >= '".$dt1."' and  DATE_ <= '".$dt2."' GROUP BY view_opl";

$result = mysql_query($qt);
$str = "";
while ($row = mysql_fetch_row($result)) {
 switch ($row[0]){
	case "1": $str .= 'Касса: '.$row[1].';    ';break;
   case "2": $str .= 'Терминал: '.$row[1].';    ';break;
   case "3": $str .= 'Безнал: '.$row[1].';    ';break;
	case "4": $str .= 'Наличные: '.$row[1].';    ';break;
}
}	 
echo $str;	

?> 