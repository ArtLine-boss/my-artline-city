<?php
$list = ['popup_Mail'];
foreach ($list as $k => $class) {
    $popup = new $class();
    $popup->view();
    unset($popup);
}
?>