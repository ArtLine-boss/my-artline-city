<?
	include "../pages/db.php";
	include "../pages/pg/utility.php";
	
	if(!isset($_POST['PLACEMENT_OPTIONS']) && !isset($_GET['PLACEMENT_OPTIONS'])):
		die(null);
	else:
		if(!isset($_POST['PLACEMENT']) && !isset($_GET['PLACEMENT']))
			die(null);
		if($_POST['PLACEMENT'] != "CRM_CONTACT_LIST_MENU" && $_GET['PLACEMENT'] != "CRM_CONTACT_LIST_MENU" && $_POST['PLACEMENT'] != "CRM_COMPANY_LIST_MENU" && $_GET['PLACEMENT'] != "CRM_COMPANY_LIST_MENU" && $_POST['PLACEMENT'] != "CRM_CONTACT_DETAIL_TAB" && $_GET['PLACEMENT'] != "CRM_CONTACT_DETAIL_TAB" && $_POST['PLACEMENT'] != "CRM_COMPANY_DETAIL_TAB" && $_GET['PLACEMENT'] != "CRM_COMPANY_DETAIL_TAB")
			die(null);
		if(isset($_POST['PLACEMENT_OPTIONS']))
			$data = json_decode($_POST['PLACEMENT_OPTIONS']);
		else
			$data = json_decode($_GET['PLACEMENT_OPTIONS']);
		$type_menu = "";
		if(isset($_POST['PLACEMENT'])) {
			if($_POST['PLACEMENT'] == "CRM_CONTACT_LIST_MENU" || $_POST['PLACEMENT'] == "CRM_CONTACT_DETAIL_TAB")
				$type_menu = "contact";
			else if($_POST['PLACEMENT'] == "CRM_COMPANY_LIST_MENU" || $_POST['PLACEMENT'] == "CRM_COMPANY_DETAIL_TAB")
				$type_menu = "company";
		}
		else if(isset($_GET['PLACEMENT'])) {
			if($_GET['PLACEMENT'] == "CRM_CONTACT_LIST_MENU" || $_GET['PLACEMENT'] == "CRM_CONTACT_DETAIL_TAB")
				$type_menu = "contact";
			else if($_GET['PLACEMENT'] == "CRM_COMPANY_LIST_MENU" || $_GET['PLACEMENT'] == "CRM_COMPANY_DETAIL_TAB")
				$type_menu = "company";
		}
		if(empty($type_menu))
			die(null);
?>
<!doctype html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <title>Создать расчет</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="../favicon.png" type="image/png">

        <!-- Stylesheet -->
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/index.css">
		<!--<link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="/vendor/bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css">-->
		<script src="js/main.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
		<script src="/bitrix24/controller/controller_bitrix.js"></script>
		<script src="//api.bitrix24.com/api/v1/"></script>
		<script src="js/index.js"></script>
    </head>
    <body>
		<!-- Имя пользователя -->
		<h1 id="user_name"></h1>
		<script>
			document.getElementById("user_name").setAttribute('data-attr', "<? echo($data->ID) ?>");
			document.getElementById("user_name").setAttribute('data-type-menu', "<? echo($type_menu) ?>");
		</script>
		<!-- Наименование компании -->
		<? if($type_menu == "contact"): ?>
			<div class="row">
				<div class="col-md-6">
					<select class="c-select has-search" id="company_name" onchange="loadFunction()">
						<option value="0" selected>Физ. лицо</option>
					</select>
				</div>
			</div>
		<? endif; ?>
		<!-- Кнопка создания нового расчета -->
		<div class="row">
			<div class="col-md-3">
				<input type="checkbox" id="listForAllUser" onchange="loadFunction()"> Расчеты всех менеджеров
			</div>
			<div class="col-md-3"></div>
			<div class="col-md-2 col u-mb-medium">
				<a class="c-btn c-btn--success c-btn--fullwidth" onclick="clickNewCalc()"><i class="fa fa-calculator"> Новый расчет</i></a>
			</div>
			<div class="col-md-2 col u-mb-medium">
				<a class="c-btn c-btn--warning c-btn--fullwidth" onclick="addTemplateAcct()"><i class="fa fa-file-text"> Создать счет</i></a>
			</div>
			<div class="col-md-2 col u-mb-medium">
				<a class="c-btn c-btn--fancy c-btn--fullwidth" onclick="addTransferInWork()"><i class="fa fa-cart-plus"> Отправить в работу</i></a>
			</div>
		</div>
		<!-- Таблица расчетов -->
		<div id="container_table_calc"></div>
	</body>
</html>
<? endif; ?>