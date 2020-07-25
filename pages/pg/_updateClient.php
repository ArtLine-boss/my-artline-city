<?php
include '../firewall.php';
?>

<?php 
$clientType = $_GET['clientType'];
$clientName = $_GET['clientName'];
$clientEmail = $_GET['clientEmail'];
$clientType = $_GET['clientType'];
$clientPhoneMob = $_GET['clientPhoneMob'];
$clientPhoneCity = $_GET['clientPhoneCity'];
$clientAddressPost = $_GET['clientAddressPost'];
$clientAddressDev = $_GET['clientAddressDev'];
$clientId = $_GET['clientId'];
$clientNadb = $_GET['clientNadb'];
$clientLim = $_GET['clientLim'];
$clientTime = $_GET['clientTime'];
$clientSize = $_GET['clientSize'];
if($clientType == 'f'){
	$clientSkype = $_GET['clientSkype'];
	$clientViber = $_GET['clientViber'];
	$clientUnp = '';
	$clientAcct = '';
	$clientBank = '';
	$clientCodeBank = '';
		$str = '';
			$osnov =  '';
	$fio = '';
	$fio1 = '';
}
if($clientType == 'u'){
	$clientSkype = '';
	$clientViber = '';
	$clientUnp = $_GET['clientUnp'];
	$clientAcct = $_GET['clientAcct'];
	$clientBank = $_GET['clientBank'];
	$clientCodeBank = $_GET['clientCodeBank'];
	$num_doc = $_GET['num_doc'];
	$num_dov = $_GET['num_dov'];
		$str = $_GET['str'];
			$osnov =  $_GET['osnov'];
	$fio =  $_GET['fio'];
	$fio1 =  $_GET['fio1'];
}

include_once '../db.php';

$query = "UPDATE clients SET 
	CLIENT_NAME = '{$clientName}', 
	EMAIL = '{$clientEmail}',
	CLIENT_STATUS = '{$clientType}', 
	PHONE_MOB = '{$clientPhoneMob}',
	PHONE_CITY = '{$clientPhoneCity}',	
	CLIENT_SKYPE = '{$clientSkype}', 
	CLIENT_VIBER = '{$clientViber}', 
	ADDRESS_POST = '{$clientAddressPost}', 
	ADDRESS_DEV = '{$clientAddressDev}',
	UNP = '{$clientUnp}', 
	ACCT = '{$clientAcct}', 
	BANK = '{$clientBank}', 
	CODE_BANK = '{$clientCodeBank }',
	NADBAVKA = '{$clientNadb}', 
	LIMITs = '{$clientLim}', 
	TIME_RAS = '{$clientTime}', 
	SIZE_PRE = '{$clientSize }',
	num_doc = '{$num_doc}',
	Temp = '{$str}',
	fio_dir = '{$fio}',
	osn = '{$osnov}', 
	fio_dir1 = '{$fio1}',
	dover = '{$num_dov}'
	WHERE id='{$clientId}';";
mysql_query($query) or die("Query failed");
mysql_close($connection);
?>
<script >
	window.close();
	window.opener.location.reload();
</script>