window.onload = function () {
    $(document).ajaxStart(function(){
        LoaderModal();
    });

    $(document).ajaxStop(function(){
        deleteLoaderModal();
    });
}
/************************
 отправка ajax
 ***********************/
function sendAjax(path = null, data = null, func = null, _async = true, _return = false) {
    if(!path)
        return;
    var _req = null;
    $.ajax({
        type: 'POST',
        url: '/www/core/ajax.php?' + path,
        data: data,
        cache: false,
        async: _async,
        dataType: 'json',
        success: function(response) {
            if(!response) {
                MessageBox('Пустой ответ от сервера', true);
                return;
            }
            if(response.ReloadURL) {
                location.href = response.ReloadURL;
            }
            if(response.Msg) {
                MessageBox(response.Msg, true);
                return;
            }
            _req = response.Data;
            if(isFunction(window[func])) {
                eval(func + "(" + JSON.stringify(response.Data) + ")");
            }
        },
        error: function(xhr) {
            MessageBox(xhr.statusText + xhr.responseText, true);
        },
    });

    if(!_async && _return)
        return _req;
}

//отправка ajax с файлами (данные передаются в гетере)
function sendAjaxForFile(path, data, files, _return) {
    var _req = null;
    $.ajax({
        url: path + "&param=" + JSON.stringify(data),
        type: 'POST',
        data: files,
        cache: false,
        dataType: 'json',
        async: false,
        processData: false, // Не обрабатываем файлы (Don't process the files)
        contentType: false, // Так jQuery скажет серверу что это строковой запрос
        success: function(response) {
            if(!response) {
                MessageBox('Пустой ответ от сервера', true);
                return;
            }
            if(response.ReloadURL) {
                location.href = response.ReloadURL;
            }
            if(response.Msg) {
                MessageBox(response.Msg, true);
                return;
            }
            _req = response.Data;
        },
        error: function(xhr) {
            MessageBox(xhr.statusText + xhr.responseText, true);
        },
    });

    if(_return)
        return _req;
}

/**
 * Скачивание файла
 */
function FileForceDownload(path) {
    if(!path) {
        MessageBox('Не задан путь к файлу', true);
        return;
    }
    let url = "/www/core/downloader.php?path=" + JSON.stringify(path);
    location.href = url;
}

//модалка ожидания загрузки
function LoaderModal() {
    //удаляем если есть
    removeElement("ajax_preloader");

    //создаем заново
    var body = document.createElement('div');
    body.setAttribute('id', "ajax_preloader");
    body.setAttribute('style', "position: fixed;left: 0;top: 0;z-index: 999;width: 100%;height: 100%;overflow: visible;background: rgba(29,37,49,.9)");

    //тело страницы
    var body_page = document.getElementsByTagName('body')[0];
    body_page.appendChild(body);

    //див по центру для крутелки
    var center = document.createElement('div');
    center.setAttribute('style', "position: fixed;top: 50%;left: 50%;width: 100px;height:100px;margin: -50px 0 0 -50px;")
    body.appendChild(center);

    //крутелка
    var i_ = document.createElement('i');
    i_.setAttribute('class', "fa fa-circle-o-notch fa-spin fa-5x u-color-warning");
    center.appendChild(i_);
}

//удаление ожидания загрузки
function deleteLoaderModal() {
    //удаляем если есть
    removeElement("ajax_preloader");
}

//запуск удаления элемента
function removeElement(id) {
    var elem = document.getElementById(id);
    if(elem)
        remove(elem);
}

//удаление элемента
function remove(elem) {
    return elem.parentNode ? elem.parentNode.removeChild(elem) : elem;
}

