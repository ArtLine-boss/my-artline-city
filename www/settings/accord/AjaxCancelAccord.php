<?php
    $ajaxObject = new core_Dto();
    do {
        $id = isset($_POST['id']) && intval($_POST['id']) > 0 ? intval($_POST['id']) : 0;
        if($id === 0) {
            $ajaxObject->setMsg('Не передан обязательный параметр');
            break;
        }

        $accord = new classes_accordUsers();
        if(null !== ($msg = $accord->loadById($id))) {
            $ajaxObject->setMsg('Ошибка аннулирования соответствия. ' . $msg);
            break;
        }
        $accord->status = classes_accordUsers::ACCORD_STATUS_NO;
        $accord->date_cancel = API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT);
        if(null !== ($msg = $accord->store())) {
            $ajaxObject->setMsg('Ошибка сохранения события аннулирования соответствия. ' . $msg);
            break;
        }

    } while(false);
?>