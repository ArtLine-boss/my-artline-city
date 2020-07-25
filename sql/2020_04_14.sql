ALTER TABLE `equipment`
    ADD COLUMN `methodCalcPriceOperation` VARCHAR(255) NULL COMMENT 'Класс расчета цены операции' AFTER `total_min`;
ALTER TABLE `operations`
    ADD COLUMN `CombiOperation` SMALLINT NULL DEFAULT '0' COMMENT 'Комбинированная печать' AFTER `comments`;
UPDATE `art`.`equipment` SET `methodCalcPriceOperation`='factorys_EquipmentXeroxIridess' WHERE  `ID`=54;