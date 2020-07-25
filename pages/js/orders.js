//загрузка документа
$(document).ready(function() {

  loadReportOfManager();

  //клики по таблице
  var table = document.getElementById("table_report_manager");
  table.onclick = function(e) {
    //определяем ячейку
    var target = e.target;
    if (!target)
      return;
    while (target.nodeName !== 'TD') {
      if (target.nodeName === 'TR')
        return;
      if (target.nodeName === 'TH') {
        sortTable(table, target);
        return;
      }
      target = target.parentNode;
    }
  }

  //загрузка таблицы "Оплачено, но не в работе"
  loadTableNotTask();
});

//запрос на почту
function checkedPost(elem) {
  if ($.fn.dataTable.isDataTable('#tb_post'))
    return;
  var default_options7 = {
    "responsive": true,
    "paging:": true,

    "iDisplayLength": 25,


    "ajax": {
      "url": 'ajax_php_sql.php?flag=48&id_cl=' + id_cl,
      "type": "GET",
      "dataSrc": ""
    },
    "columns": [{
        "data": "view"
      },
      {
        "data": "num"
      },
      {
        "data": "cl"
      },
      {
        "data": "price"
      },
      {
        "data": "track"
      },
      {
        "data": "dates"
      },
      {
        "data": "status"
      },
      {
        "data": "rty"
      }


    ],
    "aaSorting": [
      [2, 'desc']
    ]
  };

  var tb_post = $('#tb_post').dataTable(default_options7);
}

//рисует отчет по менеджерам
function loadReportOfManager() {
  var table = document.getElementById('table_report_manager');
  var tbody = table.getElementsByTagName('tbody')[0];
  deleteFooterDOM(tbody);

  $.ajax({
    type: "GET",
    url: "pg/modeler.php?loadReportOfManager",
    data: {},
    cache: false,
    success: function(respond, textStatus, jqXHR) {
      if (respond) {
        var message = JSON.parse(respond);
        if (message && Array.isArray(message)) {
          for (var i = 0; i < message.length; i++) {
            var tr = document.createElement('tr');
            tbody.appendChild(tr);
            //имя менеджера
            var td = document.createElement('td');
            td.appendChild(document.createTextNode(message[i].manager));
            tr.appendChild(td);
            //номер счета
            var td = document.createElement('td');
            td.appendChild(document.createTextNode(message[i].smeta));
            tr.appendChild(td);
            //клиент
            var td = document.createElement('td');
            td.appendChild(document.createTextNode(message[i].client_name));
            tr.appendChild(td);
            //наименование
            var td = document.createElement('td');
            td.appendChild(document.createTextNode(message[i].name));
            tr.appendChild(td);
            //дата добавления
            var td = document.createElement('td');
            if (message[i].add_date)
              td.appendChild(document.createTextNode(message[i].add_date));
            tr.appendChild(td);
            //дата отправки в работу
            var td = document.createElement('td');
            td.appendChild(document.createTextNode(message[i].date_));
            tr.appendChild(td);
            //дата сдачи
            var td = document.createElement('td');
            td.appendChild(document.createTextNode(message[i].date_rdy));
            tr.appendChild(td);
            //статус
            var td = document.createElement('td');
            tr.appendChild(td);
            var st = parseInt(message[i].status);
            var span = document.createElement('span');
            td.appendChild(span);
            switch (st) {
              case 1:
                span.setAttribute('class', "label label-info");
                span.appendChild(document.createTextNode("Цех"));
                break;
              case 4:
                span.setAttribute('class', "label label-danger");
                span.appendChild(document.createTextNode("Брак"));
                break;
              case 10:
                span.setAttribute('class', "label label-primary");
                span.appendChild(document.createTextNode("Дизайн"));
                break;
              case 11:
                span.setAttribute('class', "label label-primary");
                span.appendChild(document.createTextNode("Препресс"));
                break;
              case 12:
                span.setAttribute('class', "label label-warning");
                span.appendChild(document.createTextNode("Печать"));
                break;
              case 20:
                span.setAttribute('class', "label label-danger");
                span.appendChild(document.createTextNode("Возврат"));
                break;
              case 21:
                span.setAttribute('class', "label label-danger");
                span.appendChild(document.createTextNode("Возврат"));
                break;
            }
          }
        }
      }
    }
  });
}

