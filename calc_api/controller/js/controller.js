//загрузка документа
window.onload = function() {
	$('.preloader').fadeOut('slow', function() {});
	
	//событие начала запроса аякс
	$(document).ajaxStart(function() {
        $('.preloader').fadeIn('slow', function() {});
    });
	
	//завершение запроса
	$(document).ajaxComplete(function(event, jqxhr, settings) {
        $('.preloader').fadeOut('slow', function() {});
    });
	$(document).ajaxStop(function() {
        $('.preloader').fadeOut('slow', function() {});
    });
	$(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        $('.preloader').fadeOut('slow', function() {});
    });
}

//после загрузки документа запускаем инициализацию
$(document).ready(function() {
	initPage();
});

/***
запуск удаления элемента
id - идентификатор удаляемого блока, тега
***/
function removeElement(id) {
	var elem = document.getElementById(id);
	if(elem)
		remove(elem);
}

/***
удаление элемента
elem - удаляемый объект, тег
***/
function remove(elem) {
    return elem.parentNode ? elem.parentNode.removeChild(elem) : elem;
}

/***
удаление текста между тегами
node - тег
***/
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

/***
инициализация
c_pg - увеличивающее значение страницы
***/
function initPage(c_pg) {
	//страницы формы
	var pg = document.getElementById("current_page_title");
	if(!pg)
		return;
	var current_page = 1;
	var max_page = 1;
	if(pg.hasAttribute("data-current-page")) {
		current_page = Number(pg.getAttribute("data-current-page"));
		if(current_page <= 0)
			current_page = 1;
	}
	if(pg.hasAttribute("data-max-page")) {
		max_page = Number(pg.getAttribute("data-max-page"));
		if(current_page > max_page)
			max_page = current_page;
	}
	if(c_pg) {
		c_pg = Number(c_pg);
		//проверяем или заполнены обязательные поля при переходе вверх
		if(c_pg > 0) {
			if(!checkedForm(current_page))
				return;
		}
		current_page = current_page + c_pg;
		if(current_page <= 0)
			current_page = 1;
		else if(current_page > max_page)
			current_page = max_page;
		pg.setAttribute("data-current-page", current_page);
	}
	//если последняя станица, то проевляем кнопку отправить
	if(max_page == current_page) {
		document.getElementById("btn_next").setAttribute('style', "display:none;");
		document.getElementById("btn_send").setAttribute('style', "display:block;");
	}
	else {
		document.getElementById("btn_next").setAttribute('style', "display:block;");
		document.getElementById("btn_send").setAttribute('style', "display:none;");
	}
	//чистим текст
	removeAllTextNodes(pg);
	pg.appendChild(document.createTextNode(current_page + "/" + max_page));
	//закрываем все табы
	var tabs = document.getElementsByName("tab");
	for(var i = 0; i < tabs.length; i++) {
		tabs[i].setAttribute('style', "display:none;");
	}
	//открываем текущий таб
	if(document.getElementById("tab_" + current_page)) {
		document.getElementById("tab_" + current_page).setAttribute('style', "display:block;");
	}
	//прогресс бар ставим по шагу
	var val_progress = current_page * 100 / max_page;
	document.getElementById("current_page_progress").setAttribute('style', "width:" + val_progress + "%;");
}

//кнопка назад
function btn_back() {
	initPage(-1);
}

//кнопка вперед
function btn_next() {
	initPage(1);
}

//кнопка отправить
function btn_send(ajax_form, id_form) {
	if(!ajax_form || !id_form) {
		returnErrorGlobal(true, "Ошибка. Обновите страницу и попробуйте заново");
		return;
	}
	
	//проверяем форму
	if(!document.getElementById("current_page_title") || !document.getElementById("current_page_title").hasAttribute("data-max-page")) {
		returnErrorGlobal(true, "Ошибка. Обновите страницу и попробуйте заново");
		return;
	}
	if(!checkedForm(document.getElementById("current_page_title").getAttribute("data-max-page")))
		return;
	
	$.ajax({
		type: "POST",
		url: "../modeler/modeler.php?forms=" + id_form,
		dataType: "html",
        data: $("#" + ajax_form).serialize(),
        success: function(response) {
			if(response) {
				returnErrorGlobal(false, "Заявка отправлена менеджеру. Ответ будет выслан Вам на почту", document.getElementById(ajax_form), true);
			}
			else {
				returnErrorGlobal(true, "Ошибка. Обновите страницу и попробуйте заново", document.getElementById(ajax_form), true);
			}
    	},
    	error: function(response) {
            returnErrorGlobal(true, "Ошибка. Обновите страницу и попробуйте заново", document.getElementById(ajax_form), true);
    	}
	});
}

