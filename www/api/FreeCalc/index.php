<?php
    do {
        $postData = file_get_contents('php://input');
        $hash = json_decode($postData, true);
		API::dSerializationForm($hash);
        $CALC_TEST = $hash->FormId;
        $bitrix = new classes_Bitrix24TemplateCalculation();
        if(null !== ($msg = $bitrix->loadById($CALC_TEST))) {
            $ajaxObject->setMsg($msg);
            break;
        }
        $bitrix->setId(null);
        $form = json_decode($bitrix->data);
        foreach ($hash as $k => $v) {
            $form->$k = $v;
        }
        $bitrix->data = json_encode($form);

        if(null !== ($msg = $bitrix->reCalc(null, true))) {
            $ajaxObject->setMsg($msg);
            break;
        }

        $ajaxObject->setData($bitrix->summ);
    } while(false);
?>