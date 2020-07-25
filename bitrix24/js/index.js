/*********
	Некоторое описание запросов в битрикс24.
	
	ENTITY_TYPE_ID:
		3. Контакт
		4. Компания
		8 - Реквизит
	TYPE_ID:
		1. Фактический адрес
		4. Адрес регистрации
		6. Юридический адрес
		9. Адрес бенефициара

*********/

var globalData = {
	client_id: null,
	client_phone: null,
	company_id: null,
	company_unp: null,
	id_task: null,
	type_menu: "contact"
}

//загрузка документа
$(document).ready(function() {
	var type_menu = document.getElementById("user_name").getAttribute("data-type-menu");
	BX24.init(function() {
		BX24.callMethod('user.current', {}, function(res) {
			if(res.status == 200) {
				//рисуем страницу
				if(type_menu == "contact") {
					globalData.type_menu = type_menu;
					viewStartContact(res);
				}
				else if(type_menu == "company") {
					globalData.type_menu = type_menu;
					viewStartCompany(res);
				}
			}
		});
	});
});

//стартуемая страница контактов
function viewStartContact(res) {
	if(!res.data().ID || res.data().ID <= 0)
		return;
	document.getElementById("user_name").setAttribute('data-attr-current', JSON.stringify(res.data()));
	
	//ФИО клиента
	removeAllTextNodes(document.getElementById("user_name"));
	
	if(!document.getElementById("user_name").hasAttribute('data-attr') || document.getElementById("user_name").getAttribute('data-attr') == "")
		return;
	
	globalData.client_id = parseInt(document.getElementById("user_name").getAttribute('data-attr'));
	
	BX24.callMethod(
		"crm.contact.get", 
		{ id: document.getElementById("user_name").getAttribute('data-attr') }, 
		function(result) 
		{
			if(result.error())
				console.error(result.error());
			else
			{
				var dt = result.data();
				if(!dt)
					return;
				var user_name = "";
				if(dt.LAST_NAME && dt.LAST_NAME != "")
					user_name += dt.LAST_NAME + " ";
				user_name += dt.NAME;
				if(dt.SECOND_NAME && dt.SECOND_NAME != "")
					user_name += " " + dt.SECOND_NAME;
				document.getElementById("user_name").appendChild(document.createTextNode(user_name));
				//пишем список телефонов если есть
				if(dt.PHONE)
					document.getElementById("user_name").setAttribute('data-phone', JSON.stringify(dt.PHONE));
			}
		}
	);
	
	//наименование компании (если есть)
	removeAllTextNodes(document.getElementById("company_name"));
	BX24.callMethod(
		"crm.contact.company.items.get", 
		{
			id: document.getElementById("user_name").getAttribute('data-attr') 
		}, 
		function(result) {
			if(result.error())
				console.error(result.error());
			else {
				var dt = result.data();
				if(!dt || !Array.isArray(dt) || dt.length <= 0)
					return;
				
				var arr_client_id = new Array();		
				
				dt.forEach(function(elem) {
					BX24.callMethod(
						"crm.company.get", 
						{ id: elem.COMPANY_ID }, 
						function(result_) 
						{
							if(result_.error())
								console.error(result_.error());
							else {
								var cn = result_.data();
								if(cn && cn.TITLE != "") {
									var option_ = document.createElement('option');
									option_.setAttribute('value', elem.COMPANY_ID);
									option_.appendChild(document.createTextNode(cn.TITLE));
									//пишем УНП
									BX24.callMethod(
										"crm.requisite.list",
										{
											filter: {"ENTITY_ID": elem.COMPANY_ID, "ENTITY_TYPE_ID": 4}
										},
										function(_result_) {
											if(_result_.error())
												console.error(_result_.error());
											else {
												var cn_ = _result_.data();
												var json_arr = new Array();
												for(var i = 0; i < cn_.length; i++) {
													if(cn_[i].RQ_INN && cn_[i].RQ_INN != "") {
														json_arr.push(cn_[i].RQ_INN);
													}
												}
												option_.setAttribute('data-unp', JSON.stringify(json_arr));
											}
										}
									);
									document.getElementById("company_name").appendChild(option_);
									//ставим первую контору в выбор
									if(document.getElementById("company_name").selectedIndex < 1) {
										document.getElementById("company_name").selectedIndex = 1;
										document.getElementById("company_name").dispatchEvent(new Event('change'));
									}
								}
							}
						}
					);
				});
			}
		}
	);
	document.getElementById("company_name").dispatchEvent(new Event('change'));
}

