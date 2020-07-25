<?php
include '../firewall1.php';
session_start();
$login = $_SESSION['login'];
$query = "select user_per from users where user_login = '".$login ."' LIMIT 1";
$result = mysql_query($query) or die($query);
while ($row = mysql_fetch_row($result)) { 
	$admin = $row[0];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Система управления заказами</title>
	<link rel="icon" href="../../favicon.png" type="image/png">

    <!-- Bootstrap Core CSS -->
    <link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../../dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="../../vendor/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">



</head>

<body>
    <div id="wrapper">
        <!-- Navigation -->
	<?php
		include_once("../menu1.php");
					
	?>

       <div id="page-wrapper">
			<div class="row">
            <div class="col-lg-12">
               <h2 class="page-header">Загруженность дизайнеров</h2>
            </div>
         </div>
            <!-- /.row -->
       <!--   <div class="row">	
			<div class="col-md-1">
				</div>  
				<div class="col-md-6">
					C <input type='date' id='date1' name='date'>	 
					ПО <input type='date' id='date2' name='date'>
					<button type="button" class="btn btn-info btn-circle" onclick='rep()' >
					<i class="fa fa-search"></i> 
					</button>
				</div>    
			</div>-->
					<div class="row"><div class="col-md-6"> <br>	 	</div>    </div>				 
				<div class="row" id = "list">	</div>   
				<div class="row"><div class="col-md-6"> <br>	 	</div> 	   </div>
            <!-- /.row -->
            <!-- /.row -->				 
									 
            <!-- /.row -->
            <!-- /.row -->
             <div class="row">	
			<div class="col-md-1">
				</div>
				<hr>				
				<div class="col-md-6">
					C <input type='date' id='date1' name='date'>	 
					ПО <input type='date' id='date2' name='date'>
					<button type="button" class="btn btn-info btn-circle" onclick='rep1()' >
					<i class="fa fa-search"></i> 
					</button>
				</div>    
			</div>
				<div class="row" id = "list1">	</div>   
			
			
        </div> </div>
          </div>   
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="../../vendor/jquery/jquery.min.js"></script>
	<script src="../../js/funJs.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="../../vendor/metisMenu/metisMenu.min.js"></script>

    <!-- Morris Charts JavaScript -->
    <script src="../../vendor/raphael/raphael.min.js"></script>
    <script src="../../vendor/morrisjs/morris.min.js"></script>
    <script src="../../data/morris-data.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="../../dist/js/sb-admin-2.js"></script>

    <!-- DataTables JavaScript -->
    <script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
    <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
    <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
<script>
$(document).ready(function(){
	/*	$("#date1").val('<? echo date("Y-m-d")?>');
		$("#date2").val('<? echo date("Y-m-d")?>');*/
		 $.get( 'diz_sql.php', {/*dt1: date1, dt2: date2*/}, function (data) { document.getElementById('list').innerHTML = data });
});
</script>
	 <script>
function rep(){
	/*date1 = document.getElementById('date1').value;
	date2 = document.getElementById('date2').value;
	*/
	 $.get( 'diz_sql.php', {/*dt1: date1, dt2: date2*/}, function (data) { document.getElementById('list').innerHTML = data });
}


function rep1(){
	date1 = document.getElementById('date1').value;
	date2 = document.getElementById('date2').value;
	
	 $.get( 'di_sql.php', {dt1: date1, dt2: date2}, function (data) { document.getElementById('list1').innerHTML = data });
}
</script>
	
      <!-- Site footer -->
      <footer class="footer">
        <p>&copy; Company 2016</p>
      </footer>
</body>

</html>
