<?php
    do {
        $ajaxObject = new core_Dto();
        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_ORDERS, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $orderId = (!empty($_POST['orderId'])) ? $_POST['orderId'] : null;
        $order = new classes_orders();

        if(null !== ($msg = $order->loadById($orderId))) {
            $ajaxObject->setMsg($msg);
            break;
        }

        $list = $order->getItems();

        $dt = [
            'fields' => [
                'ID' => API::__('ID'),
                'item_id' => API::__('item_id'),
                'name_item' => API::__('name_item'),
                'dates_rdy' => API::__('dates_rdy'),
                'status_item' => API::__('status_item'),
            ],
            'items' => $order->getItems(),
        ];

        $ajaxObject->setData($dt);

    } while(false);
?>