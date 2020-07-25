//кнопка создать
function createReport(download) {
	if(document.getElementById("clientname").selectedIndex < 0) {
		alert("Выберите клиента");
		return;
	}
	var send = {
		client: document.getElementById("clientname").value,
		startDate: document.getElementById("startDate").value,
		endDate: document.getElementById("endDate").value,
	}
	
	if(download)
		send.download = true;
	
	$.ajax({
		type: "GET",
		url: "../pg/modeler.php",
		data: {'reportOrder': JSON.stringify(send)},
		cache: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer.path) {
					var url = "../pg/modeler.php?dFile=" + JSON.stringify(answer.path);
					location.href = url;
				}
				if(answer.arr) {
					createTable();
					drawDataInTable(answer.arr);
					$('#tableReport').DataTable({
						responsive: true,
						"iDisplayLength": 25
					});
				}
				else {
					alert("Нет данных");
				}
			}
			else {
				alert("Нет данных");
			}
		},
		error: function() {
			alert("Ошибка запроса");
		}
	});
}

//создание таблицы
function createTable() {
	if(document.getElementById("tableReport")) {
		deleteFooterDOM(document.getElementById("containerTable"));
	}
	
	var table = document.createElement('table');
	table.setAttribute('id', "tableReport");
	table.setAttribute('class', "table table-striped table-bordered table-hover");
	document.getElementById("containerTable").appendChild(table);
	
	var thead = document.createElement('thead');
	table.appendChild(thead);
	var tr = document.createElement('tr');
	thead.appendChild(tr);
	
	tr.appendChild(createTH("Клиент"));
	tr.appendChild(createTH("Дата"));
	tr.appendChild(createTH("Наименование"));	
	tr.appendChild(createTH("Количество"));	
	tr.appendChild(createTH("Размер"));	
	tr.appendChild(createTH("Количество страниц"));	
	tr.appendChild(createTH("Материал"));	
	tr.appendChild(createTH("Красочность"));	
	tr.appendChild(createTH("Количество печатных листов"));
	tr.appendChild(createTH("Сшивка"));
	tr.appendChild(createTH("Ламинирование"));
	
	var tbody = document.createElement('tbody');
	table.appendChild(tbody);
}

//рисуем данные
function drawDataInTable(data) {
	if(!document.getElementById("tableReport") || !data || !Array.isArray(data))
		return;
	
	var tbody = document.getElementById("tableReport").getElementsByTagName('tbody')[0];
	if(!tbody)
		return;
	
	for(var i = 0; i < data.length; i++) {
		var tr = document.createElement('tr');
		tbody.appendChild(tr);

		tr.appendChild(createTD(data[i].client));
		tr.appendChild(createTD(data[i].dates));
		tr.appendChild(createTD(data[i].name));
		tr.appendChild(createTD(data[i].total));
		tr.appendChild(createTD(data[i].size));
		tr.appendChild(createTD(data[i].pages));
		tr.appendChild(createTD(data[i].material));
		tr.appendChild(createTD(data[i].color));
		tr.appendChild(createTD(data[i].list));
		tr.appendChild(createTD(data[i].cshivka));
		tr.appendChild(createTD(data[i].lam));
	}
}

//создание ячейки заголовка
function createTH(title) {
	var th = document.createElement('th');
	if(title)
		th.appendChild(document.createTextNode(title));
	return th;
}

//создание ячейки данных
function createTD(title) {
	var td = document.createElement('td');
	if(title)
		td.appendChild(document.createTextNode(title));
	return td;
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


