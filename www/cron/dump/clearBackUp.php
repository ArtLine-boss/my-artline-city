<?php
/***
 * Удаляет излишки бэкапов. Оставляются бэкапы промежутка 18.00 - 19.00. За последний месяц остаются все
 * Путь к скрипту - http://artline.city/www/index.php?m=cron&u=dump&a=clearBackUp&scr=1
 */
//логирование
$log = new core_log('cron/dump/dumpClearBackUp.log');
// Исходный каталог
$dir = 'D:\\backups';
$scandir = scandir($dir);

$date = date(CONSTANTS::DB_DATE_FORMAT);
$date = strtotime(date(CONSTANTS::DB_DATE_FORMAT, strtotime("-1 months", strtotime($date))));

$counter = 0;
foreach ($scandir as $k => $v) {
    if($v == '.' || $v == '..') {
        continue;
    }
    $filectime = filectime($dir . '/' . $v);
    if($filectime >= $date) {
        continue;
    }
    $dateStart = strtotime(date(CONSTANTS::DB_DATETIME_FORMAT, mktime(18, 0, 0, date('m', $filectime), date('d', $filectime), date('Y', $filectime))));
    $dateEnd = strtotime(date(CONSTANTS::DB_DATETIME_FORMAT, mktime(19, 0, 0, date('m', $filectime), date('d', $filectime), date('Y', $filectime))));
    if($filectime >= $dateStart && $filectime <= $dateEnd) {
        continue;
    }
    API::removeDirectory($dir . '/' . $v);
    $counter++;
}

$log->store('Удалено каталогов бэкапа - ' . $counter);

?>