//отчет "Оплачено, но не в работе"
function loadTableNotTask() {
	var colms = [];
	//для админов видно всех
	if(document.getElementById('th_manager')) {
		colms = [
			{data: "user_fio"},
			{data: "order_id"},
			{data: "client_name"},
			{data: "p_names"},
			{data: "summ"},
			{data: "oplata"},
			{data: "func"},
		];
	}
	//для остальных - только их заказы
	else {
		colms = [
			{data: "order_id"},
			{data: "client_name"},
			{data: "p_names"},
			{data: "summ"},
			{data: "oplata"},
			{data: "func"},
		];
	}
	var default_options = {
		"iDisplayLength": 50,
		"responsive": true,
		"ajax": {
			"type": "GET",
			"url": "pg/modeler.php?loadDataNotTask",
			"dataSrc": ""
		},
		"columns": colms,
	};
	var table = $('#table_not_task').dataTable(default_options);
}

//функция для отправки в работу
function sendInWork(elem) {
	//создаем форму
	//модалка
	var modal = document.createElement('div');
	modal.setAttribute('class', "modal fade");
	modal.setAttribute('id', "modalSendInWork");
	modal.setAttribute('tabindex', "-1");
	modal.setAttribute('role', "dialog");
	modal.setAttribute('aria-labelledby', "modalSendInWorkLabel");
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
	h4.appendChild(document.createTextNode("Счет №" + elem.order_id + "_" + elem.num));
	header.appendChild(h4);
	//тело модалки
	var body = document.createElement('div');
	body.setAttribute('class', "modal-body");
	content.appendChild(body);
	
	var h3 = document.createElement('h4');
	h3.appendChild(document.createTextNode("Наименование клиента"));
	body.appendChild(h3);
	var label = document.createElement('label');
	label.setAttribute('style', "font-weight: 100;");
	label.appendChild(document.createTextNode(elem.client_name));
	body.appendChild(label);
	
	var h3 = document.createElement('h4');
	h3.appendChild(document.createTextNode("Наименование продукта"));
	body.appendChild(h3);
	var label = document.createElement('label');
	label.setAttribute('style', "font-weight: 100;");
	label.appendChild(document.createTextNode(elem.p_names));
	body.appendChild(label);
	
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
	//кнопка Создать
	var btn_print = document.createElement('button');
	btn_print.setAttribute('type', "button");
	btn_print.setAttribute('class', "btn btn-success");
	btn_print.appendChild(document.createTextNode("Отправить в работу"));
	footer.appendChild(btn_print);
	//клик по кнопке
	btn_print.onclick = function() {
		var id = elem.order_id;
		var str_id = elem.id;
		window.open('pg/proc/plan_job.php?id=' + str_id, '_blank');
		$.ajax({
			type: "GET",
			url: 'pg/_addjob.php',
			data: {
				id: id,
				id1: str_id
			}, 
			success: function (data) {//возвращаемый результат от сервера
				$('#modalSendInWork').modal('hide');
				$('#table_not_task').DataTable().ajax.reload();
			}
		});
	}
	
	//при скрывании модалки - удаляем её
	$('#modalSendInWork').on('hidden.bs.modal', function (e) {
		remove(document.getElementById("modalSendInWork"));
	});
	//открываем модалку
	$('#modalSendInWork').modal('show');
}

//удаление элементов ниже текущего в родительском блоке
//если элемент равен null, то все элементы в блоке
//если родитель равен null, то все элементы в документе
function deleteFooterDOM(parent, element) {
  if (parent == null)
    parent = document;
  var all = parent.getElementsByTagName("*");

  var index = -1;

  if (element == null)
    index = 0;

  var i = all.length - 1;
  while (all[i] != element && i >= 0) {
    remove(all[i]);
    i--;
  }
}

//запуск удаления элемента
function removeElement(id) {
  var elem = document.getElementById(id);
  if (elem)
    remove(elem);
}

