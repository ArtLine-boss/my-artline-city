var globalSend = {}
//после загрузки документа
$(document).ready(function() {
	//определяем или нашли клиента
	if(!document.getElementById("body_content").hasAttribute('data-attr-client')) {
		returnMessage("Непредвидемая ошибка! Запустите приложение заново", true);
		return;
	}
	if(document.getElementById("body_content").hasAttribute('data-attr-order')) {
		var json = JSON.parse(document.getElementById("body_content").getAttribute('data-attr-order'));
		drawOrder(json);
	}
});

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

//отрисовка доп настроек для каждой позиции
function drawOrder(arr) {
	globalSend = {}
	deleteFooterDOM(document.getElementById("ListOrder"));
	if(!arr) {
		returnMessage("Пустой входной объект");
		return;
	}
	
	//получаем коды статистики
	var codeStat = new Array();
	$.ajax({
		type: "GET",
		url: "/bitrix24/modeler/modeler_transfer.php?codeStat",
		data: {},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer && Array.isArray(answer) && answer.length > 0) {
					for(var i = 0; i < answer.length; i++) {
						codeStat.push(answer[i]);
					}
				}
				else {
					returnMessage("Пустой объект");
					return;
				}
			}
			else {
				returnMessage("Пустой ответ от сервера");
				return;
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			returnMessage('ОШИБКИ AJAX запроса: ' + errorThrown, true);
			return;
        }
	});
	
	for(var key in arr) {
		//заголовок счета
		eval("globalSend." + key + "=arr." + key);
		var parameters = eval("arr." + key);
		var row = document.createElement('div');
		row.setAttribute('class', 'row');
		document.getElementById("ListOrder").appendChild(row);
		if(!parameters || !Array.isArray(parameters) || parameters.length <= 0) {
			returnMessage("Пустой входной массив параметров");
			return;
		}
		var order_id = parameters[0].order_id;
		var h1 = document.createElement('h1');
		if(order_id == 0) {
			h1.appendChild(document.createTextNode("Новый счет"));
		}
		else {
			h1.appendChild(document.createTextNode("Счет №" + order_id));
		}
		row.appendChild(h1);
		
		for(var i = 0; i < parameters.length; i++) {
			//создаем блок для позиции
			var row = document.createElement('div');
			row.setAttribute('class', "row");
			document.getElementById("ListOrder").appendChild(row);
			var h3 = document.createElement('h3');
			h3.appendChild(document.createTextNode(parameters[i].name));
			row.appendChild(h3);
			//дата сдачи
			var row = document.createElement('div');
			row.setAttribute('class', "row");
			document.getElementById("ListOrder").appendChild(row);
			var col = document.createElement('div');
			col.setAttribute('class', "col-md-1");
			row.appendChild(col);
			var label = document.createElement('label');
			label.appendChild(document.createTextNode("Дата сдачи:"));
			col.appendChild(label);
			var col = document.createElement('div');
			col.setAttribute('class', "col-md-6");
			row.appendChild(col);
			var input = document.createElement('input');
			input.setAttribute('type', 'datetime-local');
			input.setAttribute('style', 'width: 100%');
			//ставим минимальную дату
			var now = new Date();
			var day_now = "" + now.getDate();
			if(day_now.length == 1)
				day_now = "0" + day_now;
			var month_now = "" + (now.getMonth() + 1);
			if(month_now.length == 1)
				month_now = "0" + month_now;
			var year_now = now.getFullYear();
			var hours_now = now.getHours();
			var minutes_now = now.getMinutes();
			var min_date = year_now + "-" + month_now + "-" + day_now + "T" + hours_now + ":" + minutes_now;
			input.setAttribute('min', min_date);
			input.setAttribute('id', 'date_' + key + "_" + parameters[i].id);
			col.appendChild(input);
			//код статистики
			var row = document.createElement('div');
			row.setAttribute('class', "row");
			document.getElementById("ListOrder").appendChild(row);
			var col = document.createElement('div');
			col.setAttribute('class', "col-md-1");
			row.appendChild(col);
			var label = document.createElement('label');
			label.appendChild(document.createTextNode("Наименование:"));
			col.appendChild(label);
			var col = document.createElement('div');
			col.setAttribute('class', "col-md-6");
			row.appendChild(col);
			var select = document.createElement('select');
			select.setAttribute('id', 'codeStat_' + key + "_" + parameters[i].id);
			select.setAttribute('class', "js-example-basic-single js-states form-control");
			col.appendChild(select);
			for(var j = 0; j < codeStat.length; j++) {
				var option = document.createElement('option');
				option.setAttribute('value', codeStat[j].id);
				option.setAttribute('title', codeStat[j].comm);
				option.appendChild(document.createTextNode(codeStat[j].name));
				select.appendChild(option);
			}
			select.selectedIndex = -1;
			$("#codeStat_" + key + "_" + parameters[i].id).select2({
				width: '100%',
				placeholder: 'Выберите вид товара'
			});
			//доп наименование
			var row = document.createElement('div');
			row.setAttribute('class', "row");
			document.getElementById("ListOrder").appendChild(row);
			var col = document.createElement('div');
			col.setAttribute('class', "col-md-1");
			row.appendChild(col);
			var label = document.createElement('label');
			label.appendChild(document.createTextNode("Доп. название:"));
			col.appendChild(label);
			var col = document.createElement('div');
			col.setAttribute('class', "col-md-6");
			row.appendChild(col);
			var input = document.createElement('input');
			input.setAttribute('type', 'text');
			input.setAttribute('style', 'width: 100%');
			input.setAttribute('id', 'name_' + key + "_" + parameters[i].id);
			col.appendChild(input);
			//выбор файлов
			var row = document.createElement('div');
			row.setAttribute('class', "row");
			document.getElementById("ListOrder").appendChild(row);
			var col = document.createElement('div');
			col.setAttribute('class', "col-md-2");
			row.appendChild(col);
			var a = document.createElement('a');
			a.setAttribute('class', "c-btn c-btn--warning c-btn--fullwidth");
			a.setAttribute('id', "btnfile_" + key + "_" + parameters[i].id);
			col.appendChild(a);
			var i_ = document.createElement('i');
			i_.setAttribute('class', "fa fa-file-archive-o");
			a.appendChild(i_);
			a.appendChild(document.createTextNode(" Выберите файлы"));
			a.onclick = function(e) {
				var id_ = e.target.id;
				id_ = id_.substr(8);
				if(document.getElementById("file_" + id_))
					document.getElementById("file_" + id_).click();
			}
			//загрузчик файлов
			var input = document.createElement('input');
			input.setAttribute('type', "file");
			input.setAttribute('multiple', true);
			input.setAttribute('id', "file_" + key + "_" + parameters[i].id);
			input.setAttribute('style', "display:none;");
			input.setAttribute('onchange', "uploadFile(this)");
			col.appendChild(input);
			//таблица файлов
			var row = document.createElement('div');
			row.setAttribute('class', "row");
			document.getElementById("ListOrder").appendChild(row);
			var table = document.createElement('table');
			table.setAttribute('id', "tablefile_" + key + "_" + parameters[i].id);
			table.setAttribute('class', "c-table");
			row.appendChild(table);
			var tbody = document.createElement('tbody');
			table.appendChild(tbody);
		}
	}
}

