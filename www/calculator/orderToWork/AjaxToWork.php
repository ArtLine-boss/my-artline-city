<?php
    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $param = (isset($_GET['param']) && !empty(json_decode($_GET['param']))) ? json_decode($_GET['param']) : null;

        $to_work = new classes_toWork($AppUI->login, $param, $_FILES);

        if(null !== ($msg = $to_work->toWork())) {
            $ajaxObject->setMsg($msg);
            break;
        }

        $ajaxObject->setData($to_work->order_id);
    } while(false);
?>
