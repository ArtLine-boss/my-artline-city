<?php
class cron_clearOrders_Manager extends core_Singleton {
    /** @var core_log $log */
    private $log = null;
    /** Лимит на количество */
    const LIMIT_ORDERS = 500;
    /** Количество месяцев оставляемых сделок */
    const MONTHLY_BY_ORDERS = 6;
    /** Количество месяцев оставляемых оплат наличкой */
    const MONTHLY_BY_PAYMENTS = 2;
    private $time_start = 0;
    private $is_reset_last = false;

    /** Запуск скрипта */
    public function run() {
        try {
            $this->time_start = microtime(true);
            $msg = null;
            $this->log = new core_log('cron/clearOrders/process.log');

            do {
                /** Удаляем сделки */
                if(null !== ($msg = $this->clearOrders())) break;
                /** Удаляем оплаты */
                if(null !== ($msg = $this->clearPayments())) break;
            } while (false);

            if ($msg !== null) {
                $this->log->store('ERROR! ' . $msg);
            }
        } catch (Exception $e) {
            $log = new core_log('cron/clearOrders/exception.log');
            $log->store($e->getCode() . ": " . $e->getMessage() . ". Строка: " . $e->getLine());
        }
    }

    /**
     * Очистка сделок
     * @return string|null
     */
    private function clearOrders() {
        $msgs = [];

        do {
            // Определяем крайнюю дату
            $time = strtotime(API::CurrentDate(CONSTANTS::DB_DATE_FORMAT) . ' -' . static::MONTHLY_BY_ORDERS . ' months');
            $date = date("Y-m-01", $time);

            $last_order_by_clear = entity_params::get('LAST_ORDER_BY_CLEAR')->val;
            $last_order_by_clear = empty($last_order_by_clear) ? 0 : intval($last_order_by_clear);
            $orders = classes_orders::all(['sql' => '`NUMBER` > ' . $last_order_by_clear . ' AND `DATE_OR` <= \'' . $date . '\''], 'NUMBER', 0, static::LIMIT_ORDERS);
            if(count($orders) == 0) {
                if($this->is_reset_last === true) return null;
                $this->is_reset_last = true;
                entity_params::set('LAST_ORDER_BY_CLEAR', '00');
                $_msg = $this->clearOrders();
                $this->log->store('Удаление заказов. Обработано заказов - 0. Время выполнения - ' . $this->getTime());
                return $_msg;
            }

            $count_orders = 0;
            foreach ($orders as $k => $order) {
                /** @var classes_orders $order */
                if(null !== ($m = $this->clearOrder($order, $date))) {
                    $msgs[] = 'Запись ' . $k . '. ' . $m . '. Объект: ' . json_encode($order);
                }
                $count_orders++;
                entity_params::set('LAST_ORDER_BY_CLEAR', $order->getId());
            }
            $this->log->store('Удаление заказов. Обработано заказов - ' . $count_orders . '. Время выполнения - ' . $this->getTime());

        } while(false);

        $msg = null;
        if(count($msgs) > 0) {
            $msg = '';
            foreach ($msgs as $m) {
                $msg .= $m . PHP_EOL;
            }
        }
        return $msg;
    }

