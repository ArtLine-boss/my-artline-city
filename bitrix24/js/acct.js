/****
	Для справочников переплетов и ламинации актуально следующее:
	nadb_2 - коэффициент для клиента с надбавкой 2;
	nadb_3 - коэффициент для клиента с надбавкой 3;
	nadb_5 - коэффициент для клиента с надбавкой 5;
	nadb_default - коэффициент для остальных клиентов;
****/
//справочник переплетов
var directoryPer = [
	{value: 0, title: {nadb_2: 0, nadb_3: 0, nadb_5: 0, nadb_default: 0}, name: ""},
	{value: 1, title: {nadb_2: 0.3, nadb_3: 0.4, nadb_5: 0.4, nadb_default: 0.7}, name: "пружина 6,4 мм"},
	{value: 14, title: {nadb_2: 0.6, nadb_3: 0.8, nadb_5: 0.8, nadb_default: 1.4}, name: "2 пружины 6,4 мм"},
	{value: 15, title: {nadb_2: 0.9, nadb_3: 1.2, nadb_5: 1.2, nadb_default: 2.1}, name: "3 пружины 6,4 мм"},
	{value: 2, title: {nadb_2: 0.4, nadb_3: 0.45, nadb_5: 0.45, nadb_default: 0.8}, name: "пружина 8,0 мм"},
	{value: 16, title: {nadb_2: 0.8, nadb_3: 0.9, nadb_5: 0.9, nadb_default: 1.6}, name: "2 пружина 8,0 мм"},
	{value: 17, title: {nadb_2: 1.2, nadb_3: 1.35, nadb_5: 1.35, nadb_default: 2.4}, name: "3 пружина 8,0 мм"},
	{value: 3, title: {nadb_2: 0.5, nadb_3: 0.55, nadb_5: 0.55, nadb_default: 0.9}, name: "пружина 9,5 мм"},
	{value: 4, title: {nadb_2: 0.55, nadb_3: 0.6, nadb_5: 0.6, nadb_default: 1}, name: "пружина 11,0 мм"},
	{value: 5, title: {nadb_2: 0.6, nadb_3: 0.65, nadb_5: 0.65, nadb_default: 1.1}, name: "пружина 12,7 мм"},
	{value: 6, title: {nadb_2: 0.65, nadb_3: 0.7, nadb_5: 0.7, nadb_default: 1.5}, name: "пружина 14,3 мм"},
	{value: 7, title: {nadb_2: 0.05, nadb_3: 0.1, nadb_5: 0.1, nadb_default: 0.2}, name: "скоба"},
	{value: 8, title: {nadb_2: 3.5, nadb_3: 5, nadb_5: 4, nadb_default: 6}, name: "Твердая обложка (PUR)"},
	{value: 9, title: {nadb_2: 3.5, nadb_3: 5, nadb_5: 4, nadb_default: 6}, name: "Твердая обложка (скобы)"},
	{value: 10, title: {nadb_2: 3.5, nadb_3: 5, nadb_5: 4, nadb_default: 6}, name: "Твердая обложка"},
	{value: 11, title: {nadb_2: 3.5, nadb_3: 5, nadb_5: 6, nadb_default: 4}, name: "Твердая обложка (пружина)"},
	{value: 12, title: {nadb_2: 0.5, nadb_3: 0.7, nadb_5: 0.5, nadb_default: 1}, name: "термоклей"},
	{value: 13, title: {nadb_2: 0.5, nadb_3: 0.7, nadb_5: 0.5, nadb_default: 1}, name: "нитка"},
];

//справочник для ламинации
var directoryLaminat = [
	{value: 0, title: {nadb_2: 0, nadb_3: 0, nadb_5: 0, nadb_default: 0}, name: ""},
	{value: 1, title: {nadb_2: 0.2, nadb_3: 0.2, nadb_5: 0.2, nadb_default: 0.4}, name: "гл.+гл."},
	{value: 2, title: {nadb_2: 0.4, nadb_3: 0.4, nadb_5: 0.4, nadb_default: 0.8}, name: "мат+мат"},
	{value: 3, title: {nadb_2: 0.1, nadb_3: 0.1, nadb_5: 0.1, nadb_default: 0.2}, name: "гл.+0"},
	{value: 4, title: {nadb_2: 0.1, nadb_3: 0.2, nadb_5: 0.15, nadb_default: 0.4}, name: "мат+0"},
	{value: 5, title: {nadb_2: 0.5, nadb_3: 0.5, nadb_5: 0.5, nadb_default: 1}, name: "цифра"},
	{value: 6, title: {nadb_2: 0.3, nadb_3: 0.3, nadb_5: 0.3, nadb_default: 0.6}, name: "гл.+мат"},
	{value: 7, title: {nadb_2: 0.4, nadb_3: 0.4, nadb_5: 0.4, nadb_default: 0.6}, name: "СофтТач+0"},
	{value: 8, title: {nadb_2: 0.8, nadb_3: 0.8, nadb_5: 0.8, nadb_default: 1}, name: "СофтТач+СофтТач"},
	{value: 9, title: {nadb_2: 0.5, nadb_3: 0.6, nadb_5: 0.55, nadb_default: 1}, name: "СофтТач+мат"},
];

//справочник диаметров
var directoryDiam = [
	{value: 0, name: ""},
	{value: 1, name: "3 мм"},
	{value: 2, name: "4 мм"},
	{value: 4, name: "5 мм"},
	{value: 3, name: "6 мм"},
];

//справочник раскраски люверса
var directoryColorLuv = [
	{value: 0, name: ""},
	{value: 1, name: "4 мм серебро"},
	{value: 2, name: "4 мм золото"},
	{value: 3, name: "4 мм черный"},
	{value: 4, name: "5 мм золото"},
	{value: 5, name: "5 мм серебро"},
];

//глобальные объекты
var directoryGlobal = {
	nds_marerial: 0, //надбавка на материалы
	surcharge: 0, //индекс надбавки клиента
	id_client_db: 0, //ид клиента по внутренней БД
	dollar: 0, //курс доллара
	nds_firma: 0, //надбавка фирмы
	nds: 0, //НДС
}

