<?php
    $result = new api_ConstructorCalc_Manager();
    do {
        if(null !== ($msg = $result->bind())) {
            $result = new api_ConstructorCalc_Manager();
            $ajaxObject->setMsg($msg);
            break;
        }
    } while(false);
    $ajaxObject->setData($result);
?>