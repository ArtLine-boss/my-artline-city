var globalSend = {}

$(document).ready(function () {
    $('.js-example-basic-single').select2();
});

//поиск клиента
function searchClientInDB() {
    var _val = (document.getElementById('name_client').value != '') ? document.getElementById('name_client').value : '';
    sendAjax('m=calculator&u=create&a=AjaxSearchClient', {'name_client': _val}, 'initClient');
}

//инициализация клиента
function initClient(data) {
    if(!data || !Array.isArray(data) || data.length == 0) {
        MessageBox('Клиент не найден');
        return;
    }
    if(data.length == 1) {
        document.getElementById('name_client').value = data[0].CLIENT_NAME;
        document.getElementById('id_client').value = data[0].ID;
        return;
    }
    CreateModalWindow('selectClient', 'Выберите клиента');
    var fields = {
        'CLIENT_NAME': 'Наименование',
        'PHONE_MOB': 'Мобильный телефон',
        'PHONE_CITY': 'Рабочий телефон',
        'UNP': 'УНП',
    }
    createTable(document.getElementById('selectClient-body'), fields, data, null, 'clickSelectClient');
}

//выбор клиента
function clickSelectClient(data) {
    $('#modal_selectClient').modal('hide');
    if(!data)
        return;
    document.getElementById('name_client').value = data.CLIENT_NAME;
    document.getElementById('id_client').value = data.ID;
}

//вызов окна выбора файла
function clickFile(id) {
    if(document.getElementById("file" + id))
        document.getElementById("file" + id).click();
}

//загрузка файла
function uploadFile(e) {
    var key_ = e.id;
    //диалоговое окно для выбора файла
    $.each( e.files, function( key, value ){
        //проверяем имена файлов
        var filename = value.name;
        //убираем расширение
        filename = filename.split("").reverse().join("");
        filename = filename.substr(filename.indexOf(".") + 1);
        filename = filename.split("").reverse().join("");
        //проверяем длину
        /*if(filename.length > 20) {
            MessageBox("Длина имени файла не должна превышать 20 символов", false);
            return;
        }
        //проверяем на латинские буквы, цифры, нижнее подчеркивание и тире
        var re = new RegExp('[^a-zA-Z0-9_-]');
        if(re.test(filename)) {
            MessageBox("Название файла не соответствует принятым стандартам. Длина имени файла не должна превышать 20 символов. В имени файла допустимы цифры, латинские буквы, символ нижнего подчеркивания (_) и символ тире (-)", true);
            return;
        }*/
        //получаем дату файла
        var dt = new Date(value.lastModified);
        var str_date = "" + dt.getDate();
        if(str_date.length == 1)
            str_date = "0" + str_date;
        var str_month = "" + (dt.getMonth() + 1);
        if(str_month.length == 1)
            str_month = "0" + str_month;
        var str_dt = str_date + "." + str_month + "." + dt.getFullYear() + " " + dt.getHours() + ":" + dt.getMinutes();
        //объект
        var obj = {
            value: value,
            filename: value.name,
            datetime: str_dt,
            size: (Math.ceil(value.size/1024)) + "КБ"
        }
        var tmp_arr = (eval("globalSend." + key_)) ? eval("globalSend." + key_) : eval("globalSend." + key_ + "=new Array()");
        eval("globalSend." + key_ + ".push(obj)");
        var id = key_.substr(4);
        addTableFile(id, key_);
    });

    e.value = "";
}

//добавление строки при добавлении файлов
function addTableFile(id, key_) {
    deleteFooterDOM(document.getElementById("tablefile" + id).getElementsByTagName('tbody')[0]);

    var tmp_arr = eval("globalSend." + key_);

    for(var i = 0; i < tmp_arr.length; i++) {
        var tr = document.createElement('tr');
        document.getElementById("tablefile" + id).getElementsByTagName('tbody')[0].appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode(tmp_arr[i].filename));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode(tmp_arr[i].size));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode(tmp_arr[i].datetime));
        tr.appendChild(td);
        var td = document.createElement('td');
        tr.appendChild(td);
        var i_ = document.createElement('i');
        i_.setAttribute('class', "fa fa-trash");
        i_.setAttribute('onclick', "deleteFileInList('" + key_ + "','" + i + "')");
        td.appendChild(i_);
    }
}

//удаление файла из списка
function deleteFileInList(key, num) {
    if(!key || num < 0)
        return;
    if(eval("globalSend." + key + "[" + num + "]")) {
        var arr = eval("globalSend." + key);
        eval("globalSend." + key + "=new Array()");
        for(var i = 0; i < arr.length; i++) {
            if(i != num)
                eval("globalSend." + key + ".push(arr[i])");
        }

    }
    var id = key.substr(4);
    addTableFile(id, key);
}

//сохраняем
function clickSave() {
    var msg = '';
    $("input[name^=p_dates_time]").each(function () {
        if(this.value == '') {
            msg = 'Не заполнено поле "Дата сдачи"';
            return;
        }
        var d1 = new Date();
        var d2 = new Date(this.value);
        if(d1.getTime() >= d2.getTime()) {
            msg = 'Не верно заполнено поле "Дата сдачи": дата сдачи не может быть меньше текущей';
            return;
        }
    });

    $("select[name^=codeStat]").each(function () {
        if(this.value == -1) {
            msg = 'Не заполнено поле "Наименование"';
            return;
        }
    });

    if(!document.getElementById('p_order_id').value && parseInt(document.getElementById('id_client').value) <= 0) {
        msg = 'Выберите клиента';
    }

    if(msg != '') {
        MessageBox(msg, true);
    }

    var order_id = document.getElementById('p_order_id').value;

    $("input[id^=file]").each(function () {
        var datas = new FormData();
        if(eval('globalSend.' + this.id)) {
            var fls =eval('globalSend.' + this.id);
            $.each(fls, function(key, value) {
                datas.append(key, value.value);
            });
        }
        var id_calc = this.id.substr(4);

        var get_ = {
            'id_calc': id_calc,
            'p_dates_time': document.getElementById('p_dates_time' + id_calc).value,
            'codeStat': document.getElementById('codeStat' + id_calc).value,
            'p_name': document.getElementById('p_name' + id_calc).value,
            'order_id': order_id,
            'id_client': parseInt(document.getElementById('id_client').value),
            'firmParent': parseInt(document.getElementById('firmParent').value),
            'flagNotWork': (document.getElementById('p_order_not_work').checked) ? 1 : 0,
        }
        order_id = sendAjaxForFile('/www/core/ajax.php?m=calculator&u=orderToWork&a=AjaxToWork', get_, datas, true);
    });

    if(order_id && !document.getElementById('messageAlert'))
        document.location = '/pages/pg/_addAcct.php?id='  + order_id;
}