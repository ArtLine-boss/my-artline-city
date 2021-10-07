<?php
class classes_PrestaShopWebservice2 extends classes_PrestaShopWebservice {
    public $order_id = null;
    public $reference = null;
    public $payment_method = null;
    public $carriers = null;
    public $message = null;

    public function __construct()
    {
        $url = 'https://artliner.by/api/prestashop-webservice/';
        $key = '8XWV2Q4VTVS5ZAVH43DNRNJG8528WPGQ';
        $debug = false;
        parent::__construct($url, $key, $debug);
    }

    /**
     * Retrieve (GET) a resource
     * <p>Unique parameter must take : <br><br>
     * 'url' => Full URL for a GET request of Webservice (ex: http://mystore.com/api/customers/1/)<br>
     * OR<br>
     * 'resource' => Resource name,<br>
     * 'id' => ID of a resource you want to get<br><br>
     * </p>
     * <code>
     * <?php
     * require_once('./PrestaShopWebservice.php');
     * try
     * {
     * $ws = new PrestaShopWebservice('http://mystore.com/', 'ZQ88PRJX5VWQHCWE4EE7SQ7HPNX00RAJ', false);
     * $xml = $ws->get(array('resource' => 'orders', 'id' => 1));
     *	// Here in $xml, a SimpleXMLElement object you can parse
     * foreach ($xml->children()->children() as $attName => $attValue)
     * 	echo $attName.' = '.$attValue.'<br />';
     * }
     * catch (PrestaShopWebserviceException $ex)
     * {
     * 	echo 'Error : '.$ex->getMessage();
     * }
     * ?>
     * </code>
     * @param array $options Array representing resource to get.
     * @return SimpleXMLElement status_code, response
     */
    public function get($options)
    {
        if (isset($options['url']))
            $url = $options['url'];
        elseif (isset($options['resource']))
        {
            //$url = $this->url.'/api/'.$options['resource'];
            $url = $this->url.$options['resource'];
            $url_params = array();
            if (isset($options['id']))
                $url .= '/'.$options['id'];

            $params = array('filter', 'display', 'sort', 'limit', 'id_shop', 'id_group_shop');
            foreach ($params as $p)
                foreach ($options as $k => $o)
                    if (strpos($k, $p) !== false)
                        $url_params[$k] = $options[$k];
            if (count($url_params) > 0)
                $url .= '?'.http_build_query($url_params);
        }
        else
            throw new PrestaShopWebserviceException('Bad parameters given');

        $request = self::executeRequest($url, array(
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_RETURNTRANSFER => true
        ));

        self::checkStatusCode($request['status_code']);// check the response validity
        //return self::parseXML($request['response']);
        return json_decode($request['response']);
    }

    /*
     * Строим класс по массиву
     */
    public function bind($hash = null) {
        $msg = null;

        do {
            if(empty($hash)) {
                $msg = 'Входная переменная пустая';
                break;
            }
            if(!is_array($hash)) {
                $msg = 'Входная переменная не является массивом';
                break;
            }

            if(!array_key_exists('order_id', $hash)) {
                $msg = 'Не найден ключ в массиве: order_id';
                break;
            }
            $this->order_id = $hash['order_id'];
            if(!array_key_exists('reference', $hash)) {
                $msg = 'Не найден ключ в массиве: reference';
                break;
            }
            $this->reference = $hash['reference'];

            if(null !== ($msg = $this->getCarriersOrder())) {
                break;
            }
            if(null !== ($msg = $this->getCustomerMessage())) {
                break;
            }
            if(null !== ($msg = $this->getPaymentMethodOrder())) {
                break;
            }

        } while(false);

        return $msg;
    }

    /*
     * Поиск способа доставки
     */

    protected function getCarriersOrder() {
        $msg = null;

        try {
            do {
                if (empty($this->order_id)) {
                    $msg = 'Не инициализирована переменная order_id';
                    break;
                }

                $query = array(
                    'resource' => 'order_carriers',
                    'filter[id_order]' => "[$this->order_id]",
                    'display' => 'full',
                );
                //запрос на связь
                $order_carriers = $this->get($query);
                $id_carriers = $order_carriers->order_carriers[0]->id_carrier;
                //запрос на способ доставки
                $query = array(
                    'resource' => 'carriers',
                    'filter[id]' => "[$id_carriers]",
                    'display' => 'full',
                );
                $carriers = $this->get($query);
                $this->carriers = $carriers->carriers[0]->name == 'Artliner.by' ? 'Офис' : $carriers->carriers[0]->name;
            } while (false);
        } catch (PrestaShopWebserviceException $ex) {
            $msg = 'Файл ' . $ex->getFile() . '. Строка ' . $ex->getLine() . '. Код ' . $ex->getCode() . '. Сообщение: ' . $ex->getMessage();
        }

        return $msg;
    }

    /*
     * Поиск сообщений для заказа
     * $reference - код заказа
     */
    protected function getCustomerMessage() {
        $msg = null;

        try {
            do {
                if (empty($this->reference)) {
                    $msg = 'Не инициализирована переменная reference';
                    break;
                }
                //получаем ид заказа
                $query = array(
                    'resource' => 'orders',
                    'filter[reference]' => $this->reference,
                );
                $id_order = $this->get($query);
                $id_order = $id_order->orders[0]->id;
                if (empty($id_order)) {
                    $msg = 'Заказ не найден';
                    break;
                }
                //получаем связь заказ - сообщение
                $query = array(
                    'resource' => 'customer_threads',
                    'display' => 'full',
                    'filter[id_order]' => "[$id_order]",
                );
                $id_customer_threads = $this->get($query);
                $id_customer_threads = $id_customer_threads->customer_threads[0]->id;
                if (empty($id_customer_threads)) {
                    //$msg = 'Связь заказ-сообщение не найдена';
                    break;
                }
                //получаем сообщение
                $query = array(
                    'resource' => 'customer_messages',
                    'display' => '[message]',
                    'filter[id_customer_thread]' => "[$id_customer_threads]",
                );
                $customer_messages = $this->get($query);
                $customer_messages = $customer_messages->customer_messages;
                $this->message = "";
                if(!empty($customer_messages) && is_array($customer_messages) && count($customer_messages) > 0) {
                    for($i = 1; $i <= count($customer_messages); $i++) {
                        $this->message .= $i . ". " . $customer_messages[$i - 1]->message . "\r\n";
                    }
                }

            } while (false);
        } catch (PrestaShopWebserviceException $ex) {
            $msg = 'Файл ' . $ex->getFile() . '. Строка ' . $ex->getLine() . '. Код ' . $ex->getCode() . '. Сообщение: ' . $ex->getMessage();
        }

        return $msg;
    }

    /*
     * Поиск способа оплаты
     */
    protected function getPaymentMethodOrder() {
        $msg = null;

        try {
            do {
                if (empty($this->order_id)) {
                    $msg = 'Не инициализирована переменная order_id';
                    break;
                }
                $query = array(
                    'resource' => 'order_payments',
                    'filter[id_order]' => "[$this->order_id]",
                    'display' => 'full',
                );
                $order_payments = $this->get($query);
                $this->payment_method = $order_payments->order_payments[0]->payment_method == "BeGateway" ? 'ЕРИП' : $order_payments->order_payments[0]->payment_method;
            } while(false);
        } catch (PrestaShopWebserviceException $ex) {
            $msg = 'Файл ' . $ex->getFile() . '. Строка ' . $ex->getLine() . '. Код ' . $ex->getCode() . '. Сообщение: ' . $ex->getMessage();
        }

        return $msg;
    }
}
?>