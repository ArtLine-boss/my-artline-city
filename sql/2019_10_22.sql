/* ВЫГРУЖЕНО */

INSERT INTO `settings`(`ID`, `NAME`, `VAL`) VALUES(19, 'Текущий номер товарного чека', 1);

CREATE TABLE `orders_check` (
	`ID` INT(10) NOT NULL AUTO_INCREMENT,
	`user_id` VARCHAR(30) NOT NULL COMMENT 'Пользователь',
	`number_check` VARCHAR(10) NOT NULL COMMENT 'Номер товарного чека',
	`products` VARCHAR(255) NOT NULL COMMENT 'Список продуктов',
	`summaAll` DECIMAL(18,2) NOT NULL DEFAULT '0.00' COMMENT 'Общая сумма чека',
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `number_check` (`number_check`),
	INDEX `user_id` (`user_id`),
	CONSTRAINT `FK_orders_check_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`USER_LOGIN`)
)
COMMENT='Товарные чеки'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

CREATE TABLE `accesscategory` (
	`ID` INT(10) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `ID` (`ID`)
)
COMMENT='Категории доступа'
COLLATE='utf8_general_ci'
ENGINE=InnoDB;

INSERT INTO `art`.`accesscategory` (`ID`, `name`) VALUES ('1', 'Доступ к страницам');
INSERT INTO `art`.`accesscategory` (`ID`, `name`) VALUES ('2', 'Печать');
INSERT INTO `art`.`accesscategory` (`ID`, `name`) VALUES ('3', 'Функции');

CREATE TABLE `AccessLevel` (
	`ID` INT(10) NOT NULL,
	`category_id` INT(10) NOT NULL,
	`name` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`ID`),
	UNIQUE INDEX `ID` (`ID`),
	INDEX `category_id` (`category_id`),
	CONSTRAINT `FK_AccessLevel_accesscategory` FOREIGN KEY (`category_id`) REFERENCES `accesscategory` (`ID`)
)
COMMENT='Список доступов'
ENGINE=InnoDB;

INSERT INTO `art`.`accesslevel` (`ID`, `category_id`, `name`) VALUES ('1', '2', 'Печать товарного чека');

CREATE TABLE `AccessRole` (
	`ID` INT(10) NOT NULL AUTO_INCREMENT,
	`level_id` INT(10) NOT NULL,
	`user_id` VARCHAR(30) NOT NULL,
	`date_start` DATETIME NOT NULL,
	`date_end` DATETIME NOT NULL,
	PRIMARY KEY (`ID`),
	INDEX `level_id` (`level_id`),
	INDEX `user_id` (`user_id`),
	CONSTRAINT `FK_AccessRole_accesslevel` FOREIGN KEY (`level_id`) REFERENCES `accesslevel` (`ID`),
	CONSTRAINT `FK_AccessRole_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`USER_LOGIN`)
)
COMMENT='Доступы пользователей'
ENGINE=InnoDB;

INSERT INTO `art`.`accessrole` (`level_id`, `user_id`, `date_start`) VALUES ('1', 'admins', '2019-10-22 22:55:19');
INSERT INTO `art`.`accessrole` (`level_id`, `user_id`, `date_start`) VALUES ('1', '026', '2019-10-23 08:00:00');
INSERT INTO `art`.`accessrole` (`level_id`, `user_id`, `date_start`) VALUES ('1', '027', '2019-10-23 08:00:00');
INSERT INTO `art`.`accessrole` (`level_id`, `user_id`, `date_start`) VALUES ('1', '033', '2019-10-23 08:00:00');




