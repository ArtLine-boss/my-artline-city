<?
$name = $_POST['name'];
$files1 = scandir($name);
$list = '';
for ($i = 2; $i < count($files1); $i++ ){
		$list .=	'<div class="row">
						<div class="col-md-1">  </div>
						<div class="col-md-11"> <a href="pg/'.$name.'/'.$files1[$i].'" download name="down_all">'.$files1[$i].'</a> </div>
						 </div>';
	
}



						 
echo $list;
						 
						 
						 ?>