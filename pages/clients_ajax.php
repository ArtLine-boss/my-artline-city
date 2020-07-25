<?
	include_once 'db.php';
	
	$query="select id,client_name, email,phone_city,phone_mob, unp, num_doc, acct, code_bank from clients";
		
	$json = Array();
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) {
			
	$json[] = array(
		'q' => "<a onclick=_reviewClient('$row[0]')><span class='pull-right'><i class='glyphicon glyphicon-pencil'></i></<span></a>",
		'name' =>$row[1],
		'unp' =>$row[5],
		'mail' =>$row[2],
		'phone1' =>$row[3],
		'phone2' =>$row[4],
		'dog' =>$row[6],
		'rs' =>$row[7],
		'bank' =>$row[8],
	);
}
echo json_encode($json);
?>