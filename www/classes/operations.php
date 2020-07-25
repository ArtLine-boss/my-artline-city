<?php
    class classes_operations extends core_DBObject {
        public $OPERATION_NAME = null;
        public $PAR = null;
        public $UNITS = null;
        public $UNIT_MIN = null;
        public $MAKEREADY_TIME = null;
        public $MAKEREADY_PRICE = null;
        public $OPERATION_PRICE = null;
        public $comments = null;
        public $OperationType = null;

        public function __construct()
        {
            parent::__construct('operations', 'ID', 'classes_operations');
        }

        public static function getOperationsByEquipment($eq, $type_oper = null) {
            $result = array();

            do {
                if(empty($eq)) {
                    break;
                }
                $equipment = new classes_equipment();
                if(null !== ($msg = $equipment->loadById($eq))) {
                    break;
                }
                $operation = new classes_operations();
                $str_type_oper = !empty($type_oper) ? " AND OperationType=" . $type_oper : "";
                $sql = !empty($equipment->oper) ? $operation->getKey() . " IN (" . $equipment->oper . ")" . $str_type_oper : null;
                if(!empty($sql)) {
                    $where = array('sql' => $sql, 'param' => null);
                    $result = $operation->loadAll($where);
                }
            } while(false);

            return $result;
        }
    }
?>