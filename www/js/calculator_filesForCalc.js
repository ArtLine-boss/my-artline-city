var globalSend = new Array();

function deleteFileInDir(e, path_name) {
    if(!path_name) {
        MessageBox("Не задан путь к файлу", true);
        return;
    }
    if(confirm('Файл уже сохранен. Удалить?')) {
        if(sendAjax('m=ajaxs&a=deleteFileByPath', {'path': path_name}, null, false, true)) {
            let parent = getParentNode(e, 'tr');
            if (parent) {
                remove(parent);
            }
        }
    }
}

function downloadFileInDir(e, path_name) {
    FileForceDownload(path_name);
}

//вызов окна выбора файла
function clickFile() {
    document.getElementById("lfile").click();
}

//загрузка файла
function uploadFile(e) {
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
        if(!globalSend) {
            globalSend = new Array();
        }
        globalSend.push(obj);
        addTableFile();
    });

    e.value = "";
}

//добавление строки при добавлении файлов
function addTableFile() {
    deleteFooterDOM(document.getElementById("tableloadfile").getElementsByTagName('tbody')[0]);

    for(var i = 0; i < globalSend.length; i++) {
        var tr = document.createElement('tr');
        document.getElementById("tableloadfile").getElementsByTagName('tbody')[0].appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode(globalSend[i].filename));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode(globalSend[i].size));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode(globalSend[i].datetime));
        tr.appendChild(td);
        var td = document.createElement('td');
        tr.appendChild(td);
        var i_ = document.createElement('i');
        i_.setAttribute('class', "fa fa-trash");
        i_.setAttribute('onclick', "deleteFileInList(" + i + ")");
        td.appendChild(i_);
    }
}

//удаление файла из списка
function deleteFileInList(num) {
    if(num < 0)
        return;
    if(globalSend[num]) {
        var arr = globalSend;
        globalSend = new Array();
        for(var i = 0; i < arr.length; i++) {
            if(i != num)
                globalSend.push(arr[i]);
        }

    }
    addTableFile();
}

/**
 * Сохранение файлов
 */
function clickSave(path) {
    if(!path) {
        MessageBox("Не задан путь к файлу", true);
        return;
    }
    var datas = new FormData();
    if(globalSend) {
        $.each(globalSend, function(key, value) {
            datas.append(key, value.value);
        });
    }
    if(!sendAjaxForFile('/www/core/ajax.php?m=ajaxs&a=addFileByPath', {'path': path}, datas, true)) {
        MessageBox("Не удалось сохранить файлы", true);
        return;
    } else {
        location.href = location.href;
    }
}


