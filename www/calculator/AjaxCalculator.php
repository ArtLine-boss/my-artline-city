<?php
    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $user = new classes_users();
        if(null !== ($msg = $user->loadByUnique('USER_LOGIN', $AppUI->login))) {
            $ajaxObject->setMsg($msg);
            break;
        }

        $bitrix = new classes_Bitrix24TemplateCalculation();
        /*$where = array(
            'sql' => 'user_id=:user_id OR user_login=:user_login',
            'param' => array(
                'user_id' => $user->id_bitrix24,
                'user_login' => $user->USER_LOGIN,
            )
        );
        $listOrders = $bitrix->loadAll($where, 'date_add', 1);*/
        $sql = "SELECT 
                            bitrix24_template_calculation.id,
                            bitrix24_template_calculation.date_add,
                            bitrix24_template_calculation.name,
                            CONCAT(bitrix24_template_calculation.summ_one,IF(bitrix24_template_calculation.factor=1000, ' (за тыс.шт.)', ' (за шт.)')) summ_one,
                            bitrix24_template_calculation.summ,
                            users.USER_FIO
                        FROM bitrix24_template_calculation
                        JOIN users ON bitrix24_template_calculation.user_login=users.USER_LOGIN
                        WHERE (bitrix24_template_calculation.user_id=" . $user->id_bitrix24 . " AND bitrix24_template_calculation.user_id > 0) OR bitrix24_template_calculation.user_login='" . $user->USER_LOGIN . "'
                        ORDER BY bitrix24_template_calculation.DATE_ADD DESC";
        $listOrders = $bitrix->select($sql);

        $answer = array(
            'OrdersUser' => $listOrders
        );
        if($AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC_NOT_USER, true)) {
            $bitrix = new classes_Bitrix24TemplateCalculation();
            $where = array(
                'sql' => "user_id=0 AND user_login=''",
                'param' => null
            );
            $listOrdersNotUser = $bitrix->loadAll($where, 'date_add', 1);
            $answer['OrdersNotUser'] = $listOrdersNotUser;
        }

        if($AppUI->isAccess(ACCESSES::ACCESS_PAGE_CAL_ALL, true)) {
            $bitrix = new classes_Bitrix24TemplateCalculation();
            //$listOrdersAll = $bitrix->loadAll(null, 'date_add', 1);
            $sql = "SELECT 
                            bitrix24_template_calculation.id,
                            bitrix24_template_calculation.date_add,
                            bitrix24_template_calculation.name,
                            CONCAT(bitrix24_template_calculation.summ_one,IF(bitrix24_template_calculation.factor=1000, ' (за тыс.шт.)', ' (за шт.)')) summ_one,
                            bitrix24_template_calculation.summ,
                            users.USER_FIO
                        FROM bitrix24_template_calculation
                        JOIN users ON bitrix24_template_calculation.user_login=users.USER_LOGIN
                        ORDER BY bitrix24_template_calculation.DATE_ADD DESC";
            $listOrdersAll = $bitrix->select($sql);
            $answer['OrdersAll'] = $listOrdersAll;
        }

        $ajaxObject->setData($answer);
    } while (false);
?>