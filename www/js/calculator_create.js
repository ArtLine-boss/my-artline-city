$(document).ready(function() {
    rowTable();
    if(document.getElementById('calc_parts').value != '' && is_json(document.getElementById('calc_parts').value)) {
        loadCurrentCalc(JSON.parse(document.getElementById('calc_parts').value));
    }
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

//выбор дизайна
function maket(e) {
    if(e) {
        document.getElementById("op1").style.display = 'flex';
        document.getElementById("div_p_prdiz_").style.display = 'flex';
    }
    else {
        document.getElementById("op1").style.display = 'none';
        document.getElementById("div_p_prdiz_").style.display = 'none';
    }
}

//выбор препресса
function prepressInit(e) {
    if(e)
        document.getElementById("div_p_press_").style.display = "flex";
    else
        document.getElementById("div_p_press_").style.display = "none";
}

//выбор доп функций
function checks(id, id2) {
    if (document.getElementById(id + "_").checked) {
        document.getElementById(id).hidden = false;
        if (id2 != "") {
            document.getElementById(id2).hidden = false;
        }

        switch (id) {
            case "check13" :
                document.getElementById("p_bug_1").focus();
                break;
            case "check1" :
                document.getElementById("p_perf_1").focus();
                break;
            case "check2" :
                document.getElementById("p_ygl_1").focus();
                break;
            case "check4" :
                document.getElementById("p_otv_1").focus();
                break;
            case "check5" :
                document.getElementById("p_luv_1").focus();
                break;
            case "check7" :
                document.getElementById("p_vir_1").focus();
                break;
            case "check8" :
                document.getElementById("p_con_1").focus();
                break;
            case "check9" :
                document.getElementById("p_tis_1").focus();
                break;
        }
    }
    else {
        document.getElementById(id).hidden = true;
        if (id2 != "") {
            document.getElementById(id2).hidden = true;
        }
        var part = Number(document.getElementById('kol').value) + 1;
        switch (id) {
            case "check13" :
                for (var i = 1; i < part; i++) {
                    $('#p_bug_' + i).val('');
                }
                break;
            case "check1" :
                for (var i = 1; i < part; i++) {
                    $('#p_perf_' + i).val('');
                }
                break;
            case "check2" :
                for (var i = 1; i < part; i++) {
                    $('#p_ygl_' + i).val('');
                }
                break;
            case "check4" :
                for (var i = 1; i < part; i++) {
                    $('#p_otv_' + i).val('');
                    $('#p_diam_' + i).val('');
                }
                break;
            case "check5" :
                for (var i = 1; i < part; i++) {
                    $('#p_luv_' + i).val('');
                    $('#p_colorluv_' + i).val('');
                }
                break;
            case "check7" :
                for (var i = 1; i < part; i++) {
                    $('#p_vir_' + i).val('');
                    $('#p_prstamp_' + i).val('');
                }
                break;
            case "check8" :
                for (var i = 1; i < part; i++) {
                    $('#p_con_' + i).val('');
                    $('#p_prkl_' + i).val('');
                }
                break;
            case "check9" :
                for (var i = 1; i < part; i++) {
                    $('#p_tis_' + i).val('');
                    $('#p_prckl_' + i).val('');
                }
                break;
        }
    }

}

//рисуем поля в таблице
function rowTable() {
    // таблица, с которой работаем
    var tbl = document.getElementById('dynamic');
    // коллекция существующих строк таблицы
    var rws = tbl.rows.length;
    i = 0;
    var kol_part = Number(document.getElementById('kol').value) + 1;
    document.getElementById('kol').value = kol_part;
    var tabkol = kol_part + 1;

    var ro = tbl.rows[i++].insertCell(-1);
    //наименование части
    ro.innerHTML = "<input  type = 'text' name='p_namepart_" + kol_part + "' id='p_namepart_" + kol_part + "' style='width:100%; tabindex='" + tabkol + "' >";
    ro = tbl.rows[i++].insertCell(-1);
    //размер изделия
    ro.innerHTML = "<input  type = 'text' name='p_size_" + kol_part + "'  id = 'p_size_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'  >";
    ro = tbl.rows[i++].insertCell(-1);
    //количество страниц
    ro.innerHTML = "<input  type = 'text' name='p_kolstr_" + kol_part + "' id = 'p_kolstr_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'  >";

    ro = tbl.rows[i++].insertCell(-1);
    //оборудование
    var innerOptionsEquipment = "<option value='0' title='0'>Выберите</option>";
    var arrEquipment = sendAjax('m=calculator&u=create&a=AjaxLoadEquipment', null, null, false, true);
    arrEquipment.forEach(function(answer) {
        innerOptionsEquipment += "<option value='" + answer.ID + "' style='display:block;' data-attr-full='" + JSON.stringify(answer) + "'>" + answer.EQ_NAME + "</option>";
    });
    ro.innerHTML = "<select name='p_eq_" + kol_part + "' id = 'p_eq_" + kol_part + "' " + " tabindex='" + tabkol + " style='width:100px' " + "> " + innerOptionsEquipment + "</select>";

    document.getElementById("p_eq_" + kol_part).onchange = function(e) {
        var id_ = e.target.id;
        if(!id_)
            return;
        id_ = id_.substr(5);
        var id = "p_color_" + id_;
        if(!document.getElementById(id))
            return;
        clearSelect(document.getElementById(id));
        if(e.target.selectedIndex <= 0)
            return;
        var val = e.target.value;
        //выбор цветов
        var arrColor = sendAjax('m=calculator&u=create&a=AjaxColorForEquipment', {'equipment': val}, null, false, true);
        arrColor.forEach(function(answer) {
            var option_ = document.createElement('option');
            option_.setAttribute('value', answer.ID);
            option_.setAttribute('data-attr', val);
            option_.setAttribute('title', answer.OPERATION_PRICE);
            option_.appendChild(document.createTextNode(answer.PAR));
            document.getElementById(id).appendChild(option_);
        });
        document.getElementById(id).selectedIndex = -1;

        //выбор бумаги
        var id = "p_mat_" + id_;
        if(!document.getElementById(id))
            return;
        clearSelect(document.getElementById(id));
        var arrMat = sendAjax('m=calculator&u=create&a=AjaxMatForEquipment', {'equipment': val}, null, false, true);
        if(arrMat) {
            document.getElementById(id).innerHTML = arrMat;
            document.getElementById(id).selectedIndex = -1;
            document.getElementById(id).dispatchEvent(new Event('change'));
        }
        //выбор размера
        returnSizePrint(id_);

        //добавляем доп цвета
        var id = "p_color_" + id_;
        removeElement(id + "_combi");
        var innerColor = sendAjax('m=ajaxs&&a=getCombiColorByEquipment', {'equipment': val}, null, false, true);
        if(innerColor && Array.isArray(innerColor) && innerColor.length > 0) {
            var tbl = '<div class="row" id="' + id + '_combi" style="font-size: 10pt; padding: 0;">';
            tbl += '<div class="col-md-6"><label>Лицевая сторона</label><table>';
            tbl += '<thead><tr>' +
                '<th></th>' +
                '<th>10%</th>' +
                '<th>40%</th>' +
                '<th>70%</th>' +
                '<th>90%</th>' +
                '</tr></thead><tbody>';
            innerColor.forEach(function(e) {
                tbl += '<tr>' +
                    '<td><b>' + e.PAR + '<b></td>' +
                    '<td><input type="radio" class="' + id + '_combi_f" name="' + id + "_" + e.ID + '_f" value="' + e.ID + '_10" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                    '<td><input type="radio" class="' + id + '_combi_f" name="' + id + "_" + e.ID + '_f" value="' + e.ID + '_40" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                    '<td><input type="radio" class="' + id + '_combi_f" name="' + id + "_" + e.ID + '_f" value="' + e.ID + '_70" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                    '<td><input type="radio" class="' + id + '_combi_f" name="' + id + "_" + e.ID + '_f" value="' + e.ID + '_90" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                    '</tr>';
            });
            tbl += '</tbody></table></div>';

            tbl += '<div class="col-md-6"><label>Оборотная сторона</label><table>';
            tbl += '<thead><tr>' +
                '<th></th>' +
                '<th>10%</th>' +
                '<th>40%</th>' +
                '<th>70%</th>' +
                '<th>90%</th>' +
                '</tr></thead><tbody>';
            innerColor.forEach(function(e) {
                tbl += '<tr>' +
                    '<td><b>' + e.PAR + '<b></td>' +
                    '<td><input class="' + id + '_combi_b" type="radio" name="' + id + "_" + e.ID + '_b" value="' + e.ID + '_10" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                    '<td><input class="' + id + '_combi_b" type="radio" name="' + id + "_" + e.ID + '_b" value="' + e.ID + '_40" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                    '<td><input class="' + id + '_combi_b" type="radio" name="' + id + "_" + e.ID + '_b" value="' + e.ID + '_70" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                    '<td><input class="' + id + '_combi_b" type="radio" name="' + id + "_" + e.ID + '_b" value="' + e.ID + '_90" onmousedown="down_combi(this)" onchange="change_combi(this)"></td>' +
                    '</tr>';
            });

            $('#' + id).after(tbl);
        }
    }

    //цвет
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<select name='p_color_" + kol_part + "' id = 'p_color_" + kol_part + "' onblur=" + '"' + "get_price()" + '"' + " tabindex='" + tabkol + "'" + "> <option value='0' data-attr='0' title='0'>Выберите</option> </select>";

    //бумага
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<select name='p_mat_" + kol_part + "' class='js-example-basic-single js-states form-control chosen'  style='display: block;' id = 'p_mat_" + kol_part + "'  onchange='returnSizePrint(" + kol_part + ")' onblur=" + '"' + "get_price()" + '"' + " tabindex='" + tabkol + "'" + "></select><br><input type='checkbox' " + "' onblur=" + '"' + "get_price()" + '"' + "value='' name='mat_firm" + kol_part + "' id='mat_firm" + kol_part + "' >Сырье заказчика";
    $("#p_mat_" + kol_part).select2({
        width: '100%',
        dropdownParent: $('#forms'),
        placeholder: 'Выберите бумагу'
    });

    //размер бумаги
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<select name='p_sizep_" + kol_part + "' id = 'p_sizep_" + kol_part + "'" + " tabindex='" + tabkol + "' onblur='get_price()'><option value='0' title='0'>Выберите</option></select>  <input  type = 'hidden'  id = 'p_size_r_" + kol_part + "' size= '10'  tabindex='" + tabkol + "'  >&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp <b>Другой размер: </b> <input  type = 'text' tabindex='" + tabkol + "' name='p_new_size_" + kol_part + "' id = 'p_new_size_" + kol_part + "'  size= '10' onblur='get_price()'>";
    document.getElementById("p_new_size_" + kol_part).onchange = function(e) {
        var id_ = parseInt(e.target.id.substr(11));
        document.getElementById("p_new_size_" + id_).removeAttribute('data-attr-full');
        if(e.target.value == "")
            return;
        var tmp_size = e.target.value.split('*');
        if(tmp_size.length != 2)
            return;
        var p_sizep = document.getElementById("p_sizep_" + id_);
        for(var i = 0; i < p_sizep.options.length; i++) {
            if(p_sizep.options[i].value == e.target.value) {
                p_sizep.value = p_sizep.options[i].value;
                e.target.value = "";
                return;
            }
        }

        if(document.getElementById("p_mat_" + id_).selectedIndex < 0 || document.getElementById("mat_firm" + id_).checked == true)
            return;

        var send = {
            size: e.target.value,
            material: document.getElementById("p_mat_" + id_).value
        }

        //подкидываем выносы и оступы
        if(document.getElementById("vin" + id_).value != "") {
            send.vynos = parseFloat(document.getElementById("vin" + id_).value);
        }
        if(document.getElementById("p_eq_" + id_).selectedIndex >= 0) {
            var selectedElement = document.getElementById("p_eq_" + id_).options[document.getElementById("p_eq_" + id_).selectedIndex];
            if(selectedElement.hasAttribute('data-attr-full')) {
                var json = JSON.parse(selectedElement.getAttribute('data-attr-full'));
                if(json) {
                    send.left = json.ladnr;
                    send.top = json.uandd;
                }
            }
        }
        //количество изделий
        if(document.getElementById("p_kolstr_" + id_).value != "")
            send.count_product = parseInt(document.getElementById("p_kolstr_" + id_).value);
        else if(document.getElementById("p_cir").value != "")
            send.count_product = parseInt(document.getElementById("p_cir").value);

        //подбираем бумагу
        var innerMat = sendAjax('m=calculator&u=create&a=AjaxMaterialInnerSize', {'equipment': send}, null, false, true);
        if(innerMat) {
            document.getElementById("p_new_size_" + id_).setAttribute('data-attr-full', JSON.stringify(innerMat));
        }
    }

    //резка
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_cut_" + kol_part + "' type='checkbox' tabindex='" + tabkol + "' id = 'p_cut_" + kol_part + "' style='width:5%;position: left;float: left; clear: fight;'  onblur='get_price()'>  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<b>Плот. Резка(по меткам)</b> <input type='checkbox' tabindex='" + tabkol + "' name = 'p_cut2_" + kol_part + "' id = 'p_cut2_" + kol_part + "' style='width:5%;position: rigth;float: rigth; clear: fight;'  onblur=" + '"' + "get_price()" + '"' + "> P= <input  type = 'text' name = 'p_size_cut_" + kol_part + "' id = 'p_size_cut_" + kol_part + "' size= '10' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'> мм. (Периметр 1-го изделия)";

    //плоттераня резка
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<select id='p_size_cut_op_" + kol_part + "' name='p_size_cut_op_" + kol_part + "'>" + str_plottCut + "</select>" +
        "<select id='p_size_cut_eq_" + kol_part + "' name='p_size_cut_eq_" + kol_part + "' onchange='get_price()'><option value=''>Выберите</option></select>" +
        "P= <input  type = 'text' name = 'p_size_cut2_" + kol_part + "' id = 'p_size_cut2_" + kol_part + "' size= '10' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'> мм. (Периметр 1-го изделия)";
    document.getElementById("p_size_cut_op_" + kol_part).onchange = function(e) {
        deleteFooterDOM(document.getElementById("p_size_cut_eq_" + kol_part));
        let opt = document.createElement('option');
        opt.setAttribute('value', '');
        opt.appendChild(document.createTextNode('Выберите'));
        document.getElementById("p_size_cut_eq_" + kol_part).appendChild(opt);
        if(e.target.value) {
            var eqCut = sendAjax('m=ajaxs&a=getEqupmentsByOperations', {'operations': e.target.value}, null, false, true);
            if(eqCut && Array.isArray(eqCut)) {
                for(let i in eqCut) {
                    let opt = document.createElement('option');
                    opt.setAttribute('value', eqCut[i].ID);
                    opt.appendChild(document.createTextNode(eqCut[i].EQ_NAME));
                    document.getElementById("p_size_cut_eq_" + kol_part).appendChild(opt);
                }
                if(eqCut.length == 1) {
                    document.getElementById("p_size_cut_eq_" + kol_part).selectedIndex = 1;
                    document.getElementById("p_size_cut_eq_" + kol_part).dispatchEvent(new Event('change'));
                }
            }
        }
    }

    //ламинирование
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<select name='p_lam_" + kol_part + "' id = 'p_lam_" + kol_part + "' tabindex='" + tabkol + "' onchange='get_price()'></select>";
    directoryLaminat.forEach(function(elem) {
        var option_ = document.createElement('option');
        option_.setAttribute('value', elem.value);
        option_.appendChild(document.createTextNode(elem.name));
        document.getElementById("p_lam_" + kol_part).appendChild(option_);
    });
    document.getElementById("p_lam_" + kol_part).selectedIndex = -1;

    //биговка
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_bug_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_bug_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";
    //перфорация
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_perf_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_perf_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";
    //скругление углов
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_ygl_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_ygl_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";
    //количество отверстий
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_otv_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_otv_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //диаметр отверстий
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<select name='p_diam_" + kol_part + "' id = 'p_diam_" + kol_part + "' tabindex='" + tabkol + "'" + "></select>";
    directoryDiam.forEach(function(elem) {
        var option_ = document.createElement('option');
        option_.setAttribute('value', elem.value);
        option_.appendChild(document.createTextNode(elem.name));
        document.getElementById("p_diam_" + kol_part).appendChild(option_);
    });
    document.getElementById("p_diam_" + kol_part).selectedIndex = -1;

    //люверсы
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_luv_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_luv_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //цвет люверса
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<select name='p_colorluv_" + kol_part + "' id = 'p_colorluv_" + kol_part + "' tabindex='" + tabkol + "'" + "></select>";
    directoryColorLuv.forEach(function(elem) {
        var option_ = document.createElement('option');
        option_.setAttribute('value', elem.value);
        option_.appendChild(document.createTextNode(elem.name));
        document.getElementById("p_colorluv_" + kol_part).appendChild(option_);
    });
    document.getElementById("p_colorluv_" + kol_part).selectedIndex = -1;

    //вырубка
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_vir_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_vir_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //конгрев
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_con_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_con_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //тиснение
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_tis_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_tis_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //работы на стороне
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_off_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_off_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //цена штампа
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_prstamp_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_prstamp_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //цена клише конгрев
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_prkl_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_prkl_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //цена клише тиснение
    ro = tbl.rows[i++].insertCell(-1);
    ro.innerHTML = "<input name='p_prckl_" + kol_part + "' type = 'text' tabindex='" + tabkol + "' id = 'p_prckl_" + kol_part + "' style='width:100%;' onclick=" + '"' + "get_price()" + '"' + ">";

    //раскладка,доп параметры
    ro = tbl.rows[i++].insertCell(-1);
    var row = document.createElement('div');
    row.setAttribute('class', "row");
    ro.appendChild(row);
    var col1 = document.createElement('div');
    col1.setAttribute('class', "col-md-6");
    row.appendChild(col1);
    var col2 = document.createElement('div');
    col2.setAttribute('class', "col-md-6");
    row.appendChild(col2);
    //столбец с параметрами
    //вынос цвета
    var row_ = document.createElement('div');
    row_.setAttribute('class', "row");
    col1.appendChild(row_);
    var col_ = document.createElement('div');
    col_.setAttribute('class', "col-md-4");
    col_.appendChild(document.createTextNode("Вынос цвета"));
    row_.appendChild(col_);
    var col_ = document.createElement('div');
    col_.setAttribute('class', "col-md-8");
    row_.appendChild(col_);
    var input = document.createElement('input');
    input.setAttribute('tabindex', tabkol);
    input.setAttribute('type', "number");
    input.setAttribute('value', 2);
    input.setAttribute('style', "width: 70%;");
    input.setAttribute('id', "vin" + kol_part);
    input.setAttribute('name', "vin" + kol_part);
    input.setAttribute('onchange', "get_price()");
    col_.appendChild(input);
    col_.appendChild(document.createTextNode("мм"));
    //максимальное заполнение
    var row_ = document.createElement('div');
    row_.setAttribute('class', "row");
    col1.appendChild(row_);
    var div = document.createElement('div');
    div.setAttribute('class', "checkbox");
    row_.appendChild(div);
    var label = document.createElement('label');
    label.setAttribute('style', "font-weight: inherit;");
    row_.appendChild(label);
    var input = document.createElement('input');
    input.setAttribute('type', "checkbox");
    input.setAttribute('tabindex', "tabkol");
    input.setAttribute('id', "max" + kol_part);
    input.setAttribute('name', "max" + kol_part);
    input.setAttribute('onchange', "get_price()");
    label.appendChild(input);
    label.appendChild(document.createTextNode("Максимальное заполнение"));
    //Непечатные поля
    var row_ = document.createElement('div');
    row_.setAttribute('class', "row");
    col1.appendChild(row_);
    var div = document.createElement('div');
    div.setAttribute('class', "checkbox");
    row_.appendChild(div);
    var label = document.createElement('label');
    label.setAttribute('style', "font-weight: inherit;");
    row_.appendChild(label);
    var input = document.createElement('input');
    input.setAttribute('type', "checkbox");
    input.setAttribute('tabindex', "tabkol");
    input.setAttribute('id', "pol" + kol_part);
    input.setAttribute('name', "pol" + kol_part);
    input.setAttribute('onchange', "get_price()");
    input.setAttribute('checked', true);
    label.appendChild(input);
    label.appendChild(document.createTextNode("Непечатные поля"));
    //Перерасчет (пружина, термоклей)
    var row_ = document.createElement('div');
    row_.setAttribute('class', "row");
    col1.appendChild(row_);
    var div = document.createElement('div');
    div.setAttribute('class', "checkbox");
    row_.appendChild(div);
    var label = document.createElement('label');
    label.setAttribute('style', "font-weight: inherit;");
    row_.appendChild(label);
    var input = document.createElement('input');
    input.setAttribute('type', "checkbox");
    input.setAttribute('tabindex', "tabkol");
    input.setAttribute('id', "pers" + kol_part);
    input.setAttribute('name', "pers" + kol_part);
    input.setAttribute('onchange', "get_price()");
    label.appendChild(input);
    label.appendChild(document.createTextNode("Перерасчет (пружина, термоклей)"));

    //столбец с раскладкой
    //количество на листе
    var row_ = document.createElement('div');
    row_.setAttribute('class', "row");
    col2.appendChild(row_);
    var label = document.createElement('label');
    label.setAttribute('id', "count_on_sheet_" + kol_part);
    label.setAttribute('name', "count_on_sheet_" + kol_part);
    label.appendChild(document.createTextNode("К-во на листе = 0"));
    row_.appendChild(label);
    //листов на тираж
    var row_ = document.createElement('div');
    row_.setAttribute('class', "row");
    col2.appendChild(row_);
    var label = document.createElement('label');
    label.setAttribute('id', "count_on_pages_" + kol_part);
    label.setAttribute('name', "count_on_pages_" + kol_part);
    label.appendChild(document.createTextNode("Листов на тираж = 0"));
    row_.appendChild(label);
    //блок для раскладки
    var row_ = document.createElement('div');
    row_.setAttribute('class', "row");
    col2.appendChild(row_);
    var div = document.createElement('div');
    //div.setAttribute('class', "block_table");
    div.setAttribute('id', "block_layout_" + kol_part);
    row_.appendChild(div);
}

//удаление части
function delOneRowTable(){
    var kol = Number(document.getElementById('kol').value) ;
    var tbl = document.getElementById('dynamic');
    var rws = tbl.rows.length;
    for (i = 0; i < rws; i++) {
        kol_cell = tbl.rows[i].cells.length;
        for (y = kol - 1; y < kol; y++) {
            if (tbl.rows[i].cells.length > 1) {
                tbl.rows[i].deleteCell(-1);
            }
        }
    }
    document.getElementById('kol').value = kol - 1;
}

//расчет общий
function get_price() {
    $('[type=checkbox]').each(function (f, e) {
        this.value = this.checked;
    });
    var calcObject = sendAjax('m=calculator&u=create&a=AjaxCalculate', {'val': $('#forms').serializeArray()}, null, false, true);
    if(!calcObject)
        return;
    var calcData = calcObject.data;
    var calcResult = calcObject.result;
    if(!calcData || !Array.isArray(calcData) || calcData.length == 0) {
	    return;
    }
	for(var i in calcData) {
	    if(!calcData[i].NUM)
	        continue;
        //чистим данные
        document.getElementById("block_layout_" + calcData[i].NUM).removeAttribute("data-attr-full");
        deleteFooterDOM(document.getElementById("block_layout_" + calcData[i].NUM));
        removeAllTextNodes(document.getElementById("count_on_sheet_" + calcData[i].NUM));
        document.getElementById("count_on_sheet_" + calcData[i].NUM).appendChild(document.createTextNode("К-во на листе = 0"));
        removeAllTextNodes(document.getElementById("count_on_pages_" + calcData[i].NUM));
        document.getElementById("count_on_pages_" + calcData[i].NUM).appendChild(document.createTextNode("Листов на тираж = 0"));

        if(calcData[i].ResultCalc) {
            if(calcData[i].ResultCalc.LayoutArray) {
                if(calcData[i].ResultCalc.LayoutArray.count) {
                    removeAllTextNodes(document.getElementById("count_on_sheet_" + calcData[i].NUM));
                    document.getElementById("count_on_sheet_" + calcData[i].NUM).appendChild(document.createTextNode("К-во на листе = " + calcData[i].ResultCalc.LayoutArray.count));
                }
                if(calcData[i].ResultCalc.LayoutArray.count_pages) {
                    removeAllTextNodes(document.getElementById("count_on_pages_" + calcData[i].NUM));
                    document.getElementById("count_on_pages_" + calcData[i].NUM).appendChild(document.createTextNode("Листов на тираж = " + calcData[i].ResultCalc.LayoutArray.count_pages));
                }
            }
            if(calcData[i].ResultCalc.Layout) {
                $("#block_layout_" + calcData[i].NUM).append(calcData[i].ResultCalc.Layout);
            }
        }
    }

	if(!calcResult)
	    return;

    //пишем результат
    //document.getElementById("body_content").setAttribute('data-calc-full', JSON.stringify(calcResult));
    document.getElementById("p_sum_all").value = calcResult.all_summ_order_byn_calc;
    document.getElementById("calcID").value = calcObject.calcID;
    if(parseFloat(calcResult.p_sum_all_hand) > 0)
        document.getElementById("p_sum_all_hand").value = calcResult.p_sum_all_hand;
    document.getElementById("calc_parts").value = JSON.stringify(calcData);
    document.getElementById("calc_result").value = JSON.stringify(calcResult);
}

//список доступных размеров
function returnSizePrint(id_) {
    var id = "p_sizep_" + id_;
    if(!document.getElementById(id) || !document.getElementById("p_eq_" + id_) || !document.getElementById("p_mat_" + id_))
        return;
    clearSelect(document.getElementById(id));
    if(document.getElementById("p_eq_" + id_).selectedIndex < 0)
        return;
    var val = document.getElementById("p_eq_" + id_).value;
    var send = {
        val: val
    }
    //если задан размер изделия
    if(document.getElementById("p_size_" + id_) && document.getElementById("p_size_" + id_).value != "") {
        send.size = document.getElementById("p_size_" + id_).value;
    }
    else if(document.getElementById("p_size") && document.getElementById("p_size").value != "") {
        send.size = document.getElementById("p_size").value;
    }
    //если выбрана бумага
    if(document.getElementById("p_mat_" + id_).selectedIndex >= 0 && document.getElementById("mat_firm" + id_).checked == false)
        send.material = document.getElementById("p_mat_" + id_).value;
    //непечатные поля
    if(document.getElementById('pol' + id_) && document.getElementById('pol' + id_).checked)
        send.pol = 1;
    else
        send.pol = 0;

    var equipmentSize = sendAjax('m=calculator&u=create&a=AjaxMaterialForEquipmentSize', {'send': send}, null, false, true);
    var value_selected_index = -1;
    var max_selected_index = 0;
    equipmentSize.forEach(function(answer) {
        var option_ = document.createElement('option');
        option_.setAttribute('value', answer.SIZE);
        option_.setAttribute('data-attr', val);
        option_.setAttribute('data-attr-id', answer.ID);
        option_.setAttribute('data-attr-full', JSON.stringify(answer));
        option_.appendChild(document.createTextNode(answer.SIZE));
        document.getElementById(id).appendChild(option_);
        if(answer.count_sheet && answer.count_sheet.count && answer.count_sheet.count > max_selected_index) {
            max_selected_index = answer.count_sheet.count;
            value_selected_index = answer.SIZE;
        }
    });
    if(value_selected_index != -1)
        document.getElementById(id).value = value_selected_index;
    else
        document.getElementById(id).selectedIndex = -1;
}

//расчет элемента
function calc(id_) {
    /*****  ОБЪЕКТ РАСЧЕТА *****/
    var obj_calc = {
        all_count_product: 0, //количество продукции
        all_count: 0,  //общее количество листов продукта
        count_list_pages: 0, //количество печатных листов
        count_sheet: 0, //количество элементов на одном печатном листе
        count_sheet_block: 0, //количество страниц в одном блоке
        cost_material: 0, //стоимость материала за  единицу
        name_material: "", //название материала
        size_material: "", //размер материала
        all_cost_material: 0, //себестоимость материала
        all_cost_material_nds: 0, //стоимость материала с надбавкой
        surcharge_client: 0, //надбавка клиента
        surcharge_client_material: 0, //надбавка клиента на материал
        cost_print_color: 0, //стоимость операции печать
        widescreen: false, //флаг или широкоформатка
        nadb_tir: 0, //надбавка на тираж
        print_summ_default: 0, //себестоимость печати
        print_summ: 0, //стоимость печати
        cost_offset: 0, //стоимость работ на стороне
        cost_cutting: 0, //стоимость резки
        lenght_plotter_cutting: 0, //длина реза для плоттерной резки
        cost_plotter_cutting: 0, //стоимость плоттерной резки,
        cost_lamination: 0, //стоимость ламинации
        count_scoring: 0, //количество биговок
        cost_scoring: 0, //стоимость биговки
        count_perforation: 0, //количество перфораций
        cost_perforation: 0, //стоимость перфорации
        count_corner: 0, //количество углов скругления
        cost_corner: 0, //стоимость скругления
        count_hole: 0, //количество отверстий
        cost_hole: 0, //стоимость отверстий
        count_grommet: 0, //количество люверсов
        cost_grommet: 0, //стоимость люверсов
        count_stamp_cutting: 0, //количество ударов (вырубка)
        cost_stamp_cutting: 0, //стоимость вырубки
        cost_stamp_cutting_element: 0, //цена штампа вырубки
        count_hot_stamping: 0, //количество ударов (конгрев)
        cost_hot_stamping: 0, //стоимость конгрева
        cost_hot_stamping_element: 0, //цена штампа конгрева
        count_stamping: 0, //количество ударов (тиснение)
        cost_stamping: 0, //стоимость тиснения
        cost_stamping_element: 0, //цена штампа тиснения
        all_summa: 0, //общая сумма
    }

}

//выбор дизайна
function addViewDesign() {
    //создаем пустую модалку
    CreateModalWindow("ViewDesign", "Выбор дизайна", null, 1500);
    //тело модалки
    var body = document.getElementById("ViewDesign-body");

    //первая таблица
    addTablesDesign(body, "dt_design1", 0);

    //линия между таблицами
    body.appendChild(document.createElement('hr'));

    //вторая таблица
    addTablesDesign(body, "dt_design2", 1);

    //итого
    var row = document.createElement('div');
    row.setAttribute('class', "row");
    body.appendChild(row);
    var col = document.createElement('div');
    col.setAttribute('class', "col-md-4");
    row.appendChild(col);
    var label = document.createElement('label');
    label.appendChild(document.createTextNode("Итого:"));
    col.appendChild(label);
    var col = document.createElement('div');
    col.setAttribute('class', "col-md-4");
    row.appendChild(col);
    var label = document.createElement('label');
    label.setAttribute('id', "ViewDesign_result_time");
    label.appendChild(document.createTextNode("0 часов"));
    col.appendChild(label);
    var col = document.createElement('div');
    col.setAttribute('class', "col-md-4");
    row.appendChild(col);
    var label = document.createElement('label');
    label.setAttribute('id', "ViewDesign_result_cost");
    label.appendChild(document.createTextNode("0 $"));
    col.appendChild(label);

    //перерасчет
    var arr_checheds = ($('#p_prdiz_id').val() != '') ? $('#p_prdiz_id').val().split(',') : new Array();
    if(arr_checheds.length > 0) {
        $('[class=design_check]').each(function (e, f) {
            var val = JSON.parse(this.value);
            if(val && val.ID && arr_checheds.includes("" + val.ID)) {
                this.checked = true;
                this.dispatchEvent(new Event('change'));
            }
        });
    }

    //запускаем модалку
    $('#modal_ViewDesign').modal('show');

}

//создание таблицы для дизайна
function addTablesDesign(bd, id, key) {
    var col = document.createElement("div");
    col.setAttribute('class', "col-lg-12");
    col.setAttribute('style', "padding: 15px;");
    bd.appendChild(col);
    var div = document.createElement('div');
    div.setAttribute('class', "panel panel-default");
    col.appendChild(div);
    var div_ = document.createElement('div');
    div_.setAttribute('class', "panel-body");
    div.appendChild(div_);
    var table = document.createElement('table');
    table.setAttribute('class', "table table-bordered table-hover");
    table.setAttribute('id', id);
    table.setAttribute('width', "100%");
    div_.appendChild(table);
    var thead = document.createElement('thead');
    table.appendChild(thead);
    var tr = document.createElement('tr');
    thead.appendChild(tr);
    var th = document.createElement('th');
    tr.appendChild(th);
    var th = document.createElement('th');
    th.appendChild(document.createTextNode("Операция"));
    tr.appendChild(th);
    var th = document.createElement('th');
    th.appendChild(document.createTextNode("Время"));
    tr.appendChild(th);
    var th = document.createElement('th');
    th.appendChild(document.createTextNode("Стоимость, $"));
    tr.appendChild(th);
    var tbody = document.createElement('tbody');
    table.appendChild(tbody);

    var arrDiz = sendAjax('m=calculator&u=create&a=AjaxDizOper', {'val': key}, null, false, true);
    var arr_check = new Array();
    var a_ids = new Array();
    if(document.getElementById("p_prdiz_") && document.getElementById("p_prdiz_").hasAttribute('data-attr-full')) {
        var json = JSON.parse(document.getElementById("p_prdiz_").getAttribute('data-attr-full'));
        if(json && Array.isArray(json) && json.length > 0) {
            for(var i = 0; i < json.length; i++)
                arr_check.push(json[i]);
        }
    }

    arrDiz.forEach(function(elem) {
        var tr = document.createElement('tr');
        tr.setAttribute('class', "odd gradeX");
        tbody.appendChild(tr);
        var td = document.createElement('td');
        tr.appendChild(td);
        var input = document.createElement('input');
        input.setAttribute('type', "checkbox");
        input.setAttribute('class', "design_check");
        input.setAttribute('value', JSON.stringify(elem));
        input.onchange = function(e) {
            calcDesign(JSON.parse(e.target.value), e.target.checked);
        }
        //проверяем или был отмечен ранее
        for(var i = 0; i < arr_check.length; i++) {
            if(arr_check[i].ID == elem.ID) {
                input.checked = true;
                break;
            }
        }
        td.appendChild(input);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode(elem.NAME));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('style', "text-align: center;");
        td.appendChild(document.createTextNode(elem.TIME_));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('style', "text-align: center;");
        td.appendChild(document.createTextNode(elem.COST));
        tr.appendChild(td);
    });

    //оформляем таблицу
    $('#' + id).DataTable({
        language: {
            url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json"
        },
        responsive: true,
        "iDisplayLength": 5,
        ordering: false
    });
}

//расчет дизайна
function calcDesign(obj, check) {
    removeAllTextNodes(document.getElementById("ViewDesign_result_time"));
    document.getElementById("ViewDesign_result_time").appendChild(document.createTextNode("0 часов"));
    removeAllTextNodes(document.getElementById("ViewDesign_result_cost"));
    document.getElementById("ViewDesign_result_cost").appendChild(document.createTextNode("0 $"));
    if(!obj || check == null || !document.getElementById("p_prdiz_"))
        return;
    var arr = new Array();
    if(document.getElementById("p_prdiz_").hasAttribute('data-attr-full')) {
        var json = JSON.parse(document.getElementById("p_prdiz_").getAttribute('data-attr-full'));
        if(json && Array.isArray(json) && json.length > 0) {
            for(var i = 0; i < json.length; i++)
                arr.push(json[i]);
        }
    }

    //если поставили галочку, то добавляем в массив
    if(check) {
        arr.push(obj);
    }
    //иначе удаляем
    else {
        var arr_tmp = new Array();
        for(var i = 0; i < arr.length; i++) {
            if(arr[i].ID == obj.ID)
                continue;
            arr_tmp.push(arr[i]);
        }
        arr = new Array();
        for(var i = 0; i < arr_tmp.length; i++) {
            arr.push(arr_tmp[i]);
        }
    }

    //рассчитываем показатели
    var summ_time = 0;
    var summ_cost = 0;
    var str_d = '';
    for(var i = 0; i < arr.length; i++) {
        summ_time += parseFloat(arr[i].TIME_);
        summ_cost += parseFloat(arr[i].COST);
        str_d += arr[i].ID + ',';
    }

    summ_time = Math.ceil(summ_time*100/60)/100;
    //summ_cost = Math.ceil(summ_cost*100)/100;
    removeAllTextNodes(document.getElementById("ViewDesign_result_time"));
    document.getElementById("ViewDesign_result_time").appendChild(document.createTextNode(summ_time + " часов"));
    removeAllTextNodes(document.getElementById("ViewDesign_result_cost"));
    document.getElementById("ViewDesign_result_cost").appendChild(document.createTextNode(summ_cost + " $"));
    //пишем в поле дизайна показатели
    document.getElementById("p_prdiz_").setAttribute('data-attr-full', JSON.stringify(arr));
    document.getElementById("p_prdiz_").value = summ_cost;
    document.getElementById("p_prdiz_id").value = (str_d.length > 0) ? str_d.substr(0, str_d.length - 1) : '';
}

//просмотр расчета
function viewResultCalc() {
    if(document.getElementById('calc_result').value == '' || document.getElementById('calc_parts').value == '')
        return;
    var json = JSON.parse(document.getElementById('calc_result').value);
    var arrayCalc = JSON.parse(document.getElementById('calc_parts').value);
    if(!json)
        return;
    if(!arrayCalc || !Array.isArray(arrayCalc) || arrayCalc.length <= 0)
        return;
    //создаем модальное окно
    CreateModalWindow("ResultCalc", "Детальный расчет", null, 1500);
    //тело модалки
    var body = document.getElementById("ResultCalc-body");
    //таблица
    var table = document.createElement('table');
    table.setAttribute('class', "table");
    table.setAttribute('style', "width: 100%");
    body.appendChild(table);
    //заголовок таблицы
    var thead = document.createElement('thead');
    table.appendChild(thead);
    var tr = document.createElement('tr');
    thead.appendChild(tr);
    //столбец названия расчета
    var th = document.createElement('th');
    tr.appendChild(th);
    //столбцы частей
    for(var i = 0; i < arrayCalc.length; i++) {
        var th = document.createElement('th');
        th.appendChild(document.createTextNode("Часть " + (i+1)));
        tr.appendChild(th);
    }
    //тело таблицы
    var tbody = document.createElement('tbody');
    table.appendChild(tbody);
    //заполняем таблицу
    addRowTableResultCalc(tbody, "Количество печатных листов, шт", "count_list_pages", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Надбавка клиента", "surcharge_client", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Надбавка клиента на материал", "surcharge_client_material", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Название материала", "name_material", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Размер материала, мм", "size_material", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество листов материала", "count_list_pages_material", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость материала за единицу, $", "cost_material", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Себестоимость материала, $", "all_cost_material", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость материала с надбавкой, $", "all_cost_material_nds", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость операции печать, $", "cost_print_color", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Широкоформатная печать", "widescreen", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Надбавка на тираж", "nadb_tir", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость печати, $", "print_summ", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость работ на стороне, $", "cost_offset", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость резки, $", "cost_cutting", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Длина реза для плотерной резки, м", "lenght_plotter_cutting", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость плоттерной резки, $", "cost_plotter_cutting", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость ламинации, $", "cost_lamination", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество биговок", "count_scoring", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость биговки, $", "cost_scoring", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество перфораций", "count_perforation", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость перфорации, $", "cost_perforation", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество углов скругления", "count_corner", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость скругления углов, $", "cost_corner", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество отверстий", "count_hole", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость отверстий, $", "cost_hole", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество люверсов", "count_grommet", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость люверсов, $", "cost_grommet", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество ударов для вырубки", "count_stamp_cutting", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость вырубки, $", "cost_stamp_cutting", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость штампа для вырубки, $", "cost_stamp_cutting_element", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество конгревов", "count_hot_stamping", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость конгрева, $", "cost_hot_stamping", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость штампа конгрева, $", "cost_hot_stamping_element", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Количество тиснений", "count_stamping", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость тиснения, $", "cost_stamping", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Стоимость штампа тиснения, $", "cost_stamping_element", arrayCalc, false, 'ResultCalc');
    addRowTableResultCalc(tbody, "Общая себестоимость части, $", "all_summa", arrayCalc, true, 'ResultCalc', 'border-bottom: solid 1px;');

    //пишем данные для всего заказа
    if(json.cost_prepress && json.cost_prepress > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode("Стоимость препресса, $"));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        td.appendChild(document.createTextNode(json.cost_prepress));
        tr.appendChild(td);
    }
    if(json.cost_design && json.cost_design > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode("Стоимость дизайна, $"));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        td.appendChild(document.createTextNode(json.cost_design));
        tr.appendChild(td);
    }
    if(json.cost_binding && json.cost_binding > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode("Стоимость переплета, $"));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        td.appendChild(document.createTextNode(json.cost_binding));
        tr.appendChild(td);
    }
    if(json.urgency && json.urgency > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode("Срочность"));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        td.appendChild(document.createTextNode(json.urgency));
        tr.appendChild(td);
    }
    if(json.all_summ_order && json.all_summ_order > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode("Общая себестоимость заказа, $"));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        td.appendChild(document.createTextNode(json.all_summ_order));
        tr.appendChild(td);
    }
    if(json.all_summ_order_nds_firma && json.all_summ_order_nds_firma > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode("Сумма с надбавкой фирмы, $"));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        td.appendChild(document.createTextNode(json.all_summ_order_nds_firma));
        tr.appendChild(td);
    }
    if(json.all_summ_order_byn && json.all_summ_order_byn > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        tr.appendChild(td);
        var b = document.createElement('b');
        b.appendChild(document.createTextNode("Сумма в белорусских рублях, BYN"));
        td.appendChild(b);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        tr.appendChild(td);
        var b = document.createElement('b');
        b.appendChild(document.createTextNode(json.all_summ_order_byn));
        td.appendChild(b);
    }
    if(json.count_product && json.count_product > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode("Количество изделий"));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        td.appendChild(document.createTextNode(json.count_product));
        tr.appendChild(td);
    }
    if(json.summ_one_product && json.summ_one_product > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.appendChild(document.createTextNode("Сумма за единицу изделия без НДС, BYN"));
        tr.appendChild(td);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        td.appendChild(document.createTextNode(json.summ_one_product + " (за " + json.unit_prod1 + ")"));
        tr.appendChild(td);
    }
    if(json.all_summ_order_byn_calc && json.all_summ_order_byn_calc > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        tr.appendChild(td);
        var b = document.createElement('b');
        b.appendChild(document.createTextNode("Пересчитанная сумма в белорусских рублях, BYN"));
        td.appendChild(b);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length);
        tr.appendChild(td);
        var b = document.createElement('b');
        b.appendChild(document.createTextNode(json.all_summ_order_byn_calc));
        td.appendChild(b);
    }
    //для суммы, введенной вручную
    if(json.total_hand) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        td.setAttribute('colspan', arrayCalc.length + 1);
        tr.appendChild(td);
        var b = document.createElement('b');
        b.appendChild(document.createTextNode("Перерасчет суммы, введенной вручную"));
        td.appendChild(b);
        if(json.summ_one_product_hand) {
            var tr = document.createElement('tr');
            tbody.appendChild(tr);
            var td = document.createElement('td');
            td.appendChild(document.createTextNode("Сумма за единицу изделия без НДС, BYN"));
            tr.appendChild(td);
            var td = document.createElement('td');
            td.setAttribute('colspan', arrayCalc.length);
            td.appendChild(document.createTextNode(json.summ_one_product_hand + " (за " + json.unit_prod1 + ")"));
            tr.appendChild(td);
        }
        if(json.all_summ_order_calc_hand) {
            var tr = document.createElement('tr');
            tbody.appendChild(tr);
            var td = document.createElement('td');
            td.appendChild(document.createTextNode("Сумма с НДС, BYN"));
            tr.appendChild(td);
            var td = document.createElement('td');
            td.setAttribute('colspan', arrayCalc.length);
            td.appendChild(document.createTextNode(json.all_summ_order_calc_hand));
            tr.appendChild(td);
        }
    }

    //открываем модалку
    $('#modal_ResultCalc').modal('show');
}

