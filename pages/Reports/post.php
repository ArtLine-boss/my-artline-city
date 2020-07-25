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

   <link href="../../vendor/datatables-responsive/dataTables.responsive.css" rel="stylesheet">
	<link href="../../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
	<link href="../../vendor/metisMenu/metisMenu.min.css" rel="stylesheet">
	<link href="../../dist/css/sb-admin-2.css" rel="stylesheet">
	<link type="text/css" href="../../js/jquery-ui.min.css" rel="stylesheet" />	
	<link href="../../vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
	<link href="../../vendor/bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script>

</head>

<body>
    <div id="wrapper">
       <?php
		include_once("../menu1.php");
					
	?>

      
    </div>
     
	<div id="page-wrapper">
		<div class="row">
			<div class="col-lg-12">
				<h2 class="page-header">Взаиморасчет</h2>
         </div>
      </div>


 
		<div class="row">
			<div class="col-lg-12">
				<div class="panel panel-default">
					<div class="panel-body">
						<table  width="100%"   class="table table-striped table-hover " cellspacing="0"  id="example"  >
							<thead>
								<tr>
									<th></th>
									<th>Наименование</th>
									<th>СуммаТТН</th>
									<th>СуммаОплат</th>
									<th>СуммаТТН -  СуммаОплаты</th>
						
								
								</tr>
							</thead>
							</table>
					</div>
				</div>
			</div>
		</div>
	</div>	
   

			
    <!-- /#wrapper -->

    <!-- jQuery -->
   
	 
	<script src="../../vendor/jquery/jquery.min.js"></script> 
	<script src="../../js/jquery-ui.min.js"></script>
	<script src="../../vendor/datatables/js/jquery.dataTables.min.js"></script>
   <script src="../../vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
   <script src="../../vendor/datatables-responsive/dataTables.responsive.js"></script>
	<script src="../../js/funJs.js"></script>
	<script src="../../vendor/bootstrap/js/bootstrap-select.js" type = "text/javascript"></script>
   <script src="../../vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="../../vendor/metisMenu/metisMenu.min.js"></script>
   <script src="../../dist/js/sb-admin-2.js"></script>
	
	<script src="../../js/buttons.print.min.js"></script>
	<script src="../../js/buttons.html5.min.js"></script>
	<script src="../../js/vfs_fonts.js"></script>
	<script src="../../js/pdfmake.min.js"></script>
	<script src="../../js/jszip.min.js"></script>
	<script src="../../js/buttons.flash.min.js"></script>
	<script src="../../js/dataTables.buttons.min.js"></script>
	<script src="../../js/.js"></script>
	<script src="../../js/.js"></script>
	
		<script>
		jQuery.browser = {};
		(function () {
			 jQuery.browser.msie = false;
			 jQuery.browser.version = 0;
			 if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
				  jQuery.browser.msie = true;
				  jQuery.browser.version = RegExp.$1;
			 }
		})();
	</script>

	<script type="text/javascript">
			
			var default_options = {
		dom:'Bfrtip',
		   buttons: [
             'excel']
				 
				  ,
				"iDisplayLength": 25,
					
					"ajax" : {
						"url" : 'post_json.php',
						"type" : "GET",
						"dataSrc": ""
					},
					"columns": [
					 
						{ "data": "q",  "className":	'details-control'},
						{ "data": "names",  "className":	'acct'},
						{ "data": "sum1" ,  "className":	'acct'},
						{ "data": "sum2" ,  "className":	'acct'},
						{ "data": "sum3",  "className":	'acct' }/*,
						{ "data": "TM" ,  "className":	'no_acctiv'}*/
						
				  ]   ,
				 
 

	"aaSorting": [[1,'asc']]
	
			};
			
			
			
			var dTable= $('#example').dataTable(default_options);

			
				$(function() {
	
		   $('#example tbody').on('click', 'td.details-control', function () {
				var table = $('#example').DataTable();
				
             var tr = $(this).closest('tr');
			
             var tdi = tr.find("i.fa");
				 	 				
             var row = table.row(tr);

					
             if (row.child.isShown()) {
                 // This row is already open - close it
                 row.child.hide();
                 tr.removeClass('shown');
                 tdi.first().removeClass('fa-minus-square');
                 tdi.first().addClass('fa-plus-square');
             }
             else {
                 // Open this row
                 row.child(format(row.data())).show();
                 tr.addClass('shown');
                 tdi.first().removeClass('fa-plus-square');
                 tdi.first().addClass('fa-minus-square');
             }
         });

	});

	function format(d){
        
         // `d` is the original data object for the row
         return d.TM;  
    }

	
	jQuery.browser = {};
	(function () {
		 jQuery.browser.msie = false;
		 jQuery.browser.version = 0;
		 if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
			  jQuery.browser.msie = true;
			  jQuery.browser.version = RegExp.$1;
		 }
	})();
			
			
			</script>

</body>

</html>
