ALTER TABLE `mail`
    ADD COLUMN `status_read` SMALLINT NOT NULL DEFAULT '0' COMMENT 'Прочитана ли информация по доставке' AFTER `mail`;

UPDATE mail SET mail.status_read = 1
WHERE mail.track_cod <> '' AND `mail`.`date_otpr` IS NOT NULL AND mail.DATE IS NOT NULL;

UPDATE mail SET mail.status_read = 1
WHERE mail.track_cod <> '' AND `mail`.`date_otpr` IS NOT NULL AND mail.DATE IS NULL AND DATE_FORMAT(`mail`.`date_otpr`,'%Y') != DATE_FORMAT(NOW(), '%Y');