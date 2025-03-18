<?php
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
include 'firewall1.php';
session_start();
$login = $_SESSION['login'];
$query = "select user_per, DT1, DT2, cl_id from users where user_login = '" . $login . "' LIMIT 1";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
    $admin = $row[0];
    $DT1 = $row[1];
    $DT2 = $row[2];
    $cl_id = $row[3];
}
if ($cl_id == "") {
    $cl_id = '-1';
}

if ($DT1 == "" and $DT2 == "") {
    $DT1 = date("01/m/Y");
    $DT2 = date("t/m/Y");
}

if ($admin == "4") {
    $DT1 = date("d/m/Y");
    $DT2 = date("d/m/Y");
}

if ($admin == "5" or $admin == "6" or $admin == "7") {
    header("Location: task_p_d.php");
}

if ($admin == "9") {
    header("Location: stock.php");
}
if ($admin == "8") {
    header("Location: task.php");
}
$query = "SELECT count(op.status) stat FROM orders o, order_product op where o.user_id = '" . $login . "' and op.ORDER_ID = o.NUMBER and (op.status = '20' OR op.status = '21' OR op.status = '0') ";
/*IF ($admin == "4"){
        $query = "SELECT count(op.status) stat FROM orders o, order_product op where  op.ORDER_ID = o.NUMBER and (op.status = '20' OR op.status = '21' OR op.status = '0') ";
    }*/
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
    $count_voz = $row[0];
}

$query = "SELECT count(op.status) stat FROM orders o, order_product op where o.user_id = '" . $login . "' and op.ORDER_ID = o.NUMBER and op.status = '2' ";
/*IF ($admin == "4"){
        $query = "SELECT count(op.status) stat FROM orders o, order_product op where  op.ORDER_ID = o.NUMBER and op.status = '2'";
    }*/
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) {
    $count_rdy = $row[0];
}
?>