//загрузка файла
function uploadFile(e) {
	var id_ = e.id;
	var id = id_.substr(5);
	var key_ = reverseString(id_.substr(5));
	var id_elem = reverseString(key_.substr(0, key_.indexOf("_")));
	key_ = reverseString(key_.substr(key_.indexOf("_") + 1));
	//диалоговое окно для выбора файла
    $.each( e.files, function( key, value ){
		//проверяем имена файлов
		var filename = value.name;
		//убираем расширение
		filename = filename.split("").reverse().join("");
		filename = filename.substr(filename.indexOf(".") + 1);
		filename = filename.split("").reverse().join("");
		//проверяем длину
		if(filename.length > 20) {
			returnMessage("Длина имени файла не должна превышать 20 символов");
			return;
		}
		//проверяем на латинские буквы, цифры, нижнее подчеркивание и тире
		var re = new RegExp('[^a-zA-Z0-9_-]');
		if(re.test(filename)) {
			returnMessage("Название файла не соответствует принятым стандартам. Длина имени файла не должна превышать 20 символов. В имени файла допустимы цифры, латинские буквы, символ нижнего подчеркивания (_) и символ тире (-)");
			return;
		}
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
		var tmp_arr = eval("globalSend." + key_);
		if(tmp_arr && Array.isArray(tmp_arr)) {
			var num_in_array = -1;
			var id_in_array = -1;
			for(var i = 0; i < tmp_arr.length; i++) {
				if(tmp_arr[i].id == id_elem) {
					if(eval("!globalSend." + key_ + "[" + i + "].file"))
						eval("globalSend." + key_ + "[" + i + "].file = new Array()");
					eval("globalSend." + key_ + "[" + i + "].file.push(obj)");
					break;
				}
			}
			
			addTableFile(id);
		}
    });
}

