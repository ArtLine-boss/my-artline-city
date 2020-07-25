//после загрузки документа
$(document).ready(function() {
	$(document).ready(function() {
		$('#listFields').select2({
			'width': "100%",
		});
	});
	
	loadData();
});

//событие выбора колонок таблицы
function changeSelectedFields(e) {
	if(!e)
		e = document.getElementById("listFields");
	//определяем выбранные
	var options = e.getElementsByTagName('option');
	var selected = new Array();
	for(var i = 0; i < options.length; i++) {
		if(options[i].selected)
			selected.push(options[i].value);
	}
	
	//сбрасывеам все колонки в заголовке
	var ths = document.getElementById("tableArtliner").getElementsByTagName('th');
	for(var i = 0; i < ths.length; i++) {
		if(!ths[i].hasAttribute('style'))
			ths[i].setAttribute('style', "display:none");
	}
	
	//сбрасывеам все колонки в данных
	var tds = document.getElementById("tableArtliner").getElementsByTagName('td');
	for(var i = 0; i < tds.length; i++) {
		if(!tds[i].hasAttribute('style'))
			tds[i].setAttribute('style', "display:none");
	}
	
	//отображаем колонки
	var trs = document.getElementById("tableArtliner").getElementsByTagName('tr');
	for(var i = 0; i < trs.length; i++) {
		var tds = trs[i].getElementsByTagName('td');
		if(tds.length <= 0)
			tds = trs[i].getElementsByTagName('th');
		for(var j = 0; j < selected.length; j++) {
			if(tds[selected[j]].hasAttribute('style'))
				tds[selected[j]].removeAttribute('style')
		}
	}
	
	//сохраняем для текущего пользователя
	$.ajax({
		type: "GET",
		url: "pg/modeler.php",
		data: {"saveFieldsForArtlinerReport": JSON.stringify(selected)},
		cache: false,
		success: function() {}
	});
}

//сортировка
function sortHeard(e) {
	var sort = "desc";
	var cl = e.getElementsByClassName('fa-sort-desc');
	if(cl[0]) {
		sort = "up";
	}
	if(document.getElementById("sortMarker"))
		remove(document.getElementById("sortMarker"));
	var i_ = document.createElement('i');
	i_.setAttribute('id', "sortMarker");
	i_.setAttribute('class', "fa fa-sort-" + sort + " fa-2x");
	e.appendChild(i_);
	loadData();
}

//функция запроса данных
function loadData(filter) {
	var send = {};
	//определяем порядок сортировки
	var sortMarker = document.getElementById("sortMarker");
	if(!sortMarker)
		return;
	var prnt = sortMarker.parentNode;
	if(!prnt)
		return;
	send.sort = "DESC";
	var cl = prnt.getElementsByClassName('fa-sort-desc');
	if(!cl[0]) {
		send.sort = "";
	}
	//определяем по какому полю сортировка
	if(!prnt.hasAttribute("data-name-field"))
		return;
	send.sortField = prnt.getAttribute("data-name-field");
	//определяем текущую страницу
	send.current_page = 1;
	if(document.getElementById("tableArtliner").hasAttribute('data-current-page'))
		send.current_page = Number(document.getElementById("tableArtliner").getAttribute('data-current-page'));
	//определяем количество на странице
	send.quentity = 20;
	if(document.getElementById("quentityPages").selectedIndex >= 0)
		send.quentity = document.getElementById("quentityPages").value;
	
	//если фильтр
	if(filter) {
		document.getElementById("tableArtliner").setAttribute('data-filter', true);
	}
	
	if(document.getElementById("tableArtliner").hasAttribute('data-filter') && document.getElementById("tableArtliner").getAttribute('data-filter') == 'true') {
		send.startDate = document.getElementById("startDate").value;
		send.endDate = document.getElementById("endDate").value;
		send.searchData = document.getElementById("searchData").value;
		if(document.getElementById("isERIP").checked)
			send.isERIP = true;
	}
	
	clearTable(document.getElementById("tableArtliner"));
	
	$.ajax({
		type: "GET",
		url: "pg/modeler.php",
		data: {'loadArtlinerReport': JSON.stringify(send)},
		cache: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(!answer || !answer.current_page || !answer.nmb_page)
					return;
				drowPagins(answer.current_page, answer.nmb_page);
				if(answer.arr && Array.isArray(answer.arr)) {
					var tbody = document.getElementById("tableArtliner").getElementsByTagName("tbody")[0];
					var ths = document.getElementById("tableArtliner").getElementsByTagName('th');
					var lng = ths.length;
					if(lng <= 0)
						return;
					for(var i = 0; i < answer.arr.length; i++) {
						var tr = document.createElement('tr');
						tr.setAttribute('onclick', "openEditArtliner('" + JSON.stringify(answer.arr[i]) + "')");
						for(var j = 0; j < lng; j++) {
							var td = document.createElement('td');
							if(ths[j].hasAttribute("data-name-field")) {
								var val = eval("answer.arr[i]." + ths[j].getAttribute("data-name-field"));
								if(val) {
									td.appendChild(document.createTextNode(eval("answer.arr[i]." + ths[j].getAttribute("data-name-field"))));
								}
							}
							tr.appendChild(td);
						}
						tbody.appendChild(tr);
					}
					changeSelectedFields();
				}
			}
		}
	});
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

