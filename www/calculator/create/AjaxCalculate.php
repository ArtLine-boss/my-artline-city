<?php
    $ajaxObject = new core_Dto();
    $form = isset($_POST['val']) ? $_POST['val'] : null;

    do {
        if(!$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC, true)) {
            $msg = 'Отсутствуют права на запрос к данным';
            $ajaxObject->setMsg($msg);
            break;
        }
        API::dSerializationForm($form);
        if(empty($form) || empty($form->kol)) {
            $ajaxObject->setMsg('Пустые входные данные');
            break;
        }
        $kol = intval($form->kol);
        if(empty($kol) || $kol <= 0) {
            $ajaxObject->setMsg('Нет ни одной расчетной части');
            break;
        }

		$list = array();
        //рассчитываем части
        for($i = 1; $i <= $kol; $i++) {
			$result = new classes_VariableCalc($form, $i);
			$result->calcElement();
			$list[] = $result;
        }
        $variableAll = new classes_VariableAllCalc($form);
        $variableAll->calc($list);

        $answer = array(
            'data' => $list,
            'result' => $variableAll
        );

        //сохраняем расчет
        $bitrix = new classes_Bitrix24TemplateCalculation();
        global $AppUI;
        $bitrixArray = array(
            'user_login' => $AppUI->login,
            'date_add' => API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT),
            'name' => empty($form->p_names) ? 'Расчет_' . API::CurrentDate(CONSTANTS::REPORT_DATETIME_FORMAT) : $form->p_names,
            'client_id_db' => (empty($form->id_client) || $form->id_client < 0) ? null : $form->id_client,
            'data' => json_encode($form),
            'data_calc' => json_encode($answer),
            'total' => $variableAll->total,
            'factor' => $variableAll->factor,
            'summ'=> $variableAll->all_summ_order_byn_calc,
            'summ_one' => $variableAll->summ_one_product
        );

        if(null === ($msg = $bitrix->bind($bitrixArray))) {
            $calcID = (property_exists($form, 'calcID') && !empty($form->calcID)) ? floatval($form->calcID) : null;
            $bitrix->setId($calcID);
            if(null === ($msg = $bitrix->store())) {
                $answer['calcID'] = $bitrix->getId();
            } else {
                $ajaxObject->setMsg($msg);
            }
        } else {
            $ajaxObject->setMsg($msg);
        }

		$ajaxObject->setData($answer);

    } while(false);
?>