//функция добавления строки в таблицу расчета
/*
	tbody - тело таблицы
	label - название расчета
	key - ключ в объекта
	arr - массив объектов
	_class - класс
*/
function addRowTableResultCalc(tbody, label, key, arr, fl, _class = '', style = '') {
    if(_class)
        _class = _class + '.';
    /*if(!eval("arr[0]." + _class + key))
        return;*/
    //проверяем или не пустые параметры
    var arr_ = new Array();
    for(var i = 0; i < arr.length; i++) {
        if(eval("arr[i]." + _class + key) && (eval("arr[i]." + _class + key) != "" || eval("arr[i]." + _class + key) > 0)) {
            arr_.push(eval("arr[i]." + _class + key));
        }
    }
    if(arr_.length > 0) {
        var tr = document.createElement('tr');
        tbody.appendChild(tr);
        var td = document.createElement('td');
        if(style) {
            td.setAttribute('style', style);
        }
        if(fl) {
            var b = document.createElement('b');
            b.appendChild(document.createTextNode(label));
            td.appendChild(b);
        }
        else {
            td.appendChild(document.createTextNode(label));
        }
        tr.appendChild(td);
        for(var i = 0; i < arr.length; i++) {
            var td = document.createElement('td');
            if(style) {
                td.setAttribute('style', style);
            }
            if(fl) {
                var b = document.createElement('b');
                b.appendChild(document.createTextNode(eval("arr[i]." + _class + key)));
                td.appendChild(b);
            }
            else {
                td.appendChild(document.createTextNode(eval("arr[i]." + _class + key)));
            }
            tr.appendChild(td);
        }
    }
}