//рисуем пагинацию
function drowPagins(c_page, q_page) {
	var nav = document.getElementById("navPage");
	deleteFooterDOM(nav);
	c_page = Number(c_page);
	q_page = Number(q_page);
	//предыдущую страницу
	var li = document.createElement('li');
	var cl = "page-item";
	if(c_page == 1)
		cl = "page-item disabled";
	li.setAttribute('class', cl);
	nav.appendChild(li);
	var a = document.createElement('a');
	a.setAttribute('class', "page-link");
	a.setAttribute('aria-label', "Предыдущая");
	a.setAttribute('onclick', "nextPage(null,-1)");
	li.appendChild(a);
	var span = document.createElement('span');
	a.appendChild(span);
	var i_ = document.createElement('i');
	i_.setAttribute('class', "fa fa-angle-double-left");
	span.appendChild(i_);
	
	//страницы
	var start = c_page - 2;
	if(start <= 0)
		start = 1;
	var end = c_page + 2;
	if(end > q_page)
		end = q_page;
	for(var i = start; i <= end; i++) {
		if(c_page == i) {
			document.getElementById('navPage').appendChild(addLinkPage(i, true));
		}
		else {
			document.getElementById('navPage').appendChild(addLinkPage(i, false));
		}
	}
	
	//следующая страница
	var li = document.createElement('li');
	var cl = "page-item";
	if(c_page == q_page)
		cl = "page-item disabled";
	li.setAttribute('class', cl);
	nav.appendChild(li);
	var a = document.createElement('a');
	a.setAttribute('class', "page-link");
	a.setAttribute('aria-label', "Следующая");
	a.setAttribute('onclick', "nextPage(null,1)");
	li.appendChild(a);
	var span = document.createElement('span');
	a.appendChild(span);
	var i_ = document.createElement('i');
	i_.setAttribute('class', "fa fa-angle-double-right");
	span.appendChild(i_);
}

//рисует ссылку на страницу
function addLinkPage(num, active) {
	var li = document.createElement('li');
	li.setAttribute('class', "page-item");
	if(active)
		li.setAttribute('class', "page-item active");
	else
		li.setAttribute('class', "page-item");
	var a = document.createElement('a');
	a.setAttribute('class', "page-link");
	a.appendChild(document.createTextNode(num));
	a.setAttribute('onclick', "nextPage(" + num + ")");
	li.appendChild(a);
	return li;
}

//переход по страницам
function nextPage(num, ind) {
	var current = 1;
	if(document.getElementById("tableArtliner").hasAttribute('data-current-page')) {
		current = Number(document.getElementById("tableArtliner").getAttribute('data-current-page'));
	}
	
	var new_current;
	if(num) {
		new_current = num;
	}
	else if(ind) {
		new_current = current + ind;
	}
	else
		return;
	
	if(new_current <= 0)
		new_current = 1;
	document.getElementById("tableArtliner").setAttribute('data-current-page', new_current);
	loadData();
}

