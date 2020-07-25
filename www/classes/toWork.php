<?php
class classes_toWork {
    var $id_calc = null;
    var $p_dates_time = null;
    var $codeStat = null;
    var $p_name = null;
    var $order_id = null;
    var $FILES = null;
    var $login = null;
    var $id_client = null;
    var $firmParent = null;
    var $flagNotWork = false;

    public function __construct($user_login = null, $param = null, $fls = null)
    {
        if(empty($user_login) || empty($param))
            return;
        if(!empty($fls))
            $this->FILES = $fls;
        $this->login = $user_login;
        $this->id_calc = (property_exists($param, 'id_calc') && !empty($param->id_calc)) ? $param->id_calc : null;
        $this->p_dates_time = (property_exists($param, 'p_dates_time') && !empty($param->p_dates_time)) ? API::FormatDate($param->p_dates_time, CONSTANTS::DB_DATETIME_FORMAT) : null;
        $this->codeStat = (property_exists($param, 'codeStat') && !empty($param->codeStat)) ? $param->codeStat : null;
        $cStat = new classes_DirectoryCodeStat();
        $cStat->loadById($this->codeStat);
        $this->p_name = (property_exists($param, 'p_name') && !empty($param->p_name)) ? $cStat->name . ' ' . $param->p_name : $cStat->name;
        $this->order_id = (property_exists($param, 'order_id') && !empty($param->order_id)) ? $param->order_id : null;
        $this->id_client = (property_exists($param, 'id_client') && !empty($param->id_client)) ? $param->id_client : null;
        $this->firmParent = (property_exists($param, 'firmParent') && !empty($param->firmParent)) ? $param->firmParent : null;
        $this->flagNotWork = (property_exists($param, 'flagNotWork') && !empty($param->flagNotWork)) ? true : false;
    }

    public function toWork() {
        $msg = null;

        do {
            if(empty($this->login)) {
                $msg = 'Ошибка безопасности: не найден пользователь системы';
                break;
            }
            $order = new classes_orders();
            if(empty($this->order_id)) {
                if(empty($this->id_client)) {
                    $msg = 'Не задан клиент';
                    break;
                }
                $client = new classes_clients();
                if(null !== ($msg = $client->loadById($this->id_client))) {
                    break;
                }
                $order->USER_ID = $this->login;
                $order->DATE_OR = API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT);
                $order->STATUS_ID = 1;
                $order->CLIENT_ID = $client->getId();
                $order->CUR_ID = '933';
                $order->parent_company = $this->firmParent;
                if(null !== ($msg = $order->store())) {
                    break;
                }
                $this->order_id = $order->getId();
            } else {
                if (null !== ($msg = $order->loadById($this->order_id))) {
                    break;
                }
                $this->id_client = $order->CLIENT_ID;
            }

            if(null !== ($msg = $this->CalcToOrder())) {
                break;
            }

        } while(false);

