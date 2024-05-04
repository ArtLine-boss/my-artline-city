<?php
$ajaxObject = new core_Dto();
do {
    // Параметры отчета
    // Месяц
    $month = new DateTime($_POST['month'] ? $_POST['month'] . '-01' : 'first day of last month');
    $dateFrom = $month->format('Y-m-d');
    $dateTo = $month->add(DateInterval::createFromDateString('1 month'))->format('Y-m-d');
    // Дизайнер
    $userNameStr = $_POST['username'] ? " AND lt_old.user_log = '" . $_POST['username'] . "'" : "";

    $sql = "
        SELECT 
            design.ORDER_ID,
            users.USER_FIO username,
            ROUND(IFNULL(design.sum_design, 0), 2) sum_design,
            ROUND(IFNULL(order_data.sum_all, 0), 2) sum_all,
            ROUND(IFNULL(oplati_before.sum_oplati_before, 0), 2) sum_oplati_before,
            ROUND(IFNULL(oplati_after.sum_oplati_after, 0), 2) sum_oplati_after,
            ROUND(IFNULL(oplati_before.sum_oplati_before, 0), 2) + ROUND(IFNULL(oplati_after.sum_oplati_after, 0), 2) sum_oplati_all
        FROM
            (
                SELECT 
                    order_product.ORDER_ID,
                    lt_list.user_log, 
                    SUM(order_product.SUMM) sum_design
                FROM
                    (
                        SELECT 
                            lt_new.id_prod,
                            lt_old.user_log
                        FROM
                            (
                                SELECT *
                                FROM log_task
                                WHERE status_new = 10
                            ) lt_new
                        JOIN 
                            (
                                SELECT *
                                FROM log_task
                                WHERE status_old = 10
                            ) lt_old 
                            ON lt_new.id_prod = lt_old.id_prod 
                                AND lt_new.`datetime` < lt_old.`datetime`" . $userNameStr . "
                        GROUP BY lt_new.id_prod
                        ORDER BY lt_new.id_prod
                    ) lt_list
                JOIN order_product 
                    ON lt_list.id_prod = order_product.ID 
                        AND order_product.`status` IN (2,3) 
                        AND order_product.dates_rdy >= '" . $dateFrom . "' 
                        AND order_product.dates_rdy < '" . $dateTo . "'
                GROUP BY order_product.ORDER_ID
                ORDER BY order_product.ORDER_ID
            ) design
        JOIN users
	        ON users.USER_LOGIN = design.user_log
        JOIN 
            (
                SELECT 
                    order_product.ORDER_ID, 
                    SUM(order_product.SUMM) sum_all
                FROM order_product
                GROUP BY order_product.ORDER_ID
            ) order_data ON order_data.ORDER_ID = design.ORDER_ID
        LEFT JOIN 
            (
                SELECT 
                    oplati.ORDER_NUM, 
                    SUM(oplati.ALL_SUM) sum_oplati_before
                FROM oplati
                WHERE oplati.DATE_ < '" . $dateTo . "'
                GROUP BY oplati.ORDER_NUM
            ) oplati_before ON oplati_before.ORDER_NUM = design.ORDER_ID
        LEFT JOIN 
            (
                SELECT 
                    oplati.ORDER_NUM, 
                    SUM(oplati.ALL_SUM) sum_oplati_after
                FROM oplati
                WHERE oplati.DATE_ >= '" . $dateTo . "'
                GROUP BY oplati.ORDER_NUM
            ) oplati_after ON oplati_after.ORDER_NUM = design.ORDER_ID
        HAVING sum_all > sum_oplati_before
    ";

    $ajaxObject->setData(factorys_classes::getObject('core_Entity')
        ->select($sql));

} while (false);