//модалка на редактирование
function openEditArtliner(data) {
	data = JSON.parse(data);
	if(!data)
		return;
	//создаем модалку
	CreateModal("openEditArtliner", "Редактирование информации в заявке " + data.id_order_artliner, "updateArtliner");
	var modal_body = document.getElementById("openEditArtlinerBody");
	modal_body.setAttribute('data-id', data.id);
	//номер счета
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	modal_body.appendChild(row);
	var label = document.createElement('label');
	label.appendChild(document.createTextNode("Номер счета"));
	row.appendChild(label);
	var inp = document.createElement('input');
	inp.setAttribute('type', "number");
	inp.setAttribute('class', "form-control");
	inp.setAttribute('id', "editOrder");
	if(data.id_order)
		inp.value = data.id_order;
	row.appendChild(inp);
	//оплата
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	modal_body.appendChild(row);
	var label = document.createElement('label');
	label.appendChild(document.createTextNode("Сумма оплаты"));
	row.appendChild(label);
	var inp = document.createElement('input');
	inp.setAttribute('type', "number");
	inp.setAttribute('step', "0.01");
	inp.setAttribute('min', "0");
	inp.setAttribute('class', "form-control");
	inp.setAttribute('id', "editPayment");
	if(data.payment)
		inp.value = data.payment;
	row.appendChild(inp);
	//Дата оплаты
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	modal_body.appendChild(row);
	var label = document.createElement('label');
	label.appendChild(document.createTextNode("Дата оплаты"));
	row.appendChild(label);
	var inp = document.createElement('input');
	inp.setAttribute('type', "date");
	inp.setAttribute('class', "form-control");
	inp.setAttribute('id', "editPaymentDate");
	if(data.payment_date)
		inp.value = data.payment_date;
	row.appendChild(inp);
	//код отправки
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	modal_body.appendChild(row);
	var label = document.createElement('label');
	label.appendChild(document.createTextNode("Код отправки"));
	row.appendChild(label);
	var inp = document.createElement('input');
	inp.setAttribute('type', "text");
	inp.setAttribute('class', "form-control");
	inp.setAttribute('id', "editPostCode");
	if(data.carriers_code)
		inp.value = data.carriers_code;
	row.appendChild(inp);
	//Дата отправки
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	modal_body.appendChild(row);
	var label = document.createElement('label');
	label.appendChild(document.createTextNode("Дата отправки"));
	row.appendChild(label);
	var inp = document.createElement('input');
	inp.setAttribute('type', "date");
	inp.setAttribute('class', "form-control");
	inp.setAttribute('id', "editPostDate");
	if(data.carriers_date)
		inp.value = data.carriers_date;
	row.appendChild(inp);
	//меняем клиента
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	modal_body.appendChild(row);
	var label = document.createElement('label');
	label.appendChild(document.createTextNode("Новый заказчик"));
	row.appendChild(label);
	var sel = document.createElement('select');
	sel.setAttribute('id', "newClient");
	sel.setAttribute('class', "js-example-basic-single js-states form-control");
	row.appendChild(sel);
	$.ajax({
		type: "GET",
		url: "pg/modeler.php?getClients",
		data: {},
		cache: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer && Array.isArray(answer) && answer.length > 0) {
					for(var i = 0; i < answer.length; i++) {
						var option_ = document.createElement('option');
						option_.setAttribute('value', answer[i].ID);
						option_.appendChild(document.createTextNode(answer[i].CLIENT_NAME));
						document.getElementById("newClient").appendChild(option_);
					}
				}
			}
			$('#newClient').select2({
				width: '95%',
				placeholder: 'Выберите нового клиента',
				dropdownParent: $('#openEditArtliner'),
			});
			$('#newClient').val(-1).trigger('change');
			
			//очистка селекта
			var i_ = document.createElement('i');
			i_.setAttribute('class', "fa fa-remove fa-1x u-mr-xsmall u-color-danger");
			i_.setAttribute('style', "cursor:pointer");
			i_.onclick = function(e) {
				$("#newClient").val(null).trigger('change');
			}
			row.appendChild(i_);
		}
	});
	
	//запускаем модалку
	$("#openEditArtliner").modal('show');
}

//обновление данных
function updateArtliner() {
	if(!document.getElementById("openEditArtlinerBody").hasAttribute('data-id'))
		return;
	var send = {
		id: document.getElementById("openEditArtlinerBody").getAttribute('data-id'),
		id_client: document.getElementById("newClient").value,
		id_order: document.getElementById("editOrder").value,
		payment: document.getElementById("editPayment").value,
		payment_date: document.getElementById("editPaymentDate").value,
		carriers_code: document.getElementById("editPostCode").value,
		carriers_date: document.getElementById("editPostDate").value,
	};
	
	$.ajax({
		type: "GET",
		url: "pg/modeler.php",
		data: {'updateArtliner': JSON.stringify(send)},
		cache: false,
		success: function(respond) {
			if(respond) {
				loadData();
				$("#openEditArtliner").modal('hide');
			}
		}
	});
}

//очистка фильтра
function clearFilter() {
	$("#startDate").val(null).trigger("change");
	$("#endDate").val(null).trigger("change");
	document.getElementById("isERIP").checked = false;
	$("#searchData").val(null).trigger("change");
	if(document.getElementById("tableArtliner").hasAttribute('data-filter'))
		document.getElementById("tableArtliner").removeAttribute('data-filter');
	loadData();
}