//стартуемая страница компаний
function viewStartCompany(res) {
	if(!res.data().ID || res.data().ID <= 0)
		return;
	document.getElementById("user_name").setAttribute('data-attr-current', JSON.stringify(res.data()));
	
	//ФИО клиента
	removeAllTextNodes(document.getElementById("user_name"));
	
	if(!document.getElementById("user_name").hasAttribute('data-attr') || document.getElementById("user_name").getAttribute('data-attr') == "")
		return;
	
	globalData.client_id = 0;
	globalData.company_id = parseInt(document.getElementById("user_name").getAttribute('data-attr'));
	
	//наименование компании (если есть)
	BX24.callMethod(
		"crm.company.get", 
		{ id: globalData.company_id}, 
		function(result_) 
		{
			if(result_.error())
				console.error(result_.error());
			else {
				var cn = result_.data();
				if(cn && cn.TITLE != "") {
					document.getElementById("user_name").appendChild(document.createTextNode(cn.TITLE));
					//пишем УНП
					BX24.callMethod(
						"crm.requisite.list",
						{
							filter: {"ENTITY_ID": globalData.company_id, "ENTITY_TYPE_ID": 4}
						},
						function(_result_) {
							if(_result_.error())
								console.error(_result_.error());
							else {
								var cn_ = _result_.data();
								var json_arr = new Array();
								for(var i = 0; i < cn_.length; i++) {
									if(cn_[i].RQ_INN && cn_[i].RQ_INN != "") {
										json_arr.push(cn_[i].RQ_INN);
									}
								}
								document.getElementById("user_name").setAttribute('data-unp', JSON.stringify(json_arr));
								globalData.company_unp = JSON.stringify(json_arr);
								loadFunction();
							}
						}
					);
				}
			}
		}
	);
}

//кнопка Новый расчет
function clickNewCalc(id) {
	if(!globalData || globalData.client_id == null) {
		returnMessage("Произошла непредвидемая ошибка! Откройте приложение заново", true);
		return;
	}
	
	if(document.getElementById("user_name").hasAttribute('data-attr-current') && document.getElementById("user_name").getAttribute('data-attr-current') != "") {
		var json = JSON.parse(document.getElementById("user_name").getAttribute('data-attr-current'));
		globalData.user_id = parseInt(json.ID);
	}
	else {
		returnMessage("Произошла непредвидемая ошибка! Откройте приложение заново", true);
		return;
	}
	
	//если есть номер телефона контакта
	if(document.getElementById("user_name").hasAttribute('data-phone') && document.getElementById("user_name").getAttribute('data-phone') != "")
		globalData.client_phone = document.getElementById("user_name").getAttribute('data-phone');
	
	if(globalData.type_menu == "contact") {
		//определяем компанию
		if(document.getElementById("company_name").selectedIndex < 1)
			globalData.company_id = 0;
		else
			globalData.company_id = document.getElementById("company_name").value;
		//если есть унп компании
		if(document.getElementById("company_name").selectedIndex > 0) {
			var com_n = document.getElementById("company_name").options[document.getElementById("company_name").selectedIndex];
			if(com_n) {
				if(com_n.hasAttribute('data-unp') && com_n.getAttribute('data-unp') != "")
					globalData.company_unp = com_n.getAttribute('data-unp');
			}
		}
	}
	
	if(id && Number(id) > 0)
		globalData.id_task = Number(id);
	
	//формируем ссылку
	var url = "/bitrix24/acct.php?dataLoad=" + JSON.stringify(globalData);
	location.href = url;
}

