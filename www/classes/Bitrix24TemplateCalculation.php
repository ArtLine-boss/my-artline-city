<?php
    class classes_Bitrix24TemplateCalculation extends core_DBObject {
        //клиент по bitrix24
        var $user_id = null;
        //клиент внутренней системы
        var $user_login = null;
        //дата добавления/изменения
        var $date_add = null;
        //наименование расчета
        var $name = null;
        //ид клиента по Битрикс24
        var $client_id = null;
        //ид комапании по Битрикс24
        var $company_id = null;
        //ид клиента по внутренней системе... таблица client
        var $client_id_db = null;
        //json полей
        var $data = null;
        //json расчета
        var $data_calc = null;
        //количество изделий
        var $total = null;
        //Множитель... может быть 1 или 1000
        var $factor = null;
        //сумма расчета
        var $summ= null;
        //сумма за одно изделие без НДС
        var $summ_one = null;
        //перенесен в работу или нет
        var $in_work = null;
        //номер счета
        var $order_id = null;
        //ид для заказов с artliner.by
        var $artliner_id = null;
        //путь к файлам заказа
        var $file_path = null;

        public function __construct()
        {
            parent::__construct('bitrix24_template_calculation', 'id', 'classes_Bitrix24TemplateCalculation');
        }

        public function reCalc($client = null, $free_calc = false) {
            $msg = null;

            do {
                if(empty($this->getId()) && !$free_calc) {
                    $msg = 'Шаблон не инициализирован';
                    break;
                }
                if(empty($this->data)) {
                    $msg = 'Нет данных полей. Удалите текущий шаблон и создайте новый';
                    break;
                }

                $form = json_decode($this->data);
                $form->id_client = (!empty($client)) ? $client : $form->id_client;
                $list = array();
                $kol = intval($form->kol);
                if(empty($kol) || $kol <= 0) {
                    $msg = 'Нет ни одной расчетной части';
                    break;
                }

                //рассчитываем части
                for($i = 1; $i <= $kol; $i++) {
                    $result = new classes_VariableCalc($form, $i);
                    $result->calcElement();
                    $list[] = $result;
                }
                $variableAll = new classes_VariableAllCalc($form);
                $variableAll->calc($list);

                $answer = array(
                    'data' => $list,
                    'result' => $variableAll
                );

                $bitrixArray = array(
                    'data' => json_encode($form),
                    'data_calc' => json_encode($answer),
                    'total' => $variableAll->total,
                    'factor' => $variableAll->factor,
                    'summ'=> $variableAll->all_summ_order_byn_calc,
                    'summ_one' => $variableAll->summ_one_product
                );
                $msg = $this->bind($bitrixArray);

            } while(false);

            return $msg;
        }
    }
?>
