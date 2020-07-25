<?php
class factorys_EquipmentXeroxIridess extends factorys_AFactoryEquipment {
    const EQUIPMENT_XEROX_IRIDESS = 54;

    public function getOperationPrice($hash)
    {
        // TODO: Implement getOperationPrice() method.
        $result = 0;
        do {
            $equipment = new classes_equipment();
            if(null !== ($msg = $equipment->loadById(self::EQUIPMENT_XEROX_IRIDESS))) {
                break;
            }
            $color = new classes_operations();
            $clr = isset($hash['clr']) && !empty($hash['clr']) ? $hash['clr'] : $hash['p_color'];
            if(null !== ($msg = $color->loadById($clr))) {
                $result = 0;
            } else {
                $result = $color->OPERATION_PRICE;
            }
            if(isset($hash['combi_f']) && is_array($hash['combi_f'])) {
                foreach ($hash['combi_f'] as $k => $v) {
                    $d = explode('_', $v);
                    $id_oper = intval($d[0]);
                    $pr = floatval($d[1]);
                    $cmb = new classes_operations();
                    if(null !== ($msg = $cmb->loadById($id_oper))) {
                        continue;
                    }
                    $result += $cmb->OPERATION_PRICE * $pr / 100;
                }
            }
            if(isset($hash['combi_b']) && is_array($hash['combi_b'])) {
                foreach ($hash['combi_b'] as $k => $v) {
                    $d = explode('_', $v);
                    $id_oper = intval($d[0]);
                    $pr = floatval($d[1]);
                    $cmb = new classes_operations();
                    if(null !== ($msg = $cmb->loadById($id_oper))) {
                        continue;
                    }
                    $result += $cmb->OPERATION_PRICE * $pr / 100;
                }
            }
        } while(false);

        return $result;

    }
}
?>