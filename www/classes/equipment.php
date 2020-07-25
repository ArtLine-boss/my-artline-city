<?php
    class classes_equipment extends core_DBObject {
        public $EQ_NAME = null;
        public $MAX_SIZE = null;
        public $MIN_SIZE = null;
        public $FORMAT = null;
        public $EQ_UNIT = null;
        public $EQ_KOL_OPER = null;
        public $MAKEREADY_TIME = null;
        public $EQ_AMMOR = null;
        public $SROK_AMMOR = null;
        public $EQ_PRICE = null;
        public $EQ_SQ = null;
        public $PRICE_ARENDA = null;
        public $PRICE = null;
        public $mater = null;
        public $oper = null;
        public $l_use = null;
        public $l_offset = null;
        public $ladnr = null;
        public $uandd = null;
        public $nadb_max = null;
        public $nadb_min = null;
        public $total_max = null;
        public $total_min = null;
        public $methodCalcPriceOperation = null;

        public function __construct()
        {
            parent::__construct('equipment', 'ID', 'classes_equipment');
        }

        public static function getEqupmentsByOperations($value) {
            $result = array();

            do {
                if(empty($value) || !is_array($value) || count($value) == 0) {
                    break;
                }
                foreach ($value as $k => $v) {
                    $sql = "SELECT * FROM equipment WHERE CONCAT(',',equipment.oper,',') LIKE '%," . intval($v) . ",%'";
                    $eq = new classes_equipment();
                    $list = $eq->selectByQuery($sql);
                    $result = array_merge($result, $list);
                }
            } while(false);

            return $result;
        }
    }
?>
