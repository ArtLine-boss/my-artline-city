<?php
    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $send = isset($_POST['send']) ? $_POST['send'] : null;
        $equipmentSize = new classes_MaterialForEquipmentSize($send);
        $ajaxObject->setData($equipmentSize->SelectMaterialForEquipment());
    } while(false);
?>
