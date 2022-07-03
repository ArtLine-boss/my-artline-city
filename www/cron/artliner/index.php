<?php
/*
 * Путь к скрипту - http://artline.city/www/index.php?m=cron&u=artliner&scr=1
 */
//логирование
$log = new core_log('cron/artliner/artliner.log');
//каталог заказов
$folderName = "\\\\DISKSTATION1515\\Artliner\\storage\\finished";
//$folderName = "D:\\ARTLINER"; //тестовый адрес
//искомый файл
$fileName = "info.json";

$count_upload_files = 0;

do {
//получаем имя файла
    $filenames = API::search_file($folderName, $fileName);
    if (empty($filenames) || !is_array($filenames)) {
        $log->store('Файлы не найдены');
        break;
    }

    foreach ($filenames as $filename) {
        //объект для расчета с начальными дынными
        $calc = array(
            'kol' => 2, //количество частей
            'p_eq_1' => 54,  //оборудование для печати обложки
            'p_eq_2' => 54,  //оборудование для печати блока
            'p_mat_1' => 6,  //материал обложки
            'p_mat_2' => 13,  //материал блока
            'p_color_1' => 101,  //красочность обложки
            'p_color_2' => 97,  //красочность блока
            'p_per_i' => 8,  //переплет в твердой обложке (pur)
            'p_stor_i' => 1,  //сторона переплета (по умолчанию узкая)
            'p_cut_1' => true,  //резка обложки
            'p_cut_2' => true,  //резка для блока
            'p_per_mat_i' => 430,  //материал переплета
            'pers1' => true,  //перерасчет на термик обложки
            'pers2' => true,  //перерасчет на термик блока
            'p_namepart_1' => "Обложка",  //наименование части обложка
            'p_namepart_2' => "Блок",  //наименование части блока
            'p_sizep_1' => 12, //размер печатного листа
            'p_sizep_2' => 12, //размер печатного листа
        );
        //открываем файл
        $file = fopen($filename['file'], "r");
        if(!$file) {
            $log->store('Не удалось открыть файл ' . $filename['file']);
            continue;
        }
        $contents = fread($file, filesize($filename['file']));
        fclose ($file);
        $contents = json_decode($contents);
        //если нет облоги и блока, то пропускаем
        if(empty($contents->params[0]->block_workspace) || empty($contents->params[0]->cover_workspace))
            continue;
        $bitrix24 = new classes_Bitrix24TemplateCalculation();
        if(null !== ($msg = $bitrix24->loadByUnique('artliner_id', $contents->order->number, true))) {
            $log->store('Файл ' . $filename['file'] . ': ' . $msg);
            continue;
        }
        if($bitrix24->getInit()) {
            $log->store('Файл ' . $filename['file'] . ': ' . 'Запись ' . $contents->order->number . ' уже существует');
			rename($filename['file'], $filename['folder']."/out_info.json");
            continue;
        }

        //пишем текущую дату
        $bitrix24->date_add = API::CurrentDate(CONSTANTS::DB_DATETIME_FORMAT);
        //Пользователь системы
        $bitrix24->user_login = factorys_classes::getObject('classes_settings')->_oid(CONSTANTS::SETTINGS22)->VAL; //АНЯ
        //Пишем что полный перенос в систему
        $bitrix24->in_work = 1;

        //адрес клиента строкой
        $addr = "";
        if(!empty($contents->order->shipping_address->country->name) && $contents->order->shipping_address->country->name != "Belarus") {
            $addr .= $contents->order->shipping_address->country->name . ", ";
        }
        if(!empty($contents->order->shipping_address->postcode)) {
            $addr .= $contents->order->shipping_address->postcode . " ";
        }
        if(!empty($contents->order->shipping_address->city)) {
            $addr .= $contents->order->shipping_address->city . ", ";
        }
        if(!empty($contents->order->shipping_address->address1)) {
            $addr .= $contents->order->shipping_address->address1 . ", ";
        }
        if(!empty($contents->order->shipping_address->address2)) {
            $addr .= $contents->order->shipping_address->address2;
        }
        $addr = trim($addr);
        $addr = trim($addr, ",");

        //обрабатываем клиента
        $client = new classes_clients();
        if(null !== ($msg = $client->loadByUnique('email', $contents->order->extra_data->customer_data->email, true))) {
            $log->store('Файл ' . $filename['file'] . ': ' . $msg);
            continue;
        }
        if(!$client->getInit()) {
            $client->CLIENT_NAME = trim($contents->order->extra_data->customer_data->firstname . " " . $contents->order->extra_data->customer_data->lastname);
            $client->EMAIL = $contents->order->extra_data->customer_data->email;
            $client->ADDRESS_DEV = $addr;
            $client->ADDRESS_POST= $addr;
            $client->PHONE_MOB = $contents->order->shipping_address->phone;
            $client->CLIENT_STATUS = 'f';

            if(null !== ($msg = $client->store())) {
                $log->store('Файл ' . $filename['file'] . ': ' . $msg);
                continue;
            }
        }
        $bitrix24->client_id_db = $client->getId();

        //имя альбома
        $name_calc = $contents->order->number;
        if(strrpos($name_calc, "artlinecity_") >= 0) {
            $name_calc = substr($name_calc, strrpos($name_calc, "artlinecity_") + 12);
        }
        $calc['id_order'] = $name_calc;
        $bitrix24->name = "Альбом артлайнер " . $name_calc;
        $bitrix24->artliner_id = $contents->order->number;
        $calc['p_names'] = $bitrix24->name;
        //количество изделий
        $calc['p_cir'] = $contents->order->quantity;
        $bitrix24->total = $contents->order->quantity;
        $bitrix24->factor = 1;
        //сумма за заказ
        $calc['p_sum_all_hand'] = $contents->order->extra_data->total;
        //количество страниц
        $page_count = $contents->params[0]->page_count;
        $calc['p_kolstr_2'] = $page_count;
        //размер изделия
        $w = $contents->params[0]->block_workspace->pages[0]->width;
        $h = $contents->params[0]->block_workspace->pages[0]->height;
        if($w >= $h) {
            $calc['p_stor_i'] = 1;
        }
        else {
            $calc['p_stor_i'] = 2;
        }
        $p_size = $w."*".$h;
        $calc['p_size'] = $p_size;
        //размер блока
        $top = $contents->params[0]->block_workspace->pages[0]->edge_width->top;
        $bottom = $contents->params[0]->block_workspace->pages[0]->edge_width->bottom;
        $left = $contents->params[0]->block_workspace->pages[0]->edge_width->left;
        $right = $contents->params[0]->block_workspace->pages[0]->edge_width->right;
        $w = $w + $left + $right;
        $h = $h + $top + $bottom;
        $calc['p_size_2'] = $w."*".$h;
        //размер обложки
        $w = $contents->params[0]->cover_workspace->pages[0]->width * 2;
        $h = $contents->params[0]->cover_workspace->pages[0]->height;
        $spine_width = $contents->params[0]->spine_width;
        $top = $contents->params[0]->cover_workspace->pages[0]->edge_width->top;
        $bottom = $contents->params[0]->cover_workspace->pages[0]->edge_width->bottom;
        $left = $contents->params[0]->cover_workspace->pages[0]->edge_width->left;
        $right = $contents->params[0]->cover_workspace->pages[0]->edge_width->right;
        $w = round($w + $left + $right + $spine_width);
        $h = $h + $top + $bottom;
        $calc['p_size_1'] = $w."*".$h;

        //ОБЪЕКТ ИНТЕРНЕТ-МАГАЗИНА
        $presta = new classes_PrestaShopWebservice2();
        $presta_arr = array(
            'order_id' => $calc['id_order'],
            'reference' => $contents->order->extra_data->reference,
        );
        if(null !== ($msg = $presta->bind($presta_arr))) {
            $log->store('Файл ' . $filename['file'] . ': ' . $msg);
            continue;
        }

        //получаем сообщения пользователя
        $COMMENT = $presta->message;
        //пишем имя и адрес в комменты
        $COMMENT .= "\r\nЗаказчик: " . $contents->order->shipping_address->last_name . " " . $contents->order->shipping_address->first_name . " \r\nАдрес: " . $addr;
        $calc['list_comm'] = $COMMENT;

        //Переносим файлы
        $files = $contents->render->files;
        foreach ($files as $file) {
            $filename_n = $calc['id_order'] . "_" . $contents->number;
            if(strpos($file->filename, "_cover") > 0) {
                $filename_n .= "_cover";
            }
            else if(strpos($file->filename, "_pages") > 0) {
                $filename_n .= "_pages";
            }

            $index = 1;
            while(file_exists("cron/artliner/files/" . $bitrix24->artliner_id . "/" . $filename_n . ".pdf")) {
                $filename_n .= "_".$index;
                $index++;
            }

            if( ! is_dir( "cron/artliner/files" ) ) mkdir( "cron/artliner/files", 0777 );
            if( ! is_dir( "cron/artliner/files/" . $bitrix24->artliner_id ) ) mkdir( "cron/artliner/files/" . $bitrix24->artliner_id, 0777 );
            if(!copy($filename['folder'] . "/" . $file->filename, "cron/artliner/files/" . $bitrix24->artliner_id . "/" . $filename_n . ".pdf")) {
                $log->store('Файл ' . $filename['file'] . ': ' . 'Ошибка копирования файлов');
                continue;
            }
        }

        //пишем данные для расчета
        $bitrix24->data = json_encode($calc, JSON_UNESCAPED_UNICODE);
        $bitrix24->file_path = "cron/artliner/files/" . $bitrix24->artliner_id;
        if(null !== ($msg = $bitrix24->store())) {
            $log->store('Файл ' . $filename['file'] . ': ' . $msg);
            continue;
        }

        //Пишем в статистику
        $report = new classes_ArtlinerReport();
        $report->id_calc = $bitrix24->getId();
        $report->id_order_artliner = $calc['id_order'];
        $report->date_artliner = API::FormatDate($contents->order->create_time, CONSTANTS::DB_DATE_FORMAT);
        $report->id_client = $client->getId();
        $report->product_name = 'Альбом';
        $report->product_count = $calc['p_cir'];
        $report->product_size = $calc['p_size'];
        $report->product_pages = $calc['p_kolstr_2'];
        $report->product_laminat = 'Мат';
        $report->product_summa = $calc['p_sum_all_hand'];
        $report->payment_type = $presta->payment_method;
        $report->carriers_type = $presta->carriers;
        $report->post_index = $contents->order->shipping_address->postcode;
        $report->post_address = $addr;
        $report->post_username = $contents->order->shipping_address->last_name . " " . $contents->order->shipping_address->first_name;
        $report->post_email = empty($contents->order->shipping_address->email) ? $client->EMAIL : $contents->order->shipping_address->email;
        $report->post_phone = empty($contents->order->shipping_address->phone) ? $client->PHONE_MOB : $contents->order->shipping_address->phone;
        if(null !== ($msg = $report->store())) {
            $log->store('Файл ' . $filename['file'] . ', ОТЧЕТ: ' . $msg);
            continue;
        }

        $count_upload_files++;
        //переименовываем файл
        rename($filename['file'], $filename['folder']."/out_info.json");
    }

} while(false);

$log->store('Обработано всего файлов - ' . $count_upload_files);

?>