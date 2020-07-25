<?php
// test - http://artline.city/www/index.php?m=orders&u=ordersBlank&scr=1&idItems=[7]
    //$AppUI->isAccess(PRINT_ORDERS_BLANK);
    $msg = null;

    do {
        $idItems = (isset($_GET['idItems']) && !empty($_GET['idItems']) && is_array(json_decode($_GET['idItems']))) ? json_decode($_GET['idItems']) : array();
        if(empty($idItems) || !is_array($idItems) || count($idItems) == 0) {
            $msg = 'Не задан заказ';
            break;
        }

        foreach ($idItems as $key => $value) {
            $item = new classes_orderItem();
            if(null !== ($msg = $item->loadById($value)))
                break(2);
            $order = new classes_orders();
            if(null !== ($msg = $order->loadById($item->order_id)))
                break(2);
            $client = new classes_clients();
            if(null !== ($msg = $client->loadById($order->CLIENT_ID)))
                break(2);
            $user = new classes_users();
            if(null !== ($msg = $user->loadByUnique('USER_LOGIN', $order->USER_ID)))
                break(2);
            $unit = new classes_units();
            if(null !== ($msg = $unit->loadById($item->units)))
                break(2);
?>
            <p style="font-size: 10pt;">Дата печати бланка: <?php echo API::CurrentDate(CONSTANTS::REPORT_DATETIME_FORMAT_SHORT); ?></p>
           <table style="width: 100%; font-size: 10pt;">
               <tr>
                   <td>Дата сдачи: <?php echo API::FormatDate($item->dates_rdy, CONSTANTS::REPORT_DATETIME_FORMAT_SHORT); ?></td>
                   <td rowspan="3" align="right" style="font-size: 22pt;"><b><?php echo $item->order_id . "_" . $item->item_id; ?></b></td>
               </tr>
               <tr>
                   <td>Заказчик: <?php echo $client->CLIENT_NAME; ?></td>
               </tr>
               <tr>
                   <td>Продукция: <?php echo $item->name_item; ?></td>
               </tr>
               <tr>
                   <td>Формат, мм: <?php echo $item->size; ?></td>
                   <td>Тираж: <?php echo $item->total . " " . $unit->Name; ?></td>
               </tr>
               <tr>
                   <td>Менеджер: <b><?php echo $user->USER_FIO; ?></b></td>
               </tr>
               <tr>
                   <td></td>
               </tr>
           </table>
<?php

            $form = json_decode($item->template);
            $form_calc = json_decode($item->template_calc);

            $kol = $form->kol;
            for($i = 0; $i < $kol; $i++) {
?>
                <hr>
                <table id="tbl_part_<?php echo $i; ?>" style="width: 100%; font-size: 10pt;">
<?php
                $itemHTML = "";

                $calc = new classes_VariableCalc();
                $calc->bind($form_calc->data[$i]);
                //API::vardump($calc, false);

                $itemHTML .= "<tr><td></td><td id='td_layout_" . $i . "' rowspan='5' align='right'>Листов на тираж - " . $calc->ResultCalc->count_list_pages
                    . "<br>К-во на листе - " . $calc->ResultCalc->count_sheet . "<br>"
                    . $calc->ResultCalc->Layout . "</td></tr>";
                $itemHTML .= !empty($calc->p_namepart) ? "<tr><td><b>Наименование части: " . $calc->p_namepart . "</b></td></tr>" : "";
                $itemHTML .= !empty($calc->p_size) ? "<tr><td>Размер части: " . $calc->p_size . "</td></tr>" : "";

                $equipment = new classes_equipment();
                if(null !== ($msg = $equipment->loadById($calc->p_eq))) {
                    break(2);
                }
                $itemHTML .= "<tr><td>Оборудование: " . $equipment->EQ_NAME . "</td></tr>";

                $oper = new classes_operations();
                $oper->loadById($calc->p_color);
                $itemHTML .= !empty($oper->PAR) ? "<tr><td>Цвет: " . $oper->PAR . "</td></tr>" : "";

                $classMat = new classes_klMat();
                $classMat->loadById($calc->p_mat);
                $classMat->get_tree();
                $itemHTML .= !empty($classMat->title) ? "<tr><td>Материал: " . $classMat->title . "</td></tr>" : "";

                $itemHTML .= !empty($calc->p_new_size) ? "<tr><td>Печатный размер: " . $calc->p_new_size . "</td></tr>" : (!empty($calc->p_sizep) ? "<tr><td>Печатный размер: " . $calc->p_sizep . "</td></tr>" : "");

                echo $itemHTML;
?>
                </table>

                <script>
                    var count_tr = document.getElementById('tbl_part_' + <?php echo $i; ?>).getElementsByTagName('tbody')[0].getElementsByTagName('tr').length;
                    document.getElementById('td_layout_' + <?php echo $i; ?>).setAttribute('rowspan', count_tr);
                </script>
<?php
            }

?>
            <div class="more"></div>
<?php
        }

    } while(false);

?>

    <style>
        .block_table {
            border: 1px solid #000000;
            position: relative;
            background-color: #ffffff;
            width: 130px;
            height: 120px;
        }

        .bl {
            background-color: #000000;
            position: absolute;
            border: 1px solid #ffffff;
        }

        @media print {
            @page {
                size: A5 portrait;
            }
            .more {
                page-break-after: always;
            }
        }

    </style>

<?php

    echo $msg;
?>