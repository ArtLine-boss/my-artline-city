<?php
/**
 * Возвращает стоимость операции (операций)
 */
    $result = 0;
    do {
        if(!empty($_POST['p_size_cut_op']) && !empty($_POST['p_size_cut_eq'])) {
            /** определяем оборудование */
            $eq = new classes_equipment();
            if(null !== ($msg = $eq->loadById($_POST['p_size_cut_eq']))) {
                break;
            }
            $arr_oper_by_eq = explode(',', $eq->oper);
            /** ищем первую операцию */
            $operation = new classes_operations();
            $search_oper = false;
            $opers = explode(',', $_POST['p_size_cut_op']);
            foreach ($opers as $k => $v) {
                if(in_array($v, $arr_oper_by_eq)) {
                    $search_oper = true;
                    if(null !== ($msg = $operation->loadById($v))) {
                        break(2);
                    }
                    break;
                }
            }
            if(!$search_oper)
                break;
            $result = floatval($operation->OPERATION_PRICE);
        }
    } while(false);
    $ajaxObject->setData($result);
?>