//добавление строки при добавлении файлов
function addTableFile(id) {
	deleteFooterDOM(document.getElementById("tablefile_" + id).getElementsByTagName('tbody')[0]);
	
	var key_ = reverseString(id);
	var id_elem = reverseString(key_.substr(0, key_.indexOf("_")));
	key_ = reverseString(key_.substr(key_.indexOf("_") + 1));
	
	var tmp_arr = eval("globalSend." + key_);
	var arr = null;
	var id_in_array = -1;
	if(tmp_arr && Array.isArray(tmp_arr)) {
		for(var i = 0; i < tmp_arr.length; i++) {
			if(tmp_arr[i].id == id_elem) {
				if(eval("globalSend." + key_ + "[" + i + "].file")) {
					arr = eval("globalSend." + key_ + "[" + i + "].file");
					id_in_array = i;
					break;
				}
			}
		}
	}
	
	for(var i = 0; i < arr.length; i++) {
		var tr = document.createElement('tr');
		document.getElementById("tablefile_" + id).getElementsByTagName('tbody')[0].appendChild(tr);
		var td = document.createElement('td');
		td.appendChild(document.createTextNode(arr[i].filename));
		tr.appendChild(td);
		var td = document.createElement('td');
		td.appendChild(document.createTextNode(arr[i].size));
		tr.appendChild(td);
		var td = document.createElement('td');
		td.appendChild(document.createTextNode(arr[i].datetime));
		tr.appendChild(td);
		var td = document.createElement('td');
		tr.appendChild(td);
		var i_ = document.createElement('i');
		i_.setAttribute('class', "fa fa-trash");
		i_.setAttribute('onclick', "deleteFileInList('" + key_ + "','" + id_in_array + "','" + i + "')");
		td.appendChild(i_);
	}
}

//удаление файла из списка
function deleteFileInList(key, id, num) {
	if(!key || id < 0 || num < 0)
		return;
	if(eval("globalSend." + key + "[" + id + "].file[" + num + "]")) {
		var arr = eval("globalSend." + key + "[" + id + "].file");
		var tmp = new Array();
		for(var i = 0; i < arr.length; i++) {
			if(i != num)
				tmp.push(arr[i]);
		}
		eval("globalSend." + key + "[" + id + "].file=tmp");
	}
	var id_ = key + "_" + eval("globalSend." + key + "[" + id + "].id");
	addTableFile(id_);
}

//отправить в работу
function inWork() {
	if(!globalSend) {
		returnMessage("Объект для отправки пуст");
		return;
	}
	
	//проверяем или есть незаполненный инпут
	var inputs = document.getElementsByTagName('input');
	for(var i = 0; i < inputs.length; i++) {
		if(inputs[i].hasAttribute('id')) {
			if(inputs[i].id.indexOf("date_") >= 0 && inputs[i].value == "") {
				returnMessage("Заполните дату сдачи");
				return;
			}
			else if(inputs[i].id.indexOf("date_") >= 0) {
				var d_sel = new Date(inputs[i].value);
				var d_cur = new Date();
				if(d_cur.getTime() > d_sel.getTime()) {
					returnMessage("Выбранная дата не может быть меньше текущей");
					inputs[i].value = "";
					return;
				}
				//пишем дату в объект
				var key_ = reverseString(inputs[i].id.substr(5));
				var id_elem = reverseString(key_.substr(0, key_.indexOf("_")));
				key_ = reverseString(key_.substr(key_.indexOf("_") + 1));
				var arr = eval("globalSend." + key_);
				for(var j = 0; j < arr.length; j++) {
					if(arr[j].id == id_elem) {
						eval("globalSend." + key_ + "[" + j + "].date_rdy='" + inputs[i].value + "'");
						break;
					}
				}
			}
			if(inputs[i].id.indexOf("name_") >= 0 && inputs[i].value != "") {
				//пишем значение в объект
				var key_ = reverseString(inputs[i].id.substr(5));
				var id_elem = reverseString(key_.substr(0, key_.indexOf("_")));
				key_ = reverseString(key_.substr(key_.indexOf("_") + 1));
				var arr = eval("globalSend." + key_);
				for(var j = 0; j < arr.length; j++) {
					if(arr[j].id == id_elem) {
						eval("globalSend." + key_ + "[" + j + "].inner_name='" + inputs[i].value + "'");
						break;
					}
				}
			}
		}
	}
	//проверяем селекты
	var selects = document.getElementsByTagName('select');
	for(var i = 0; i < selects.length; i++) {
		if(selects[i].hasAttribute('id')) {
			if(selects[i].id.indexOf("codeStat_") >= 0 && selects[i].selectedIndex < 0) {
				returnMessage("Выберите наименование продукции");
				return;
			}
			
			//пишем значение в объект
			var key_ = reverseString(selects[i].id.substr(9));
			var id_elem = reverseString(key_.substr(0, key_.indexOf("_")));
			key_ = reverseString(key_.substr(key_.indexOf("_") + 1));
			var arr = eval("globalSend." + key_);
			for(var j = 0; j < arr.length; j++) {
				if(arr[j].id == id_elem) {
					var nm = getConcatenedTextContent(selects[i].options[selects[i].selectedIndex]);
					eval("globalSend." + key_ + "[" + j + "].code_stat='" + selects[i].value + "'");
					eval("globalSend." + key_ + "[" + j + "].name_code_stat='" + nm + "'");
					break;
				}
			}
		}
	}
	
	var arr_key = new Array();
	for(var key in globalSend) {
		arr_key.push(key);
	}
	
	if(arr_key.length <= 0)
		return;
	
	var arr = eval("globalSend." + arr_key[0]);
	sendOrder(arr_key, arr, 0, 0);
}

