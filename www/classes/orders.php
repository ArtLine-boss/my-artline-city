<?php
	class classes_orders extends core_DBObject {
		var $DATE_OR = null;
		var $USER_ID = null;
		var $STATUS_ID = null;
		var $CLIENT_ID = null;
		var $CUR_ID = null;
		var $CALC = null;
		var $parent_company = null;
		
		public function __construct() {
			parent::__construct('orders', 'NUMBER', 'classes_orders');
		}

		public function getItems() {
		    $list = factorys_classes::getObject('classes_orderItem')->loadAll(['sql' => 'order_id = ' . $this->getId(), 'param' => null]);
		    foreach ($list as $k => $v) {
		       $name = factorys_classes::getObject('core_Entity')
                    ->select("SELECT `directory_status_item`.`name` FROM `directory_status_item` WHERE `directory_status_item`.`ID` = '" . $v->status_item . "'")[0]['name'];
		       $icon = factorys_classes::getObject('core_Entity')
                   ->select("SELECT `directory_status_item`.`icon` FROM `directory_status_item` WHERE `directory_status_item`.`ID` = '" . $v->status_item . "'")[0]['icon'];
		       $v->status_item = '<span class="' . $icon . '">' . $name . '</span>';

               $v->add_date = API::FormatDate($v->add_date, CONSTANTS::REPORT_DATETIME_FORMAT);
               $v->dates_rdy = API::FormatDate($v->dates_rdy, CONSTANTS::REPORT_DATETIME_FORMAT);
            }
		    return $list;
        }
	}
?>