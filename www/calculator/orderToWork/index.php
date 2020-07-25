<?php
    //проверка доступа
    $AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC);

do {
    $dt = (isset($_GET['dt']) && !empty($_GET['dt']) && is_array(json_decode($_GET['dt']))) ? json_decode($_GET['dt']) : array();
    if (count($dt) == 0) {
        echo 'Ошибка: Нет расчетов!!!';
        break;
    }

    //проверяем клиента
    $dbObj = new core_DBObject(null, null);
    $arr_clients = $dbObj->select('SELECT DISTINCT(bitrix24_template_calculation.client_id_db) FROM bitrix24_template_calculation WHERE bitrix24_template_calculation.id IN (' . implode(',', $dt) . ')');
    if(count($arr_clients) == 0) {
        echo 'Неожиданная ошибка. Обратитесь к системному администратору';
        break;
    }
    $client = -1;
    $client_str = '';
    if(count($arr_clients) == 1 && !empty($arr_clients[0]['client_id_db'])) {
        $client_object = new classes_clients();
        if (null !== ($msg = $client_object->loadById($arr_clients[0]['client_id_db']))) {
            echo $msg;
            break;
        }
        $client = $client_object->getId();
        $client_str = $client_object->CLIENT_NAME;
    }

    $firm_parent = new classes_firmParent();
    $firm_parent_array = $firm_parent->loadAll();
    ?>
    <form class='form-signin' method='post' action='' enctype='multipart/form-data' id='forms'>
        <div><h1 align="center">Новый счет</h1></div>

        <table width="100%">
            <tbody>
            <tr>
                <td><label><b>ФИРМА: </b></label></td>
                <td width="100%">
                    <select id="firmParent" name="firmParent" class="js-example-basic-single" placeholder="Выберите фирму">
                        <?php
                        //определяем на какую фирму последний заказ был
                        $order_last = new classes_orders();
                        $sql = "SELECT * FROM orders WHERE orders.USER_ID='" . $AppUI->login . "' ORDER BY orders.NUMBER DESC LIMIT 1";
                        $order_last = $order_last->selectByQuery($sql);
                        $order_last = !empty($order_last) ? $order_last[0] : null;
                        $firma = !empty($order_last) ? $order_last->parent_company : null;

                        foreach ($firm_parent_array as $key => $value) {
                            $selected = ($firma == $value->getId()) ? ' selected' : '';
                            ?>
                            <option value="<?php echo $value->getId(); ?>"<?php echo $selected; ?>><?php echo $value->name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>

        <table width="100%">
            <tbody>
            <tr>
                <td>
                    <label><b>Клиент:&nbsp;</b></label>
                </td>
                <td width="100%">
                    <div class="c-field has-icon-left">
                        <span class="c-field__icon">
                            <i class="fa fa-search"></i>
                        </span>
                        <input class="c-input" type="text" id="name_client" name="name_client"
                               onchange="searchClientInDB()" placeholder="Введите наименование, УНП или номер телефона" value="<?php echo $client_str; ?>">
                        <input type="number" id="id_client" name="id_client" hidden value="<?php echo $client; ?>">
                    </div>
                </td>
                <td>
                    <i class="fa fa-search fa-2x" onclick="searchClientInDB()"></i>
                </td>
            </tr>
            </tbody>
        </table>

        <div class="row">
            <label><b>Добавить в счет:</b></label>&nbsp;<input type="number" id="p_order_id" name="p_order_id">
        </div>
        <div class="row" style="margin-top: 15px; margin-bottom: 15px;">
            <label><b>НЕ ОТПРАВЛЯТЬ В РАБОТУ:</b></label>&nbsp;<input type="checkbox" id="p_order_not_work" name="p_order_not_work" checked>
        </div>
        <hr>
        <hr>
        <?php
        $codeStat = new classes_DirectoryCodeStat();
        $codeStatArray = $codeStat->loadAll();
        //отрисовываем выбранные расчеты
        foreach ($dt as $k => $v) {
            $bitrix = new classes_Bitrix24TemplateCalculation();
            if (null !== ($msg = $bitrix->loadById($v))) {
                echo $msg;
                break;
            }
            ?>
            <div>
                <input type="text" name="p_id<?php echo $bitrix->getId(); ?>" value="<?php echo $bitrix->getId(); ?>"
                       hidden>
                <div class="row">
                    <label><b>Наименование расчета:</b>&nbsp;<?php echo $bitrix->name; ?></label>
                </div>
                <div class="row">
                    <label><b>Дата
                            расчета:</b>&nbsp;<?php echo API::FormatDate($bitrix->date_add, CONSTANTS::REPORT_DATETIME_FORMAT); ?>
                    </label>
                </div>
                <div class="row">
                    <label><b>Дата сдачи:</b></label>&nbsp;<input type="datetime-local"
                                                                  id="p_dates_time<?php echo $bitrix->getId(); ?>"
                                                                  name="p_dates_time<?php echo $bitrix->getId(); ?>">
                </div>
                <div class="row">
                    <label><b>Наименование:</b></label>&nbsp;
                    <select id="codeStat<?php echo $bitrix->getId(); ?>" name="codeStat<?php echo $bitrix->getId(); ?>"
                            class="js-example-basic-single" placeholder="Выберите вид товара">
                        <option value="-1">&nbsp;</option>
                        <?php
                        foreach ($codeStatArray as $key => $value) {
                            ?>
                            <option value="<?php echo $value->getId(); ?>"
                                    title="<?php echo $value->code_stat; ?>"><?php echo $value->name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <label><b>Доп. название:</b></label>&nbsp;<input type="text"
                                                                     id="p_name<?php echo $bitrix->getId(); ?>"
                                                                     name="p_name<?php echo $bitrix->getId(); ?>">
                </div>

                <!-- Пишем файлы если есть -->
                <?php
                    $filepath = $bitrix->file_path;
                    if(!empty($filepath)) {
                        $files = glob($filepath . "/*");
                        $str_files = count($files) > 0 ? "<div style='margin-bottom: 20px;'><h3 style='margin: 0;'>Файлы:</h3>" : "";
                        foreach ($files as $file) {
                            $str_files .= "<p>" . basename($file) . "</p>";
                        }
                        $str_files .= count($files) > 0 ? "</div>" : "";
                        echo $str_files;
                    }
                ?>

                <a class="c-btn c-btn--warning c-btn--fullwidth" onclick="clickFile(<?php echo $bitrix->getId(); ?>)"
                   id="btnfile<?php echo $bitrix->getId(); ?>" style="width: auto">Выберите файлы</a>
                <input type="file" multiple="true" id="file<?php echo $bitrix->getId(); ?>" onchange="uploadFile(this)"
                       hidden>
                <div class="row">
                    <table class="c-table" id="tablefile<?php echo $bitrix->getId(); ?>">
                        <tbody></tbody>
                    </table>
                </div>

                <hr>
            </div>
            <?php
        }
        ?>
    </form>
    <div class="row" style="margin-top: 20px">
        <!-- Кнопка сохранить -->
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <a class="c-btn c-btn--success c-btn--fullwidth" onclick="clickSave()">Сохранить</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
        </div>
        <!-- Кнопка отмена -->
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <a class="c-btn c-btn--secondary c-btn--fullwidth" href="?m=calculator">Отмена</a>
                </div>
            </div>
        </div>
    </div>
    <?php
} while(false);
?>