//отправка заявки на сервер
function sendOrder(arr_key, arr, ind_key, ind) {
	if(!arr_key[ind_key]) {
		//переходим на стартовую страницу
		clickCancel();
		return;
	}
	if(!arr[ind]) {
		ind_key++;
		var arr = eval("globalSend." + arr_key[ind_key]);
		sendOrder(arr_key, arr, ind_key, 0);
		return;
	}
	//собираем файлы в одну форму
	var datas = new FormData();
	if(arr[ind].file) {
		var fls = arr[ind].file;
		$.each(fls, function(key, value) {
			datas.append(key, value.value);
		});
	}
	
	//создаем форму и процент загрузки
	var mod_div = document.createElement('div');
	mod_div.setAttribute('id', "modal_upload");
	mod_div.setAttribute('style', "position: fixed;top: 0;left: 0;width: 100%;height: 100%;z-index: 2000;background: rgba(29,37,49,.9);");
	document.getElementsByTagName('body')[0].appendChild(mod_div);
	var head = document.createElement('h3');
	head.setAttribute('style', "color: #f7e946;top: 40%;position: relative;left: 25%;");
	head.setAttribute('id', "progress_label");
	head.appendChild(document.createTextNode(arr[ind].name + ": загрузка 0%"));
	mod_div.appendChild(head);
	var pr_div = document.createElement('div');
	pr_div.setAttribute('class', "c-progress c-progress--warning");
	pr_div.setAttribute('style', "top: 40%;position: relative;width: 50%;left: 25%;background-color: black;");
	mod_div.appendChild(pr_div);
	var progressbar_div = document.createElement('div');
	progressbar_div.setAttribute('id', "_progressbar");
	progressbar_div.setAttribute('class', "c-progress__bar");
	progressbar_div.setAttribute('role', "progressbar");
	progressbar_div.setAttribute('style', "width: 0;");
	progressbar_div.setAttribute('aria-valuenow', "0");
	progressbar_div.setAttribute('aria-valuemin', "0");
	progressbar_div.setAttribute('aria-valuemax', "100");
	pr_div.appendChild(progressbar_div);
	
	//формируем get из данных
	var get_ = {
		id: arr[ind].id,
		order_id: arr[ind].order_id
	}
	if(!arr[ind].date_rdy || !arr[ind].code_stat || !arr[ind].name_code_stat) {
		returnMessage("Заполнены не все данные");
		return;
	}
	get_.date_rdy = arr[ind].date_rdy.replace("T", " ");
	get_.code_stat = arr[ind].code_stat;
	get_.name_code_stat = arr[ind].name_code_stat;
	if(arr[ind].inner_name)
		get_.inner_name = arr[ind].inner_name;
	
	
	var xhr = new XMLHttpRequest();
	// обработчик для закачки
	xhr.upload.onprogress = function(event) {
		var pr = Math.floor(parseFloat(event.loaded) * 100 / parseFloat(event.total));
		var str = getConcatenedTextContent(document.getElementById("progress_label"));
		removeAllTextNodes(document.getElementById("progress_label"));
		str = str.substr(0, str.indexOf("загрузка") + 9) + pr + "%";
		document.getElementById("progress_label").appendChild(document.createTextNode(str));
		document.getElementById("_progressbar").setAttribute('style', "width:" + pr + "%");
		document.getElementById("_progressbar").setAttribute('aria-valuenow', pr);
	}
	// обработчики успеха и ошибки
	// если status == 200, то это успех, иначе ошибка
	xhr.onload = xhr.onerror = function() {
		if (this.status == 200) {
			removeElement("modal_upload");
			ind++;
			if(!this.response)
				returnMessage("Произошла ошибка при сохранении данных");
			else {
				var answer = JSON.parse(this.response);
				if(answer.error) {
					returnMessage(answer.message);
				}
				else
					sendOrder(arr_key, arr, ind_key, ind);
			}
		} else {
			returnMessage("error " + this.status, true);
		}
	};

	xhr.open("POST", "/bitrix24/modeler/modeler_transfer.php?transfer=" + JSON.stringify(get_), true);
	xhr.send(datas);
}









