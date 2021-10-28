CREATE TABLE `webhooks` (
                            `ID` INT(11) NOT NULL AUTO_INCREMENT,
                            `name` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Наименование вебхука',
                            `url` VARCHAR(255) NOT NULL DEFAULT '' COMMENT 'Адрес',
                            PRIMARY KEY (`ID`),
                            INDEX `url` (`url`)
)
    COMMENT='Список вебхуков'
COLLATE='utf8_general_ci'
ENGINE=InnoDB
;

INSERT INTO `webhooks` (`name`, `url`) VALUES ('Редактор ежедневников', '/editor/books');

CREATE TABLE `wh_blocks` (
                               `ID` INT(11) NOT NULL AUTO_INCREMENT,
                               `name` VARCHAR(255) NOT NULL DEFAULT '',
                               `image` VARCHAR(255) NULL DEFAULT '' COMMENT 'URL к картинке',
                               PRIMARY KEY (`ID`)
)
    COMMENT='Справочник составных частей продукта'
ENGINE=InnoDB
;

INSERT INTO `wh_blocks` (`name`, `image`) VALUES ('Снаружи', '/www/webhooks/editor/books/img/cover/c_BASE.png');
INSERT INTO `wh_blocks` (`name`, `image`) VALUES ('Внутри', '/www/webhooks/editor/books/img/face/f_BASE.png');

CREATE TABLE `wh_elements` (
                               `ID` INT(11) NOT NULL AUTO_INCREMENT,
                               `name` VARCHAR(255) NOT NULL DEFAULT '',
                               PRIMARY KEY (`ID`)
)
    COMMENT='Справочник элементов продукта'
ENGINE=InnoDB
;

INSERT INTO `wh_elements` (`name`) VALUES ('Обложка');
INSERT INTO `wh_elements` (`name`) VALUES ('Пружина');
INSERT INTO `wh_elements` (`name`) VALUES ('Ляссе');
INSERT INTO `wh_elements` (`name`) VALUES ('Строчка');
INSERT INTO `wh_elements` (`name`) VALUES ('Резинка');
INSERT INTO `wh_elements` (`name`) VALUES ('Крепление для ручки');

CREATE TABLE `wh_colors` (
                               `ID` INT(11) NOT NULL AUTO_INCREMENT,
                               `name` VARCHAR(255) NOT NULL DEFAULT '',
                               `value` VARCHAR(255) NOT NULL DEFAULT '#000000',
                               PRIMARY KEY (`ID`)
)
    COMMENT='Справочник цветов'
ENGINE=InnoDB
;

INSERT INTO `wh_colors` (`name`, `value`) VALUES ('черный', '#000000');
INSERT INTO `wh_colors` (`name`, `value`) VALUES ('синий', '#1a41c1');
INSERT INTO `wh_colors` (`name`, `value`) VALUES ('зеленый', '#03720a');
INSERT INTO `wh_colors` (`name`, `value`) VALUES ('оранжевый', '#b36900');
INSERT INTO `wh_colors` (`name`, `value`) VALUES ('красный', '#d80000');
INSERT INTO `wh_colors` (`name`, `value`) VALUES ('желтый', '#c6b41b');
INSERT INTO `wh_colors` (`name`, `value`) VALUES ('серый', '#868684');


CREATE TABLE `wh_editor` (
                             `ID` INT(11) NOT NULL AUTO_INCREMENT,
                             `webhook_id` INT(11) NULL DEFAULT NULL COMMENT 'Вебхук',
                             `block_id` INT(11) NULL DEFAULT NULL COMMENT 'Блок',
                             `element_id` INT(11) NULL DEFAULT NULL COMMENT 'Элемент',
                             `color_id` INT(11) NULL DEFAULT NULL COMMENT 'Цвет',
                             `image` VARCHAR(255) NULL DEFAULT '' COMMENT 'URL к картинке',
                             PRIMARY KEY (`ID`),
                             UNIQUE INDEX `u_key` (`webhook_id`, `block_id`, `element_id`, `color_id`),
                             INDEX `webhook_id` (`webhook_id`),
                             INDEX `block_id` (`block_id`),
                             INDEX `element_id` (`element_id`),
                             INDEX `color_id` (`color_id`),
                             CONSTRAINT `FK_wh_editor_webhooks` FOREIGN KEY (`webhook_id`) REFERENCES `webhooks` (`ID`),
                             CONSTRAINT `FK_wh_editor_wh_blocks` FOREIGN KEY (`block_id`) REFERENCES `wh_blocks` (`ID`),
                             CONSTRAINT `FK_wh_editor_wh_colors` FOREIGN KEY (`color_id`) REFERENCES `wh_colors` (`ID`),
                             CONSTRAINT `FK_wh_editor_wh_elements` FOREIGN KEY (`element_id`) REFERENCES `wh_elements` (`ID`)
)
    COMMENT='Настройки редактора'
ENGINE=InnoDB
;

INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 3, 1, '/www/webhooks/editor/books/img/cover/c_bookmark-black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 3, 2, '/www/webhooks/editor/books/img/cover/c_bookmark-blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 3, 3, '/www/webhooks/editor/books/img/cover/c_bookmark-green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 3, 4, '/www/webhooks/editor/books/img/cover/c_bookmark-orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 3, 5, '/www/webhooks/editor/books/img/cover/c_bookmark-red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 3, 6, '/www/webhooks/editor/books/img/cover/c_bookmark-yellow.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 5, 1, '/www/webhooks/editor/books/img/cover/c_elastic_black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 5, 2, '/www/webhooks/editor/books/img/cover/c_elastic_blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 5, 3, '/www/webhooks/editor/books/img/cover/c_elastic_green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 5, 4, '/www/webhooks/editor/books/img/cover/c_elastic_orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 5, 5, '/www/webhooks/editor/books/img/cover/c_elastic_red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 5, 6, '/www/webhooks/editor/books/img/cover/c_elastic_yellow.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 6, 1, '/www/webhooks/editor/books/img/cover/c_pen_black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 6, 2, '/www/webhooks/editor/books/img/cover/c_pen_blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 6, 3, '/www/webhooks/editor/books/img/cover/c_pen_green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 6, 4, '/www/webhooks/editor/books/img/cover/c_pen_orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 6, 5, '/www/webhooks/editor/books/img/cover/c_pen_red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 6, 6, '/www/webhooks/editor/books/img/cover/c_pen_yellow.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 1, 3, '/www/webhooks/editor/books/img/cover/c_skin-1.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 1, 6, '/www/webhooks/editor/books/img/cover/c_skin-2.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 1, 4, '/www/webhooks/editor/books/img/cover/c_skin-3.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 1, 7, '/www/webhooks/editor/books/img/cover/c_skin-4.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 2, 1, '/www/webhooks/editor/books/img/cover/c_spring_black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 2, 2, '/www/webhooks/editor/books/img/cover/c_spring_blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 2, 3, '/www/webhooks/editor/books/img/cover/c_spring_green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 2, 4, '/www/webhooks/editor/books/img/cover/c_spring_orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 2, 5, '/www/webhooks/editor/books/img/cover/c_spring_red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 2, 6, '/www/webhooks/editor/books/img/cover/c_spring_yellow.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 4, 1, '/www/webhooks/editor/books/img/cover/c_stroke-black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 4, 2, '/www/webhooks/editor/books/img/cover/c_stroke-blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 4, 3, '/www/webhooks/editor/books/img/cover/c_stroke-green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 4, 4, '/www/webhooks/editor/books/img/cover/c_stroke-orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 4, 5, '/www/webhooks/editor/books/img/cover/c_stroke-red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 1, 4, 6, '/www/webhooks/editor/books/img/cover/c_stroke-yellow.png');


INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 3, 1, '/www/webhooks/editor/books/img/face/f_bookmark-black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 3, 2, '/www/webhooks/editor/books/img/face/f_bookmark-blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 3, 3, '/www/webhooks/editor/books/img/face/f_bookmark-green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 3, 4, '/www/webhooks/editor/books/img/face/f_bookmark-orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 3, 5, '/www/webhooks/editor/books/img/face/f_bookmark-red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 3, 6, '/www/webhooks/editor/books/img/face/f_bookmark-yellow.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 6, 1, '/www/webhooks/editor/books/img/face/f_pen-black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 6, 2, '/www/webhooks/editor/books/img/face/f_pen-blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 6, 3, '/www/webhooks/editor/books/img/face/f_pen-green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 6, 4, '/www/webhooks/editor/books/img/face/f_pen-orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 6, 5, '/www/webhooks/editor/books/img/face/f_pen-red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 6, 6, '/www/webhooks/editor/books/img/face/f_pen-yellow.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 1, 7, '/www/webhooks/editor/books/img/face/f_skin-1.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 1, 3, '/www/webhooks/editor/books/img/face/f_skin-2.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 1, 6, '/www/webhooks/editor/books/img/face/f_skin-3.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 1, 4, '/www/webhooks/editor/books/img/face/f_skin-4.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 2, 1, '/www/webhooks/editor/books/img/face/f_spring-black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 2, 2, '/www/webhooks/editor/books/img/face/f_spring-blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 2, 3, '/www/webhooks/editor/books/img/face/f_spring-green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 2, 4, '/www/webhooks/editor/books/img/face/f_spring-orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 2, 5, '/www/webhooks/editor/books/img/face/f_spring-red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 2, 6, '/www/webhooks/editor/books/img/face/f_spring-yellow.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 4, 1, '/www/webhooks/editor/books/img/face/f_stroke-black.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 4, 2, '/www/webhooks/editor/books/img/face/f_stroke-blue.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 4, 3, '/www/webhooks/editor/books/img/face/f_stroke-green.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 4, 4, '/www/webhooks/editor/books/img/face/f_stroke-orange.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 4, 5, '/www/webhooks/editor/books/img/face/f_stroke-red.png');
INSERT INTO `wh_editor` (`webhook_id`, `block_id`, `element_id`, `color_id`, `image`) VALUES (1, 2, 4, 6, '/www/webhooks/editor/books/img/face/f_stroke-yellow.png');