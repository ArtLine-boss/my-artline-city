<?


include '../firewall1.php';
session_start();
$login = $_SESSION['login'];

$id = $_GET['id'];
$id1 = $_GET['id1'];
include_once '../db.php';

// Переход на Мечту
$sql = "SELECT parent_company FROM orders WHERE NUMBER=" . $id;
$resultSql = mysql_query($sql) or die($sql);
$rowSql = mysql_fetch_assoc($resultSql);
if ($rowSql['parent_company'] != 2) {
    die("Ошибка! Обратитесь к системному администратору!");
}

$query = "UPDATE orders SET STATUS_ID = 1 WHERE  NUMBER=" . $id;
mysql_query($query) or die($query);

/*
$query = "UPDATE order_product SET status = '1' WHERE  FIND_IN_SET(id,'".$id1."') and flags = '4'";
mysql_query($query) or die($query);
$query = "UPDATE order_product SET status = '10' WHERE  FIND_IN_SET(id,'".$id1."') and flags = '1'";
mysql_query($query) or die($query);
$query = "UPDATE order_product SET status = '11' WHERE  FIND_IN_SET(id,'".$id1."') and flags = '2'";
mysql_query($query) or die($query);
$query = "UPDATE order_product SET status = '12' WHERE  FIND_IN_SET(id,'".$id1."') and flags = '3'";
mysql_query($query) or die($query);

*/

$query = "select status,id,flags from order_product where FIND_IN_SET( id , '" . $id1 . "') ";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
    $flags = $row[2];
    $status_old = $row[0];
    $id12 = $row[1];
    $orderDate = date("Y-m-d H:i:s");
    if ($flags == "4") {
        $status = 1;
    }
    if ($flags == "2") {
        $status = 11;
    }
    if ($flags == "3") {
        $status = 12;
    }
    if ($flags == "1") {
        $status = 10;
    }
    $query = "UPDATE order_product SET status = '" . $status . "' WHERE id = " . $id12;
    mysql_query($query) or die($query);


    $query2 = "INSERT INTO log_task (id_prod, user_log, datetime, status_old,status_new) VALUES('{$id12}','{$login}','{$orderDate}','{$status_old}','{$status}');";
    mysql_query($query2) or die($query2);


}

mysql_close($connection);
?>