<?php
    /*
     * чистит таблицу клиентов на повторения УНП
     * ссылка - http://artline.city/www/index.php?m=scripts&u=clearClients&scr=1
     */

    //объект логирования
    $date_name = API::CurrentDate(CONSTANTS::NAME_DATETIME_FORMAT);
    $log = new core_log('scripts/clearClients/data/clear_' . $date_name . '.log');

    //формируем запрос
    $where = array(
        'sql' => "UNP <> ''",
        'param' => array()
    );
    $client = new classes_clients();
    $list = $client->loadAll($where);

    //массив повторений
    $client_replay = array();

    for ($i = 0; $i < count($list); $i++) {
        if(in_array($list[$i], $client_replay))
            continue;
        $msg = '';
        for($j = ($i + 1); $j < count($list); $j++) {
            if(in_array($list[$j], $client_replay))
                continue;
            if($list[$i]->UNP == $list[$j]->UNP && $list[$i]->CLIENT_NAME == $list[$j]->CLIENT_NAME) {
                $client_replay[] = $list[$j];
                $msg .= (empty($msg) ? $list[$i]->UNP . "\t" . $list[$i]->getId() . "\t" . $list[$j]->getId() : "\t" . $list[$j]->getId());
            }
        }
        if(!empty($msg))
            $log->store($msg, false);
    }

?>