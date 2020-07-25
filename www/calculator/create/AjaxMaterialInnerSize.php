<?php
    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $equipment = isset($_POST['equipment']) ? $_POST['equipment'] : null;

        $innerSize = new classes_MaterialInnerSize($equipment);

        $ajaxObject->setData($innerSize->SelectMaterial());
    } while(false);
?>
