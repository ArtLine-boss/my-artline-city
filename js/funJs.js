$.xhrPool = [];
$.xhrPool.abortAll = function() {
    $(this).each(function(idx, jqXHR) {
        jqXHR.abort();
    });
    $.xhrPool.length = 0
};
 
$.ajaxSetup({
    beforeSend: function(jqXHR) {
        $.xhrPool.push(jqXHR);
    },
    complete: function(jqXHR) {
        var index = $.xhrPool.indexOf(jqXHR);
        if (index > -1) {
            $.xhrPool.splice(index, 1);
        }
    }
});

$(window).on("beforeunload", function() { 
	$.xhrPool.abortAll();
	window.stop();
});

$(document).ready(function(){
	var css = document.createElement("link");
	css.setAttribute("rel", "stylesheet");
	css.setAttribute("href", "/dist/font-awesome/css/font-awesome.min.css");
	document.getElementsByTagName("head")[0].appendChild(css);
	
	var script = document.createElement("script");
	script.src = "/js/controller.js?version=3";
	script.type="text/javascript";
	document.getElementsByTagName("body")[0].appendChild(script);
	
	script.onload = function() {
		NewYear(document, 5000);
	}
	
	if(document.getElementById("headerSystem") && document.getElementById("headerSystem").hasAttribute('data-color-default') && document.getElementById("headerSystem").getAttribute('data-color-default') == "true") {
		//замена файла css
		var links = document.getElementsByTagName('link');
		for(var i = 0; i < links.length; i++) {
			if(links[i].hasAttribute('href')) {
				var str = links[i].getAttribute('href');
				if(str.indexOf("sb-admin-2.css") >= 0) {
					remove1(links[i]);
					i = 0;
				}
			}
		}
		if(document.getElementById("logoSystem")) {
			remove1(document.getElementById("logoSystem"));
		}
		if(document.getElementById("headerSystem")) {
			if(document.getElementById("headerSystem").hasAttribute('style')) {
				document.getElementById("headerSystem").removeAttribute('style')
			}
		}
		var css = document.createElement("link");
		css.setAttribute("rel", "stylesheet");
		css.setAttribute("href", "/dist/css/sb-admin-2-default.css");
		document.getElementsByTagName("head")[0].appendChild(css);
	}
	
	var div = document.createElement('div');
	div.setAttribute("style", "position: fixed; top: 0; left: 30%; background-color: red; z-index: 2000;");
	var p = document.createElement('p');
	p.appendChild(document.createTextNode("ЛОКАЛЬНАЯ ВЕРСИЯ"));
	div.appendChild(p);
	document.getElementsByTagName('body')[0].appendChild(div);
});

//удаление элемента
function remove1(elem) {
    return elem.parentNode ? elem.parentNode.removeChild(elem) : elem;
}

function fun1() {
    var rad=document.getElementsByName('r1');
    for (var i=0;i<rad.length; i++) {
         var input = rad[i];
		 if(rad[i].checked){
	
	var theElement = document.getElementById("elem");
if(input.value == 'f'){
	 document.getElementById('elem1' ).style.display = 'block';
	 document.getElementById('elem2' ).style.display = 'none';

	/*
theElement.innerHTML = "<form  class='form-signin' method='post' action='_addClient.php'><p>Пожалуйста, заполните поля:</p><div class='row'><div class='col-md-2'><div class='block1'><label >Наименование:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientName'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label >Email: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientEmail'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label >Город. телефон:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientPhoneCity'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Моб. телефон:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientPhoneMob'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Skype: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientSkype'></div></div></div>	<div class='row'><div class='col-md-2'><div class='block1'><label>Viber: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientViber'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Почтовый адрес:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientAddressPost'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Адрес доставки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientAddressDev'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Адрес доставки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientAddressDev'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Надбавка:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientNadb'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Лимит:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientLim'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Время рассрочки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientTime'></div></div></div>  	<div class='row'><div class='col-md-2'><div class='block1'><label>Размер предоплаты:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientSize'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><input type='hidden' value='f' name='clientType'><br/><input type='submit' value='Создать' /></div></div></div></form>";*/
} else if (input.value == 'u'){
		 document.getElementById('elem1' ).style.display = 'none';
	 document.getElementById('elem2' ).style.display = 'block';
/*	theElement.innerHTML = "<form  class='form-signin' method='post' action='_addClient.php'><p>Пожалуйста, заполните поля:</p><div class='row'><div class='col-md-2'><div class='block1'><label>Наименование:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientName'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label >Email: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientEmail'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label >Город. телефон:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientPhoneCity'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Моб. телефон:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientPhoneMob'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Почтовый адрес:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientAddressPost'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Адрес доставки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientAddressDev'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>УНП: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientUnp'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>р/с: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientAcct'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Банк: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientBank'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Код банка: </label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientCodeBank'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Надбавка:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientNadb'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Лимит:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientLim'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Время рассрочки:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientTime'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><label>Размер предоплаты:</label></div></div><div class='col-md-2'><div class='block1'><input type='text' name='clientSize'></div></div></div><div class='row'><div class='col-md-2'><div class='block1'><input type='hidden' value='u' name='clientType'> <input type='submit' value='Создать' /></form></div></div></div>"*/
	}
		 }
    }
}
function _deleteAcctProduct(id, id_Acct){
	var id_AcctProd = '_delAcctProd.php?id_orderProd='+id+'&id_Acct='+id_Acct;
	window.location.href = id_AcctProd;	
}
function _deleteEquipment(id, id_Acct){
	var id_Prod = 'pg/_delEquipment.php?id_Equipment='+id;
	window.location.href = id_Prod;	
}

