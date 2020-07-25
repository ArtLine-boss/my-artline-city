<?php
//проверка доступа
$AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC);

$id = (isset($_GET['id']) && !empty($_GET['id'])) ? $_GET['id'] : null;
$bitrix = new classes_Bitrix24TemplateCalculation();
$msg = $bitrix->loadById($id);

// для плоттерной резки формируем список операций
$sql = "SELECT GROUP_CONCAT(operations.ID) IDs, operations.OPERATION_NAME 
        FROM operations WHERE operations.OperationType=" . CONSTANTS::OPERATIONS_TYPE_PLOTTER_CUT . " 
        GROUP BY operations.OPERATION_NAME";
$plottCut = new classes_NotVariable($sql);
$str_plottCut = "<option value=''>Выберите</option>";
if(!empty($plottCut->List)) {
    foreach ($plottCut->List as $k => $v) {
        $str_plottCut .= "<option value='" . $v->IDs . "'>" . $v->OPERATION_NAME . "</option>";
    }
}

?>
<script>
    var str_plottCut = "<?php echo $str_plottCut; ?>";
    //справочник для ламинации
    <?php
        $dLamination = new classes_directorylaminat();
        $dLaminationArray = $dLamination->loadAll();
    ?>
    var directoryLaminat = [];
    <?php
    $data_tmp = '';
    foreach ($dLaminationArray as $k => $v) {
        $data_tmp .= 'directoryLaminat.push(' . json_encode($v) . ');';
    }
    ?>
    var evalTmp = <?php echo $data_tmp; ?>

    //справочник диаметров
    <?php
        $dDiam = new classes_directorydiam();
        $dDiamArray = $dDiam->loadAll();
    ?>
    var directoryDiam = [];
    <?php
        $data_tmp = '';
        foreach ($dDiamArray as $k => $v) {
            $data_tmp .= 'directoryDiam.push(' . json_encode($v) . ');';
        }
    ?>
    var evalTmp = <?php echo $data_tmp; ?>

    //справочник раскраски люверса
    <?php
        $dColorLuv = new classes_directorycolorluv();
        $dColorLuvArray = $dColorLuv->loadAll();
    ?>
    var directoryColorLuv = [];
    <?php
    $data_tmp = '';
    foreach ($dColorLuvArray as $k => $v) {
        $data_tmp .= 'directoryColorLuv.push(' . json_encode($v) . ');';
    }
    ?>
    var evalTmp = <?php echo $data_tmp; ?>
</script>

