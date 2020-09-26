<?
	// Меню
	
	//определяем полное имя пользователя
	$select = "SELECT USER_FIO,color_default FROM users WHERE USER_LOGIN='".$login."'";
	$query = mysql_query($select) or die(null);
	$username = "";
	$color_default = "";
	if($row = mysql_fetch_array($query)) {
		if(!empty($row['USER_FIO'])) {
			$username = $row['USER_FIO'];
		}
		if($row['color_default'] == 1) {
			$color_default = " data-color-default='true'";
			?>
			<script>
				var addNewYear = false;
			</script>
			<?php
		} else {
			?>
			<script>
				var addNewYear = true;
			</script>
			<?php
		}
	}
	
	$poruchnie = "";
	if($admin  == 3 || $admin  == 4) {
		$poruchnie = '<li><a href="#" onclick="ComboUser()"><i class="fa fa-user-circle fa-fw"></i> Совмещенный пользователь</a></li>
						<li class="divider"></li>';
	}
	
	$fon = "";
	if($admin == 4 || $admin  == 6 || $admin  == 7 || $admin == 8) {
		$fon = '<li><a href="#" onclick="SelectedFont()"><i class="fa fa-tint fa-fw"></i> Изменить раскраску</a></li>
						<li class="divider"></li>';
	}
	
	echo '<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
	<div class="navbar-header"><img id="logoSystem" class="logo" src="../../dist/css/logo-white.png" alt="Logo">
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	<span class="sr-only">Toggle navigation</span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	<span class="icon-bar"></span>
	</button>
	<a class="navbar-brand" href="../orders.php" style="color: #f7e946" id="headerSystem"'.$color_default.'>Система управления заказами</a>
	</div>
	<ul class="nav navbar-top-links navbar-right">
	<li class="dropdown">
	<a class="menu_a" class="dropdown-toggle" data-toggle="dropdown" href="#">
	<i class="fa fa-user fa-fw"></i> '.$username.' <i class="fa fa-caret-down"></i>
	</a>
	<ul class="dropdown-menu dropdown-user">'.$poruchnie.$fon.'
	<li><a href="../rpassword.php?current"><i class="fa fa-gear fa-fw"></i> Сбросить пароль</a></li>
	<li class="divider"></li>
	<li><a href="../exit.php"><i class="fa fa-sign-out fa-fw"></i> Выход</a></li>
	</ul>
	</li>
	</ul>
	';
	echo ' <div class="navbar-default sidebar" role="navigation">
	<div class="sidebar-nav navbar-collapse">
	<ul class="nav" id="side-menu">';
	
	
	if ($admin == '4' OR $admin == '2' OR $admin == '3'){
		echo '<li><a class="menu_a" href="../orders.php"><i class="fa fa-shopping-cart fa-fw"></i> Заказы</a></li>';
		if(in_array(2, $ACCESS_ROLES)) {
			echo '<li><a class="menu_a" href="/www/index.php?m=calculator"><i class="fa fa-calculator fa-fw"></i> Калькулятор</a></li>';
		}
		echo '<li><a class="menu_a" href="../clients.php"><i class="fa fa-align-justify fa-fw"></i> Клиенты</a></li>
		<li><a class="menu_a" href="list_contact_fresh.php"><i class="fa fa-link fa-fw"></i> Неперенесенные контакты</a></li>';
	}
	if($admin == '4' || $login == '026' || $login == 'buh2') {
		echo '<li><a class="menu_a" href="../artliner_report.php"><i class="fa fa-book fa-fw"></i> Отчет по артлайнерам</a></li>';
	}
	if ($admin == '4' OR $admin == '2' OR $login == '026' OR $login == '027' OR $login == '028' or $login == '033' or $login == '032'){
		echo '<li><a class="menu_a" href="../tn_list.php"><i class="fa fa-money fa-fw"></i>ТН</a></li>';
		echo ' <li><a class="menu_a" href="../oplati.php"><i class="fa fa-money fa-fw"></i> Оплаты</a></li>';
	}
	if($login == '026' OR  $admin == '027' or $login == '033' or $login == '032'){
		echo '<li>
		<a class="menu_a" href="../stock.php"><i class="fa fa-money fa-fw"></i> Склад</a>
	</li>';}
	if ($admin == '4' OR  $admin == '5' OR $admin == '6' OR  $admin == '7'){	echo ' <li><a class="menu_a" href="../task_p_d.php"><i class="fa fa-th-list fa-fw"></i>Подготовка файлов </a></li>';}
	if($admin == '4' OR  $admin == '9'){
		
		echo '<li>
		<a class="menu_a" href="../stock.php"><i class="fa fa-money fa-fw"></i> Склад</a>
		</li>';
	}	
	if($admin == '9'){
		echo '	<li><a class="menu_a" href="../klass.php">Классификатор</a></li>';
	}
	if($login == 'preprint'){
		
		echo '<li>
		<a class="menu_a" href="../stamps.php"><i class="fa fa-money fa-fw"></i> Штампы</a>
	</li>';
	}
	if($login == '15'){
	
	echo '<li>
	<a class="menu_a" href="diz.php"><i class="fa fa-money fa-fw"></i> Загруженность дизайнеров</a>
	</li>';
	}							
	if($login == '8' || $login == '29' || $login == '030'){
	
	echo '<li>
	<a class="menu_a" href="mang_bank.php"> Оформленные заказы</a>
	</li>';
	}	
	if($admin == '2'){
	
	echo '<li>
	<a class="menu_a" href="mang_bank.php"> Оформленные заказы</a>
	</li>';
	}	
	if ($admin == '4' OR $admin == '2' OR $admin == '3'){
	echo '<li><a class="menu_a" href="../task_p_list.php"><i class="fa fa-th-list fa-fw"></i>Загруженность производства</a></li>';
	}
	if ($admin == '4'){
	
	echo '
	<li><a class="menu_a" href="../task.php"><i class="fa fa-th-list fa-fw"></i>Производство</a></li>
	<li><a class="menu_a" href="#" ><i class="fa fa-file-text-o fa-fw"></i> Отчет <span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
	<li><a class="menu_a" href="mang_bank.php">Оформленные заказы</a></li>
	</ul>
	<ul class="nav nav-second-level">
	<li><a class="menu_a" href="diz.php">Загруженность дизайнеров</a></li>
	</ul>
	<ul class="nav nav-second-level">
	<li><a class="menu_a" href="post.php">Поставщики</a></li>
	</ul>
	<ul class="nav nav-second-level">
	<li><a class="menu_a" href="reportOrder.php">Отчет в XLS</a></li>
	</ul>
	</li> 
	<li><a class="menu_a" href="#" ><i class="fa fa-gear fa-fw"></i> Настройки <span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
	<li><a class="menu_a" href="../klass.php">Классификатор</a></li>
	<li><a class="menu_a" href="../product.php">Продукты</a></li>
	<li><a class="menu_a" href="../directoryCodeStat.php">Коды продуктов</a></li>
	<li><a class="menu_a" href="../equipment.php">Оборудование</a></li>
	<li><a class="menu_a" href="../operation.php">Операции</a></li>
	<li><a class="menu_a" href="../calendar.php">Календарь</a> </li>
	<li><a class="menu_a" href="../rpassword.php">Сбросить пароль пользователя</a></li>
	<li><a class="menu_a" href="#">Справочники<span class="fa arrow"></span></a>
	<ul class="nav nav-third-level">
	<li><a class="menu_a" href="../users.php">Пользователи</a></li>
	<li><a class="menu_a" href="../material.php">Материалы</a></li>
	<li><a class="menu_a" href="../stamps.php">Штампы</a></li>
	<li><a class="menu_a" href="../params.php">Параметры</a></li>
	<li><a class="menu_a" href="../design.php">Дизайн и Препресс</a></li>
	<li><a class="menu_a" href="../units.php">Единицы измерения</a></li>
	</ul>
	</li>
	</ul>
	</li>';
	}
	echo '</ul>
	</div>
	</div>
	</nav>';

// Сообщения
include_once($_SERVER['DOCUMENT_ROOT'] . '/www/popup/index.php');
	
	
	?>	