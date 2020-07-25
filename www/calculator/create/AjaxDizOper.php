<?php
    $ajaxObject = new core_Dto();
    $type = isset($_POST['val']) ? $_POST['val'] : 0;

    do {
        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }

        $setting = new classes_settings();
        if (null !== ($msg = $setting->loadById(3))) {
            $ajaxObject->setMsg($msg);
            break;
        }
        $cost = floatval(str_replace(',', '.', $setting->VAL));

        $dizOper = new classes_DizOper();
        $where = array(
            'sql' => 'DEFAULT_=:default',
            'param' => array('default' => $type)
        );

        $list = $dizOper->loadAll($where, 'NAME');
        foreach ($list as $key => $value) {
            $list[$key]->COST = ceil($cost * floatval($value->TIME_) * 100 / 60) / 100;
        }

        $ajaxObject->setData($list);

    } while(false);
?>