<form class='form-signin' method='post' action='' enctype='multipart/form-data' id='forms'>
    <div class="row">
        <div class='col-md-2'><label>Клиент: </label></div>
        <div class="col-md-9">
            <div class="c-field has-icon-left" style="display: inline;">
                <span class="c-field__icon">
                    <i class="fa fa-search"></i>
                </span>
                <input class="c-input" type="text" id="name_client" name="name_client" onchange="searchClientInDB()" style="display: inline;width: 95%" placeholder="Введите наименование, УНП или номер телефона">
                <input type="number" id="id_client" name="id_client" hidden value="-1">
            </div>
            <i class="fa fa-search fa-2x" onclick="searchClientInDB()"></i>
        </div>
    </div>
    <table>
        <tbody>
            <tr>
                <td><label>Название расчета: </label></td>
                <td style="width: 40%"><input type='text' id='p_names' size='100%' style='width:100%' name='p_names' value='' onblur="get_price()" tabindex='1'></td>
                <td><label>Переплет</label></td>
                <td style="width: 40%"><select id='p_per_i' name="p_per" style="width:90%;" tabindex='1'>
                        <?php
                            $dp = new classes_directoryper();
                            $list = $dp->loadAll();
                            foreach ($list as $k => $v) {
                                echo '<option style="display:block;" value="' . $v->value . '">' . $v->name . '</option>';
                            }
                        ?>
                    </select></td>
            </tr>
            <tr>
                <td><label>Кол-во. изделий:</label></td>
                <td style="width: 40%"><input type='text' id='p_cir' size='10' name='p_cir' value='' onblur="get_price()" tabindex='1'>
                    <select id='unit_prod1' name='unit_prod1' tabindex='1' onblur="get_price()">
                        <option value=''></option>
                        <?php
                        $units = new classes_units();
                        $list = $units->loadAll();
                        foreach($list as $k => $v) {
                            echo "<option value='" . $v->Name . "'> " . $v->Name . "</option>";
                        }
                        ?>
                    </select></td>
                <td><label>Сторона переплета:</label></td>
                <td style="width: 40%"><select id='p_stor_i' name="p_stor" style="width:90%;" onblur="get_price()" tabindex='1'>
                        <option value="0" style="display:block;"></option>
                        <option value="1" style="display:block;">узкой</option>
                        <option value="2" style="display:block;">широкой</option>
                    </select></td>
            </tr>
            <tr>
                <td><label>Размер готового изделия:</label></td>
                <td style="width: 40%"><input type='text' id='p_size' size='20' name='p_size' value='' onblur="get_price()" tabindex='1'></td>
                <td><label>Материал переплета:</label></td>
                <td style="width: 40%"><select id='p_per_mat_i' name="p_per_mat" style="width:90%;" onblur="get_price()" tabindex='1'>
                        <option style="display:block;" ></option>
                        <?php
                            $sql = "SELECT 
                                    ttr.ID, ttr.title, ttr.m_price, ttr.m_size, '8,9,10,11' idd, ttr.flags , ttr.parent   
                                    FROM
                                    (SELECT 
                                    kl.ID, kl.title, '' m_price, '' m_size, kl.flags , kl.parent  
                                    FROM  
                                    kl_mat kl where kl.id = 428 OR kl.id = 429 OR kl.id = 430
                                    ORDER BY kl.title) ttr
                                    GROUP BY ttr.ID ORDER BY ttr.title";
                            $var = new classes_NotVariable($sql);
                            $flags == "-1";
                            $return = "";
                            foreach($var->List as $key => $value) {
                                $title = array(
                                    'm_price' => $value->m_price,
                                    'm_size' => $value->m_size,
                                );
                                $kl_mat = new classes_klMat();
                                if(null !== ($msg = $kl_mat->loadById($value->ID)))
                                    continue;
                                if($flags == "-1") {
                                    $flags = $kl_mat->fun_group();
                                    $namess = $kl_mat->fun_names();
                                    $return .= "<optgroup label='$flags' name = 'optgr'><option value='".$value->ID."' data-opt_gr = '$flags' data-attr = '".$value->idd."' data-attr-size = '".$value->m_size."' title='".json_encode($title)."' " . ">$namess</option>";
                                }
                                else {
                                    if($flags != $kl_mat->fun_group()) {
                                        $flags = $kl_mat->fun_group();
                                        $namess = $kl_mat->fun_names();
                                        $return .= "</optgroup> <optgroup label='$flags' name = 'optgr'>";
                                        $return .= "<option value='".$value->ID."' data-opt_gr = '$flags' data-attr = '".$value->idd."' data-attr-size = '".$value->m_size."' title='".json_encode($title)."' >$namess</option>";
                                    }
                                    else {
                                        $namess = $kl_mat->fun_names();
                                        $return .= "<option value='".$value->ID."'  data-opt_gr = '$flags' data-attr = '".$value->idd."' data-attr-size = '".$value->m_size."' title='".json_encode($title)."'>$namess</option>";
                                    }
                                }
                            }
                            echo $return;
                        ?>
                    </select></td>
            </tr>
        </tbody>
    </table>
    <div id = 'err_chk'></div>

    <?
        $pr_oper = new classes_prOper();
        $list = $pr_oper->loadAll();
        $iddd = '';
        foreach ($list as $k => $v) {
            if($v->DEFAULT_ == 1) {
                $iddd = $iddd . $v->ID . ",";
            }
        }
        $iddd = substr($iddd, 0, -1);
    ?>
    <br>

    <div class='row'>
        <div class='col-md-3'>
            <div class='checkbox'>
                <label>
                    <input type='checkbox' name='ViewDesignCheck' id='ViewDesignCheck' onchange='maket(this.checked)'>Дизайн
                </label>
            </div>
        </div>
        <div class='col-md-3'>
            <div class='checkbox'>
                <label>
                    <input type='checkbox' name='ViewPressCheck' id='ViewPressCheck' onchange="prepressInit(this.checked)" checked>Препресс
                </label>
            </div>
        </div>
    </div>

    <div class='row'>
        <div class='col-md-3'>
            <div id='op1' style='display:none;'>
                <button type='button' class='c-btn c-btn--secondary c-btn--sm' onclick='addViewDesign()'>Выбор дизайн</button>
            </div>
        </div>
        <div class='col-md-3'>
            <div id='op2' style='display:none;'>
                <!--<button type='button' class='c-btn c-btn--secondary btn--sm' onclick='addViewPrepress()'>Выбор препресс</button>-->
            </div>
        </div>
        <div class='col-md-2'></div>
        <div class='col-md-4'>
            <div id="listss"></div>
        </div>
    </div>

    <div class="row" id="div_p_press_">
        <div class='col-md-3'>
            <label>Стоимость препресса:</label>
        </div>
        <div class="col-md-9">
            <input type='text' tabindex='tabkol' id='p_press_' name="p_press_" style='width:100%;' onblur="get_price()" onclick="get_price()" value="0.46">
        </div>
    </div>
    <div class="row" id="div_p_prdiz_" style="display: none">
        <div class='col-md-3'>
            <label>Стоимость дизайна:</label>
        </div>
        <div class="col-md-9">
            <input type='text' tabindex='tabkol' id='p_prdiz_' name="p_prdiz_" style='width:100%;' onblur="get_price()" onclick="get_price()">
            <input type='text' id='p_prdiz_id' name="p_prdiz_id" hidden value="">
        </div>
    </div>

    <hr>
    <div class='row'>
        <div class='col-md-12'>
            <div class='row'>
                <div class='col-md-3'>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="" name="chkperf"
                                       onchange="checks('check1','')" id="check1_">Перфорация
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="" name="chkygl"
                                       onchange="checks('check2','')" id="check2_">Скругление углов
                            </label>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="" name="chkbug"
                                       onchange="checks('check13','')" id="check13_">Биговка
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="" name="chkotv"
                                       onchange="checks('check4','check3')" id="check4_">Отверстия
                            </label>
                        </div>
                    </div>
                </div>
                <div class='col-md-3'>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="" name="chkluv"
                                       onchange="checks('check5','check6')" id="check5_">Люверс
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="" name="chkvir"
                                       onchange="checks('check7','check10')" id="check7_">Вырубка
                            </label>
                        </div>
                    </div>
                </div>
                <div class='col-md-2'>
                    <div class="form-group">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="" name="chkcon"
                                       onchange="checks('check8','check11')" id="check8_">Конгрев
                            </label>
                        </div>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="chktis" value=""
                                       onchange="checks('check9','check12')" id="check9_">Тиснение
                            </label>
                        </div>
                    </div>
                </div>

                <div class='col-md-1'>
                    <button type="button" class="btn btn-default btn-circle" onclick='rowTable()'
                            id="bnrr"><i class="fa fa-plus"></i></button>
                    <button type="button" class="btn btn-default btn-circle" onclick='delOneRowTable()'
                            id="bnrr"><i class="fa fa-minus"></i></button>
                </div>
            </div>

            <table class='table' id='dynamic'>
                <tr class='odd gradeX' id='tr1'>
                    <td><label>Наим. части:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Размер изделия:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Кол-во стр:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Оборудование:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Цвет:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Бумага:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Размер бумаги:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Резка:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Плот. Резка:</label></td>
                </tr>
                <tr class='odd gradeX'>
                    <td><label>Ламинирование:</label></td>
                </tr>
                <tr class='odd gradeX' hidden id="check13">
                    <td><label>Кол-во биговок:</label></td>
                </tr>
                <div>
                    <tr class='odd gradeX' hidden id="check1">
                        <td><label>Кол-во перф.:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check2">
                        <td><label>Кол-во скр. углов:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check3">
                        <td><label>Кол-во отверстий:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check4">
                        <td><label>Диаметр отверстий:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check5">
                        <td><label>Кол-во люверсов:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check6">
                        <td><label>Цвет, диам. люверса:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check7">
                        <td><label>Вырубка:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check8">
                        <td><label>Конгрев:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check9">
                        <td><label>Тиснение:</label></td>
                    </tr>
                    <tr class='odd gradeX'>
                        <td><label>Работы на стороне:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check10">
                        <td><label>Цена штампа:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check11">
                        <td><label>Цена клише, конгрев:</label></td>
                    </tr>
                    <tr class='odd gradeX' hidden id="check12">
                        <td><label>Цена клише, тиснение:</label></td>
                    </tr>
                    <tr class='odd gradeX'>
                        <td><label>Параметры:</label></td>
                    </tr>
                </div>
            </table>

            <hr>
            <div class='row'>
                <div class='col-md-4'>
                    <div class='row'>
                        <div class='col-md-6'><label>Стоимость, BYN:</label></div>
                        <div class='col-md-6'>
                            <input style="width: 100%;" type='text' id='p_sum_all' readonly>
                            <input type='hidden' id='kol' value="0" name='kol'>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-6'><label>Сумма(без расчета), BYN:</label></div>
                        <div class='col-md-6'>
                            <input style="width: 100%;" type='number' id='p_sum_all_hand' name='p_sum_all_hand' onblur='get_price()'>
                        </div>
                    </div>

                    <div class='row'>
                        <div class='col-md-3'><label>Срочность:</label></div>
                        <div class='col-md-9'>
                            <select style="width: 100%;" id='p_fast' name='p_fast' onblur="get_price()">
                                <option value="1" selected>ОБЫЧНО</option>
                                <option value="1.2">СРОЧНО</option>
                                <option value="1.5">ОЧЕНЬ СРОЧНО</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class='col-md-8'>
                    <label>Комментарий:</label>
                    <textarea rows="5" id='list_comm' name='list_comm' style="width: 100%;"></textarea>
                </div>
            </div>
        </div>
    </div>
    <input type="text" id="calcID" name="calcID" hidden value="<?php echo empty($id) ? '' : $id ?>">
</form>
<div class="row" style="margin-top: 20px;">
    <!-- Кнопка сохранить -->
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <a class="c-btn c-btn--success c-btn--fullwidth" onclick="clickSave()">Сохранить и закрыть</a>
            </div>
        </div>
    </div>
    <!-- Кнопка рассчитать -->
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <a class="c-btn c-btn--warning c-btn--fullwidth" onclick="get_price()">Рассчитать</a>
            </div>
        </div>
    </div>
    <!-- Кнопка просмотра расчета -->
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <a class="c-btn c-btn--fancy c-btn--fullwidth" onclick="viewResultCalc()">Просмотр расчета</a>
            </div>
        </div>
    </div>
    <!-- Кнопка отмена -->
    <div class="col-md-3">
        <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
                <a class="c-btn c-btn--secondary c-btn--fullwidth" href="?m=calculator">Отмена</a>
            </div>
        </div>
    </div>
    <input type="text" id="calc_parts" hidden value='<?php echo empty($bitrix->data) ? '' : $bitrix->data ?>'>
    <input type="text" id="calc_result" hidden>
</div>
