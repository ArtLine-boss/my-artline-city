<?php
/* 
 * Удаление файлов по пути
 */
do {
    $path = (isset($_POST['path']) && !empty($_POST['path'])) ? $_POST['path'] : null;
    if(null !== ($msg = API::deleteFile($path))) {
        $ajaxObject->setMsg($msg);
        break;
    }
    $ajaxObject->setData(true);
} while(false);
?>

