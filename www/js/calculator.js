$(document).ready(function() {
    sendAjax('m=calculator&a=AjaxCalculator', null, 'viewDataCalc');
});

//отображение расчетов
function viewDataCalc(data) {
    if(!data)
        return;

    if(data.OrdersUser) {
        addTRsInTable(data.OrdersUser, 'data_table');
    }

    if(data.OrdersNotUser) {
        addTRsInTable(data.OrdersNotUser, 'data_table_notUser', '/www/index.php?m=calculator&u=create', 'id');
    }
    if(data.OrdersAll) {
        addTRsInTable(data.OrdersAll, 'data_table_all', '/www/index.php?m=calculator&u=create', 'id');
    }
}

//отправка в работу
function toWork() {
    var works = $("input[name=check_add]:checked");
    if(works.length == 0) {
        MessageBox('Не выбраны расчеты', true);
        return;
    }
    var send = Array();
    for(var i = 0; i < works.length; i++) {
        send.push(works[i].value);
    }
    location.href = '?m=calculator&u=orderToWork&dt=' + JSON.stringify(send);
}

//удаление скопом
function deleteAllChecked() {
    var del = $("input[name=check_add]:checked");
    if(del.length == 0) {
        MessageBox('Не выбраны расчеты', true);
        return;
    }
    var del_arr = Array();
    for(var i = 0; i < del.length; i++) {
        del_arr.push(del[i].value);
    }
    deleteRowInTable(del_arr, 'm=calculator&a=AjaxDeleteCalc');
}

//открываем или скрываем фильтры
function openWindowFilter(id, e) {
    id = 'windowFilter' + id;
    if(!document.getElementById(id))
        return;
    document.getElementById(id).style.display = document.getElementById(id).style.display == 'flex' ? 'none' : 'flex';

    var _i = e.getElementsByTagName('i');
    _i[0].setAttribute('class', (_i[0].getAttribute('class') == "fa fa-caret-square-o-right" ? "fa fa-caret-square-o-down" : "fa fa-caret-square-o-right"));
}

//фильтруем данные
function FilterData(id) {
    var CalcFilter = 0;
    switch (id) {
        case 'Calc':
            CalcFilter = 1;
            break;
        case 'NotCalc':
            CalcFilter = 2;
            break;
        case 'AllCalc':
            CalcFilter = 3;
            break;
    }
    var data = {
        'NameCalc': $("#filter_name_" + id).val(),
        'NameClient': $("#filter_name_client_" + id).val(),
        'StartDate': $("#start_date_" + id).val(),
        'EndDate': $("#end_date_" + id).val(),
        'CalcFilter': CalcFilter
    }
    sendAjax('m=calculator&a=AjaxFilterCalculator', data, 'viewDataCalc');
}

//сброс фильтра
function resetFilter(id) {
    var CalcFilter = 0;
    switch (id) {
        case 'Calc':
            CalcFilter = 1;
            break;
        case 'NotCalc':
            CalcFilter = 2;
            break;
        case 'AllCalc':
            CalcFilter = 3;
            break;
    }
    var data = {
        'CalcFilter': CalcFilter,
    }
    sendAjax('m=calculator&a=AjaxFilterCalculator', data, 'viewDataCalc');

    //чистим данные
    $("#filter_name_" + id).val('');
    $("#filter_name_client_" + id).val('');
    $("#start_date_" + id).val('');
    $("#end_date_" + id).val('');
}