function _deleteProduct(id, id_Acct){
	var id_Prod = 'pg/_delProd.php?id_Prod='+id;
	window.location.href = id_Prod;	
}

function _reviewAcctProduct(id,id_prod,id_ord,act){
	var id_AcctProd = '_reviewAcctProduct.php?id_orderProd='+id+'&id_prod='+id_prod+'&id_ord='+id_ord+'&activ='+act;
	window.open(id_AcctProd, 'Просмотр заказа', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');
}
function _openTempProduct(id)
{
  var id_acct = '_addOrProd.php?id_acct='+id;
 window.open(id_acct, 'Добавление нового продукта к заказу', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');
}
function _openTempProduct_ord(id)
{
  var id_acct = '_uske.php?id='+id;
 window.open(id_acct, 'Добавление нового продукта к заказу', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');
}

function _openstock(id)
{
	var id_acct = '_addstockattr.php?id_acct='+id;
	window.open(id_acct, 'Добавление', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');
}


function _reviewClient(id)
{
  var id_client = 'pg/_reviewClient.php?id_client='+id;
  window.open(id_client, 'Просмотр клиента', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');
}


function _reviewOper(id)
{
  var id_oper = 'pg/_reviewOperation.php?id_oper='+id;
  window.open(id_oper, 'Просмотр операции', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}

function _reviewProduct(id)
{
 location.href =  'pg/_reviewProduct.php?id_product='+id;
//  window.open(id_product, 'Просмотр продукта', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}

function _copyProduct(id)
{
 location.href =  'pg/_copyProduct.php?id_product='+id;
//  window.open(id_product, 'Просмотр продукта', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}


function _reviewUsers(id)
{
  var id_product = 'pg/_reviewUsers.php?id_users='+id;
  window.open(id_product, 'Просмотр пользователя', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}
function _reviewEquipment(id)
{
  var id_equipment = 'pg/_reviewEquipment.php?id_Equipment='+id;
  window.open(id_equipment, 'Просмотр продукта', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');
}
function _reviewMaterial(id)
{
  var id_material = 'pg/_reviewMaterials.php?id_material='+id;
  window.open(id_material, 'Просмотр материала', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}
function _revieStamp(id)
{
  var id_stamp = 'pg/_reviewStamps.php?id_stamp='+id;
  window.open(id_stamp, 'Просмотр', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}

function showOrHide(ch1, ch2) {
  ch1 = document.getElementById(ch1);
  ch2 = document.getElementById(ch2);
  if (ch1.checked) ch2.style.display = "block";
  else ch2.style.display = "none";
}

function showOrActiv(ch1, ch2) {
  ch1 = document.getElementById(ch1);
  ch2 = document.getElementById(ch2);
  if (ch1.checked) ch2.disabled = false;
  else ch2.disabled = true;
}
  
function showOrActiv2(ch1, ch2,ch3) {
  ch1 = document.getElementById(ch1);
  ch2 = document.getElementById(ch2);
  ch3 = document.getElementById(ch3);
  if (ch1.checked) {ch2.disabled = false; ch3.disabled = false;}
  else {ch2.disabled = true; ch3.disabled = true;}
}
  
 function _orderTask(id)
{
  var id_order = '_tacks.php?id_order='+id;
  window.open(id_order, '', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1024, resize=no, Height=768');
}

function _copyOper(id)
{
  var id_oper = 'pg/copy_oper.php?id_oper='+id;
  window.open(id_oper, 'Просмотр операции', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}

/************************
 отправка ajax
 ***********************/
function sendAjax(path = null, data = null, func = null, _async = true, _return = false) {
	if(!path)
		return;
	var _req = null;
	$.ajax({
		type: 'POST',
		url: '/www/core/ajax.php?' + path,
		data: data,
		cache: false,
		async: _async,
		dataType: 'json',
		success: function(response) {
			if(!response) {
				MessageBox('Пустой ответ от сервера', true);
				return;
			}
			if(response.ReloadURL) {
				location.href = response.ReloadURL;
			}
			if(response.Msg) {
				MessageBox(response.Msg, true);
				return;
			}
			_req = response.Data;
			if(isFunction(window[func])) {
				eval(func + "(" + JSON.stringify(response.Data) + ")");
			}
		},
		error: function(xhr) {
			MessageBox(xhr.statusText + xhr.responseText, true);
		},
	});

	if(!_async && _return)
		return _req;
}

//вывод сообщения
function MessageBox(txt, error) {
	//контейнер
	var container = document.createElement('div');
	container.setAttribute('class', "c-modal modal fade");
	container.setAttribute('id', "messageAlert");
	container.setAttribute('style', "z-index: 2000");
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
	p.setAttribute('style', "padding: 15px");
	div.appendChild(p);
	//кнопка
	var btn = document.createElement('button');
	btn.setAttribute('class', "btn btn-info");
	if(error == true)
		btn.setAttribute('class', "btn btn-danger");
	else if(error == false)
		btn.setAttribute('class', "btn btn-success");
	btn.setAttribute('data-dismiss', "modal");
	btn.setAttribute('style', "float: right");
	btn.appendChild(document.createTextNode("Закрыть"));
	div.appendChild(btn);
	//при скрывании модалки - удаляем её
	$('#messageAlert').on('hidden.bs.modal', function (e) {
		remove(document.getElementById("messageAlert"));
	});
	//открываем модалку
	$('#messageAlert').modal('show');
}

//проверяет или существует функция
function isFunction(functionToCheck) {
	var getType = {};
	return functionToCheck && getType.toString.call(functionToCheck) === '[object Function]';
}
