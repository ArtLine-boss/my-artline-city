//загрузка документа
$(document).ready(function () {
	loadData();
	$('#tableCode').DataTable({
		responsive: true,
		"iDisplayLength": 25
	});
	$('.dataTables_length').addClass('bs-select');
	$('[data-toggle="tooltip"]').tooltip({container: 'body'});
});

//загрузка данных в таблицу
function loadData() {
	var table = document.getElementById("tableCode");
	if(!table)
		return;
	var tbody = table.getElementsByTagName('tbody')[0];
	if(!tbody)
		return;
	clearTable(table);
	//запрос к БД
	$.ajax({
		type: "GET",
		url: "pg/modeler.php?loadDataCodeStatFull",
		data: {},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer && Array.isArray(answer) && answer.length > 0) {
					for(var i = 0; i < answer.length; i++) {
						var tr = document.createElement('tr');
						tr.setAttribute('data-id', answer[i].id);
						tbody.appendChild(tr);
						var td = document.createElement('td');
						td.appendChild(document.createTextNode(answer[i].name));
						tr.appendChild(td);
						var td = document.createElement('td');
						td.setAttribute('data-toggle', "tooltip");
						td.setAttribute('data-original-title', answer[i].comm);
						td.appendChild(document.createTextNode(answer[i].code_stat));
						tr.appendChild(td);
						//клик по строке
						tr.onclick = function(e) {
							var target = e.target;
							if(!target)
								return;
							while(target.nodeName !== 'TD') {
								if(target.nodeName === 'TR')
									return;
								if(target.nodeName === 'TH') {
									return;
								}
								target = target.parentNode;
							}
							
							var parent = target.parentNode;
							
							if(!parent.hasAttribute('data-id'))
								return;
							
							var id = parent.getAttribute('data-id');
							
							$.ajax({
								type: "GET",
								url: "pg/modeler.php?loadDataCodeStatCurrent=" + id,
								data: {},
								cache: false,
								async: false,
								success: function(respond_) {
									if(respond_) {
										var answer_ = JSON.parse(respond_);
										if(answer_ && answer_.name && answer_.code) {
											addElem();
											document.getElementById("modalName").value = answer_.name;
											document.getElementById("modalName").setAttribute('data-id', id);
											document.getElementById("modalListCode").value = answer_.code;
											document.getElementById("modalListCode").dispatchEvent(new Event('change'));
										}
									}
								}
							});
						}
					}
				}
			}
		}
	});
}