//удаление элемента
function remove(elem) {
  return elem.parentNode ? elem.parentNode.removeChild(elem) : elem;
}

//поиск по таблице
function tableSearch(inputname, tablename) {
  var phrase = document.getElementById(inputname);
  var table = document.getElementById(tablename);
  var regPhrase = new RegExp(phrase.value, 'i');
  var flag = false;
  for (var i = 1; i < table.rows.length; i++) {
    flag = false;
    for (var j = table.rows[i].cells.length - 1; j >= 0; j--) {
      flag = regPhrase.test(table.rows[i].cells[j].innerHTML);
      if (flag) break;
    }
    if (flag) {
      table.rows[i].style.display = "";
    } else {
      table.rows[i].style.display = "none";
    }

  }
}

//сортируем таблицу
function sortTable(table, target) {
  //заголовок таблицы
  var theads = table.getElementsByTagName('thead');
  if (theads.length <= 0)
    return;
  var thead = theads[0];
  //тело таблицы
  var tbodys = table.getElementsByTagName('tbody');
  if (tbodys.length <= 0)
    return;
  var tbody = tbodys[0];
  //меняем значок в заголовке
  var is_ = target.getElementsByTagName('i');
  if (is_.length <= 0)
    return;
  var i_ = is_[0];
  if (!i_.hasAttribute('class'))
    return;
  //тип сортировки
  //-1 - уменьшение
  //0 - без сортировки
  //1 - увеличение
  var type_sort = 0;
  switch (i_.getAttribute('class')) {
    case "fa fa-fw fa-sort":
      i_.setAttribute('class', "fa fa-fw fa-sort-desc");
      type_sort = 1;
      break;
    case "fa fa-fw fa-sort-desc":
      i_.setAttribute('class', "fa fa-fw fa-sort-asc");
      type_sort = -1;
      break;
    case "fa fa-fw fa-sort-asc":
      i_.setAttribute('class', "fa fa-fw fa-sort");
      type_sort = 0;
      break;
    default:
      return;
  }

  //определяем номер столбца
  parent = target.parentNode;
  var childrens = parent.childNodes;
  var numcol_tmp = -1;
  var numcol = -1;
  var numcol_current_sort = -1;
  var numcol_sort = -1;
  var arrsort = new Array();
  for (var i = 0; i < childrens.length; i++) {
    if (childrens[i].tagName == "TH") {
      numcol_tmp++;
      if (childrens[i].hasAttribute('class') && childrens[i].getAttribute('class') == "sort") {
        numcol_sort++;
        if (!childrens[i].hasAttribute('data-sort') || childrens[i].getAttribute('data-sort') == "") {
          arrsort.push(0);
        } else
          arrsort.push(parseInt(childrens[i].getAttribute('data-sort')));
      }
    }
    if (childrens[i] == target && numcol_tmp >= 0 && numcol_sort >= 0) {
      numcol = numcol_tmp;
      numcol_current_sort = numcol_sort;
      childrens[i].setAttribute('data-sort', type_sort);
    }
  }
  if (numcol < 0 || numcol_sort < 0 || arrsort.length <= 0 || numcol_current_sort < 0)
    return;

  //проверяем на 0 массив...
  var isNullArray = true;
  for (var i = 0; i < arrsort.length; i++) {
    if (arrsort[i] != 0) {
      isNullArray = false;
      break;
    }
  }
  //если 0е параметры сортировки, то запоминаем начальные номера строк
  if (isNullArray) {
    var trs = tbody.getElementsByTagName('tr');
    if (trs.length <= 0)
      return;
    for (var i = 0; i < trs.length; i++) {
      trs[i].setAttribute('data-init-num', i);
    }
  }

  arrsort[numcol_current_sort] = type_sort;

  //собираем текущую таблицу
  var trs = tbody.getElementsByTagName('tr');
  if (trs.length <= 0)
    return;
  var arr_tmp = new Array();
  for (var i = 0; i < trs.length; i++) {
    var tds = trs[i].getElementsByTagName('td');
    if (tds.length <= numcol)
      return;
    var value = getConcatenedTextContent(tds[numcol]);
    var obj = {
      'value': value,
      'tr': trs[i].cloneNode(true)
    }
    arr_tmp.push(obj);
  }

  if (arr_tmp.length <= 0)
    return;

  //чистим таблицу
  deleteFooterDOM(tbody, null);

  //восстанавливаем начальный массив
  var input_arr = new Array();
  input_arr.push(arr_tmp[0]);
  for (var i = 1; i < arr_tmp.length; i++) {
    if (!arr_tmp[i].tr.hasAttribute('data-init-num'))
      return;
    var curent = arr_tmp[i].tr.getAttribute('data-init-num');
    var flag = false;
    for (var j = 0; j < input_arr.length; j++) {
      if (!input_arr[j].tr.hasAttribute('data-init-num'))
        return;
      var in_curent = input_arr[j].tr.getAttribute('data-init-num');

      if (_sort(curent, in_curent) < 0) {
        var index = j;
        input_arr.splice(index, 0, arr_tmp[i]);
        flag = true;
        break;
      }
    }
    if (!flag)
      input_arr.push(arr_tmp[i]);
  }
  arr_tmp = input_arr;

  //определяем заголовки
  var ths = thead.getElementsByTagName('th');
  var current_column = 0;
  //проходим по матрице
  for (var index = 0; index < arrsort.length; index++) {
    //ищем первый меняемый заголовок
    var fl = false;
    for (var i = current_column; i < ths.length; i++) {
      if (ths[i].getAttribute('class') == "sort") {
        fl = true;
        current_column = i + 1;
        break;
      }
    }
    if (!fl)
      continue;

    //определяем столбец
    if (arrsort[index] == 0)
      continue;

    //обновляем значение, по которым будем сортировать
    for (var i = 0; i < arr_tmp.length; i++) {
      var row = arr_tmp[i].tr;
      var row_td = row.getElementsByTagName('td');
      if (row_td.length < current_column)
        return;
      var val = getConcatenedTextContent(row_td[current_column - 1]);
      arr_tmp[i].value = val;
    }

    var SORT_ARRAY = new Array();
    SORT_ARRAY.push(arr_tmp[0]);
    //сортируем массив
    for (var i = 1; i < arr_tmp.length; i++) {
      var val1 = arr_tmp[i].value;
      var flag = false;
      for (var j = 0; j < SORT_ARRAY.length; j++) {
        var val2 = SORT_ARRAY[j].value;

        if ((_sort(val1, val2) * arrsort[index]) < 0) {
          var k = j;
          flag = true;
          SORT_ARRAY.splice(k, 0, arr_tmp[i]);
          break;
        }
      }
      if (!flag)
        SORT_ARRAY.push(arr_tmp[i]);
    }

    arr_tmp = SORT_ARRAY;
  }

  //пишем строки
  for (var i = 0; i < arr_tmp.length; i++) {
    tbody.appendChild(arr_tmp[i].tr);
  }
}

//определение большего значения
//если меньше 0, то a < b
//если больше 0, то a > b
//если равно 0, то a = b
function _sort(a, b) {
  var _a = (a + '').replace(/,/, '.');
  var _b = (b + '').replace(/,/, '.');
  //если входные данные числа
  if (isInt(_a) || isFloat(_a)) {
    if (isInt(_b) || isFloat(_b)) {
      if (parseFloat(_a) < parseFloat(_b)) return -1;
      if (parseFloat(_a) > parseFloat(_b)) return 1;
      return 0;
    }
  }
  //если строки
  return sort_insensitive(a, b);
}

//сравнивает 2 строки без учета регистра
function sort_insensitive(a, b) {
  var anew = a.toLowerCase();
  var bnew = b.toLowerCase();
  if (anew < bnew) return -1;
  if (anew > bnew) return 1;
  return 0;
}

//проверяет или число (целое)
function isInt(n) {
  return Number(n) == n && n % 1 === 0;
}

//проверяет или число (дробь)
function isFloat(n) {
  return Number(n) == n && n % 1 !== 0;
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
