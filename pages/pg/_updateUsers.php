<?php
include '../firewall.php';
?>
<?php 

$usersFIO = $_POST['usersFIO']; 
$usersPost = $_POST['usersPost'];
$usersLogin = $_POST['usersLogin'];
$usersPer = $_POST['usersPer'];
$usersId = $_POST['usersId'];

include_once '../db.php';

$query = "UPDATE users SET 
USER_LOGIN = '{$usersLogin}', 
USER_FIO = '{$usersFIO}', 
USER_POST = '{$usersPost}', 
USER_PER = {$usersPer} 
WHERE  ID = {$usersId};";
print $query ;
mysql_query($query) or die("Query failed");
mysql_close($connection);
?>
<script >
	window.close();
	window.opener.location.reload();
</script>
