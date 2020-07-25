<?php
    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $clients = new classes_clients();
        $value = '%' . (!isset($_POST['name_client']) ? '' : strval($_POST['name_client'])) . '%';
        //$value = '%Брестгипрозем%';
        $where = array(
            'sql' => "CLIENT_NAME LIKE :CLIENTNAME OR PHONE_MOB LIKE :PHONEMOB OR PHONE_CITY LIKE :PHONECITY OR UNP LIKE :UNP",
            'param' => array(
                'CLIENTNAME' => $value,
                'PHONEMOB' => $value,
                'PHONECITY' => $value,
                'UNP' => $value,
            ),
        );

        $list = $clients->loadAll($where);
        $ajaxObject->setData($list);
    } while(false);
?>