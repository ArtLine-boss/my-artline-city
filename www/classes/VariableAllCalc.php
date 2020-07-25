<?php
class classes_VariableAllCalc extends core_Object {
    var $count_product = 0; //количество изделий
    var $cost_binding = 0; //стоимость переплета
    var $adv_summ_order = 0; //общая сумма заказа предварительная
    var $cost_design = 0; //стоимость дизайна
    var $design_id = null;
    var $cost_prepress = 0; //стоимость препресса
    var $urgency = 0; //срочность
    var $all_summ_order = 0; //общая сумма
    var $all_summ_order_nds_firma = 0; //сумма с надбавкой фирмы
    var $all_summ_order_nds_firma_byn = 0; //сумма с надбавкой фирмы в BYN
    var $all_summ_order_nds_byn = 0; //сумма с НДС
    var $all_summ_order_byn = 0; //общая сумма BYN
    var $summ_one_product = 0; //сумма за один продукт в BYN
    var $all_summ_order_byn_calc = 0; //общая сумма BYN пересчитанная
    var $total = 0; //количество изделий после алгоритма округления
    var $factor = 1; //коэффициент (шт или тыс шт)
    var $p_per_i = 0; //переплет
    var $id_client = null; //клиент
    var $unit_prod1 = 'шт.'; //единицы измерения для заказа
    var $p_sum_all_hand = 0; //сумма, которую пользователь ввел вручную
    var $total_hand = 0; //количество
    var $factor_hand = 1; //фактор по единице измерения
    var $summ_one_product_hand = 0; // сумма за один продукт
    var $all_summ_order_nds_hand = 0; //полученная сумма
    var $all_summ_order_calc_hand = 0; // сумма до 2х знаков

    public function __construct($param = null)
    {
        if(empty($param))
            return;
        if(property_exists($param, 'p_cir') && !empty($param->p_cir)) {
            $this->factor = 1;
            if(property_exists($param, 'unit_prod1') && !empty($param->unit_prod1) && $param->unit_prod1 == 'тыс.шт.') {
                $this->factor = 1000;
            }
            $this->count_product = floatval($param->p_cir) * $this->factor;
        } else
            $this->count_product = 0;
        $this->total = $this->count_product;

        if(property_exists($param, 'ViewDesignCheck')) {
            $this->cost_design = (property_exists($param, 'p_prdiz_') && !empty($param->p_prdiz_)) ? floatval($param->p_prdiz_) : 0;
            $this->design_id = (property_exists($param, 'p_prdiz_id') && !empty($param->p_prdiz_id)) ? floatval($param->p_prdiz_id) : null;
        }

        if(property_exists($param, 'ViewPressCheck')) {
            $this->cost_prepress = (property_exists($param, 'p_press_') && !empty($param->p_press_)) ? floatval($param->p_press_) : 0;
            $this->p_per_i = (property_exists($param, 'p_per_i') && !empty($param->p_per_i)) ? intval($param->p_per_i) : 0;
        }

        $this->urgency = (property_exists($param, 'p_fast') && !empty($param->p_fast)) ? floatval($param->p_fast) : 0;
        $this->p_sum_all_hand = (property_exists($param, 'p_sum_all_hand') && !empty($param->p_sum_all_hand)) ? floatval($param->p_sum_all_hand) : 0;
        $this->id_client = (!empty($param->id_client)) ? intval($param->id_client) : null;
    }

