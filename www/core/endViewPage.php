<?php
?>

</div>
</main>
<!-- Scripts -->
<script src="js/main.min.js"></script>
<script src="js/controller.js"></script>
<?php
if(isset($_GET['m'])) {
    $js = strval($_GET['m']);
    if(isset($_GET['u'])) {
        $js .= "_" . strval($_GET['u']);
    }
    if(isset($_GET['a'])) {
        $js .= "_" . strval($_GET['a']);
    }
    $js = "js/" . $js . ".js";
    if(file_exists($js)) {
        echo '<script src="' . $js . '"></script>';
    }
}
?>
</body>
</html>