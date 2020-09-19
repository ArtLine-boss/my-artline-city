<?php
class entity_mail extends core_DBObject {
    public $id_ord = null; //номер заказа
    public $fio = null; //ФИО
    public $obl = null; //Область
    public $city = null; //Город
    public $house_num = null; //номер дома
    public $room = null; //квартира
    public $korp = null; //корпус
    public $phone = null; //телефон
    public $index_ = null; //индекс
    public $price = null; //цена
    public $street = null; //улица
    public $view_ = null; //Вид оплаты
    public $id_cl = null; //ид клиента
    public $user_login = null; //
    public $date_otpr = null; //дата отправки
    public $goods = null; //товары которые отправили
    public $track_cod = null; //трек код
    public $parm = null; //
    public $date = null; //
    public $status = null; //
    public $raion = null; //
    public $mail = null; //

    public function __construct()
    {
        parent::__construct('mail', 'ID', 'entity_mail');
    }

    /**
     * Метод проверки трек-кода
     */
    public function checkTrack() {
        $msg = null;

        do {
            if(empty($this->track_cod) || (!empty($this->date) && !empty($this->status))) {
                $msg = 'Не задан трек-код';
                break;
            }

            if(null !== ($msg = $this->getStatusTrack())) {
                break;
            }

        } while(false);

        return $msg;
    }

    /**
     * Возвращает список недоставленных кодов
     * @return array
     */
    public static function getActiveTrackCode() {
        $result = [];

        do {
            $object = new entity_mail();
            $whr = [
                'sql' => "`mail`.`track_cod` <> '' AND `mail`.`date` IS NULL AND `mail`.`date_otpr` IS NOT NULL AND DATE_FORMAT(`mail`.`date_otpr`,'%Y') = DATE_FORMAT(NOW(), '%Y')",
            ];
            $result = $object->loadAll($whr);
        } while(false);

        return $result;
    }

    /**
     * Запрос статуса доставки
     * @param $track - код трека
     * @return |null
     */
    public function getStatusTrack() {
        $msg = null;

        do {
            if(empty($this->track_cod)) {
                $msg = 'Не задан трек код';
                break;
            }

            // для ssl соединения
            $arrContextOptions = [
                "ssl" => [
                    "verify_peer" => false,
                    "verify_peer_name" => false,
                ],
            ];

            // получает страницу со статусом
            $content = file_get_contents(CONFIG::BELPOST . $this->track_cod, false, stream_context_create($arrContextOptions));

            if(empty($content)) {
                $msg = 'Не удалось получить таблицу';
                break;
            }

            // таблица статусов
            $pregstr = '#<table[^>]+?id\s*?=\s*?(["\'])GridInfo.*?\1[^>]*?>(.+?)</table>#su';
            preg_match_all($pregstr, $content, $match);
            if(!isset($match[2][0]) || empty($match[2][0])) {
                break;
            }
            // все статусы
            $pregstr = '#<font face="Times New Roman" color="Black" size="4">(.+?)</font>#su';
            preg_match_all($pregstr, $match[2][0], $match);

            if(!isset($match[1]) || count($match[1]) < 6) {
                $msg = 'Ошибка парсинга страницы';
                break;
            }

            // определяем последний статус
            if(!isset($match[1]) || empty($match[1])) {
                $msg = 'Ошибка парсинга таблицы';
                break;
            }
            $this->status = $match[1][count($match[1]) - 2] . '(' . $match[1][count($match[1]) - 1] . ')';
            if(strpos($match[1][count($match[1]) - 2], 'Вручено') !== false) {
                $this->date = API::FormatDate($match[1][count($match[1]) - 3], CONSTANTS::DB_DATE_FORMAT);
            }

            // обновляем запись
            if(null !== ($msg = $this->store())) {
                break;
            }
        } while(false);

        return $msg;
    }

    public static function updateTracks() {
        $msg = null;

        do {
            $listTrack = self::getActiveTrackCode();
            $msg = 'Количество обрабатываемых записей - ' . count($listTrack) . '.' . PHP_EOL;
            $trueTrack = 0;
            foreach ($listTrack as $key => $track) {
                if(null !== ($tmsg = $track->checkTrack())) {
                    $msg .= $track->track_cod . '. ' . $tmsg . '.' . PHP_EOL;
                } else {
                    $trueTrack++;
                }
            }
            $msg .= 'Количество успешных записей - ' . $trueTrack . '.';

        } while(false);

        return $msg;
    }
}
?>