    public function calc($hash = null) {
        do {
            if(empty($hash))
                break;

            $this->calcPer();

            foreach ($hash as $k => $v) {
                $this->adv_summ_order += $v->ResultCalc->all_summa;
                $this->all_summ_order += $v->ResultCalc->all_summa;
            }

            //рассчитываем полную стоимость
            $this->all_summ_order = ($this->all_summ_order + $this->cost_binding + $this->cost_design + $this->cost_prepress) * $this->urgency;

            //надбавка фирмы
            $settings = new classes_settings();
            $nds_firma = 0;
            if(null === ($msg = $settings->loadById(11)))
                $nds_firma = empty($settings->VAL) ? 0 :  floatval($settings->VAL);
            $nds_firma = (100 + $nds_firma) / 100;
            $this->all_summ_order_nds_firma = $this->all_summ_order * $nds_firma;
            $settings = new classes_settings();
            $dollar = 0;
            if(null === ($msg = $settings->loadById(2)))
                $dollar = empty($settings->VAL) ? 0 :  floatval(str_replace(',', '.', $settings->VAL));
            $this->all_summ_order_nds_firma_byn = $this->all_summ_order_nds_firma * $dollar;

            //округляем по алгоритму
            $round = $this->AlgoritmRound($this->all_summ_order_nds_firma_byn, $this->count_product);
            if($round['factor'] == 1000 && $round['summ_one']) {
                $this->unit_prod1 = "тыс.шт.";
                $this->total = $round['total'];
                $this->factor = $round['factor'];
            }
            //цена за штуку
            $this->summ_one_product = $round['summ_one'];
            //сумма с НДС
            $settings = new classes_settings();
            $nds = 0;
            if(null === ($msg = $settings->loadById(4)))
                $nds = empty($settings->VAL) ? 0 :  floatval($settings->VAL);
            $nds = (100 + $nds) / 100;
            $this->all_summ_order_nds_byn = $round['summ'] * $nds;
            //общая пересчитанная сумма
            $this->all_summ_order_byn_calc = ceil($this->all_summ_order_nds_byn * 100) / 100;
            //общая сумма
            $this->all_summ_order_byn = $this->all_summ_order_nds_firma_byn * $nds;

            $this->calcHandSum();

        } while(false);
    }

    //расчет переплета
    protected function calcPer() {
        do {
            if(empty($this->id_client) || empty($this->p_per_i))
                break;
            $per = new classes_directoryper();
            if(null !== ($msg = $per->loadByUnique('value', $this->p_per_i)))
                break;
            $client = new classes_clients();
            $client->loadById($this->id_client);
            switch ($client->NADBAVKA) {
                case 2:
                    $this->cost_binding = $this->count_product * $per->nadb_2;
                    break;
                case 3:
                    $this->cost_binding = $this->count_product * $per->nadb_3;
                    break;
                case 5:
                    $this->cost_binding = $this->count_product * $per->nadb_5;
                    break;
                default:
                    $this->cost_binding = $this->count_product * $per->nadb_default;
                    break;
            }
        } while(false);
    }

    //расчет для суммы, введенной вручную
    public function calcHandSum() {
        do {
            if(empty($this->p_sum_all_hand))
                break;
            $settings = new classes_settings();
            $nds = 0;
            if(null === ($msg = $settings->loadById(4)))
                $nds = empty($settings->VAL) ? 0 :  floatval($settings->VAL);
            $nds = (100 + $nds) / 100;
            $val = $this->p_sum_all_hand / $nds;
            $r_val = $this->AlgoritmRound($val, $this->count_product);
            if($r_val['factor'] == 1000 && $r_val['summ_one']) {
                $this->unit_prod1 = "тыс.шт.";
            }
            $this->total_hand = $r_val['total'];
            $this->factor_hand = $r_val['factor'];
            //цена за штуку
            $this->summ_one_product_hand = $r_val['summ_one'];
            //сумма с НДС
            $this->all_summ_order_nds_hand = $r_val['summ'] * $nds;
            //общая пересчитанная сумма
            $this->all_summ_order_calc_hand = ceil($this->all_summ_order_nds_hand * 100) / 100;
            //переписываем введенную сумму
            $k_summ_hand = $this->p_sum_all_hand / $this->all_summ_order_calc_hand;
            $this->p_sum_all_hand = ($k_summ_hand > 0.99 && $k_summ_hand < 1.01) ? $this->p_sum_all_hand : $this->all_summ_order_calc_hand;
        } while(false);
    }

    //алгоритм определения как считать округление
    protected function AlgoritmRound($summ, $total) {
        if($total > 0) {
            $r_sum = ceil(($summ / $total) * 100) / 100;
            $r_sum_no = $summ / $total;
        } else {
            $return_ = array(
                'factor' => 1,
                'summ_one' => 0,
                'total' => $total,
                'summ' => $summ,
            );
            return $return_;
        }
        $return_ = array();
        if($r_sum > 0 && (1 - $r_sum_no / $r_sum) < 0.02 && $r_sum_no >= 0.01) {
            $return_['factor'] = 1;
            $return_['summ_one'] = $r_sum;
            $return_['summ'] = round(100 * $r_sum * $total) / 100;
            $return_['total'] = $total;
        }
        else {
            $return_['factor'] = 1000;
            $return_['summ_one'] = ceil(($summ * 1000 / $total) * 100) / 100;
            $return_['total'] = $total / 1000;
            $return_['summ'] = round(100 * $return_['summ_one'] * $return_['total']) / 100;
        }

        return $return_;
    }
}
?>