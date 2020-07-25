<?php
include '../firewall.php';


$id = $_GET['id'];

echo '<iframe  id = "fr" src="pg/proc/tn1.php?id='.$id.'" width="100%" height="1000" align="center">
    Ваш браузер не поддерживает плавающие фреймы!
 </iframe>';


?>


 