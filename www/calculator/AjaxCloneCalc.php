<?php
    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CAL_ALL, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $user = new classes_users();
        if(null !== ($msg = $user->loadByUnique('USER_LOGIN', $AppUI->login))) {
            $ajaxObject->setMsg($msg);
            break;
        }

        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $bitrix = new classes_Bitrix24TemplateCalculation();
        if(null !== ($msg = $bitrix->loadById($id))) {
            $ajaxObject->setMsg($msg);
            break;
        }

        if($bitrix->user_login != $user->USER_LOGIN) {
            $bitrix->resetId();
            $bitrix->user_login = $user->USER_LOGIN;
            $bitrix->file_path = '';
            if(null !== ($msg = $bitrix->store())) {
                $ajaxObject->setMsg($msg);
                break;
            }

            $data = json_decode($bitrix->data, true);
            $data['calcID'] = $bitrix->getId();
            $bitrix->data = json_encode($data);

            if(null !== ($msg = $bitrix->store())) {
                $ajaxObject->setMsg($msg);
                break;
            }
        }

    } while(false);
?>