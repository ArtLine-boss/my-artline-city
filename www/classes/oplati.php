<?php
class classes_oplati extends core_DBObject {
    var $CLIENT_ID = null;
    var $ORDER_NUM = null;
    var $OST_SUM = null;
    var $ALL_SUM = null;
    var $DATE_ = null;
    var $view_opl = null; //вид оплаты: 1- касса 2 терминал 3 безнал 4 наличные 5 расчет с поставщиками
    var $Comments = null;
    var $doc_date = null;
    var $doc_num = null;
    var $doc_payment_num = null;

    public function __construct()
    {
        parent::__construct('oplati', 'ID', 'classes_oplati');
    }

    //конструктор для кроновского скрипта
    public function bindByScript($hash = null, $filename = '', &$log = null) {
        $msg = null;

        do {
            if(empty($hash) || !is_array($hash) || count($hash) == 0 || empty($log)) {
                $msg = 'Пустой входной массив';
                break;
            }
            //данные выписки
            $this->doc_date = !empty($hash[0]) ? API::FormatDate($hash[0], CONSTANTS::DB_DATE_FORMAT) : null;
            $this->doc_num = !empty($hash[1]) ? $hash[1] : null;
            $this->doc_payment_num = !empty($hash[2]) ? intval($hash[2]) : null;
            //проверяем данные выписки
            if(null !== ($msg = $this->checkDoc())) {
                $msg = 'Документ №' . $this->doc_num . '(' . $this->doc_date . '). Строка ' . $this->doc_payment_num . ': ' . $msg;
                break;
            }
            //определяем клиента
            $client = new classes_clients();
            //если УНП совпадает с мечтой и УНП не найден, то ищем по имени
            if(!in_array($hash[3], array('291546971')) && null !== ($msg = $client->searchByUNP($hash[3]))) {
                //ищем клиента по имени
                if(null !== ($msg = $client->searchByClientName($hash[4]))) {
                    //создаем нового клиента
                    unset($client);
                    $client = new classes_clients();
                    $client->CLIENT_NAME = $hash[4];
                    $client->UNP = !empty($hash[3]) ? $hash[3] : null;
                    $client->CLIENT_STATUS = !empty($client->UNP) ? 'u' : 'f';
                    if(empty($client->CLIENT_NAME)) {
                        $msg = 'Входное значение имени клиента пустое';
                        $msg = 'Документ №' . $this->doc_num . '(' . $this->doc_date . '). Строка ' . $this->doc_payment_num . ': ' . $msg;
                        break;
                    }
                    if(null !== ($msg = $client->store())) {
                        $msg = 'Документ №' . $this->doc_num . '(' . $this->doc_date . '). Строка ' . $this->doc_payment_num . ': ' . $msg;
                        break;
                    }
                }
            }
            $this->CLIENT_ID = $client->getId();
            //определяем номер заказа
            $this->ORDER_NUM = !empty($hash[5]) ? $hash[5] : null;
            $order = new classes_orders();
            if(empty($this->ORDER_NUM) || null !== ($msg = $order->loadById($this->ORDER_NUM))) {
                $msg = empty($this->ORDER_NUM) ? 'Пустой номер заявки' : $msg;
                $msg = 'Документ №' . $this->doc_num . '(' . $this->doc_date . '). Строка ' . $this->doc_payment_num . ': ' . $msg;
                $log->store($msg);
                $msg = null;
                $this->ORDER_NUM = 0;
            } else {
                //для мечты пишем клиента из заказа
                if(in_array($hash[3], array('291546971'))) {
                    $this->CLIENT_ID = $order->CLIENT_ID;
                }
                //проверяем или сходится клиент
                else if($order->CLIENT_ID != $this->CLIENT_ID)
                    $this->ORDER_NUM = 0;
            }

            //сумма
            $this->OST_SUM = (!empty($hash[6]) && $hash[7] == 5) ? floatval($hash[6]) : 0;
            $this->ALL_SUM = (!empty($hash[6]) && $hash[7] == 3) ? floatval($hash[6]) : 0;
            if(empty($this->ALL_SUM) && empty($this->OST_SUM)) {
                $msg = 'Пустая сумма оплаты либо неверно указан тип оплаты';
                break;
            }
            //дата оплаты
            $this->DATE_ = API::CurrentDate(CONSTANTS::DB_DATE_FORMAT);
            //вид оплаты
            $this->view_opl = !empty($hash[7]) ? $hash[7] : 3;
            //комментарий
            $this->Comments = (!empty($filename) ? $filename . ', ' : '') . $hash[4];

        } while(false);

        return $msg;
    }

    //проверка или есть уже в БД данные выписки
    protected function checkDoc() {
        $msg = null;

        do {
            if(empty($this->doc_date)) {
                $msg = 'Нет даты выписки';
                break;
            }
            if(empty($this->doc_num)) {
                $msg = 'Нет номера выписки';
                break;
            }
            if(empty($this->doc_payment_num)) {
                $msg = 'Нет номера оплаты в выписке';
                break;
            }

            $where = array(
                'sql' => 'doc_date=:DOCDATE AND doc_num=:DOCNUM AND doc_payment_num=:DOCPAYMENTNUM',
                'param' => array(
                    'DOCDATE' => $this->doc_date,
                    'DOCNUM' => $this->doc_num,
                    'DOCPAYMENTNUM' => $this->doc_payment_num
                )
            );
            $list = $this->loadAll($where);
            $msg = count($list) == 0 ? null : 'Оплата уже добавлена в систему';

        } while(false);

        return $msg;
    }
}
?>