/***
сообщение об ошибке/успехе... убирает форму на странице
параметр error принимает true(есть ошибка) или false(нет ошибки)
параметр frm - объет формы
***/
function returnErrorGlobal(error, message, frm, out) {
	if(document.getElementById("message_error")) {
		document.getElementById("message_error").setAttribute('style', "display:block;");
		removeAllTextNodes(document.getElementById("message_error"));
		if(out) {
			var i_ = document.createElement("i");
			if(error === false) {
				document.getElementById("message_error").setAttribute('style', "display:block;background-color:#1bb934;height:100%");
				i_.setAttribute('class', "fa fa-check-circle me_ico_size");
			}
			else {
				document.getElementById("message_error").setAttribute('style', "display:block;height:100%");
				i_.setAttribute('class', "fa fa-times-circle me_ico_size");
			}
			document.getElementById("message_error").appendChild(i_);
			var p = document.createElement('p');
			p.setAttribute('style', "color: #fff;");
			p.appendChild(document.createTextNode(message));
			document.getElementById("message_error").appendChild(p);
			setTimeout(function() {
				location.reload();
			}, 5000);
		}
		else {
			document.getElementById("message_error").appendChild(document.createTextNode(message));
			if(error === false) {
				document.getElementById("message_error").setAttribute('style', "display:block;background-color:#1bb934;");
			}
		}
		if(frm)
			remove(frm);
	}
}

//проверяем заполненность инпутов/селектов
function checkedForm(index) {
	if(!index || !document.getElementById("tab_" + index)) {
		returnErrorGlobal(true, "Ошибка. Обновите страницу и попробуйте заново");
		return false;
	}
	//проверяем инпуты
	var inputs = document.getElementById("tab_" + index).getElementsByTagName('input');
	for(var i = 0; i < inputs.length; i++) {
		if(inputs[i].hasAttribute('data-checked')) {
			var val = inputs[i].getAttribute('data-checked');
			var prnt = inputs[i].parentNode;
			if(prnt && val == "true") {
				removeElement('error_window_popup');
				var cls = document.getElementsByClassName("error_window");
				for(var j = 0; j < cls.length; j++) {
					cls[j].classList.remove("error_window");
				}
				if(inputs[i].value == "") {
					prnt.classList.add("error_window");
					prnt.insertBefore(addBlockMessageError("Пожалуйста, заполните все обязательные поля"), inputs[i]);
					return false;
				}
				else if(inputs[i].hasAttribute('type') && inputs[i].getAttribute('type') == "number" && inputs[i].value <= 0) {
					prnt.classList.add("error_window");
					prnt.insertBefore(addBlockMessageError("Пожалуйста, заполните все обязательные поля"), inputs[i]);
					return false;
				}
			}
		}
	}
	
	//проверяем селекты
	var selects = document.getElementById("tab_" + index).getElementsByTagName('select');
	for(var i = 0; i < selects.length; i++) {
		if(selects[i].hasAttribute('data-checked')) {
			var val = selects[i].getAttribute('data-checked');
			var prnt = selects[i].parentNode;
			if(prnt && val == "true") {
				removeElement('error_window_popup');
				var cls = document.getElementsByClassName("error_window");
				for(var j = 0; j < cls.length; j++) {
					cls[j].classList.remove("error_window");
				}
				if(selects[i].selectedIndex < 0) {
					prnt.classList.add("error_window");
					prnt.insertBefore(addBlockMessageError("Пожалуйста, заполните все обязательные поля"), selects[i]);
					return false;
				}
			}
		}
	}
	
	return true;
}

//возвращает блок-сообщение об ошибке
function addBlockMessageError(mess) {
	if(!mess)
		mess = "Ошибка";
	var label = document.createElement('label');
	label.setAttribute('id', "error_window_popup");
	label.setAttribute('class', "error_window_popup");
	label.appendChild(document.createTextNode(mess));
	return label;
}

/***
выбор в списке радиобатон (когда картинки)
***/
function checkedRadioImg(target) {
	if(!target.hasAttribute('data-input-id')) {
		returnErrorGlobal(true, "Ошибка. Обновите страницу и попробуйте заново");
		return;
	}
	var inp_id = target.getAttribute('data-input-id');
	if(!document.getElementById(inp_id)) {
		returnErrorGlobal(true, "Ошибка. Обновите страницу и попробуйте заново");
		return;
	}
	document.getElementById(inp_id).value = "";
	if(!target || !target.hasAttribute("data-id")) {
		returnErrorGlobal(true, "Ошибка. Обновите страницу и попробуйте заново");
		return;
	}
	var chImg = document.getElementsByName(inp_id + "_checkedImg");
	while (chImg.length > 0) {
		remove(chImg[0]);
	}
	var val = target.getAttribute("data-id");
	if(val != "") {
		document.getElementById(inp_id).value = val;
		var i_ = document.createElement('i');
		i_.setAttribute('class', "fa fa-check-circle fa-2x");
		i_.setAttribute('name', inp_id + "_checkedImg");
		i_.setAttribute('style', "position:absolute;left:0;");
		target.appendChild(i_);
	}
}

//чекед на чекбоксе
function changeChecked(e) {
	e.value = e.checked
}



