function fun1() {
    var rad=document.getElementsByName('r1');
    for (var i=0;i<rad.length; i++) {
         var input = rad[i];
		 if(rad[i].checked){
	
	var theElement = document.getElementById("elem");
if(input.value == 'f'){
theElement.innerHTML = "<form  class='form-signin' method='post' action='_addClient.php'>	<p>Пожалуйста, заполните поля:</p><label >Наименование:</label><input type='text' name='clientName'><br/>    	<label >Email: </label><input type='text' name='clientEmail'><br/>     	<label >Город. телефон:</label><input type='text' name='clientPhoneCity'><br/>    	<label>Моб. телефон:</label><input type='text' name='clientPhoneMob'><br/>    	<label>Skype: </label><input type='text' name='clientSkype'><br/>    	<label>Viber: </label><input type='text' name='clientViber'><br/> 	<label>Почтовый адрес:</label><input type='text' name='clientAddressPost'><br/>    	<label>Адрес доставки:</label><input type='text' name='clientAddressDev'><br/>   	<input type='hidden' value='f' name='clientType'><br/><input type='submit' value='Создать' /></form>";
} else if (input.value == 'u'){
	var array 
	theElement.innerHTML = "<form  class='form-signin' method='post' action='_addClient.php'> <p>Пожалуйста, заполните поля:</p> <label >Наименование:</label><input type='text' name='clientName'><br/>    <label >Email: </label><input type='text' name='clientEmail'><br/>   <label >Город. телефон:</label><input type='text' name='clientPhoneCity'><br/>   <label>Моб. телефон:</label><input type='text' name='clientPhoneMob'><br/>  <label>Почтовый адрес:</label><input type='text' name='clientAddressPost'><br/>  <label>Адрес доставки:</label><input type='text' name='clientAddressDev'><br/> <label>УНП: </label><input type='text' name='clientUnp'><br/>  <label>р/с: </label><input type='text' name='clientAcct'><br/> <label>Банк: </label><input type='text' name='clientBank'><br/>  <label>Код банка: </label><input type='text' name='clientCodeBank'><br/> <input type='hidden' value='u' name='clientType'> <br/>  <input type='submit' value='Создать' /></form>"}
		 }
    }
}
function _deleteAcctProduct(id, id_Acct){
	var id_AcctProd = '_delAcctProd.php?id_orderProd='+id+'&id_Acct='+id_Acct;
	window.location.href = id_AcctProd;	
}
function _deleteProduct(id, id_Acct){
	var id_Prod = 'pg/_delProd.php?id_Prod='+id;
	window.location.href = id_Prod;	
}
function _reviewAcctProduct(id,id_prod){
	var id_AcctProd = '_reviewAcctProduct.php?id_orderProd='+id+'&id_prod='+id_prod;
	window.open(id_AcctProd, 'Просмотр заказа', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}
function _openTempProduct(id)
{
  var id_acct = '_addOrProd.php?id_acct='+id;
 window.open(id_acct, 'Добавление нового продукта к заказу', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=1200, resize=no, Height=600');
}

function _reviewClient(id)
{
  var id_client = 'pg/_reviewClient.php?id_client='+id;
  window.open(id_client, 'Просмотр клиента', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}


function _reviewOper(id)
{
  var id_oper = 'pg/_reviewOperation.php?id_oper='+id;
  window.open(id_oper, 'Просмотр операции', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}

function _reviewProduct(id)
{
  var id_product = 'pg/_reviewProduct.php?id_product='+id;
  window.open(id_product, 'Просмотр продукта', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}
function _reviewUsers(id)
{
  var id_product = 'pg/_reviewUsers.php?id_users='+id;
  window.open(id_product, 'Просмотр пользователя', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}
function _reviewEquipment(id)
{
  var id_equipment = 'pg/_reviewEquipment.php?id_Equipment='+id;
  window.open(id_equipment, 'Просмотр продукта', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
}
function _reviewMaterial(id)
{
  var id_material = 'pg/_reviewMaterials.php?id_material='+id;
  window.open(id_material, 'Просмотр материала', 'Toolbar=0, Scrollbars=1, Resizable=0, Location=0, Width=640, resize=no, Height=480');
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
  
 