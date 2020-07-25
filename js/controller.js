//Прикалюшки на странице на Новый Год
function NewYear(doc, timeout) {
	if(addNewYear == undefined || !addNewYear)
		return;
	var d_today = new Date();
	var year_start = d_today.getFullYear();
	var year_end = d_today.getFullYear();
	if(d_today.getMonth() == 0)
		year_start--;
	else
		year_end++;
	var d_start = new Date(year_start, 11, 20);
	var d_end = new Date(year_end, 0, 8);
	
	if(d_today.getTime() < d_start.getTime() || d_today.getTime() > d_end.getTime())
		return;
	
	var script = doc.createElement("script");
	script.src = "/js/snowfall/snowfall.js";
	script.type="text/javascript";
	doc.getElementsByTagName("head")[0].appendChild(script);
	
	//когда скрипт загрузился
	script.onload = function() {
		var body = doc.getElementsByTagName('body')[0];
		//добавляем гирлянду
		var div = document.createElement('div');
		div.setAttribute('id', "girlianda");
		div.setAttribute('style', "height: 30px; background: url(/js/snowfall/img/girlianda.gif) repeat-x 100%; position: fixed; width: 100%; z-index: 1000; top: 0");
		body.appendChild(div);
		div.onclick = function() {
			remove(doc.getElementById("girlianda"));
		}
		
		//добавляем елку
		var div = document.createElement('div');
		div.setAttribute('id', "elka");
		div.setAttribute('style', "position:fixed; z-index: 1000; bottom: 0; right: 0;");
		body.appendChild(div);
		var img = document.createElement('img');
		img.setAttribute('src', "/js/snowfall/img/elka.gif");
		img.setAttribute('width', 118);
		img.setAttribute('height', 173);
		div.appendChild(img);
		div.onclick = function() {
			remove(doc.getElementById("elka"));
		}
		
		//запускаем снег
		/*$(doc).snowfall({
			image :"/js/snowfall/img/snow4.png", 
			flakeCount: 100,
			minSize: 10, 
			maxSize:50
		});*/
		
		//через время удаляем
		/*setTimeout(function(){
			if(doc.getElementById("elka"))
				remove(doc.getElementById("elka"));
			if(doc.getElementById("girlianda"))
				remove(doc.getElementById("girlianda"));
			
			var imgs = doc.getElementsByClassName("snowfall-flakes");
			while(imgs.length > 0) {
				remove(imgs[0]);
			}
		}, timeout);*/
	}
}