        return $msg;
    }

    //перегон на старые продукты
    protected function CalcToOrder() {
        $msg = null;

        do {
            $bitrix = new classes_Bitrix24TemplateCalculation();
            if(null !== ($bitrix->loadById($this->id_calc))) {
                break;
            }
            //пересчитываем для клиента
            if(null !== ($msg = $bitrix->reCalc($this->id_client))) {
                break;
            }

            $product = new classes_orderProduct();
            $form = json_decode($bitrix->data, true);
            $form_calc = json_decode($bitrix->data_calc);
            $kol = $form['kol'];
            if(empty($kol)) {
                $msg = 'Не верно сохранен расчет: пустое количество частей';
                break;
            }

            //курс доллара
            $settings = new classes_settings();
            $dollar = 0;
            if(null === ($msg = $settings->loadById(2)))
                $dollar = empty($settings->VAL) ? 0 :  floatval(str_replace(',', '.', $settings->VAL));

            //проходим по частям и формируем строку
            $temp_pr = "";
            $template = "";
            for($i = 1; $i <= $kol; $i++) {
                //данные формы
                $temp_pr .= $form['p_namepart_'.$i]."|"
                    .$form['p_size_'.$i]."|"
                    .$form['p_kolstr_'.$i]."|"
                    .$form['p_eq_'.$i]."|"
                    .$form['p_color_'.$i]."|"
                    .$form['p_sizep_'.$i]."|"
                    .$form['p_mat_'.$i]."|"
                    .((!empty($form['p_cut_'.$i]) && $form['p_cut_'.$i] == 'true') ? 1 : 0)."|"
                    .$form['p_lam_'.$i]."|"
                    .$form['p_bug_'.$i]."|"
                    .$form['p_perf_'.$i]."|"
                    .$form['p_ygl_'.$i]."|"
                    .$form['p_otv_'.$i]."|"
                    .$form['p_diam_'.$i]."|"
                    .$form['p_luv_'.$i]."|"
                    .$form['p_colorluv_'.$i]."|"
                    .$form['p_vir_'.$i]."|"
                    .$form['p_con_'.$i]."|"
                    .$form['p_tis_'.$i]."|"
                    .$form['p_off_'.$i]."|"
                    .$form['p_prstamp_'.$i]."|"
                    .$form['p_prkl_'.$i]."|"
                    .$form['p_prckl_'.$i]."|"
                    .(!empty($form['ViewDesignCheck']) && $i == 1 ? $form['p_prdiz_'] * $dollar : '')."|"
                    .((!empty($form['p_press_']) && !empty($form['ViewPressCheck']) && $i == 1) ? ceil($dollar * floatval($form['p_press_']) * 100) / 100 : '')."|"
                    .$form['vin'.$i]."|"
                    .((!empty($form['vin1'.$i]) && $form['vin1'.$i] == 'true') ? 1 : 0)."|"
                    .((!empty($form['max'.$i]) && $form['max'.$i] == 'true') ? 1 : 0)."|"
                    .((!empty($form['pol'.$i]) && $form['pol'.$i] == 'true') ? 1 : 0)."|"
                    .((!empty($form['pers'.$i]) && $form['pers'.$i] == 'true') ? 1 : 0)."|"
                    .((!empty($form['mat_firm'.$i]) && $form['mat_firm'.$i] == 'true') ? 1 : 0)."|"
                    .$form['p_size_r_'.$i]."|"
                    .((!empty($form['p_cut2_'.$i]) && $form['p_cut2_'.$i] == 'true') ? 1 : 0)."|"
                    .$form['p_size_cut_'.$i]."|"
                    .$form['p_new_size_'.$i]."|"
                    .(!empty($form_calc->data[$i-1]->combi_f) || !empty($form_calc->data[$i-1]->combi_b) ? implode('~', $form_calc->data[$i-1]->combi_f) . "|" . implode('~', $form_calc->data[$i-1]->combi_b) : "|") . "|"
                    .$form['p_size_cut_op_'.$i]."|"
                    .$form['p_size_cut_eq_'.$i]."|"
                    .$form['p_size_cut2_'.$i]."^";

                //данные для формы заявки на производство
                $mat = new classes_klMat();
                $name_mat = '';
                if(null === ($msg = $mat->loadById($form['p_mat_'.$i]))) {
                    $name_mat = $mat->fun_names();
                }
                $template .= $form['p_namepart_'.$i]."|"
                    .$form['p_size_'.$i]."|"
                    .$form['p_kolstr_'.$i]."|"
                    .$this->returnValSQL("classes_equipment","EQ_NAME",$form['p_eq_'.$i])."|"
                    .$this->returnValSQL("classes_operations","PAR",$form['p_color_'.$i])."|"
                    .$form['p_sizep_'.$i]."|"
                    .$name_mat." : ".$form_calc->data[$i-1]->ResultCalc->count_list_pages."|"
                    .((!empty($form['p_cut_'.$i]) && $form['p_cut_'.$i] == 'true') ? 1 : 0)."|"
                    .$this->returnValSQL('classes_directorylaminat', 'name', $form['p_lam_'.$i], 'value')."|"
                    .$form['p_bug_'.$i]."|"
                    .$form['p_perf_'.$i]."|"
                    .$form['p_ygl_'.$i]."|"
                    .$form['p_otv_'.$i]."|"
                    .$this->returnValSQL('', 'name', $form['p_diam_'.$i], 'value')."|"
                    .$form['p_luv_'.$i]."|"
                    .$this->returnValSQL('classes_directorydiam', 'name', $form['p_colorluv_'.$i], 'value')."|"
                    .$form['p_vir_'.$i]."|"
                    .$form['p_con_'.$i]."|"
                    .$form['p_tis_'.$i]."|"
                    .$form['vin'.$i]."|"
                    .((!empty($form['vin1'.$i]) && $form['vin1'.$i] == 'true') ? 1 : 0)."|"
                    .((!empty($form['max'.$i]) && $form['max'.$i] == 'true') ? 1 : 0)."|"
                    .((!empty($form['pol'.$i]) && $form['pol'.$i] == 'true') ? 1 : 0)."|"
                    .((!empty($form['pers'.$i]) && $form['pers'.$i] == 'true') ? 1 : 0)."|"
                    .((!empty($form['mat_firm'.$i]) && $form['mat_firm'.$i] == 'true') ? 1 : 0)."|"
                    .$form['p_size_r_'.$i]."|"
                    .((!empty($form['p_cut2_'.$i]) && $form['p_cut2_'.$i] == 'true') ? 1 : 0)."|"
                    .$form['p_size_cut_'.$i]."|"
                    .$form['p_new_size_'.$i]."^";
            }
            $template = trim($template,"^");
            $temp_pr = trim($temp_pr,"^");

            //присваиваем в продукт данные
            $product->TEMPLATE = $template;
            $product->TEMP_PR = $temp_pr;
            $product->ORDER_ID = $this->order_id;
            $product->PRODUCT_ID = 0;
            $product->TOTAL = $form['p_cir'];
            $product->units = $form['unit_prod1'];
            $product->SUMM = !empty($form_calc->result->all_summ_order_calc_hand) ? $form_calc->result->all_summ_order_calc_hand : $bitrix->summ;
            $product->PRICE = floatval($product->SUMM) / floatval($product->TOTAL);
            $product->SIZE = $form['p_size'];
            $product->p_names = $this->p_name;
            $product->sum_press = (!empty($form['p_press_']) && !empty($form['ViewPressCheck'])) ? ceil($dollar * floatval($form['p_press_']) * 100) / 100 : 0;
            $product->view_press = (!empty($product->sum_press)) ? 1 : 0;
            $product->fast = $form['p_fast'];
            $product->dates_rdy = $this->p_dates_time;
            $product->add_date = API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT);
            $product->code_stat = $this->codeStat;
            $product->price_sys = $form_calc->result->all_summ_order_byn;
            $product->view_diz = $form_calc->result->design_id;

            if(null !== ($msg = $product->getLastNumProdOrd()))
                break;

            $path = "files/prod/" . $product->ORDER_ID . "/" . $product->num_prod_ord . "/";
            $product->press_diz = $path . "press";
            if(null !== ($msg = API::createPath($product->press_diz)))
                break;
            $product->print_diz = $path . "diz";
            if(null !== ($msg = API::createPath($product->print_diz)))
                break;
            $product->cl_file = $path . "client";
            if(null !== ($msg = API::createPath($product->cl_file)))
                break;

            if(!empty($form['ViewDesignCheck'])) {
                if(!$this->flagNotWork) {
                    $product->flags = 1;
                    $product->status = CONSTANTS::STATUS10;
                }
            } else if(!empty($form['ViewPressCheck'])) {
                if(!$this->flagNotWork) {
                    $product->flags = 2;
                    $product->status = CONSTANTS::STATUS11;
                }
                if(null !== ($msg = $this->uploadFiles($product->print_diz)))
                    break;
                if(null !== ($msg = $this->copyFiles((!empty($bitrix->file_path) ? "../" . $bitrix->file_path : null), $product->print_diz)))
                    break;
            } else if(!empty($this->FILES)) {
                if(!$this->flagNotWork) {
                    $product->flags = 3;
                    $product->status = CONSTANTS::STATUS12;
                }
                if(null !== ($msg = $this->uploadFiles($product->press_diz)))
                    break;
                if(null !== ($msg = $this->copyFiles((!empty($bitrix->file_path) ? "../" . $bitrix->file_path : null), $product->press_diz)))
                    break;
            } else {
                if(!$this->flagNotWork) {
                    $product->flags = 4;
                    $product->status = CONSTANTS::STATUS1;
                }
                if(null !== ($msg = $this->uploadFiles($product->cl_file)))
                    break;
                if(null !== ($msg = $this->copyFiles((!empty($bitrix->file_path) ? "../" . $bitrix->file_path : null), $product->cl_file)))
                    break;
            }

            $product->cshivka = (!empty($form['p_per']) ? $form['p_per'] : 0) . '|' . $this->getPStor($form['p_stor']) . '|' . (!empty($form['p_per_mat']) ? $form['p_per_mat'] : '');
            $product->comment = $form['list_comm'];

            /** ПИШЕМ ПОЗИЦИЮ В ЗАКАЗЕ ПО НОВОМУ */
            $item = new classes_orderItem();
            if(null !== ($msg = $item->convertCalcToItem($bitrix, $this->order_id)))
                break;
            $item->code_stat = $this->codeStat;
            $item->dates_rdy = $this->p_dates_time;
            $item->add_date = API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT);
            $item->status_item = ($item->status_item == CONSTANTS::STATUS1 && !empty($this->FILES)) ? CONSTANTS::STATUS12 : $item->status_item;
            $item->name_item = $this->p_name;
            if(null !== ($msg = $item->store()))
                break;

            /*************************************/

            if(null !== ($msg = $product->store()))
                break;

            /** Пишем в лог задачи */
            if(!$this->flagNotWork) {
                $logTask = new classes_logTask();
                $logTask->id_prod = $product->getId();
                $logTask->user_log = $this->login;
                $logTask->datetime = API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT);
                $logTask->status_new = $product->status;
                if (null !== ($msg = $logTask->store()))
                    break;
            }

            //Если переносим, то удаляем запись. Также пишем в отчет, если есть запись
            if($bitrix->in_work == 1) {
                $report = new classes_ArtlinerReport();
                if(null !== ($msg = $report->loadByUnique('id_calc', $bitrix->getId(), true))) {
                    break;
                }
                if($report->getInit()) {
                    $report->id_order = $this->order_id;
                    if(null !== ($msg = $report->store()))
                        break;
                }
                //удаляем каталог если есть
                API::removeDirectory("../" . $bitrix->file_path);
                if(null !== ($msg = $bitrix->delete()))
                    break;
            }

        } while(false);

        return $msg;
    }

    protected function returnValSQL($class, $field, $id, $unique = '') {
        $dt = '';

        do {
            if(empty($class)) {
                break;
            }
            $cl = new $class;
            if(empty($unique)) {
                if (null !== ($msg = $cl->LoadById($id))) {
                    break;
                }
            } else {
                if (null !== ($msg = $cl->loadByUnique($unique, $id))) {
                    break;
                }
            }

            $dt = (property_exists($cl, $field) && !empty($cl->$field)) ? $cl->$field : '';
        } while(false);

        return $dt;
    }

    private function getPStor($pstor = null) {
        $value = '';

        do {
            if(empty($pstor))
                break;

            switch ($pstor) {
                case 1:
                    $value = 'узкой';
                    break;
                case 2:
                    $value = 'широкой';
                    break;
            }

        } while(false);

        return $value;
    }

    private function uploadFiles($path = null) {
        $msg = null;

        do {
            if(empty($this->FILES))
                break;
            if(null !== ($msg = API::createPath($path)))
                break;

            // Создадим папку если её нет
            $path = $_SERVER['DOCUMENT_ROOT'] . "/pages/pg/" . $path;

            foreach ($this->FILES as $file) {
                $filename = API::rus2translit($file['name']);
                if( !move_uploaded_file( $file['tmp_name'], $path . '/' . $filename ) ){
                    $msg = 'Файл ' . $file['name'] . ' не может быть перемещён по каким-либо причинам';
                    break;
                }
            }

        } while(false);

        return $msg;
    }

    private function copyFiles($out_path = null, $in_path = null) {
        $msg = null;

        do {
            if(empty($out_path))
                break;
            if(!is_dir($out_path)) {
                $msg = 'Не найден каталог ' . $out_path;
                break;
            }
            if(null !== ($msg = API::createPath($in_path)))
                break;
            $in_path = $_SERVER['DOCUMENT_ROOT'] . "/pages/pg/" . $in_path;

            $files = glob($out_path . "/*");
            foreach ($files as $file) {
                $filename = basename($file);
                copy($file, $in_path . "/" . $filename);
            }

        } while(false);

        return $msg;
    }
}
?>