    /**
     * Удаление сделки с привязками
     * @param classes_orders $order
     * @param string $date
     * @return string|null
     */
    private function clearOrder($order, $date) {
        $msg = null;

        do {
            if(!$order->getInit()) {
                $msg = 'Объект не инициализирован';
                break;
            }

            // Определяем продукты
            $products = $order->getProducts();
            $id_products = [];
            foreach ($products as $product) {
                /** @var classes_orderProduct $product */
                if(strtotime($product->dates_rdy) > strtotime($date)/* || $product->dates_rdy == '0000-00-00 00:00:00' || !in_array($product->status, [3, 4])*/) break(2);
                $id_products[] = $product->getId();
            }
            $is_pr = count($id_products) > 0;

            // Удаляем каталоги с файлами
            $path = $_SERVER['DOCUMENT_ROOT'] . '/pages/pg/files/prod/' . $order->getId();
            API::removeDirectory($path);

            //return;

            // Массив запросов
            $query = [];

            // Удаления связных данных в таблицах
            $query[] = 'DELETE FROM `artliner_report` WHERE `artliner_report`.`id_order` IN (' . $order->getId() . ');';
            $query[] = 'DELETE FROM `bitrix24_template_calculation` WHERE `bitrix24_template_calculation`.`order_id` IN (' . $order->getId() . ');';
            if($is_pr) $query[] = 'DELETE FROM `lock_task` WHERE `lock_task`.`id_prod` IN (' . implode(',', $id_products) . ');';
            if($is_pr) $query[] = 'DELETE FROM `log_task` WHERE `log_task`.`id_prod` IN (' . implode(',', $id_products) . ');';
            $query[] = 'DELETE FROM `oplati` WHERE `oplati`.`ORDER_NUM` IN (' . $order->getId() . ');';
            $query[] = 'DELETE FROM `orders_check` WHERE `orders_check`.`number_check` IN (' . $order->getId() . ');';
            $query[] = 'DELETE FROM `order_items` WHERE `order_items`.`order_id` IN (' . $order->getId() . ');';
            if($is_pr) $query[] = 'DELETE FROM `plan_job` WHERE `plan_job`.`ID_PROD` IN (' . implode(',', $id_products) . ');';
            $query[] = 'DELETE FROM `tn_list` WHERE `tn_list`.`order_id` IN (' . $order->getId() . ');';
            $query[] = 'DELETE FROM `tn_list_par` WHERE `tn_list_par`.`order_id` IN (' . $order->getId() . ');';
            $query[] = 'DELETE FROM `order_product` WHERE `order_product`.`ORDER_ID` IN (' . $order->getId() . ');';
            $query[] = 'DELETE FROM `orders` WHERE `orders`.`NUMBER` IN (' . $order->getId() . ');';

            if(null !== ($msg = $this->exec($query))) break;

            // Чистим лог оплат
            $query = [];
            $query[] = 'DELETE FROM `log_oplati` WHERE `log_oplati`.`ORDER_NUM` IN (' . $order->getId() . ');';
            if(null !== ($msg = $this->exec($query))) break;

        } while(false);

        return $msg;
    }

    /**
     * Очистка оплат
     * @return string|null
     */
    private function clearPayments() {
        $msg = null;

        do {
            $time = strtotime(API::CurrentDate(CONSTANTS::DB_DATE_FORMAT) . ' -' . static::MONTHLY_BY_PAYMENTS . ' months');
            $date = date("Y-m-01", $time);

            $query = [];
            $query[] = 'DELETE FROM `oplati` WHERE `oplati`.`view_opl` = 4 AND `oplati`.`DATE_` < \'' . $date . '\';';

            if(null !== ($msg = $this->exec($query))) break;

            // Чистим лог оплат
            $query = [];
            $query[] = 'DELETE FROM `log_oplati` WHERE  `log_oplati`.`view_opl` = 4 AND `oplati`.`DATE_` < \'' . $date . '\';';
            if(null !== ($msg = $this->exec($query))) break;

            $this->log->store('Удаление оплат. Оплаты удалены. Время выполнения - ' . $this->getTime());
        } while(false);

        return $msg;
    }

    /**
     * Выполнение стека запросов
     * @param array $arr
     * @return string|null
     */
    private function exec($arr) {
        $msg = null;

        do {
            // Старт сессии и отключение внешних ключей
            $query[] = 'START TRANSACTION;';
            $query[] = 'SET FOREIGN_KEY_CHECKS=0;';

            $query = array_merge($query, $arr);

            // Включаем внешние ключи и делаем коммит
            $query[] = 'SET FOREIGN_KEY_CHECKS=1;';
            $query[] = 'COMMIT;';

            $query_str = implode(PHP_EOL, $query);

            $query_res = factorys_classes::getObject('classes_orderProduct')->exec($query_str);

            if($query_res == -1) {
                $msg = 'Не удалось выполнить запрос. Внутренняя ошибка.' . PHP_EOL . $query_str;
                break;
            }
        } while(false);

        return $msg;
    }

    /**
     * Возврат пройденого времени
     * @return string
     */
    private function getTime() {
        if(empty($this->time_start)) {
            $this->time_start = microtime(true);
            return '00:00:00';
        }

        $time = microtime(true) - $this->time_start;
        $this->time_start = microtime(true);

        $sec = $time % 60;
        $time = floor($time / 60);
        $min = $time % 60;
        $time = floor($time / 60);
        return ($time > 9 ? $time : '0' . $time) . ':' . ($min > 9 ? $min : '0' . $min) . ':' . ($sec > 9 ? $sec : '0' . $sec);

    }

}
?>