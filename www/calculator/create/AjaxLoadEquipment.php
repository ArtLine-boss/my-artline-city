<?php

    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        //по типу операции определяем какое оборудование добовлять
        $sql = "SELECT equipment.*
                FROM equipment
                INNER JOIN equipment_operation ON equipment.ID=equipment_operation.EQUIPMENT_ID AND equipment_operation.OPERATION_TYPE=" . CONSTANTS::OPERATIONS_TYPE_PRINT . "
                WHERE equipment.l_use=1
                ORDER BY equipment.EQ_NAME";

        $equipments = new classes_equipment();
        $list = $equipments->selectByQuery($sql);
        /*$where = array('sql' => "l_use = 1 AND EQ_NAME IS NOT NULL", 'param' => null);
        $list = $equipments->loadAll($where, 'EQ_NAME');*/
        $ajaxObject->setData($list);
    } while(false);
?>
