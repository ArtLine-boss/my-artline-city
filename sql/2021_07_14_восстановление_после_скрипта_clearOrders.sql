SET FOREIGN_KEY_CHECKS = 0;

# orders
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_orders`;
CREATE TEMPORARY TABLE `art2`.`tmp_orders` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_orders`
SELECT `art3`.`orders`.`NUMBER` FROM `art3`.`orders` LEFT JOIN `art2`.`orders` ON `art2`.`orders`.`NUMBER` = `art3`.`orders`.`NUMBER` WHERE `art2`.`orders`.`NUMBER` IS NULL;
INSERT INTO `art2`.`orders` SELECT * FROM `art3`.`orders` WHERE `art3`.`orders`.`NUMBER` IN (SELECT * FROM `art2`.`tmp_orders`);

# order_product
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_order_product`;
CREATE TEMPORARY TABLE `art2`.`tmp_order_product` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_order_product`
SELECT `art3`.`order_product`.`ID` FROM `art3`.`order_product` LEFT JOIN `art2`.`order_product` ON `art2`.`order_product`.`ID` = `art3`.`order_product`.`ID` WHERE `art2`.`order_product`.`ID` IS NULL;
INSERT INTO `art2`.`order_product` SELECT * FROM `art3`.`order_product` WHERE `art3`.`order_product`.`ID` IN (SELECT * FROM `art2`.`tmp_order_product`);

# tn_list_par
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_tn_list_par`;
CREATE TEMPORARY TABLE `art2`.`tmp_tn_list_par` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_tn_list_par`
SELECT `art3`.`tn_list_par`.`ID` FROM `art3`.`tn_list_par` LEFT JOIN `art2`.`tn_list_par` ON `art2`.`tn_list_par`.`ID` = `art3`.`tn_list_par`.`ID` WHERE `art2`.`tn_list_par`.`ID` IS NULL;
INSERT INTO `art2`.`tn_list_par` SELECT * FROM `art3`.`tn_list_par` WHERE `art3`.`tn_list_par`.`ID` IN (SELECT * FROM `art2`.`tmp_tn_list_par`);

# tn_list
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_tn_list`;
CREATE TEMPORARY TABLE `art2`.`tmp_tn_list` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_tn_list`
SELECT `art3`.`tn_list`.`ID` FROM `art3`.`tn_list` LEFT JOIN `art2`.`tn_list` ON `art2`.`tn_list`.`ID` = `art3`.`tn_list`.`ID` WHERE `art2`.`tn_list`.`ID` IS NULL;
INSERT INTO `art2`.`tn_list` SELECT * FROM `art3`.`tn_list` WHERE `art3`.`tn_list`.`ID` IN (SELECT * FROM `art2`.`tmp_tn_list`);

# plan_job
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_plan_job`;
CREATE TEMPORARY TABLE `art2`.`tmp_plan_job` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_plan_job`
SELECT `art3`.`plan_job`.`ID` FROM `art3`.`plan_job` LEFT JOIN `art2`.`plan_job` ON `art2`.`plan_job`.`ID` = `art3`.`plan_job`.`ID` WHERE `art2`.`plan_job`.`ID` IS NULL;
INSERT INTO `art2`.`plan_job` SELECT * FROM `art3`.`plan_job` WHERE `art3`.`plan_job`.`ID` IN (SELECT * FROM `art2`.`tmp_plan_job`);

# order_items
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_order_items`;
CREATE TEMPORARY TABLE `art2`.`tmp_order_items` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_order_items`
SELECT `art3`.`order_items`.`ID` FROM `art3`.`order_items` LEFT JOIN `art2`.`order_items` ON `art2`.`order_items`.`ID` = `art3`.`order_items`.`ID` WHERE `art2`.`order_items`.`ID` IS NULL;
INSERT INTO `art2`.`order_items` SELECT * FROM `art3`.`order_items` WHERE `art3`.`order_items`.`ID` IN (SELECT * FROM `art2`.`tmp_order_items`);

