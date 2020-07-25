<?php
    $dir = $_SERVER['DOCUMENT_ROOT'];
    if(file_exists($dir . '/www/core/core.php'))
        require_once ($dir . '/www/core/core.php');

    $ajaxObject = null;
    try {
        $AppUI = new core_ui(false, true);
        $ajaxObject = new core_Dto();

        if (!empty($AppUI->ajax_reload_url)) {
            $ajaxObject = new core_Dto();
            $ajaxObject->setReloadURL($AppUI->ajax_reload_url);
        } else {
            if (isset($_GET['m'])) {
                $file = '../' . strval($_GET['m']);
                if (isset($_GET['a'])) {
                    if (isset($_GET['u'])) {
                        $file .= '/' . strval($_GET['u']);
                    }
                    $file .= '/' . strval($_GET['a']) . '.php';
                    if (file_exists($file)) {
                        require_once($file);
                    }
                }
            }
        }
    } catch (Throwable $tr) {
        $ajaxObject->setMsg($tr->getCode() . ": " . $tr->getMessage() . ". Строка: " . $tr->getLine());
    } catch (Exception $ex) {
        $ajaxObject->setMsg($ex->getCode() . ": " . $ex->getMessage() . ". Строка: " . $ex->getLine());
    }
    while (ob_get_level() !== 0) {
        ob_end_clean();
    }
    echo json_encode($ajaxObject);
?>
