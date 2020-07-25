<?
	include "../pages/db.php";
	include "../pages/pg/utility.php";
	
	if(!isset($_GET['dataLoad']) || !isset($_GET['parameters'])):
		die(null);
	else:
		$data  = json_decode($_GET['dataLoad']);
		$parameters  = json_decode($_GET['parameters']);
		if(empty($data) || empty($parameters) || !is_array($parameters) || count($parameters) <= 0)
			die(null);
		//определяем клиента
		$client_id = 0;
		$select = "SELECT client_id_db FROM bitrix24_template_calculation WHERE id=".$parameters[0]->id;
		$query = mysql_query($select) or die(null);
		if($row = mysql_fetch_array($query)) {
			$client_id = intval($row['client_id_db']);
		}
		//разбиваем расчеты по счетам
		$container = array();
		foreach($parameters as $parameter) {
			$container["order_".$parameter->order_id][] = $parameter;
		}
?>

<!doctype html>
<html lang="en-us">
    <head>
        <meta charset="utf-8">
        <title>Создание заявки на основании расчета</title>

        <!-- Favicon -->
        <link rel="shortcut icon" href="../favicon.png" type="image/png">

        <!-- Stylesheet -->
        <link rel="stylesheet" href="css/main.css">
		<script src="js/main.min.js"></script>
		<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
		<!-- DataTables JavaScript -->
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
		<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
		
        <link rel="stylesheet" href="css/transfer.css">
		<script src="/bitrix24/controller/controller_bitrix.js"></script>
		<script src="js/transfer.js"></script>
    </head>
    <body class="o-page o-page--center">
		<div id="body_content">
			<script>
				document.getElementById("body_content").setAttribute('data-attr-full', JSON.stringify(<? echo json_encode($data) ?>));
				document.getElementById("body_content").setAttribute('data-attr-order', JSON.stringify(<? echo json_encode($container) ?>));
				document.getElementById("body_content").setAttribute('data-attr-client', JSON.stringify(<? echo json_encode($client_id) ?>));
			</script>
			
			<div id="formData">
				<div class="row">
					<div class="col-md-2">
						<a class="c-btn c-btn--success c-btn--fullwidth" onclick="inWork()"><i class="fa fa-truck"> Отправить в работу</i></a>
					</div>
					<div class="col-md-2">
						<a class="c-btn c-btn--secondary c-btn--fullwidth" onclick="clickCancel()"><i class="fa fa-mail-reply"> Отмена</i></a>
					</div>
				</div>
				<div id="ListOrder"></div>
			</div>
			
		</div>
	</body>
</html>


<? endif; ?>
