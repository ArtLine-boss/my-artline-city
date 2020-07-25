<?php
class factorys_FactoryEquipment {
    public static function bind($equipmentId) {
        $result = null;

        do {
            if(empty($equipmentId)) {
                break;
            }
            $equipment = new classes_equipment();
            if(null !== ($msg = $equipment->loadById($equipmentId))) {
                break;
            }
            if(empty($equipment->methodCalcPriceOperation)) {
                break;
            }
            if(!class_exists($equipment->methodCalcPriceOperation)) {
                break;
            }
            $result = new $equipment->methodCalcPriceOperation();
        } while(false);

        return $result;
    }
}
?>