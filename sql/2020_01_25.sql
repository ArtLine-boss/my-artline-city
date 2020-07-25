/** ВЫГРУЖЕНО */
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

-- Дамп структуры для таблица art.directory_status_item
CREATE TABLE IF NOT EXISTS `directory_status_item` (
                                                       `ID` int(11) NOT NULL,
                                                       `name` varchar(50) NOT NULL,
                                                       PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Справочник статусов заявки';

-- Дамп данных таблицы art.directory_status_item: ~9 rows (приблизительно)
/*!40000 ALTER TABLE `directory_status_item` DISABLE KEYS */;
INSERT INTO `directory_status_item` (`ID`, `name`) VALUES
(1, 'Цех'),
(2, 'Готово'),
(3, 'Отдано заказчику'),
(4, 'Брак'),
(10, 'Дизайн'),
(11, 'Препресс'),
(12, 'Печать'),
(20, 'Возврат'),
(21, 'Возврат');
/*!40000 ALTER TABLE `directory_status_item` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;


CREATE TABLE `order_items` (
                               `ID` INT(11) NOT NULL AUTO_INCREMENT,
                               `order_id` INT(11) NOT NULL COMMENT 'Заказ',
                               `item_id` INT(11) NULL DEFAULT NULL COMMENT 'номер в заказе',
                               `code_stat` INT(11) NULL DEFAULT NULL COMMENT 'код для статистики',
                               `add_date` DATETIME NULL DEFAULT NULL COMMENT 'дата добавления',
                               `dates_rdy` DATETIME NOT NULL COMMENT 'дата готовности',
                               `status_item` INT(11) NOT NULL COMMENT 'текущий статус заявки',
                               `name_item` VARCHAR(256) NOT NULL COMMENT 'наименование',
                               `size` VARCHAR(50) NOT NULL COMMENT 'размер',
                               `total` FLOAT NOT NULL COMMENT 'кол-во',
                               `units` INT(11) NOT NULL COMMENT 'ед измерения',
                               `price` FLOAT NOT NULL COMMENT 'цена',
                               `summ_sys` FLOAT NOT NULL COMMENT 'просчет системы',
                               `summ` FLOAT NOT NULL COMMENT 'сумма',
                               `view_diz` VARCHAR(256) NOT NULL COMMENT 'список кодов дизайна',
                               `view_press` VARCHAR(256) NOT NULL COMMENT 'есть ли препресс',
                               `fast` VARCHAR(50) NOT NULL COMMENT 'Срочность ',
                               `template` VARCHAR(4096) NOT NULL COMMENT 'исходная форма',
                               `template_calc` VARCHAR(4096) NOT NULL COMMENT 'полный расчет',
                               `press_diz` VARCHAR(256) NOT NULL COMMENT 'папка для файлов печати',
                               `print_diz` VARCHAR(256) NOT NULL COMMENT 'папка для файлов препресса',
                               `cl_file` VARCHAR(256) NOT NULL COMMENT 'файлы клиента',
                               `comment` VARCHAR(4096) NULL DEFAULT NULL COMMENT 'комментарий',
                               PRIMARY KEY (`ID`),
                               INDEX `status_item` (`status_item`),
                               INDEX `order_id` (`order_id`),
                               INDEX `item_id` (`item_id`),
                               INDEX `units` (`units`),
                               CONSTRAINT `FK_order_items_directory_status_item` FOREIGN KEY (`status_item`) REFERENCES `directory_status_item` (`ID`),
                               CONSTRAINT `FK_order_items_orders` FOREIGN KEY (`order_id`) REFERENCES `orders` (`NUMBER`),
                               CONSTRAINT `FK_order_items_units` FOREIGN KEY (`units`) REFERENCES `units` (`ID`)
)
    COMMENT='Продукты к заказу'
    COLLATE='utf8_general_ci'
    ENGINE=InnoDB
;

