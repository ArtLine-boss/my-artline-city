<?php
/*
 * Путь к скрипту - http://artline.city/www/index.php?m=cron&u=updateTrackCodePost&scr=1
 */
//логирование
$log = new core_log('cron/updateTrackCodePost/track.log');
$log->store(entity_mail::updateTracks());
//API::vardump(entity_mail::updateTracks());
?>