//вывод сообщения
function MessageBox(txt, error) {
    //контейнер
    var container = document.createElement('div');
    container.setAttribute('class', "c-modal modal fade");
    container.setAttribute('id', "messageAlert");
    container.setAttribute('style', "z-index: 2000");
    container.setAttribute('tabindex', -1);
    container.setAttribute('role', "dialog");
    container.setAttribute('aria-labelledby', "messageAlert");
    document.getElementsByTagName('body')[0].appendChild(container);
    //документ
    var doc = document.createElement('div');
    doc.setAttribute('class', "c-modal__dialog modal-dialog");
    doc.setAttribute('role', "document");
    container.appendChild(doc);
    //контент
    var content = document.createElement('div');
    content.setAttribute('class', "modal-content");
    doc.appendChild(content);
    //основно контейнер
    var div = document.createElement('div');
    div.setAttribute('class', "c-card u-p-medium u-mh-auto");
    div.setAttribute('style', "max-width:500px;");
    content.appendChild(div);
    if(error == true) {
        //заголовок
        var h3 = document.createElement('h3');
        h3.setAttribute('style', "color: red");
        h3.appendChild(document.createTextNode("Ошибка"));
        div.appendChild(h3);
    }
    //текст сообщения
    var p = document.createElement('p');
    p.appendChild(document.createTextNode(txt));
    div.appendChild(p);
    //кнопка
    var btn = document.createElement('button');
    btn.setAttribute('class', "c-btn c-btn--info");
    if(error == true)
        btn.setAttribute('class', "c-btn c-btn--danger");
    else if(error == false)
        btn.setAttribute('class', "c-btn c-btn--success");
    btn.setAttribute('data-dismiss', "modal");
    btn.setAttribute('style', "float: right");
    btn.appendChild(document.createTextNode("Закрыть"));
    div.appendChild(btn);
    //при скрывании модалки - удаляем её
    $('#messageAlert').on('hidden.bs.modal', function (e) {
        removeElement("messageAlert");
    });
    //открываем модалку
    $('#messageAlert').modal('show');
}

//удаление элементов ниже текущего в родительском блоке
//если элемент равен null, то все элементы в блоке
//если родитель равен null, то все элементы в документе
function deleteFooterDOM(parent, element) {
    if(parent == null)
        parent = document;
    var all = parent.getElementsByTagName("*");

    var index = -1;

    if(element == null)
        index = 0;

    var i = all.length - 1;
    while(all[i] != element && i >= 0) {
        remove(all[i]);
        i--;
    }
}

//функция очистки таблицы
function clearTable(table) {
    if(!table)
        return;
    var tbody = table.getElementsByTagName('tbody')[0];
    if(!tbody)
        return;
    deleteFooterDOM(tbody);
}

//удаление текста между тегами
function removeAllTextNodes(node) {
    if(!node)
        return;
    if (node.nodeType === 3) {
        node.parentNode.removeChild(node);
    } else if (node.childNodes) {
        for (var i = node.childNodes.length; i--;) {
            removeAllTextNodes(node.childNodes[i]);
        }
    }
}

//чистим селекты
function clearSelect(select) {
    while(select.options.length > 0) {
        remove(select.options[0]);
    }
}

//проверка на число
function isNumeric(n) {
    return !isNaN(parseFloat(n)) && isFinite(n);
}

//перевернуть строку
function reverseStr(str) {
    return str.split("").reverse().join("");
}

//возвращает текст из тега
function getConcatenedTextContent(node) {
    var _result = "";
    if (node == null) {
        return _result;
    }
    var childrens = node.childNodes;
    var i = 0;
    while (i < childrens.length) {
        var child = childrens.item(i);
        switch (child.nodeType) {
            case 1: // ELEMENT_NODE
            case 5: // ENTITY_REFERENCE_NODE
                _result += getConcatenedTextContent(child);
                break;
            case 3: // TEXT_NODE
            case 2: // ATTRIBUTE_NODE
            case 4: // CDATA_SECTION_NODE
                _result += child.nodeValue;
                break;
            case 6: // ENTITY_NODE
            case 7: // PROCESSING_INSTRUCTION_NODE
            case 8: // COMMENT_NODE
            case 9: // DOCUMENT_NODE
            case 10: // DOCUMENT_TYPE_NODE
            case 11: // DOCUMENT_FRAGMENT_NODE
            case 12: // NOTATION_NODE
                // skip
                break;
        }
        i++;
    }
    return _result;
}

//проверяет или существует функция
function isFunction(functionToCheck) {
    var getType = {};
    return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}

