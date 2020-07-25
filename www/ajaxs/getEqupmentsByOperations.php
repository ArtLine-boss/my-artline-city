<?php
do {
    if(!isset($_POST['operations'])) {
        $ajaxObject->setMsg('Не найдены входные даннае');
        break;
    }
    /** Данные запроса. По итогу должен быть массив ИД */
    $data = array();
    if(API::isJSON($_POST['operations'])) {
        $data = json_decode($_POST['operations']);
        if(empty($data) || !is_array($data) || count($data) == 0 || is_object($data[0])) {
            $ajaxObject->setMsg('Неверные входные даннае');
            break;
        }
    } elseif(strpos(",", $_POST['operations']) >= 0) {
        $data = explode(',', $_POST['operations']);
    } else {
        $data[] = intval($_POST['operations']);
    }
    if(empty($data) || empty($data[0])) {
        $ajaxObject->setMsg('Неверные входные даннае');
        break;
    }

    /** Ищем оборудование по операциям */
    $ajaxObject->setData(classes_equipment::getEqupmentsByOperations($data));

} while(false);
?>