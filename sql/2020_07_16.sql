INSERT INTO `art`.`accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('32', 'Счета', 'ACCESS_PAGE_ORDERS', '/www/index.php?m=orders', '1', 'fa fa-shopping-bag u-mr-xsmall');

INSERT INTO `art`.`accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('33', 'Заказы в работе', 'ACCESS_PAGE_ORDERS_ACTIVE', '/www/index.php?m=orders&u=active', '1', 'fa fa-shopping-cart u-mr-xsmall');

INSERT INTO `art`.`accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('34', 'Отчет по менеджерам', 'ACCESS_PAGE_ORDERS_REPORT_MANAGER', '/www/index.php?m=orders&u=reportManager', '1', 'fa fa-line-chart u-mr-xsmall');

INSERT INTO `art`.`accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('35', 'Оплачено но не в работе', 'ACCESS_PAGE_ORDERS_NOTWORK', '/www/index.php?m=orders&u=notWork', '1', 'fa fa-cc-mastercard u-mr-xsmall');

UPDATE `art`.`accessmenu` SET `url`='' WHERE  `ID`=1;

INSERT INTO `art`.`accesslevel` (`ID`, `category_id`, `name`, `access_code`) VALUES ('36', '1', 'Доступ к странице заказов в работе', 'ACCESS_PAGE_ORDERS_ACTIVE');
INSERT INTO `art`.`accesslevel` (`ID`, `category_id`, `name`, `access_code`) VALUES ('37', '1', 'Доступ к странице отчета по менеджерам', 'ACCESS_PAGE_ORDERS_REPORT_MANAGER');
INSERT INTO `art`.`accesslevel` (`ID`, `category_id`, `name`, `access_code`) VALUES ('38', '1', 'Доступ к странице оплаченных заказов но не в работе', 'ACCESS_PAGE_ORDERS_NOTWORK');

INSERT INTO `art`.`accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('36', 'Продукты', 'ACCESS_PAGE_ORDERS_PRODUCTS', '/www/index.php?m=orders&u=products', '1', 'fa fa-product-hunt u-mr-xsmall');

INSERT INTO `art`.`accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('37', 'Оплаты', 'ACCESS_PAGE_ORDERS_PAYMENTS', '/www/index.php?m=orders&u=payments', '1', 'fa fa-paypal u-mr-xsmall');

INSERT INTO `art`.`accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('38', 'ТН', 'ACCESS_PAGE_ORDERS_TN', '/www/index.php?m=orders&u=tn', '1', 'fa fa-truck u-mr-xsmall');

INSERT INTO `art`.`accessmenu` (`ID`, `name`, `accessValue`, `url`, `parent_id`, `icon`)
VALUES ('39', 'Доставка', 'ACCESS_PAGE_ORDERS_POST', '/www/index.php?m=orders&u=post', '1', 'fa fa-envelope u-mr-xsmall');

INSERT INTO `art`.`accesslevel` (`ID`, `category_id`, `name`, `access_code`) VALUES ('39', '1', 'Доступ к странице заказов (продукты)', 'ACCESS_PAGE_ORDERS_PRODUCTS');
INSERT INTO `art`.`accesslevel` (`ID`, `category_id`, `name`, `access_code`) VALUES ('40', '1', 'Доступ к странице заказов (оплаты)', 'ACCESS_PAGE_ORDERS_PAYMENTS');
INSERT INTO `art`.`accesslevel` (`ID`, `category_id`, `name`, `access_code`) VALUES ('41', '1', 'Доступ к странице заказов (ТН)', 'ACCESS_PAGE_ORDERS_TN');
INSERT INTO `art`.`accesslevel` (`ID`, `category_id`, `name`, `access_code`) VALUES ('42', '1', 'Доступ к странице заказов (Доставка)', 'ACCESS_PAGE_ORDERS_POST');