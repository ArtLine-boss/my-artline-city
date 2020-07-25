-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               5.6.43 - MySQL Community Server (GPL)
-- Операционная система:         Win32
-- HeidiSQL Версия:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица art.directorycolorluv
CREATE TABLE IF NOT EXISTS `directorycolorluv` (
                                                   `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                                   `name` varchar(50) NOT NULL,
                                                   `value` smallint(6) NOT NULL,
                                                   PRIMARY KEY (`ID`),
                                                   UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='Справочник раскраски люверса';

-- Дамп данных таблицы art.directorycolorluv: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `directorycolorluv` DISABLE KEYS */;
INSERT INTO `directorycolorluv` (`ID`, `name`, `value`) VALUES
(1, '', 0),
(2, '4 мм серебро', 1),
(3, '4 мм золото', 2),
(4, '4 мм черный', 3),
(5, '5 мм золото', 4),
(6, '5 мм серебро', 5);
/*!40000 ALTER TABLE `directorycolorluv` ENABLE KEYS */;

-- Дамп структуры для таблица art.directorydiam
CREATE TABLE IF NOT EXISTS `directorydiam` (
                                               `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                               `name` varchar(50) NOT NULL,
                                               `value` smallint(6) NOT NULL,
                                               PRIMARY KEY (`ID`),
                                               UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='Справочник диаметров';

-- Дамп данных таблицы art.directorydiam: ~5 rows (приблизительно)
/*!40000 ALTER TABLE `directorydiam` DISABLE KEYS */;
INSERT INTO `directorydiam` (`ID`, `name`, `value`) VALUES
(1, '', 0),
(2, '3 мм', 1),
(3, '4 мм', 2),
(4, '5 мм', 4),
(5, '6 мм', 3);
/*!40000 ALTER TABLE `directorydiam` ENABLE KEYS */;

-- Дамп структуры для таблица art.directorylaminat
CREATE TABLE IF NOT EXISTS `directorylaminat` (
                                                  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                                  `name` varchar(50) NOT NULL,
                                                  `value` smallint(6) NOT NULL,
                                                  `nadb_2` float NOT NULL DEFAULT '0',
                                                  `nadb_3` float NOT NULL DEFAULT '0',
                                                  `nadb_5` float NOT NULL DEFAULT '0',
                                                  `nadb_default` float NOT NULL DEFAULT '0',
                                                  PRIMARY KEY (`ID`),
                                                  UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='Спрвочник ламинации';

-- Дамп данных таблицы art.directorylaminat: ~10 rows (приблизительно)
/*!40000 ALTER TABLE `directorylaminat` DISABLE KEYS */;
INSERT INTO `directorylaminat` (`ID`, `name`, `value`, `nadb_2`, `nadb_3`, `nadb_5`, `nadb_default`) VALUES
(1, '', 0, 0, 0, 0, 0),
(2, 'гл.+гл.', 1, 0.2, 0.2, 0.2, 0.4),
(3, 'мат+мат', 2, 0.4, 0.4, 0.4, 0.8),
(4, 'гл.+0', 3, 0.1, 0.1, 0.1, 0.2),
(5, 'мат+0', 4, 0.1, 0.2, 0.15, 0.4),
(6, 'цифра', 5, 0.5, 0.5, 0.5, 1),
(7, 'гл.+мат', 6, 0.3, 0.3, 0.3, 0.6),
(8, 'СофтТач+0', 7, 0.4, 0.4, 0.4, 0.6),
(9, 'СофтТач+СофтТач', 8, 0.8, 0.8, 0.8, 1),
(10, 'СофтТач+мат', 9, 0.5, 0.6, 0.55, 1);
/*!40000 ALTER TABLE `directorylaminat` ENABLE KEYS */;

-- Дамп структуры для таблица art.directoryper
CREATE TABLE IF NOT EXISTS `directoryper` (
                                              `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                              `name` varchar(50) NOT NULL,
                                              `value` smallint(6) NOT NULL,
                                              `nadb_2` float NOT NULL DEFAULT '0',
                                              `nadb_3` float NOT NULL DEFAULT '0',
                                              `nadb_5` float NOT NULL DEFAULT '0',
                                              `nadb_default` float NOT NULL DEFAULT '0',
                                              PRIMARY KEY (`ID`),
                                              UNIQUE KEY `value` (`value`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='Справочник переплетов';

-- Дамп данных таблицы art.directoryper: ~18 rows (приблизительно)
/*!40000 ALTER TABLE `directoryper` DISABLE KEYS */;
INSERT INTO `directoryper` (`ID`, `name`, `value`, `nadb_2`, `nadb_3`, `nadb_5`, `nadb_default`) VALUES
(1, '', 0, 0, 0, 0, 0),
(2, 'пружина 6,4 мм', 1, 0.3, 0.4, 0.4, 0.7),
(3, '2 пружины 6,4 мм', 14, 0.6, 0.8, 0.8, 1.4),
(4, '3 пружины 6,4 мм', 15, 0.9, 1.2, 1.2, 2.1),
(5, 'пружина 8,0 мм', 2, 0.4, 0.45, 0.45, 0.8),
(6, '2 пружина 8,0 мм', 16, 0.8, 0.9, 0.9, 1.6),
(7, '3 пружина 8,0 мм', 17, 1.2, 1.35, 1.35, 2.4),
(8, 'пружина 9,5 мм', 3, 0.5, 0.55, 0.55, 0.9),
(9, 'пружина 11,0 мм', 4, 0.55, 0.6, 0.6, 1),
(10, 'пружина 12,7 мм', 5, 0.6, 0.65, 0.65, 1.1),
(11, 'пружина 14,3 мм', 6, 0.65, 0.7, 0.7, 1.5),
(12, 'скоба', 7, 0.05, 0.1, 0.1, 0.2),
(13, 'Твердая обложка (PUR)', 8, 3.5, 5, 4, 6),
(14, 'Твердая обложка (скобы)', 9, 3.5, 5, 4, 6),
(15, 'Твердая обложка', 10, 3.5, 5, 4, 6),
(16, 'Твердая обложка (пружина)', 11, 3.5, 5, 4, 6),
(17, 'термоклей', 12, 0.5, 0.7, 0.5, 1),
(18, 'нитка', 13, 0.5, 0.7, 0.5, 1);
/*!40000 ALTER TABLE `directoryper` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

ALTER TABLE `bitrix24_template_calculation`
    ADD COLUMN `user_login` VARCHAR(30) NOT NULL COMMENT 'логин клиента во внутренней системе' AFTER `user_id`;