//совмещение пользователей
function ComboUser() {
	CreateModal("ComboUser", "Совмещенный пользователь", "funcComboUser");
	var modal_body = document.getElementById("ComboUserBody");
	
	$.ajax({
		type: "GET",
		url: "/pages/pg/modeler.php?InitComboUser",
		data: {},
		cache: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer) {
					var row = document.createElement('div');
					row.setAttribute('class', "row");
					row.setAttribute('style', "margin-right: 0;margin-left: 0");
					modal_body.appendChild(row);
					
					if(answer.init) {
						if(!answer.data || !Array.isArray(answer.data) || answer.data.length <= 0) {
							alert("Не удалось получить массив пользователей");
							return;
						}
						var label = document.createElement('label');
						label.appendChild(document.createTextNode("Выберите пользователя"));
						row.appendChild(label);
						var select = document.createElement('select');
						select.setAttribute('id', "ComboUserList");
						select.setAttribute('class', "form-control");
						row.appendChild(select);
						for(var i = 0; i < answer.data.length; i++) {
							var option = document.createElement('option');
							option.setAttribute('value', answer.data[i].login);
							option.appendChild(document.createTextNode(answer.data[i].user));
							select.appendChild(option);
						}
						select.selectedIndex = -1;
					}
					else {
						var mess = "Уже выполнено совмещение с пользователем " + answer.user_children + ". Для создания нового совмещения необходимо отменить текущее";
						var label = document.createElement('label');
						label.appendChild(document.createTextNode(mess));
						row.appendChild(label);
						var btn = document.createElement('button');
						btn.setAttribute('class', "btn btn-danger");
						btn.appendChild(document.createTextNode("Отменить текущее совмещение"));
						row.appendChild(btn);
						btn.onclick = function() {
							$.ajax({
								type: "GET",
								url: "/pages/pg/modeler.php?deactiveComboUser",
								data: {},
								cache: false,
								success: function(res) {
									if(res) {
										var ans = JSON.parse(res);
										if(ans) {
											if(ans.not_error) {
												location.href = "/pages/orders.php";
											}
											else {
												alert(ans.message);
											}
										}
										else {
											alert("Пустой объект");
										}
									}
									else {
										alert("Пустой ответ");
									}
								},
								error: function( jqXHR, textStatus, errorThrown ){
									alert('ОШИБКИ AJAX запроса: ' + errorThrown, true);
								}
							});
						}
					}
				}
				else {
					alert("Пустой объект ответа");
				}
			}
			else {
				alert("Пустой ответ");
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			alert('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
	
	$("#ComboUser").modal('show');
}

//совмещение пользователей
function funcComboUser() {
	if(!document.getElementById("ComboUserList")) {
		$("#ComboUser").modal('hide');
		return;
	}
	if(document.getElementById("ComboUserList").selectedIndex < 0) {
		alert("Выберите пользователя");
		return;
	}
	$.ajax({
		type: "GET",
		url: "/pages/pg/modeler.php",
		data: {'saveComboUser': JSON.stringify(document.getElementById("ComboUserList").value)},
		cache: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer) {
					if(answer.not_error) {
						$("#ComboUser").modal('hide');
						location.href = "/pages/orders.php";
					}
					else {
						alert(answer.message);
					}
				}
				else {
					alert("Пустой объект ответа");
				}
			}
			else {
				alert("Пустой ответ от сервера");
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			alert('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
}

//изменение расскраски
function SelectedFont() {
	$.ajax({
		type: "GET",
		url: "/pages/pg/modeler.php?SelectedFont",
		data: {},
		cache: false,
		success: function(respond) {
			if(respond) {
				var answer = JSON.parse(respond);
				if(answer) {
					if(!answer.not_error) {
						alert(answer.message);
					}
					else {
						location.reload();
					}
				}
				else {
					alert("Пустой объект");
				}
			}
			else {
				alert("Пустой ответ");
			}
		},
		error: function( jqXHR, textStatus, errorThrown ){
			alert('ОШИБКИ AJAX запроса: ' + errorThrown, true);
        }
	});
}

//создание модалки
function CreateModal(id, label, funcSubmit) {
	//создаем форму
	//модалка
	var modal = document.createElement('div');
	modal.setAttribute('class', "modal fade");
	modal.setAttribute('id', id);
	modal.setAttribute('tabindex', "-1");
	modal.setAttribute('role', "dialog");
	modal.setAttribute('aria-labelledby', id + "Label");
	modal.setAttribute('aria-hidden', true);
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
	h4.setAttribute('id', id + "Label");
	h4.appendChild(document.createTextNode(label));
	header.appendChild(h4);
	//тело модалки
	var body = document.createElement('div');
	body.setAttribute('id', id + "Body");
	body.setAttribute('class', "modal-body");
	content.appendChild(body);
	
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
	if(funcSubmit) {
		//кнопка ОК
		var btn_print = document.createElement('button');
		btn_print.setAttribute('type', "button");
		btn_print.setAttribute('onclick', funcSubmit + "()");
		btn_print.setAttribute('class', "btn btn-success");
		btn_print.appendChild(document.createTextNode("ОК"));
		footer.appendChild(btn_print);
	}
	
	//при скрывании модалки - удаляем её
	$('#' + id).on('hidden.bs.modal', function (e) {
		remove(document.getElementById(id));
	});
}

//удаление элемента
function remove(elem) {
    return elem.parentNode ? elem.parentNode.removeChild(elem) : elem;
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

function selectedFirm(idAct) {
	$.ajax({
		type: 'GET',
		url: '/www/core/ajax.php?m=orders&u=selectedFirm&a=AjaxSelectedFirm&idAct=' + idAct,
		cache: false,
		success: function (res) {
			if(res && res.Msg && res.Msg != '') {
				alert(res.Msg);
			} else {
				location.href = location.href;
			}
		}
	});
}
