<?php
	class classes_orderProduct extends core_DBObject {
		var $ORDER_ID = null;
		var $PRODUCT_ID = null;
		var $TOTAL = null;
		var $PRICE = null;
		var $SUMM = null;
		var $TEMPLATE = null;
		var $TEMP_PR = null;
		var $SIZE = null;
		var $DIZ = null;
		var $SHABLON_CL = null;
		var $p_names = null;
		var $PR = null;
		var $press_diz = null;
		var $print_diz = null;
		var $sum_press = null;
		var $view_diz = null;
		var $view_press = null;
		var $flags = null;
		var $cshivka = null;
		var $cl_file = null;
		var $units = null;
		var $dates_rdy = null;
		var $fast = null;
		var $status = null;
		var $num_prod_ord = null;
		var $comment = null;
		var $price_sys = null;
		var $add_date = null;
		var $code_stat = null;
		
		public function __construct() {
			parent::__construct('order_product', 'ID', 'classes_orderProduct');
		}

		public function getLastNumProdOrd() {
		    $msg = null;

		    do {
                if(empty($this->ORDER_ID)) {
                    $msg = 'Не задан номер заказа';
                    break;
                }

                $list = $this->select('SELECT IFNULL(MAX(order_product.num_prod_ord) + 1, 1) as npo FROM order_product WHERE order_product.ORDER_ID=' . intval($this->ORDER_ID));
                $this->num_prod_ord = intval($list[0]['npo']);

            } while(false);

		    return $msg;
        }
	}
?>