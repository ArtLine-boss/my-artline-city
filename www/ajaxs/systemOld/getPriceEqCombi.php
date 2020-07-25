<?php
$result = 0;

do {
    $ajaxObject = new core_Dto();
    if(!isset($_POST['eq']) || !isset($_POST['clr'])) {
        break;
    }
    $f_class = factorys_FactoryEquipment::bind($_POST['eq']);
    if(empty($f_class)) {
        break;
    }
    $result = $f_class->getOperationPrice($_POST);
} while(false);

$ajaxObject->setData($result);
?>