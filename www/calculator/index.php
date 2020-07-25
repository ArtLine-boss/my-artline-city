<?php
    //проверка доступа
    $AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC);
?>
<div class="row">
    <div class="col col-md-4 u-mb-medium">
        <a class="c-btn c-btn--success c-btn--fullwidth" href="?m=calculator&u=create"><i class="fa fa-plus u-mr-xsmall"></i> Новый расчет</a>
    </div>
</div>
<div class="c-tabs">
    <ul class="c-tabs__list nav nav-tabs" id="menu_calc" role="tablist">
        <li><a class="c-tabs__link active" id="nav-calc-tab" data-toggle="tab" href="#nav-calc" role="tab" aria-controls="nav-calc" aria-selected="true">Расчеты</a></li>
        <?php if($AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC_NOT_USER, true)) { ?>
        <li><a class="c-tabs__link" id="nav-notcalc-tab" data-toggle="tab" href="#nav-notcalc" role="tab" aria-controls="nav-notcalc">Нераспределенные расчеты</a></li>
        <?php } ?>
        <?php if($AppUI->isAccess(ACCESSES::ACCESS_PAGE_CAL_ALL, true)) { ?>
            <li><a class="c-tabs__link" id="nav-allcalc-tab" data-toggle="tab" href="#nav-allcalc" role="tab" aria-controls="nav-allcalc">Все расчеты</a></li>
        <?php } ?>
    </ul>
    <div class="c-tabs__content tab-content" id="nav-tabContent">
        <div class="c-tabs__pane active" id="nav-calc" role="tabpanel" aria-labelledby="nav-calc-tab">
            <div class="row">
                <div class="col col-md-3 u-mb-medium">
                    <a class="c-btn c-btn--info c-btn--fullwidth" onclick="toWork()"><i class="fa fa-cart-plus u-mr-xsmall"></i> Отправить в работу</a>
                </div>
                <div class="col col-md-3 u-mb-medium">
                    <a class="c-btn c-btn--danger c-btn--fullwidth" onclick="deleteAllChecked()"><i class="fa fa-trash u-mr-xsmall"></i> Удалить отмеченные</a>
                </div>
            </div>
            <a onclick="openWindowFilter('Calc', this)"><i class="fa fa-caret-square-o-right"> ФИЛЬТРЫ</i></a>
            <a onclick="resetFilter('Calc')" style="margin-left: 50px;"><i class="fa fa-close"> СБРОСИТЬ ФИЛЬТР</i></a>
            <div class="row" id="windowFilterCalc" style="display: none">
                <div class="col-md-2">
                    <input class="c-input" type="text" id="filter_name_Calc" name="filter_name_Calc" placeholder="Название расчета">
                </div>
                <div class="col-md-3">
                    <input class="c-input" type="text" id="filter_name_client_Calc" name="filter_name_client_Calc" placeholder="Наименование клиента">
                </div>
                <div class="col-md-5" style="padding: .59375rem .9375rem;">
                    <lable>Дата: </lable>
                    с
                    <input type="date" style="width: 150px;" id="start_date_Calc" name="start_date_Calc">
                    по
                    <input type="date" style="width: 150px;" id="end_date_Calc" name="end_date_Calc">
                </div>
                <div class="col col-md-2 u-mb-medium">
                    <a class="c-btn c-btn--secondary c-btn--fullwidth" onclick="FilterData('Calc')"><i class="fa fa-search u-mr-xsmall"></i> Фильтр</a>
                </div>
            </div>
            <script>
                const SETTING_TABLE = {
                    'data_table': {
                        'valuePage': '<?php echo isset($_GET['page']) ? intval($_GET['page']) : '' ?>',
                        'valueLimit': '<?php echo isset($_GET['limit']) ? intval($_GET['limit']) : '' ?>',
                        'namePage': 'page',
                    }
                }
            </script>
            <?php
                $field = array(
                    0 => array(
                        'name' => 'check_add',
                        'type' => 'checkbox',
                        'value' => 'id'
                    ),
                    1 => array('name' => 'Наименование'),
                    2 => array('date_add' => 'Дата'),
                    3 => array('summ_one' => 'Сумма за единицу изделия без НДС, BYN'),
                    4 => array('summ' => 'Сумма расчета, BYN'),
                    5 => array(
                         'name' => 'btn_edit',
                        'type' => 'i',
                        'url' => '/www/index.php?m=calculator&u=create',
                        'class' => 'fa fa-pencil-square-o fa-lg',
                        'value' => 'id'
                    ),
                    6 => array(
                         'name' => 'btn_edit',
                        'type' => 'i',
                        'url' => '/www/index.php?m=calculator&u=filesForCalc',
                        'class' => 'fa fa-files-o fa-lg',
                        'value' => 'id'
                    ),
                    7 => array(
                        'name' => 'btn_delete',
                        'type' => 'i',
                        'func' => 'deleteRowInTable',
                        'url' => 'm=calculator&a=AjaxDeleteCalc',
                        'class' => 'fa fa-trash fa-lg',
                        'value' => 'id'
                    ),
                );
                echo API::viewTable(array(), $field, null, 'limit', 'page');
            ?>
        </div>

        <?php if($AppUI->isAccess(ACCESSES::ACCESS_PAGE_CALC_NOT_USER, true)) { ?>
        <div class="c-tabs__pane" id="nav-notcalc" role="tabpanel" aria-labelledby="nav-notcalc-tab">
            <?php
            $field = array(
                0 => array('name' => 'Наименование'),
                1 => array('date_add' => 'Дата'),
                2 => array('summ_one' => 'Сумма за единицу изделия без НДС, BYN'),
                3 => array('summ' => 'Сумма расчета, BYN'),
            );
            echo API::viewTable(array(), $field, 'data_table_notUser');
            ?>
        </div>
        <?php } ?>

        <?php if($AppUI->isAccess(ACCESSES::ACCESS_PAGE_CAL_ALL, true)) { ?>
            <div class="c-tabs__pane" id="nav-allcalc" role="tabpanel" aria-labelledby="nav-allcalc-tab">
                <?php
                $field = array(
                    0 => array('name' => 'Наименование'),
                    1 => array('date_add' => 'Дата'),
                    2 => array('summ_one' => 'Сумма за единицу изделия без НДС, BYN'),
                    3 => array('summ' => 'Сумма расчета, BYN'),
                    4 => array('USER_FIO' => 'Пользователь'),
                    5 => array(
                        'name' => 'btn_clone',
                        'type' => 'i',
                        'func' => 'cloneRowInTable',
                        'url' => 'm=calculator&a=AjaxCloneCalc',
                        'class' => 'fa fa-clone fa-lg',
                        'value' => 'id'
                    ),
                );
                echo API::viewTable(array(), $field, 'data_table_all');
                ?>
            </div>
        <?php } ?>

    </div>
</div>
