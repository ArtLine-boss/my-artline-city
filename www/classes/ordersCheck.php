<?php
	class classes_ordersCheck extends core_DBObject {
		var $ID = null;
		var $user_id = null;
		var $number_check = null;
		var $products = null;
		var $summaAll = null;
		
		public function __construct()
        {
            parent::__construct('orders_check', 'ID', 'classes_ordersCheck');
        }
	}
?>