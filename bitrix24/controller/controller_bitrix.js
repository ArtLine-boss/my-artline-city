//при загрузке страницы
window.onload = function() {	
	//событие начала запроса аякс
	$(document).ajaxStart(function() {
        LoaderModal();
    });
	
	//завершение запроса
	$(document).ajaxComplete(function(event, jqxhr, settings) {
        removeElement("ajax_preloader");
    });
	$(document).ajaxStop(function() {
        removeElement("ajax_preloader");
    });
	$(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
        removeElement("ajax_preloader");
    });
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

//чистим селекты
function clearSelect(select) {
	while(select.options.length > 0) {
		remove(select.options[0]);
	}
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

//переворот строки
function reverseString(str) {
	var splitString = str.split("");
	var reverseArray = splitString.reverse();
	var joinArray = reverseArray.join("");
	return joinArray;
}

//вывод сообщения
function returnMessage(txt, error) {
	//контейнер
	var container = document.createElement('div');
	container.setAttribute('class', "c-modal modal fade");
	container.setAttribute('id', "messageAlert");
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


