INSERT INTO accessmenu (name, accessValue, url, parent_id, icon)
VALUES ('Дизайн без оплаты', 'ACCESS_PAGE_REPORTS_DESIGNS_NO_PAYMENT', '/www/index.php?m=reports&u=designNoPayment', 12, 'fa fa-book');

INSERT INTO accesslevel (ID, category_id, name, access_code)
VALUES (44, 1, 'Доступ к отчету по дизайну без оплаты', 'ACCESS_PAGE_REPORTS_DESIGNS_NO_PAYMENT');

INSERT INTO accessrole(level_id, user_id, date_start)
VALUES (44, 'admins', '2024-05-04'),
        (44, 'admin', '2024-05-04');