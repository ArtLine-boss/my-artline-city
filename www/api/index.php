<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST');
    header('Access-Control-Allow-Headers: Content-Type, Method');

    try {
        if(file_exists('core.php'))
            require_once ('core.php');
        else
            require_once ('../core/core.php');
        $ajaxObject = null;
        $AppUI = new core_ui(true);
        $ajaxObject = new core_Dto();

        do {
            if (!isset($_SERVER['HTTP_METHOD']) || empty($_SERVER['HTTP_METHOD'])) {
                $ajaxObject->setMsg("В заголовке отсутствует метод");
                break;
            }
            $filename = $_SERVER['DOCUMENT_ROOT'] . "/www/api/" . $_SERVER['HTTP_METHOD'] . '/index.php';
            if(!file_exists($filename)) {
                $ajaxObject->setMsg("Неверный метод " . $_SERVER['HTTP_METHOD']);
                break;
            }
            require($filename);
        } while(false);

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