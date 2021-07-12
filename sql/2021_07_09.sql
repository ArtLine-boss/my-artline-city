CREATE TABLE `params` (
                          `ID` INT(11) NOT NULL AUTO_INCREMENT,
                          `key_param` VARCHAR(30) NOT NULL COMMENT 'Ключ параметра',
                          `val` VARCHAR(255) NOT NULL COMMENT 'Значение параметра',
                          PRIMARY KEY (`ID`),
                          UNIQUE INDEX `key_param` (`key_param`)
)
    COMMENT='Параметры системы'
    ENGINE=InnoDB
;
