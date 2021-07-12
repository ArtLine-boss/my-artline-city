<?php
/*
 * D:\#WORK\OSPanel\modules\wget\bin\wget.exe -q --no-cache
 * Путь к скрипту - http://artline.city/www/index.php?m=cron&u=clearOrders&scr=1
 */

    cron_clearOrders_Manager::getInstance()->run();
?>