CREATE TABLE IF NOT EXISTS `firm_parent` (
                                             `ID` int(11) NOT NULL,
                                             `name` varchar(255) NOT NULL,
                                             PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Обслуживающие фирмы';

-- Дамп данных таблицы art.firm_parent: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `firm_parent` DISABLE KEYS */;
INSERT INTO `firm_parent` (`ID`, `name`) VALUES
(1, 'ОДО АртЛайнСити'),
(2, 'ЧУП Мечта клиента');

ALTER TABLE `clients`
    ADD COLUMN `firm_parent` INT NULL DEFAULT '1' AFTER `post_raion`;
ALTER TABLE `clients`
    ADD CONSTRAINT `FK_clients_firm_parent` FOREIGN KEY (`firm_parent`) REFERENCES `firm_parent` (`ID`);