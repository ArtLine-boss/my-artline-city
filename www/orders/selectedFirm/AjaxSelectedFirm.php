<?php
    do {
        $ajaxObject = new core_Dto();

        $idAcct = (isset($_GET['idAct']) && !empty($_GET['idAct'])) ? intval($_GET['idAct']) : null;

        $order = new classes_orders();
        if(null !== ($msg = $order->loadById($idAcct))) {
            $ajaxObject->setMsg($msg);
            break;
        }
        $order->parent_company = ($order->parent_company == 1) ? 2 : 1;
        if(null !== ($msg = $order->store())) {
            $ajaxObject->setMsg($msg);
            break;
        }

    } while(false);
?>