<?
	include "../../pages/db.php";
	include "../../pages/pg/utility.php";
	include "../modeler/constructor_fields.php";
	
	if(!isset($_GET['frms']))
		die(null);
	//номер формы
	$frms_id = $_GET['frms'];
	$count_page = 1;
	$title = "";
	$class_ico = "";
	$select = "SELECT frm.name,frm.count_page,frm.class_ico FROM api_forms frm WHERE frm.id=$frms_id";
	$query = mysql_query($select) or die(null);
	if($row = mysql_fetch_array($query)) {
		$count_page = intval($row['count_page']);
		$title = strval($row['name']);
		$class_ico = strval($row['class_ico']);
	}
	
	$select = "SELECT t3.name,t3.id_name,t3.number_page,t3.flag_checked,t3.value_default,t3.display_none,t4.name type_data
				FROM
					(SELECT *
					FROM
						(SELECT mm.id_fields FROM api_mm_forms_fields mm WHERE mm.id_forms=1) t1
					INNER JOIN
						api_fields t2
					ON t1.id_fields=t2.id) t3
				INNER JOIN
					api_directory_type_data t4
				ON t3.type_data=t4.id";
	$query = mysql_query($select) or die(null);
	$return = array();
	while($row = mysql_fetch_array($query)) {
		$return['tab_'.$row['number_page']][] = $row;
	}
	
	for($i = 1; $i <= $count_page; $i++) {
		if(empty($return['tab_'.$i]) || !is_array($return['tab_'.$i]))
			continue;
		for($j = 0; $j < count($return['tab_'.$i]); $j++) {
			$return['tab_'.$i][$j]['html_code'] = init_constructor_fields($return['tab_'.$i][$j]);
		}
	}
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <title>АртЛайнСити API</title>
		<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600" rel="stylesheet">
        <link rel="shortcut icon" href="/favicon.png" type="image/png">
        <link rel="stylesheet" href="../controller/css/main.min.css">	
        <link rel="stylesheet" href="../controller/css/style.css">	
		<script src="../controller/js/main.min.js"></script>
		<script src="../controller/jquery-ui/jquery-ui.js"></script>
		<script src="../controller/js/controller.js"></script>
    </head>
    <body class="o-page">
		<!-- прелоадер страницы -->
		<div class="preloader">
			<span class="preloader fa-lg"><i class="fa fa-spinner fa-spin preloader_element"></i></span>
		</div>
		<!-- сообщение об успехе/ошибке -->
		<div id="message_error" class="message_error" style="display:none;">
			<!--<i class="fa fa-times-circle me_ico_size"></i>
			<p class="error_window_popup">ТЕКСТ аыолвпрыфолпф</p>-->
		</div>
		<form id="formCalcBooks" name="formCalcBooks" role="form">
			<div class="c-modal__header">
				<h1 class="c-modal__title"><i class="<? echo $class_ico ?>"></i><? echo $title ?></h1>
				<h1 class="c-modal__title" id="current_page_title" data-current-page="1" data-max-page="<? echo $count_page ?>"></h1>
			</div>
			<div class="c-modal__body">
				<div class="c-progress c-progress no_border_radius">
					<div id="current_page_progress" class="c-progress__bar no_border_radius" style="width:0%;"></div>
				</div>
				<?
					for($i = 1; $i <= $count_page; $i++) {
						echo '<div id="tab_'.$i.'" name="tab" class="tab_pages">';
						if(!empty($return['tab_'.$i]) && is_array($return['tab_'.$i])) {
							for($j = 0; $j < count($return['tab_'.$i]); $j++) {
								echo $return['tab_'.$i][$j]['html_code'];
							}
						}
						echo '</div>';
					}
				?>
			</div>
			<footer class="c-modal__footer">
				<a id="btn_back" class="c-btn c-btn--secondary c-btn--fullwidth btn_back" onclick="btn_back()"><i class="fa fa-long-arrow-left"></i>Назад</a>
				<a id="btn_next" class="c-btn c-btn--info c-btn--fullwidth btn_next" onclick="btn_next()">Вперед<i class="fa fa-long-arrow-right"></i></a>
				<a id="btn_send" class="c-btn c-btn--success c-btn--fullwidth btn_send" style="display:none;" onclick="btn_send('formCalcBooks',1)"><i class="fa fa-check"></i>Отправить</a>
			</footer>
		</form>	
	</body>
</html>


