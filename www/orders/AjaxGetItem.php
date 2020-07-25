<?php
    do {
        $ajaxObject = new core_Dto();
        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_ORDERS, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $itemId = (!empty($_POST['itemId'])) ? $_POST['itemId'] : null;
        $item = new classes_orderItem();

        if(null !== ($msg = $item->viewById($itemId))) {
            $ajaxObject->setMsg($msg);
            break;
        }

        $ajaxObject->setData($item);

    } while(false);
?>