//загрузка таблицы
function loadFunction() {
	//чистим данные
	deleteFooterDOM(document.getElementById("container_table_calc"));
	
	if(!document.getElementById("user_name").hasAttribute('data-attr'))
		return;
	var send = {
		client_id: parseInt(document.getElementById("user_name").getAttribute('data-attr')),
		company_id: 0
	}
	if(globalData.type_menu == "contact") {
		if(document.getElementById("company_name").selectedIndex >= 0)
			send.company_id = parseInt(document.getElementById("company_name").value);
	}
	else {
		send.client_id = 0;
		send.company_id = parseInt(document.getElementById("user_name").getAttribute('data-attr'));
	}
	
	//рисуем только для одного менеджера
	if(!document.getElementById("listForAllUser").checked) {
		if(!document.getElementById("user_name").hasAttribute('data-attr-current'))
			return;
		var json = JSON.parse(document.getElementById("user_name").getAttribute('data-attr-current'));
		send.user_id = json.ID;
	}
	
	//создаем таблицу
	var table = document.createElement('table');
	table.setAttribute('id', "listCalc");
	table.setAttribute('class', "c-table");
	document.getElementById("container_table_calc").appendChild(table);
	var thead = document.createElement('thead');
	table.appendChild(thead);
	var tr = document.createElement('tr');
	thead.appendChild(tr);
	var th = document.createElement('th');
	tr.appendChild(th);
	var th = document.createElement('th');
	th.appendChild(document.createTextNode("Дата"));
	tr.appendChild(th);
	var th = document.createElement('th');
	th.appendChild(document.createTextNode("Название"));
	tr.appendChild(th);
	var th = document.createElement('th');
	th.appendChild(document.createTextNode("Номер счета"));
	tr.appendChild(th);
	var th = document.createElement('th');
	th.appendChild(document.createTextNode("Количество"));
	tr.appendChild(th);
	var th = document.createElement('th');
	th.appendChild(document.createTextNode("Цена за единицу"));
	tr.appendChild(th);
	var th = document.createElement('th');
	th.appendChild(document.createTextNode("Сумма"));
	tr.appendChild(th);
	var th = document.createElement('th');
	tr.appendChild(th);
	var th = document.createElement('th');
	tr.appendChild(th);
	table.appendChild(document.createElement('tbody'));
	
	//запрос на сервер
	$.ajax({
		type: "GET",
		url: "/bitrix24/modeler/modeler_bitrix.php",
		data: {'listCalcForUsers': JSON.stringify(send)},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer && Array.isArray(answer) && answer.length > 0) {
					var tbody = document.getElementById("listCalc").getElementsByTagName('tbody')[0];
					if(tbody) {
						answer.forEach(function(elem) {
							var tr = document.createElement('tr');
							tr.setAttribute('data-full-attr', JSON.stringify(elem));
							tbody.appendChild(tr);
							//радиобаттон
							var td = document.createElement('td');
							tr.appendChild(td);
							var input = document.createElement('input');
							input.setAttribute('type', "checkbox");
							input.setAttribute('name', "selectedRow");
							input.setAttribute('value', JSON.stringify(elem));
							td.appendChild(input);
							//дата
							var td = document.createElement('td');
							td.appendChild(document.createTextNode(elem.date_add));
							tr.appendChild(td);
							//название
							var td = document.createElement('td');
							td.appendChild(document.createTextNode(elem.name));
							tr.appendChild(td);
							//номер счета
							var td = document.createElement('td');
							td.setAttribute('align', "center");
							if(elem.order_id)
								td.appendChild(document.createTextNode(elem.order_id));
							tr.appendChild(td);
							//количество
							var td = document.createElement('td');
							td.setAttribute('align', "center");
							var total = Number(elem.total) * Number(elem.factor);
							td.appendChild(document.createTextNode(total));
							tr.appendChild(td);
							//цена за единицу
							var td = document.createElement('td');
							td.setAttribute('align', "center");
							if(Number(elem.factor) > 1)
								td.appendChild(document.createTextNode(elem.summ_one + " за " + elem.factor + "шт."));
							else
								td.appendChild(document.createTextNode(elem.summ_one));
							tr.appendChild(td);
							//сумма
							var td = document.createElement('td');
							td.setAttribute('align', "center");
							td.appendChild(document.createTextNode(elem.summ));
							tr.appendChild(td);
							//кнопка редактировать
							var td = document.createElement('td');
							td.setAttribute('align', "center");
							tr.appendChild(td);
							var span = document.createElement('span');
							span.setAttribute('class', "c-tooltip c-tooltip--top");
							span.setAttribute('aria-label', "Редактировать");
							span.setAttribute('onclick', "clickNewCalc(" + JSON.stringify(elem.id) + ")");
							td.appendChild(span);
							var i_ = document.createElement('i');
							i_.setAttribute('class', "fa fa-fw fa-edit");
							span.appendChild(i_);
							if(elem.file_path) {
								var span = document.createElement('span');
								span.setAttribute('class', "c-tooltip c-tooltip--top");
								span.setAttribute('aria-label', "Скачать файлы");
								span.setAttribute('onclick', "DownloadAllFiles('" + elem.file_path + "')");
								td.appendChild(span);
								var i_ = document.createElement('i');
								i_.setAttribute('class', "fa fa-fw fa-download");
								span.appendChild(i_);
							}
							//кнопка удалить
							var td = document.createElement('td');
							td.setAttribute('align', "center");
							tr.appendChild(td);
							var span = document.createElement('span');
							span.setAttribute('class', "c-tooltip c-tooltip--top");
							span.setAttribute('aria-label', "Удалить");
							span.setAttribute('onclick', "clickDelCalc(" + JSON.stringify(elem.id) + ")");
							td.appendChild(span);
							var i_ = document.createElement('i');
							i_.setAttribute('class', "fa fa-fw fa-trash");
							span.appendChild(i_);
						});
					}
				}
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
	
	$('#listCalc').DataTable({
		language: {
			url: "https://cdn.datatables.net/plug-ins/1.10.19/i18n/Russian.json"
		},
		responsive: true,
		"iDisplayLength": 25,
		ordering: false
	});
}

//скачиваем все файлы для расчетов
function DownloadAllFiles(json) {
	json = JSON.parse(json);
	if(json && Array.isArray(json) && json.length > 0) {
		for(var i = 0; i < json.length; i++) {
			if(json[i]) {
				var link = document.createElement('a');
				link.setAttribute('href', json[i]);
				var fileName = json[i].substring(json[i].lastIndexOf('/') + 1, json[i].length);
				link.setAttribute('download', fileName);
				link.click();
				remove(link);
			}
		}
	}
}

//сообщение об удалении выбранного расчета
function clickDelCalc(id) {
	//создаем модалку
	CreateModalWindow("delMessageFrom", "Удалить расчет?", "deleteCurrentCalc");
	//создаем сообщение
	var txt = "Расчет будет удален без права восстановления. Удалить расчет?";
	var label = document.createElement('label');
	label.appendChild(document.createTextNode(txt));
	document.getElementById("delMessageFrom-body").appendChild(label);
	document.getElementById("delMessageFrom-body").setAttribute('data-id', id);
	//запускаем модалку
	$('#modal_delMessageFrom').modal('show');
}

//удаление выбранного расчета
function deleteCurrentCalc() {
	if(!document.getElementById("delMessageFrom-body").hasAttribute('data-id')) {
		returnMessage("Произошла ошибка при удалении. Обновите страницу и повторите попытку заново", true);
		return;
	}
	
	//запрос на сервер
	$.ajax({
		type: "GET",
		url: "/bitrix24/modeler/modeler_bitrix.php",
		data: {'delCurrentCalc': JSON.stringify(document.getElementById("delMessageFrom-body").getAttribute('data-id'))},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer) {
					if(!answer.error) {
						$('#modal_delMessageFrom').modal('hide');
						loadFunction();
					}
					else {
						returnMessage(answer.message);
					}
				}
				else
					returnMessage("Пустой объект");
			}
			else
				returnMessage("Пустой ответ");
		},
		error: function( jqXHR, textStatus, errorThrown ){
			returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
}

//загрузка выбора счета
function addTemplateAcct() {
	var selectedCalc = document.getElementsByName("selectedRow");
	var slcd = false;
	for(var i = 0; i < selectedCalc.length; i++) {
		if(selectedCalc[i].checked) {
			slcd = true;
			break;
		}
	}
	if(!slcd) {
		returnMessage("Не выбраны расчеты");
		return;
	}
	//справочник актов
	var directoryAcct = [
		{value: "acct_prot", name: "Счет-протокол"},
		{value: "acct_prot5", name: "Счет-протокол(БЕЗ НДС)"},
		{value: "acct_prot2", name: "Счет-протокол(с печатью)"},
		{value: "acct_prot4", name: "Счет-протокол(на один лист)"},
	];
	//создаем модалку
	CreateModalWindow("addTemplateAcct", "Создать счет-протокол", "addTemplateAcct_click");
	//тело модалки
	var body = document.getElementById("addTemplateAcct-body");
	//дата счета
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	body.appendChild(row);
	var label = document.createElement('label');
	row.appendChild(label);
	var b = document.createElement('b');
	b.appendChild(document.createTextNode("Дата счета"));
	label.appendChild(b);
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	body.appendChild(row);
	var input = document.createElement('input');
	input.setAttribute('type', "date");
	input.setAttribute('id', "dateAcct");
	row.appendChild(input);
	var now = new Date();
	var y = now.getFullYear();
	var m = "" + (now.getMonth() + 1);
	if(m.length == 1)
		m = "0" + m;
	var d = "" + now.getDate();
	if(d.length == 1)
		d = "0" + d;
	var dt = y + "-" + m + "-" + d;
	$('#dateAcct').val(dt);
	//добавляем селект с видами счетов
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	body.appendChild(row);
	var label = document.createElement('label');
	row.appendChild(label);
	var b = document.createElement('b');
	b.appendChild(document.createTextNode("Выберите счет-протокол"));
	label.appendChild(b);
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	body.appendChild(row);
	var select = document.createElement('select');
	select.setAttribute('id', "typeAcct");
	row.appendChild(select);
	$('#typeAcct').select2({
		width: "100%"
	});
	
	//вешаем на селект данные по клиенту
	//определяем ид контакта
	var client_id = null;
	var company_id = null;
	if(globalData.type_menu == "contact") {
		if(document.getElementById("user_name").hasAttribute('data-attr')) {
			client_id = document.getElementById("user_name").getAttribute('data-attr');
		}
		//определяем ид компании
		if(document.getElementById("company_name").selectedIndex >= 0) {
			company_id = document.getElementById("company_name").value;
			if(company_id <= 0)
				company_id = null;
		}
	}
	else if(globalData.type_menu == "company") {
		if(document.getElementById("user_name").hasAttribute('data-attr')) {
			company_id = document.getElementById("user_name").getAttribute('data-attr');
		}
	}
	else {
		returnMessage("Произошла непредвидимая ошибка. Откройте приложение заново", true);
		return;
	}
	//запускаем формирование данных
	returnInfoClient(company_id, client_id, document.getElementById("typeAcct"));
	
	//заполняем селект
	directoryAcct.forEach(function(elem) {
		var option = document.createElement('option');
		option.setAttribute('value', elem.value);
		option.appendChild(document.createTextNode(elem.name));
		document.getElementById("typeAcct").appendChild(option);
	});
	document.getElementById("typeAcct").selectedIndex = -1;
	//список выбранных расчетов
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	body.appendChild(row);
	var label = document.createElement('label');
	row.appendChild(label);
	var b = document.createElement('b');
	b.appendChild(document.createTextNode("Выбранные расчеты:"));
	label.appendChild(b);
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	body.appendChild(row);
	var table = document.createElement('table');
	table.setAttribute('id', "selectedCalcTable");
	table.setAttribute('class', "c-table");
	row.appendChild(table);
	var tbody = document.createElement('tbody');
	table.appendChild(tbody);
	//получаем выбранные расчеты
	for(var i = 0; i < selectedCalc.length; i++) {
		if(!selectedCalc[i].hasAttribute('value') || selectedCalc[i].value == "" || !selectedCalc[i].checked)
			continue;
		var json = JSON.parse(selectedCalc[i].value);
		var tr = document.createElement('tr');
		tr.setAttribute('data-id', json.id);
		tbody.appendChild(tr);
		var td = document.createElement('td');
		td.appendChild(document.createTextNode(json.name + " (количество - " + json.total + ", сумма - " + json.summ + ")"));
		tr.appendChild(td);	
	};
	
	//запускаем модалку
	$('#modal_addTemplateAcct').modal('show');
}

//создаем счет-протокол
function addTemplateAcct_click() {
	//проверяем или выбраны данные
	if(document.getElementById("typeAcct").selectedIndex < 0) {
		returnMessage("Выберите тип счета-протокола");
		return;
	}
	//определяем строку индексов
	var selectedCalc = document.getElementsByName("selectedRow");
	var slcd = false;
	var str_id = "";
	for(var i = 0; i < selectedCalc.length; i++) {
		if(selectedCalc[i].checked) {
			var json = JSON.parse(selectedCalc[i].value);
			if(json) {
				slcd = true;
				if(str_id != "")
					str_id += ",";
				str_id += json.id;
			}
		}
	}
	if(!slcd) {
		returnMessage("Не выбраны расчеты");
		return;
	}
	
	//определяем пользователя
	if(!document.getElementById("user_name").hasAttribute('data-attr-current')) {
		returnMessage("Произошла непредвидемая ошибка. Запустите приложение заново", true);
		return;
	}
	var json = JSON.parse(document.getElementById("user_name").getAttribute('data-attr-current'));
	if(!json) {
		returnMessage("Произошла непредвидемая ошибка. Запустите приложение заново", true);
		return;
	}
	
	//объект для запроса
	var send = {
		list_id: str_id,
		username: json.LAST_NAME + " " + json.NAME,
		email: json.EMAIL,
		date_: document.getElementById("dateAcct").value
	}
	
	//определяем данные клиента
	var client = {}
	if(document.getElementById("company_name") && document.getElementById("company_name").selectedIndex >= 0 && parseInt(document.getElementById("company_name").value) > 0) {
		client.name = getConcatenedTextContent(document.getElementById("company_name").options[document.getElementById("company_name").selectedIndex]);
	}
	else {
		client.name = getConcatenedTextContent(document.getElementById("user_name"));
	}
	if(document.getElementById("typeAcct").hasAttribute('data-info-contact')) {
		var json = JSON.parse(document.getElementById("typeAcct").getAttribute('data-info-contact'));
		if(json) {
			if(json.email)
				client.email = json.email;
			if(json.phone)
				client.phone = json.phone;
		}
	}
	if(document.getElementById("typeAcct").hasAttribute('data-info-bank')) {
		var json = JSON.parse(document.getElementById("typeAcct").getAttribute('data-info-bank'));
		if(json) {
			if(json.bank)
				client.bank = json.bank;
			if(json.bik)
				client.bik = json.bik;
			if(json.acc_num)
				client.acc_num = json.acc_num;
		}
	}
	if(document.getElementById("typeAcct").hasAttribute('data-info-address')) {
		var json = JSON.parse(document.getElementById("typeAcct").getAttribute('data-info-address'));
		if(json) {
			client.address = json;
		}
	}
	client.manager = client.name;
	if(document.getElementById("typeAcct").hasAttribute('data-info-manager')) {
		var json = JSON.parse(document.getElementById("typeAcct").getAttribute('data-info-manager'));
		if(json) {
			client.manager = json;
		}
	}
	
	send.client = client;
	
	//открываем счет-протокол
	var url = "/bitrix24/modeler/acct/" + document.getElementById("typeAcct").value + ".php?list_id=" + JSON.stringify(send);
	window.open(url);
	
	$('#modal_addTemplateAcct').modal('hide');
}

//получение информации о компании/физ лице
function returnInfoClient(company_id, client_id, container) {
	if(!container)
		return;
	if(!company_id && !client_id)
		return;
	var id = 0;
	ENTITY_TYPE_ID = 0;
	
	if(!company_id && client_id) {
		id = client_id;
		ENTITY_TYPE_ID = 3;
		BX24.callMethod(
			"crm.contact.get", 
			{ id: client_id }, 
			function(result) {
				if(result.error())
					console.error(result.error());
				else
				{
					var dt = result.data();
					var obj = {}
					if(dt.EMAIL && Array.isArray(dt.EMAIL) && dt.EMAIL.length > 0)
						obj.email = dt.EMAIL[0].VALUE;
					if(dt.PHONE && Array.isArray(dt.PHONE) && dt.PHONE.length > 0)
						obj.phone = dt.PHONE[0].VALUE;
					container.setAttribute('data-info-contact', JSON.stringify(obj));
				}
			}
		);	
	}
	else {
		id = company_id;
		ENTITY_TYPE_ID = 4;
		BX24.callMethod(
			"crm.company.get", 
			{ id: company_id }, 
			function(result) {
				if(result.error())
					console.error(result.error());
				else
				{
					var dt = result.data();
					var obj = {}
					if(dt.EMAIL && Array.isArray(dt.EMAIL) && dt.EMAIL.length > 0)
						obj.email = dt.EMAIL[0].VALUE;
					if(dt.PHONE && Array.isArray(dt.PHONE) && dt.PHONE.length > 0)
						obj.phone = dt.PHONE[0].VALUE;
					container.setAttribute('data-info-contact', JSON.stringify(obj));
				}
			}
		);
	}
	
	//реквизиты
	BX24.callMethod(
		"crm.requisite.list",
		{
			filter: {"ENTITY_ID": id, "ENTITY_TYPE_ID": ENTITY_TYPE_ID}
		},
		function(result) {
			if(result.error())
				console.error(result.error());
			else {
				var requisite = result.data();
				for(var i = 0; i < requisite.length; i++) {
					//пишем ген.директора
					if(requisite[i].RQ_DIRECTOR) {
						container.setAttribute('data-info-manager', JSON.stringify(requisite[i].RQ_DIRECTOR));
					}
					//определяем адрес
					BX24.callMethod(
						"crm.address.list",
						{
							filter: {"ENTITY_ID": requisite[i].ID, "ENTITY_TYPE_ID": 8}, 
						},
						function(result1) {
							if(result1.error())
								console.error(result1.error());
							else {
								var address = result1.data();
								var address1 = "";
								var address4 = "";
								var address6 = "";
								var address9 = "";
								for(var j = 0; j < address.length; j++) {
									var str_address = "";
									if(address[j].PROVINCE) {
										str_address += address[j].PROVINCE + " обл.";
									}
									if(address[j].REGION) {
										if(str_address.length > 0)
											str_address += ", ";
										str_address += address[j].REGION + " р-н";
									}
									if(address[j].CITY) {
										if(str_address.length > 0)
											str_address += ", ";
										str_address += address[j].CITY;
									}
									if(address[j].ADDRESS_1) {
										if(str_address.length > 0)
											str_address += ", ";
										str_address += address[j].ADDRESS_1;
									}
									if(address[j].ADDRESS_2) {
										str_address += "кв " + address[j].ADDRESS_2;
									}
									
									switch(parseInt(address[j].TYPE_ID)) {
										case 1:
											address1 = str_address;
										case 4:
											address4 = str_address;
										case 6:
											address6 = str_address;
										case 9:
											address9 = str_address;
									}
								}
								var address = "";
								if(address6 != "")
									address = address6;
								else if(address4 != "")
									address = address4;
								else if(address9 != "")
									address = address9;
								else if(address1 != "")
									address = address1;
								if(address != "")
									container.setAttribute('data-info-address', JSON.stringify(address));
							}
						}
					);
					
					//банковские реквизиты
					BX24.callMethod(
						"crm.requisite.bankdetail.list",
						{
							filter: {"ENTITY_ID": requisite[i].ID, "ENTITY_TYPE_ID": 8}, 
						},
						function(result1) {
							if(result1.error())
								console.error(result1.error());
							else {
								var data = result1.data();
								if(data.length > 0) {
									var obj = {}
									if(data[0].RQ_BANK_NAME)
										obj.bank = data[0].RQ_BANK_NAME;
									if(data[0].RQ_BIK)
										obj.bik = data[0].RQ_BIK;
									if(data[0].RQ_ACC_NUM)
										obj.acc_num = data[0].RQ_ACC_NUM;
									container.setAttribute('data-info-bank', JSON.stringify(obj));
								}
							}
						}
					);
				}
				if(result.more())
					result.next();
			}
		}
	);
}

//отправить в работу
function addTransferInWork() {
	//определяем список выделенных расчетов
	var selectedValue = new Array();
	var selectedCalc = document.getElementsByName("selectedRow");
	var slcd = false;
	var one_order = true;
	var current_order = -1;
	for(var i = 0; i < selectedCalc.length; i++) {
		if(selectedCalc[i].checked) {
			slcd = true;
			var obj = JSON.parse(selectedCalc[i].value);
			selectedValue.push(obj);
			if(obj.order_id) {
				if(current_order >= 0 && current_order != obj.order_id) {
					one_order = false;
				}
				else
					current_order = obj.order_id;
			}
			else {
				if(current_order > 0) {
					one_order = false;
				}
				current_order = 0;
			}
		}
	}
	if(!slcd) {
		returnMessage("Не выбраны расчеты");
		return;
	}
	
	if(!globalData || (!globalData.client_id && !globalData.company_id)) {
		returnMessage("Произошла непредвидемая ошибка! Откройте приложение заново", true);
		return;
	}
	
	//если есть номер телефона контакта
	if(document.getElementById("user_name").hasAttribute('data-phone') && document.getElementById("user_name").getAttribute('data-phone') != "")
		globalData.client_phone = document.getElementById("user_name").getAttribute('data-phone');
	
	if(globalData.type_menu == "contact") {
		//определяем компанию
		if(document.getElementById("company_name").selectedIndex < 1)
			globalData.company_id = 0;
		else
			globalData.company_id = document.getElementById("company_name").value;
		//если есть унп компании
		if(document.getElementById("company_name").selectedIndex > 0) {
			var com_n = document.getElementById("company_name").options[document.getElementById("company_name").selectedIndex];
			if(com_n) {
				if(com_n.hasAttribute('data-unp') && com_n.getAttribute('data-unp') != "")
					globalData.company_unp = com_n.getAttribute('data-unp');
			}
		}
	}
	else if(globalData.type_menu == "company" && globalData.company_id) {
		globalData.client_id = 0;
		if(document.getElementById("user_name").hasAttribute('data-unp') && document.getElementById("user_name").getAttribute('data-unp') != "")
			globalData.company_unp = document.getElementById("user_name").getAttribute('data-unp');
	}
	else {
		returnMessage("Произошла непредвидемая ошибка! Откройте приложение заново", true);
		return;
	}
	
	//выводим форму, если счет не один
	if(one_order) {
		var parameters = new Array();
		//формируем массив идов
		for(var i = 0; i < selectedValue.length; i++) {
			var parameter = {
				id: selectedValue[i].id,
				order_id: 0,
				name: selectedValue[i].name,
			}
			if(selectedValue[i].order_id)
				parameter.order_id = parseInt(selectedValue[i].order_id);
			parameters.push(parameter);
		}
		//формируем ссылку
		var url = "/bitrix24/transfer.php?dataLoad=" + JSON.stringify(globalData) + "&parameters=" + JSON.stringify(parameters);
		location.href = url;
	}
	else {
		//создаем форму
		CreateModalWindow("SelectOrders", "", null, 1000);
		//тело модалки
		var body = document.getElementById("SelectOrders-body");
		
		var row = document.createElement('div');
		row.setAttribute('class', "row");
		body.appendChild(row);
		
		//создаем кнопку для объединенного счета
		var col = document.createElement('div');
		col.setAttribute('class', "col-md-6");
		row.appendChild(col);	
		var div = document.createElement('div');
		div.setAttribute('class', "block_add");
		col.appendChild(div);	
		var h2 = document.createElement('h2');
		div.appendChild(h2);	
		var i_ = document.createElement('i');
		i_.setAttribute('class', "fa fa-list-ul fa-1x u-mr-xsmall");
		h2.appendChild(i_);
		h2.appendChild(document.createTextNode("Объединить в один счет"));	
		//разделительная линия
		var hr = document.createElement('hr');
		hr.setAttribute('color', "#EEF2F5");
		div.appendChild(hr);	
		var txt = "Все расчеты будут объеденены в один счет. Номер счета выбирается первый из найденых. Если такового нет, то создается новый счет";	
		var p = document.createElement('p');
		p.appendChild(document.createTextNode(txt));
		div.appendChild(p);
		//нажатие на див
		div.onclick = function() {
			//определяем номер непустого счета
			var num_order = 0;
			for(var i = 0; i < selectedValue.length; i++) {
				if(selectedValue[i].order_id)
					num_order = selectedValue[i].order_id;
			}
			var parameters = new Array();
			//формируем массив идов
			for(var i = 0; i < selectedValue.length; i++) {
				var parameter = {
					id: selectedValue[i].id,
					order_id: num_order,
					name: selectedValue[i].name,
				}
				parameters.push(parameter);
			}
			//формируем ссылку
			var url = "/bitrix24/transfer.php?dataLoad=" + JSON.stringify(globalData) + "&parameters=" + JSON.stringify(parameters);
			location.href = url;
		}
		
		//создаем кнопку для разных счетов
		var col = document.createElement('div');
		col.setAttribute('class', "col-md-6");
		row.appendChild(col);	
		var div = document.createElement('div');
		div.setAttribute('class', "block_add");
		col.appendChild(div);	
		var h2 = document.createElement('h2');
		div.appendChild(h2);	
		var i_ = document.createElement('i');
		i_.setAttribute('class', "fa fa-subscript fa-1x u-mr-xsmall");
		h2.appendChild(i_);
		h2.appendChild(document.createTextNode("Разнести в разные счета"));	
		//разделительная линия
		var hr = document.createElement('hr');
		hr.setAttribute('color', "#EEF2F5");
		div.appendChild(hr);	
		var txt = "Счета выбираются на основании ранее выписанных счетов-протоколов. Если не был выписан счет-протокол, то такие расчеты относятся к пустому счету";	
		var p = document.createElement('p');
		p.appendChild(document.createTextNode(txt));
		div.appendChild(p);
		//нажатие на див
		div.onclick = function() {
			var parameters = new Array();
			//формируем массив идов
			for(var i = 0; i < selectedValue.length; i++) {
				var parameter = {
					id: selectedValue[i].id,
					order_id: 0,
					name: selectedValue[i].name,
				}
				if(selectedValue[i].order_id)
					parameter.order_id = parseInt(selectedValue[i].order_id);
				parameters.push(parameter);
			}
			//формируем ссылку
			var url = "/bitrix24/transfer.php?dataLoad=" + JSON.stringify(globalData) + "&parameters=" + JSON.stringify(parameters);
			location.href = url;
		}
		
		//запускаем форму
		$("#modal_SelectOrders").modal('show');
	}
}


