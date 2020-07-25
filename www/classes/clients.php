<?php
    class classes_clients extends core_DBObject {
        var $CLIENT_NAME = null;
        var $EMAIL = null;
        var $CLIENT_STATUS = null;
        var $PHONE_MOB = null;
        var $PHONE_CITY = null;
        var $CLIENT_SKYPE = null;
        var $CLIENT_VIBER = null;
        var $ADDRESS_POST = null;
        var $ADDRESS_DEV = null;
        var $UNP = null;
        var $ACCT = null;
        var $BANK = null;
        var $CODE_BANK = null;
        var $NADBAVKA = null;
        var $LIMITs = null;
        var $TIME_RAS = null;
        var $SIZE_PRE = null;
        var $temp = null;
        var $num_doc = null;
        var $fio_dir = null;
        var $osn = null;
        var $fio_dir1 = null;
        var $mfo = null;
        var $dover = null;
        var $dev_post_index = null;
        var $dev_post_kv = null;
        var $dev_post_kor = null;
        var $dev_post_street = null;
        var $dev_post_city = null;
        var $dev_region_id = null;
        var $post_post_index = null;
        var $post_post_kv = null;
        var $post_post_kor = null;
        var $post_post_street = null;
        var $post_post_city = null;
        var $post_region_id = null;
        var $post_house_num = null;
        var $dev_house_num = null;
        var $dev_raion = null;
        var $post_raion = null;
        var $num_doc_m = null;

        public function __construct()
        {
            parent::__construct('clients', 'ID', 'classes_clients');
        }

        //поиск по УНП
        public function searchByUNP($unp = null) {
            $msg = null;

            do {
                if(empty($unp)) {
                    $msg = 'Пустой запрос';
                    break;
                }

                $msg = $this->loadByUnique('UNP', $unp);

            } while(false);

            return $msg;
        }

        //поиск по имени клиента
        public function searchByClientName($name = null) {
            $msg = null;

            do {
                if(empty($name)) {
                    $msg = 'Пустой запрос';
                    break;
                }

                $msg = $this->loadByUnique('CLIENT_NAME', $name);

            } while(false);

            return $msg;
        }
    }
?>
