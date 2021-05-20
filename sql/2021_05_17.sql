INSERT INTO `accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('32', 'Соответствие пользователей', 'ACCESS_PAGE_SETTINGS_ACCORD', '/www/index.php?m=settings&u=accord', '17', 'fa fa-retweet u-mr-xsmall');

INSERT INTO `accesslevel` VALUES ('35', '1', 'Доступ к странице соответствий пользователей в компаниях', 'ACCESS_PAGE_SETTINGS_ACCORD');

INSERT INTO `accessrole` (`level_id`, `user_id`, `date_start`) VALUES ('35', 'admins', '2021-05-17 21:36:40');

INSERT INTO `accessrole` (`level_id`, `user_id`, `date_start`) VALUES ('35', 'admin', '2021-05-19 08:00:00');

CREATE TABLE `accord_users` (
                                `ID` BIGINT(20) NOT NULL AUTO_INCREMENT,
                                `user` VARCHAR(30) NOT NULL COMMENT 'Пользователь, который добавил',
                                `user1` VARCHAR(30) NOT NULL COMMENT 'Пользователь 1й компании',
                                `user2` VARCHAR(30) NOT NULL COMMENT 'Пользователь 2й компании',
                                `accord_type` SMALLINT(6) NOT NULL DEFAULT '0' COMMENT '0 - АртЛайн-Мечта, 1 - Мечта-АртЛайн',
                                `status` SMALLINT(6) NOT NULL DEFAULT '1' COMMENT '1 - акутально',
                                `date_start` DATETIME NOT NULL COMMENT 'Дата старта соответствия',
                                `date_cancel` DATETIME NOT NULL COMMENT 'Дата аннулирования',
                                PRIMARY KEY (`ID`),
                                INDEX `user` (`user`),
                                INDEX `user1` (`user1`),
                                INDEX `user2` (`user2`),
                                INDEX `accord_type` (`accord_type`),
                                INDEX `status` (`status`),
                                INDEX `date_start` (`date_start`),
                                INDEX `date_cancel` (`date_cancel`)
)
    COMMENT='Соответствие пользователей'
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;
