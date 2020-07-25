<?php

    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $equipment = isset($_POST['equipment']) ? $_POST['equipment'] : null;
        if (empty($equipment))
            $ajaxObject->setMsg("Пустой входной объект");
        else {
            $equipmentObject = new classes_equipment();
            if (null !== ($msg = $equipmentObject->loadById($equipment))) {
                $ajaxObject->setMsg($msg);
            } else {
                $operation = new classes_operations();
                $where = array('sql' => "FIND_IN_SET(ID ,'" . $equipmentObject->oper . "') AND OperationType=" . CONSTANTS::OPERATIONS_TYPE_PRINT, 'param' => null);
                $list = $operation->loadAll($where);
                $ajaxObject->setData($list);
            }
        }
    } while(false);
?>