//сохранение расчета
function clickSave() {
    get_price();
    if(!document.getElementById('messageAlert'))
        document.location = '/www/index.php?m=calculator';
}

//загрузка сохраненного расчета
function loadCurrentCalc(data) {
    if(!data && !data.kol)
        return;
    //дорисовываем части
    if(data.kol > 1) {
        for(var i = 1; i < data.kol; i++) {
            rowTable();
        }
    }

    //отдельно пишем оборудование, цвет, бумагу и размер бумаги
    for(var i = 1; i <= data.kol; i++) {
        if(eval("data.p_eq_" + i)) {
            $("#p_eq_" + i).val(eval("data.p_eq_" + i)).change();
            if(eval("data.p_color_" + i)) {
                $("#p_color_" + i).val(eval("data.p_color_" + i)).change();
            }
            if(eval("data.p_mat_" + i)) {
                $("#p_mat_" + i).val(eval("data.p_mat_" + i)).change();
            }
            if(eval("data.p_sizep_" + i)) {
                $("#p_sizep_" + i).val(eval("data.p_sizep_" + i)).change();
            }
            if(eval("data.p_size_cut_op_" + i)) {
                $("#p_size_cut_op_" + i).val(eval("data.p_size_cut_op_" + i)).change();
            }
            if(eval("data.p_size_cut_eq_" + i)) {
                $("#p_size_cut_eq_" + i).val(eval("data.p_size_cut_eq_" + i)).change();
            }
        }
    }

    //пишем параметры
    for(var key in data) {
        if(key == "kol" || key.indexOf("p_eq_") >= 0 || (key.indexOf("p_color_") >= 0 && key.indexOf("_f") < 0  && key.indexOf("_b") < 0) || key.indexOf("p_mat_") >= 0 || key.indexOf("p_sizep_") >= 0 || key.indexOf("p_size_cut_op_") >=0 || key.indexOf("p_size_cut_eq_") >=0)
            continue;
        var keyId = key;
        if(!document.getElementById(key)) {
            var tmp_elem = null
            $('[name=' + key + ']').each(function () {
                keyId = this.id;
                tmp_elem = this;
            });
            if(!document.getElementById(keyId)) {
                if(tmp_elem) {
                    // для радиобаттонов делаем через проход по имени... РАДИОБАТТОНАМ не задавать ИД
                    var n = eval("data." + key);
                    $('[name=' + key + ']').each(function() {
                        if(this.value == n) {
                            this.checked = true;
                        }
                    });
                }
                continue;
            }
        }
        if(typeof(eval("data." + key)) == "boolean" || eval("data." + key) == "true" || eval("data." + key) == "false") {
            if(eval("data." + key) == "true") {
                document.getElementById(keyId).checked = true;
            }
            else if(eval("data." + key) == "false") {
                document.getElementById(keyId).checked = false;
            }
            else {
                document.getElementById(keyId).checked = eval("data." + key);
            }
            $('#' + keyId).change();
        } else if (key == "calcID") {
            // для ИД смотрим или есть
            if(eval("data." + key)) {
                document.getElementById(keyId).value = eval("data." + key);
            }
        }
        else {
            document.getElementById(keyId).value = eval("data." + key);
        }
    }

    //для галочек по умолчанию
    if(!data.ViewPressCheck) {
        document.getElementById('ViewPressCheck').checked = false;
        $('#ViewPressCheck').change();
    }
    for(var i = 1; i <= data.kol; i++) {
        if(!eval('data.pol' + i)) {
            document.getElementById('pol' + i).checked = false;
            $('#pol' + i).change();
        }
    }

    //если был дизайн, то пишем json
    if(data.json_design) {
        document.getElementById("p_prdiz_").setAttribute('data-attr-full', JSON.stringify(data.json_design));
    }

    //ставим единицы измерения если есть количество, а единицы не указаны
    if(data.p_cir && !data.unit_prod1) {
        $("#unit_prod1").val("шт.").change();
    }

    get_price();
}

// событие нажатия на радиобаттон дополнительного цвета
function down_combi(e) {
    if(e.checked) {
        e.checked = false;
        return false;
    }
}

//событие выбора дополнительного цвета
function change_combi(e) {
    if(e.classList[0]) {
        var cl_this = document.getElementsByClassName(e.classList[0]);
        var flag = false;
        var i_flag = 0;
        for(var i in cl_this) {
            if(cl_this[i].checked) {
                i_flag++;
            }
            if(i_flag > 2) {
                flag = true;
                break;
            }
        }
        if(flag) {
            e.checked = false;
            return false;
        }
        get_price();
    }
}
