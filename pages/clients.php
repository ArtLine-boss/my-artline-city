<?php
	
	include 'firewall1.php';
	session_start();
	$login = $_SESSION['login'];
	$query = "select user_per from users where user_login = '".$login ."' LIMIT 1";
	$result = mysql_query($query) or die($query);
	while ($row = mysql_fetch_row($result)) { 
		$admin = $row[0];
	}
	
	
	if ($admin == "7" OR  $admin == "6" OR $admin == "7" ){
		header("Location: task_p_d.php"); 
	}
	if ($admin == "8" ){
		header("Location: task.php"); 
	}
	
	
	
?>
<!DOCTYPE html>
<html lang="ru">
	
	<head>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="">
		
		<title>Система управления заказами</title>
		<link rel="icon" href="../favicon.png" type="image/png">
		
		<!-- Bootstrap Core CSS -->
		<link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
		<link href="../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
		<!-- MetisMenu CSS -->
		<link href="../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
		
		<!-- Custom CSS -->
		<link href="../dist/css/sb-admin-2.css" rel="stylesheet">
		
		
		<!-- Custom Fonts -->
		<link href="../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		
		
		<style>
			.modal-lg
			{
			height:90%;
			width:80%;
			margin-left: 10%; 
			
			}
		</style>
		<script src="../vendor/jquery/jquery.js"></script>
		<script src="../js/jquery.maskedinput-1.2.2.js"></script>
	</head>
	
	<body>
		<div id="wrapper">
			<?php
				include_once("menu.php");
				
			?>
			
			<div id="page-wrapper">
				<div class="row">
					<div class="col-md-12">
						<h2 class="page-header">Клиенты</h2>
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							
							<div class="row"><div class="col-md-2">			
								<button type="button" class="btn btn-default form-control" onClick="form_cl()"><span class="glyphicon glyphicon-plus-sign"></span>Добавить</button>
							</div></div>
							<!-- /.panel-heading -->
							<div class="panel-body">
								
								<table width="100%" class="table table-striped table-bordered table-hover"
								id="dataTables-example">
									
									<thead>
										<tr>
											<th></th>
											<th>Наименование</th>
											<th>УНП</th>
											<th>Email</th>
											<th>Телефон 1</th>
											<th>Телефон 2</th>
											<th>Договор</th>
											<th>Р/Сч</th>
											<th>Код банка</th>

										</tr>
									</thead>
									
									
								</table>
								<!-- /.table-responsive -->
								
							</div>
							<!-- /.panel-body -->
						</div>
						<!-- /.panel -->
					</div>
					<!-- /.col-lg-12 -->
				</div>
				<!-- /.row -->
				<!-- /.row -->
			</div>
			
			
			
			<!-- МОДАЛЬНЫЕ ОКНА---->
			
			<div id="form_cl" class="modal fade " tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
				<div class="modal-dialog modal-lg">
					<div class="modal-content ">
						<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
							<h4 class="modal-title">Клиент</h4>
						</div>
						<div class="modal-body">
							<div class="panel-body">
								<div class="row">
									<div  class="col-md-10">
										<div class="row">
											<div class="form-group">
												
												<label class="radio-inline">
													<input type="radio" name="r1"  id = 'inp_f' value="f" onclick="funViewCl()" checked>Физ. лицо
												</label>
												<label class="radio-inline">
													<input type="radio" name="r1" id = 'inp_u' value="u" onclick="funViewCl()">Юр. лицо
												</label>
											</div>
										</div>
										<div class="row">
											<div class="form-group">
												<label>Название</label>
												<input type='text' id='clientName' name='clientName' class="form-control">
												
											</div>
										</div>
									</div>
									<div  class="col-md-2">				
										<svg xmlns="http://www.w3.org/2000/svg" width="140" height="140"><rect width="140" height="140" fill="#eee"/>
											<text text-anchor="middle" x="70" y="70" style="fill:#aaa;font-weight:bold;font-size:12px;font-family:Arial,Helvetica,sans-serif;dominant-baseline:central">140x140</text>
										</svg>
									</div>	
								</div>
								
								<div id="elem1"  style="display:none;">
									<!--	<form  class='form-signin' method='post' action='_addClient.php'> -->
									
									
									<div class='row'>
										<div class='col-md-2'> <label >Email: </label></div>
										<div class='col-md-7'> <input type='text' id='clientEmail' name='clientEmail' size = 60 class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label >Город. телефон:</label></div>
										<div class='col-md-7'> <input type='text' id='clientPhoneCity' size = 60 name='clientPhoneCity' placeholder="(99999) 999999" class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Моб. телефон:</label></div>
										<div class='col-md-7'> <input type='text' id='clientPhoneMob' size = 60 name='clientPhoneMob' onkeyup="formattingNumbers( this )" value="+375"   class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Skype: </label></div>
										<div class='col-md-7'> <input type='text' id='clientSkype' size = 60 name='clientSkype' class="form-control"></div>
									</div>	
									<div class='row'>
										<div class='col-md-2'> <label>Viber: </label></div>
										<div class='col-md-7'> <input type='text' id='clientViber' size = 60 name='clientViber' class="form-control"></div>
									</div>
									
									<div class='row'>
										<div class='col-md-2'> <label>Почтовый адрес OLD:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientAddressPost' name='clientAddressPost' disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>ПОИСК населенного пункта</label></div>
										
										
										<div class='col-md-7'> <!-- <input type='text' size = 60 id='postSearch' name='postSearch'   class="form-control">
										<select id='postSearchsel' name='postSearchsel'   class="form-control"></select> -->
										<input type="text" id='postSearch' name='postSearch'   class="form-control" list="postSearchsel" >
										<datalist id="postSearchsel">
										</datalist>
										
										</div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Почтовый адрес:</label></div>
										<div class='col-md-7'> 
											<div class="row">
												<div class="col-md-2"><label>Область</label></div>
												<div class="col-md-10">
													<select name="region_id" id="post_region_id" class="form-control">
														<option value=''>Выберите область</option>
														<option>Брестская обл.</option>
														<option>Витебская обл.</option>
														<option>Гомельская обл.</option>
														<option>Гродненская обл.</option>
														<option>Минская обл.</option>
														<option>Могилевская обл.</option>
													</select>  
												</div>
											</div>
											<div class="row">
												<div class="col-md-3"><label>Район</label></div>
												<div class="col-md-9">
													<input type="text" id='post_post_raion' class="form-control">
												</div>
											</div>
											<div class="row">
												<div class="col-md-3"><label>Населенный пункт</label></div>
												<div class="col-md-9">
													<input type="text" id='post_post_city' class="form-control">
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-2"><label>Улица</label></div>
												<div class="col-md-10">
													<div class="input-group ">
														<span class="input-group-addon">ул.</span>
														<input type="text" id='post_post_street' class="form-control">
													</div>
												</div>
												
											</div>
											
											<div class="row">
												
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">д.</span>
														<input type="text" id='post_post_home' class="form-control">
													</div>
												</div>
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">корпус</span>
														<input type="text" id='post_post_kor' class="form-control">
													</div>
												</div>
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">кв.</span>
														<input type="text" id='post_post_kv' class="form-control">
													</div>
												</div>
												
											</div>
											
											
											<div class="row">
												<div class="col-md-2"><label>Индекс</label></div>
												<div class="col-md-10">
													<input type="text" id='post_post_index' class="form-control"  placeholder="224000" onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')">
												</div>
											</div>
										</div>
									</div>
									
									<div class='row'>
										<div class='col-md-2'> <label>Адрес юридический OLD:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientAddressDev' name='clientAddressDev' class="form-control" disabled></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>ПОИСК населенного пункта</label></div>
										
										
										<div class='col-md-7'> 
											<input type="text" id='postSearch1' name='postSearch1'   class="form-control" list="postSearchsel1" >
											<datalist id="postSearchsel1">
											</datalist>
											
										</div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Адрес юридический:</label></div>
										<div class='col-md-7'> 
											<div class="row">
												<div class="col-md-2"><label>Область</label></div>
												<div class="col-md-10">
													<select name="region_id" id="dev_region_id" class="form-control">
														<option value=''>Выберите область</option>
														<option>Брестская обл.</option>
														<option>Витебская обл.</option>
														<option>Гомельская обл.</option>
														<option>Гродненская обл.</option>
														<option>Минская обл.</option>
														<option>Могилевская обл.</option>
													</select>  
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-3"><label>Район</label></div>
												<div class="col-md-9">
													<input type="text" id='dev_post_raion' class="form-control">
												</div>
											</div>
											<div class="row">
												<div class="col-md-3"><label>Населенный пункт</label></div>
												<div class="col-md-9">
													<input type="text" id='dev_post_city' class="form-control">
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-2"><label>Улица</label></div>
												<div class="col-md-10">
													<div class="input-group ">
														<span class="input-group-addon">ул.</span>
														<input type="text" id='dev_post_street' class="form-control">
													</div>
												</div>
												
												
											</div>
											
											<div class="row">
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">д.</span>
														<input type="text" id='dev_post_home' class="form-control">
													</div>
												</div>
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">корпус</span>
														<input type="text" id='dev_post_kor' class="form-control">
													</div>
												</div>
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">кв.</span>
														<input type="text" id='dev_post_kv' class="form-control">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-2"><label>Индекс</label></div>
												<div class="col-md-10">
													<input type="text" id='dev_post_index' class="form-control"  placeholder="224000" onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')">
												</div>
											</div>
										</div>
									</div>
									
									<div class='row'>
										<div class='col-md-2'> <label>Категория:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientNadb' name='clientNadb' value = '4' class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Лимит:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientLim' name='clientLim' value = '<? echo $lim ?>' class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Время рассрочки:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientTime' name='clientTime' class="form-control"></div>
									</div>  	
									<div class='row'>
										<div class='col-md-2'> <label>Размер предоплаты:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientSize' name='clientSize' value = '<? echo $pred ?>' class="form-control"></div>
									</div>
									
									
									<!--	</form> -->
									
									
								</div>
								<div id="elem2"  style="display:none;">
									<div class='row'>
										<div class='col-md-2'> <label>УНП: </label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientUnp1' name='clientUnp' placeholder="123476789"  class="form-control"></div>
									</div>
									
									<div class='row'>
										<div class='col-md-2'> <label >Email: </label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientEmail1' name='clientEmail'  disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label >Город. телефон:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientPhoneCity1' name='clientPhoneCity' placeholder="(99999) 999999" disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Моб. телефон:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientPhoneMob1' name='clientPhoneMob'  placeholder="(99999) 999999" class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Юридический адрес OLD:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientAddressPost1' name='clientAddressPost' disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>ПОИСК населенного пункта</label></div>
										
										
										<div class='col-md-7'> 
											<input type="text" id='postSearch2' name='postSearch2'   class="form-control" list="postSearchsel2" >
											<datalist id="postSearchsel2">
											</datalist>
											
										</div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Юридический адрес:</label></div>
										<div class='col-md-7'> 
											<div class="row">
												<div class="col-md-2"><label>Область</label></div>
												<div class="col-md-10">
													<select name="region_id" id="post1_region_id" class="form-control" disabled>
														<option value=''>Выберите область</option>
														<option>Брестская обл.</option>
														<option>Витебская обл.</option>
														<option>Гомельская обл.</option>
														<option>Гродненская обл.</option>
														<option>Минская обл.</option>
														<option>Могилевская обл.</option>
													</select>  
												</div>
											</div>
											<div class="row">
												<div class="col-md-3"><label>Район</label></div>
												<div class="col-md-9">
													<input type="text" id='post1_post_raion' class="form-control">
												</div>
											</div>
											<div class="row">
												<div class="col-md-3"><label>Населенный пункт</label></div>
												<div class="col-md-9">
													<input type="text" id='post1_post_city' class="form-control" disabled>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-2"><label>Улица</label></div>
												<div class="col-md-10">
													<div class="input-group ">
														<span class="input-group-addon">ул.</span>
														<input type="text" id='post1_post_street' class="form-control" disabled>
													</div>
												</div>
												
												
											</div>
											
											<div class="row">	
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">д.</span>
														<input type="text" id='post1_post_home' class="form-control" disabled>
													</div>
												</div>
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">корпус</span>
														<input type="text" id='post1_post_kor' class="form-control" disabled>
													</div>
												</div>
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">кв.</span>
														<input type="text" id='post1_post_kv' class="form-control" disabled>
													</div>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-2"><label>Индекс</label></div>
												<div class="col-md-10">
													<input type="text" id='post1_post_index' class="form-control"  placeholder="224000" onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')" disabled>
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Почтовый адрес OLD:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientAddressDev1' name='clientAddressDev' disabled  class="form-control"></div>
									</div>	
									<div class='row'>
										<div class='col-md-2'> <label>ПОИСК населенного пункта</label></div>
										
										
										<div class='col-md-7'> 
											<input type="text" id='postSearch3' name='postSearch3'   class="form-control" list="postSearchsel3" >
											<datalist id="postSearchsel3">
											</datalist>
											
										</div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Почтовый адрес:</label></div>
										<div class='col-md-7'> 
											<div class="row">
												<div class="col-md-2"><label>Область</label></div>
												<div class="col-md-10">
													<select name="region_id" id="dev1_region_id" class="form-control" disabled>
														<option value=''>Выберите область</option>
														<option>Брестская обл.</option>
														<option>Витебская обл.</option>
														<option>Гомельская обл.</option>
														<option>Гродненская обл.</option>
														<option>Минская обл.</option>
														<option>Могилевская обл.</option>
													</select>  
												</div>
											</div>
											<div class="row">
												<div class="col-md-3"><label>Район</label></div>
												<div class="col-md-9">
													<input type="text" id='dev1_post_raion' class="form-control">
												</div>
											</div>
											<div class="row">
												<div class="col-md-3"><label>Населенный пункт</label></div>
												<div class="col-md-9">
													<input type="text" id='dev1_post_city' class="form-control" disabled>
												</div>
											</div>
											
											<div class="row">
												<div class="col-md-2"><label>Улица</label></div>
												<div class="col-md-10">
													<div class="input-group ">
														<span class="input-group-addon">ул.</span>
														<input type="text" id='dev1_post_street' class="form-control" disabled>
													</div>
												</div>
											</div>
											
											
											<div class="row">
												
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">д.</span>
														<input type="text" id='dev1_post_home' class="form-control" disabled>
													</div>
												</div>
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">корпус</span>
														<input type="text" id='dev1_post_kor' class="form-control" disabled>
													</div>
												</div>
												<div class="col-md-4">
													<div class="input-group ">
														<span class="input-group-addon">кв.</span>
														<input type="text" id='dev1_post_kv' class="form-control" disabled>
													</div>
												</div>
												
											</div>
											
											<div class="row">
												<div class="col-md-2"><label>Индекс</label></div>
												<div class="col-md-10">
													<input type="text" id='dev1_post_index' class="form-control"  placeholder="224000" onKeyUp="this.value = this.value.replace (/[^\d,]/g, '')" disabled>
												</div>
											</div>
										</div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>ФИО директора(р.п.):</label></div>
										<div class='col-md-7'> <input type='text' id='fio' name='fio' size = 60 placeholder="Иванова Ивана Ивановича" disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>ФИО директора:</label></div>
										<div class='col-md-7'> <input type='text' id='fioo1' size = 60 name='fioo1' placeholder="Иванов Иван Иванович"  disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Основании:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='osnov' name='osnov' disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Договор №:</label></div>
										<div class='col-md-3'> <input type='text'  size = 12 id='num_doc' disabled  class=" form-control"></div>
										<div class='col-md-1'> <label>от:</label></div>
										<div class='col-md-3'> <input type='date'  id='num_doc_date' disabled  class="form-control"></div>
									</div>
                                    <div class='row'>
                                        <div class='col-md-2'> <label>Договор № (Мечта клиента):</label></div>
                                        <div class='col-md-3'> <input type='text'  size = 12 id='num_doc_m' disabled  class=" form-control"></div>
                                        <div class='col-md-1'> <label>от:</label></div>
                                        <div class='col-md-3'> <input type='date'  id='num_doc_date_m' disabled  class="form-control"></div>
                                    </div>
									<div class='row'>
										<div class='col-md-2'> <label>Довереность №:</label></div>
										<div class='col-md-3'> <input type='text'  size = 12 id='num_dov' disabled  class="form-control"></div>
										<div class='col-md-1'> <label>от:</label></div>
										<div class='col-md-3'> <input type='date'  id='num_dov_date' disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>р/с: </label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientAcct1' name='clientAcct'  disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Банк: </label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientBank1' name='clientBank'  disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Код банка: </label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientCodeBank1' name='clientCodeBank' disabled  class="form-control"></div>
									</div>
									
									<div class='row'>
										<div class='col-md-2'> <label>Категория:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientNadb1' name='clientNadb' value = '4' disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Лимит:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientLim1' name='clientLim' value = '<? echo $lim ?>' disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Время рассрочки:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientTime1' name='clientTime' disabled  class="form-control"></div>
									</div>
									<div class='row'>
										<div class='col-md-2'> <label>Размер предоплаты:</label></div>
										<div class='col-md-7'> <input type='text' size = 60 id='clientSize1' name='clientSize' value = '<? echo $pred ?>' disabled  class="form-control"></div>
									</div>
									
									
									
									
									
									<div class='row'>
										<hr>
										<h3>	Контакты и адреса</h3>
									</div>
									<div class='row'>
										<div class='col-md-2'> <input type="button" class="btn btn-default form-control" value="Добавить" style="margin: 10px" onclick="addRow()"   ></div>
										<div class='col-md-2'> <input type="button" class="btn btn-default form-control" value="Удалить" style="margin: 10px" onclick="del_row()"  ></div>
									</div>
									<table width='100%' name = 'clientType' id = "dynamic" class="table table-striped table-bordered table-hover " id="contact_cl" >
										<thead>
											<tr>
												<th scope="col">Del <i class='glyphicon glyphicon-trash'></i> </th>
												<th scope="col">ФИО</th>
												<th scope="col">Email</th>
												<th scope="col">Город. телефон</th>
												<th scope="col">Моб. телефон</th>
												<th scope="col">Skype</th>
												<th scope="col">Viber</th>
											</tr>
										</thead>
										<tbody>
											<tr>
											</tr>
										</tbody>
									</table>		
									
								</div>
								
								<input name='kol' id = 'kol' type='hidden' value = '0'/> 
								<input type='hidden' id = 'id_edit' value = ''>
								<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-default">Отмена</button>
									<a onclick="save_prod()"><button type="button" class="btn btn-primary">Сохранить</button></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			
			
			<!--Модальное окно на добавление контактного лица
				<div id="add_edit_cl_cont" class="modal fade " style="display: none;">
				<div class="modal-dialog ">
				<div class="modal-content ">
				<div class="modal-header"><button class="close" type="button" data-dismiss="modal">×</button>
				<h4 class="modal-title">Выбор клиента</h4>
				</div>
				<div class="modal-body">
				<div class='row'>
				<div class='col-md-3'> <label>ФИО</label></div>
				<div class='col-md-7'><input name='fio_cont' id = 'fio_cont' type='text' value='' class="form-control"/></div>
				</div>
				<div class='row'>
				<div class='col-md-3'> <label>Email</label></div>
				<div class='col-md-7'><input name='email_cont' id = 'email_cont' type='text' class="form-control" /></div>
				</div>
				<div class='row'>
				<div class='col-md-3'> <label>Город. телефон</label></div>
				<div class='col-md-7'><input name='city_cont' id = 'city_cont' type='text' class="form-control"/></div>
				</div>
				<div class='row'>
				<div class='col-md-3'> <label>Моб. телефон</label></div>
				<div class='col-md-7'><input name='mob_cont' id = 'mob_cont' type='text' class="form-control"/></div>
				</div>
				<div class='row'>
				<div class='col-md-3'> <label>Skype</label></div>
				<div class='col-md-7'><input name='Skype_cont' id = 'Skype_cont' type='text' class="form-control" /></div>
				</div>
				<div class='row'>
				<div class='col-md-3'> <label>Viber</label></div>
				<div class='col-md-7'><input name='Viber_cont' id = 'Viber_cont' type='text' class="form-control" /></div>
				</div>
				</div>
				<div class="modal-footer">
				<input name='kol' id = 'kol' type='hidden' value = '0'/> 
				<button type="button" onClick='hide_modal()' class="btn btn-default">Отмена</button>
				<button type="button" onClick='save_cont()'class="btn btn-primary">Сохранить</button>
				</div>
				</div>
				</div>
			</div>-->
			
			
			
		</div>
		
		
		<!-- /#wrapper -->
		
		<!-- jQuery -->
		<script src="../vendor/jquery/jquery.min.js"></script>
		<script src="../js/funJs.js"></script>
		<!-- Bootstrap Core JavaScript -->
		<script src="../vendor/bootstrap/js/bootstrap.min.js"></script>
		
		<!-- Metis Menu Plugin JavaScript -->
		<script src="../vendor/metisMenu/metisMenu.min.js"></script>
		
		<!-- Morris Charts JavaScript -->
		
		<!-- Custom Theme JavaScript -->
		<script src="../dist/js/sb-admin-2.js"></script>
		
		<!-- DataTables JavaScript -->
		<script src="../vendor/datatables/js/jquery.dataTables.min.js"></script>
		<script src="../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
		<script src="../vendor/datatables-responsive/dataTables.responsive.js"></script>
		<!-- Page-Level Demo Scripts - Tables - Use for reference -->
		<script>
			function formattingNumbers(b) {
				var a = "+375294567890",
				c = b.value.match(/\d/g);
				if(!c) return b.value = "+";
				a = a.replace(/\d/g, function () {
					return c.shift() || "#"
				});
				b.value = a.replace(/#.*/, "")
			};
			
			var default_options = {
				
				
				"iDisplayLength": 27,
				"bStateSave": true,
				"responsive": true,
				"paging:": true,
				"ajax": {
					"url": 'ajax_php_sql.php?flag=209',
					"type": "GET",
					
					"dataSrc": ""
				},
				"columns": [
				{"data": "q"},
				{"data": "name"},
				{"data": "unp"},
				{"data": "mail"},
				{"data": "phone1"},
				{"data": "phone2"},
				{"data": "dog"},
				{"data": "rs"},
				{"data": "bank"},
				],
			};
			var dTable = $('#dataTables-example').dataTable(default_options);
			
			
			
			$('#contact_cl').dataTable();
			
			function form_cl(){
				
				
				$('#clientName').val('');
				$('#clientEmail').val('');
				$('#clientPhoneCity').val('');
				$('#clientPhoneMob').val('+375');
				$('#clientSkype').val('');
				$('#clientViber').val('');
				$('#clientAddressPost').val('');
				$('#post_region_id').val('');
				$('#post_post_raion').val('');
				$('#post_post_raion').val('');
				$('#post_post_city').val('');
				$('#post_post_street').val('');	
				$('#post_post_home').val('');
				$('#post_post_kor').val('');
				$('#post_post_kv').val('');
				$('#post_post_index').val('');
				$('#clientAddressDev').val('');
				$('#dev_region_id').val('');
				$('#dev_post_city').val('');
				$('#dev_post_home').val('');
				$('#dev_post_raion').val('');
				$('#dev1_post_raion').val('');
				$('#post1_post_raion').val('');
				$('#dev_post_street').val('');
				$('#dev_post_kor').val('');
				$('#dev_post_kv').val('');
				$('#dev_post_index').val('');
				$('#clientNadb').val('');
				$('#clientLim').val('');
				$('#clientTime').val('');
				$('#clientSize').val('');
				$('#clientUnp1').val('');
				$('#clientEmail1').val('');
				$('#clientPhoneCity1').val('');
				$('#clientPhoneMob1').val('');
				$('#clientAddressPost1').val('');
				$('#post1_region_id').val('');
				$('#post1_post_city').val('');
				$('#post1_post_street').val('');
				$('#post1_post_kor').val('');
				$('#postSearch').val('');
				$('#postSearch1').val('');
				$('#postSearch2').val('');
				$('#postSearch3').val('');
				$('#post1_post_kv').val('');
				$('#post1_post_home').val('');
				$('#post1_post_index').val('');
				$('#clientAddressDev1').val('');
				$('#dev1_region_id').val('');
				$('#dev1_post_city').val('');
				$('#dev1_post_street').val('');
				$('#dev1_post_kor').val('');
				$('#dev1_post_home').val('');
				$('#dev1_post_kv').val('');
				$('#dev1_post_index').val('');
				$('#fio').val('');
				$('#fioo1').val('');
				$('#osnov').val('');
				$('#num_doc').val('');
				$('#num_doc_date').val('');
                $('#num_doc_m').val('');
                $('#num_doc_date_m').val('');
				$('#num_dov').val('');
				$('#num_dov_date').val('');
				$('#clientAcct1').val('');
				$('#clientBank1').val('');
				$('#clientCodeBank1').val('');
				$('#clientNadb1').val('');
				$('#clientLim1').val('');
				$('#clientTime1').val('');
				$('#clientSize1').val('');
				$('#id_edit').val('');
				
				
				if ($('#kol').val() > '0'){
					
					$("input[name='chDel']").prop('checked', true);
					del_row();
				}
				$('#kol').val('0')
				
				
				$('#form_cl').modal('show');
				
			}
			
			function save_prod(){
				/*if(document.getElementById("inp_u").checked && document.getElementById("clientAddressPost1").value == "") {
					alert('Не заполнено поле Юридический адрес OLD');
					return;
				}*/
				if(document.getElementById("inp_u").checked && ((document.getElementById("post1_post_city").value == "" || document.getElementById("dev1_post_city").value == "") && document.getElementById("clientAddressPost1").value == "")) {
					alert("Введите название населенного пункта в юридическом или почтовом адресе");
					return;
				}
				
				var rad=document.getElementsByName('r1');
				for (var i=0;i<rad.length; i++) {
					var input = rad[i];
					if(rad[i].checked){
						
						var theElement = document.getElementById("elem");
						if(input.value == 'f'){
							$.get( 'ajax_php_sql.php', {flag : '229', phone : document.getElementById('clientPhoneMob').value}, function (data) {
							 flags = data.replace(/\s+/g, '');
						      if ( document.getElementById('id_edit').value == '' &&  flags == '1'){
									alert('Такой клиент есть!')
									return;
								} 
								else {
									$.ajax({
										type: "GET",
										url: 'ajax_php_sql.php',
										data: {
											flag : 211, 	
											id : document.getElementById('id_edit').value,
											clientType : 'f',
											clientName : document.getElementById('clientName').value,
											clientEmail : document.getElementById('clientEmail').value,
											clientPhoneMob : document.getElementById('clientPhoneMob').value,
											clientPhoneCity : document.getElementById('clientPhoneCity').value,
											clientAddressPost : document.getElementById('clientAddressPost').value,
											clientAddressDev : document.getElementById('clientAddressDev').value,
											clientNadb : document.getElementById('clientNadb').value,
											clientLim : document.getElementById('clientLim').value,
											clientTime : document.getElementById('clientTime').value,
											clientSize : document.getElementById('clientSize').value,
											clientSkype : document.getElementById('clientSkype').value,
											clientViber : document.getElementById('clientViber').value,
											post_region_id  : document.getElementById('post_region_id').value,	
											post_post_raion  : document.getElementById('post_post_raion').value,  
											post_post_city  : document.getElementById('post_post_city').value,
											post_post_street  : document.getElementById('post_post_street').value,
											post_post_kor  : document.getElementById('post_post_kor').value,
											post_post_kv  : document.getElementById('post_post_kv').value,
											post_post_home  : document.getElementById('post_post_home').value,
											post_post_index  : document.getElementById('post_post_index').value,
											dev_region_id  : document.getElementById('dev_region_id').value,
											dev_post_city  : document.getElementById('dev_post_city').value,
											dev_post_street  : document.getElementById('dev_post_street').value,
											dev_post_kor  : document.getElementById('dev_post_kor').value,
											dev_post_home  : document.getElementById('dev_post_home').value,
											dev_post_kv  : document.getElementById('dev_post_kv').value,
											dev_post_raion  : document.getElementById('dev_post_raion').value,
											dev_post_index  : document.getElementById('dev_post_index').value
											},  success:function (data) {//возвращаемый результат от сервера
											dTable.fnClearTable();
											dTable.DataTable().ajax.reload();
											$('#form_cl').modal('hide');
											
										}
									}); 
								}
							});
						} 
						if (input.value == 'u'){
							
							if(document.getElementById('clientUnp1').value == '' || document.getElementById('clientUnp1').value == '0'){
								
								alert('Введите УНП!')
								return;
							}
							var kol = 1;
							var current;
							var str = "";
							var tbl = document.getElementById ('dynamic');                   // таблица, с которой работаем
							var rws = tbl.rows;                                            // коллекция существующих строк таблицы
							var lst = rws [rws.length - 1]; 
							var cls = lst.cells.length; 
							
							/*for (var i=2; i<rws.length; i++) 							//цикл по всем строкам
							{*/	
							
							for (var t = kol ; t <= document.getElementById('kol').value; t ++ ){
								
								
								current = document.getElementById('fio' + kol).value;
								str = str + current;
								str = str.concat("$");	
								
								current = document.getElementById('email' + kol).value;
								str = str + current;
								str = str.concat("$");	
								
								current = document.getElementById('city' + kol).value;
								str = str + current;
								str = str.concat("$");	
								
							current = document.getElementById('mob' + kol).value;
							str = str + current;
							str = str.concat("$");	
							
							current = document.getElementById('Skype' + kol).value;
							str = str + current;
							str = str.concat("$");	
							
							current = document.getElementById('Viber' + kol).value;
							str = str + current;
							str = str.concat("!");
							
							kol++;
							
							}
							
							dateObj = new Date(document.getElementById('num_doc_date').value)
							dateObj1 = 	String(('0' + dateObj.getDate()).slice(-2)) + "." + String( ('0' + (dateObj.getMonth() + 1)).slice(-2)) + "." + String(dateObj.getFullYear()) 
							str = str.substring(0, str.length-1);
							if (document.getElementById('num_doc').value != ''){
								num_doc = document.getElementById('num_doc').value + " от " +  dateObj1 ;
							}
							else num_doc =  '';

                            dateObj_m = new Date(document.getElementById('num_doc_date_m').value)
                            dateObj1_m = 	String(('0' + dateObj_m.getDate()).slice(-2)) + "." + String( ('0' + (dateObj_m.getMonth() + 1)).slice(-2)) + "." + String(dateObj_m.getFullYear())
                            if (document.getElementById('num_doc_m').value != ''){
                                num_doc_m = document.getElementById('num_doc_m').value + " от " +  dateObj1_m ;
                            }
                            else num_doc_m =  '';
							
							dateObj2 = new Date(document.getElementById('num_dov_date').value)
							dateObj12 = 	String(('0' + dateObj2.getDate()).slice(-2)) + "." + String( ('0' + (dateObj2.getMonth() + 1)).slice(-2)) + "." + String(dateObj2.getFullYear()) 
							str = str.substring(0, str.length-1);
							if (document.getElementById('num_dov').value != ''){
								num_dov = document.getElementById('num_dov').value + " от " +  dateObj12 ;
							}
							else num_dov =  '';
							
							$.ajax({
								type: "GET",
								url: 'ajax_php_sql.php',
								data: {
									flag : 211, 
									id : document.getElementById('id_edit').value,
									clientType : 'u',
									clientName : document.getElementById('clientName').value,
									clientEmail : document.getElementById('clientEmail1').value,
									clientPhoneMob : document.getElementById('clientPhoneMob1').value,
									clientPhoneCity : document.getElementById('clientPhoneCity1').value,
									clientAddressPost : document.getElementById('clientAddressPost1').value,
									clientAddressDev : document.getElementById('clientAddressDev1').value,
									clientNadb : document.getElementById('clientNadb1').value,
									clientLim : document.getElementById('clientLim1').value,
									clientTime : document.getElementById('clientTime1').value,
									clientSize : document.getElementById('clientSize1').value,
									clientUnp : document.getElementById('clientUnp1').value,
									clientAcct : document.getElementById('clientAcct1').value,
									clientBank : document.getElementById('clientBank1').value,
									clientCodeBank : document.getElementById('clientCodeBank1').value,
									str : str,
									num_doc : num_doc,
									num_doc_m : num_doc_m,
									osnov : document.getElementById('osnov').value,
									fio : document.getElementById('fio').value,
									fio1 : document.getElementById('fioo1').value,
									num_dov : num_dov,
									post_region_id  : document.getElementById('post1_region_id').value,
									post_post_city  : document.getElementById('post1_post_city').value,
									post_post_street  : document.getElementById('post1_post_street').value,
									post_post_kor  : document.getElementById('post1_post_kor').value,
									post_post_home  : document.getElementById('post1_post_home').value,
									post_post_kv  : document.getElementById('post1_post_kv').value,
									post_post_index  : document.getElementById('post1_post_index').value,
									dev_region_id  : document.getElementById('dev1_region_id').value,
									dev_post_city  : document.getElementById('dev1_post_city').value,
									dev_post_home : document.getElementById('dev1_post_home').value,
									dev_post_street  : document.getElementById('dev1_post_street').value,
									dev_post_kor  : document.getElementById('dev1_post_kor').value,
									dev_post_kv  : document.getElementById('dev1_post_kv').value,
									dev_post_index  : document.getElementById('dev1_post_index').value,
									post_post_raion  : document.getElementById('post1_post_raion').value, 
									dev_post_raion  : document.getElementById('dev1_post_raion').value
									},  success:function (data) {//возвращаемый результат от сервера
									dTable.fnClearTable();
									dTable.DataTable().ajax.reload();
									$('#form_cl').modal('hide');
									
								}
							});
							
						}
					}
					
				}
			}
			
			function save_cont(){
				hide_modal();
			}
			
			function hide_modal(){
				$('#add_edit_cl_cont').modal('hide');	
				
			}
			
			$('#add_edit_cl_cont').on('hidden.bs.modal', function () {
				$('#form_cl').focus();
			})
			
			jQuery(function($) {
				
				$.mask.definitions['~']='[+-]';
				
				$('#clientPhoneCity').mask('(99999) 999999');
				$('#clientPhoneMob').mask('(9999) 9999999');
				$('#clientPhoneCity1').mask('(99999) 999999');
				$('#clientPhoneMob1').mask('(9999) 9999999');
				$('#clientUnp1').mask('999999999');
				// $('#clientAcct1').mask('9999999999999');
				
				// $('#clientCodeBank1').mask('999');
			});</script> 
			<script>
				$('#clientUnp1').focusout(function(){
					if(document.getElementById('clientUnp1').value == '' || document.getElementById('clientUnp1').value == '0'){ return; }
					$.get( 'ajax_php_sql.php', {unp: document.getElementById ('clientUnp1').value, flag: '210'}, function (data) {
						if(data == "1"){
							alert("Внимание контрогент с таким УНП уже существует!")
							
							document.getElementById('clientEmail1').disabled = true;	
							document.getElementById('clientPhoneCity1').disabled = true;	
							document.getElementById('clientPhoneMob1').disabled = true;	
							document.getElementById('clientAddressPost1').disabled = true;	
							document.getElementById('clientAddressDev1').disabled = true;	
							document.getElementById('fio').disabled = true;	
							document.getElementById('fioo1').disabled = true;	
							document.getElementById('osnov').disabled = true;	
							document.getElementById('num_doc').disabled = true;	
							document.getElementById('num_doc_date').disabled = true;
                            document.getElementById('num_doc_m').disabled = true;
                            document.getElementById('num_doc_date_m').disabled = true;
							document.getElementById('num_dov').disabled = true;	
							document.getElementById('num_dov_date').disabled = true;	
							document.getElementById('clientAcct1').disabled = true;	
							document.getElementById('clientBank1').disabled = true;	
							document.getElementById('clientCodeBank1').disabled = true;	
							document.getElementById('clientNadb1').disabled = true;	
							document.getElementById('clientLim1').disabled = true;	
							document.getElementById('clientTime1').disabled = true;	
							document.getElementById('clientSize1').disabled = true;	
							document.getElementById('post1_region_id').disabled = true;	
							document.getElementById('post1_post_city').disabled = true;	
							document.getElementById('post1_post_street').disabled = true;		
							document.getElementById('post1_post_home').disabled = true;	
							document.getElementById('post1_post_kor').disabled = true;	
							document.getElementById('post1_post_kv').disabled = true;	
							document.getElementById('post1_post_index').disabled = true;	
							document.getElementById('dev1_region_id').disabled = true;	
							document.getElementById('dev1_post_city').disabled = true;	
							document.getElementById('dev1_post_street').disabled = true;	
							document.getElementById('dev1_post_home').disabled = true;	
							document.getElementById('dev1_post_kor').disabled = true;	
							document.getElementById('dev1_post_kv').disabled = true;	
							document.getElementById('dev1_post_index').disabled = true;	
							document.getElementById('clientAddressPost1').disabled = true;	
							document.getElementById('clientAddressDev1').disabled = true;	
							}else {
							
							document.getElementById('clientEmail1').disabled = false;	
							document.getElementById('clientPhoneCity1').disabled = false;	
							document.getElementById('clientPhoneMob1').disabled = false;	
							document.getElementById('clientAddressPost1').disabled = true;	
							document.getElementById('clientAddressDev1').disabled = true;	
							document.getElementById('fio').disabled = false;	
							document.getElementById('fioo1').disabled = false;	
							document.getElementById('osnov').disabled = false;	
							document.getElementById('num_doc').disabled = false;
							document.getElementById('num_doc_date').disabled = false;
                            document.getElementById('num_doc_m').disabled = false;
                            document.getElementById('num_doc_date_m').disabled = false;
							document.getElementById('num_dov').disabled = false;	
							document.getElementById('num_dov_date').disabled = false;	
							document.getElementById('clientAcct1').disabled = false;	
							document.getElementById('clientBank1').disabled = false;	
							document.getElementById('clientCodeBank1').disabled = false;	
							document.getElementById('clientNadb1').disabled = false;	
							document.getElementById('clientLim1').disabled = false;	
							document.getElementById('clientTime1').disabled = false;	
							document.getElementById('clientSize1').disabled = false;	
							document.getElementById('post1_region_id').disabled = false;	
							document.getElementById('post1_post_city').disabled = false;	
							document.getElementById('post1_post_street').disabled = false;	
							document.getElementById('post1_post_home').disabled = false;	
							document.getElementById('post1_post_kor').disabled = false;	
							document.getElementById('post1_post_kv').disabled = false;	
							document.getElementById('post1_post_index').disabled = false;	
							document.getElementById('dev1_region_id').disabled = false;	
							document.getElementById('dev1_post_city').disabled = false;	
							document.getElementById('dev1_post_street').disabled = false;	
							document.getElementById('dev1_post_home').disabled = false;	
							document.getElementById('dev1_post_kor').disabled = false;	
							document.getElementById('dev1_post_kv').disabled = false;	
							document.getElementById('dev1_post_index').disabled = false;	
							document.getElementById('clientAddressPost1').disabled = false;	
							document.getElementById('clientAddressDev1').disabled = false;	
							
						}; });
				});
				
				
				function addRow_form(){
					
					$('#add_edit_cl_cont').modal('show');	
					
				}
				
				function addRow(){
					
					var tbl = document.getElementById ('dynamic');                   // таблица, с которой работаем
					var rws = tbl.rows;                                            // коллекция существующих строк таблицы
					var lst = rws [rws.length - 1];                                // последняя (самая нижняя) существующая строка таблицы
					var cls = lst.cells.length;                                    // количество ячеек в последней существующей строке
					//console.log(cls);
					var ro = tbl.insertRow (-1);                                   // добавляем снизу ОБРАЗ ещё одной строки
					
					var kol = document.getElementById ('kol').value; 
					kol = Number(kol) + 1;
					document.getElementById ('kol').value = kol;
					
					var ce = ro.insertCell (-1);
					ce.innerHTML = "<input type='checkbox' name='chDel' value='1'>";
					
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input name='fio" + kol + "' id = 'fio" + kol + "' type='text' value=''  class='form-control'/>";
					
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input name='email" + kol + "' id = 'email" + kol + "' type='text'  class='form-control' />";
					
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input name='city" + kol + "' id = 'city" + kol + "' type='text'  class='form-control' />";
					
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input name='mob" + kol + "' id = 'mob" + kol + "' type='text'  class='form-control'/>";
					
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input name='Skype" + kol + "' id = 'Skype" + kol + "' type='text'  class='form-control' />";
					
					ce = ro.insertCell (-1);
					ce.innerHTML = "<input name='Viber" + kol + "' id = 'Viber" + kol + "' type='text'  class='form-control'/>";
					
					
				}
				
				function del_row(){
					
					var nodeList = document.getElementsByName('chDel');
					var array = Array.prototype.slice.call(nodeList);
					for (var i = 0; i < array.length; i++) {
						if (array[i].checked) {
							var tr = array[i].parentNode.parentNode;
							var parent = tr.parentNode
							parent.removeChild(tr);
						}
					}
				}
				
				
				
				function funViewCl() {
					var rad=document.getElementsByName('r1');
					for (var i=0;i<rad.length; i++) {
						var input = rad[i];
						if(rad[i].checked){
							
							var theElement = document.getElementById("elem");
							if(input.value == 'f'){
								document.getElementById('elem1' ).style.display = 'block';
								document.getElementById('elem2' ).style.display = 'none';
								} else if (input.value == 'u'){
								document.getElementById('elem1' ).style.display = 'none';
								document.getElementById('elem2' ).style.display = 'block';
							}
						}
					}
				}
				
				function edit_cl(id){
					$('#clientName').val('');
					$('#clientEmail').val('');
					$('#clientPhoneCity').val('');
					$('#clientPhoneMob').val('+375');
					$('#clientSkype').val('');
					$('#clientViber').val('');
					$('#clientAddressPost').val('');
					$('#post_region_id').val('');
					$('#post_post_city').val('');
					$('#post_post_street').val('');	
					$('#post_post_home').val('');
					$('#post_post_kor').val('');
					$('#post_post_kv').val('');
					$('#post_post_index').val('');
					$('#clientAddressDev').val('');
					$('#dev_region_id').val('');
					$('#dev_post_city').val('');
					$('#dev_post_home').val('');
					$('#dev_post_street').val('');
					$('#dev_post_kor').val('');
					$('#dev_post_kv').val('');
					$('#dev_post_index').val('');
					$('#clientNadb').val('');
					$('#clientLim').val('');
					$('#clientTime').val('');
					$('#clientSize').val('');
					$('#clientUnp1').val('');
					$('#clientEmail1').val('');
					$('#clientPhoneCity1').val('');
					$('#clientPhoneMob1').val('');
					$('#clientAddressPost1').val('');
					$('#post1_region_id').val('');
					$('#post1_post_city').val('');
					$('#post1_post_street').val('');
					$('#post1_post_kor').val('');
					$('#post1_post_kv').val('');
					$('#post1_post_home').val('');
					$('#post1_post_index').val('');
					$('#clientAddressDev1').val('');
					$('#dev1_region_id').val('');
					$('#dev1_post_city').val('');
					$('#dev1_post_street').val('');
					$('#dev1_post_kor').val('');
					$('#dev1_post_home').val('');
					$('#dev1_post_kv').val('');
					$('#dev1_post_index').val('');
					$('#fio').val('');
					$('#fioo1').val('');
					$('#osnov').val('');
					$('#num_doc').val('');
					$('#num_doc_date').val('');
                    $('#num_doc_m').val('');
                    $('#num_doc_date_m').val('');
					$('#num_dov').val('');
					$('#num_dov_date').val('');
					$('#clientAcct1').val('');
					$('#clientBank1').val('');
					$('#clientCodeBank1').val('');
					$('#clientNadb1').val('');
					$('#clientLim1').val('');
					$('#clientTime1').val('');
					$('#clientSize1').val('');
					$('#id_edit').val('');
					$('#dev_post_raion').val('');
					$('#post_post_raion').val('');
					$('#dev1_post_raion').val('');
					$('#post1_post_raion').val('');
					$('#postSearch').val('');
					$('#postSearch1').val('');
					$('#postSearch2').val('');
					$('#postSearch3').val('');

					if ($('#kol').val() > '0'){
						
						$("input[name='chDel']").prop('checked', true);
						del_row();
					}
					$('#kol').val('0')
					
					$.ajax({
						type: "GET",
						url: 'ajax_php_sql.php',
						data: {
							flag : 213, 	
							id_cl : id
							},  success:function (data) {//возвращаемый результат от сервера
							str = data.split('|');
							
							if (str[3] == 'f'){
								$('#inp_f').prop('checked', true);
								funViewCl();
								document.getElementById('id_edit').value = str[0];
								document.getElementById('clientName').value = str[1];
								document.getElementById('clientEmail').value = str[2];
								document.getElementById('clientPhoneMob').value = str[4];
								document.getElementById('clientPhoneCity').value = str[5];
								document.getElementById('clientAddressPost').value = str[8];
								document.getElementById('clientAddressDev').value = str[9];
								document.getElementById('clientNadb').value = str[14];
								document.getElementById('clientLim').value = str[15];
								document.getElementById('clientTime').value = str[16];
								document.getElementById('clientSize').value = str[17];
								document.getElementById('clientSkype').value = str[6];
								document.getElementById('clientViber').value = str[7];
								document.getElementById('post_region_id').value = str[36];
								document.getElementById('post_post_city').value = str[35];
								document.getElementById('post_post_street').value = str[34];
								document.getElementById('post_post_kor').value = str[33];
								document.getElementById('post_post_kv').value = str[32];
								document.getElementById('post_post_home').value = str[37];
								document.getElementById('post_post_index').value = str[31];
								document.getElementById('dev_region_id').value = str[30];
								document.getElementById('dev_post_city').value = str[29];
								document.getElementById('dev_post_street').value = str[28];
								document.getElementById('dev_post_kor').value = str[27];
								document.getElementById('dev_post_home').value = str[38];
								document.getElementById('dev_post_kv').value = str[26];
								document.getElementById('dev_post_index').value = str[25];
								document.getElementById('dev_post_raion').value = str[39];
								document.getElementById('post_post_raion').value = str[40];
								
							}
							if (str[3] == 'u'){
								$('#inp_u').prop('checked', true);
								funViewCl();
								document.getElementById('clientEmail1').disabled = false;	
								document.getElementById('clientPhoneCity1').disabled = false;	
								document.getElementById('clientPhoneMob1').disabled = false;	
								document.getElementById('clientAddressPost1').disabled = true;	
								document.getElementById('clientAddressDev1').disabled = true;	
								document.getElementById('fio').disabled = false;	
								document.getElementById('fioo1').disabled = false;	
								document.getElementById('osnov').disabled = false;	
								document.getElementById('num_doc').disabled = false;	
								document.getElementById('num_doc_date').disabled = false;
                                document.getElementById('num_doc_m').disabled = false;
                                document.getElementById('num_doc_date_m').disabled = false;
								document.getElementById('num_dov').disabled = false;	
								document.getElementById('num_dov_date').disabled = false;	
								document.getElementById('clientAcct1').disabled = false;	
								document.getElementById('clientBank1').disabled = false;	
								document.getElementById('clientCodeBank1').disabled = false;	
								document.getElementById('clientNadb1').disabled = false;	
								document.getElementById('clientLim1').disabled = false;	
								document.getElementById('clientTime1').disabled = false;	
								document.getElementById('clientSize1').disabled = false;	
								document.getElementById('post1_region_id').disabled = false;	
								document.getElementById('post1_post_city').disabled = false;	
								document.getElementById('post1_post_street').disabled = false;	
								document.getElementById('post1_post_home').disabled = false;	
								document.getElementById('post1_post_kor').disabled = false;	
								document.getElementById('post1_post_kv').disabled = false;	
								document.getElementById('post1_post_index').disabled = false;	
								document.getElementById('dev1_region_id').disabled = false;	
								document.getElementById('dev1_post_city').disabled = false;	
								document.getElementById('dev1_post_street').disabled = false;	
								document.getElementById('dev1_post_home').disabled = false;	
								document.getElementById('dev1_post_kor').disabled = false;	
								document.getElementById('dev1_post_kv').disabled = false;	
								document.getElementById('dev1_post_index').disabled = false;	
								document.getElementById('clientAddressPost1').disabled = false;	
								document.getElementById('clientAddressDev1').disabled = false;	
								
								
								document.getElementById('id_edit').value = str[0];
								document.getElementById('clientName').value = str[1];
								document.getElementById('clientEmail1').value = str[2];
								document.getElementById('clientPhoneMob1').value = str[4];
								document.getElementById('clientPhoneCity1').value = str[5];
								document.getElementById('clientAddressPost1').value = str[8];
								document.getElementById('clientAddressDev1').value = str[9];
								document.getElementById('clientNadb1').value = str[14];
								document.getElementById('clientLim1').value = str[15];
								document.getElementById('clientTime1').value = str[16];
								document.getElementById('clientSize1').value = str[17];
								document.getElementById('clientUnp1').value = str[10];
								document.getElementById('clientAcct1').value = str[11];
								document.getElementById('clientBank1').value = str[12];
								document.getElementById('clientCodeBank1').value = str[13];
								document.getElementById('osnov').value = str[21];
								document.getElementById('fio').value = str[20];
								document.getElementById('fioo1').value = str[22];
								document.getElementById('post1_region_id').value = str[36];
								document.getElementById('post1_post_city').value = str[35];
								document.getElementById('post1_post_street').value = str[34];
								document.getElementById('post1_post_kor').value = str[33];
								document.getElementById('post1_post_kv').value = str[32];
								document.getElementById('post1_post_home').value = str[37];
							document.getElementById('post1_post_index').value = str[31];
							document.getElementById('dev1_region_id').value = str[30];
							document.getElementById('dev1_post_city').value = str[29];
							document.getElementById('dev1_post_street').value = str[28];
							document.getElementById('dev1_post_kor').value = str[27];
							document.getElementById('dev1_post_home').value = str[38];
							document.getElementById('dev1_post_kv').value = str[26];
							document.getElementById('dev1_post_index').value  = str[25];
							document.getElementById('dev1_post_raion').value = str[39];
							document.getElementById('post1_post_raion').value = str[40];
							
							/*alert(str[24] + " " + str[18] + " " + str[19])	*/
							if (str[19] != ""){
                                num_doc = str[19].split(' от ');
                                document.getElementById('num_doc').value = num_doc[0];
                                num_doc_date = num_doc[1].split('.');
                                document.getElementById('num_doc_date').value = num_doc_date[2] + "-" + num_doc_date[1]  + "-" + num_doc_date[0] ;
							}
                            if (str[41] != ""){
                                num_doc_m = str[41].split(' от ');
                                if(num_doc_m.length == 2) {
                                    document.getElementById('num_doc_m').value = num_doc_m[0];
                                    num_doc_date_m = num_doc_m[1].split('.');
                                    document.getElementById('num_doc_date_m').value = num_doc_date_m[2].trim() + "-" + num_doc_date_m[1].trim() + "-" + num_doc_date_m[0].trim();
                                }
                            }
							
							if (str[24] != ""){
                                num_dov = str[24].split(' от ');
                                document.getElementById('num_dov').value = num_dov[0];
                                num_dov_date = num_dov[1].split('.');
                                document.getElementById('num_dov_date').value = num_dov_date[2] + "-" + num_dov_date[1]  + "-" + num_dov_date[0] ;
							}
							
							
							if (str[18] != ""){	
							
							pta = str[18].split("!");
							
							
							for (i = 0; i < pta.length; i++){
							addRow();
							ii = i + 1;
							tt = pta[i].split('$');
							$('#fio' + ii).val(tt[0]);
							$('#email' + ii).val(tt[1]);
							$('#city' + ii).val(tt[2]);
							$('#mob' + ii).val(tt[3]);
							$('#Skype' + ii).val(tt[4]);
							$('#Viber' + ii).val(tt[5]);
							
							
							}
							
							}	
							// str : str,    18
							
							
							}
							
							$('#form_cl').modal('show');
							
							}
							});
							
							
							}
							
							$(document).ready(function(){
							funViewCl();
							search_city('');
							
							
							});
							
							
							$('#postSearch').keyup(function(){
							var Value = $('#postSearch').val();
							search_city(Value , 1);
							});
							$('#postSearch1').keyup(function(){
							var Value = $('#postSearch1').val();
							search_city(Value, 2);
							});
							$('#postSearch2').keyup(function(){
							var Value = $('#postSearch2').val();
							search_city(Value, 3);
							});
							$('#postSearch3').keyup(function(){
							var Value = $('#postSearch3').val();
							search_city(Value, 4);
							});
							
							function search_city(srt, flag){
							str = srt;
							if (srt.length < 3){
							str = '';	
							}
							
							
							$.ajax({
							type: "GET",
							url: 'ajax_php_sql.php',
							data: {
							flag : 214, 	
							str  : str
							},  
							success:function (data) {//возвращаемый результат от сервера
							if (flag == 1){
							$('#postSearchsel').empty().append(data);
							}
							if (flag == 2){
							$('#postSearchsel1').empty().append(data);
							}
							if (flag == 3){
							$('#postSearchsel2').empty().append(data);
							}
							
							if (flag == 4){
							$('#postSearchsel3').empty().append(data);
							}
							}
							});
							
							}
							
							
							
							$("#postSearch").on('input', function () {
							var val = this.value;
							if($('#postSearchsel option').filter(function(){
							return this.value === val;        
							}).length) {
							
							
							$.ajax({
							type: "GET",
							url: 'ajax_php_sql.php',
							data: {
							flag : 215, 	
							id  : this.value 
							},  
							success:function (data) {//возвращаемый результат от сервера
							
							str = data.split('|')
							
							document.getElementById('post_region_id').value = str[1];
							document.getElementById('post_post_city').value = str[0];
							document.getElementById('post_post_raion').value = str[2];
							}
							});
							
							
							}
							});		
							
							$("#postSearch1").on('input', function () {
							var val = this.value;
							if($('#postSearchsel1 option').filter(function(){
							return this.value === val;        
							}).length) {
							
							
							$.ajax({
							type: "GET",
							url: 'ajax_php_sql.php',
							data: {
							flag : 215, 	
							id  : this.value 
							},  
							success:function (data) {//возвращаемый результат от сервера
							
							str = data.split('|')
							document.getElementById('dev_region_id').value = str[1];
							document.getElementById('dev_post_city').value = str[0];
							document.getElementById('dev_post_raion').value = str[2];
							}
							});
							
							
							}
							});
							
							$("#postSearch2").on('input', function () {
							var val = this.value;
							if($('#postSearchsel2 option').filter(function(){
							return this.value === val;        
							}).length) {
							
							
							$.ajax({
							type: "GET",
							url: 'ajax_php_sql.php',
							data: {
							flag : 215, 	
							id  : this.value 
							},  
							success:function (data) {//возвращаемый результат от сервера
							
							str = data.split('|')
							document.getElementById('post1_region_id').value = str[1];
							document.getElementById('post1_post_city').value = str[0];
							document.getElementById('post1_post_raion').value = str[2];
							}
							});
							
							
							}
							});
							
							$("#postSearch3").on('input', function () {
							var val = this.value;
							if($('#postSearchsel3 option').filter(function(){
							return this.value === val;        
							}).length) {
							
							
							$.ajax({
							type: "GET",
							url: 'ajax_php_sql.php',
							data: {
							flag : 215, 	
							id  : this.value 
							},  
							success:function (data) {//возвращаемый результат от сервера
							
							str = data.split('|')
							document.getElementById('dev1_region_id').value = str[1];
							document.getElementById('dev1_post_city').value = str[0];
							document.getElementById('dev1_post_raion').value = str[2];
							}
							});
							
							
							}
							});
							
							
							</script>
							
							<!-- Site footer -->
							<footer class="footer">
							<p>&copy; Company 2016</p>
							</footer>
							</body>
							
							</html>
														