//загрузка документа
$(document).ready(function() {
	var send_client = null;
	if(document.getElementById("body_content").hasAttribute("data-attr-full")) {
		var json = JSON.parse(document.getElementById("body_content").getAttribute("data-attr-full"));
		if(json)
			send_client = json;
	}
	
	var data_select = null;
	var data_calc_select = null;
	
	//получаем глобальные объекты
	$.ajax({
		type: "GET",
		url: "/bitrix24/modeler/modeler_bitrix.php",
		data: {'globalParameters': JSON.stringify(send_client)},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer) {
					if(answer.nds_marerial)
						directoryGlobal.nds_marerial = parseFloat(answer.nds_marerial);
					if(answer.data_client_db && Array.isArray(answer.data_client_db)) {
						if(answer.data_client_db.length == 1) {
							if(answer.data_client_db[0].surcharge != "")
								directoryGlobal.surcharge = parseInt(answer.data_client_db[0].surcharge);
							directoryGlobal.id_client_db = parseInt(answer.data_client_db[0].id_client_db);
						}
						if(answer.data_client_db.length > 1) {
							selectClientUNP(answer.data_client_db);
						}
					}
					if(answer.dollar)
						directoryGlobal.dollar = parseFloat(answer.dollar.replace(",", "."));
					if(answer.nds_firma)
						directoryGlobal.nds_firma = 1 + parseFloat(answer.nds_firma) / 100;
					if(answer.nds)
						directoryGlobal.nds = 1 + parseFloat(answer.nds) / 100;
					if(answer.data)
						data_select = JSON.parse(answer.data);
					if(answer.data_calc)
						data_calc_select = JSON.parse(answer.data_calc);
				}
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
	//заполняем переплет
	directoryPer.forEach(function(elem) {
		var option_ = document.createElement('option');
		option_.setAttribute('value', elem.value);
		option_.setAttribute('title', JSON.stringify(elem.title));
		option_.setAttribute('style', "display:block;");
		option_.appendChild(document.createTextNode(elem.name));
		document.getElementById("p_per_i").appendChild(option_);
	});
	
	rowTable();
	
	//если есть сохранненный расчет, то загружаем его
	if(data_select) {
		loadCurrentCalc(data_select);
	}
});

//форма выбора клиента при совпадающем УНП или номерах телефона в БД
function selectClientUNP(arr) {
	if(!arr || !Array.isArray(arr) || arr.length <= 0) {
		returnMessage("Что-то пошло не так. Массив клиентов пуст", true);
		return;
	}
	//создаем форму
	CreateModalWindow("selectClientUNP", "Выберите клиента", "btn_selectClientUNP");
	//тело модалки
	var modal_body = document.getElementById("selectClientUNP-body");
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	modal_body.appendChild(row);
	var label = document.createElement('label');
	label.appendChild(document.createTextNode("Выберите клиента из списка:"));
	row.appendChild(label);
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	modal_body.appendChild(row);
	var slct = document.createElement('select');
	slct.setAttribute('id', "selectClient_");
	row.appendChild(slct);
	$('#selectClient_').select2({
		width: "100%"
	});
	for(var i = 0; i < arr.length; i++) {
		var opt = document.createElement('option');
		opt.setAttribute('value', arr[i].id_client_db);
		if(arr[i].surcharge != "") {
			opt.setAttribute('data-surcharge', arr[i].surcharge);
		}
		opt.appendChild(document.createTextNode(arr[i].client_name_db + "(" + arr[i].addr_client_db + ")"));
		document.getElementById("selectClient_").appendChild(opt);
	}
	document.getElementById("selectClient_").selectedIndex = -1;
	
	$("#modal_selectClientUNP").modal('show');
}

//выбор клиента
function btn_selectClientUNP() {
	if(document.getElementById("selectClient_").selectedIndex < 0) {
		returnMessage("Не выбран клиент", true);
		return;
	}
	var elem = document.getElementById("selectClient_");
	directoryGlobal.id_client_db = parseInt(elem.value);
	if(elem.options[elem.selectedIndex].hasAttribute("data-surcharge") && elem.options[elem.selectedIndex].getAttribute("data-surcharge") != "") {
		directoryGlobal.surcharge = parseInt(elem.options[elem.selectedIndex].getAttribute("data-surcharge"));
	}
	$("#modal_selectClientUNP").modal('hide');
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
			document.getElementById("p_ygl+_1").focus();
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
		
		get_price();
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
		
		get_price();
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
	ro.innerHTML = "<input  type = 'text' id='p_namepart_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + " tabindex='" + tabkol + "' >";
	ro = tbl.rows[i++].insertCell(-1);
	//размер изделия
	ro.innerHTML = "<input  type = 'text'  id = 'p_size_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'  >";
	ro = tbl.rows[i++].insertCell(-1);
	//количество страниц
	ro.innerHTML = "<input  type = 'text'  id = 'p_kolstr_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'  >";
	
	ro = tbl.rows[i++].insertCell(-1);
	//оборудование
	var innerOptionsEquipment = "<option value='0' title='0'>Выберите</option>";
	$.ajax({
		type: "GET",
		url: "/bitrix24/modeler/modeler_bitrix.php?loadEquipment",
		data: {},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answers = JSON.parse(respond);
				if(answers && Array.isArray(answers) && answers.length > 0) {
					answers.forEach(function(answer) {
						innerOptionsEquipment += "<option value='" + answer.id + "' style='display:block;' data-attr-full='" + JSON.stringify(answer) + "'>" + answer.eq_name + "</option>";
					});
				}
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
	
	ro.innerHTML = "<select id = 'p_eq_" + kol_part + "' " + " tabindex='" + tabkol + " style='width:100px' " + "> " + innerOptionsEquipment + "</select>";
	
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
		$.ajax({
			type: "GET",
			url: "/bitrix24/modeler/modeler_bitrix.php",
			data: {'loadCurrentEquipmentColor': JSON.stringify(val)},
			cache: false,
			async: false,
			success: function(respond) {
				if(respond) {
					var answers = JSON.parse(respond);
					if(answers && Array.isArray(answers) && answers.length > 0) {
						answers.forEach(function(answer) {
							var option_ = document.createElement('option');
							option_.setAttribute('value', answer.id);
							option_.setAttribute('data-attr', val);
							option_.setAttribute('title', answer.OPERATION_PRICE);
							option_.appendChild(document.createTextNode(answer.par));
							document.getElementById(id).appendChild(option_);
						});
						document.getElementById(id).selectedIndex = -1;
					}
				}
			},
			error: function( jqXHR, textStatus, errorThrown ){
				returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
			}
		});
		//выбор бумаги
		var id = "p_mat_" + id_;
		if(!document.getElementById(id))
			return;
		clearSelect(document.getElementById(id));
		$.ajax({
			type: "GET",
			url: "/bitrix24/modeler/modeler_bitrix.php",
			data: {'loadCurrentEquipmentMaterial': JSON.stringify(val)},
			cache: false,
			async: false,
			success: function(respond) {
				if(respond) {
					document.getElementById(id).innerHTML = respond;
					document.getElementById(id).selectedIndex = -1;
					document.getElementById(id).dispatchEvent(new Event('change'));
				}
			},
			error: function( jqXHR, textStatus, errorThrown ){
				returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
			}
		});
		//выбор размера
		returnSizePrint(id_);
	}
	
	//цвет
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<select id = 'p_color_" + kol_part + "' onblur=" + '"' + "get_price()" + '"' + " tabindex='" + tabkol + "'" + "> <option value='0' data-attr='0' title='0'>Выберите</option> </select>";
	
	//бумага
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<select class='js-example-basic-single js-states form-control chosen'  style='display: block;' id = 'p_mat_" + kol_part + "'  onchange='returnSizePrint(" + kol_part + ")' onblur=" + '"' + "get_price()" + '"' + " tabindex='" + tabkol + "'" + "></select><br><input type='checkbox' " + "' onblur=" + '"' + "get_price()" + '"' + "value='' id='mat_firm" + kol_part + "' >Сырье заказчика";
	$("#p_mat_" + kol_part).select2({
		width: '80%',
		dropdownParent: $('#body_content'),
		placeholder: 'Выберите бумагу'
	});
	
	//размер бумаги
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<select id = 'p_sizep_" + kol_part + "'" + " tabindex='" + tabkol + "' onblur=" + '"' + "get_price()" + '"' + "><option value='0' title='0'>Выберите</option></select>  <input  type = 'hidden'  id = 'p_size_r_" + kol_part + "' size= '10' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'  >&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp <b>Другой размер: </b> <input  type = 'text' tabindex='" + tabkol + "' id = 'p_new_size_" + kol_part + "'  size= '10' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
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
		$.ajax({
			type: "GET",
			url: "/bitrix24/modeler/modeler_bitrix.php",
			data: {'loadMaterialInnerSize': JSON.stringify(send)},
			cache: false,
			async: false,
			success: function(respond) {
				if(respond && JSON.parse(respond)) {
					document.getElementById("p_new_size_" + id_).setAttribute('data-attr-full', respond);
				}
			},
			error: function( jqXHR, textStatus, errorThrown ){
				returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
			}
		});
	}
	
	//резка
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input type='checkbox' tabindex='" + tabkol + "' id = 'p_cut_" + kol_part + "' style='width:5%;position: left;float: left; clear: fight;'  onblur=" + '"' + "get_price()" + '"' + ">  &nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;<b>Плот. Резка(по меткам)</b> <input type='checkbox' tabindex='" + tabkol + "' id = 'p_cut2_" + kol_part + "' style='width:5%;position: rigth;float: rigth; clear: fight;'  onblur=" + '"' + "get_price()" + '"' + "> P= <input  type = 'text'  id = 'p_size_cut_" + kol_part + "' size= '10' onblur=" + '"' + "get_price()" + '"' + "  tabindex='" + tabkol + "'> мм. (Периметр 1-го изделия)";
	
	//ламинирование
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<select id = 'p_lam_" + kol_part + "' tabindex='" + tabkol + "'" + "></select>";
	directoryLaminat.forEach(function(elem) {
		var option_ = document.createElement('option');
		option_.setAttribute('value', elem.value);
		option_.setAttribute('title', JSON.stringify(elem.title));
		option_.appendChild(document.createTextNode(elem.name));
		document.getElementById("p_lam_" + kol_part).appendChild(option_);
	});
	document.getElementById("p_lam_" + kol_part).selectedIndex = -1;
	
	//биговка
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_bug_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	//перфорация
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_perf_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	//скругление углов
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_ygl_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	//количество отверстий
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_otv_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
	//диаметр отверстий
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<select id = 'p_diam_" + kol_part + "' tabindex='" + tabkol + "'" + "></select>";
	directoryDiam.forEach(function(elem) {
		var option_ = document.createElement('option');
		option_.setAttribute('value', elem.value);
		option_.appendChild(document.createTextNode(elem.name));
		document.getElementById("p_diam_" + kol_part).appendChild(option_);
	});
	document.getElementById("p_diam_" + kol_part).selectedIndex = -1;
	
	//люверсы
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_luv_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
	//цвет люверса
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<select id = 'p_colorluv_" + kol_part + "' tabindex='" + tabkol + "'" + "></select>";
	directoryColorLuv.forEach(function(elem) {
		var option_ = document.createElement('option');
		option_.setAttribute('value', elem.value);
		option_.appendChild(document.createTextNode(elem.name));
		document.getElementById("p_colorluv_" + kol_part).appendChild(option_);
	});
	document.getElementById("p_colorluv_" + kol_part).selectedIndex = -1;
	
	//вырубка
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_vir_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
	//конгрев
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_con_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
	//тиснение
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_tis_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
	//работы на стороне
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_off_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
	//цена штампа
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_prstamp_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
	//цена клише конгрев
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_prkl_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
	//цена клише тиснение
	ro = tbl.rows[i++].insertCell(-1);
	ro.innerHTML = "<input  type = 'text' tabindex='" + tabkol + "' id = 'p_prckl_" + kol_part + "' style='width:100%;' onblur=" + '"' + "get_price()" + '"' + "onclick=" + '"' + "get_price()" + '"' + ">";
	
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
	label.appendChild(document.createTextNode("К-во на листе = 0"));
	row_.appendChild(label);
	//листов на тираж
	var row_ = document.createElement('div');
	row_.setAttribute('class', "row");
	col2.appendChild(row_);
	var label = document.createElement('label');
	label.setAttribute('id', "count_on_pages_" + kol_part);
	label.appendChild(document.createTextNode("Листов на тираж = 0"));
	row_.appendChild(label);
	//блок для раскладки
	var row_ = document.createElement('div');
	row_.setAttribute('class', "row");
	col2.appendChild(row_);
	var div = document.createElement('div');
	div.setAttribute('class', "block_table");
	div.setAttribute('id', "block_table_" + kol_part);
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
	//обнуляемся
	document.getElementById("p_sum_all").value = 0;
	if(document.getElementById("body_content").hasAttribute('data-calc-full'))
		document.getElementById("body_content").removeAttribute('data-calc-full')
	if(document.getElementById('kol').value == "")
		return;
	var kol = parseInt(document.getElementById('kol').value);
	var arrayCalc = new Array();
	//проходим по всем элементам
	var all_summ_order = 0;
	for(var i = 1; i <= kol; i++) {
		LayoutOnSheet(i);
		var calc_ = calc(i);
		all_summ_order += calc_.all_summa;
		arrayCalc.push(calc_);
	}
	var calculation = {
		count_product: 0, //количество изделий
		arrayCalc: arrayCalc, //список частей заказа
		cost_binding: 0, //стоимость переплета
		adv_summ_order: all_summ_order, //общая сумма заказа предварительная
		cost_design: 0, //стоимость дизайна
		cost_prepress: 0, //стоимость препресса
		urgency: 0, //срочность
		all_summ_order: all_summ_order, //общая сумма
		all_summ_order_nds_firma: 0, //сумма с надбавкой фирмы
		all_summ_order_nds_firma_byn: 0, //сумма с надбавкой фирмы в BYN
		all_summ_order_nds_byn: 0, //сумма с НДС
		all_summ_order_byn: 0, //общая сумма BYN
		summ_one_product: 0, //сумма за один продукт в BYN
		all_summ_order_byn_calc: 0, //общая сумма BYN пересчитанная
		total: 0, //количество изделий после алгоритма округления
		factor: 1, //коэффициент (шт или тыс шт)
	};
	//количество изделий
	if(document.getElementById("p_cir").value != "") {
		calculation.count_product = parseFloat(document.getElementById("p_cir").value);
		if(document.getElementById("unit_prod1").value == "тыс.шт.")
			calculation.count_product = calculation.count_product * 1000;
		calculation.total = calculation.count_product;
	}
	//стоимость дизайна
	if(document.getElementById("div_p_prdiz_").style.display != "none" && document.getElementById("p_prdiz_").value != "") {
		calculation.cost_design = parseFloat(document.getElementById("p_prdiz_").value);
	}
	//стоимость препресса
	if(document.getElementById("div_p_press_").style.display != "none" && document.getElementById("p_press_").value != "") {
		calculation.cost_prepress = parseFloat(document.getElementById("p_press_").value);
	}
	//цена переплета
	if(document.getElementById("p_per_i").selectedIndex > 0 && arrayCalc.length > 0) {
		var selectedElement = document.getElementById("p_per_i").options[document.getElementById("p_per_i").selectedIndex];
		if(selectedElement.hasAttribute('title')) {
			var json = JSON.parse(selectedElement.getAttribute('title'));
			if(json) {
				switch(directoryGlobal.surcharge) {
					case 2:
						calculation.cost_binding = arrayCalc[0].all_count_product * json.nadb_2;
						break;
					case 3:
						calculation.cost_binding = arrayCalc[0].all_count_product * json.nadb_3;
						break;
					case 5:
						calculation.cost_binding = arrayCalc[0].all_count_product * json.nadb_5;
						break;
					default:
						calculation.cost_binding = arrayCalc[0].all_count_product * json.nadb_default;
						break;
				}
			}
		}
	}
	//срочность
	if(document.getElementById("p_fast").selectedIndex >= 0) {
		calculation.urgency = document.getElementById("p_fast").value;
	}
	
	//рассчитываем полную стоимость
	calculation.all_summ_order = (calculation.all_summ_order + calculation.cost_binding + calculation.cost_design + calculation.cost_prepress) * calculation.urgency;
	//надбавка фирмы
	calculation.all_summ_order_nds_firma = calculation.all_summ_order * directoryGlobal.nds_firma;
	calculation.all_summ_order_nds_firma_byn = calculation.all_summ_order_nds_firma * directoryGlobal.dollar;
	//округляем по алгоритму
	var round = AlgoritmRound(calculation.all_summ_order_nds_firma_byn, calculation.count_product);
	if(round.factor == 1000 && round.summ_one) {
		$("#unit_prod1").val("тыс.шт.");
		document.getElementById("p_cir").value = round.total;
		calculation.total = round.total;
		calculation.factor = round.factor;
	}
	//цена за штуку
	calculation.summ_one_product = round.summ_one;
	//сумма с НДС
	calculation.all_summ_order_nds_byn = round.summ * directoryGlobal.nds;
	//общая пересчитанная сумма
	calculation.all_summ_order_byn_calc = Math.ceil(calculation.all_summ_order_nds_byn * 100) / 100;
	//общая сумма
	calculation.all_summ_order_byn = calculation.all_summ_order_nds_firma_byn * directoryGlobal.nds;
	
	//если введена сумма вручную
	if(document.getElementById("p_sum_all_hand").value != "") {
		var val = parseFloat(document.getElementById("p_sum_all_hand").value) / directoryGlobal.nds;
		var r_val = AlgoritmRound(val, calculation.count_product);
		if(r_val.factor == 1000 && r_val.summ_one) {
			$("#unit_prod1").val("тыс.шт.");
			document.getElementById("p_cir").value = r_val.total;
		}
		calculation.total_hand = r_val.total;
		calculation.factor_hand = r_val.factor;
		//цена за штуку
		calculation.summ_one_product_hand = r_val.summ_one;
		//сумма с НДС
		calculation.all_summ_order_nds_hand = r_val.summ * directoryGlobal.nds;
		//общая пересчитанная сумма
		calculation.all_summ_order_calc_hand = Math.ceil(calculation.all_summ_order_nds_hand * 100) / 100;
		//переписываем введенную сумму
		document.getElementById("p_sum_all_hand").value = calculation.all_summ_order_calc_hand;
	}
	
	//пишем результат
	document.getElementById("body_content").setAttribute('data-calc-full', JSON.stringify(calculation));
	document.getElementById("p_sum_all").value = calculation.all_summ_order_byn_calc;
}

//кнока отмена
function clickCancel() {
	var url = "/bitrix24/index.php";
	if(document.getElementById("body_content").hasAttribute("data-attr-full") && document.getElementById("body_content").getAttribute("data-attr-full") != "") {
		var json = JSON.parse(document.getElementById("body_content").getAttribute("data-attr-full"));
		if(json) {
			var send = {
				'ID': json.client_id
			}
			if(send.ID == 0 && json.company_id > 0) {
				send.ID = json.company_id;
			}
			var pl = "";
			if(json.type_menu == "contact")
				pl = "CRM_CONTACT_LIST_MENU";
			else if(json.type_menu == "company")
				pl = "CRM_COMPANY_LIST_MENU";
			url += "?PLACEMENT=" + pl + "&PLACEMENT_OPTIONS=" + JSON.stringify(send);
		}
	}
	location.href = url;
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
	
	//запрос на сервер
	$.ajax({
		type: "GET",
		url: "/bitrix24/modeler/modeler_bitrix.php",
		data: {'loadCurrentEquipmentSize': JSON.stringify(send)},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answers = JSON.parse(respond);
				if(answers && Array.isArray(answers) && answers.length > 0) {
					var value_selected_index = -1;
					var max_selected_index = 0;
					answers.forEach(function(answer) {
						var option_ = document.createElement('option');
						option_.setAttribute('value', answer.size);
						option_.setAttribute('data-attr', val);
						option_.setAttribute('data-attr-id', answer.s_id);
						option_.setAttribute('data-attr-full', JSON.stringify(answer));
						option_.appendChild(document.createTextNode(answer.size));
						document.getElementById(id).appendChild(option_);
						if(answer.count_sheet && answer.count_sheet.count && answer.count_sheet.count > max_selected_index) {
							max_selected_index = answer.count_sheet.count;
							value_selected_index = answer.size;
						}
					});
					if(value_selected_index != -1)
						document.getElementById(id).value = value_selected_index;
					else
						document.getElementById(id).selectedIndex = -1;
				}
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
}

//раскладка
function LayoutOnSheet(id_) {
	//чистим данные
	document.getElementById("block_table_" + id_).removeAttribute("data-attr-full");
	deleteFooterDOM(document.getElementById("block_table_" + id_));
	removeAllTextNodes(document.getElementById("count_on_sheet_" + id_));
	document.getElementById("count_on_sheet_" + id_).appendChild(document.createTextNode("К-во на листе = 0"));
	removeAllTextNodes(document.getElementById("count_on_pages_" + id_));
	document.getElementById("count_on_pages_" + id_).appendChild(document.createTextNode("Листов на тираж = 0"));
	//объект с данными для раскладки
	var element_cost = {
		size_product: "",
		size_page: "",
		vynos: 0,
		max_fill: false,
		is_fields: false,
		left: 0,
		top: 0,
		spring: false,
		count_pages: 0,
		plotter_cutting: false
	};
	//если задан размер изделия
	if(document.getElementById("p_size_" + id_) && document.getElementById("p_size_" + id_).value != "") {
		element_cost.size_product = document.getElementById("p_size_" + id_).value;
	}
	else if(document.getElementById("p_size") && document.getElementById("p_size").value != "") {
		element_cost.size_product = document.getElementById("p_size").value;
	}
	if(element_cost.size_product == "")
		return;
	var arr_size_product = element_cost.size_product.split("*");
	if(arr_size_product.length != 2)
		return;
	
	//вынос цвета
	if(document.getElementById("vin" + id_).value != "")
		element_cost.vynos = parseFloat(document.getElementById("vin" + id_).value);
	
	//печатный лист
	if(document.getElementById("p_new_size_" + id_).value != "")
		element_cost.size_page = document.getElementById("p_new_size_" + id_).value;
	if(element_cost.size_page == "" && document.getElementById("p_sizep_" + id_).selectedIndex >= 0)
		element_cost.size_page = document.getElementById("p_sizep_" + id_).value;
	if(document.getElementById("p_sizep_" + id_).options.length <= 0) {
		var tmp_e = document.getElementById("p_eq_" + id_).options[document.getElementById("p_eq_" + id_).selectedIndex];
		if(tmp_e.hasAttribute('data-attr-full')) {
			var json = JSON.parse(tmp_e.getAttribute('data-attr-full'));
			if(json) {
				var w = parseFloat(arr_size_product[0]) + 2 * parseFloat(json.ladnr) + 2 * element_cost.vynos;
				var h = parseFloat(arr_size_product[1]) + 2 * parseFloat(json.uandd) + 2 * element_cost.vynos;
				element_cost.size_page = w + "*" + h;
				$("#p_new_size_" + id_).val(element_cost.size_page).change();
			}
		}
	}
	if(element_cost.size_page == "")
		return;
	var arr_size_page = element_cost.size_page.split("*");
	if(arr_size_page.length != 2)
		return;
	//максимальное заполнение
	if(document.getElementById("max" + id_).checked == true)
		element_cost.max_fill = true;
	//непечатные поля
	if(document.getElementById("pol" + id_).checked == true) {
		element_cost.is_fields = true;
		if(document.getElementById("p_eq_" + id_).options[document.getElementById("p_eq_" + id_).selectedIndex].hasAttribute("data-attr-full") && document.getElementById("p_eq_" + id_).options[document.getElementById("p_eq_" + id_).selectedIndex].getAttribute("data-attr-full") != "") {
			var json = JSON.parse(document.getElementById("p_eq_" + id_).options[document.getElementById("p_eq_" + id_).selectedIndex].getAttribute("data-attr-full"));
			if(json) {
				element_cost.left = 2*parseFloat(json.ladnr);
				element_cost.top = 2*parseFloat(json.uandd);
			}
		}
	}
	
	//плоттерная резка
	if(document.getElementById("p_cut2_" + id_).checked) {
		element_cost.left = 50;
		element_cost.top = 70;
	}
	
	//если перерасчет на пружину
	if(document.getElementById("pers" + id_).checked == true) {
		element_cost.spring = true;
	}
	else {
		element_cost.spring = false;
		var w = parseFloat(element_cost.size_product.split("*")[0]);
		var h = parseFloat(element_cost.size_product.split("*")[1]);
		//узкая сторона
		if(document.getElementById("p_stor_i").value == 1) {
			var w_2 = w * 2;
			var h_2 = h * 2;
			if(w_2 >= h_2)
				w = Math.ceil(w_2);
			else
				h = Math.ceil(h_2);
			element_cost.size_product = w + "*" + h;
		}
		//широкая сторона
		else if(document.getElementById("p_stor_i").value == 2) {
			var w_2 = w * 2;
			var h_2 = h * 2;
			if(w_2 <= h_2)
				w = Math.ceil(w_2);
			else
				h = Math.ceil(h_2);
			element_cost.size_product = w + "*" + h;
		}
	}
	
	/*----------------- РАСКЛАДКА ------------------------*/
	//размер продукта
	var w = parseFloat(element_cost.size_product.split("*")[0]);
	var h = parseFloat(element_cost.size_product.split("*")[1]);
	//размер печатного листа
	var w_ = parseFloat(element_cost.size_page.split("*")[0]);
	var h_ = parseFloat(element_cost.size_page.split("*")[1]);
	// 4 варианта раскладки
	var d1 = {
		count: Math.floor((w_ - element_cost.left) / (w + 2 * element_cost.vynos)) * Math.floor((h_ - element_cost.top) / (h + 2 * element_cost.vynos)),
		width_product: w,
		height_product: h,
		width_page: w_,
		height_page: h_,
		width_delta: (w_ - element_cost.left) - Math.floor((w_ - element_cost.left) / (w + 2 * element_cost.vynos)) * (w + 2 * element_cost.vynos),
		height_delta: (h_ - element_cost.top) - Math.floor((h_ - element_cost.top) / (h + 2 * element_cost.vynos)) * (h + 2 * element_cost.vynos),
	}
	var d2 = {
		count: Math.floor((w_ - element_cost.left) / (h + 2 * element_cost.vynos)) * Math.floor((h_ - element_cost.top) / (w + 2 * element_cost.vynos)),
		width_product: h,
		height_product: w,
		width_page: w_,
		height_page: h_,
		width_delta: (w_ - element_cost.left) - Math.floor((w_ - element_cost.left) / (h + 2 * element_cost.vynos)) * (h + 2 * element_cost.vynos),
		height_delta: (h_ - element_cost.top) - Math.floor((h_ - element_cost.top) / (w + 2 * element_cost.vynos)) * (w + 2 * element_cost.vynos),
	}
	var d3 = {
		count: Math.floor((h_ - element_cost.left) / (w + 2 * element_cost.vynos)) * Math.floor((w_ - element_cost.top) / (h + 2 * element_cost.vynos)),
		width_product: w,
		height_product: h,
		width_page: h_,
		height_page: w_,
		width_delta: (h_ - element_cost.left) - Math.floor((h_ - element_cost.left) / (w + 2 * element_cost.vynos)) * (w + 2 * element_cost.vynos),
		height_delta: (w_ - element_cost.top) - Math.floor((w_ - element_cost.top) / (h + 2 * element_cost.vynos)) * (h + 2 * element_cost.vynos),
	}
	var d4 = {
		count: Math.floor((h_ - element_cost.left) / (h + 2 * element_cost.vynos)) * Math.floor((w_ - element_cost.top) / (w + 2 * element_cost.vynos)),
		width_product: h,
		height_product: w,
		width_page: h_,
		height_page: w_,
		width_delta: (h_ - element_cost.left) - Math.floor((h_ - element_cost.left) / (h + 2 * element_cost.vynos)) * (h + 2 * element_cost.vynos),
		height_delta: (w_ - element_cost.top) - Math.floor((w_ - element_cost.top) / (w + 2 * element_cost.vynos)) * (w + 2 * element_cost.vynos),
	}
	
	var d = d1;
	if(d.count < d2.count)
		d = d2;
	if(d.count < d3.count)
		d = d3;
	if(d.count < d4.count)
		d = d4;
	
	if(d.width_page <= 0 || d.height_page <= 0 || d.width_product <= 0 || d.height_product <= 0)
		return;
	
	//считаем количество печатных листов
	var all_count = 0;
	var all_count_sheet = 0;
	if(document.getElementById("p_kolstr_" + id_).value != "")
		all_count_sheet = parseFloat(document.getElementById("p_kolstr_" + id_).value)/2;
	if(document.getElementById("p_cir").value != "") {
		if(document.getElementById("unit_prod1").value == "шт.")
			all_count = parseFloat(document.getElementById("p_cir").value);
		else if(document.getElementById("unit_prod1").value == "тыс.шт.")
			all_count = parseFloat(document.getElementById("p_cir").value) * 1000;
		if(all_count_sheet > 0)
			all_count = all_count * all_count_sheet;
	}
	d.count_pages = Math.ceil(all_count / d.count);
	//на скобу
	if(document.getElementById("p_per_i").value == 7)
		d.count_pages = Math.ceil(d.count_pages / 2);
	document.getElementById("block_table_" + id_).setAttribute("data-attr-full", JSON.stringify(d));
	removeAllTextNodes(document.getElementById("count_on_sheet_" + id_));
	document.getElementById("count_on_sheet_" + id_).appendChild(document.createTextNode("К-во на листе = " + d.count));
	removeAllTextNodes(document.getElementById("count_on_pages_" + id_));
	document.getElementById("count_on_pages_" + id_).appendChild(document.createTextNode("Листов на тираж = " + d.count_pages));
	
	//рисуем картинку
	//пропорция размера
	var koeff = 1;
	if(d.width_page >= d.height_page)
		koeff = 120 / d.width_page;
	else
		koeff = 120 / d.height_page;
	
	//меняем размер поля
	document.getElementById("block_table_" + id_).setAttribute('style', "width:" + d.width_page*koeff + "px; height:" + d.height_page*koeff + "px");
	
	//начальные отступы от краев
	var d_left = (element_cost.left/2) + element_cost.vynos;
	var d_top = (element_cost.top/2) + element_cost.vynos;
	
	//проходим по количеству на странице и раскалдываем
	for(var i = 0; i < d.count; i++) {
		if(d_left > (element_cost.left/2) + element_cost.vynos) {
			//если вышли за пределы страницы, то меняем отступы на начальные
			if((d_left + 2 * element_cost.vynos + (element_cost.left/2) + d.width_product) > d.width_page) {
				d_left = (element_cost.left/2) + element_cost.vynos;
				d_top += d.height_product + 2 * element_cost.vynos;
			}
		}
		var div = document.createElement('div');
		div.setAttribute('class', "bl");
		div.setAttribute('style', "width:" + d.width_product*koeff + "px;height:" + d.height_product*koeff + "px;left:" + d_left*koeff + "px;top:" + d_top*koeff + "px");
		document.getElementById("block_table_" + id_).appendChild(div);
		d_left += d.width_product + 2 * element_cost.vynos;
	}
	
	//если максимальное заполнение
	if(element_cost.max_fill == true) {
		var tmp_width = 0;
		var tmp_height = 0;
		var _d_left = 0;
		var _d_top = 0;
		//определяем или можно впихнуть ещё элементы
		//снизу
		if((d.width_product + 2 * element_cost.vynos) <= d.height_delta) {
			_d_left = (element_cost.left/2) + element_cost.vynos;
			_d_top += d.height_product + element_cost.vynos;
			tmp_width = d.width_page - element_cost.left;
			tmp_height = d.height_delta;
		}
		//справа
		else if((d.height_product + 2 * element_cost.vynos) <= d.width_delta) {
			_d_left = d_left;
			_d_top = (element_cost.top/2) + element_cost.vynos;
			tmp_width = d.width_delta;
			tmp_height = d.height_page - element_cost.top;
		}
		//если есть остаток
		if(tmp_width > 0 && tmp_height > 0) {
			//для этого куска 2 варианта раскладки
			var _d1 = {
				count: Math.floor(tmp_width/(d.width_product + 2*element_cost.vynos)) * Math.floor(tmp_height/(d.height_product + 2*element_cost.vynos)),
				width_product: d.width_product,
				height_product: d.height_product,
			}
			var _d2 = {
				count: Math.floor(tmp_width/(d.height_product + 2*element_cost.vynos)) * Math.floor(tmp_height/(d.width_product + 2*element_cost.vynos)),
				width_product: d.height_product,
				height_product: d.width_product,
			}
			var _d = _d1;
			if(_d.count < _d2.count)
				_d = _d2;
			if(_d.count > 0) {
				//пересчитываем количество на странице и в тираже
				d.count += _d.count;
				d.count_pages = Math.ceil(all_count / d.count);
				document.getElementById("block_table_" + id_).setAttribute("data-attr-full", JSON.stringify(d));
				removeAllTextNodes(document.getElementById("count_on_sheet_" + id_));
				document.getElementById("count_on_sheet_" + id_).appendChild(document.createTextNode("К-во на листе = " + d.count));
				removeAllTextNodes(document.getElementById("count_on_pages_" + id_));
				document.getElementById("count_on_pages_" + id_).appendChild(document.createTextNode("Листов на тираж = " + d.count_pages));
				//рисуем раскладку
				d_left = _d_left;
				d_top = _d_top;
				for(var i = 0; i < _d.count; i++) {
					//если вышли за пределы страницы, то меняем отступы на начальные
					if((d_left + 2 * element_cost.vynos + (element_cost.left/2) + _d.width_product) > d.width_page) {
						d_left = _d_left;
						d_top += _d.height_product + 2 * element_cost.vynos;
					}
					var div = document.createElement('div');
					div.setAttribute('class', "bl");
					div.setAttribute('style', "width:" + _d.width_product*koeff + "px;height:" + _d.height_product*koeff + "px;left:" + d_left*koeff + "px;top:" + d_top*koeff + "px");
					document.getElementById("block_table_" + id_).appendChild(div);
					d_left += _d.width_product + 2 * element_cost.vynos;
				}
			}
		}
	}
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
	/***** РАСЧЕТ МАТЕРИАЛА *****/
	if(document.getElementById("p_new_size_" + id_).value != "") {
		if(document.getElementById("p_new_size_" + id_).hasAttribute('data-attr-full') && document.getElementById("p_new_size_" + id_).getAttribute('data-attr-full') != "") {
			var json = JSON.parse(document.getElementById("p_new_size_" + id_).getAttribute('data-attr-full'));
			//или широкоформатка
			if(json && json.widescreen)
				obj_calc.widescreen = json.widescreen;
			if(json && json.cost && json.count_sheet && json.count_sheet.count) {
				if(!obj_calc.widescreen)
					obj_calc.cost_material = parseFloat(json.cost)/parseFloat(json.count_sheet.count);
				//считаем длину для широкоформатки
				else {
					obj_calc.cost_material = parseFloat(json.cost) * parseFloat(json.widescreen_lenght);
				}
			}
			if(json && json.name_mat)
				obj_calc.name_material = json.name_mat;
			if(json && json.count_sheet && json.count_sheet.width_parent && json.count_sheet.height_parent)
				obj_calc.size_material = json.count_sheet.width_parent + "*" + json.count_sheet.height_parent;
		}
	}
	else if(document.getElementById("p_sizep_" + id_).selectedIndex >= 0) {
		var elem = document.getElementById("p_sizep_" + id_).options[document.getElementById("p_sizep_" + id_).selectedIndex];
		if(elem.hasAttribute('data-attr-full') && elem.getAttribute('data-attr-full') != "") {
			var json = JSON.parse(elem.getAttribute('data-attr-full'));
			if(json && json.material && json.material.cost && json.material.data && json.material.data.count)
				obj_calc.cost_material = parseFloat(json.material.cost)/parseFloat(json.material.data.count);
			if(json && json.material && json.material.name_mat)
				obj_calc.name_material = json.material.name_mat;
			if(json && json.material && json.material.data && json.material.data.width_parent && json.material.data.height_parent)
				obj_calc.size_material = json.material.data.width_parent + "*" + json.material.data.height_parent;
		}
	}
	//стоимость материала
	if(document.getElementById("block_table_" + id_) && document.getElementById("block_table_" + id_).hasAttribute('data-attr-full') && document.getElementById("block_table_" + id_).getAttribute('data-attr-full') != "") {
		var json = JSON.parse(document.getElementById("block_table_" + id_).getAttribute('data-attr-full'));
		if(json && json.count_pages) {
			obj_calc.all_cost_material = parseFloat(json.count_pages) * obj_calc.cost_material;
			obj_calc.count_list_pages = parseInt(json.count_pages);
			obj_calc.count_sheet = parseInt(json.count);
		}
	}
	//для широкоформатного полотна
	if(obj_calc.widescreen) {
		obj_calc.all_cost_material = obj_calc.cost_material;
	}
	/*********определяем надбавки для клиента
	ЭТО ПОЛНОЕ ГОВНО ПЕРЕНЕСЛИ ИЗ СТАРОЙ СИСТЕМЫ
	**********/
	switch(directoryGlobal.surcharge) {
		case 2:
			obj_calc.surcharge_client = 1.1;
			obj_calc.surcharge_client_material = 1.1;
			break;
		case 3:
			obj_calc.surcharge_client = 1.3;
			obj_calc.surcharge_client_material = 1.3;
			break;
		case 5:
			obj_calc.surcharge_client = 1.2;
			obj_calc.surcharge_client_material = 1.2;
			break;
		default:
			obj_calc.surcharge_client = 1.4;
			obj_calc.surcharge_client_material = 1.4;
			break;
	}
	//цена с надбавкой
	obj_calc.all_cost_material_nds = obj_calc.all_cost_material * obj_calc.surcharge_client_material;
	
	//количество в тираже
	if(document.getElementById("p_kolstr_" + id_).value != "")
		obj_calc.count_sheet_block = parseFloat(document.getElementById("p_kolstr_" + id_).value)/2;
	if(document.getElementById("p_cir").value != "") {
		if(document.getElementById("unit_prod1").value == "шт.")
			obj_calc.all_count = parseFloat(document.getElementById("p_cir").value);
		else if(document.getElementById("unit_prod1").value == "тыс.шт.")
			obj_calc.all_count = parseFloat(document.getElementById("p_cir").value) * 1000;
		obj_calc.all_count_product = obj_calc.all_count;
		if(obj_calc.count_sheet_block > 0)
			obj_calc.all_count = obj_calc.all_count * obj_calc.count_sheet_block;
	}
	
	/***** РАСЧЕТ ПЕЧАТИ *****/
	var nadb_max = 0;
	var nadb_min = 0;
	var total_max = 0;
	var total_min = 0;
	if(document.getElementById("p_eq_" + id_).selectedIndex >= 0) {
		var selectedElement = document.getElementById("p_eq_" + id_).options[document.getElementById("p_eq_" + id_).selectedIndex];
		if(selectedElement.hasAttribute('data-attr-full')) {
			var json = JSON.parse(selectedElement.getAttribute('data-attr-full'));
			if(json) {
				nadb_max = json.nadb_max/100;
				nadb_min = json.nadb_min/100;
				total_max = json.total_max;
				total_min = json.total_min;
			}
		}
	}
	//стоимость печати
	if(document.getElementById("p_color_" + id_).selectedIndex >= 0) {
		var selectedElement = document.getElementById("p_color_" + id_).options[document.getElementById("p_color_" + id_).selectedIndex];
		if(selectedElement.hasAttribute('title')) {
			obj_calc.cost_print_color = parseFloat(selectedElement.getAttribute('title'));
		}
	}
	//если широкоформатки
	if(obj_calc.widescreen) {
		//размер изделия
		var scr = 0;
		if(document.getElementById("p_size_" + id_).value != "")
			sqr = parseFloat(eval(document.getElementById("p_size_" + id_).value))/1000000;
		else if(document.getElementById("p_size").value != "")
			sqr = parseFloat(eval(document.getElementById("p_size").value))/1000000;
		obj_calc.cost_print_color = obj_calc.cost_print_color * sqr * obj_calc.all_count;
	}
	/******
	ОПЯТЬ ПЕРЕНОСИМ ИЗ СТАРОЙ СИСТЕМЫ НАБОР ФОРМУЛ
	******/
	if(total_max < obj_calc.count_list_pages)
		total_max = obj_calc.count_list_pages;
	//надбавка за тираж
	var b_ = (nadb_max - nadb_min) / (total_min - total_max);
	var a_ = nadb_max - (b_ * total_min);
	obj_calc.nadb_tir = a_ + b_ * Number(obj_calc.count_list_pages);
	/*var nadbvv = 0;
	if(total_max > 0) {
		nadbvv = (Number(nadb_max) - Number(nadb_min)) / Number(total_max);
	}
	obj_calc.nadb_tir = ((-1 * Number(nadbvv)) * Number(obj_calc.count_list_pages)) + (Number(nadb_max) + Number(nadbvv));*/
	
	//стоимость печати - результат
	obj_calc.print_summ_default = obj_calc.count_list_pages * obj_calc.cost_print_color;
	obj_calc.print_summ = obj_calc.count_list_pages * obj_calc.cost_print_color * obj_calc.surcharge_client * obj_calc.nadb_tir;
	
	/***** ОСТАЛЬНЫЕ ПАРАМЕТРЫ *****/
	//работы на стороне
	if(document.getElementById("p_off_" + id_).value != "") {
		obj_calc.cost_offset = parseFloat(document.getElementById("p_off_" + id_).value);
	}
	//стоимость резки
	if(document.getElementById("p_cut_" + id_).checked) {
		//широкоформатка
		if(obj_calc.widescreen) {
			//из размера материала вытаскиваем погонные метры
			var m_p = obj_calc.size_material.split("*")[1];
			if(m_p) {
				m_p = parseFloat(m_p) / 1000;
				//0.15$ за 1м/п
				obj_calc.cost_cutting = 0.15 * m_p;
			}
		}
		//обычная
		else {
			//берем 10 процентов от суммы печати и материалов
			obj_calc.cost_cutting = 0.1 * (obj_calc.all_cost_material + obj_calc.print_summ_default);
		}
	}
	//плоттерная резка по меткам
	if ((document.getElementById('p_cut2_' + id_).checked) && (document.getElementById('p_size_cut_' + id_).value != "")) {
		//определяем материал
		var matter = "";
		if(document.getElementById('p_mat_' + id_).selectedIndex >= 0 && document.getElementById('p_mat_' + id_).options[document.getElementById('p_mat_' + id_).selectedIndex].hasAttribute("data-opt_gr")) {
			matter = document.getElementById('p_mat_' + id_).options[document.getElementById('p_mat_' + id_).selectedIndex].getAttribute("data-opt_gr");
		}
		//длина реза
		obj_calc.lenght_plotter_cutting = obj_calc.all_count * parseFloat(document.getElementById('p_size_cut_' + id_).value) / 1000;
		obj_calc.lenght_plotter_cutting = Math.ceil(Math.floor(obj_calc.lenght_plotter_cutting * 1000) / 1000);
		//если самоклейка
		if(matter != "Бумага самоклеящаяся" && matter != "Пленка самоклеящаяся") {
			//формируем объект для отправки
			var send = {
				operation: 93,
				width: document.getElementById('p_size_cut_' + id_).value
			}
			//запрос на сервер
			$.ajax({
				type: "GET",
				url: "/pages/pg/modeler.php",
				data: {'CostOperations': JSON.stringify(send)},
				cache: false,
				async: false,
				success: function(respond) {
					if(respond) {
						//получаем стоимсть резки для данной бумаги
						var answer = JSON.parse(respond);
						if(answer) {
							obj_calc.cost_plotter_cutting = obj_calc.lenght_plotter_cutting * parseFloat(answer);
						}
					}
				},
				error: function( jqXHR, textStatus, errorThrown ){
					returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown,true);
				}
			});
		}
		else {
			/****
			ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
			****/
			if (obj_calc.lenght_plotter_cutting > 0 && obj_calc.lenght_plotter_cutting < 10) {
				obj_calc.cost_plotter_cutting = obj_calc.lenght_plotter_cutting * 0.3
			}
			else if (obj_calc.lenght_plotter_cutting >= 10 && obj_calc.lenght_plotter_cutting < 20) {
				obj_calc.cost_plotter_cutting = obj_calc.lenght_plotter_cutting * 0.25
			}
			else if (obj_calc.lenght_plotter_cutting >= 20 && obj_calc.lenght_plotter_cutting < 50) {
				obj_calc.cost_plotter_cutting = obj_calc.lenght_plotter_cutting * 0.2
			}
			else if (obj_calc.lenght_plotter_cutting >= 50 && obj_calc.lenght_plotter_cutting < 100) {
				obj_calc.cost_plotter_cutting = obj_calc.lenght_plotter_cutting * 0.1
			}
			else if (obj_calc.lenght_plotter_cutting >= 100 && obj_calc.lenght_plotter_cutting < 500) {
				obj_calc.cost_plotter_cutting = obj_calc.lenght_plotter_cutting * 0.08
			}
			else if (obj_calc.lenght_plotter_cutting >= 500 && obj_calc.lenght_plotter_cutting < 1000) {
				obj_calc.cost_plotter_cutting = obj_calc.lenght_plotter_cutting * 0.07
			}
			else if (obj_calc.lenght_plotter_cutting >= 1000) {
				obj_calc.cost_plotter_cutting = obj_calc.lenght_plotter_cutting * 0.06
			}
		}
	}
	//ламинация
	if(document.getElementById("p_lam_" + id_).selectedIndex > 0) {
		var selectedElement = document.getElementById("p_lam_" + id_).options[document.getElementById("p_lam_" + id_).selectedIndex];
		if(selectedElement.hasAttribute('title')) {
			var json = JSON.parse(selectedElement.getAttribute('title'));
			if(json) {
				switch(directoryGlobal.surcharge) {
					case 2:
						obj_calc.cost_lamination = obj_calc.count_list_pages * json.nadb_2;
						break;
					case 3:
						obj_calc.cost_lamination = obj_calc.count_list_pages * json.nadb_3;
						break;
					case 5:
						obj_calc.cost_lamination = obj_calc.count_list_pages * json.nadb_5;
						break;
					default:
						obj_calc.cost_lamination = obj_calc.count_list_pages * json.nadb_default;
						break;
				}
			}
		}
	}
	//биговка
	if (document.getElementById('p_bug_' + id_).style.display != "none" && document.getElementById('p_bug_' + id_).value != "") {
		obj_calc.count_scoring = parseFloat((document.getElementById('p_bug_' + id_).value).replace(",", ".")) * obj_calc.count_list_pages * obj_calc.count_sheet;
		/****
		ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
		****/
		if (obj_calc.count_scoring > 0 && obj_calc.count_scoring < 50) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.06
		}
		else if (obj_calc.count_scoring >= 50 && obj_calc.count_scoring < 100) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.05
		}
		else if (obj_calc.count_scoring >= 100 && obj_calc.count_scoring < 200) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.04
		}
		else if (obj_calc.count_scoring >= 200 && obj_calc.count_scoring < 300) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.035
		}
		else if (obj_calc.count_scoring >= 300 && obj_calc.count_scoring < 500) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.03
		}
		else if (obj_calc.count_scoring >= 500 && obj_calc.count_scoring < 1000) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.025
		}
		else if (obj_calc.count_scoring >= 1000 && obj_calc.count_scoring < 2000) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.02
		}
		else if (obj_calc.count_scoring >= 2000 && obj_calc.count_scoring < 3000) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.01
		}
		else if (obj_calc.count_scoring >= 3000 && obj_calc.count_scoring < 5000) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.009
		}
		else if (obj_calc.count_scoring >= 5000) {
			obj_calc.cost_scoring = obj_calc.count_scoring * 0.008
		}
	}
	//перфорация
	if (document.getElementById('p_perf_' + id_).style.display != "none" && document.getElementById('p_perf_' + id_).value != "") {
		obj_calc.count_perforation = parseFloat((document.getElementById('p_perf_' + id_).value).replace(",", ".")) * obj_calc.count_list_pages * obj_calc.count_sheet;
		/****
		ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
		****/
		if (obj_calc.count_perforation > 0 && obj_calc.count_perforation < 50) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.065
		}
		else if (obj_calc.count_perforation >= 50 && obj_calc.count_perforation < 100) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.055
		}
		else if (obj_calc.count_perforation >= 100 && obj_calc.count_perforation < 200) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.045
		}
		else if (obj_calc.count_perforation >= 200 && obj_calc.count_perforation < 300) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.04
		}
		else if (obj_calc.count_perforation >= 300 && obj_calc.count_perforation < 500) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.035
		}
		else if (obj_calc.count_perforation >= 500 && obj_calc.count_perforation < 1000) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.03
		}
		else if (obj_calc.count_perforation >= 1000 && obj_calc.count_perforation < 2000) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.025
		}
		else if (obj_calc.count_perforation >= 2000 && obj_calc.count_perforation < 3000) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.015
		}
		else if (obj_calc.count_perforation >= 3000 && obj_calc.count_perforation < 5000) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.014
		}
		else if (obj_calc.count_perforation >= 5000) {
			obj_calc.cost_perforation = obj_calc.count_perforation * 0.013
		}
	}
	//скругление углов
	if (document.getElementById('p_ygl_' + id_).style.display != "none" && document.getElementById('p_ygl_' + id_).value != "") {
		obj_calc.count_corner = parseFloat((document.getElementById('p_ygl_' + id_).value).replace(",", ".")) * obj_calc.count_list_pages * obj_calc.count_sheet;
		/****
		ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
		****/
		if (obj_calc.count_corner > 0 && obj_calc.count_corner < 50) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.005
		}
		else if (obj_calc.count_corner >= 50 && obj_calc.count_corner < 100) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.004
		}
		else if (obj_calc.count_corner >= 100 && obj_calc.count_corner < 200) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0035
		}
		else if (obj_calc.count_corner >= 200 && obj_calc.count_corner < 300) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.003
		}
		else if (obj_calc.count_corner >= 300 && obj_calc.count_corner < 500) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0025
		}
		else if (obj_calc.count_corner >= 500 && obj_calc.count_corner < 1000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0023
		}
		else if (obj_calc.count_corner >= 1000 && obj_calc.count_corner < 2000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0022
		}
		else if (obj_calc.count_corner >= 2000 && obj_calc.count_corner < 3000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0021
		}
		else if (obj_calc.count_corner >= 3000 && obj_calc.count_corner < 5000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.002
		}
		else if (obj_calc.count_corner >= 5000 && obj_calc.count_corner < 10000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0019
		}
		else if (obj_calc.count_corner >= 10000 && obj_calc.count_corner < 20000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0018
		}
		else if (obj_calc.count_corner >= 20000 && obj_calc.count_corner < 30000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0017
		}
		else if (obj_calc.count_corner >= 30000 && obj_calc.count_corner < 50000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0016
		}
		else if (obj_calc.count_corner >= 50000) {
			obj_calc.cost_corner = obj_calc.count_corner * 0.0015
		}
	}
	//отверстия
	if (document.getElementById('p_otv_' + id_).style.display != "none" && document.getElementById('p_otv_' + id_).value != "") {
		obj_calc.count_hole = parseFloat((document.getElementById('p_otv_' + id_).value).replace(",", ".")) * obj_calc.count_list_pages * obj_calc.count_sheet;
		/****
		ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
		****/
		if (obj_calc.count_hole > 0 && obj_calc.count_hole < 50) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.02
		}
		else if (obj_calc.count_hole >= 50 && obj_calc.count_hole < 100) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.01
		}
		else if (obj_calc.count_hole >= 100 && obj_calc.count_hole < 200) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.009
		}
		else if (obj_calc.count_hole >= 200 && obj_calc.count_hole < 300) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.008
		}
		else if (obj_calc.count_hole >= 300 && obj_calc.count_hole < 500) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.007
		}
		else if (obj_calc.count_hole >= 500 && obj_calc.count_hole < 1000) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.006
		}
		else if (obj_calc.count_hole >= 1000 && obj_calc.count_hole < 2000) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.005
		}
		else if (obj_calc.count_hole >= 2000 && obj_calc.count_hole < 3000) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.004
		}
		else if (obj_calc.count_hole >= 3000) {
			obj_calc.cost_hole = obj_calc.count_hole * 0.003
		}
	}
	//люверс
	if (document.getElementById('p_luv_' + id_).style.display != "none" && document.getElementById('p_luv_' + id_).value != "") {
		obj_calc.count_grommet = parseFloat((document.getElementById('p_luv_' + id_).value).replace(",", ".")) * obj_calc.count_list_pages * obj_calc.count_sheet;
		/****
		ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
		****/
		if (obj_calc.count_grommet > 0 && obj_calc.count_grommet < 50) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.15
		}
		else if (obj_calc.count_grommet >= 50 && obj_calc.count_grommet < 100) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.13
		}
		else if (obj_calc.count_grommet >= 100 && obj_calc.count_grommet < 200) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.12
		}
		else if (obj_calc.count_grommet >= 200 && obj_calc.count_grommet < 300) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.11
		}
		else if (obj_calc.count_grommet >= 300 && obj_calc.count_grommet < 500) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.1
		}
		else if (obj_calc.count_grommet >= 500 && obj_calc.count_grommet < 1000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.09
		}
		else if (obj_calc.count_grommet >= 1000 && obj_calc.count_grommet < 2000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.085
		}
		else if (obj_calc.count_grommet >= 2000 && obj_calc.count_grommet < 3000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.08
		}
		else if (obj_calc.count_grommet >= 3000 && obj_calc.count_grommet < 5000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.075
		}
		else if (obj_calc.count_grommet >= 5000 && obj_calc.count_grommet < 10000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.07
		}
		else if (obj_calc.count_grommet >= 10000 && obj_calc.count_grommet < 20000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.065
		}
		else if (obj_calc.count_grommet >= 20000 && obj_calc.count_grommet < 30000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.06
		}
		else if (obj_calc.count_grommet >= 30000 && obj_calc.count_grommet < 50000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.055
		}
		else if (obj_calc.count_grommet >= 50000) {
			obj_calc.cost_grommet = obj_calc.count_grommet * 0.05
		}
	}
	//вырубка
	if (document.getElementById('p_vir_' + id_).style.display != "none" && document.getElementById('p_vir_' + id_).value != "") {
		obj_calc.count_stamp_cutting = obj_calc.count_list_pages * obj_calc.count_sheet / parseFloat(document.getElementById('p_vir_' + id_).value);
		obj_calc.count_stamp_cutting = Math.ceil(obj_calc.count_stamp_cutting);
		/****
		ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
		****/
		if (obj_calc.count_stamp_cutting > 0 && obj_calc.count_stamp_cutting < 50) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.6
		}
		else if (obj_calc.count_stamp_cutting >= 50 && obj_calc.count_stamp_cutting < 100) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.3
		}
		else if (obj_calc.count_stamp_cutting >= 100 && obj_calc.count_stamp_cutting < 200) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.25
		}
		else if (obj_calc.count_stamp_cutting >= 200 && obj_calc.count_stamp_cutting < 300) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.2
		}
		else if (obj_calc.count_stamp_cutting >= 300 && obj_calc.count_stamp_cutting < 500) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.15
		}
		else if (obj_calc.count_stamp_cutting >= 500 && obj_calc.count_stamp_cutting < 1000) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.1
		}
		else if (obj_calc.count_stamp_cutting >= 1000 && obj_calc.count_stamp_cutting < 2000) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.08
		}
		else if (obj_calc.count_stamp_cutting >= 2000 && obj_calc.count_stamp_cutting < 3000) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.06
		}
		else if (obj_calc.count_stamp_cutting >= 3000 && obj_calc.count_stamp_cutting < 5000) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.05
		}
		else if (obj_calc.count_stamp_cutting >= 5000) {
			obj_calc.cost_stamp_cutting = obj_calc.count_stamp_cutting * 0.04
		}
	}
	//цена штампа вырубки 
	if(document.getElementById('p_prstamp_' + id_).style.display != "none" && document.getElementById('p_prstamp_' + id_).value != "") {
		obj_calc.cost_stamp_cutting_element = parseFloat(document.getElementById('p_prstamp_' + id_).value);
	}
	//конгрев
	if (document.getElementById('p_con_' + id_).style.display != "none" && document.getElementById('p_con_' + id_).value != "") {
		obj_calc.count_hot_stamping = obj_calc.count_list_pages * obj_calc.count_sheet / parseFloat(document.getElementById('p_con_' + id_).value);
		obj_calc.count_hot_stamping = Math.ceil(obj_calc.count_hot_stamping);
		/****
		ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
		****/
		if (obj_calc.count_hot_stamping > 0 && obj_calc.count_hot_stamping < 50) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.8
		}
		else if (obj_calc.count_hot_stamping >= 50 && obj_calc.count_hot_stamping < 100) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.45
		}
		else if (obj_calc.count_hot_stamping >= 100 && obj_calc.count_hot_stamping < 200) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.27
		}
		else if (obj_calc.count_hot_stamping >= 200 && obj_calc.count_hot_stamping < 300) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.23
		}
		else if (obj_calc.count_hot_stamping >= 300 && obj_calc.count_hot_stamping < 500) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.16
		}
		else if (obj_calc.count_hot_stamping >= 500 && obj_calc.count_hot_stamping < 1000) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.09
		}
		else if (obj_calc.count_hot_stamping >= 1000 && obj_calc.count_hot_stamping < 2000) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.08
		}
		else if (obj_calc.count_hot_stamping >= 2000 && obj_calc.count_hot_stamping < 3000) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.07
		}
		else if (obj_calc.count_hot_stamping >= 3000 && obj_calc.count_hot_stamping < 5000) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.06
		}
		else if (obj_calc.count_hot_stamping >= 5000 && obj_calc.count_hot_stamping < 10000) {
			obj_calc.cost_hot_stamping = obj_calc.count_hot_stamping * 0.05
		}
		else if (obj_calc.count_hot_stamping >= 10000) {
			price_luv = obj_calc.count_hot_stamping * 0.04
		}
	}
	//цена штампа конгрева 
	if(document.getElementById('p_prkl_' + id_).style.display != "none" && document.getElementById('p_prkl_' + id_).value != "") {
		obj_calc.cost_hot_stamping_element = parseFloat(document.getElementById('p_prkl_' + id_).value);
	}
	//тиснение
	if (document.getElementById('p_tis_' + id_).style.display != "none" && document.getElementById('p_tis_' + id_).value != "") {
		obj_calc.count_stamping = obj_calc.count_list_pages * obj_calc.count_sheet / parseFloat(document.getElementById('p_tis_' + id_).value);
		/****
		ОЧЕРЕДНОЙ ПЕРЕНОС ИЗ СТАРОЙ СИСТЕМЫ
		****/
		if (obj_calc.count_stamping > 0 && obj_calc.count_stamping < 50) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.9
		}
		else if (obj_calc.count_stamping >= 50 && obj_calc.count_stamping < 100) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.5
		}
		else if (obj_calc.count_stamping >= 100 && obj_calc.count_stamping < 200) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.3
		}
		else if (obj_calc.count_stamping >= 200 && obj_calc.count_stamping < 300) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.25
		}
		else if (obj_calc.count_stamping >= 300 && obj_calc.count_stamping < 500) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.17
		}
		else if (obj_calc.count_stamping >= 500 && obj_calc.count_stamping < 1000) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.1
		}
		else if (obj_calc.count_stamping >= 1000 && obj_calc.count_stamping < 2000) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.09
		}
		else if (obj_calc.count_stamping >= 2000 && obj_calc.count_stamping < 3000) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.08
		}
		else if (obj_calc.count_stamping >= 3000 && obj_calc.count_stamping < 5000) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.07
		}
		else if (obj_calc.count_stamping >= 5000 && obj_calc.count_stamping < 10000) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.06
		}
		else if (obj_calc.count_stamping >= 10000 && obj_calc.count_stamping < 20000) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.05
		}
		else if (obj_calc.count_stamping >= 20000) {
			obj_calc.cost_stamping = obj_calc.count_stamping * 0.04
		}
	}
	//цена штампа тиснения 
	if(document.getElementById('p_prckl_' + id_).style.display != "none" && document.getElementById('p_prckl_' + id_).value != "") {
		obj_calc.cost_stamping_element = parseFloat(document.getElementById('p_prckl_' + id_).value);
	}
	
	/*** ОБЩАЯ СУММА ***/
	obj_calc.all_summa = obj_calc.all_cost_material_nds + obj_calc.print_summ + obj_calc.cost_offset + obj_calc.cost_cutting + obj_calc.cost_plotter_cutting + obj_calc.cost_lamination + obj_calc.cost_scoring + obj_calc.cost_perforation + obj_calc.cost_corner + obj_calc.cost_hole + obj_calc.cost_grommet + obj_calc.cost_stamp_cutting + obj_calc.cost_stamp_cutting_element + obj_calc.cost_hot_stamping + obj_calc.cost_hot_stamping_element + obj_calc.cost_stamping + obj_calc.cost_stamping_element;
	
	
	return obj_calc;
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
	//заполняем таблицу
	$.ajax({
		type: "GET",
		url: "/bitrix24/modeler/modeler_bitrix.php",
		data: {'listDefaultDesign': JSON.stringify(key)},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer && Array.isArray(answer) && answer.length > 0) {
					var arr_check = new Array();
					if(document.getElementById("p_prdiz_") && document.getElementById("p_prdiz_").hasAttribute('data-attr-full')) {
						var json = JSON.parse(document.getElementById("p_prdiz_").getAttribute('data-attr-full'));
						if(json && Array.isArray(json) && json.length > 0) {
							for(var i = 0; i < json.length; i++)
								arr_check.push(json[i]);
						}
					}
					answer.forEach(function(elem) {
						var tr = document.createElement('tr');
						tr.setAttribute('class', "odd gradeX");
						tbody.appendChild(tr);
						var td = document.createElement('td');
						tr.appendChild(td);
						var input = document.createElement('input');
						input.setAttribute('type', "checkbox");
						input.setAttribute('value', JSON.stringify(elem));
						input.onchange = function(e) {
							calcDesign(JSON.parse(e.target.value), e.target.checked);
						}
						//проверяем или был отмечен ранее
						for(var i = 0; i < arr_check.length; i++) {
							if(arr_check[i].id == elem.id) {
								input.checked = true;
								break;
							}
						}
						td.appendChild(input);
						var td = document.createElement('td');
						td.appendChild(document.createTextNode(elem.name));
						tr.appendChild(td);
						var td = document.createElement('td');
						td.setAttribute('style', "text-align: center;");
						td.appendChild(document.createTextNode(elem.time_work));
						tr.appendChild(td);
						var td = document.createElement('td');
						td.setAttribute('style', "text-align: center;");
						td.appendChild(document.createTextNode(elem.cost));
						tr.appendChild(td);
					});
				}
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
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
			if(arr[i].id == obj.id)
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
	for(var i = 0; i < arr.length; i++) {
		summ_time += parseFloat(arr[i].time_work);
		summ_cost += parseFloat(arr[i].cost);
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
}

//просмотр расчета
function viewResultCalc() {
	if(!document.getElementById("body_content") || !document.getElementById("body_content").hasAttribute("data-calc-full"))
		return;
	var json = JSON.parse(document.getElementById("body_content").getAttribute("data-calc-full"));
	if(!json)
		return;
	if(!json.arrayCalc || !Array.isArray(json.arrayCalc) || json.arrayCalc.length <= 0)
		return;
	//создаем модальное окно
	CreateModalWindow("ResultCalc", "Детальный расчет", null, 1500);
	//тело модалки
	var body = document.getElementById("ResultCalc-body");
	//таблица
	var table = document.createElement('table');
	table.setAttribute('class', "table");
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
	for(var i = 0; i < json.arrayCalc.length; i++) {
		var th = document.createElement('th');
		th.appendChild(document.createTextNode("Часть " + (i+1)));
		tr.appendChild(th);
	}
	//тело таблицы
	var tbody = document.createElement('tbody');
	table.appendChild(tbody);
	//заполняем таблицу
	addRowTableResultCalc(tbody, "Количество печатных листов, шт", "count_list_pages", json.arrayCalc);
	addRowTableResultCalc(tbody, "Надбавка клиента", "surcharge_client", json.arrayCalc);
	addRowTableResultCalc(tbody, "Надбавка клиента на материал", "surcharge_client_material", json.arrayCalc);
	addRowTableResultCalc(tbody, "Название материала", "name_material", json.arrayCalc);
	addRowTableResultCalc(tbody, "Размер материала, мм", "size_material", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость материала за единицу, $", "cost_material", json.arrayCalc);
	addRowTableResultCalc(tbody, "Себестоимость материала, $", "all_cost_material", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость материала с надбавкой, $", "all_cost_material_nds", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость операции печать, $", "cost_print_color", json.arrayCalc);
	addRowTableResultCalc(tbody, "Широкоформатная печать", "widescreen", json.arrayCalc);
	addRowTableResultCalc(tbody, "Надбавка на тираж", "nadb_tir", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость печати, $", "print_summ", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость работ на стороне, $", "cost_offset", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость резки, $", "cost_cutting", json.arrayCalc);
	addRowTableResultCalc(tbody, "Длина реза для плотерной резки, м", "lenght_plotter_cutting", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость плоттерной резки, $", "cost_plotter_cutting", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость ламинации, $", "cost_lamination", json.arrayCalc);
	addRowTableResultCalc(tbody, "Количество биговок", "count_scoring", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость биговки, $", "cost_scoring", json.arrayCalc);
	addRowTableResultCalc(tbody, "Количество перфораций", "count_perforation", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость перфорации, $", "cost_perforation", json.arrayCalc);
	addRowTableResultCalc(tbody, "Количество углов скругления", "count_corner", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость скругления углов, $", "cost_corner", json.arrayCalc);
	addRowTableResultCalc(tbody, "Количество отверстий", "count_hole", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость отверстий, $", "cost_hole", json.arrayCalc);
	addRowTableResultCalc(tbody, "Количество люверсов", "count_grommet", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость люверсов, $", "cost_grommet", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость отверстий, $", "count_stamp_cutting", json.arrayCalc);
	addRowTableResultCalc(tbody, "Количество ударов для вырубки", "cost_hole", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость отверстий, $", "count_stamp_cutting", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость вырубки, $", "cost_stamp_cutting", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость штампа для вырубки, $", "cost_stamp_cutting_element", json.arrayCalc);
	addRowTableResultCalc(tbody, "Количество конгревов", "count_hot_stamping", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость конгрева, $", "cost_hot_stamping", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость штампа конгрева, $", "cost_hot_stamping_element", json.arrayCalc);
	addRowTableResultCalc(tbody, "Количество тиснений", "count_stamping", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость тиснения, $", "cost_stamping", json.arrayCalc);
	addRowTableResultCalc(tbody, "Стоимость штампа тиснения, $", "cost_stamping_element", json.arrayCalc);
	addRowTableResultCalc(tbody, "Общая себестоимость части, $", "all_summa", json.arrayCalc, true);
	
	//пишем данные для всего заказа
	if(json.cost_prepress && json.cost_prepress > 0) {
		var tr = document.createElement('tr');
		tbody.appendChild(tr);
		var td = document.createElement('td');
		td.appendChild(document.createTextNode("Стоимость препресса, $"));
		tr.appendChild(td);
		var td = document.createElement('td');
		td.setAttribute('colspan', json.arrayCalc.length);
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
		td.setAttribute('colspan', json.arrayCalc.length);
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
		td.setAttribute('colspan', json.arrayCalc.length);
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
		td.setAttribute('colspan', json.arrayCalc.length);
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
		td.setAttribute('colspan', json.arrayCalc.length);
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
		td.setAttribute('colspan', json.arrayCalc.length);
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
		td.setAttribute('colspan', json.arrayCalc.length);
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
		td.setAttribute('colspan', json.arrayCalc.length);
		td.appendChild(document.createTextNode(json.count_product));
		tr.appendChild(td);
	}
	if(json.summ_one_product && json.summ_one_product > 0) {
		var tr = document.createElement('tr');
		tbody.appendChild(tr);
		var td = document.createElement('td');
		td.appendChild(document.createTextNode("Сумма за одно изделие без НДС, BYN"));
		tr.appendChild(td);
		var td = document.createElement('td');
		td.setAttribute('colspan', json.arrayCalc.length);
		td.appendChild(document.createTextNode(json.summ_one_product));
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
		td.setAttribute('colspan', json.arrayCalc.length);
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
		td.setAttribute('colspan', json.arrayCalc.length + 1);
		tr.appendChild(td);
		var b = document.createElement('b');
		b.appendChild(document.createTextNode("Перерасчет суммы, введенной вручную"));
		td.appendChild(b);
		if(json.summ_one_product_hand) {
			var tr = document.createElement('tr');
			tbody.appendChild(tr);
			var td = document.createElement('td');
			td.appendChild(document.createTextNode("Сумма за одно изделие без НДС, BYN"));
			tr.appendChild(td);
			var td = document.createElement('td');
			td.setAttribute('colspan', json.arrayCalc.length);
			td.appendChild(document.createTextNode(json.summ_one_product_hand));
			tr.appendChild(td);
		}
		if(json.all_summ_order_calc_hand) {
			var tr = document.createElement('tr');
			tbody.appendChild(tr);
			var td = document.createElement('td');
			td.appendChild(document.createTextNode("Сумма с НДС, BYN"));
			tr.appendChild(td);
			var td = document.createElement('td');
			td.setAttribute('colspan', json.arrayCalc.length);
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
*/
function addRowTableResultCalc(tbody, label, key, arr, fl) {
	if(!eval("arr[0]." + key))
		return;
	//проверяем или не пустые параметры
	var arr_ = new Array();
	for(var i = 0; i < arr.length; i++) {
		if(eval("arr[i]." + key) != "" || eval("arr[i]." + key) > 0) {
			arr_.push(eval("arr[i]." + key));
		}
	}
	if(arr_.length > 0) {
		var tr = document.createElement('tr');
		tbody.appendChild(tr);
		var td = document.createElement('td');
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
			if(fl) {
				var b = document.createElement('b');
				b.appendChild(document.createTextNode(eval("arr[i]." + key)));
				td.appendChild(b);
			}
			else {
				td.appendChild(document.createTextNode(eval("arr[i]." + key)));
			}
			tr.appendChild(td);
		}
	}
}

//сохранение расчета
function clickSave() {
	get_price();
	//название обязательное
	if(document.getElementById("p_names").value == "") {
		returnMessage("Не задано наименование расчета");
		return;
	}
	//объект для сохранения
	var obj_save = {
		name: document.getElementById("p_names").value,
		total: 0,
		summ: 0,
		summ_one: 0,
		client_id: 0,
		company_id: 0,
		client_id_db: 0,
		factor: 1,
	}
	//собираем данные
	var body = document.getElementById("body_content");
	if(!body.hasAttribute('data-attr-full')) {
		returnMessage("Произошла непредвиденная ошибка. Закройте текущий расчет и откройте заново", true);
		return;
	}
	//определяем клиент и компанию по Битрикс24
	var json = JSON.parse(body.getAttribute('data-attr-full'));
	if(!json || (!json.client_id && !json.company_id) || !json.user_id) {
		returnMessage("Произошла непредвиденная ошибка: пустой объект. Закройте текущий расчет и откройте заново", true);
		return;
	}
	obj_save.client_id = json.client_id;
	obj_save.user_id = json.user_id;
	if(json.company_id)
		obj_save.company_id = json.company_id;
	//ид заявки если есть
	if(json.id_task)
		obj_save.id = json.id_task;
	//если получили ид клиента по внутренней БД
	if(directoryGlobal.id_client_db && directoryGlobal.id_client_db > 0)
		obj_save.client_id_db = directoryGlobal.id_client_db;
	
	//проходим по инпутам
	var inputs = body.getElementsByTagName('input');
	//объект для данных
	var data = {}
	for(var i = 0; i < inputs.length; i++) {
		//отдельно флаги обрабатываем
		if(inputs[i].getAttribute('type') == "checkbox") {
			if(inputs[i].checked)
				eval("data." + inputs[i].getAttribute('id') + "=true");
			else
				eval("data." + inputs[i].getAttribute('id') + "=false");
		}
		//все остальные
		else {
			if(inputs[i].value != "") {
				eval("data." + inputs[i].getAttribute('id') + "='" + inputs[i].value + "'");
			}
		}
	}
	//проходим по селектам
	var selects = body.getElementsByTagName('select');
	for(var i = 0; i < selects.length; i++) {
		if(selects[i].selectedIndex >= 0)
			eval("data." + selects[i].getAttribute('id') + "='" + selects[i].value + "'");
	}
	//пишем комменты
	if(document.getElementById("list_comm").value != "")
		data.list_comm = document.getElementById("list_comm").value;
	//отдельно json дизайна
	if(document.getElementById("div_p_prdiz_").style.display != "none") {
		if(document.getElementById("p_prdiz_").hasAttribute('data-attr-full')) {
			data.json_design = JSON.parse(document.getElementById("p_prdiz_").getAttribute('data-attr-full'));
		}
	}
	
	obj_save.data = JSON.stringify(data);
	
	//пишем расчет
	if(body.hasAttribute('data-calc-full')) {
		obj_save.data_calc = body.getAttribute('data-calc-full');
		var json = JSON.parse(obj_save.data_calc);
		if(json) {
			if(json.total_hand && json.factor_hand && json.all_summ_order_calc_hand && json.summ_one_product_hand) {
				obj_save.total = json.total_hand;
				obj_save.factor = json.factor_hand;
				obj_save.summ = json.all_summ_order_calc_hand;
				obj_save.summ_one = json.summ_one_product_hand;
			}
			else {
				obj_save.total = json.total;
				obj_save.factor = json.factor;
				obj_save.summ = json.all_summ_order_byn_calc;
				obj_save.summ_one = json.summ_one_product;
			}
		}
	}
	
	//отправляем на сервер
	$.ajax({
		type: "POST",
		url: "/bitrix24/modeler/modeler_bitrix.php",
		data: {'saveCalc': JSON.stringify(obj_save)},
		cache: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer) {
					if(answer.error) {
						returnMessage(answer.message);
					}
					else {
						clickCancel();
					}
				}
				else {
					returnMessage("Неизвестная ошибка при сохранении. Нет возвращаемого объекта");
				}
			}
			else {
				returnMessage("Неизвестная ошибка при сохранении. Пустой ответ");
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
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
	
	//пишем параметры
	for(var key in data) {
		if(key == "kol" || key.indexOf("p_eq_") >= 0 || key.indexOf("p_color_") >= 0 || key.indexOf("p_mat_") >= 0 || key.indexOf("p_sizep_") >= 0)
			continue;
		if(!document.getElementById(key))
			continue;
		if(typeof(eval("data." + key)) == "boolean" || eval("data." + key) == "true" || eval("data." + key) == "false") {
			if(eval("data." + key) == "true") {
				document.getElementById(key).checked = true;
			}
			else if(eval("data." + key) == "false") {
				document.getElementById(key).checked = false;
			}
			else {
				document.getElementById(key).checked = eval("data." + key);
			}
			$('#' + key).change();
		}
		else {
			document.getElementById(key).value = eval("data." + key);
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

//алгоритм определения как считать округление
function AlgoritmRound(summ, total) {
	var r_sum = Math.ceil((summ / total) * 100) / 100;
	var r_sum_no = summ/total;
	var return_ = {};
	if((1 - r_sum_no / r_sum) < 0.02 || r_sum_no >= 0.01) {
		return_.factor = 1;
		return_.summ_one = r_sum;
		return_.summ = Math.round(100 * r_sum * total) / 100;
		return_.total = total;
	}
	else {
		return_.factor = 1000;
		return_.summ_one = Math.ceil((summ * 1000 / total) * 100) / 100;
		return_.total = total / 1000;
		return_.summ = Math.round(100 * return_.summ_one * return_.total) / 100;
	}
	
	return return_;
}