//модалка на создание новой записи
function addElem() {
	//создаем форму
	//модалка
	var modal = document.createElement('div');
	modal.setAttribute('class', "modal fade");
	modal.setAttribute('id', "modalElem");
	modal.setAttribute('tabindex', "-1");
	modal.setAttribute('role', "dialog");
	modal.setAttribute('aria-labelledby', "modalElemLabel");
	modal.setAttribute('aria-hidden', "true");
	document.getElementsByTagName('body')[0].appendChild(modal);
	//диалог
	var dialog = document.createElement('div');
	dialog.setAttribute('class', "modal-dialog");
	dialog.setAttribute('role', "document");
	modal.appendChild(dialog);
	//контент
	var content = document.createElement('div');
	content.setAttribute('class', "modal-content");
	dialog.appendChild(content);
	//заголовок
	var header = document.createElement('div');
	header.setAttribute('class', "modal-header");
	content.appendChild(header);
	//... кнопка закрыть...
	//...спан...
	var span = document.createElement('span');
	span.setAttribute('class', "close");
	span.setAttribute('data-dismiss', "modal");
	span.setAttribute('aria-label', "Close");
	header.appendChild(span);											
	//значок закрытия
	var i_ = document.createElement('i');
	i_.setAttribute('class', "fa fa-close");
	span.appendChild(i_);
	//...заголовок окна...
	var h4 = document.createElement('h4');
	h4.setAttribute('class', "modal-title");
	h4.setAttribute('id', "modalElemLabel");
	h4.appendChild(document.createTextNode("Создание или редактирование кода"));
	header.appendChild(h4);
	//тело модалки
	var body = document.createElement('div');
	body.setAttribute('class', "modal-body");
	content.appendChild(body);

	//поля
	//наименование
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	body.appendChild(row);
	var col = document.createElement('div');
	col.setAttribute('class', "col-md-4");
	col.appendChild(document.createTextNode("Наименование товара"));
	row.appendChild(col);
	var col = document.createElement('div');
	col.setAttribute('class', "col-md-8");
	row.appendChild(col);
	var input = document.createElement('input');
	input.setAttribute('type', "text");
	input.setAttribute('class', "form-control");
	input.setAttribute('id', "modalName");
	col.appendChild(input);
	//список кодов
	var row = document.createElement('div');
	row.setAttribute('class', "row");
	body.appendChild(row);
	var col = document.createElement('div');
	col.setAttribute('class', "col-md-4");
	col.appendChild(document.createTextNode("Список кодов"));
	row.appendChild(col);
	var col = document.createElement('div');
	col.setAttribute('class', "col-md-8");
	row.appendChild(col);
	var select_ = document.createElement('select');
	select_.setAttribute('id', "modalListCode");
	select_.setAttribute('class', "js-example-basic-single js-states form-control");
	col.appendChild(select_);
	//заполняем селект
	$.ajax({
		type: "GET",
		url: "pg/modeler.php?loadDataCodeStat",
		data: {},
		cache: false,
		async: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer && Array.isArray(answer) && answer.length > 0) {
					for(var i = 0; i < answer.length; i++) {
						var option_ = document.createElement('option');
						option_.setAttribute('value', answer[i].code_stat);
						option_.setAttribute('title', answer[i].comm);
						option_.appendChild(document.createTextNode(answer[i].code_stat));
						document.getElementById("modalListCode").appendChild(option_);
					}
				}
			}
		}
	});
	$('#modalListCode').select2({
		width: '100%',
		placeholder: 'Выберите вид товара',
		dropdownParent: $('#modalElem')
	});
	$('#modalListCode').val(-1).trigger('change');
	
	//подвал
	var footer = document.createElement('div');
	footer.setAttribute('class', "modal-footer");
	content.appendChild(footer);
	//кнопка отмена
	var btn_close = document.createElement('button');
	btn_close.setAttribute('type', "button");
	btn_close.setAttribute('class', "btn btn-secondary");
	btn_close.setAttribute('data-dismiss', "modal");
	btn_close.appendChild(document.createTextNode("Отмена"));
	footer.appendChild(btn_close);
	//кнопка удаление
	var btn_delete = document.createElement('button');
	btn_delete.setAttribute('type', "button");
	btn_delete.setAttribute('class', "btn btn-danger");
	btn_delete.appendChild(document.createTextNode("Удалить"));
	footer.appendChild(btn_delete);
	btn_delete.onclick = function() {
		if(!document.getElementById("modalName").hasAttribute('data-id') || parseInt(document.getElementById("modalName").getAttribute('data-id')) <= 0)
			return;
		var id = parseInt(document.getElementById("modalName").getAttribute('data-id'));
		//сохранение
		$.ajax({
			type: "GET",
			url: "pg/modeler.php?deleteDataCodeStatCurrent=" + id,
			data: {},
			cache: false,
			success: function(respond) {
				if(respond) {
					window.location.reload();
				}
			}
		});
	}
	//кнопка Создать
	var btn_print = document.createElement('button');
	btn_print.setAttribute('type', "button");
	btn_print.setAttribute('class', "btn btn-success");
	btn_print.appendChild(document.createTextNode("Создать"));
	footer.appendChild(btn_print);
	//клик по кнопке
	btn_print.onclick = function() {
		if(!returnMessageErrorForm(document.getElementById("modalName")) || !returnMessageErrorForm(document.getElementById("modalListCode")))
			return;
		var send = {
			name: document.getElementById("modalName").value,
			code: document.getElementById("modalListCode").value
		}
		if(document.getElementById("modalName").hasAttribute('data-id') && parseInt(document.getElementById("modalName").getAttribute('data-id')) > 0)
			send.id = parseInt(document.getElementById("modalName").getAttribute('data-id'));
		//сохранение
		$.ajax({
			type: "GET",
			url: "pg/modeler.php",
			data: {"insertDataCodeStat": JSON.stringify(send)},
			cache: false,
			success: function(respond) {
				if(respond) {
					window.location.reload();
				}
			}
		});
	}
	
	//при скрывании модалки - удаляем её
	$('#modalElem').on('hidden.bs.modal', function (e) {
		remove(document.getElementById("modalElem"));
	});
	//открываем модалку
	$('#modalElem').modal('show');
}

//очистить таблицу
function clearTable(table) {
	var tbodys = table.getElementsByTagName('tbody');
	if(tbodys.length <= 0)
		return;
	var tbody = tbodys[0];
	var trs = tbody.getElementsByTagName('tr');
	if(trs.length <= 0)
		return;
	
	while(trs.length > 0)
		remove(trs[0]);
}

//удаление элемента
function remove(elem) {
    return elem.parentNode ? elem.parentNode.removeChild(elem) : elem;
}

//ошибка при заполнении формы
function returnMessageErrorForm(elem) {
	if(!elem)
		return true;
	//родитель
	var prnt = elem.parentNode;
	var smalls = prnt.getElementsByTagName('small');
	while(smalls.length > 0) {
		remove(smalls[0]);
	}
	
	if(elem.tagName == "SELECT") {
		if(elem.selectedIndex < 0) {
			var small = document.createElement('small');
			small.setAttribute('class', "c-field__message u-color-danger");
			prnt.appendChild(small);
			var i_ = document.createElement('i');
			i_.setAttribute('class', "fa fa-times-circle");
			small.appendChild(i_);
			small.appendChild(document.createTextNode("Заполните данные"));
			
			return false;
		}
	}
	else if(elem.tagName == "INPUT") {
		if(elem.value == "") {
			var small = document.createElement('small');
			small.setAttribute('class', "c-field__message u-color-danger");
			prnt.appendChild(small);
			var i_ = document.createElement('i');
			i_.setAttribute('class', "fa fa-times-circle");
			small.appendChild(i_);
			small.appendChild(document.createTextNode("Заполните данные"));
			
			return false;
		}
	}
	
	return true;
}





