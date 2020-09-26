<?php
    include_once('core/core.php');
    $AppUI = null;

    if(isset($_GET['scr']) && $_GET['scr'] == 1) {
        $AppUI = new core_ui(true);
        if(isset($_GET['m'])) {
            $file = strval($_GET['m']);
            if(isset($_GET['u'])) {
                $file .= "/" . strval($_GET['u']);
            }
            if(isset($_GET['a'])) {
                $file .= "/" . strval($_GET['a']) . ".php";
            } else {
                $file .= "/index.php";
            }
            include_once ($file);
        }
    }
    else if(isset($_GET['login']) && $_GET['login'] == -1) {
        //include_once("../pages/login.php");
        header("Location: ../pages/login.php");
        exit;
    } else {
        $AppUI = new core_ui();
        $AppUI->startViewPage();
        if(isset($_GET['m'])) {
            $file = strval($_GET['m']);
            if(isset($_GET['u'])) {
                $file .= "/" . strval($_GET['u']);
            }
            if(isset($_GET['a'])) {
                $file .= "/" . strval($_GET['a']) . ".php";
            } else {
                $file .= "/index.php";
            }
            include_once ($file);
        } else {
            //include_once("../pages/orders.php");
            //header("Location: ../pages/orders.php");
            echo 'Тут страница по умолчанию';
            exit;
        }
        $AppUI->endViewPage();

        // Сообщения
        include_once('popup/index.php');
    }


?>