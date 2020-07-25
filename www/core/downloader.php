<?php
/**
 * интерфейс скачивания файла
 */
$dir = $_SERVER['DOCUMENT_ROOT'];
if(file_exists($dir . '/www/core/core.php'))
    require_once ($dir . '/www/core/core.php');

try {
    $AppUI = new core_ui(false, true);

    $path = (isset($_GET['path']) && !empty($_GET['path'])) ? json_decode($_GET['path']) : null;

    if(empty($path)) {
        $msg = "Не задан путь к файлу";
        throw new Exception($msg);
    }
    $path = $_SERVER['DOCUMENT_ROOT'] . "/www/" . $path;
    if(!file_exists($path)) {
        $msg = "Файл " . basename($path) . " не найден";
        throw new Exception($msg);
    }
    if(in_array(filetype($path), array('.php', '.log', '.js', '.css', '.html'))) {
        $msg = "Файл " . basename($path) . " является системным, его нельзя скачать";
        throw new Exception($msg);
    }
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
        ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($path));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($path));
    // читаем файл и отправляем его пользователю
    readfile($path);
    exit;

} catch (Throwable $tr) {
    echo $tr->getCode() . ": " . $tr->getMessage() . ". Строка: " . $tr->getLine();
} catch (Exception $ex) {
    echo $ex->getCode() . ": " . $ex->getMessage() . ". Строка: " . $ex->getLine();
}
?>