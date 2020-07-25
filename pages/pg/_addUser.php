<?php



/*
foreach ($_POST['usersOp'] as $names)
{
       $operation =  $operation.$names.'&';
}
$operation = substr($operation, 0, -1);*/


$operation = $_POST['usersOp'];
$usersFIO = $_POST['usersFIO']; 
$usersPost = $_POST['usersPost'];
$usersLogin = $_POST['usersLogin'];
$usersPassword = md5(md5($_POST['usersPassword']));
$usersPer = $_POST['usersPer'];

include_once '../db.php';

$query = "INSERT INTO users (USER_LOGIN, USER_PASSWORD, USER_FIO, USER_POST, USER_PER,USER_OP,reset_password) VALUES ('{$usersLogin}', '{$usersPassword}', '{$usersFIO}', '{$usersPost}', {$usersPer}, '{$operation}',1);";
mysql_query($query) or die("Query failed");
mysql_close($connection);

?>
<script >
	window.close();
	window.opener.location.reload();

	</script>

