<?php
class classes_equipmentOperation extends core_DBObject {
    public $EQUIPMENT_ID = null;
    public $OPERATION_TYPE = null;

    public function __construct()
    {
        parent::__construct('equipment_operation', 'ID', 'classes_equipmentOperation');
    }
}
?>