# orders_check
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_orders_check`;
CREATE TEMPORARY TABLE `art2`.`tmp_orders_check` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_orders_check`
SELECT `art3`.`orders_check`.`ID` FROM `art3`.`orders_check` LEFT JOIN `art2`.`orders_check` ON `art2`.`orders_check`.`ID` = `art3`.`orders_check`.`ID` WHERE `art2`.`orders_check`.`ID` IS NULL;
INSERT INTO `art2`.`orders_check` SELECT * FROM `art3`.`orders_check` WHERE `art3`.`orders_check`.`ID` IN (SELECT * FROM `art2`.`tmp_orders_check`);

# oplati
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_oplati`;
CREATE TEMPORARY TABLE `art2`.`tmp_oplati` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_oplati`
SELECT `art3`.`oplati`.`ID` FROM `art3`.`oplati`
LEFT JOIN `art2`.`oplati` ON `art2`.`oplati`.`ID` = `art3`.`oplati`.`ID` WHERE `art2`.`oplati`.`ID` IS NULL AND `art3`.`oplati`.`view_opl` <> 4;
INSERT INTO `art2`.`oplati` SELECT * FROM `art3`.`oplati` WHERE `art3`.`oplati`.`ID` IN (SELECT * FROM `art2`.`tmp_oplati`);

# log_task
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_log_task`;
CREATE TEMPORARY TABLE `art2`.`tmp_log_task` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_log_task`
SELECT `art3`.`log_task`.`id` FROM `art3`.`log_task` LEFT JOIN `art2`.`log_task` ON `art2`.`log_task`.`id` = `art3`.`log_task`.`id` WHERE `art2`.`log_task`.`id` IS NULL;
INSERT INTO `art2`.`log_task` SELECT * FROM `art3`.`log_task` WHERE `art3`.`log_task`.`id` IN (SELECT * FROM `art2`.`tmp_log_task`);

# lock_task
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_lock_task`;
CREATE TEMPORARY TABLE `art2`.`tmp_lock_task` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_lock_task`
SELECT `art3`.`lock_task`.`id` FROM `art3`.`lock_task` LEFT JOIN `art2`.`lock_task` ON `art2`.`lock_task`.`id` = `art3`.`lock_task`.`id` WHERE `art2`.`lock_task`.`id` IS NULL;
INSERT INTO `art2`.`lock_task` SELECT * FROM `art3`.`lock_task` WHERE `art3`.`lock_task`.`id` IN (SELECT * FROM `art2`.`tmp_lock_task`);

# bitrix24_template_calculation
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_bitrix24_template_calculation`;
CREATE TEMPORARY TABLE `art2`.`tmp_bitrix24_template_calculation` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_bitrix24_template_calculation`
SELECT `art3`.`bitrix24_template_calculation`.`id` FROM `art3`.`bitrix24_template_calculation`
LEFT JOIN `art2`.`bitrix24_template_calculation` ON `art2`.`bitrix24_template_calculation`.`id` = `art3`.`bitrix24_template_calculation`.`id` WHERE `art2`.`bitrix24_template_calculation`.`id` IS NULL;
INSERT INTO `art2`.`bitrix24_template_calculation` SELECT * FROM `art3`.`bitrix24_template_calculation` WHERE `art3`.`bitrix24_template_calculation`.`id` IN (SELECT * FROM `art2`.`tmp_bitrix24_template_calculation`);

# artliner_report
DROP TEMPORARY TABLE IF EXISTS `art2`.`tmp_artliner_report`;
CREATE TEMPORARY TABLE `art2`.`tmp_artliner_report` (`id` int(11) unsigned NOT NULL, PRIMARY KEY (`id`));
INSERT INTO `art2`.`tmp_artliner_report`
SELECT `art3`.`artliner_report`.`id` FROM `art3`.`artliner_report`
LEFT JOIN `art2`.`artliner_report` ON `art2`.`artliner_report`.`id` = `art3`.`artliner_report`.`id` WHERE `art2`.`artliner_report`.`id` IS NULL;
INSERT INTO `art2`.`artliner_report` SELECT * FROM `art3`.`artliner_report` WHERE `art3`.`artliner_report`.`id` IN (SELECT * FROM `art2`.`tmp_artliner_report`);

SET FOREIGN_KEY_CHECKS = 1;