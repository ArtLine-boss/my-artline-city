<?php
    $ajaxObject = new core_Dto();
    do {
        $id = isset($_POST['id']) && intval($_POST['id']) > 0 ? intval($_POST['id']) : 0;
        $accordType = intval($_POST['accordType']);
        if($id === 0) {
            $ajaxObject->setMsg('Не передан обязательный параметр');
            break;
        }
        $user1 = new classes_users();
        if(null !== ($msg = $user1->loadById($id))) {
            $ajaxObject->setMsg($msg);
            break;
        }
        $ajaxObject->setData(classes_accordUsers::getUsers2ByUser1($user1, $accordType));
    } while(false);
?>