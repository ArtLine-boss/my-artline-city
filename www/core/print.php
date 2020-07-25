<?php
	if(file_exists('core.php'))
        require_once ('core.php');
    else
        require_once ('../core/core.php');
	$AppUI = new core_ui();
    if(isset($_GET['m'])) {
        $file = '../' . strval($_GET['m']);
        if(isset($_GET['a'])) {
            if(isset($_GET['u'])) {
                $file .= '/' . strval($_GET['u']);
            }
            $file .= '/' . strval($_GET['a']) . '.php';
            if(file_exists($file)) {
?>
				<!-- Google Font -->
				<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600" rel="stylesheet">
				<!-- Favicon -->
				<link rel="shortcut icon" href="../css/img/favicon.png" type="image/png">
				<!-- Stylesheet -->
				<link rel="stylesheet" href="../css/main.min.css">
				<link rel="stylesheet" href="../css/controller.css">
				<style>
					body {
						font-family: Times New Romance;
						font-size: 14px;
					}
				</style>
<?php
                include_once ($file);
?>
				<script src="../js/main.min.js"></script>
<?php
            }
        }
    }
?>
