<?php
    do {
        $ajaxObject = new core_Dto();

        $user = new classes_users();
        if(null !== ($msg = $user->loadByUnique('USER_LOGIN', $AppUI->login))) {
            $ajaxObject->setMsg($msg);
            break;
        }

        /** СМОТРИМ ИЛИ ПРИСЛАЛИ ФИЛЬТР */
        $filterCalc = null;
        $filterSelect = '';
        if(isset($_POST) && !empty($_POST)) {
            $filterCalc = (!empty($_POST['CalcFilter'])) ? $_POST['CalcFilter'] : null;
            if(!empty($filterCalc)) {
                $filterSelect .= (!empty($_POST['NameCalc'])) ? " AND bitrix24_template_calculation.name LIKE '%" . $_POST['NameCalc'] . "%'" : '';
                $filterSelect .= (!empty($_POST['NameClient'])) ? " AND clients.CLIENT_NAME LIKE '%" . $_POST['NameClient'] . "%'" : '';
                $filterSelect .= (!empty($_POST['StartDate'])) ? " AND bitrix24_template_calculation.date_add>='" . API::FormatDate(($_POST['StartDate'] . "00:00:00"), CONSTANTS::DB_DATETIME_FORMAT) . "'" : '';
                $filterSelect .= (!empty($_POST['EndDate'])) ? " AND bitrix24_template_calculation.date_add>='" . API::FormatDate(($_POST['EndDate'] . "23:59:59"), CONSTANTS::DB_DATETIME_FORMAT) . "'" : '';
            }
        }
        $bitrix = new classes_Bitrix24TemplateCalculation();
        $sql = "SELECT bitrix24_template_calculation.*
                FROM bitrix24_template_calculation";
        $sql .= (!empty($_POST['NameClient'])) ? " JOIN clients ON clients.ID=bitrix24_template_calculation.client_id_db" : "";
        $where = " WHERE bitrix24_template_calculation.user_login='" . $user->USER_LOGIN . "'";
        switch ($filterCalc) {
            case CONSTANTS::TYPECALC:
                $where = " WHERE bitrix24_template_calculation.user_login='" . $user->USER_LOGIN . "'";
                break;
            case CONSTANTS::TYPENOTCALC:
                $where = " WHERE bitrix24_template_calculation.user_login=''";
                break;
            case CONSTANTS::TYPEALLCALC:
                $where = " WHERE 1";
                break;
        }
        $where .= $filterSelect;
        $sql .= $where;
        $list = $bitrix->selectByQuery($sql);

        $answer = array();
        if(CONSTANTS::TYPECALC && $AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $answer = array(
                'OrdersUser' => $list
            );
        } elseif(CONSTANTS::TYPENOTCALC && $AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC_NOT_USER, true)) {
            $answer = array(
                'OrdersNotUser' => $list
            );
        } elseif(CONSTANTS::TYPEALLCALC && $AppUI->isAccess(ACCESSES::ACCESS_PAGE_CAL_ALL, true)) {
            $answer = array(
                'OrdersAll' => $list
            );
        }
        $ajaxObject->setData($answer);

    } while(false);
?>