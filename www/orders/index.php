<?php
?>
<h1>Счета</h1>
<?php
    $sql = "SELECT `orders`.`NUMBER`, `orders`.`DATE_OR`, `clients`.`CLIENT_NAME`
            FROM `orders`
            JOIN `clients` ON `clients`.`ID` = `orders`.`CLIENT_ID`
            WHERE `orders`.`USER_ID` = '" . $AppUI->login . "'";
    echo API::viewTable2('listOrders', $sql, 'clickOrder');
?>
