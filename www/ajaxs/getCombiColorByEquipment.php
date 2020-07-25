<?php
$result = array();

do {
    $ajaxObject = new core_Dto();
    if(!isset($_POST['equipment'])) {
        break;
    }
    $c_oper = classes_operations::getOperationsByEquipment($_POST['equipment']);
    foreach ($c_oper as $k => $v) {
        if($v->OperationType == CONSTANTS::OPERATIONS_TYPE_PRINT_COMBI_COLOR) {
            $result[] = $v;
        }
    }
} while(false);

$ajaxObject->setData($result);
?>