//проверка или является строка json
function is_json(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

//отрисовка данных таблиц
function addTRsInTable(element, id_table, url = null, getID = null) {
    if(!element || !Array.isArray(element) || !id_table)
        return;
    var table = document.getElementById(id_table);
    if(!table)
        return;
    clearTable(table);
    var thead = table.getElementsByTagName('thead')[0];
    if(!thead)
        return;
    var tr = thead.getElementsByTagName('tr')[0];
    if(!tr)
        return;
    var ths = tr.getElementsByTagName('th');
    var _ths = [];
    var onclick_tr = false;
    for(var i = 0; i < ths.length; i++) {
        if(ths[i].hasAttribute('id')) {
            _ths.push(ths[i].getAttribute('id'));
        } else {
            if(ths[i].hasAttribute('data-json') && is_json(ths[i].getAttribute('data-json'))) {
                _ths.push(JSON.parse(ths[i].getAttribute('data-json')));
                onclick_tr = true;
            } else {
                _ths.push('');
            }
        }
    }
    if(_ths.length == 0)
        return;

    var tbody = table.getElementsByTagName('tbody')[0];
    if(!tbody)
        return;

    let _start = 0;
    let _end = element.length - 1;
    let count_pages = 0;
    let limit = eval('SETTING_TABLE') && eval('SETTING_TABLE.' + id_table) && eval('SETTING_TABLE.' + id_table + '.valueLimit') ? eval('SETTING_TABLE.' + id_table + '.valueLimit') : 0;
    let page = eval('SETTING_TABLE') && eval('SETTING_TABLE.' + id_table) && eval('SETTING_TABLE.' + id_table + '.valuePage') ? eval('SETTING_TABLE.' + id_table + '.valuePage') : 0;
    let name_page_object = eval('SETTING_TABLE') && eval('SETTING_TABLE.' + id_table) && eval('SETTING_TABLE.' + id_table + '.namePage') ? eval('SETTING_TABLE.' + id_table + '.namePage') : '';
    if(limit && page && name_page_object) {
        count_pages = Math.ceil(element.length / limit);
        if(count_pages > 0) {
            page = page > count_pages ? count_pages : page;
            _start = (page - 1) * limit;
            _end = page * limit - 1;
        } else {
            count_pages = 1;
        }

    }
    // приводим к числам
    limit = parseInt(limit);
    page = parseInt(page);

    if(_start > (element.length - 1)) {
        _start = (element.length - 1 - page) > 0 ? (element.length - 1 - page) : 0;
    }
    if(_end > (element.length - 1)) {
        _end = element.length - 1;
    }

    for(var e = _start; e <= _end; e++) {
        var tr = document.createElement('tr');
        tr.setAttribute('class', 'c-table__row');
        if(!onclick_tr && url && getID) {
            var href = "document.location='" + url + '&id=' + eval('element[e].' + getID) + "'";
            tr.setAttribute('onclick', href);
        } else if(!onclick_tr && url) {
            tr.setAttribute('onclick', url);
        }
        tbody.appendChild(tr);
        for (var i = 0; i < _ths.length; i++) {
            var td = document.createElement('td');
            td.setAttribute('class', 'c-table__cell');
            if(typeof _ths[i] === 'object' && _ths[i] !== null) {
                if(_ths[i].type && _ths[i].type == 'i')
                    var _input = document.createElement('i');
                else
                    var _input = document.createElement('input');
                if(_ths[i].name)
                    _input.setAttribute('name', _ths[i].name);
                if(_ths[i].type && _ths[i].type != 'i')
                    _input.setAttribute('type', _ths[i].type);
                if(_ths[i].value)
                    _input.setAttribute('value', eval('element[e].' + _ths[i].value));
                if(_ths[i].class)
                    _input.setAttribute('class', _ths[i].class);
                if(_ths[i].type && _ths[i].type == 'i' && _ths[i].url) {
                    _input.setAttribute('style', 'cursor: pointer');
                    if(_ths[i].func) {
                        href = _ths[i].func + '(' + eval('element[e].' + _ths[i].value) + ', "' + _ths[i].url + '")';
                    } else {
                        var href = "document.location='" + _ths[i].url + '&' + _ths[i].value + '=' + eval('element[e].' + _ths[i].value) + "'";
                    }
                    _input.setAttribute('onclick', href);
                }
                td.appendChild(_input);
            } else if (_ths[i] != '') {
                td.appendChild(document.createTextNode(eval('(element[e].' + _ths[i] + ') ? element[e].' + _ths[i] + ' : ""')));
            }
            tr.appendChild(td);
        }
    }

    // отрисовка номеров страниц
    let nav = document.getElementById('nav_' + name_page_object);
    if(nav) {
        // чистим контейнер
        deleteFooterDOM(nav);
        // получаем урл
        let paramGET = _GET(location.href);
        let _href = window.location.origin + window.location.pathname + '?';
        let _href_get = '';
        for(let e in paramGET) {
            if(name_page_object == e) {
                continue;
            }
            _href_get += _href_get ? '&' : '';
            _href_get += e + '=' + eval('paramGET.' + e);
        }
        _href += _href_get;
        // определяем стартовую и конечную страницу для отображения
        let start_nav = (page - 3) < 1 ? 1 : (page - 3);
        let end_nav = (page + 3) > count_pages ? count_pages : (page + 3);
        // стрелка влево
        let str_nav = page > start_nav ? '<li class="c-pagination__item"><a class="c-pagination__control" onclick="redirectTable(\'' + _href + '\', \'' + name_page_object + '\', ' + (page - 1) + ')"><i class="fa fa-caret-left"></i></a></li>' : '';
        // кнопки номеров
        for(let e = start_nav; e <= end_nav; e++) {
            str_nav += '<li class="c-pagination__item"><a class="c-pagination__link' + (e == page ? ' is-active' : '') + '" onclick="redirectTable(\'' + _href + '\', \'' + name_page_object + '\', ' + e + ')">' + e + '</a></li>';
        }
        // стрелка вправо
        str_nav += page < end_nav ? '<li class="c-pagination__item"><a class="c-pagination__control" onclick="redirectTable(\'' + _href + '\', \'' + name_page_object + '\', ' + (page + 1) + ')"><i class="fa fa-caret-right"></i></a></li>' : '';
        nav.innerHTML = str_nav;
    }
}

//удаление строки в таблице
function deleteRowInTable(id, url) {
    if(!id || !url)
        return;
    if(confirm('Удалить расчет?'))
    {
        if(Array.isArray(id)) {
            for(var i = 0; i < id.length; i++) {
                sendAjax(url, {'id': id[i]});
            }
        } else {
            sendAjax(url, {'id': id});
        }
        if(!document.getElementById('messageAlert'))
            location.href = location.href;
    }
}

//копирование строки в таблице
function cloneRowInTable(id, url) {
    if(!id || !url)
        return;
    if(confirm('Скопировать расчет?'))
    {
        if(Array.isArray(id)) {
            for(var i = 0; i < id.length; i++) {
                sendAjax(url, {'id': id[i]});
            }
        } else {
            sendAjax(url, {'id': id});
        }
        if(!document.getElementById('messageAlert'))
            location.href = location.href;
    }
}

//конструктор модального окна
//name - ид окна
//label - заголовок окна
//funcSubmit - функция кнопки ОК... если null, то не создаем кнопку
//width_ - максимальная ширина экрана в px
function CreateModalWindow(name, label, funcSubmit, width_) {
    //если такое окно есть, то удаляем
    removeElement("modal_" + name);

    //основной див модального окна
    var div_modal = document.createElement('div');
    div_modal.setAttribute('class', "c-modal c-modal--xlarge modal fade");
    div_modal.setAttribute('id', "modal_" + name);
    //div_modal.setAttribute('tabindex', -1);
    div_modal.setAttribute('role', "dialog");
    div_modal.setAttribute('aria-labelledby', name + "Label");
    div_modal.setAttribute('data-backdrop', "static");
    document.getElementsByTagName('body')[0].appendChild(div_modal);

    //див документа модального окна
    var div_dialog = document.createElement('div');
    div_dialog.setAttribute('class', "c-modal__dialog modal-dialog");
    div_dialog.setAttribute('role', "document");
    if(width_) {
        div_dialog.setAttribute('style', "max-width: " + width_ + "px");
    }
    div_modal.appendChild(div_dialog);

    //див контента модального окна
    var div_content = document.createElement('div');
    div_content.setAttribute('class', "modal-content");
    div_dialog.appendChild(div_content);

    //див заголовка...
    var div_header = document.createElement('header');
    div_header.setAttribute('class', "c-modal__header");
    div_content.appendChild(div_header);
    //... кнопка закрыть...
    //...заголовок окна...
    var h1 = document.createElement('h1');
    h1.setAttribute('class', "c-modal__title");
    h1.setAttribute('id', name + "Label");
    h1.appendChild(document.createTextNode(label));
    div_header.appendChild(h1);

    //...спан...
    var span = document.createElement('span');
    span.setAttribute('class', "c-modal__close");
    span.setAttribute('data-dismiss', "modal");
    span.setAttribute('aria-label', "Close");
    div_header.appendChild(span);

    //значок закрытия
    var i_ = document.createElement('i');
    i_.setAttribute('class', "fa fa-close");
    span.appendChild(i_);

    //тело модального окна
    var div_body = document.createElement('div');
    div_body.setAttribute('id', name + "-body");
    div_body.setAttribute('class', "c-modal__body u-pb-small");
    div_content.appendChild(div_body);

    //подвал модального окна
    var div_footer = document.createElement('footer');
    div_footer.setAttribute('class', "c-modal__footer u-justify-center");
    div_content.appendChild(div_footer);

    //строка для кнопок
    var div_footer_row = document.createElement('div');
    div_footer_row.setAttribute('class', "row");
    div_footer.appendChild(div_footer_row);

    if(funcSubmit) {
        //кнопка ОК
        var div_button_ok = document.createElement('div');
        div_button_ok.setAttribute('class', "col u-mb-medium");
        div_footer_row.appendChild(div_button_ok);
        var a_button_ok = document.createElement('a');
        a_button_ok.setAttribute('class', "c-btn c-btn--info c-btn--fullwidth");
        a_button_ok.setAttribute('onclick', funcSubmit + "()");
        a_button_ok.appendChild(document.createTextNode("ОК"));
        div_button_ok.appendChild(a_button_ok);
    }
    //кнопка Отмена
    var div_button_cancel = document.createElement('div');
    div_button_cancel.setAttribute('class', "col u-mb-medium");
    div_footer_row.appendChild(div_button_cancel);
    var a_button_cancel = document.createElement('a');
    a_button_cancel.setAttribute('class', "c-btn c-btn--secondary c-btn--fullwidth");
    a_button_cancel.setAttribute('data-dismiss', "modal");
    a_button_cancel.appendChild(document.createTextNode("Отмена"));
    div_button_cancel.appendChild(a_button_cancel);

    //при скрывании модалки - удаляем её
    $('#modal_' + name).on('hidden.bs.modal', function (e) {
        removeElement("modal_" + name);
    });

    $('#modal_' + name).modal('show');
}

//создание таблицы
function createTable(parent = null, fields = null, data = null, id = null, func_onclick = null) {
    if(!parent) return;
    if(!fields) return;
    if(!data || !Array.isArray(data) || data.length == 0) return;
    
    var table = document.createElement('table');
    table.setAttribute('class', 'c-table');
    if(id)
        table.setAttribute('id', id);
    parent.appendChild(table);

    var thead = document.createElement('thead');
    thead.setAttribute('class', 'c-table__head c-table__head--slim');
    table.appendChild(thead);
    var tr = document.createElement('tr');
    tr.setAttribute('class', 'c-table__row');
    thead.appendChild(tr);

    for(var _k in fields) {
        var th = document.createElement('th');
        th.setAttribute('class', 'c-table__cell c-table__cell--head sort');
        th.setAttribute('id', _k);
        th.appendChild(document.createTextNode(fields[_k]));
        tr.appendChild(th);
    }

    var tbody = document.createElement('tbody');
    table.appendChild(tbody);

    for(var i = 0; i < data.length; i++) {
        var tr = document.createElement('tr');
        tr.setAttribute('class', 'c-table__row');
        if(func_onclick) {
            tr.setAttribute('onclick', func_onclick + '(' + JSON.stringify(data[i]) + ')');
        }
        tbody.appendChild(tr);
        for(var _k in fields) {
            var td = document.createElement('td');
            td.setAttribute('class', 'c-table__cell');
            td.appendChild(document.createTextNode(eval('data[i].' + _k + ' ? data[i].' + _k + ' : ""')));
            tr.appendChild(td);
        }
    }
}

//переворот строки
function reverseString(str) {
    var splitString = str.split("");
    var reverseArray = splitString.reverse();
    var joinArray = reverseArray.join("");
    return joinArray;
}

/**
 * Возваращает родительский элемент
 * @param {type} tag. Если не null, то ищем до указанного тега, иначе родитель
 */
function getParentNode(e, tag = null) {
    let result = null;
    
    do {
        if(!e || !e.parentNode) {
            break;
        }
        if(!tag) {
            result = e.parentNode;
        } else {
            tag = tag.toUpperCase();
            let _e = e;
            while(_e.parentNode && _e.tagName != tag) {
                _e = _e.parentNode;
            }
            result = _e.tagName != tag ? null : _e;
        }
    } while(false);
    
    return result;
}

/**
 * перегрузка страница с новыми гетерами
 * @param url - строка запроса
 * @param field - параметр
 * @param value - значение параметра
 */
function redirectTable(url, field, value) {
    url += "&" + field + "=" + value;
    location.href = url;
}

/**
 * получение get парраметров
 * @param url
 * @private
 */
function _GET(url) {
    let result = {};

    do {
        if (!url) {
            break
        }
        result = window
            .location
            .search
            .replace('?','')
            .split('&')
            .reduce(
                function(p,e){
                    var a = e.split('=');
                    p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                    return p;
                },
                {}
            );
    } while(false);

    return result;
}

function redirectTable2(json, field, value) {
    // получаем урл
    let paramGET = _GET(location.href);
    let _href = window.location.origin + window.location.pathname + '?';
    let _href_get = '';
    for(let e in paramGET) {
        if(json._id == e) {
            continue;
        }
        _href_get += _href_get ? '&' : '';
        _href_get += e + '=' + eval('paramGET.' + e);
    }
    _href += _href_get;

    if(field == 'sort' && value == json.sort && json.desc != true) {
        json.desc = true;
    } else {
        json.desc = false;
    }
    eval('json.' + field + ' = \'' + value + '\'');
    _href += '&' + json._id + '=' + JSON.stringify(json);
    location.href = _href;
}

//конструктор модального окна
//name - ид окна
// html - строка кода
function CreateModalWindow2(name, html = '') {
    //если такое окно есть, то удаляем
    removeElement("modal_" + name);

    // размеры и отступы модалки
    let lft = $('.o-page__sidebar').css('transform') == 'none' ?  $('.o-page__sidebar').width() : 0;
    let top = $('.o-page__sidebar').css('transform') == 'none' ?  0 : $('.c-navbar').height();
    let wdt = $(window).width() - lft;

    let _modal = '<div class="m-modal" id="modal_' + name + '" style="width: ' + wdt + 'px; left: ' + lft + 'px; top: ' + top + 'px">';

    _modal += '<div class="row" style="text-align-last: right;"><div class="col-md-12"><i class="fa fa-close fa-3x" style="cursor: pointer;" onclick="removeElement(\'modal_' + name + '\')"></i></div></div>';
    _modal += '<div class="row">' + html + '</div>'
    _modal += '</div>';

    $('.container-fluid').append(_modal);

    /** при изменении окна меняем размер модалки */
    window.addEventListener(`resize`, event => {
        let lft = $('.o-page__sidebar').css('transform') == 'none' ?  $('.o-page__sidebar').width() : 0;
        let top = $('.o-page__sidebar').css('transform') == 'none' ?  0 : $('.c-navbar').height();
        let wdt = $(window).width() - lft;
        $('[id ^= "modal_"]').attr('style', 'width: ' + wdt + 'px; left: ' + lft + 'px; top: ' + top + 'px');
    }, false);

    $('#modal_' + name).addClass('show');
}