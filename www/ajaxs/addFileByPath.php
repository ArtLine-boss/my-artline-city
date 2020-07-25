<?php
/**
 * загрузка файлов
 */
do {
    $param = (isset($_GET['param']) && !empty(json_decode($_GET['param']))) ? json_decode($_GET['param']) : null;
    if(empty($param)) {
        $ajaxObject->setMsg('Не заданы параметры');
        break;
    }
    $path = (property_exists($param, 'path') && !empty($param->path)) ? $param->path : null;
    if(empty($path)) {
        $ajaxObject->setMsg('Не задан путь к каталогу');
        break;
    }
    if(empty($_FILES)) {
        $ajaxObject->setMsg('Не найдены файлы');
        break;
    }
    if(null !== ($msg = API::createPath($path, $_SERVER['DOCUMENT_ROOT'] . "/www"))) {
        $ajaxObject->setMsg($msg);
        break;
    }
    $path = $_SERVER['DOCUMENT_ROOT'] . "/www/" . $path;
    foreach ($_FILES as $file) {
        $filetype = strrev(substr(strrev($file['name']), 0, strpos(strrev($file['name']), ".")));
        if(in_array($filetype, array('php', 'log', 'js', 'css', 'html', 'htaccess'))) {
            $ajaxObject->setMsg("Файл " . $file['name'] . " является системным, его нельзя загрузить");
            break(2);
        }
        $filename = API::rus2translit($file['name']);
        if( !move_uploaded_file( $file['tmp_name'], $path . '/' . $filename ) ){
            $ajaxObject->setMsg('Файл ' . $file['name'] . ' не может быть перемещён по каким-либо причинам');
            break(2);
        }
    }
    $ajaxObject->setData(true);
} while(false);
?>