<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Система управления заказами</title>
    <link rel="icon" href="../favicon.png" type="image/png">
    <link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
    <link href="../dist/css/sb-admin-2.css" rel="stylesheet">
    <link type="text/css" href="../js/jquery-ui.min.css" rel="stylesheet"/>
    <link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="../../vendor/chosen.min.css">
    <link href="../vendor/select2.min.css" rel="stylesheet"/>
    <style>
        .modal-lg {
            height: 90%;
            width: 90%;
            margin-left: 5%;
        }

        .content {
            padding: 3px;
            margin-bottom: 5px;
        }

        .MYlistbox {
            display: block;
            width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        .btn-block {
            display: block;
            width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        .inputSearch {
            display: block;
            width: 100%;
            padding-left: 0;
            padding-right: 0;
        }

        select {
            width: 100%; /* Ширина списка в пикселах */
        }
    </style>


    <script>

        window.addEventListener("DOMContentLoaded", function () {

            var inpMY = document.getElementById("search2");
            var sMY = document.getElementById("orderClient");
            var grMY = sMY.querySelectorAll("optgroup");

            var oMY = [].map.call(grMY, function (el) {
                return [].slice.call(el.querySelectorAll("option"))
            });

            function FiltrGoAnother() {
                var regMY = new RegExp(inpMY.value, "ig");
                oMY.forEach(function (node, i) {
                    node.forEach(function (op, a) {
                        regMY.lastIndex = 0;
                        regMY.test(op.text) ? grMY[i].appendChild(op) : op.parentNode && grMY[i].removeChild(op)
                    });
                    grMY[i].children.length ?
                        sMY.appendChild(grMY[i]) : grMY[i].parentNode && sMY.removeChild(grMY[i])
                })
            }

            inpMY.addEventListener("input", FiltrGoAnother, false)
        });
        window.addEventListener("DOMContentLoaded", function () {
            var inpMY = document.getElementById("search1");
            var sMY = document.getElementById("orderClient1");
            var grMY = sMY.querySelectorAll("optgroup");
            var oMY = [].map.call(grMY, function (el) {
                return [].slice.call(el.querySelectorAll("option"))
            });

            function FiltrGoAnother() {
                var regMY = new RegExp(inpMY.value, "ig");
                oMY.forEach(function (node, i) {
                    node.forEach(function (op, a) {
                        regMY.lastIndex = 0;
                        regMY.test(op.text) ? grMY[i].appendChild(op) : op.parentNode && grMY[i].removeChild(op)
                    });
                    grMY[i].children.length ?
                        sMY.appendChild(grMY[i]) : grMY[i].parentNode && sMY.removeChild(grMY[i])
                })
            }

            inpMY.addEventListener("input", FiltrGoAnother, false)
        });
    </script>
</head>

<body>
<div id="container">
    <!-- Navigation -->
    <?php
    include_once("menu.php");
    ?>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Заказы</h2>
            </div>
        </div>

        <div class="row" style="margin-bottom: 10px">
            <div class="col-md-3">
                <input id="getCurrentOrderId" type="number" class="form-control" placeholder="Перейти в заказ"
                       style="width: 60%; display: inline;" onchange="getCurrentOrder()">
                <button class="btn btn-warning" onclick="getCurrentOrder()">Перейти</button>
                <script>
                    function getCurrentOrder() {
                        if (document.getElementById("getCurrentOrderId").value != "") {
                            location.href = "/pages/pg/_addAcct.php?id=" + document.getElementById("getCurrentOrderId").value;
                        }
                    }
                </script>
            </div>
        </div>

        <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#home" data-toggle="tab">Счета</a></li>
            <li><a href="#profile" data-toggle="tab">Заказы в работе <span
                            class="label label-danger"><?php echo $count_voz; ?></span> <span
                            class="label label-success"><?php echo $count_rdy; ?></span> </a></li>
            <li><a href="#report_of_manager" data-toggle="tab">Отчет по менеджерам</a></li>
            <li><a href="#not_task" data-toggle="tab">Оплачено но не в работе</a></li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane active" id="home">
                <div class="row">
                    <div class="col-lg-1 col-md-1 col-sm-1"><b>Заказчик</b></div>
                    <div class="col-lg-6 col-md-6 col-sm-6">
                        <div class="row">
                            <div class="col-lg-7 col-md-7 col-sm-7">
                                <select class="myselect" data-live-search='true' id='orderClient2' class="form-control">
                                    <?php
                                    $srt_cl = '';
                                    echo "<option value='-1'>Все</option>";
                                    $srt_cl .= "<option value='$row[0]'>" . $row[1] . " (" . $row[2] . ")</option>";
                                    $query = "SELECT id,client_name, unp, PHONE_MOB, CLIENT_STATUS,
                                                            IF(CLIENT_STATUS='f', 1, IF(tbl2.parent_company=2, 1, 0)) pc
                                                            FROM clients
                                                            LEFT JOIN 
                                                                (SELECT tbl.CLIENT_ID,tbl.parent_company
                                                                FROM 
                                                                (SELECT * FROM orders ORDER BY orders.NUMBER DESC) tbl
                                                                GROUP BY tbl.CLIENT_ID) tbl2 ON clients.ID=tbl2.CLIENT_ID
                                                            WHERE client_name <> ''
                                                            ORDER BY client_name";
                                    $result = mysql_query($query) or die($query);
                                    $srt_cl = "";
                                    while ($row = mysql_fetch_row($result)) {
                                        $indi = '';
                                        if ($row[4] == 'f') {
                                            $indi = $row[3];
                                        } else {
                                            $indi = $row[2];
                                        }
                                        if ((int)$row[0] == (int)$cl_id) {
                                            echo "<option value='$row[0]' data-mechta='" . $row[5] . "' selected>" . $row[1] . " (" . $indi . ")</option>";
                                        } else {
                                            echo "<option value='$row[0]' data-mechta='" . $row[5] . "'>" . $row[1] . " (" . $indi . ")</option>";
                                        }
                                        $srt_cl .= "<option value='$row[0]' data-mechta='" . $row[5] . "'>" . $row[1] . " (" . $indi . ")</option>";
                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-1 col-md-1 col-sm-1">
                                <a onclick="refr()"><span class="glyphicon glyphicon-remove  "></span></a>
                            </div>
                        </div>
                    </div>

                </div>


                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a href="#tab_a" data-toggle="tab">Счета</a></li>
                    <li><a href="#tab_p" data-toggle="tab">Продукты </a></li>
                    <li><a href="#tab_o" data-toggle="tab">Оплаты </a></li>
                    <li><a href="#tab_t" data-toggle="tab">ТН </a></li>
                    <li><a href="#tab_post" data-toggle="tab" onclick="checkedPost(this)">Доставки </a></li>
                </ul>

                <div class="tab-content">
                    <!-- Счета -->
                    <div class="tab-pane active" id="tab_a">
                        <div class="row">
                            <div class="col-md-2">
                                <?php
                                if ($admin != '2' and $admin != '7' and $admin != '6' and $admin != '5') {
                                    echo "	<button type='button' class='btn btn-default form-control'  onClick='addclient()' >Добавить заказ</button>";
                                }
                                ?>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-3">
                                <div class="input-group">
                                    <input type="text" name="dateStart" id="dateStart" class="datepicker form-control"
                                           value="<?php echo $DT1; ?>" size="8"/>
                                    <span class="input-group-addon">по</span>
                                    <input type="text" name="dateEnd" id="dateEnd" class="datepicker form-control"
                                           value="<?php echo $DT2; ?>" size="8"/>
                                </div>
                            </div>
                            <div class="col-md-1"></div>
                            <div class="col-md-2">
                                <select id='user_list' onchange='crt()' class="form-control">
                                    <option value='0'>Все</option>
                                    <?php
                                    $qr = "select u.user_login, u.USER_FIO,u.USER_FIO from users u where u.USER_PER = 3 OR u.USER_PER = 4";
                                    $rt = mysql_query($qr) or die($qr);
                                    while ($row = mysql_fetch_row($rt)) {
                                        if (!in_array($row[0], ['026', '033'])) {
                                            if ($row[0] == $login) {
                                                echo "<option value='$row[0]' selected>$row[1]</option>";
                                            } else {
                                                echo "<option value='$row[0]'>$row[1]</option>";
                                            }
                                        } else {
                                            echo "<option value='$row[0]'>$row[1]</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>

                        </div>
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover " cellspacing="0"
                                   id="tb_a">
                                <thead>
                                <tr>
                                    <th>+/-</th>
                                    <th>#</th>
                                    <th>Дата заказа</th>
                                    <th>Номер счета</th>
                                    <th>Наименование клиента</th>
                                    <th>Менеджер</th>
                                    <th>Сумма счета</th>
                                    <th>Сумма ТН</th>
                                    <th>Оплата</th>
                                    <th></th>
                                    <th></th>

                                </tr>

                                </thead>

                            </table>
                        </div>
                    </div>
                    <!-- Продукты -->
                    <div class="tab-pane" id="tab_p">
                        <div class="row">
                            <div class="col-md-3">
                                <?php
                                if ($admin != '2' and $admin != '7' and $admin != '6' and $admin != '5') {
                                    echo "	<button type='button' class='btn btn-default form-control'  onClick='addclient1()' >Добавить в новый заказ</button>";
//                                    echo "	<button type='button' class='btn btn-default form-control'  onClick='javascript: $(\"#addParentCompany\").modal(\"show\");' >Добавить в новый заказ</button>";
                                }
                                ?>
                            </div>
                            <div class="col-md-3">
                                <?php
                                if ($admin == '4' or $admin == '3') {
                                    echo ' <button type="button" class="btn btn-default"   id = "post">Доставка <span class="fa fa-car"> </span></button>';
                                }
                                ?>
                            </div>
                        </div>
                        <input type='hidden' id='copy_p' value=''>
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover " cellspacing="0"
                                   id="tb_p">
                                <thead>
                                <tr>
                                    <th>+/-</th>
                                    <th>#</th>
                                    <th>Дата счета</th>
                                    <th>Наименование клиента</th>
                                    <th>Наименование продукта</th>
                                    <th>Статус</th>
                                    <th>Кол-во</th>
                                    <th>Стоимость, BYN</th>
                                    <th>Номер счета</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- Оплаты -->
                    <div class="tab-pane" id="tab_o">
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover " cellspacing="0"
                                   id="tb_o">
                                <thead>
                                <tr>
                                    <th>Номер заказа</th>
                                    <th>Сумма</th>
                                    <th>Дата оплаты</th>
                                    <th>Вид</th>
                                    <th>Comment</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- ТН -->
                    <div class="tab-pane" id="tab_t">
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover " id="tb_t">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Дата Накладной</th>
                                    <th>Номер накладной</th>
                                    <th>Сумма</th>
                                    <th>Номер заказа</th>
                                    <th>Пользователь</th>
                                    <th>Испорчена</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    <!-- ДОСТАВКА -->

                    <div class="tab-pane" id="tab_post">
                        <div class="panel-body">
                            <table width="100%" class="table table-striped table-bordered table-hover " id="tb_post">
                                <thead>
                                <tr>
                                    <th>Вид</th>
                                    <th>Номер заказа</th>
                                    <th>Наименование клиента</th>
                                    <th>Стоимость, BYN</th>
                                    <th>Трек-код</th>
                                    <th>Дата</th>
                                    <th>Статус</th>
                                    <th></th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
            <div class="tab-pane" id="profile">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="row">
                                <div class='col-md-2'>
                                    <button type='button' class='btn btn-default form-control' onclick='_prod_rdy()'>
                                        Сдано заказчику
                                    </button>
                                </div>
                                <div class='col-md-2'>
                                    <button type='button' class='btn btn-default form-control' onclick='_prod_brak()'>
                                        Брак/Отказ
                                    </button>
                                </div>
                                <?php
                                if ($admin != '2' and $admin != '7' and $admin != '6' and $admin != '5') {
                                    echo "<div class='col-md-3'>	<button type='button' class='btn btn-default form-control'  onClick='_add_plan_job()' >Добавить в план работы</button></div>";
                                }
                                ?>
                                <?php
                                if ($admin == '4' or $login == '008' or $login == '029' or $login == '030') {
                                    echo "<div class='col-md-2'>		<button type='button' class='btn btn-default form-control'  onClick='_add_plan_job3()'>Создать план работы</button></div>";
                                }
                                ?>
                                <?php
                                if ($admin == '4' or $login == '008' or $login == '029' or $login == '030') {
                                    echo "<div class='col-md-2'>		<button type='button' class='btn btn-default form-control'  onclick='downloadCSV({ filename: " . '"' . "stock-data.csv" . '"' . " });'>Всего в работе</button></div>";
                                }
                                ?>
                            </div>
                            <div class="row" style="margin-top: 15px">
                                <div class="col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-addon">Менеджер</span>
                                        <select id='user_list_ord' onchange='crt_ord()' class="form-control">
                                            <option value='0'>Все</option>
                                            <?php
                                            if ($admin == '4') {
                                                $qr = "select u.user_login, u.USER_FIO from users u where u.USER_PER = 3 OR u.USER_PER = 4 ORDER BY u.USER_FIO";
                                            } else {
                                                $slct = "SELECT login_children,active FROM combo_users WHERE login_parent='$login' ORDER BY id DESC LIMIT 1";
                                                $q = mysql_query($slct) or die(null);
                                                $combo = "";
                                                if ($r = mysql_fetch_array($q)) {
                                                    if ($r['active'] == 0) {
                                                        $combo = " OR u.USER_LOGIN='" . $r['login_children'] . "'";
                                                    }
                                                }
                                                $qr = "
                                                        SELECT u.user_login, u.USER_FIO
                                                        FROM users u
                                                        WHERE u.USER_LOGIN = '" . $login . "'" . $combo . "
                                                        ORDER BY u.USER_FIO
                                                ";
                                            }
                                            $rt = mysql_query($qr) or die($qr);
                                            while ($row = mysql_fetch_row($rt)) {
                                                if ($row[0] == $login) {
                                                    echo "<option value='$row[0]' selected>$row[1]</option>";
                                                } else {
                                                    echo "<option value='$row[0]'>$row[1]</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-addon">Дата с</span>
                                        <input type="text" name="dateStartOrd" id="dateStartOrd"
                                               class="datepicker form-control"
                                               value="<?php
                                               $dt1_ord_w = core_sessionInfo::getInstance()->getInfoByField('dt1_ord_w');
                                               if (!$dt1_ord_w) {
                                                   if ($admin == 4) {
                                                       $dt1_ord_w = date("d/m/Y");
                                                   } else {
                                                       $dt1_ord_w = date("01/m/Y");
                                                   }
                                               }
                                               echo $dt1_ord_w;
                                               ?>" size="8"/>
                                        <span class="input-group-addon">по</span>
                                        <input type="text" name="dateEndOrd" id="dateEndOrd"
                                               class="datepicker form-control"
                                               value="<?php
                                               $dt2_ord_w = core_sessionInfo::getInstance()->getInfoByField('dt2_ord_w');
                                               if (!$dt2_ord_w) {
                                                   if ($admin == 4) {
                                                       $dt2_ord_w = date("d/m/Y");
                                                   } else {
                                                       $dt2_ord_w = date("t/m/Y");
                                                   }
                                               }
                                               echo $dt2_ord_w;
                                               ?>"
                                               size="8"/>
                                    </div>
                                    <?php
                                    if ($admin == 4 && $login != 'admins') {
                                        echo '<div class="col-md-3"><input type="checkbox" id="all_list_orders_work" onchange="refListOrdersWork(this)"><b>Все заявки</b></div>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="panel-body">
                                <table width="100%"
                                       class="table table-striped table-bordered table-hover responsive nowrap display"
                                       cellspacing="0" id="example1">
                                    <thead>
                                    <tr>
                                        <th></th>
                                        <th>Дата сдачи</th>
                                        <th>Номер счета</th>
                                        <th>Наименование клиента</th>
                                        <th>Наименование продукта</th>
                                        <th>Статус</th>
                                        <th>Проблема</th>
                                        <th>Комментарий</th>
                                        <th>Требуется</th>
                                        <th>Готово</th>
                                        <th>Заказан</th>
                                        <th>Сумма счета</th>
                                        <th>Сумма заказа</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tfoot align="right">
                                    <tr>
                                        <th colspan="11"></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------- Отчет по менеджерам ----------------------------------------------------------->
            <div class="tab-pane" id="report_of_manager">
                <div class="row">
                    <div class="col-md-3">
                        <button type="button" class="btn btn-default form-control" onclick="loadReportOfManager()">
                            Обновить
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <input class="form-control input-sm" id="searchOfReportManager" type="text" placeholder="Поиск"
                               oninput="tableSearch('searchOfReportManager','table_report_manager')">
                    </div>
                </div>
                <div class="row">
                    <div class="panel-body">
                        <table width="100%" class="table table-striped table-bordered table-hover responsive nowrap "
                               cellspacing="0" id="table_report_manager">
                            <thead>
                            <tr>
                                <th style="cursor:pointer" class="sort"><i class="fa fa-fw fa-sort"></i>Менеджер</th>
                                <th style="cursor:pointer" class="sort"><i class="fa fa-fw fa-sort"></i>Номер счета</th>
                                <th style="cursor:pointer" class="sort"><i class="fa fa-fw fa-sort"></i>Клиент</th>
                                <th style="cursor:pointer" class="sort"><i class="fa fa-fw fa-sort"></i>Наименование
                                </th>
                                <th style="cursor:pointer" class="sort"><i class="fa fa-fw fa-sort"></i>Дата добавления
                                </th>
                                <th style="cursor:pointer" class="sort"><i class="fa fa-fw fa-sort"></i>Дата отправки в
                                    работу
                                </th>
                                <th style="cursor:pointer" class="sort"><i class="fa fa-fw fa-sort"></i>Дата сдачи</th>
                                <th style="cursor:pointer" class="sort"><i class="fa fa-fw fa-sort"></i>Статус</th>
                            </tr>
                            </thead>
                            <tbody></tbody>

                        </table>
                    </div>
                </div>
            </div>
            <!-------------------------------------------------------Оплачено, но не в работе-------------------------------------------------------------->
            <div class="tab-pane" id="not_task">
                <div class="row">
                    <div class="panel-body">
                        <table width="100%" class="table table-striped table-bordered table-hover responsive nowrap "
                               cellspacing="0" id="table_not_task">
                            <thead>
                            <tr>
                                <?php if ($admin == '4'): ?>
                                    <th id="th_manager">Менеджер</th>
                                <?php endif; ?>
                                <th>Номер счета</th>
                                <th>Наименование клиента</th>
                                <th>Наименование продукта</th>
                                <th>Сумма счета</th>
                                <th>Оплата по всему счету</th>
                                <th></th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!------------------------------------------Доставка--------------------------------------------------------------->
<div id="form_post" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false"
     style="display: none;">
    <div class="modal-dialog modal-lgd">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">Доставка</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-2"><label>Вид</label></div>
                    <div class="col-md-10">
                        <select name="view_post" id="view_post" class="form-control">
                            <option>БЕЛПОЧТА</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>ФИО</label></div>
                    <div class="col-md-10">
                        <input type="text" id='post_fio' class="form-control"
                               onKeyUp="this.value = this.value.replace (/[^a-zA-Zа-яА-ЯёЁ .]/, '')">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Область</label></div>
                    <div class="col-md-10">
                        <select name="region_id" id="region_id" class="form-control">
                            <option value=''>Выберите область</option>
                            <option>Брестская обл.</option>
                            <option>Витебская обл.</option>
                            <option>Гомельская обл.</option>
                            <option>Гродненская обл.</option>
                            <option>Минская обл.</option>
                            <option>Могилевская обл.</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2"><label>Район</label></div>
                    <div class="col-md-10">
                        <input type="text" id='post_raion' class="form-control">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2"><label>Населенный пункт</label></div>
                    <div class="col-md-10">
                        <input type="text" id='post_city' class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Улица</label></div>
                    <div class="col-md-10">
                        <div class="input-group ">
                            <span class="input-group-addon">ул.</span>
                            <input type="text" id='post_street' class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Дом</label></div>
                    <div class="col-md-10">
                        <div class="input-group ">
                            <span class="input-group-addon">д.</span>
                            <input type="text" id='post_house_num' class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Корпус</label></div>
                    <div class="col-md-10">
                        <div class="input-group ">
                            <span class="input-group-addon">корпус.</span>
                            <input type="text" id='post_house_kor' class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Квартира</label></div>
                    <div class="col-md-10">
                        <div class="input-group ">
                            <span class="input-group-addon">кв.</span>
                            <input type="text" id='post_room' class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Индекс</label></div>
                    <div class="col-md-10">
                        <input type="text" id='post_index' class="form-control" placeholder="224000"
                               onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Телефон</label></div>
                    <div class="col-md-10">
                        <input type="text" id='post_phone' class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Цена</label></div>
                    <div class="col-md-10">
                        <input type="text" id='post_price' class="form-control"
                               onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Трек-код</label></div>
                    <div class="col-md-10">
                        <div id='post_track' class="form-control"></div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-2"><label>Дата отправки</label></div>
                    <div class="col-md-10">
                        <input type="date" id='post_date' class="form-control"
                               onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')">
                    </div>
                </div>

                <div class='row'>
                    <div class='col-md-2'><input type="button" class="btn btn-default form-control" value="Добавить"
                                                 style="margin: 10px" onclick="addRow_post()"></div>
                    <div class='col-md-2'><input type="button" class="btn btn-default form-control" value="Удалить"
                                                 style="margin: 10px" onclick="del_row_post()"></div>
                </div>

                <div>
                    <table width='100%' name='post_inf' id="post_inf"
                           class="table table-striped table-bordered table-hover " id="contact_cl">
                        <thead>
                        <tr>
                            <th scope="col">Del <i class='glyphicon glyphicon-trash'></i></th>
                            <th scope="col">Описание</th>
                            <th scope="col">Кол-во мест</th>
                            <th scope="col">Вес, кг</th>
                            <th scope="col">Длина, см</th>
                            <th scope="col">Ширина, см</th>
                            <th scope="col">Высота, см</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div id='info_dost'>

                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="post_kol" value='0'>
                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                <button type="button" class="btn btn-primary" onclick="add_post()">Добавить</button>
            </div>
        </div>
    </div>
</div>
<!--------------------------------------------------------------------------------------------------------->
<!------------------------------------------tn --------------------------------------------------------------->
<div id="myModal" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">ПРОСМОТР</h4>
            </div>
            <div class="modal-body">
                <div id="hh1">
                    <button id='deltn' type="button" class="btn btn-default">Удалить</button>
                </div>
                <div id="hh">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
            </div>
        </div>
    </div>
</div>
<!------------------------------------------act --------------------------------------------------------------->
<div id="myacct" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static" data-keyboard="false"
     style="display: none;">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">ТН</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <iframe id='fr' src="" width="100%" height="700" align="left">Ваш браузер не поддерживает
                            плавающие фреймы!
                        </iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!------------------------------------------cl --------------------------------------------------------------->
<div id="add_opl" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                <h4 class="modal-title">Добавление оплаты</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="block1">
                            <?php
                            echo "<label>Клиент:</label></div></div><div class='col-md-6'><div class='block1'>";
                            echo "<select id = 'client' disabled>";
                            echo "<option value=''></option>";
                            include 'db.php';
                            echo $srt_cl;
                            echo "</select></div></div></div>";
                            ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="block1">
                                        <label>№ заказа:</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="block1">
                                        <input id='num' type='text' disabled value='0'/>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="block1">
                                        <label>Сумма:</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="block1">
                                        <input id='sum' type='text' value='0'/>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <div class='block1'>
                                        <label>Дата:</label>
                                    </div>
                                </div>
                                <div class='col-md-2'>
                                    <div class='block1'>
                                        <input type='date' id='date_opl' name='date_opl'>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <div class='block1'><label>Вид:</label></div>
                                </div>
                                <div class='col-md-9'>
                                    <div class='block1'>
                                        <div class="form-group" id='radio_chk'>
                                            <label class="radio-inline">
                                                <input type="radio" name="view_opl" id="view_opl1" value="1" checked>Касса
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="view_opl" id="view_opl2" value="2">Терминал
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="view_opl" id="view_opl3" value="3">безнал
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="view_opl" id="view_opl4" value="4">Наличные
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class='row'>
                                <div class='col-md-3'>
                                    <div class='block1'>
                                        <label>Комментарий:</label>
                                    </div>
                                </div>
                                <div class='col-md-9'>
                                    <textarea class="form-control" rows="3" id='list_comm'></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                            <a onclick="fun_opl()">
                                <button type="button" class="btn btn-primary">Добавить</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-------------------------------------------------dd-------------------------------------------------------->

            <div id="smena_cl" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true"
                 style="display: none;">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Выбор клиента</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="block1"><label>Поиск:</label></div>
                                </div>
                                <div class="col-md-9">
                                    <div class="block1"><input type="text" id="search1" class="inputSearch"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="block1">
                                        <label>Клиент:</label>
                                    </div>
                                </div>
                                <div class='col-md-10'>
                                    <input type="hidden" id="smen" value="">
                                    <div class='block1'>
                                        <?php
                                        echo "<select id = 'orderClient1' size='10' style='overflow:auto;'>";
                                        echo " <optgroup label=''>";
                                        echo $srt_cl;
                                        echo "</select> &nbsp";
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                            <a onclick="sscm()">
                                <button type="button" class="btn btn-primary">Изменить</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------------------------------------------------------------------------------------------------->
            <!-------------------------------------------------dd-------------------------------------------------------->

            <div id="smena_dt" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true"
                 style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Выбор даты</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="block1"><label></label></div>
                                </div>
                                <div class="col-md-2">
                                    <div class="block1"><label>Дата:</label></div>
                                </div>
                                <div class="col-md-4">
                                    <div class="block1"><input type="date" id="dt_sme"></div>
                                </div>
                                <div class="col-md-3">
                                    <div class="block1"><input type="hidden" id="id_dt_sme" value=""></div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                            <a onclick="save_dt_acct()">
                                <button type="button" class="btn btn-primary">Изменить</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------------------------------------------------------------------------------------------------->

            <!-- /#Модальные окна -->
            <div id="myaddacct" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true"
                 style="display: none;">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Добавление заказа</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger" id='err' style="display: none;">
                                <a href="#" class="close">×</a>
                                <strong>Ошибка!</strong> Выберите клиента!
                            </div>
                            <!-- Переход на Мечту -->
                            <!--<div class="row">
                            <div class="col-md-2"><label>ФИРМА:</label></div>
                            <div class="col-md-4">
                                <label class="radio-inline">
                                    <input type="radio" name="parent_company"  id = 'parent_company_a' value="1" checked>ОДО АртЛайнСити
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="parent_company" id = 'parent_company_m' value="2">ЧУП Мечта клиента
                                </label>
                            </div>
                        </div>-->
                            <div class="row">
                                <div class="col-md-2"><label>От даты:</label></div>
                                <div class="col-md-2"><input type='date' id='date_acct' name='date_acct'></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <label>Валюта:</label>
                                </div>
                                <div class="col-md-5">
                                    <select id='val_'>
                                        <?php
                                        $query = "select id,name,code from currency";
                                        $result = mysql_query($query) or die($query);
                                        while ($row = mysql_fetch_row($result)) {
                                            if ($row[2] == "974" || $row[2] == "933") {
                                                echo "<option value='$row[2]' SELECTED>" . $row[1] . "</option>";
                                            } else {
                                                echo "<option value='$row[2]'>" . $row[1] . "</option>";
                                            }
                                        }

                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-2">
                                    <div class="block1"><label>Поиск:</label></div>
                                </div>
                                <div class="col-md-9">
                                    <div class="block1"><input type="text" id="search2" class="inputSearch"></div>
                                </div>
                                <div class='col-md-1'>
                                    <div class='block1'><a
                                                onClick="window.open('clients.php', 'Добавление нового клиента', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');">
                                            <button type="button" class="btn btn-sm"><span
                                                        class="glyphicon glyphicon-plus-sign"></span></button>
                                        </a></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="block1"><label>Клиент:</label></div>
                                </div>
                                <div class='col-md-10'>
                                    <div class='block1'>
                                        <select id='orderClient' size='10' style='overflow:auto;'
                                                onchange="selectClient(this)">
                                            <?php
                                            echo " <optgroup label=''>";
                                            echo $srt_cl;
                                            echo "</select> &nbsp";
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--	<input type='hidden' id = 'orderClient' value='705'>-->
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                            <a onclick="preaddorders()">
                                <button type="button" class="btn btn-primary" id='lock_add'>Добавить</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--------------------------------------------------------------------------------------------------------->

            <div id="filesa" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true"
                 style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Редактирование продукта</h4>
                        </div>
                        <div class="modal-body">
                            <form class='form-signin' method='post' action='ajax_php_sql_post.php'
                                  enctype='multipart/form-data' id='forms'>
                                <div id='info_prod'></div>
                            </form>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                                <a onclick="save_prod()">
                                    <button type="button" class="btn btn-primary">Сохранить</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--------------------------------------------------------------------------------------------------------->

            <!-- /#Модальные окна -->
            <div id="planJob" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true"
                 style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Заказ</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-2"><label>Дата:</label></div>
                                <div class="col-md-3"><input type='date' id='datepj_' name='datepj_'></div>
                                <div class="row">
                                    <div class="col-md-1"> &nbsp;</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1"> &nbsp;</div>
                                </div>
                                <div id='pj'></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                                <a onclick="_add_plan_job2()">
                                    <button type="button" class="btn btn-primary">Заказать</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- /#Модальные окна -->
            <div id="planJob1" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="true"
                 style="display: none;">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content ">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Заказ</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-1"></div>
                                <div class="col-md-2"><label>Дата:</label></div>
                                <div class="col-md-3">
                                    <input type='date' id='datepj1_' name='datepj1_'>
                                    <button type="button" class="btn btn-info btn-circle" onclick='_add_plan_job4()'><i
                                                class="fa fa-search"></i></button>
                                </div>
                                <div class="row">
                                    <div class="col-md-1"> &nbsp;</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-1"> &nbsp;</div>
                                </div>
                                <div id='pj1'></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                                <a onclick="_add_plan_job5()">
                                    <button type="button" class="btn btn-primary">Заказать</button>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--------------------------------------------------------------------------------------------------------->

            <!------------------------------------------acct_tranc --------------------------------------------------------------->
            <div id="acct_tranc" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static"
                 data-keyboard="false" style="display: none;">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">ПЕРЕМЕЩЕНИЕ</h4>
                        </div>
                        <div class="modal-body">
                            <div id='lst'></div>
                            <div class="modal-footer">
                                <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                                <button type="button" class="btn btn-primary" onclick='tran_()'>Переместить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--------------------------------------------------------------------------------------------------------->
            <!------------------------------------------addContract --------------------------------------------------------------->
            <div id="addContract" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static"
                 data-keyboard="false" style="display: none;">
                <div class="modal-dialog  modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">×</button>
                            <h4 class="modal-title">Добавить договор</h4>
                        </div>
                        <div class="modal-body">
                            <input type='text' size=12 id='addContract_company' hidden>
                            <input type='text' size=12 id='addContract_client' hidden>
                            <div class='row'>
                                <div class='col-md-2'><label>Договор №:</label></div>
                                <div class='col-md-3'><input type='text' size=12 id='addContract_num_doc'
                                                             class=" form-control"></div>
                                <div class='col-md-1'><label>от:</label></div>
                                <div class='col-md-3'><input type='date' id='addContract_num_doc_date'
                                                             class="form-control"></div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
                            <button type="button" class="btn btn-primary" onclick='addFuncContract()'>Добавить</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--------------------------------------------------------------------------------------------------------->
            <!------------------------------------------addParentCompany --------------------------------------------------------------->
            <!--            <div id="addParentCompany" class="modal fade bd-example-modal-lg" tabindex="-1" data-backdrop="static"-->
            <!--                 data-keyboard="false" style="display: none;">-->
            <!--                <div class="modal-dialog  modal-lg">-->
            <!--                    <div class="modal-content">-->
            <!--                        <div class="modal-header">-->
            <!--                            <button class="close" type="button" data-dismiss="modal">×</button>-->
            <!--                            <h4 class="modal-title">Добавить фирму</h4>-->
            <!--                        </div>-->
            <!--                        <div class="modal-body">-->
            <!--                            <div class="row">-->
            <!--                                <div class="col-md-2"><label>ФИРМА:</label></div>-->
            <!--                                <div class="col-md-4">-->
            <!--                                    <label class="radio-inline">-->
            <!--                                        <input type="radio" name="addParentCompany_parent_company"-->
            <!--                                               id='addParentCompany_parent_company_a' value="1" checked>ОДО АртЛайнСити-->
            <!--                                    </label>-->
            <!--                                    <label class="radio-inline">-->
            <!--                                        <input type="radio" name="addParentCompany_parent_company"-->
            <!--                                               id='addParentCompany_parent_company_m' value="2">ЧУП Мечта клиента-->
            <!--                                    </label>-->
            <!--                                </div>-->
            <!--                            </div>-->
            <!--                        </div>-->
            <!--                        <div class="modal-footer">-->
            <!--                            <button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>-->
            <!--                            <button type="button" class="btn btn-primary" onclick='addclient1()'>Добавить</button>-->
            <!--                        </div>-->
            <!--                    </div>-->
            <!--                </div>-->
            <!--            </div>-->

            <!--------------------------------------------------------------------------------------------------------->
            <script src="../vendor/jquery/jquery.min.js"></script>
            <script src="../js/jquery-ui.min.js"></script>
            <script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
            <script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
            <script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
            <script src="../js/funJs.js"></script>
            <script src="../vendor/bootstrap/js/bootstrap-select.js" type="text/javascript"></script>
            <script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
            <script src="../vendor/metisMenu/metisMenu.min.js"></script>
            <script src="../dist/js/sb-admin-2.js"></script>
            <script src="../vendor/select2.min.js"></script>
            <script src="js/orders.js?version=2"></script>

            <script>
                //	$("#orderClient2").chosen();
                $(".myselect").select2();
                var id_cl = $("#orderClient2").val();
                if (id_cl == '-1') {
                    ajax_a = 'ajax_php_sql.php?dt1=' + parseDateValue2($("#dateStart").val()) + "&" + 'dt2=' + parseDateValue2($("#dateEnd").val()) + "&" + 'dt1_=' + $("#dateStart").val() + "&" + 'dt2_=' + $("#dateEnd").val() + "&" + 'user_id=' + $("#user_list").val() + "&" + 'flag=46';
                } else {
                    ajax_a = 'ajax_php_sql.php?flag=38&id_cl=' + id_cl;
                }

                var default_options = {
                    "iDisplayLength": 10,
                    "bStateSave": true,
                    "responsive": true,
                    "paging:": true,
                    "ajax": {
                        type: "GET",
                        url: ajax_a,
                        "dataSrc": ""
                    },
                    "columns": [
                        {"data": "q", "className": 'details-control'},
                        {"data": "edit"},
                        {"data": "dates", "sType": "ruDate"},
                        {"data": "id"},
                        {"data": "names"},
                        {"data": "mng"},
                        {"data": "sum1"},
                        {"data": "sum2"},
                        {"data": "opl"},
                        {"data": "opl_add"},
                        {"data": "info"},
                    ],
                    "aaSorting": [[2, 'desc']]
                };

                var tb_a = $('#tb_a').dataTable(default_options);

                var default_options1 = {
                    "iDisplayLength": 10,
                    "bStateSave": true,
                    "responsive": true,
                    "paging:": true,
                    "ajax": {
                        type: "GET",
                        url: 'ajax_php_sql.php?flag=39&id_cl=' + id_cl,
                        "dataSrc": ""
                    },
                    "columns": [
                        {"data": "q", "className": 'details-control'},
                        {"data": "copy"},
                        {"data": "dt", "sType": "ruDate"},
                        {"data": "name"},
                        {"data": "col3"},
                        {"data": "col4"},
                        {"data": "col5"},
                        {"data": "col6"},
                        {"data": "col1"}
                    ],
                    "aaSorting": [[6, 'desc']]
                };

                var tb_p = $('#tb_p').dataTable(default_options1);

                var default_options2 = {
                    "iDisplayLength": 10,
                    "bStateSave": true,
                    "responsive": true,
                    "paging:": true,
                    "ajax": {
                        type: "GET",
                        url: 'ajax_php_sql.php?flag=40&id_cl=' + id_cl,
                        "dataSrc": ""
                    },
                    "columns": [
                        {"data": "td3"},
                        {"data": "td5"},
                        {"data": "td6", "sType": "ruDate"},
                        {"data": "td7"},
                        {"data": "td8"}
                    ],
                    "aaSorting": [[0, 'desc']]
                };

                var tb_o = $('#tb_o').dataTable(default_options2);

                var default_options3 = {
                    "responsive": true,
                    "paging:": true,
                    "iDisplayLength": 25,
                    "ajax": {
                        "url": 'ajax_php_sql.php?flag=41&id_cl=' + id_cl,
                        "type": "GET",
                        "dataSrc": ""
                    },
                    "columns": [
                        {"data": "flags"},
                        {"data": "td2", "sType": "ruDate"},
                        {"data": "td3"},
                        {"data": "td9"},
                        {"data": "td1"},
                        {"data": "td5"},
                        {"data": "td6"}
                    ],
                    "aaSorting": [[2, 'desc']]
                };

                var tb_t = $('#tb_t').dataTable(default_options3);

                var default_options5 = {
                    "iDisplayLength": 50,
                    "responsive": true,
                    "buttons": [
                        {
                            extend: 'collection',
                            text: 'Export',
                            buttons: ['pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5']
                        }
                    ],
                    "ajax": {
                        "url": 'ajax_php_sql.php?flag=44&dt1=' + parseDateValue2($("#dateStartOrd").val()) + '&' + 'dt2=' + parseDateValue2($("#dateEndOrd").val()) + '&dt1_=' + $("#dateStartOrd").val() + '&dt2_=' + $("#dateEndOrd").val() + '&user_id=' + $("#user_list_ord").val(),
                        "dataSrc": ""
                    },
                    "columns": [
                        {"data": "flags"},
                        {"data": "dats", "sType": "ruDate"},
                        {"data": "ids"},
                        {"data": "namess"},
                        {"data": "namess_orod"},
                        {"data": "stast"},
                        {"data": "prob"},
                        {"data": "comm"},
                        {"data": "total"},
                        {"data": "rdy"},
                        {"data": "zakaz"},
                        {"data": "sum_prod"},
                        {"data": "sum_ord"},
                        {"data": "info"}
                    ],
                    "aaSorting": [[1, 'asc']],
                    footerCallback: function (row, data, start, end, display) {
                        var api = this.api(), data;
                        $(api.column(0).footer()).html('Всего');

                        var sum_prod = 0.0;
                        var sum_ord = 0.0;
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                flag: 44,
                                dt1: parseDateValue2($("#dateStartOrd").val()),
                                dt2: parseDateValue2($("#dateEndOrd").val()),
                                dt1_: $("#dateStartOrd").val(),
                                dt2_: $("#dateEndOrd").val(),
                                user_id: $("#user_list_ord").val(),
                                isSum: true
                            },
                            async: false,
                            dataType: 'json',
                            success: function (res) {
                                if (res.sum_prod) {
                                    sum_prod = res.sum_prod;
                                }
                                if (res.sum_ord) {
                                    sum_ord = res.sum_ord;
                                }
                            }
                        });

                        $(api.column(11).footer()).html(sum_prod);
                        $(api.column(12).footer()).html(sum_ord);
                    }
                };

                var dTable1 = $('#example1').dataTable(default_options5);

                function refListOrdersWork(e) {
                    let all = $(e).prop('checked');
                    let str_all = all ? '&allListOrdersWork' : '';
                    dTable1.fnDestroy();
                    dTable1 = $('#example1').dataTable({
                        "iDisplayLength": 50,
                        "responsive": true,
                        "buttons": [
                            {
                                extend: 'collection',
                                text: 'Export',
                                buttons: ['pdfHtml5', 'csvHtml5', 'copyHtml5', 'excelHtml5']
                            }
                        ],
                        "ajax": {
                            "url": 'ajax_php_sql.php?flag=44' + str_all,
                            "dataSrc": ""
                        },
                        "columns": [
                            {"data": "flags"},
                            {"data": "dats", "sType": "ruDate"},
                            {"data": "ids"},
                            {"data": "namess"},
                            {"data": "namess_orod"},
                            {"data": "stast"},
                            {"data": "prob"},
                            {"data": "comm"},
                            {"data": "total"},
                            {"data": "rdy"},
                            {"data": "zakaz"},
                            {"data": "info"}
                        ],
                        "aaSorting": [[1, 'asc']]
                    });
                }

                function view_tn(id, type) {
                    if (type == 'tn') {
                        document.getElementById('fr').src = 'pg/proc/tn1.php?id=' + id;
                    }
                    if (type == 'act') {
                        document.getElementById('fr').src = 'pg/proc/act1.php?id=' + id;
                    }
                    $('#myacct').modal('show');
                }

                function selectClient(e) {
                    var opt = e.options[e.selectedIndex];

                    // Переход на Мечту
                    // if (opt.hasAttribute('data-mechta') && opt.getAttribute('data-mechta') == 1) {
                    //     document.getElementById('parent_company_a').checked = false;
                    //     document.getElementById('parent_company_m').checked = true;
                    // }
                }

                function repMail(cod, mail) {
                    if (mail != '') {
                        $.ajax({
                            type: "GET",
                            url: 'pg/_post_mail_track.php',
                            data: {
                                cod: cod,
                                mail: mail
                            },
                            success: function (data) {
                                alert("Отправлен ")
                            }
                        });
                    } else {
                        alert("Не задан eMail")
                    }
                }

                $(function () {
                    $('#tb_a tbody').on('click', 'td.details-control', function () {
                        var table = $('#tb_a').DataTable();
                        var tr = $(this).closest('tr');
                        var tdi = tr.find("i.fa");
                        var row = table.row(tr);
                        if (row.child.isShown()) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');
                            tdi.first().removeClass('fa-minus-square');
                            tdi.first().addClass('fa-plus-square');
                        } else {
                            // Open this row
                            // alert(table.row( this ).data().id)
                            id = table.row(this).data().id;
                            list_prod_num = "";
                            $.ajax({
                                type: "GET",
                                url: 'ajax_php_sql.php',
                                data: {
                                    flag: 42,
                                    id: id
                                },
                                success: function (data) {//возвращаемый результат от сервера
                                    list_prod_num = data;
                                    row.child(list_prod_num).show();
                                    tr.addClass('shown');
                                    tdi.first().removeClass('fa-plus-square');
                                    tdi.first().addClass('fa-minus-square');
                                }
                            });
                        }
                    });

                    $('#tb_p tbody').on('click', 'td.details-control', function () {
                        var table = $('#tb_p').DataTable();
                        var tr = $(this).closest('tr');
                        var tdi = tr.find("i.fa");
                        var row = table.row(tr);
                        if (row.child.isShown()) {
                            // This row is already open - close it
                            row.child.hide();
                            tr.removeClass('shown');
                            tdi.first().removeClass('fa-minus-square');
                            tdi.first().addClass('fa-plus-square');
                        } else {
                            // Open this row
                            // alert(table.row( this ).data().id_prod)
                            id = table.row(this).data().id_prod;
                            list_prod_num = "";
                            $.ajax({
                                type: "GET",
                                url: 'ajax_php_sql.php',
                                data: {
                                    flag: 43,
                                    id: id
                                },
                                success: function (data) {//возвращаемый результат от сервера
                                    list_prod_num = data;
                                    row.child(list_prod_num).show();
                                    tr.addClass('shown');
                                    tdi.first().removeClass('fa-plus-square');
                                    tdi.first().addClass('fa-minus-square');
                                }
                            });
                        }
                    });
                });

                function acct(id) {
                    location.href = 'pg/_addAcct.php?id=' + id;
                }

                function add_oplat(id_ord, id_cl) {
                    $('#client').val(id_cl);
                    $('#num').val(id_ord);
                    $('#sum').val("");
                    $("#date_opl").val('<?php echo date("Y-m-d")?>');
                    document.getElementById("view_opl1").checked = true;
                    document.getElementById("list_comm").value = "";
                    $('#add_opl').modal('show');
                }

                function _smena_cl(id) {
                    document.getElementById('smen').value = id;
                    $('#smena_cl').modal('show');
                }

                function _smena_dt(id, dt) {
                    $('#dt_sme').val(dt);
                    $('#id_dt_sme').val(id);
                    $('#smena_dt').modal('show');
                }

                function save_dt_acct() {
                    dt = $('#dt_sme').val();
                    id = $('#id_dt_sme').val();
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            dt: dt,
                            id: id,
                            flag: '226'
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            $('#smena_dt').modal('hide');
                            tb_a.DataTable().ajax.reload();
                        }
                    });
                }

                $('#myaddacct').on('shown.bs.modal', function (e) {
                    $("#search2").focus();
                });

                $('#smena_cl').on('shown.bs.modal', function (e) {
                    $("#search1").focus();
                });

                function addclient() {
                    var nodeList = document.getElementsByName('chDel');
                    var array = Array.prototype.slice.call(nodeList);
                    var array_id = '';
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            array_id = array_id + array[i].value + ",";
                        }
                    }
                    if (array_id != "") {
                        addclient1(array_id);
                        // $("#addParentCompany").modal('show');
                        /*array_id = array_id.substring(0, array_id.length - 1);
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            array_id : array_id,
                            flag : '201'
                        },
                        success:function (data) {//возвращаемый результат от сервера
                            acct(data)
                        }
                    });*/
                    } else {
                        if (document.getElementById('orderClient2').value != '-1') {
                            document.getElementById('orderClient').value = document.getElementById('orderClient2').value;
                        }
                        $('#myaddacct').modal('show');
                    }
                }

                function addclient1(array_id) {
                    // Переход на Мечту
                    // var parent_company = $("input[name=addParentCompany_parent_company]:checked").val();
                    if (!array_id) {
                        array_id = $("#copy_p").val();
                    }

                    if (array_id == "") {
                        var nodeList = document.getElementsByName('chDel');
                        var array = Array.prototype.slice.call(nodeList);
                        array_id = '';
                        for (var i = 0; i < array.length; i++) {
                            if (array[i].checked) {
                                array_id = array_id + array[i].value + ",";
                            }
                        }
                        if (array_id != "")
                            array_id = array_id.substring(0, array_id.length - 1);
                    }

                    if (array_id != "") {
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                array_id: array_id,
                                flag: '201',
                                // parent_company: parent_company
                            },
                            success: function (data) {//возвращаемый результат от сервера
                                // $("#addParentCompany").modal('hide');
                                acct(data)
                            }
                        });
                    } else {
                        alert("Не выбраны позиции для копирования!!!")
                    }
                }

                function preaddorders() {
                    // Переход на Мечту
                    /*var parent_company = $('input[name=parent_company]:checked').val();
                    if (!parent_company) {
                        alert('Произошла внутренняя ошибка: обновите страницу и повторите процедуру');
                        return;
                    }*/
                    var orderClient = document.getElementById('orderClient').value;
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            orderClient: orderClient,
                            flag: '231',
                            // Переход на Мечту
                            // parent_company: parent_company
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            data = data.trim();
                            if (data == '') {
                                addorders();
                            } else {
                                if (data == 1) {
                                    alert('Произошла внутренняя ошибка: обновите страницу и повторите процедуру');
                                    return;
                                }
                                if (data == 2) {
                                    // $('#addContract_company').val(parent_company);
                                    $('#addContract_client').val(orderClient);
                                    $('#addContract_num_doc').val('');
                                    $('#addContract_num_doc_date').val('');
                                    $('#addContract').modal('show');
                                }
                            }
                        }
                    });
                }

                function addFuncContract() {
                    var num_doc = '';
                    dateObj = new Date(document.getElementById('addContract_num_doc_date').value)
                    dateObj1 = String(('0' + dateObj.getDate()).slice(-2)) + "." + String(('0' + (dateObj.getMonth() + 1)).slice(-2)) + "." + String(dateObj.getFullYear())
                    if (document.getElementById('addContract_num_doc').value != '') {
                        num_doc = document.getElementById('addContract_num_doc').value + " от " + dateObj1;
                    } else {
                        alert('Необходимо ввести данные');
                        return;
                    }
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            orderClient: $('#addContract_client').val(),
                            flag: '232',
                            parent_company: $('#addContract_company').val(),
                            num_doc: num_doc
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            data = data.trim();
                            if (data == '') {
                                $('#addContract').modal('hide');
                                addorders();
                            } else {
                                alert('Произошла внутренняя ошибка: обновите страницу и повторите процедуру');
                                return;
                            }
                        }
                    });
                }

                function addorders() {
                    $("#lock_add").attr('disabled', true);
                    var orderClient = document.getElementById('orderClient').value;
                    var val_ = document.getElementById('val_').value;
                    var date_ = document.getElementById('date_acct').value;

                    // Переход на Мечту
                    // var parent_company = $('input[name=parent_company]:checked').val();
                    // if (!parent_company) {
                    //     alert('Произошла внутренняя ошибка: обновите страницу и повторите процедуру');
                    //     return;
                    // }

                    if ((orderClient != '') && (orderClient != '0') && (orderClient != '-1')) {
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                orderClient: orderClient,
                                val_: val_,
                                date_: date_,
                                flag: '200',
                                // Переход на Мечту
                                // parent_company: parent_company
                            },
                            success: function (data) {//возвращаемый результат от сервера
                                if (!data) {
                                    alert('Произошла внутренняя ошибка: обновите страницу и повторите процедуру');
                                } else {
                                    location.href = 'pg/_addAcct.php?id=' + data;
                                }
                            }
                        });
                    } else {
                        document.getElementById('err').style.display = 'block';
                        $("#lock_add").attr('disabled', false);
                    }
                }

                $('select.myselect').on('change', function () {
                    id = document.getElementById('orderClient2').value;
                    if (id == '-1') {
                        $("#orderClient2").val('-1').trigger('change.select2');
                        ;
                        tb_a.DataTable().ajax.url('ajax_php_sql.php?dt1=' + parseDateValue2($("#dateStart").val()) + "&" + 'dt2=' + parseDateValue2($("#dateEnd").val()) + "&" + 'dt1_=' + $("#dateStart").val() + "&" + 'dt2_=' + $("#dateEnd").val() + "&" + 'user_id=' + $("#user_list").val() + "&" + 'flag=46');
                    } else {
                        tb_a.DataTable().ajax.url('ajax_php_sql.php?flag=38&id_cl=' + id_cl);
                    }
                    tb_a.DataTable().ajax.reload();
                    tb_p.DataTable().ajax.url('ajax_php_sql.php?flag=39&id_cl=' + id_cl);
                    tb_p.DataTable().ajax.reload();
                    tb_o.DataTable().ajax.url('ajax_php_sql.php?flag=40&id_cl=' + id_cl);
                    tb_o.DataTable().ajax.reload();
                    tb_t.DataTable().ajax.url('ajax_php_sql.php?flag=41&id_cl=' + id_cl);
                    tb_t.DataTable().ajax.reload();
                    $("#tb_post").DataTable().ajax.url('ajax_php_sql.php?flag=48&id_cl=' + id_cl);
                    $("#tb_post").DataTable().ajax.reload();
                });

                jQuery(function ($) {
                    var checklist = [];
                    $(document).on('change', '.chcopy_', function () {
                        var val = this.value | 0; // to int
                        if (this.checked) {
                            checklist.push(val); // если в начало, то .ushift(val)
                        } else {
                            var idx = $.inArray(val, checklist);
                            if (idx > -1) {
                                checklist.splice(idx, 1);
                            }
                        }
                        $('#copy_p').val(checklist.join(','));
                    });
                });

                function _prod_rdy() {
                    var nodeList = document.getElementsByName('chjob');
                    var array = Array.prototype.slice.call(nodeList);
                    str_id = "";
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            str_id = str_id + array[i].value + ",";
                        }
                    }
                    str_id = str_id.slice(0, -1);
                    if (str_id != "") {
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                id1: str_id,
                                flag: '203'
                            },
                            success: function (data) {//возвращаемый результат от сервера
                                dTable1.fnClearTable();
                                dTable1.DataTable().ajax.reload();
                            }
                        });
                    } else {
                        alert('не выбраны продукты!')
                    }
                }

                function _prod_brak() {
                    var nodeList = document.getElementsByName('chjob');
                    var array = Array.prototype.slice.call(nodeList);
                    str_id = "";
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            str_id = str_id + array[i].value + ",";
                        }
                    }
                    str_id = str_id.slice(0, -1);
                    if (str_id != "") {
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                id1: str_id,
                                flag: '223'
                            },
                            success: function (data) {//возвращаемый результат от сервера
                                dTable1.fnClearTable();
                                dTable1.DataTable().ajax.reload();
                            }
                        });
                    } else {
                        alert('не выбраны продукты!')
                    }
                }

                function info_acct(id) {
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            id: id,
                            flag: '3'
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            document.getElementById('info_prod').innerHTML = "";
                            document.getElementById('info_prod').innerHTML = data;
                        }
                    });
                    $('#filesa').modal('show');
                }

                function save_prod() {
                    today = '<?echo date("Y-m-d")?>';
                    d1 = new Date(document.getElementById('p_dates_time').value);
                    d2 = new Date(today);
                    if (d1 < d2) {
                        alert("Дата сдачи не может быть меньше текущей!")
                        return
                    }
                    var form = $('#forms')[0];
                    var datass = new FormData(form);
                    $.ajax({
                        type: "POST",
                        enctype: 'multipart/form-data',
                        url: 'ajax_php_sql_post.php',
                        data: datass,
                        processData: false,
                        contentType: false,
                        cache: false,
                        success: function (data) {
                            $('#filesa').modal('hide');
                            dTable1.fnClearTable();
                            dTable1.DataTable().ajax.reload();
                        }
                    });
                }

                function del_file(put, pol) {
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            put: put,
                            flag: '5'
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            document.getElementById(pol).style.display = 'none';
                        }
                    });
                }

                $(".close").click(function () {
                    document.getElementById('err').style.display = 'none';
                });

                function _add_plan_job() {
                    var nodeList = document.getElementsByName('chjob');
                    var array = Array.prototype.slice.call(nodeList);
                    str_id = "";
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            str_id = str_id + array[i].value + ",";
                        }
                    }
                    str_id = str_id.slice(0, -1);
                    if (str_id != "") {
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                id1: str_id,
                                flag: '204'
                            },
                            success: function (data) {//возвращаемый результат от сервера
                                $("#datepj_").val('<?php echo date("Y-m-d")?>');
                                document.getElementById('pj').innerHTML = data;
                                $('#planJob').modal('show');
                            }
                        });
                    } else {
                        alert('не выбраны продукты!')
                    }
                }

                function _add_plan_job2() {
                    var nodeList = document.getElementsByName('chjob2');
                    var array = Array.prototype.slice.call(nodeList);
                    srt_pj = "";
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            srt_pj = srt_pj + array[i].value + "^" + document.getElementById('total' + array[i].value).value + "|";
                        }
                    }
                    srt_pj = srt_pj.slice(0, -1);
                    if (srt_pj != "") {
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                dates: document.getElementById('datepj_').value,
                                id1: srt_pj,
                                flag: '206'
                            },
                            success: function (data) {//возвращаемый результат от сервера
                                alert("Заказ отправлен!")
                                $('#planJob').modal('hide');
                                $("input[name='chjob']").prop('checked', false);
                            }
                        });
                    } else {
                        alert('не выбраны продукты!')
                    }
                }

                function _add_plan_job3() {
                    $("#datepj1_").val('<?echo date("Y-m-d")?>');
                    document.getElementById('pj1').innerHTML = "";
                    $('#planJob1').modal('show');
                }

                function _add_plan_job4() {
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            dates: document.getElementById('datepj1_').value,
                            flag: '205'
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            var url = 'pg/modeler.php?dFile=' + JSON.stringify("../tmp_files/work_plan.xls");
                            location.href = url;
                            document.getElementById('pj1').innerHTML = "";
                            document.getElementById('pj1').innerHTML = data;
                            $('#planJob1').modal('show');
                        }
                    });
                }

                function _add_plan_job5() {
                    var nodeList = document.getElementsByName('chjob3');
                    var array = Array.prototype.slice.call(nodeList);
                    srt_pj = "";
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            srt_pj = srt_pj + document.getElementById('id_zakaz' + array[i].value).value + "^" + array[i].value + "^" + document.getElementById('total' + array[i].value).value + "|";
                        }
                    }
                    srt_pj = srt_pj.slice(0, -1);
                    if (srt_pj != "") {
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                dates: document.getElementById('datepj1_').value,
                                id1: srt_pj,
                                flag: '206'
                            },
                            success: function (data) {//возвращаемый результат от сервера
                                alert("Заказ отправлен!")
                                $('#planJob1').modal('hide');
                            }
                        });
                    } else {
                        alert('не выбраны продукты!')
                    }
                }

                function downloadCSV(args) {
                    var data, filename, link;
                    var csv = "";
                    csv = csv + "Дата сдачи; Номер заказа; Клиент; Продукт;Статус ; Кол-во; Цена за ед.; Сумма ;Размер;Переплет;Сторона;Наим. части;Размер изделия;Кол-во стр;Оборудование;Цвет;Бумага;Кол-во листов;Резка;Ламинирование;Биговка;Перфорация;Скругление углов;Отверстия;Кол-во отверстий;Люверс;Кол-во люверсов;Вырубка;Конгрев;Тиснение;\n";
                    $.getJSON("ajax_php_sql.php?flag=45", function (data) {
                        for (var i in data) {
                            csv = csv + data[i].dats + ";" + data[i].ids + ";" + data[i].namess + ";" + data[i].namess_orod + ";" + data[i].stast + ";" + data[i].total + ";" + data[i].rdy + ";" + data[i].zakaz + ";" + data[i].temp + ";\n"
                        }
                        if (csv == null) return;
                        filename = args.filename || 'export.csv';
                        if (!csv.match(/^data:text\/csv/i)) {
                            csv = 'data:text/csv;charset=utf-8,\uFEFF' + csv;
                        }
                        data = encodeURI(csv);
                        link = document.createElement('a');
                        link.setAttribute('href', data);
                        link.setAttribute('download', filename);
                        link.click();
                    });
                }

                $(document).ready(function () {
                    $("#date_acct").val('<?php echo date("Y-m-d")?>');
                });

                function parseDateValue(rawDate) {
                    var dateArray = rawDate.split(".");
                    var parsedDate = dateArray[2] + dateArray[1] + dateArray[0];
                    return parsedDate;
                }

                function parseDateValue1(rawDate) {
                    var dateArray = rawDate.split("/");
                    var parsedDate = dateArray[2] + dateArray[1] + dateArray[0];
                    return parsedDate;
                }

                function parseDateValue2(rawDate) {
                    var dateArray = rawDate.split("/");
                    var parsedDate = dateArray[2] + "-" + dateArray[1] + "-" + dateArray[0];
                    return parsedDate;
                }

                $(function () {
                    // $('.datepicker').datepicker();
                    $('.datepicker').datepicker({
                        dateFormat: 'dd/mm/yy',
                        firstDay: 1,
                        onSelect: function (dateText, inst) {
                            sum_acct = 0;
                            sum_tn = 0;
                            tb_a.fnClearTable();
                            $("#orderClient2").val('-1').trigger('change.select2');
                            ;
                            $('.selectpicker').selectpicker('refresh');
                            tb_a.DataTable().ajax.url('ajax_php_sql.php?dt1=' + parseDateValue2($("#dateStart").val()) + "&" + 'dt2=' + parseDateValue2($("#dateEnd").val()) + "&" + 'dt1_=' + $("#dateStart").val() + "&" + 'dt2_=' + $("#dateEnd").val() + "&" + 'user_id=' + $("#user_list").val() + "&" + 'flag=46');
                            tb_a.DataTable().ajax.reload();
                            tb_p.DataTable().ajax.url('ajax_php_sql.php?flag=39&id_cl=' + id_cl);
                            tb_p.DataTable().ajax.reload();
                            tb_o.DataTable().ajax.url('ajax_php_sql.php?flag=40&id_cl=' + id_cl);
                            tb_o.DataTable().ajax.reload();
                            tb_t.DataTable().ajax.url('ajax_php_sql.php?flag=41&id_cl=' + id_cl);
                            tb_t.DataTable().ajax.reload();
                            $("#tb_post").DataTable().ajax.url('ajax_php_sql.php?flag=48&id_cl=' + id_cl);
                            $("#tb_post").DataTable().ajax.reload();

                            dTable1.fnClearTable();
                            dTable1.DataTable().ajax.url('ajax_php_sql.php?dt1=' + parseDateValue2($("#dateStartOrd").val()) + '&dt2=' + parseDateValue2($("#dateEndOrd").val()) + '&dt1_=' + $("#dateStartOrd").val() + '&dt2_=' + $("#dateEndOrd").val() + '&user_id=' + $("#user_list_ord").val() + '&flag=44');
                            dTable1.DataTable().ajax.reload();
                        }
                    });
                });

                function crt() {
                    tb_a.fnClearTable();
                    $("#orderClient2").val('-1').trigger('change.select2');
                    ;
                    $('.selectpicker').selectpicker('refresh');
                    tb_a.DataTable().ajax.url('ajax_php_sql.php?dt1=' + parseDateValue2($("#dateStart").val()) + "&" + 'dt2=' + parseDateValue2($("#dateEnd").val()) + "&" + 'dt1_=' + $("#dateStart").val() + "&" + 'dt2_=' + $("#dateEnd").val() + "&" + 'user_id=' + $("#user_list").val() + "&" + 'flag=46');
                    tb_a.DataTable().ajax.reload();
                    tb_p.DataTable().ajax.url('ajax_php_sql.php?flag=39&id_cl=' + id_cl);
                    tb_p.DataTable().ajax.reload();
                    tb_o.DataTable().ajax.url('ajax_php_sql.php?flag=40&id_cl=' + id_cl);
                    tb_o.DataTable().ajax.reload();
                    tb_t.DataTable().ajax.url('ajax_php_sql.php?flag=41&id_cl=' + id_cl);
                    tb_t.DataTable().ajax.reload();
                    $("#tb_post").DataTable().ajax.url('ajax_php_sql.php?flag=48&id_cl=' + id_cl);
                    $("#tb_post").DataTable().ajax.reload();
                }

                function crt_ord() {
                    dTable1.fnClearTable();
                    dTable1.DataTable().ajax.url('ajax_php_sql.php?dt1=' + parseDateValue2($("#dateStartOrd").val()) + '&dt2=' + parseDateValue2($("#dateEndOrd").val()) + '&dt1_=' + $("#dateStartOrd").val() + '&dt2_=' + $("#dateEndOrd").val() + '&user_id=' + $("#user_list_ord").val() + '&flag=44');
                    dTable1.DataTable().ajax.reload();
                }

                function format(id) {
                    return tb_p.id_prod;
                }

                jQuery.browser = {};
                (function () {
                    jQuery.browser.msie = false;
                    jQuery.browser.version = 0;
                    if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
                        jQuery.browser.msie = true;
                        jQuery.browser.version = RegExp.$1;
                    }
                })();

                function fun_opl() {
                    if (document.getElementById('sum').value == "0" || document.getElementById('sum').value == "") {
                        alert("Внесите сумму оплаты!")
                        return
                    }
                    client = document.getElementById('client').value;
                    num = document.getElementById('num').value;
                    sum = document.getElementById('sum').value;
                    date = document.getElementById('date_opl').value;
                    list_comm = document.getElementById('list_comm').value;
                    var tableElem = document.getElementById('radio_chk');
                    var elements = tableElem.getElementsByTagName('input');
                    for (var i = 0; i < elements.length; i++) {
                        if (elements[i].checked == true) {
                            var view_opl1 = elements[i].value
                        }
                    }
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            client: client,
                            num: num,
                            sum: sum,
                            date: date,
                            view_opl: view_opl1,
                            flags: "1",
                            flag: '208'
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            $('#add_opl').modal('hide');
                            $("#orderClient2").val('-1').trigger('change.select2');
                            $('.selectpicker').selectpicker('refresh');
                            tb_a.DataTable().ajax.url('ajax_php_sql.php?dt1=' + parseDateValue2($("#dateStart").val()) + "&" + 'dt2=' + parseDateValue2($("#dateEnd").val()) + "&" + 'dt1_=' + $("#dateStart").val() + "&" + 'dt2_=' + $("#dateEnd").val() + "&" + 'user_id=' + $("#user_list").val() + "&" + 'flag=46');
                            tb_a.DataTable().ajax.reload();
                            tb_p.DataTable().ajax.url('ajax_php_sql.php?flag=39&id_cl=' + id_cl);
                            tb_p.DataTable().ajax.reload();
                            tb_o.DataTable().ajax.url('ajax_php_sql.php?flag=40&id_cl=' + id_cl);
                            tb_o.DataTable().ajax.reload();
                            tb_t.DataTable().ajax.url('ajax_php_sql.php?flag=41&id_cl=' + id_cl);
                            tb_t.DataTable().ajax.reload();
                            $("#tb_post").DataTable().ajax.url('ajax_php_sql.php?flag=48&id_cl=' + id_cl);
                            $("#tb_post").DataTable().ajax.reload();
                        }
                    });
                }

                function sscm() {
                    id_acct = document.getElementById('smen').value;
                    id_cl = document.getElementById('orderClient1').value;
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            id_acct: id_acct,
                            id_cl: id_cl,
                            flag: '2'
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            $('#smena_cl').modal('hide');
                            tb_a.fnClearTable();
                            //tb_a.DataTable().ajax.url('orders_json.php?dt1=' + parseDateValue2($("#dateStart").val()) + "&" +' dt2=' + parseDateValue2($("#dateEnd").val()) + "&" + 'user_id=' + $("#user_list").val());
                            //tb_a.DataTable().ajax.reload();
                            tb_p.DataTable().ajax.url('ajax_php_sql.php?flag=39&id_cl=' + id_cl);
                            tb_p.DataTable().ajax.reload();

                            tb_o.DataTable().ajax.url('ajax_php_sql.php?flag=40&id_cl=' + id_cl);
                            tb_o.DataTable().ajax.reload();

                            tb_t.DataTable().ajax.url('ajax_php_sql.php?flag=41&id_cl=' + id_cl);
                            tb_t.DataTable().ajax.reload();

                            $("#tb_post").DataTable().ajax.url('ajax_php_sql.php?flag=48&id_cl=' + id_cl);
                            $("#tb_post").DataTable().ajax.reload();
                        }
                    });
                }

                function refr() {
                    $("#orderClient2").val('-1').trigger('change.select2');
                    ;
                    $('.selectpicker').selectpicker('refresh');
                    tb_a.DataTable().ajax.url('ajax_php_sql.php?dt1=' + parseDateValue2($("#dateStart").val()) + "&" + 'dt2=' + parseDateValue2($("#dateEnd").val()) + "&" + 'dt1_=' + $("#dateStart").val() + "&" + 'dt2_=' + $("#dateEnd").val() + "&" + 'user_id=' + $("#user_list").val() + "&" + 'flag=46');
                    tb_a.DataTable().ajax.reload();
                    tb_p.DataTable().ajax.url('ajax_php_sql.php?flag=39&id_cl=-1');
                    tb_p.DataTable().ajax.reload();
                    tb_o.DataTable().ajax.url('ajax_php_sql.php?flag=40&id_cl=-1');
                    tb_o.DataTable().ajax.reload();
                    tb_t.DataTable().ajax.url('ajax_php_sql.php?flag=41&id_cl=-1');
                    tb_t.DataTable().ajax.reload();
                    $("#tb_post").DataTable().ajax.url('ajax_php_sql.php?flag=48&id_cl=-1');
                    $("#tb_post").DataTable().ajax.reload();
                }

                function tran_() {
                    var nodeList = document.getElementsByName('tran');
                    var array = Array.prototype.slice.call(nodeList);
                    str_id = "";
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            if ($("#new_acct" + array[i].value).val() != "") {
                                str_id = str_id + array[i].value + "," + $("#new_acct" + array[i].value).val() + "|";
                            } else {
                                alert("Не задан номер счета для продукта " + document.getElementById('namsse' + array[i].value).innerHTML)
                            }
                        }
                    }
                    str_id = str_id.slice(0, -1);
                    if (str_id != '') {
                        //alert(str_id)
                        $.ajax({
                            type: "GET",
                            url: 'ajax_php_sql.php',
                            data: {
                                str_id: str_id,
                                flag: '33'
                            },
                            success: function (data) {//возвращаемый результат от сервера
                                //alert(data)
                                window.location.reload();
                            }
                        });
                    } else {
                        alert("Не выбраны позиции для перемещения!")
                    }
                }

                function add_post() {
                    var kol = 1;
                    var current;
                    var str = "";
                    var tbl = document.getElementById('post_inf');                   // таблица, с которой работаем
                    var rws = tbl.rows;                                            // коллекция существующих строк таблицы
                    var lst = rws [rws.length - 1];
                    var cls = lst.cells.length;
                    for (var t = kol; t <= document.getElementById('post_kol').value; t++) {
                        current = document.getElementById('post_opis' + kol).value;
                        str = str + current;
                        str = str.concat("$");
                        current = document.getElementById('post_mest' + kol).value;
                        str = str + current;
                        str = str.concat("$");
                        current = document.getElementById('post_ves' + kol).value;
                        str = str + current;
                        str = str.concat("$");
                        current = document.getElementById('post_dl' + kol).value;
                        str = str + current;
                        str = str.concat("$");
                        current = document.getElementById('post_wir' + kol).value;
                        str = str + current;
                        str = str.concat("$");
                        current = document.getElementById('post_vis' + kol).value;
                        str = str + current;
                        str = str.concat("!");
                        kol++;
                    }
                    var nodeList = document.getElementsByName('chDel');
                    var array = Array.prototype.slice.call(nodeList);
                    var array_id = '';
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            array_id = array_id + array[i].value + ",";
                        }
                    }
                    if (array_id != "") {
                        array_id = array_id.substring(0, array_id.length - 1);
                    }
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            flag: '47',
                            id_ord: '0',
                            post_fio: $('#post_fio').val(),
                            region_id: $('#region_id').val(),
                            view_post: $('#view_post').val(),
                            post_city: $('#post_city').val(),
                            post_raion: $('#post_raion').val(),
                            post_street: $('#post_street').val(),
                            post_house_num: $('#post_house_num').val(),
                            post_house_kor: $('#post_house_kor').val(),
                            post_room: $('#post_room').val(),
                            post_index: $('#post_index').val(),
                            post_phone: $('#post_phone').val(),
                            post_price: $('#post_price').val(),
                            post_track: $('#post_track').val(),
                            post_date: $('#post_date').val(),
                            str: str,
                            goods: array_id
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            alert('Доставка добавлена');
                            window.open('pg/proc/post.php?id=' + data, '_blank');
                            $('#form_post').modal('hide')
                        }
                    });
                }

                function view_post(id) {
                    $.ajax({
                        type: "GET",
                        url: 'ajax_php_sql.php',
                        data: {
                            flag: '216',
                            id_ord: id
                        },
                        success: function (data) {//возвращаемый результат от сервера
                            par = data.split('|');
                            //CLIENT_NAME, PHONE_CITY,post_post_index, post_post_kv, post_post_kor, post_post_street, post_post_city, post_region_id
                            console.log(par)
                            $('#post_fio').val(par[0]);
                            $('#post_raion').val(par[13]);
                            $('#region_id').val(par[7]);
                            $('#view_post').val();
                            $('#post_city').val(par[6]);
                            $('#post_street').val(par[5]);
                            $('#post_house_num').val(par[8]);
                            $('#post_house_kor').val(par[4]);
                            $('#post_room').val(par[3]);
                            $('#post_index').val(par[2]);
                            $('#post_phone').val(par[1]);
                            $('#post_price').val('')
                            $("#post_date").val('<?php echo date("Y-m-d")?>');
                            if (par[9] != '') {
                                document.getElementById('info_dost').innerHTML = "<br>Адрес: " + par[9] + " <br> телефон: " + par[10];
                            }
                            document.getElementById('post_track').innerHTML = par[11];
                        }
                    });
                }


                function addRow_post() {
                    var tbl = document.getElementById('post_inf');                   // таблица, с которой работаем
                    var rws = tbl.rows;                                            // коллекция существующих строк таблицы
                    var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
                    var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
                    //console.log(cls);
                    var ro = tbl.insertRow(-1);                                   // добавляем снизу ОБРАЗ ещё одной строки
                    var kol = document.getElementById('post_kol').value;
                    kol = Number(kol) + 1;
                    document.getElementById('post_kol').value = kol;
                    var ce = ro.insertCell(-1);
                    ce.innerHTML = "<input type='checkbox' name='post_chk' value='1'>";
                    ce = ro.insertCell(-1);
                    ce.innerHTML = "<input name='post_opis" + kol + "' id = 'post_opis" + kol + "' type='text' value=''  class='form-control'/>";
                    ce = ro.insertCell(-1);
                    ce.innerHTML = "<input name='post_mest" + kol + "' id = 'post_mest" + kol + "' type='text'  class='form-control' />";
                    ce = ro.insertCell(-1);
                    ce.innerHTML = "<input name='post_ves" + kol + "' id = 'post_ves" + kol + "' type='text'  class='form-control' />";
                    ce = ro.insertCell(-1);
                    ce.innerHTML = "<input name='post_dl" + kol + "' id = 'post_dl" + kol + "' type='text'  class='form-control'/>";
                    ce = ro.insertCell(-1);
                    ce.innerHTML = "<input name='post_wir" + kol + "' id = 'post_wir" + kol + "' type='text'  class='form-control' />";
                    ce = ro.insertCell(-1);
                    ce.innerHTML = "<input name='post_vis" + kol + "' id = 'post_vis" + kol + "' type='text'  class='form-control'/>";
                }

                function del_row_post() {
                    var nodeList = document.getElementsByName('post_chk');
                    var array = Array.prototype.slice.call(nodeList);
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            var tr = array[i].parentNode.parentNode;
                            var parent = tr.parentNode
                            parent.removeChild(tr);
                        }
                    }
                }

                $('#post').bind('click', function () {
                    $('#post_fio').val('');
                    $('#region_id').val('');
                    $('#view_post').val('');
                    $('#post_city').val('');
                    $('#post_raion').val('');
                    $('#post_street').val('');
                    $('#post_house_num').val('');
                    $('#post_house_kor').val('');
                    $('#post_room').val('');
                    $('#post_index').val('');
                    $('#post_phone').val('');
                    $('#post_price').val('');
                    //	$('#post_track').val('');
                    if ($('#post_kol').val() > '0') {
                        $("input[name='post_chk']").prop('checked', true);
                        del_row_post();
                    }
                    $('#post_kol').val('0')
                    document.getElementById("info_dost").innerHTML = '';
                    var last_id = '0';
                    var nodeList = document.getElementsByName('chcopy_p');
                    var array = Array.prototype.slice.call(nodeList);
                    var array_id = '';
                    for (var i = 0; i < array.length; i++) {
                        if (array[i].checked) {
                            if (last_id == 0 || last_id == "0") {
                                last_id = array[i].value
                            }
                            array_id = array_id + array[i].value + ",";
                        }
                    }
                    if (array_id != "") {
                        view_post(last_id);
                        $('#form_post').modal('show')
                    } else {
                        alert("Выберите продукт!!!")
                    }
                });


                jQuery.extend(jQuery.fn.dataTableExt.oSort, {
                    "ruDate-asc": function (a, b) {
                        var ruDatea = $.trim(a).split('.');
                        var ruDateb = $.trim(b).split('.');

                        if (ruDatea[2] * 1 < ruDateb[2] * 1)
                            return 1;
                        if (ruDatea[2] * 1 > ruDateb[2] * 1)
                            return -1;
                        if (ruDatea[2] * 1 == ruDateb[2] * 1) {
                            if (ruDatea[1] * 1 < ruDateb[1] * 1)
                                return 1;
                            if (ruDatea[1] * 1 > ruDateb[1] * 1)
                                return -1;
                            if (ruDatea[1] * 1 == ruDateb[1] * 1) {
                                if (ruDatea[0] * 1 < ruDateb[0] * 1)
                                    return 1;
                                if (ruDatea[0] * 1 > ruDateb[0] * 1)
                                    return -1;
                            } else
                                return 0;
                        }
                    },

                    "ruDate-desc": function (a, b) {
                        var ruDatea = $.trim(a).split('.');
                        var ruDateb = $.trim(b).split('.');

                        if (ruDatea[2] * 1 < ruDateb[2] * 1)
                            return -1;
                        if (ruDatea[2] * 1 > ruDateb[2] * 1)
                            return 1;
                        if (ruDatea[2] * 1 == ruDateb[2] * 1) {
                            if (ruDatea[1] * 1 < ruDateb[1] * 1)
                                return -1;
                            if (ruDatea[1] * 1 > ruDateb[1] * 1)
                                return 1;
                            if (ruDatea[1] * 1 == ruDateb[1] * 1) {
                                if (ruDatea[0] * 1 < ruDateb[0] * 1)
                                    return -1;
                                if (ruDatea[0] * 1 > ruDateb[0] * 1)
                                    return 1;
                            } else
                                return 0;
                        }
                    }
                });

            </script>

            <footer class="footer">
                <p>&copy; Company 2016</p>
            </footer>
</body>

</html>
