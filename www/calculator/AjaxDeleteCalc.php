<?php
    do {
        $ajaxObject = new core_Dto();

        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $bitrix = new classes_Bitrix24TemplateCalculation();
        if(null !== ($msg = $bitrix->loadById($id))) {
            $ajaxObject->setMsg($msg);
            break;
        }
        //удаляем каталог если есть
        if(!empty($bitrix->file_path))
            API::removeDirectory("../" . $bitrix->file_path);
        if(null !== ($msg = $bitrix->delete())) {
            $ajaxObject->setMsg($msg);
            break;
        }

    } while(false);
?>