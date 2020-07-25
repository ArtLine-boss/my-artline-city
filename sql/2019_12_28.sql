ALTER TABLE `oplati`
    ADD COLUMN `doc_date` DATE NULL COMMENT 'Дата выписки' AFTER `Comments`,
    ADD COLUMN `doc_num` INT(11) NULL COMMENT 'Номер выписки' AFTER `doc_date`,
    ADD COLUMN `doc_payment_num` INT(11) NULL COMMENT 'Номер позиции в выписке' AFTER `doc_num`;
